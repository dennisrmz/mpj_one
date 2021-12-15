<?php

class WOOCCM_PRO_Notices
{

  protected static $_instance;
  var $free = 'woocommerce-checkout-manager';

  public function __construct()
  {
    add_action('admin_notices', array($this, 'add_admin_notices'));
    add_filter('plugin_action_links_' . plugin_basename(WOOCCM_PRO_PLUGIN_FILE), array($this, 'add_action_links'));
  }

  public static function instance()
  {
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  function add_action_links($links)
  {

    $links[] = '<a target="_blank" href="' . WOOCCM_PRO_SUPPORT_URL . '">' . esc_html__('Support', 'woocommerce-checkout-manager-pro') . '</a>';
    $links[] = '<a target="_blank" href="' . WOOCCM_PRO_LICENSES_URL . '">' . esc_html__('License', 'woocommerce-checkout-manager-pro') . '</a>';

    return $links;
  }

  function add_admin_notices()
  {

    $screen = get_current_screen();

    if (isset($screen->parent_file) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id) {
      return;
    }

    $plugin = "{$this->free}/{$this->free}.php";

    if (is_plugin_active($plugin)) {
      return;
    }

    if ($this->is_installed($plugin)) {

      if (!current_user_can('activate_plugins')) {
        return;
      }
?>
      <div class="error">
        <p>
          <a href="<?php echo wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1', 'activate-plugin_' . $plugin); ?>" class='button button-secondary'><?php printf(esc_html__('Activate %s', 'woocommerce-checkout-manager-pro'), WOOCCM_PLUGIN_NAME); ?></a>
          <?php printf(esc_html__('%s not working because you need to activate the %s plugin.', 'woocommerce-checkout-manager-pro'), WOOCCM_PRO_PLUGIN_NAME, WOOCCM_PLUGIN_NAME); ?>
        </p>
      </div>
    <?php
    } else {
      if (!current_user_can('install_plugins')) {
        return;
      }
    ?>
      <div class="error">
        <p>
          <a href="<?php echo wp_nonce_url(self_admin_url("update.php?action=install-plugin&plugin={$this->free}"), "install-plugin_{$this->free}"); ?>" class='button button-secondary'><?php printf(esc_html__('Install %s', 'woocommerce-checkout-manager-pro'), WOOCCM_PLUGIN_NAME); ?></a>
          <?php printf(esc_html__('%s not working because you need to install the %s plugin.', 'woocommerce-checkout-manager-pro'), WOOCCM_PRO_PLUGIN_NAME, WOOCCM_PLUGIN_NAME); ?>
        </p>
      </div>
<?php
    }
  }

  function is_installed($path)
  {

    $installed_plugins = get_plugins();

    return isset($installed_plugins[$path]);
  }
}

WOOCCM_PRO_Notices::instance();
