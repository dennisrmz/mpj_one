<?php $text_align = is_rtl() ? 'right' : 'left'; ?>
<h2 class="woocommerce-order-details__title"><?php echo ($title = get_option('wooccm_email_upload_files_title', false)) ? esc_html($title) : esc_html__('Uploaded files', 'woocommerce-checkout-manager'); ?></h2>
<div style="margin-bottom: 40px;">
  <table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
    <thead>
      <tr class="woocommerce-table__line-item order_item">
        <td class="td" scope="col" style="text-align:<?php echo esc_attr($text_align); ?>;"><?php _e('File', 'woocommerce-checkout-manager'); ?></td>
        <td class="td" scope="col" style="text-align:<?php echo esc_attr($text_align); ?>;"><?php _e('Filename', 'woocommerce-checkout-manager'); ?></td>
        <td class="td" scope="col" style="text-align:<?php echo esc_attr($text_align); ?>;"><?php _e('Dimensions', 'woocommerce-checkout-manager'); ?></td>
        <td class="td" scope="col" style="text-align:<?php echo esc_attr($text_align); ?>;"><?php _e('Extension', ' woocommerce-checkout-manager'); ?></td>
      </tr>
    </thead>
    <tbody class="product_images">
      <?php
      if (!empty($attachments)) :
        foreach ($attachments as $attachment_id) :
          $image_attributes = wp_get_attachment_url($attachment_id);
          $image_attributes2 = wp_get_attachment_image_src($attachment_id);
          $filename = basename($image_attributes);
          $wp_filetype = wp_check_filetype($filename);
          ?>
          <tr class="woocommerce-table__line-item order_item">
            <td class="td" scope="col" style="text-align:<?php echo esc_attr($text_align); ?>;"><?php echo wp_get_attachment_link($attachment_id, '', false, false, wp_get_attachment_image($attachment_id, array(75, 75), false)); ?></td>
            <td class="td" scope="col" style="text-align:<?php echo esc_attr($text_align); ?>;"><?php echo wp_get_attachment_link($attachment_id, '', false, false, preg_replace('/\.[^.]+$/', '', $filename)); ?></td>
            <td class="td" scope="col" style="text-align:<?php echo esc_attr($text_align); ?>;">
              <?php
              if ($image_attributes2[1] == '') {
                echo '-';
              } else {
                echo $image_attributes2[1] . ' x ' . $image_attributes2[2];
              }
              ?>
            </td>
            <td class="td" scope="col" style="text-align:<?php echo esc_attr($text_align); ?>;"><?php echo strtoupper($wp_filetype['ext']); ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr class="woocommerce-table__line-item order_item">
          <td colspan="6" style="text-align:left;"><?php esc_html_e('No files have been uploaded to this order.', 'woocommerce-checkout-manager'); ?></td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>