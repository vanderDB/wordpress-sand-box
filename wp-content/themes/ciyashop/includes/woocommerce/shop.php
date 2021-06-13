<?php
/**
 * Shop
 *
 * @package CiyaShop
 */

add_filter( 'woocommerce_show_page_title', 'ciyashop_woocommerce_hide_page_title' ); // Hide Page Title.
/**
 * Remove Shop page title
 */
function ciyashop_woocommerce_hide_page_title() {
	return false;
}

// Remove default breadcrumb.
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );


// Content Wrappers.
add_action( 'woocommerce_before_main_content', 'ciyashop_woocommerce_output_content_wrapper', 10 );
add_action( 'woocommerce_after_main_content', 'ciyashop_woocommerce_output_content_wrapper_end', 10 );

if ( ! function_exists( 'ciyashop_woocommerce_output_content_wrapper' ) ) {
	/**
	 * Output the start of the page wrapper.
	 */
	function ciyashop_woocommerce_output_content_wrapper() {
		if ( empty( get_query_var( 'store' ) ) ) {
			wc_get_template( 'global/wrapper-start.php' );
		}
	}
}

if ( ! function_exists( 'ciyashop_woocommerce_output_content_wrapper_end' ) ) {
	/**
	 * Output the end of the page wrapper.
	 */
	function ciyashop_woocommerce_output_content_wrapper_end() {
		if ( empty( get_query_var( 'store' ) ) ) {
			wc_get_template( 'global/wrapper-end.php' );
		}
	}
}

// Sidebars.
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 ); // Remove Default Sidebars.

// Archive Banner.
add_action( 'woocommerce_archive_description', 'ciyashop_shop_page_banner', 5 );
if ( ! function_exists( 'ciyashop_shop_page_banner' ) ) {

	/**
	 * Show banner above archive description.
	 */
	function ciyashop_shop_page_banner() {
		wc_get_template( 'custom/shop_page_banner.php' );
	}
}

add_filter( 'ciyashop_content_container_classes', 'ciyashop_shop_page_width_class' );
/**
 * Shop Product Container Width
 *
 * @param string $classes .
 */
function ciyashop_shop_page_width_class( $classes ) {

	if ( is_shop() || is_product_category() || is_product_tag() ) {
		$shop_page_width = ciyashop_shop_page_width();

		/**
		 * Unset 'container-fluid' class
		 */
		$cf_index = array_search( 'container-fluid', $classes, true );
		if ( $cf_index ) {
			unset( $classes[ $cf_index ] );
		}

		/**
		 * Unset 'container' class
		 */
		$c_index = array_search( 'container', $classes, true );
		if ( $c_index ) {
			unset( $classes[ $c_index ] );
		}

		if ( 'wide' === $shop_page_width ) {
			$classes = array( 'container-fluid' );
		} else {
			$classes = array( 'container' );
		}
	}

	return $classes;
}

add_action( 'ciyashop_content_top', 'ciyashop_shop_category_carousel', 30 );
/**
 * Content
 *
 * @see ciyashop_shop_category_carousel()
 */
function ciyashop_shop_category_carousel() {
	wc_get_template( 'custom/shop-category-carousel.php' );
}
