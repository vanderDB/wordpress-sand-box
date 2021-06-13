<?php
/**
 * VC init
 *
 * @package CiyaShop
 */

add_filter( 'ciyashop_content_wrapper_classes', 'ciyashop_add_content_wrapper_vc_enabled_class' );
/**
 * Add content wrapper vc enabled class
 *
 * @param string $classes .
 */
function ciyashop_add_content_wrapper_vc_enabled_class( $classes ) {

	if ( is_home() ) {
		return $classes;
	}

	// Check if VC is enabled and append vc-enabled class.
	if ( ciyashop_is_vc_enabled() ) {
		$classes[] = 'content-wrapper-vc-enabled';
	}

	return $classes;
}

add_filter( 'ciyashop_content_wrapper_classes', 'ciyashop_404_page_add_content_wrapper_vc_enabled_class' );

if ( ! function_exists( 'ciyashop_404_page_add_content_wrapper_vc_enabled_class' ) ) {
	/**
	 * Content wrapper vc enabled class
	 *
	 * @param string $classes .
	 */
	function ciyashop_404_page_add_content_wrapper_vc_enabled_class( $classes ) {
		global $ciyashop_options;

		if ( ! is_404() ) {
			return $classes;
		}

		if ( ! isset( $ciyashop_options['fourofour_page_content_source'] ) || ( isset( $ciyashop_options['fourofour_page_content_source'] ) && 'page' !== $ciyashop_options['fourofour_page_content_source'] ) ) {
			return $classes;
		}

		if ( ! isset( $ciyashop_options['fourofour_page_content_source'] ) || ( isset( $ciyashop_options['fourofour_page_content_page'] ) && '' === $ciyashop_options['fourofour_page_content_page'] ) ) {
			return $classes;
		}

		$fourofour_content_page_id = $ciyashop_options['fourofour_page_content_page'];
		$fourofour_content_page    = get_post( $fourofour_content_page_id );

		if ( ! $fourofour_content_page || 'publish' !== $fourofour_content_page->post_status ) {
			return $classes;
		}

		// Check if VC is enabled and append vc-enabled class.
		if ( ciyashop_is_vc_enabled( $fourofour_content_page_id ) ) {
			$classes[] = 'content-wrapper-vc-enabled';
		}

		return $classes;
	}
}
