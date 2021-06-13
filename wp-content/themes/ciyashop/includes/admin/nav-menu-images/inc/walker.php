<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Nav Menu Images Nav Menu Edit Walker
 *
 * @package Nav Menu Images
 * @subpackage Nav Menu Edit Walker
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter nav menu items on edit screen.
 *
 * @since 1.0
 *
 * @uses Walker_Nav_Menu_Edit
 */
class NMI_Walker_Nav_Menu_Edit extends Walker_Nav_Menu_Edit {

	/**
	 * Walker menu
	 *
	 * @see Walker_Nav_Menu_Edit::start_el()
	 * @since 1.0
	 * @access public
	 *
	 * @global $wp_version
	 * @uses Walker_Nav_Menu_Edit::start_el()
	 * @uses admin_url() To get URL of uploader.
	 * @uses esc_url() To escape URL.
	 * @uses add_query_arg() To append variables to URL.
	 * @uses esc_attr() To escape string.
	 * @uses has_post_thumbnail() To check if item has thumb.
	 * @uses get_the_post_thumbnail() To get item's thumb.
	 * @uses version_compare() To compare WordPress versions.
	 * @uses wp_create_nonce() To create item's nonce.
	 * @uses esc_html__() To translate & escape string.
	 * @uses esc_html() To escape string.
	 * @uses do_action_ref_array() Calls 'nmi_menu_item_walker_output' with the output.
	 *                        post object, depth and arguments to overwrite item's output.
	 * @uses NMI_Walker_Nav_Menu_Edit::get_settings() To get JSONed item's data.
	 * @uses do_action_ref_array() Calls 'nmi_menu_item_walker_end' with the output.
	 *                        post object, depth and arguments to overwrite item's output.
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int    $depth Depth of menu item. Used for padding.
	 * @param array  $args Not used.
	 * @param int    $id Not used.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		global $wp_version;

		// First, make item with standard class.
		parent::start_el( $output, $item, $depth, $args, $id );

		// Now add additional content.
		$item_id = $item->ID;

		// Hidden field with item's ID.
		$output .= '<input type="hidden" name="nmi_item_id" id="nmi_item_id" value="' . esc_attr( $item_id ) . '" />';
	}
}
