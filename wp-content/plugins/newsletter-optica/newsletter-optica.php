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
    
    function content_before_product(){

    //     echo "<div id='yith-wapo-option-3-0' class='yith-wapo-option' data-replace-image=''>

    //     <div class='image position-above'>
    //         <img src='http://localhost/mpj_one/wp-content/uploads/2021/08/petr-sevcovic-e12wQLAjQi0-unsplash-3-edited.jpg'>
    //     </div>
    
    
    //     <span class='radiobutton '>
    
    //         <!-- INPUT -->
    //         <input type='radio' id='yith-wapo-3-0' name='yith_wapo[][3]' value='0' data-price='75' data-price-sale='0' data-price-type='fixed' data-price-method='increase' data-first-free-enabled='' data-first-free-options='' data-addon-id='3'>
    
    //     </span>
    
    //     <!-- RIGHT IMAGE -->
        
    //     <!-- LABEL -->
    //     <label for='yith-wapo-3-0'>
    //         Monofocal		
    //         <!-- PRICE -->
    //         <small class='option-price'><span class='brackets'>(</span><span class='sign positive'>+</span><span class='woocommerce-Price-amount amount'><span class='woocommerce-Price-currencySymbol'>$</span>75.00</span><span class='brackets'>)</span></small>	</label>
    
    //             <span class='tooltip position-' style='width: 100%'>
    //             <span>Para edades entre 1 - 39 anios</span>
    //         </span>
        
    //     <!-- UNDER IMAGE -->
        
    //     <!-- DESCRIPTION -->
    //             <p class='description'>Monofocal Desc</p>
        
    // </div>";
    echo "hola";

    
    }