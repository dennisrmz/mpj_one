<?php

class WOOCCM_PRO_Order_List_Controller
{

  protected static $_instance;

  public function __construct()
  {
    add_action('wp_ajax_wooccm_search_field', array($this, 'ajax_search_field'));

    // List
    // -------------------------------------------------------------------------

    add_filter('manage_edit-shop_order_columns', array($this, 'render_column_titles'), 15);
    add_action('manage_shop_order_posts_custom_column', array($this, 'render_column_content'), 5);
    add_filter('manage_edit-shop_order_sortable_columns', array($this, 'add_sortable_columns'));
    add_action('restrict_manage_posts', array($this, 'restrict_orders'), 15);

    // Filter
    // -------------------------------------------------------------------------
    add_action('parse_query', array($this, 'search_custom_fields'));

    // Save
    // -------------------------------------------------------------------------

    add_action('woocommerce_process_shop_order_meta', array($this, 'save_order_data'));
  }

  public static function instance()
  {
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  public function ajax_search_field()
  {

    global $wpdb;

    if (check_admin_referer('wooccm_search_field', 'nonce') && isset($_GET['term']) && isset($_GET['key'])) {

      $term = sanitize_text_field($_GET['term']);

      $key = strip_tags($_GET['key']);

      $field_values = array();

      $query = sprintf("SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = '%s' AND meta_value LIKE '%s' ", sprintf('_%s', $key), '%' . $term . '%');

      if ($results = $wpdb->get_results($query)) {
        foreach ($results as $result) {
          $field_values[$result->meta_value] = $result->meta_value;
        }
      }

      wp_send_json($field_values);
    }

    wp_die();
  }

  // List
  // ---------------------------------------------------------------------------

  protected function restrict_orders_field($field)
  {

    $option = WOOCCM()->billing->get_option_types();

    if (count($field['options']) && in_array($field['type'], $option)) {

      $value = isset($_GET[$field['key']]) ? $_GET[$field['key']] : null;
?>
      <select class="wc-enhanced-select" name="<?php echo esc_attr($field['key']); ?>" data-placeholder="<?php printf(esc_attr(__('Show all %s', 'woocommerce-checkout-manager-pro')), $field['label']); ?>" data-allow_clear="true" style="min-width:200px;">
        <option value=""></option>
        <?php foreach ($field['options'] as $option) : ?>
          <?php if (!empty($option['label'])) { ?>
            <option value="<?php echo esc_html($option['label']); ?>" <?php selected($option['label'], $value); ?>><?php echo esc_html__($option['label'], 'woocommerce-checkout-manager-pro'); ?></option>
        <?php }
        endforeach; ?>
      </select>
    <?php
    } elseif ('checkbox' === $field['type'] || 'file' === $field['type']) {
    ?>
      <label style="line-height: 32px; margin: 0 10px;">
        <input style="height:auto" type="checkbox" id="<?php echo esc_attr($field['key']); ?>" name="<?php echo esc_attr($field['key']); ?>" value="1" <?php checked(isset($_GET[$field['key']]) && $_GET[$field['key']], true, true); ?> />
        <?php echo sprintf('%s', $field['label'] ? esc_html($field['label']) : sprintf(esc_html__('Field %s', 'woocommerce-checkout-manager'), $field_id)); ?>
      </label>
<?php
    } else /*if ('text' === $field['type'])*/ {
      $key = isset($_GET[$field['key']]) ? $_GET[$field['key']] : null;
    ?>
      <select class="wooccm-enhanced-search" name="<?php echo esc_attr($field['key']); ?>" data-placeholder="<?php printf(__('Show all %s', 'woocommerce-checkout-manager-pro'), $field['label']); ?>" data-allow_clear="true" data-key="<?php echo esc_attr($field['key']); ?>" style="min-width:200px;">
        <?php if (!empty($key)) : ?>
          <option value="<?php echo esc_attr($key); ?>" selected="selected"><?php echo esc_html($key); ?></option>
        <?php endif; ?>
      </select>
    <?php
    } 
  }

  public function restrict_orders()
  {
    global $typenow;

    if ('shop_order' !== $typenow) {
      return;
    }

    if ($fields = WOOCCM()->billing->get_fields()) {
      foreach ($fields as $field_id => $field) {
        if (!empty($field['filterable'])) {
          $this->restrict_orders_field($field);
        }
      }
    }

    if ($fields = WOOCCM()->shipping->get_fields()) {
      foreach ($fields as $field_id => $field) {
        if (!empty($field['filterable'])) {
          $this->restrict_orders_field($field);
        }
      }
    }

    if ($fields = WOOCCM()->additional->get_fields()) {
      foreach ($fields as $field_id => $field) {
        if (!empty($field['filterable'])) {
          $this->restrict_orders_field($field);
        }
      }
    }
  }

  public function render_column_titles($columns)
  {

    // get all columns up to and excluding the 'order_actions' column
    $new_columns = array();

    foreach ($columns as $name => $value) {

      if ('order_actions' === $name) {
        prev($columns);
        break;
      }

      $new_columns[$name] = $value;
    }

    if ($fields = WOOCCM()->billing->get_fields()) {
      foreach ($fields as $field_id => $field) {
        if (!empty($field['listable'])) {
          $new_columns[$field['key']] = sprintf('%s', $field['label'] ? esc_html($field['label']) : sprintf(esc_html__('Field %s', 'woocommerce-checkout-manager'), $field_id));
        }
      }
    }

    if ($fields = WOOCCM()->shipping->get_fields()) {
      foreach ($fields as $field_id => $field) {
        if (!empty($field['listable'])) {
          $new_columns[$field['key']] = sprintf('%s', $field['label'] ? esc_html($field['label']) : sprintf(esc_html__('Field %s', 'woocommerce-checkout-manager'), $field_id));
        }
      }
    }

    if ($fields = WOOCCM()->additional->get_fields()) {
      foreach ($fields as $field_id => $field) {
        if (!empty($field['listable'])) {
          $new_columns[$field['key']] = sprintf('%s', $field['label'] ? esc_html($field['label']) : sprintf(esc_html__('Field %s', 'woocommerce-checkout-manager'), $field_id));
        }
      }
    }
    // add the 'order_actions' column, and any others
    foreach ($columns as $name => $value) {
      $new_columns[$name] = $value;
    }

    return $new_columns;
  }

  protected function render_column_content_field($order_id, $field)
  {

    switch ($field['type']) {

      case 'checkbox':
        echo get_post_meta($order_id, sprintf('_%s', $field['key']), true) ? '&#x2713;' : '';
        break;

      case 'file':

        if ($file_ids = explode(',', get_post_meta($order_id, sprintf('_%s', $field['key']), true))) {

          echo '<ul class="wooccm-files" style="list-style:none">';
          foreach ($file_ids as $key => $file_id) {
            if ($url = get_edit_post_link($file_id)) {

              $image = wp_get_attachment_image_src($file_id);

              echo '<li style="display:inline-block"><a style="display:block" href="' . esc_url($url) . '"><img src="' . $image[0] . '" width="32px" heigth="32px" /></a></li>';
            }
          }
          echo '</ul>';
        }

        break;

      case 'text':

        $label = get_post_meta($order_id, sprintf('_%s', $field['key']), true);
        echo esc_html($label);

        break;

      case 'textarea':
        $label = get_post_meta($order_id, sprintf('_%s', $field['key']), true);
        echo wp_kses_post($label);

        break;

      default:

        $label = get_post_meta($order_id, sprintf('_%s', $field['key']), true);
        echo is_array($label) ? implode(', ', $label) : $label;
    }
  }

  public function render_column_content($column)
  {
    global $post;

    if ($fields = WOOCCM()->billing->get_fields()) {
      foreach ($fields as $field_id => $field) {
        if ($column === $field['key']) {
          $this->render_column_content_field($post->ID, $field);
        }
      }
    }
    if ($fields = WOOCCM()->shipping->get_fields()) {
      foreach ($fields as $field_id => $field) {
        if ($column === $field['key']) {
          $this->render_column_content_field($post->ID, $field);
        }
      }
    }
    if ($fields = WOOCCM()->additional->get_fields()) {
      foreach ($fields as $field_id => $field) {
        if ($column === $field['key']) {
          $this->render_column_content_field($post->ID, $field);
        }
      }
    }
  }

  public function add_sortable_columns($columns)
  {

    if ($fields = WOOCCM()->billing->get_fields()) {
      foreach ($fields as $field_id => $field) {
        if (!empty($field['sortable'])) {
          $columns[$field['key']] = sprintf('_%s', $field['key']);
        }
      }
    }
    if ($fields = WOOCCM()->shipping->get_fields()) {
      foreach ($fields as $field_id => $field) {
        if (!empty($field['sortable'])) {
          $columns[$field['key']] = sprintf('_%s', $field['key']);
        }
      }
    }
    if ($fields = WOOCCM()->additional->get_fields()) {
      foreach ($fields as $field_id => $field) {
        if (!empty($field['sortable'])) {
          $columns[$field['key']] = sprintf('_%s', $field['key']);
        }
      }
    }

    return $columns;
  }

  // Filter
  // ---------------------------------------------------------------------------

  public function search_custom_fields($wp)
  {

    global $typenow;

    if ('shop_order' !== $typenow) {
      return $wp;
    }

    if ($fields = array_merge(
      (array) WOOCCM()->billing->get_fields(),
      (array) WOOCCM()->shipping->get_fields(),
      (array) WOOCCM()->additional->get_fields()
    )) {

      foreach ($fields as $field_id => $field) {

        if (!empty($field['filterable']) && isset($_GET[$field['key']]) && $_GET[$field['key']]) {

          $value = trim($_GET[$field['key']]);

          switch ($field['type']) {

            case 'file':
            case 'multiselect':
            case 'multicheckbox':
              $wp->query_vars['meta_query'][] = array(
                array(
                  'key' => sprintf('_%s', $field['key']),
                  'value' => $value,
                  'compare' => 'LIKE',
                ),
              );
              break;
            case 'checkbox':
              $wp->query_vars['meta_query'][] = array(
                'relation' => 'OR',
                array(
                  'key' => sprintf('_%s', $field['key']),
                  'value' => 'Yes',
                  'compare' => '=',
                ),
                array(
                  'key' => sprintf('_%s', $field['key']),
                  'value' => 1,
                  'compare' => '=',
                ),
                array(
                  'key' => sprintf('_%s', $field['key']),
                  'value' => true,
                  'compare' => '=',
                ),
              );
              break;
            default:
              $wp->query_vars['meta_query'][] = array(
                array(
                  'key' => sprintf('_%s', $field['key']),
                  'value' => $value,
                  'compare' => '=',
                ),
              );
          }
        }
      }
    }
  }

  function save_order_data($order_id)
  {

    if (count($fields = WOOCCM()->billing->get_fields())) {

      foreach ($fields as $field_id => $field) {

        $key = sprintf('_%s', $field['key']);

        if (!empty($_POST[$field['key']])) {

          $value = $_POST[$field['key']];

          if ($field['type'] == 'textarea') {
            update_post_meta($order_id, $key, wp_kses($value, false));
          } else {
            update_post_meta($order_id, $key, sanitize_text_field($value));
          }
        }
        //      
      }
    }

    if (count($fields = WOOCCM()->shipping->get_fields())) {

      foreach ($fields as $field_id => $field) {

        $key = sprintf('_%s', $field['key']);

        if (!empty($_POST[$field['key']])) {

          $value = $_POST[$field['key']];

          if ($field['type'] == 'textarea') {
            update_post_meta($order_id, $key, wp_kses($value, false));
          } else {
            update_post_meta($order_id, $key, sanitize_text_field($value));
          }
        }
        //      
      }
    }

    if (count($fields = WOOCCM()->additional->get_fields())) {

      foreach ($fields as $field_id => $field) {

        $key = sprintf('_%s', $field['key']);

        if (!empty($_POST[$field['key']])) {

          $value = $_POST[$field['key']];

          if ($field['type'] == 'textarea') {
            update_post_meta($order_id, $key, wp_kses($value, false));
          } else {
            update_post_meta($order_id, $key, sanitize_text_field($value));
          }
        }
        //      
      }
    }
  }
}

WOOCCM_PRO_Order_List_Controller::instance();
