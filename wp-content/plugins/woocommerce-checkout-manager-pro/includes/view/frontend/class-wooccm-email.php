<?php

class WOOCCM_PRO_Order_Email_Controller
{

  protected static $_instance;

  public function __construct()
  {
    add_action('woocommerce_email_after_order_table', array($this, 'add_custom_fields'), 10, 4);
  }

  public static function instance()
  {
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  // Frontend
  // ---------------------------------------------------------------------------

  public function add_custom_fields($order, $sent_to_admin, $plain_text, $email)
  {

    if (get_option('wooccm_email_custom_fields', 'no') === 'yes') {

      $order_id = $order->get_id();

      if (!count(array_values(get_option('wooccm_email_custom_fields_status', array()))) || in_array("wc-{$order->get_status()}", array_values(get_option('wooccm_email_custom_fields_status', array())))) {
        wc_get_template('templates/emails/email-custom-fields.php', array('order_id' => $order_id), '', WOOCCM_PRO_PLUGIN_DIR);
      }
    }

    if (get_option('wooccm_email_upload_files', 'no') === 'yes') {

      if (!count(array_values(get_option('wooccm_email_upload_files_order_status', array()))) || in_array("wc-{$order->get_status()}", array_values(get_option('wooccm_email_upload_files_order_status', array())))) {

        $attachments = get_posts(
          array(
            'fields' => 'ids',
            'post_type' => 'attachment',
            'numberposts' => -1,
            'post_status' => null,
            'post_parent' => $order->get_id()
          )
        );

        wc_get_template('templates/emails/email-upload-files.php', array('order' => $order, 'attachments' => $attachments), '', WOOCCM_PRO_PLUGIN_DIR);
      }
    }
  }
}

WOOCCM_PRO_Order_Email_Controller::instance();
