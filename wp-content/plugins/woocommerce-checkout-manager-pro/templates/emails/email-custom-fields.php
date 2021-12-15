<?php $text_align = is_rtl() ? 'right' : 'left'; ?>
<h2 class="woocommerce-order-details__title"><?php echo ($title = get_option('wooccm_email_custom_fields_title', false)) ? esc_html($title) : esc_html__('Order extra', 'woocommerce-checkout-manager-pro'); ?></h2>
<div style="margin-bottom: 40px;">
  <table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
    <tbody>
      <?php foreach (WOOCCM()->billing->get_fields() as $field_id => $field) :
        $billing_defaults = array_column(WOOCCM()->billing->get_defaults(), 'key');
      ?>
        <?php if (!in_array($field['key'], $billing_defaults) && empty($field['hide_email'])) : ?>
          <?php if ($value = get_post_meta($order_id, sprintf('_%s', $field['key']), true)) : ?>
            <tr id="tr-<?php echo esc_attr($field['key']); ?>" class="woocommerce-table__line-item order_item">
              <td class="td" scope="col" style="text-align:<?php echo esc_attr($text_align); ?>;">
                <?php echo esc_html($field['label']); ?>
              </td>
              <td class="td" scope="col" style="text-align:<?php echo esc_attr($text_align); ?>;">
                <?php echo esc_html($value); ?>
              </td>
            </tr>
          <?php endif; ?>
        <?php endif; ?>
      <?php endforeach; ?>
      <?php foreach (WOOCCM()->shipping->get_fields() as $field_id => $field) :
        $shipping_defaults = array_column(WOOCCM()->shipping->get_defaults(), 'key'); ?>
        <?php if (!in_array($field['key'], $shipping_defaults) && empty($field['hide_email'])) : ?>
          <?php if ($value = get_post_meta($order_id, sprintf('_%s', $field['key']), true)) : ?>
            <tr id="tr-<?php echo esc_attr($field['key']); ?>">
              <td class="td" scope="col" style="text-align:<?php echo esc_attr($text_align); ?>;">
                <?php echo esc_html($field['label']); ?>
              </td>
              <td class="td" scope="col" style="text-align:<?php echo esc_attr($text_align); ?>;">
                <?php echo esc_html($value); ?>
              </td>
            </tr>
          <?php endif; ?>
        <?php endif; ?>
      <?php endforeach; ?>
      <?php foreach (WOOCCM()->additional->get_fields() as $field_id => $field) : ?>
        <?php if (empty($field['hide_email'])) : ?>
          <?php if ($value = get_post_meta($order_id, sprintf('_%s', $field['key']), true)) : ?>
            <tr id="tr-<?php echo esc_attr($field['key']); ?>">
              <td class="td" scope="col" style="text-align:<?php echo esc_attr($text_align); ?>;">
                <?php echo esc_html($field['label']); ?>
              </td>
              <td class="td" scope="col" style="text-align:<?php echo esc_attr($text_align); ?>;">
                <?php echo esc_html($value); ?>
              </td>
            </tr>
          <?php endif; ?>
        <?php endif; ?>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>