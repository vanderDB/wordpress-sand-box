/**
 * Display thumb when set as featured.
 *
 * Overwritess built in function.
 */

function WPSetThumbnailID( id ) {
	tb_remove();
	jQuery.post(
		ajaxurl,
		{
			action: "nmi_added_thumbnail",
			thumbnail_id: id,
			post_id: window.clicked_item_id
		},
		function( response ) {
			jQuery( ".pgs_menu_popup-content .nmi-current-image-wrapper" ).html( response );
			tb_remove();
		}
	);
}

jQuery( document ).ready(
	function( $ ) {

			// Save item ID on click on a link.
			$( ".nmi-upload-link" ).click(
				function() {
					window.clicked_item_id = $( this ).parent().parent().children( "#nmi_item_id" ).val();
				}
			);

			// Display alert when not added as featured.
			window.send_to_editor = function( html ) {
				alert( nmi_vars.alert );
				tb_remove();
			};
	}
);
