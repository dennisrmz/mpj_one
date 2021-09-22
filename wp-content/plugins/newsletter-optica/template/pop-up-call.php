<?php
	function app_cal_gui(){
            ob_start();
            include ABSPATH . 'wp-content/plugins/newsletter-optica/template/pop-up-gui.php';
            $page = ob_get_contents();
            ob_end_clean();
            echo $page;
	} 
?>