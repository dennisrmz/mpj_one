<?php //include_once( 'parts/header.php' ); ?>
<h1 class="screen-reader-text"><?php esc_html_e('Order', 'woocommerce-checkout-manager-pro'); ?></h1>
<h2><?php esc_html_e('Email settings', 'woocommerce-checkout-manager-pro'); ?></h2>
<div id="<?php printf('wooccm_%s_settings-description', $current_section); ?>">
  <p><?php printf(esc_html__('Customize and manage the checkout %s fields.', 'woocommerce-checkout-manager-pro'), $current_section); ?></p>
</div>
<?php woocommerce_admin_fields($settings); ?>
