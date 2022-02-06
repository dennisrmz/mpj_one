<?php

class WOOCCM_PRO_Fields_Datepicker
{

  protected static $_instance;

  public function __construct()
  {
    add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'), -10);
    // Add field attributes
    add_filter('wooccm_checkout_field_filter', array($this, 'add_field_attributes'));
    add_filter('wooccm_checkout_field_filter', array($this, 'add_field_class'));
    add_filter('woocommerce_form_field_date', array($this, 'datepicker_field'), 10, 4);
  }

  public static function instance()
  {
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  public function enqueue_scripts()
  {

    WOOCCM()->register_scripts();

    if (is_checkout()) {

      // UI
      // ---------------------------------------------------------------------
      wp_enqueue_style('jquery-ui-style');

      // Datepicker
      // ---------------------------------------------------------------------
      wp_enqueue_script('jquery-ui-datepicker');
    }
  }

  public function get_date_days($date)
  {

    $symbol = '-';

    $today = strtotime(date('l'));

    $days = (strtotime($date) - $today) / DAY_IN_SECONDS;

    if ($days > 0) {
      $symbol = '+';
    }

    return $symbol . absint($days);
  }

  public function add_field_attributes($field)
  {

    if ($field['type'] == 'date' && $field['select2']) {

      $field['custom_attributes']['readonly'] = 'readonly';
      $field['custom_attributes']['data-formatdate'] = $field['date_format'];

      if (@$field['date_limit'] == 'variable') {

        //        if (!empty($field['date_limit_variable_min'])) {
        $field['custom_attributes']['data-mindate'] = (int) @$field['date_limit_variable_min'];
        //      }
        //        if (!empty($field['date_limit_variable_max'])) {
        $field['custom_attributes']['data-maxdate'] = (int) @$field['date_limit_variable_max'];
        //        }
      } else {
        if (!empty($field['date_limit_fixed_min'])) {
          $field['custom_attributes']['data-mindate'] = $this->get_date_days($field['date_limit_fixed_min']);
        }
        if (!empty($field['date_limit_fixed_max'])) {
          $field['custom_attributes']['data-maxdate'] = $this->get_date_days($field['date_limit_fixed_max']);
        }
      }

      if (!empty($field['date_limit_days']) && is_array($field['date_limit_days'])) {
        $field['custom_attributes']['data-disable'] = htmlspecialchars(json_encode($field['date_limit_days']), ENT_QUOTES, 'UTF-8');
      }
    }
    return $field;
  }

  public function add_field_class($field)
  {
    if ($field['type'] == 'date' && $field['select2']) {
      $field['input_class'][] = 'wooccm-enhanced-datepicker';
    }
    return $field;
  }

  // Datepicker
  // ---------------------------------------------------------------------------
  public function datepicker_field($field = '', $key, $args, $value)
  {
    if ($args['select2']) {

      $args['type'] = 'text';

      ob_start();

      if (!is_string($value)) {
        $value = '';
      }

      woocommerce_form_field($key, $args, $value);

      $field = ob_get_clean();
    }

    return $field;
  }
}

WOOCCM_PRO_Fields_Datepicker::instance();
