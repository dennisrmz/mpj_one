<?php


    /**************************** Filtro En Producto Individual **********************/

    function mpj_filter_add_cart($passed, $product_id, $quantity, $variation_id = '', $variations = '')
    {
        if( $quantity > 1){
            $passed = false;
            $msg = "Lo sentimos, solo puedes llevar una vez cada tipo de aro, si deseas llevar este aro nuevamente realiza otra compra :)";
            wc_add_notice(__($msg, 'textdomain'), 'error');
            return $passed;
        }
        
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

            $product = wc_get_product($cart_item['product_id']);

           $product_id_cart = $cart_item['product_id'];
           $name            = $product->get_name();
           $quantity_cart        = $cart_item['quantity'];
           $price           = $product->get_price();
           $link            = $product->get_permalink( $cart_item );
           $slug            = $product->get_slug();
           $category_id     = $product->get_category_ids();
           
           if($product_id == $product_id_cart){
                $passed = false;
                $msg = "Lo sentimos, solo puedes llevar una vez cada tipo de aro, si deseas llevar este aro nuevamente realiza otra compra :)";
                wc_add_notice(__($msg, 'textdomain'), 'error');
                return $passed;
           }

        };
        return $passed;
    }

    function filter_woocommerce_update_cart_action_cart_updated($true, $cart_item_key, $values, $quantity)
    {
        $true = true;
        if ($quantity > 1) {
            $true = false;
            $msg = "Error: La cantidad maxima de " . $product_name . " disponible por compra es de: 1" ;
            wc_add_notice(__($msg, 'textdomain'), 'error');
            return $true;
        } else {
            $true = true;
            return $true;
        }

        return $true;
    }