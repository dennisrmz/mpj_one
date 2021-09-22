<?php 

    function mpj_activate_plugin(){
        
        if( version_compare( get_bloginfo( 'version' ), '5.0', '<' ) ){
            wp_die( __( "Se debe actualizar wordpress para utilizar este plugin.", 'vclobi' ));
        }

        global $wpdb;

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    }

?>