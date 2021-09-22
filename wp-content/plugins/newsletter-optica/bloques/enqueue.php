<?php 

function mpj_enqueue_scripts(){

    wp_register_script(
        'mpj_script_pop_up', 
        plugins_url( '/assets/pop-up-mpj.js', NEWSLETTER_MPJ_PLUGIN_URL ), 
        ['jquery'], 
        '1.0.0', 
        true );

    wp_enqueue_script('mpj_script_pop_up');

}
