(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	 

	/**
	 * WCOrdersTable class.
	 */
	var EasyNoteForWCObj = function() {
		$( document )
			.on( 'click', '.order-notes:not(.disabled)', this.onPreviewNotes );
		
		$( document )
			.on( 'click', 'button.bgbn_wc_cm_add_note', this.add_order_note )
			.on( 'click', 'a.bgbn_wc_cm_delete_note', this.delete_order_note );
	};

	/**
	 * Preview an order.
	 */
	 EasyNoteForWCObj.prototype.onPreviewNotes = function() { 
		var $previewButton    = $( this ),
			$order_id         = $previewButton.data( 'orderId' );

		/*if ( $previewButton.data( 'order-data' ) ) {
			$( this ).WCBackboneModal({
				template: 'wc-modal-view-order',
				variable : $previewButton.data( 'orderData' )
			});
		} else {*/
			$previewButton.addClass( 'disabled' );

			$.ajax({
				url:     wc_orders_params.ajax_url,
				data:    {
					order_id: $order_id,
					action  : 'woocommerce_get_order_details',
					security: wc_orders_params.preview_nonce
				},
				type:    'GET',
				success: function( response ) { 
					$( '.order-notes' ).removeClass( 'disabled' );

					if ( response.success ) {
						$previewButton.data( 'orderData', response.data );

						$( this ).WCBackboneModal({
							template: 'wc-modal-view-order',
							variable : response.data
						});

					}
				}
			});
		//}
		return false;

	};

	EasyNoteForWCObj.prototype.delete_order_note = function() { 
		if ( window.confirm( woocommerce_admin_meta_boxes.i18n_delete_note ) ) {
			var note = $( this ).closest( 'li.note' );

			$( note ).block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});

			var data = {
				action:   'woocommerce_delete_order_note',
				note_id:  $( note ).attr( 'rel' ),
				security: woocommerce_admin_meta_boxes.delete_order_note_nonce
			};

			$.post( woocommerce_admin_meta_boxes.ajax_url, data, function() {
				$( note ).remove();
			});
		}

		return false;
	};

	EasyNoteForWCObj.prototype.add_order_note = function() { 
		if ( ! $( 'textarea#add_order_note' ).val() ) {
			return;
		}

		$( '#woocommerce-order-notes' ).block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});

		var data = {
			action:    'woocommerce_add_order_note',
			post_id:   woocommerce_admin_meta_boxes.post_id,
			note:      $( 'textarea#add_order_note' ).val(),
			note_type: $( 'select#order_note_type' ).val(),
			security:  woocommerce_admin_meta_boxes.add_order_note_nonce
		};

		$.post( woocommerce_admin_meta_boxes.ajax_url, data, function( response ) {
			$( 'ul.order_notes .no-items' ).remove();
			$( 'ul.order_notes' ).prepend( response );
			$( '#woocommerce-order-notes' ).unblock();
			$( '#add_order_note' ).val( '' );
			window.wcTracks.recordEvent( 'order_edit_add_order_note', {
				order_id: woocommerce_admin_meta_boxes.post_id,
				note_type: data.note_type || 'private',
				status: $( '#order_status' ).val()
			} );
		});

		return false;
	},

	/**
	 * Init WCOrdersTable.
	 */
	new EasyNoteForWCObj();
})( jQuery );
