<?php

class WOOCCM_PRO_License
{

  protected static $_instance;

  public function __construct()
  {
    add_action('admin_init', array($this, 'save_license'));
    add_action('wooccm_sections_header', array($this, 'add_header'));
    add_action('woocommerce_sections_' . WOOCCM_PREFIX, array($this, 'add_section'));
    add_action('woocommerce_settings_save_' . WOOCCM_PREFIX, array($this, 'save_settings'));
  }

  public static function instance()
  {
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  public function add_header()
  {
    global $current_section;
?>
    <li><a href="<?php echo admin_url('admin.php?page=wc-settings&tab=wooccm&section=license'); ?>" class="<?php echo ($current_section == 'license' ? 'current' : ''); ?>"><?php esc_html_e('License', 'woocommerce-checkout-manager-pro'); ?></a> | </li>
<?php
  }

  public function add_section()
  {

    global $current_section, $wooccm_updater;

    if ('license' == $current_section) {

      $settings = $this->get_settings();

      include_once(WOOCCM_PRO_PLUGIN_DIR . 'includes/view/backend/pages/license.php');
    }
  }

  public function get_settings()
  {

    return array(
      array(
        'type' => 'title',
        'id' => 'section_title',
        'name' => esc_html__('License', 'woocommerce-checkout-manager-pro'),
        'desc' => esc_html__('Add your license key to activate the premium features.', 'woocommerce-checkout-manager-pro'),
      ),
      array(
        'name' => esc_html__('Market', 'woocommerce-checkout-manager-pro'),
        'id' => 'wooccm_license_market',
        'type' => 'select',
        'class' => 'chosen_select',
        'options' => array(
          '' => esc_html__('QuadLayers', 'woocommerce-checkout-manager'),
          'envato' => esc_html__('Envato', 'woocommerce-checkout-manager'),
        ),
        'placeholder' => esc_html__('Enter your license market', 'woocommerce-checkout-manager-pro'),
      ),
      array(
        'name' => esc_html__('Key', 'woocommerce-checkout-manager-pro'),
        'id' => 'wooccm_license_key',
        'type' => 'password',
        'placeholder' => esc_html__('Enter your license key', 'woocommerce-checkout-manager-pro'),
      ),
      array(
        'name' => esc_html__('Email', 'woocommerce-checkout-manager-pro'),
        'id' => 'wooccm_license_email',
        'type' => 'password',
        'placeholder' => esc_html__('Enter your license email', 'woocommerce-checkout-manager-pro'),
      ),
      array(
        'type' => 'sectionend',
        'id' => 'section_end'
      )
    );
  }

  public function save_license()
  {

    global $wooccm_updater;

    if (
      isset($_POST['save']) &&
      isset($_POST['wooccm_license_key']) &&
      isset($_POST['wooccm_license_email']) &&
      isset($_POST['wooccm_license_market'])
    ) {
      $wooccm_updater->request_activation(
        $_POST['wooccm_license_key'],
        $_POST['wooccm_license_email'],
        $_POST['wooccm_license_market']
      );
    }
  }

  public function save_settings()
  {

    global $current_section;

    if ('license' == $current_section) {
      woocommerce_update_options($this->get_settings());
    }
  }
}

WOOCCM_PRO_License::instance();
