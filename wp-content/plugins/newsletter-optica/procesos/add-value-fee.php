<?php

    function add_checkout_fee(){

        session_start();
        $valor = $_POST['valor'];
        WC()->session->set('fee', $valor);
        // WC()->cart->add_fee('valor', 5);
        $var = WC()->session->get( 'fee' );
        
        $salida = [
            'mensaje' => 'No hay registros recuperados',
            'codigo' => 0
        ];
        // echo do_shortcode('[woocommerce_cart]');
	    wp_send_json($salida);

    //wp_send_json($salida);
}