<?php 
    
    function mpj_save_info(){

        global $wpdb;
        $data_get_ajax = $_POST['data'];

        $email = isset($data_get_ajax['email']) ? $data_get_ajax['email'] : "";
        $phone = isset($data_get_ajax['phone']) ? $data_get_ajax['phone'] : "";


        $table_contacts = $wpdb->prefix ."mpj_news_contacts";
        $data_contact = array(
            "email"         => $email,
            "phone_number"  => $phone

        );

        $format = array('%s','%s');

        $result = $wpdb->insert($table_contacts, $data_contact, $format);
        

        $output = [
            'code' => 1,
            'message' => 'Insersion Exitosa',
            'nose' => $data_get_ajax['phone']
        ];




        wp_send_json($output);
    }