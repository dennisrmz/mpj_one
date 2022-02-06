<?php

class WOOCCM_PRO_Fields_Display
{

  protected static $_instance;

  public function __construct()
  {
    add_filter('wooccm_checkout_field_filter', array($this, 'disable_account'));
    add_filter('wooccm_checkout_field_filter', array($this, 'disable_checkout'));
    add_filter('wooccm_checkout_field_filter', array($this, 'disable_by_cart_subtotal'));
  }

  public static function instance()
  {
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  function disable_by_cart_subtotal($field)
  {

    $minimum = (int) $field['show_cart_minimum'];
    $maximum = (int) $field['show_cart_maximun'];

    if ($minimum || $maximum) {

      $subtotal = WC()->cart->get_subtotal();
      if ($minimum && $maximum) {
        // between
        $field['disabled'] = !($minimum <= $subtotal && $subtotal <= $maximum);
      } elseif ($minimum) {
        // higher than
        $field['disabled'] = !($minimum <= $subtotal);
      } elseif ('' !== $maximum) {
        // lower than
        $field['disabled'] = !($subtotal <= $maximum);
      }
    }

    return $field;
  }

  function disable_account($field)
  {

    if (!empty($field['hide_account']) && is_account_page()) {
      $field['disabled'] = true;
    }

    return $field;
  }

  function disable_checkout($field)
  {

    if (!empty($field['hide_checkout']) && is_checkout()) {
      $field['disabled'] = true;
    }

    return $field;
  }
}

WOOCCM_PRO_Fields_Display::instance();
