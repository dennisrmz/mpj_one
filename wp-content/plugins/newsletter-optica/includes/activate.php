<?php 

    function mpj_activate_plugin(){
        
        if( version_compare( get_bloginfo( 'version' ), '5.0', '<' ) ){
            wp_die( __( "Se debe actualizar wordpress para utilizar este plugin.", 'vclobi' ));
        }

        global $wpdb;

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $tablesSQL = "";
        
        $charset = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix ."mpj_news_contacts";

        $tablesSQL = "CREATE TABLE IF NOT EXISTS $table_name (
            id INT NOT NULL AUTO_INCREMENT,
            email varchar(500) DEFAULT NULL,
            phone_number varchar(50) DEFAULT NULL,
            activate boolean DEFAULT 1,
            created_at timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY  (id)
        )ENGINE=InnoDB $charset;";
        $result = dbDelta( $tablesSQL );


    }

?>