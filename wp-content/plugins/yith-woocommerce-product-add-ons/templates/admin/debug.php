<?php
/**
 * Debug Template
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

global $wpdb;

?>

<div id="plugin-fw-wc" class="yit-admin-panel-content-wrap yith-plugin-ui yith-wapo">
	<div id="yith_wapo_panel_debug" class="yith-plugin-fw yit-admin-panel-container">
		<div class="yith-plugin-fw-panel-custom-tab-container">

			<div class="list-table-title">
				<h2><?php echo esc_html__( 'Debug', 'yith-woocommerce-product-add-ons' ); ?></h2>
			</div>

			Debug

		</div>
	</div>
</div>
