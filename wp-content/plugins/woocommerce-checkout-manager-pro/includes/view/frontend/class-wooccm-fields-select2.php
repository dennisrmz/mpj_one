<?php

class WOOCCM_PRO_Fields_Select2
{

  protected static $_instance;

  public function __construct()
  {
    // Add field attributes
    add_filter('wooccm_checkout_field_filter', array($this, 'add_field_attributes'));
    add_filter('wooccm_checkout_field_filter', array($this, 'add_field_class'));
  }

  public static function instance()
  {
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  public function add_field_attributes($field)
  {

    switch ($field['type']) {
      case 'select':
      case 'multiselect':
        if (!empty($field['select2'])) {
          $field['custom_attributes']['data-placeholder'] = $field['placeholder'] ? $field['placeholder'] : __('Choose an option', 'woocommerce-checkout-manager-pro');
          $field['custom_attributes']['data-allowclear'] = $field['select2_allowclear'];
          $field['custom_attributes']['data-selectonclose'] = $field['select2_selectonclose'];
          $field['custom_attributes']['data-closeonselect'] = $field['select2_closeonselect'];
          $field['custom_attributes']['data-search'] = $field['select2_search'];
          $field['placeholder'] = false;
        }
        break;
    }

    return $field;
  }

  public function add_field_class($field)
  {

    switch ($field['type']) {
      case 'select':
      case 'multiselect':
        if (!empty($field['select2'])) {
          $field['input_class'][] = 'wooccm-enhanced-select';
        }
        break;
    }

    return $field;
  }
}

WOOCCM_PRO_Fields_Select2::instance();
