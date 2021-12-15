<?php

final class WOOCCM_PRO
{

  protected static $_instance;

  public function __construct()
  {

    add_action('admin_head', array($this, 'css'));
    add_action('admin_init', array($this, 'add_updater'));
    add_action('init', array($this, 'compatibility'));
    add_action('woocommerce_init', array($this, 'includes'), 15);
    add_filter('wooccm_fields_disabled_types', '__return_empty_array');

    load_plugin_textdomain('woocommerce-checkout-manager-pro', false, dirname(plugin_basename(__FILE__)) . '/languages/');
  }

  public static function instance()
  {
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  public function compatibility()
  {
    include_once(WOOCCM_PRO_PLUGIN_DIR . 'includes/compatibility/class-wooccm-fields-invoices.php');
    include_once(WOOCCM_PRO_PLUGIN_DIR . 'includes/compatibility/class-wooccm-fields-subscriptions.php');
  }

  public function includes()
  {

    include_once(WOOCCM_PRO_PLUGIN_DIR . 'includes/view/backend/class-wooccm-notices.php');

    if (class_exists('WOOCCM')) {
      include_once(WOOCCM_PRO_PLUGIN_DIR . 'includes/view/backend/class-wooccm-license.php');
      include_once(WOOCCM_PRO_PLUGIN_DIR . 'includes/view/backend/class-wooccm-order.php');
      include_once(WOOCCM_PRO_PLUGIN_DIR . 'includes/view/backend/class-wooccm-export.php');

      if (!is_admin()) {
        include_once(WOOCCM_PRO_PLUGIN_DIR . 'includes/view/frontend/class-wooccm-myaccount.php');
        include_once(WOOCCM_PRO_PLUGIN_DIR . 'includes/view/frontend/class-wooccm-email.php');
        include_once(WOOCCM_PRO_PLUGIN_DIR . 'includes/view/frontend/class-wooccm-fields-price.php');
        include_once(WOOCCM_PRO_PLUGIN_DIR . 'includes/view/frontend/class-wooccm-fields-display.php');
        include_once(WOOCCM_PRO_PLUGIN_DIR . 'includes/view/frontend/class-wooccm-fields-select2.php');
        include_once(WOOCCM_PRO_PLUGIN_DIR . 'includes/view/frontend/class-wooccm-fields-timepicker.php');
        include_once(WOOCCM_PRO_PLUGIN_DIR . 'includes/view/frontend/class-wooccm-fields-datepicker.php');
        include_once(WOOCCM_PRO_PLUGIN_DIR . 'includes/view/frontend/class-wooccm-fields-filters.php');
      }

      if (class_exists('WOOCCM_Checkout_Premium_Controller')) {
        $premium = WOOCCM_Checkout_Premium_Controller::instance();
        remove_filter('wooccm_sections_header', array($premium, 'add_menu'));
        remove_filter('wooccm_sections_header', array($premium, 'add_header'));
      }
    }
  }

  public function css()
  {
?>
    <style>
      .wooccm-premium {
        opacity: 1;
        pointer-events: all;
      }

      .wooccm-premium .description.hidden {
        display: inline-block;
      }

      .wooccm-premium .description.premium {
        display: none;
      }
    </style>
<?php

  }

  public function register_scripts()
  {

    // UI
    // ---------------------------------------------------------------------
    wp_register_style('jquery-ui-style', WC()->plugin_url() . '/assets/css/jquery-ui/jquery-ui.min.css', array(), WC_VERSION);

    // Timepicker
    // ---------------------------------------------------------------------
    wp_register_style('jquery-ui-timepicker', plugins_url('assets/frontend/timepicker/jquery.ui.timepicker.css', WOOCCM_PRO_PLUGIN_FILE), array('jquery-ui-style'), WOOCCM_PRO_PLUGIN_VERSION);
    wp_register_script('jquery-ui-timepicker', plugins_url('assets/frontend/timepicker/jquery.ui.timepicker.js', WOOCCM_PRO_PLUGIN_FILE), array('jquery'), WOOCCM_PRO_PLUGIN_VERSION, true);
  }

  function add_updater()
  {

    global $wooccm_updater;

    include_once(WOOCCM_PRO_PLUGIN_DIR . 'includes/3rd/QLWDDUpdater.php');

    $wooccm_updater = qlwdd_updater(array(
      'api_url' => 'https://quadlayers.com/wc-api/qlwdd/',
      'plugin_url' => WOOCCM_PRO_DEMO_URL,
      'plugin_file' => WOOCCM_PRO_PLUGIN_FILE,
      'license_key' => get_option('wooccm_license_key'),
      'license_email' => get_option('wooccm_license_email'),
      'license_market' => get_option('wooccm_license_market'),
      'license_url' => admin_url('admin.php?page=wc-settings&tab=wooccm&section=license'),
      'product_key' => 'f917e527442a7307126a095fcb71f2c1',
      'envato_key' => 'Gn46hMOIcvz8uyVvpe0jB2ge7A1RdH5T',
      'envato_id' => '28155975',
    ));
  }
}

WOOCCM_PRO::instance();
