<?php

class WOOCCM_PRO_Fields_Price
{

  protected static $_instance;

  public function __construct()
  {
    // Add checkout feeds
    add_action('woocommerce_cart_calculate_fees', array($this, 'add_cart_fees'));

    // Add price class
    add_filter('wooccm_checkout_field_filter', array($this, 'add_field_class'));
  }

  public static function instance()
  {
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  public function add_checkout_fees($post_data)
  {

    if (count($checkout = WC()->checkout->get_checkout_fields())) {

      foreach ($checkout as $field_type => $fields) {

        foreach ($fields as $key => $field) {

          // check if this cart is a recurring cart from Subscriptions
          // if so, check that the add-on is set to be renewable
          //if (is_subscriptions_active() && WC()->cart->start_date && !$field->is_renewable()) {
          //  continue;
          //}

          if (!empty($field['add_price_total'])) {

            $value = isset($post_data[$key]) ? $post_data[$key] : null;
            $value = null === $value && isset($_POST[$key]) ? $_POST[$key] : $value;

            //$value = $post_data[$key];

            if (in_array($field['type'], array('text', 'textarea'), true)) {
              $value = stripslashes($value);
            }

            if ($value !== null) {
              $this->add_field_fee($key, $field, $value);
            }
          }
        }
      }
    }
  }

  public function add_field_fee($key, $field = array(), $value = false)
  {

    $session_data = WC()->session->wooccm;

    $cost = 0;
    $taxable = false;
    $tax_class = '';
    $name = $field['label'];

    switch ($field['type']) {

      case 'select':
      case 'radio':

        if (is_array($field['options'])) {

          foreach ($field['options'] as $option_key => $option_text) {

            if (isset($field['add_price_total'][$option_key]) && ($value == $option_text)) {

              if (($cost = $field['add_price_total'][$option_key])) {

                $type = @$field['add_price_type'][$option_key];
                $taxable = @$field['add_price_tax'][$option_key];
                // Calculate the percentage if needed.
                if ('percent' === $type) {
                  $cost = ($cost / 100) * WC()->cart->cart_contents_total;
                }

                WC()->cart->add_fee($name, $cost, $taxable, $tax_class);
                $session_data['fees'][$key] = $cost;
              }
            }
          }
        }

        break;

      case 'multiselect':
      case 'multicheckbox':

        $has_value = false;

        if (is_array($field['options'])) {

          foreach ($field['options'] as $option_key => $option_text) {

            if (isset($field['add_price_total'][$option_key]) && in_array($option_text, (array) $value, false)) {

              if (($price = $field['add_price_total'][$option_key]) > 0) {

                $has_value = true;
                $type = @$field['add_price_type'][$option_key];
                $taxable = @$field['add_price_tax'][$option_key];
                // Calculate the percentage if needed.
                if ('percent' === $type) {
                  $price = ($price / 100) * WC()->cart->cart_contents_total;
                }

                $cost += (float) $price;
              }
            }
          }
        }

        if ($has_value) {
          WC()->cart->add_fee($name, $cost, $taxable, $tax_class);
          $session_data['fees'][$key] = $cost;
        }

        break;

      default:

        if ($field['add_price_name']) {
          $name = $field['add_price_name'];
        }
        $type = $field['add_price_type'];
        $taxable = $field['add_price_tax'];

        if ($value && ($cost = $field['add_price_total']) > 0) {

          if ('percent' === $type) {
            $cost = ($cost / 100) * WC()->cart->cart_contents_total;
          }

          WC()->cart->add_fee($name, $cost, $taxable, $tax_class);
          $session_data['fees'][$key] = $cost;
        }
        break;
    }

    WC()->session->wooccm = $session_data;
  }

  public function add_cart_fees($cart)
  {

    if (!$_POST || (is_admin() && !is_ajax())) {
      return;
    }

    if (isset($_POST['post_data'])) {
      parse_str($_POST['post_data'], $post_data);
    }

    if (empty($post_data)) {
      $post_data = [];
    }

    $this->add_checkout_fees($post_data);
  }

  public function add_field_class($field)
  {

    if (!empty($field['add_price_total']) && count(array_filter((array) $field['add_price_total']))) {
      $field['class'][] = 'wooccm-add-price';
    }

    return $field;
  }
}

WOOCCM_PRO_Fields_Price::instance();
