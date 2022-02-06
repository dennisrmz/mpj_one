<?php

/**
 * Plugin Name: WooCommerce Checkout Manager PRO
 * Description: Manages WooCommerce Checkout, the advanced way.
 * Version:     1.3.7
 * Author:      QuadLayers
 * Author URI:  https://www.quadlayers.com
 * Copyright:   2019 QuadLayers (https://www.quadlayers.com)
 * Text Domain: woocommerce-checkout-manager
 * WC requires at least: 3.1.0
 * WC tested up to: 5.1.0
 */
if (!defined('ABSPATH')) {
  die('-1');
}
if (!defined('WOOCCM_PLUGIN_NAME')) {
  define('WOOCCM_PLUGIN_NAME', 'WooCommerce Checkout Manager');
}
if (!defined('WOOCCM_PRO_PLUGIN_NAME')) {
  define('WOOCCM_PRO_PLUGIN_NAME', 'WooCommerce Checkout Manager PRO');
}
if (!defined('WOOCCM_PRO_PLUGIN_VERSION')) {
  define('WOOCCM_PRO_PLUGIN_VERSION', '1.3.7');
}
if (!defined('WOOCCM_PRO_PLUGIN_FILE')) {
  define('WOOCCM_PRO_PLUGIN_FILE', __FILE__);
}
if (!defined('WOOCCM_PRO_PLUGIN_DIR')) {
  define('WOOCCM_PRO_PLUGIN_DIR', __DIR__ . DIRECTORY_SEPARATOR);
}
if (!defined('WOOCCM_PRO_PREFIX')) {
  define('WOOCCM_PRO_PREFIX', 'wooccm');
}
if (!defined('WOOCCM_PRO_WORDPRESS_URL')) {
  define('WOOCCM_PRO_WORDPRESS_URL', 'https://wordpress.org/plugins/woocommerce-checkout-manager/');
}
if (!defined('WOOCCM_PRO_REVIEW_URL')) {
  define('WOOCCM_PRO_REVIEW_URL', 'https://wordpress.org/support/plugin/woocommerce-checkout-manager/reviews/?filter=5#new-post');
}
if (!defined('WOOCCM_PRO_DOCUMENTATION_URL')) {
  define('WOOCCM_PRO_DOCUMENTATION_URL', 'https://quadlayers.com/documentation/woocommerce-checkout-manager/?utm_source=wooccm_admin');
}
if (!defined('WOOCCM_PRO_DEMO_URL')) {
  define('WOOCCM_PRO_DEMO_URL', 'https://quadlayers.com/portfolio/woocommerce-checkout-manager/?utm_source=wooccm_admin');
}
if (!defined('WOOCCM_PRO_PURCHASE_URL')) {
  define('WOOCCM_PRO_PURCHASE_URL', WOOCCM_PRO_DEMO_URL);
}
if (!defined('WOOCCM_PRO_SUPPORT_URL')) {
  define('WOOCCM_PRO_SUPPORT_URL', 'https://quadlayers.com/account/support/?utm_source=wooccm_admin');
}
if (!defined('WOOCCM_PRO_LICENSES_URL')) {
  define('WOOCCM_PRO_LICENSES_URL', 'https://quadlayers.com/account/licenses/?utm_source=wooccm_admin');
}
if (!defined('WOOCCM_PRO_GROUP_URL')) {
  define('WOOCCM_PRO_GROUP_URL', 'https://www.facebook.com/groups/quadlayers');
}

if (!class_exists('WOOCCM_PRO', false)) {
  include_once( WOOCCM_PRO_PLUGIN_DIR . 'includes/class-wooccm.php' );
}