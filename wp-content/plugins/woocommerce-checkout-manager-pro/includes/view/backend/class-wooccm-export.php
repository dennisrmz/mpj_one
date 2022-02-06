<?php

class WOOCCM_Field_Export
{

  protected static $_instance;
  protected $csv_headers = array();

  public function __construct()
  {
    if (get_option('wooccm_export_custom_fields', 'yes') === 'yes') {
      add_filter('wc_customer_order_export_csv_order_row', array($this, 'modify_row_data'), 10, 3);
      add_filter('wc_customer_order_export_csv_order_headers', array($this, 'modify_column_headers'));
    }
  }

  public static function instance()
  {

    if (is_null(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  public function modify_column_headers($column_headers)
  {

    include_once(WOOCCM_PLUGIN_DIR . 'includes/view/frontend/class-wooccm-fields-register.php');

    if (count($checkout = WC()->checkout->get_checkout_fields())) {

      foreach ($checkout as $field_type => $fields) {
        foreach ($fields as $key => $field) {
          if (isset(WOOCCM()->$field_type) && !isset($column_headers[$key])) {
            $this->csv_headers[$key] = $field['label'];
          }
        }
      }
    }

    return array_merge($column_headers, $this->csv_headers);
  }

  public function modify_row_data($order_data, $order, $csv_generator)
  {
    if (count($this->csv_headers)) {
      foreach ($this->csv_headers as $key => $label) {
        if ($value = get_post_meta($order->get_id(), sprintf('_%s', $key), true)) {
          $order_data[$key] = $value;
        }
      }
    }
    return $order_data;
  }
}

WOOCCM_Field_Export::instance();
