<?php

class WOOCCM_PRO_Invoices
{

  protected static $_instance;
  protected $billing_fields = [];
  protected $shipping_fields = [];
  protected $additional_fields = [];

  public function __construct()
  {
    //Print Invoice & Delivery Notes for WooCommerce
    //https://wordpress.org/plugins/woocommerce-delivery-notes/
    add_filter('wcdn_order_info_fields', array($this, 'wcdn_order_info_fields'), 10, 2);
    //PDF Invoice
    //https://docs.woocommerce.com/documentation/plugins/woocommerce/woocommerce-extensions/pdf-invoice/
    add_filter('pdf_content_additional_content', array($this, 'pdf_content_additional_content'), 10, 2);
    //WooCommerce PDF Invoices & Packing Slips
    //https://wordpress.org/plugins/woocommerce-pdf-invoices-packing-slips/
    add_action('wpo_wcpdf_after_order_details', array($this, 'wpo_wcpdf_after_order_details'), 10, 2);
  }

  public static function instance()
  {
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  function register_invoice_fields($order_id)
  {

    $billing_fields = WOOCCM()->billing->get_fields();
    $billing_defaults = array_column(WOOCCM()->billing->get_defaults(), 'key');

    $shipping_fields = WOOCCM()->shipping->get_fields();
    $shipping_defaults = array_column(WOOCCM()->shipping->get_defaults(), 'key');

    $additional_fields = WOOCCM()->additional->get_fields();

    foreach ($billing_fields as $field_id => $field) {
      if (!in_array($field['key'], $billing_defaults) && empty($field['hide_invoice'])) {
        if ($value = get_post_meta($order_id, sprintf('_%s', $field['key']), true)) {
          $this->billing_fields[$field['key']] = [
            'key' => $field['key'],
            'label' => $field['label'],
            'value' => $value
          ];
        }
      }
    }

    foreach ($shipping_fields as $field_id => $field) {
      if (!in_array($field['key'], $shipping_defaults) && empty($field['hide_invoice'])) {
        if ($value = get_post_meta($order_id, sprintf('_%s', $field['key']), true)) {
          $this->shipping_fields[$field['key']] = [
            'key' => $field['key'],
            'label' => $field['label'],
            'value' => $value
          ];
        }
      }
    }

    foreach ($additional_fields as $field_id => $field) {
      if ($value = get_post_meta($order_id, sprintf('_%s', $field['key']), true)) {
        $this->additional_fields[$field['key']] = [
          'key' => $field['key'],
          'label' => $field['label'],
          'value' => $value
        ];
      }
    }
  }

  function wcdn_order_info_fields($fields, $order)
  {

    $this->register_invoice_fields($order->get_order_number());

    if (
      !empty($this->billing_fields)
      ||
      !empty($this->shipping_fields)
      ||
      !empty($this->additional_fields)
    ) {
      return array_merge(
        $fields,
        $this->billing_fields,
        $this->shipping_fields,
        $this->additional_fields
      );
    }

    return $fields;
  }


  function wpo_wcpdf_after_order_details($type, $order)
  {

    $this->register_invoice_fields($order->get_order_number());

    if (
      !empty($this->billing_fields)
      ||
      !empty($this->shipping_fields)
      ||
      !empty($this->additional_fields)
    ) {

      $fields = array_merge(
        $this->billing_fields,
        $this->shipping_fields,
        $this->additional_fields
      );

?>
      <h2><?php echo ($title = get_option('wooccm_invoice_custom_fields_title', false)) ? esc_html($title) : esc_html__('Order Additional', 'woocommerce-checkout-manager-pro'); ?></h2>

      <table style="width: 100%;">
        <thead>
          <tr>
            <th class="product"><?php _e('Label', 'woocommerce-checkout-manager-pro'); ?></th>
            <th class="quantity"><?php _e('Value', 'woocommerce-checkout-manager-pro'); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($fields as $key => $field) : ?>
            <tr id="tr-<?php echo esc_attr($field['key']); ?>">
              <td class="product">
                <span class="item-name">
                  <?php echo esc_html($field['label']); ?>
                </span>
              </td>
              <td class="price">
                <?php echo esc_html($field['value']); ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php

    }
  }

  function pdf_content_additional_content($output, $order_id)
  {

    $this->register_invoice_fields($order_id);

    if (
      !empty($this->billing_fields)
      ||
      !empty($this->shipping_fields)
      ||
      !empty($this->additional_fields)
    ) {

      $fields = array_merge(
        $this->billing_fields,
        $this->shipping_fields,
        $this->additional_fields
      );

      ob_start();
    ?>
      <h2><?php echo ($title = get_option('wooccm_invoice_custom_fields_title', false)) ? esc_html($title) : esc_html__('Order Additional', 'woocommerce-checkout-manager-pro'); ?></h2>
      <table class="shop_table orderdetails" width="100%">
        <thead>
          <tr class="pdf_table_row">
            <th class="pdf_table_cell" width="5%" align="right" valign="top"><?php _e('Label', 'woocommerce-checkout-manager-pro'); ?></th>
            <th class="pdf_table_cell" width="95%" align="right" valign="top"><?php _e('Value', 'woocommerce-checkout-manager-pro'); ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($fields as $key => $field) : ?>
            <tr>
              <td valign="top">
                <?php echo esc_html($field['label']); ?>
              </td>
              <td align="right">
                <?php echo esc_html($field['value']); ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
<?php
      return ob_get_clean();
    }
  }
}

WOOCCM_PRO_Invoices::instance();
