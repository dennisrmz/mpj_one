<?php

class WOOCCM_PRO_MyAccount_Controller
{

    protected static $_instance;
    protected $billing_fields = [];
    protected $shipping_fields = [];

    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_filter('woocommerce_my_account_my_address_formatted_address', array($this, 'add_fields'), 10, 3);
        add_filter('woocommerce_localisation_address_formats', array($this, 'add_formats'));
        add_filter('woocommerce_formatted_address_replacements', array($this, 'add_replacements'), 10, 2);
        //add_action('woocommerce_after_save_address_validation', array($this, 'save_fields'), 10, 4);
        add_filter('woocommerce_save_account_details_required_fields', '__return_empty_array');
        //add_filter('woocommerce_default_address_fields', array($this, 'test'));
    }
/* 
    function test($address) {
        foreach ($address as $key => $field) {
            $address[$key]['required'] = false;
            $address[$key]['test'] = 1326;
        }

        return $address;
    } */

/*     function save_fields($user_id, $load_address, $address, $customer)
    {

        foreach ($address as $key => $field) {

            if (isset($_POST[$key])) {
                // Get Value.
                if ('checkbox' === $field['type']) {
                    $value = (int) isset($_POST[$key]);
                } else if (is_array($_POST[$key])) {
                    $value = implode(',', $_POST[$key]);
                } else {
                    $value = isset($_POST[$key]) ? wc_clean(wp_unslash($_POST[$key])) : '';
                }

                update_user_meta($user_id, sprintf('%s', $key), $value);
            }
        }
    } */

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function add_fields($address, $customer_id, $address_type)
    {

        $this->billing_fields = WOOCCM()->billing->get_fields();
        $this->shipping_fields = WOOCCM()->shipping->get_fields();

        if (is_array($this->billing_fields)) {
            if ($address_type == 'billing') {
                foreach ($this->billing_fields as $field_id => $field) {
                    if (!isset($address[$field['name']])) {
                        $value = get_user_meta(get_current_user_id(), sprintf('%s', $field['key']), true);
                        if ($value) {
                            $address[$field['key']] = is_array($value) ? implode(', ', $value) : $value;
                        }
                    }
                }
            }
        }

        if (is_array($this->shipping_fields)) {
            if ($address_type == 'shipping') {
                foreach ($this->shipping_fields as $field_id => $field) {
                    if (!isset($address[$field['name']])) {
                        $value = get_user_meta($customer_id, sprintf('%s', $field['key']), true);
                        if ($value) {
                            $address[$field['key']] = is_array($value) ? implode(', ', $value) : $value;
                        }
                    }
                }
            }
        }

        return $address;
    }

    public function add_formats($formats)
    {

        $custom_formats = '';

        if (is_array($this->billing_fields)) {
            foreach ($this->billing_fields as $key => $field) {
                $custom_formats .= "\n{{$field['key']}}";
            }
        }

        if (is_array($this->shipping_fields)) {
            foreach ($this->shipping_fields as $key => $field) {
                $custom_formats .= "\n{{$field['key']}}";
            }
        }

        if ($custom_formats) {
            foreach ($formats as $key => &$format) {
                $format .= $custom_formats;
            }
        }

        return $formats;
    }

    public function add_replacements($replacements, $args)
    {

        if (is_array($this->billing_fields)) {
            foreach ($this->billing_fields as $key => $field) {
                if (isset($args[$field['key']])) {
                    $replacements["{{$field['key']}}"] = $args[$field['key']];
                } else {
                    $replacements["{{$field['key']}}"] = '';
                }
            }
        }

        if (is_array($this->shipping_fields)) {
            foreach ($this->shipping_fields as $key => $field) {
                if (isset($args[$field['key']])) {
                    $replacements["{{$field['key']}}"] = $args[$field['key']];
                } else {
                    $replacements["{{$field['key']}}"] = '';
                }
            }
        }

        return $replacements;
    }

    //1326 maybe delete 
    public function enqueue_scripts()
    {

        if (is_account_page()) {

            // UI
            // ---------------------------------------------------------------------
            wp_enqueue_style('jquery-ui-style');

            // Datepicker
            // ---------------------------------------------------------------------
            wp_enqueue_script('jquery-ui-datepicker');

            WOOCCM()->register_scripts();

            $i18n = substr(get_user_locale(), 0, 2);

            wp_enqueue_style('wooccm');

            // Colorpicker
            // ---------------------------------------------------------------------
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');

            // Farbtastic
            // ---------------------------------------------------------------------
            wp_enqueue_style('farbtastic');
            wp_enqueue_script('farbtastic');

            // Dashicons
            // ---------------------------------------------------------------------
            wp_enqueue_style('dashicons');


            // Checkout
            // ---------------------------------------------------------------------
            wp_enqueue_script('wooccm-checkout');
        }
    }
}

WOOCCM_PRO_MyAccount_Controller::instance();
