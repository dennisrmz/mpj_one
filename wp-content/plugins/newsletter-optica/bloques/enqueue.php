<?php 

function mpj_enqueue_scripts(){

    wp_register_style('mpj_style',plugins_url('assets/mpj_style.css', NEWSLETTER_MPJ_PLUGIN_URL));
    wp_enqueue_style('mpj_style');
    
    wp_register_script(
        'mpj_main', 
        plugins_url( '/assets/main.js', NEWSLETTER_MPJ_PLUGIN_URL ), 
        ['jquery'], 
        '1.0.0', 
        true );

    wp_register_script(
        'mpj_script_pop_up', 
        plugins_url( '/assets/pop-up-mpj.js', NEWSLETTER_MPJ_PLUGIN_URL ), 
        ['jquery'], 
        '1.0.0', 
        true );
     
    if(is_front_page()){
        wp_enqueue_script('mpj_script_pop_up');
    }

    wp_localize_script( 'mpj_main', 'mpj_obj', [
        'ajax_url'              =>  admin_url( 'admin-ajax.php' ),
        'home_url'              =>  home_url('/')
    ]);
    
    wp_enqueue_script( 'mpj_main' );
    

}


//estos assets se cargan del lado del admin panel del wordpress
function enqueue_my_script(){
    global $wpdb;

    wp_enqueue_script( 'jquery-ui-datepicker' );
    wp_enqueue_style( 'cartflows-cart-users', plugins_url('assets/css/admin-cart-rename.css',NEWSLETTER_MPJ_PLUGIN_URL));
    wp_enqueue_style('vc_appform_style-users',plugins_url('assets/css/jquery-ui.css',NEWSLETTER_MPJ_PLUGIN_URL));
    
    wp_register_script(
        'mpj_main_admin_panel', 
        plugins_url( '/assets/main_admin_panel.js', NEWSLETTER_MPJ_PLUGIN_URL ), 
        ['jquery'], 
        '1.0.0', 
        true );

    wp_register_script(
        'mpj_ajax_admin_panel', 
        plugins_url( '/assets/ajax_admin_panel.js', NEWSLETTER_MPJ_PLUGIN_URL ), 
        ['jquery'], 
        '1.0.0', 
        true );

         

        wp_localize_script( 'mpj_main_admin_panel', 'mpj_obj_admin_panel', [
            'ajax_url'      =>  admin_url( 'admin-ajax.php' ),
            'home_url'      =>  home_url('/')
        ]);
        
        wp_enqueue_script( 'mpj_main_admin_panel' );

        $page = isset($_GET['page']) ? $_GET['page'] : "";

        if($page == "owt-list-table"):
        
            wp_enqueue_script('mpj_ajax_admin_panel');
            
        endif;
}


