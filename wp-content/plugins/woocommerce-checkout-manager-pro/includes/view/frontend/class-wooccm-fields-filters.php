<?php

class WOOCCM_PRO_Fields_Message
{

  protected static $_instance;

  public function __construct()
  {
    add_filter('woocommerce_form_field_message', array($this, 'message_field'), 10, 4);
    add_filter('woocommerce_form_field_button', array($this, 'button_field'), 10, 4);
  }

  public static function instance()
  {
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  public function message_field($field = '', $key, $args, $value)
  {

    // Custom attribute handling.
    $custom_attributes = array();

    if (!empty($args['custom_attributes']) && is_array($args['custom_attributes'])) {
      foreach ($args['custom_attributes'] as $attribute => $attribute_value) {
        $custom_attributes[] = esc_attr($attribute) . '="' . esc_attr($attribute_value) . '"';
      }
    }

    $sort = $args['priority'] ? $args['priority'] : '';
    //message//info//error
    $field_html = '<div class="woocommerce-' . esc_attr($args['message_type']) . ' ' . esc_attr($args['extra_class']) . '">';
    if ($args['label']) {
      $field_html .= '<b ' . implode(' ', $custom_attributes) . '>' . esc_html($args['label']) . '</b>';
      $field_html .= '</br>';
    }
    $field_html .= wp_kses_post($args['description']);
    $field_html .= '</div>';

    $container_class = esc_attr(implode(' ', $args['class']));
    $container_id = esc_attr($args['id']) . '_field';
    $field_container = '<div class="form-row %1$s" id="%2$s" data-priority="' . esc_attr($sort) . '">%3$s</div>';

    return sprintf($field_container, $container_class, $container_id, $field_html);
  }

  public function button_field($field = '', $key, $args, $value)
  {

    // Custom attribute handling.
    $custom_attributes = array();

    if (!empty($args['custom_attributes']) && is_array($args['custom_attributes'])) {
      foreach ($args['custom_attributes'] as $attribute => $attribute_value) {
        $custom_attributes[] = esc_attr($attribute) . '="' . esc_attr($attribute_value) . '"';
      }
    }

    $sort = $args['priority'] ? $args['priority'] : '';

    if ($args['label']) {
      $field_html = '<a target="_blank" class="button ' . esc_attr($args['button_type']) . ' ' . esc_attr($args['extra_class']) . '" href="' . esc_url($args['button_link']) . '" ' . implode(' ', $custom_attributes) . '>';
      $field_html .= esc_html($args['label']);
      $field_html .= '</a>';
    }

    $container_class = esc_attr(implode(' ', $args['class']));
    $container_id = esc_attr($args['id']) . '_field';
    $field_container = '<div class="form-row %1$s" id="%2$s" data-priority="' . esc_attr($sort) . '">%3$s</div>';

    return sprintf($field_container, $container_class, $container_id, $field_html);
  }
}

WOOCCM_PRO_Fields_Message::instance();
