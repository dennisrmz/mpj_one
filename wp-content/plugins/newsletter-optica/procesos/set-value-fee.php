<?php
/**
 * @snippet       WooCommerce Add fee to checkout for a gateway ID
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 3.7
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
  
// Part 1: assign fee
  
add_action( 'woocommerce_cart_calculate_fees', 'bbloomer_add_checkout_fee_for_gateway' );

function bbloomer_add_checkout_fee_for_gateway() {
    $chosen_gateway = WC()->session->get( 'fee' );
      WC()->cart->add_fee( 'Valor de Complementos', $chosen_gateway );
}

  
// Part 2: reload checkout on payment gateway change
  
add_action( 'woocommerce_review_order_before_payment', 'bbloomer_refresh_checkout_on_payment_methods_change' );
  
function bbloomer_refresh_checkout_on_payment_methods_change(){
    ?>
    <script type="text/javascript">
        (function($){
              $('body').trigger('update_checkout');
            
        })(jQuery);
    </script>
    <?php
}