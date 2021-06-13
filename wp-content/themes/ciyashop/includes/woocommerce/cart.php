<?php
/**
 * WC cart
 *
 * @package CiyaShop
 */

if ( ! function_exists( 'ciyashop_update_cart_item_details' ) ) {
	function ciyashop_update_cart_item_details() {

		$ciyashop_nonce = isset( $_GET['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['ajax_nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $ciyashop_nonce, 'ciyashop_nonce' ) ) {
			return;
		}

		global $woocommerce;

		if ( isset( $_GET['item_id'] ) && $_GET['item_id'] ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			if ( isset( $_GET['qty'] ) && $_GET['qty'] ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				$woocommerce->cart->set_quantity( sanitize_text_field( wp_unslash( $_GET['item_id'] ) ), (int) $_GET['qty'] );
			} else {
				$woocommerce->cart->remove_cart_item( sanitize_text_field( wp_unslash( $_GET['item_id'] ) ) );
			}
		}

		WC_AJAX::get_refreshed_fragments();
		wp_die();
	}
}

add_action( 'wp_ajax_ciyashop_update_cart_item_details', 'ciyashop_update_cart_item_details' );
add_action( 'wp_ajax_nopriv_ciyashop_update_cart_item_details', 'ciyashop_update_cart_item_details' );

remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );

add_action( 'woocommerce_before_checkout_form', 'ciyashop_row_start', 9 );
if ( ! function_exists( 'ciyashop_row_start' ) ) {
	/**
	 * Row start
	 */
	function ciyashop_row_start() {
		?>
		<div class="row">
		<?php
	}
}

add_action( 'woocommerce_before_checkout_form', 'ciyashop_row_end', 11 );
if ( ! function_exists( 'ciyashop_row_end' ) ) {
	/**
	 * Row end
	 */
	function ciyashop_row_end() {
		?>
		</div> 
		<?php
	}
}

add_filter( 'woocommerce_cross_sells_columns', 'ciyashop_change_cross_sells_columns' );

if ( ! function_exists( 'ciyashop_change_cross_sells_columns' ) ) {
	/**
	 * Change scross sells columns
	 *
	 * @param int $columns .
	 */
	function ciyashop_change_cross_sells_columns( $columns ) {
		return 4;
	}
}
