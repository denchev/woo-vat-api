/*global woocommerce_admin_meta_boxes, woocommerce_admin, accounting, woocommerce_admin_meta_boxes_order */
jQuery( function ( $ ) {

	/**
	 * Order Data Panel
	 */
	var wc_meta_boxes_order = {
		states: null,
		init: function() {
			$('#_billing_email').on('change', this.load_billing);
		},

		load_billing: function( force ) {

			// Get user ID to load data for
			var user_id = $( '#customer_user' ).val();

			if ( ! user_id ) {
				window.alert( woocommerce_admin_meta_boxes.no_customer_selected );
				return false;
			}

			var data = {
				user_id:      user_id,
				type_to_load: 'billing',
				action:       'woocommerce_get_customer_details',
				security:     woocommerce_admin_meta_boxes.get_customer_details_nonce
			};

			$( this ).closest( 'div.edit_address' ).block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});

			$.ajax({
				url: woocommerce_admin_meta_boxes.ajax_url,
				data: data,
				type: 'POST',
				success: function( response ) {
					var info = response;

					if ( info ) {
						$( 'input#_billing_eu_vat_number').val(info.billing_eu_vat_number).change();
					}

					$( 'div.edit_address' ).unblock();
				}
			});

			return false;
		},
	};

	wc_meta_boxes_order.init();
});
