<?php

function mpj_save_info()
{

    global $wpdb;
    $data_get_ajax = $_POST['data'];

    $email = isset($data_get_ajax['email']) ? $data_get_ajax['email'] : "";
    $phone = isset($data_get_ajax['phone']) ? $data_get_ajax['phone'] : "";

    $table_contacts = $wpdb->prefix . "mpj_news_contacts";
    $data_contact = array(
        "email" => $email,
        "phone_number" => $phone,

    );

    $format = array('%s', '%s');

    $result = $wpdb->insert($table_contacts, $data_contact, $format);

    $output = [
        'code' => 1,
        'message' => 'Insersion Exitosa',
        'nose' => $data_get_ajax['phone'],
    ];

    wp_send_json($output);
}

function mpj_get_cart()
{
    $dataInfo = [];

    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {

        $product = wc_get_product($cart_item['product_id']);

        $product_id = $cart_item['product_id'];
        $name = $product->get_name();
        $quantity = $cart_item['quantity'];
        $price = $product->get_price();
        $link = $product->get_permalink($cart_item);
        $slug = $product->get_slug();
        $category_id = $product->get_category_ids();

        $row = [
            'id' => $product_id,
            'name' => $name,
            'count' => $quantity,
            'precio' => $price,
            'uri' => $link,
            'slug' => $slug,
        ];

        array_push($dataInfo, $row);
    };

    wp_send_json($dataInfo);
}
