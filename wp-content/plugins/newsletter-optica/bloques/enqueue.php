<?php 

function mpj_get_current_id_product(){
    global $post;
    return $post->ID;
}

function CartInfo(){
    $dataInfo = [];

        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

            $product = wc_get_product($cart_item['product_id']);

           $product_id      = $cart_item['product_id'];
           $name            = $product->get_name();
           $quantity        = $cart_item['quantity'];
           $price           = $product->get_price();
           $link            = $product->get_permalink( $cart_item );
           $slug            = $product->get_slug();
           $category_id     = $product->get_category_ids();
           


           $row = [
            'id'        => $product_id,
            'name'      => $name,
            'count'     => $quantity,
            'precio'    => $price,
            'uri'       => $link,
            'slug'      => $slug
           ];

            array_push($dataInfo,$row);
        };

    return $dataInfo;
}

// Asset y funciones que cargan del lado de frontend de wordpress
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

    wp_register_script(
        'mpj_script_product_add_ons', 
        plugins_url( '/assets/mpj_products_add_ons.js', NEWSLETTER_MPJ_PLUGIN_URL ), 
        ['jquery'], 
        '1.0.0', 
        true 
    );
    
    $limites_graduacion = array();
    
    $args = array(
        'post_type' => 'opticampj_add_ons',
        'nopaging'  => true
    );

    $posts_type_limites = new WP_Query($args);

    while( $posts_type_limites->have_posts() ): $posts_type_limites->the_post();
        if(get_field('tipo_add_on') == "tipo_aumento"):

            $id = get_the_ID();
            $nombre = get_field('nombre');
            $precio_extra = get_field('precio_extra');
            $min = get_field('valor_inferior_rango_aumento');
            $max = get_field('valor_superior_rango_aumento');
            
            $datos = [
                "id"    => $id,
                "nombre"  => $nombre,
                "precio_extra" => $precio_extra,
                "min_limit" => $min,
                "max_limit" => $max
            ];
            array_push($limites_graduacion, $datos);

        endif;
    endwhile;

    wp_reset_postdata();
     
    if(is_front_page()){
        wp_enqueue_script('mpj_script_pop_up');
    }

    if(is_product() || is_checkout() || is_cart() || is_shop()){
        wp_enqueue_script('mpj_script_product_add_ons');
    }


    wp_localize_script( 'mpj_main', 'mpj_obj', [
        'ajax_url'              =>  admin_url( 'admin-ajax.php' ),
        'home_url'              =>  home_url('/'),
        'mpj_current_prod'      =>  mpj_get_current_id_product(),
        'limites_rango'         =>  $limites_graduacion,
        'products_in_cart'      =>  CartInfo()
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


