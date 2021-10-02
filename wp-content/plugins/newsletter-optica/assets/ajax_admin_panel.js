//alert('Hola');

(function ($) {
    $(document).ready(function () {
        //Definicion de variables globales
        console.log('funca');
        $( '#wcf_ca_custom_filter_from' )
				.datepicker( {
					dateFormat: 'yy-mm-dd'
				} )
				.attr( 'readonly', 'readonly' )
				.css( 'background', 'white' );

			$( '#wcf_ca_custom_filter_to' )
				.datepicker( {
					dateFormat: 'yy-mm-dd'
				} )
				.attr( 'readonly', 'readonly' )
				.css( 'background', 'white' );

			$( '.dropdown-item' ).on( 'click', function (e) {
                e.preventDefault();
				var from = $( '#wcf_ca_custom_filter_from' ).val().trim();
				var to = $( '#wcf_ca_custom_filter_to' ).val().trim();
				var url = $(".url-site-pdf").attr('href');
				url =
					url +
					'&from_date=' +
					from +
					'&to_date=' +
					to +
					'&filter=custom';
				window.location.href = url;
			} );

    });//Fin OnReady
})(jQuery);