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
    include('template/mpj-templater.php');
    include('template/pop-up-call.php');
    include('bloques/enqueue.php');
    include('procesos/save_info.php');
    
     //Hooks
    register_activation_hook(NEWSLETTER_MPJ_PLUGIN_URL, 'mpj_activate_plugin');

    add_action('plugins_loaded', array( 'PageTemplater', 'get_instance' ));
    add_action('wp_footer', 'app_cal_gui');

     //Para Cargar JS del lado del publico de wordpress
    add_action('wp_enqueue_scripts', 'mpj_enqueue_scripts');


    //Ajax
    add_action('wp_ajax_mpj_send_data_news','mpj_save_info');
    add_action('wp_ajax_nopriv_mpj_send_data_news','mpj_save_info');