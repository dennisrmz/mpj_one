<?php

class WOOCCM_PRO_Fields_Timepicker
{

  protected static $_instance;

  public function __construct()
  {
    add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'), -10);
    // Add field attributes
    add_filter('wooccm_checkout_field_filter', array($this, 'add_field_attributes'));
    add_filter('wooccm_checkout_field_filter', array($this, 'add_field_class'));
    add_filter('woocommerce_form_field_time', array($this, 'timepicker_field'), 10, 4);
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

    WOOCCM_PRO::instance()->register_scripts();

    if (is_checkout()) {

      $i18n = substr(get_user_locale(), 0, 2);

      // Timepicker
      // ---------------------------------------------------------------------
      wp_enqueue_style('jquery-ui-timepicker');
      wp_enqueue_script('jquery-ui-timepicker');

      if (is_file(WOOCCM_PRO_PLUGIN_DIR . 'assets/timepicker/i18n/jquery.ui.timepicker-' . $i18n . '.js')) {
        wp_enqueue_script('jquery-ui-timepicker-' . $i18n, plugins_url('assets/timepicker/i18n/jquery.ui.timepicker-' . $i18n . '.js', WOOCCM_PRO_PLUGIN_FILE), array('jquery-ui-timepicker'), WOOCCM_PRO_PLUGIN_VERSION);
      }
    }
  }

  public function add_field_attributes($field)
  {

    if ($field['type'] == 'time' && $field['select2']) {
      $field['custom_attributes']['readonly'] = 'readonly';
      if (!empty($field['time_limit_start']) || !empty($field['time_limit_end'])) {

        $starts = substr(@$field['time_limit_start'], 0, 2);
        $end = substr(@$field['time_limit_end'], 0, 2);

        if ($starts != $end) {
          $field['custom_attributes']['data-hours'] = json_encode(array(
            'starts' => intval(@$starts),
            'ends' => intval(@$end) ? intval(@$end) : 24
          ));
        }
      }
      if (!empty($field['time_limit_interval'])) {
        $field['custom_attributes']['data-minutes'] = json_encode(array('interval' => intval($field['time_limit_interval'])));
      }
      $field['custom_attributes']['data-format-ampm'] = $field['time_format_ampm'];
    }

    return $field;
  }

  public function add_field_class($field)
  {
    if ($field['type'] == 'time' && $field['select2']) {
      $field['input_class'][] = 'wooccm-enhanced-timepicker';
    }
    return $field;
  }

  // Timepicker
  // ---------------------------------------------------------------------------
  public function timepicker_field($field = '', $key, $args, $value)
  {

    if ($args['select2']) {

      $args['type'] = 'text';

      ob_start();

      woocommerce_form_field($key, $args, $value);

      $field = ob_get_clean();
    }

    return $field;
  }
}

WOOCCM_PRO_Fields_Timepicker::instance();
