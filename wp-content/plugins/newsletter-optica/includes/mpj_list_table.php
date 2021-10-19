<?php

add_action("admin_menu", "wpl_owt_list_table_menu");

    function wpl_owt_list_table_menu(){
        add_menu_page("OWT List Table", 'Lista Contactos Newsletter', "manage_options", "owt-list-table", "wpl_owt_list_table_fn",'dashicons-admin-users');
        add_submenu_page( 'owt-list-table', 'Add Ons Actuales', 'Add Ons Actuales', "manage_options", 'edit.php?post_type=opticampj_add_ons', '',1);
    }

    function wpl_owt_list_table_fn()
    {   
        ob_start();

        include ABSPATH . 'wp-content/plugins/newsletter-optica/template/owt-table-list.php';
        
        $template = ob_get_contents();

        ob_end_clean();

        echo $template;
    }