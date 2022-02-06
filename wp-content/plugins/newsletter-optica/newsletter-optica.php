<?php
    /*
    Plugin Name: Newsletter
    Plugin URI:
    Description: Show PopUp
    Version: 1.0.0
    Author: Dennis Ramirez
    Author URI: https://fundacionmpj.org
    Text Domain: mpj
    */

    if (!defined('ABSPATH')) {
        die();
    }

    // Setup
    define('NEWSLETTER_MPJ_PLUGIN_URL', __FILE__);

    include('includes/activate.php');
    include('includes/mpj_post_types.php');
    include('template/mpj-templater.php');
    include('template/pop-up-call.php');
    include('bloques/enqueue.php');
    include('procesos/save_info.php');
    include('includes/mpj_list_table.php');
    include('includes/download_excel.php');
    include('procesos/add-value-fee.php');
    include('procesos/set-value-fee.php');
    include('procesos/mpj-filter-add-cart.php');
    include('procesos/mpj-filter-add-checkout.php');

     //Hooks
    register_activation_hook(NEWSLETTER_MPJ_PLUGIN_URL, 'mpj_activate_plugin');

    add_action('plugins_loaded', array( 'PageTemplater', 'get_instance' ));
    add_action('wp_footer', 'app_cal_gui');

     //Para Cargar JS del lado del publico de wordpress
    add_action('wp_enqueue_scripts', 'mpj_enqueue_scripts');
    add_action('admin_enqueue_scripts', 'enqueue_my_script');
    

    //Ajax
    add_action('wp_ajax_mpj_send_data_news','mpj_save_info');
    add_action('wp_ajax_nopriv_mpj_send_data_news','mpj_save_info');

    add_action('wp_ajax_add_checkout_fee', 'add_checkout_fee');
    add_action('wp_ajax_nopriv_add_checkout_fee', 'add_checkout_fee');
    
    add_action('wp_ajax_mpj_get_cart','mpj_get_cart');
    add_action('wp_ajax_nopriv_mpj_get_cart','mpj_get_cart');

    add_action('init', 'print_excel');


    // add_action( 'woocommerce_product_thumbnails', 'content_before_product');
    add_action( 'woocommerce_before_add_to_cart_button', 'content_before_product');
    add_action( 'woocommerce_product_thumbnails', 'content_before_image');
    
    function content_before_image(){
        // echo $page;

    }

    
    function content_before_product(){

        ob_start();
        include ABSPATH . 'wp-content/plugins/newsletter-optica/template/mpj-add-ons.php';
        $page = ob_get_contents();
        ob_end_clean();
        echo $page;


    
    }

    /**
     * Codigo para cargar una nueva opcion extra en la vista de mi cuenta
    */

    /**
     * Register new endpoints to use inside My Account page.
    */


    function my_account_new_endpoints() {
        add_rewrite_endpoint( 'awards', EP_ROOT | EP_PAGES );
    }

    add_action( 'init', 'my_account_new_endpoints' );

    /**
     * Get new endpoint content
    */

    // Awards
    add_action( 'woocommerce_account_awards_endpoint', 'awards_endpoint_content' );

    function awards_endpoint_content() {
        ob_start();
        include ABSPATH . 'wp-content/plugins/newsletter-optica/template/my-account-awards.php';
        $page = ob_get_contents();
        ob_end_clean();
        echo $page;
    }


/**
  * Edit my account menu order
  */

 function my_account_menu_order() {
 	$menuOrder = array(
		'dashboard'          => __( 'Escritorio', 'woocommerce' ),
 		'orders'             => __( 'Pedidos', 'woocommerce' ),
 		'awards'             => __( 'Recetas Oftalmologicos', 'woocommerce' ),
 		'downloads'          => __( 'Descargas', 'woocommerce' ),
 		'edit-address'       => __( 'Direcciones', 'woocommerce' ),
 		'edit-account'    	=> __( 'Detalles de la cuenta', 'woocommerce' ),
 		'customer-logout'    => __( 'Salir', 'woocommerce' ),
 	);
 	return $menuOrder;
 }
 add_filter ( 'woocommerce_account_menu_items', 'my_account_menu_order' );
 add_filter( 'woocommerce_add_to_cart_validation', 'mpj_filter_add_cart', 10, 5 );
 add_filter( 'woocommerce_update_cart_validation', 'filter_woocommerce_update_cart_action_cart_updated', 10, 4 );
 add_action( 'woocommerce_after_checkout_validation', 'mpj_custom_validate_stock', 10, 2);