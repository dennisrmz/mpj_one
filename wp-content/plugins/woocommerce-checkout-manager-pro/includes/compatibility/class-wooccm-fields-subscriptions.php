<?php

class WOOCCM_PRO_Subscriptions
{

  protected static $_instance;

  public function __construct()
  {
    //add_action('ywsbs_renew_subscription', array($this, 'ywsbs_fields_on_renew_order'), 10, 2);
    add_action('ywcsb_admin_subscription_data_after_billing_address', array($this, 'ywcsb_admin_subscription_data_after_billing_address'), 10);
    add_action('ywcsb_admin_subscription_data_after_shipping_address', array($this, 'ywcsb_admin_subscription_data_after_shipping_address'), 10);
    add_action('ywcsb_admin_subscription_data_after_shipping_address', array($this, 'ywcsb_admin_subscription_data_after_additional_address'), 10);
  }

  public static function instance()
  {
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  function ywcsb_admin_subscription_data_after_billing_address($subscription)
  {

    include_once(WOOCCM_PLUGIN_DIR . 'includes/view/frontend/class-wooccm-fields-handler.php');

    if (!$billing = WOOCCM()->billing->get_fields()) {
      return;
    }

    $defaults = WOOCCM()->billing->get_defaults();
    $template = WOOCCM()->billing->get_template_types();

?>
    <div class="wooccm_fields address">
      <?php

      foreach ($billing as $field_id => $field) {

        if (in_array($field['name'], $defaults)) {
          continue;
        }

        if (in_array($field['name'], $template)) {
          continue;
        }

        if (!isset($field['type']) || $field['type'] != 'textarea') {
          $field['type'] = 'text';
        }

        $key = sprintf('_%s', $field['key']);

        if ($value = get_post_meta($subscription->order_id, $key, true)) {
      ?>
          <p>
            <strong><?php esc_html_e($field['label']); ?></strong>
            <?php esc_html_e($value); ?>
          </p>
      <?php

        }
      }

      ?>

    </div>

    <!-- <div class="wooccm_fields edit_address">
      <?php

      foreach ($billing as $field_id => $field) {

        if (in_array($field['name'], $defaults)) {
          continue;
        }

        if (in_array($field['name'], $template)) {
          continue;
        }

        $key = sprintf('_%s', $field['key']);

        $value = get_post_meta($subscription->order_id, $key, true);

        $field['id'] = sprintf('_%s', $field['key']);
        $field['name'] = $field['key'];
        $field['value'] = $value;
        $field['class'] = join(' ', $field['class']);
        $field['wrapper_class'] = 'wooccm-premium';

        switch ($field['type']) {
          case 'textarea':
            woocommerce_wp_textarea_input($field);
            break;
          default:
            $field['type'] = 'text';
            woocommerce_wp_text_input($field);
            break;
        }
      }

      ?>

    </div> -->

  <?php
  }

  function ywcsb_admin_subscription_data_after_shipping_address($subscription)
  {

    include_once(WOOCCM_PLUGIN_DIR . 'includes/view/frontend/class-wooccm-fields-handler.php');

    if (!$shipping = WOOCCM()->shipping->get_fields()) {
      return;
    }

    $defaults = WOOCCM()->shipping->get_defaults();
    $template = WOOCCM()->shipping->get_template_types();

  ?>
    <div class="wooccm_fields address">
      <?php

      foreach ($shipping as $field_id => $field) {

        if (in_array($field['name'], $defaults)) {
          continue;
        }

        if (in_array($field['name'], $template)) {
          continue;
        }

        if (!isset($field['type']) || $field['type'] != 'textarea') {
          $field['type'] = 'text';
        }

        $key = sprintf('_%s', $field['key']);

        if ($value = get_post_meta($subscription->order_id, $key, true)) {
      ?>
          <p>
            <strong><?php esc_html_e($field['label']); ?></strong>
            <?php esc_html_e($value); ?>
          </p>
      <?php

        }
      }

      ?>

    </div>

    <!-- <div class="wooccm_fields edit_address">
      <?php

      foreach ($shipping as $field_id => $field) {

        if (in_array($field['name'], $defaults)) {
          continue;
        }

        if (in_array($field['name'], $template)) {
          continue;
        }

        $key = sprintf('_%s', $field['key']);

        $value = get_post_meta($subscription->order_id, $key, true);

        $field['id'] = sprintf('_%s', $field['key']);
        $field['name'] = $field['key'];
        $field['value'] = $value;
        $field['class'] = join(' ', $field['class']);
        $field['wrapper_class'] = 'wooccm-premium';

        switch ($field['type']) {
          case 'textarea':
            woocommerce_wp_textarea_input($field);
            break;
          default:
            $field['type'] = 'text';
            woocommerce_wp_text_input($field);
            break;
        }
      }

      ?>

    </div> -->

  <?php
  }

  function ywcsb_admin_subscription_data_after_additional_address($subscription)
  {

    //include_once(WOOCCM_PLUGIN_DIR . 'includes/view/frontend/class-wooccm-fields-handler.php');

    if (!$additional = WOOCCM()->additional->get_fields()) {
      return;
    }

    $defaults = WOOCCM()->additional->get_defaults();
    $template = WOOCCM()->additional->get_template_types();

  ?>
    </div>
    <style>
      #subscription-data .subscription_data_column {
        width: 23%;
      }

      #subscription-data .subscription_data_column .wooccm-premium {
        width: 100% !important;
        float: none !important;
        clear: both;
      }

      #subscription-data .subscription_data_column .wooccm-premium:after,
      #subscription-data .subscription_data_column .wooccm-premium:before {
        display: block;
        content: "";
        clear: both;
      }

      #subscription-data .subscription_data_column_additional .form-field {
        width: 100%;
        clear: both;
      }
    </style>
    <div class="subscription_data_column subscription_data_column_additional">
      <h3>
        <?php esc_html_e('Additional', 'woocommerce-checkout-manager'); ?>
        <!-- <a href="#" class="edit_address"><?php esc_html_e('Edit', 'woocommerce-checkout-manager'); ?></a> -->
      </h3>
      <div class="wooccm_fields address">
        <?php

        foreach ($additional as $field_id => $field) {

          if (in_array($field['name'], $defaults)) {
            continue;
          }

          if (in_array($field['name'], $template)) {
            continue;
          }

          if (!isset($field['type']) || $field['type'] != 'textarea') {
            $field['type'] = 'text';
          }

          $key = sprintf('_%s', $field['key']);

          if ($value = get_post_meta($subscription->order_id, $key, true)) {
        ?>
            <p>
              <strong><?php esc_html_e($field['label']); ?></strong>
              <?php esc_html_e($value); ?>
            </p>
        <?php

          }
        }

        ?>

      </div>

      <!-- <div class="wooccm_fields edit_address">
      <?php

      foreach ($additional as $field_id => $field) {

        if (in_array($field['name'], $defaults)) {
          continue;
        }

        if (in_array($field['name'], $template)) {
          continue;
        }

        $key = sprintf('_%s', $field['key']);

        $value = get_post_meta($subscription->order_id, $key, true);

        $field['id'] = sprintf('_%s', $field['key']);
        $field['name'] = $field['key'];
        $field['value'] = $value;
        $field['class'] = join(' ', $field['class']);
        $field['wrapper_class'] = 'wooccm-premium';

        switch ($field['type']) {
          case 'textarea':
            woocommerce_wp_textarea_input($field);
            break;
          default:
            $field['type'] = 'text';
            woocommerce_wp_text_input($field);
            break;
        }
      }

      ?>

    </div> -->
  <?php

  }

  /*function ywsbs_fields_on_renew_order($order_id, $subscription_id)
  {
    
    if (function_exists('ywsbs_get_subscription')) {

      $subscription = ywsbs_get_subscription($subscription_id);
      $renew_order = wc_get_order($order_id);
      $parent_order = wc_get_order($subscription->order_id);

      if ($subscription && $renew_order && $parent_order) {

        // $myfield1 = $parent_order->get_meta( ‘#additional_myfield1’ );
        // $myfield2 = $parent_order->get_meta( ‘#additional_myfield2’ );
        // $myfield3 = $parent_order->get_meta( ‘#additional_myfield3’ );
        // $renew_order->update_meta_data( ‘#additional_myfield1’, $myfield1 );
        // $renew_order->update_meta_data( ‘#additional_myfield2’, $myfield2 );
        // $renew_order->update_meta_data( ‘#additional_myfield3’, $myfield3 );
        // $renew_order->save();

        //     if (count($fields = WOOCCM()->additional->get_fields())) {

        //       foreach ($fields as $field_id => $field) {

        //         $key = sprintf('_%s', $field['key']);

        //         if (!empty($data[$field['key']])) {

        //           $value = $data[$field['key']];

        //           if ($field['type'] == 'textarea') {
        //             update_post_meta($order_id, $key, wp_kses($value, false));
        //           } else if (is_array($value)) {
        //             update_post_meta($order_id, $key, implode(',', array_map('sanitize_text_field', $value)));
        //           } else {
        //             update_post_meta($order_id, $key, sanitize_text_field($value));
        //           }
        //         }
        // //      
        //       }
      }
    }
  }*/
}

WOOCCM_PRO_Subscriptions::instance();
