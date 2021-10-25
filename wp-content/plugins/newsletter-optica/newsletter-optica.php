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

    add_action('init', 'print_excel');

    

    // add_action( 'woocommerce_product_thumbnails', 'content_before_product');
    add_action( 'woocommerce_before_add_to_cart_button', 'content_before_product');
    add_action( 'woocommerce_product_thumbnails', 'content_before_image');
    
    function content_before_image(){

        ob_start();
        include ABSPATH . 'wp-content/plugins/newsletter-optica/template/mpj-info-receta.php';
        $page = ob_get_contents();
        ob_end_clean();
        // echo $page;

    }
    
    function content_before_product(){

        ob_start();
        include ABSPATH . 'wp-content/plugins/newsletter-optica/template/mpj-add-ons.php';
        $page = ob_get_contents();
        ob_end_clean();
        echo $page;


    
    }