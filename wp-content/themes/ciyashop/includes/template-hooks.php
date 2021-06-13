<?php
/**
 * Header
 *
 * @see ciyashop_preloader()
 *
 * @see ciyashop_before_header_nav_content_wrapper_start()
 * @see ciyashop_category_menu()
 * @see ciyashop_catmenu_primenu_separator()
 * @see ciyashop_primary_menu()
 * @see ciyashop_after_header_nav_content_wrapper_end()
 *
 * @see ciyashop_logo()
 * @see ciyashop_site_description()
 *
 * @see ciyashop_header_wootools_cart()
 * @see ciyashop_header_wootools_compare()
 * @see ciyashop_header_wootools_wishlist()
 *
 * @package CiyaShop
 */

add_action( 'ciyashop_before_header_wrapper', 'ciyashop_preloader', 10 );

add_action( 'ciyashop_before_header', 'ciyashop_header_style_settings' );

add_action( 'ciyashop_before_header_nav_content', 'ciyashop_before_header_nav_content_wrapper_start', 10 );
add_action( 'ciyashop_header_nav_content', 'ciyashop_category_menu', 10 );
add_action( 'ciyashop_header_nav_content', 'ciyashop_catmenu_primenu_separator', 15 );
add_action( 'ciyashop_header_nav_content', 'ciyashop_primary_menu', 20 );
add_action( 'ciyashop_after_header_nav_content', 'ciyashop_after_header_nav_content_wrapper_end', 10 );

add_action( 'ciyashop_site_title', 'ciyashop_logo', 10 );
add_action( 'ciyashop_before_site_title_wrapper_end', 'ciyashop_site_description', 10 );

add_action( 'ciyashop_sticky_site_title', 'ciyashop_sticky_logo', 10 );
add_action( 'ciyashop_sticky_nav_content', 'ciyashop_sticky_wootools', 10 );
add_action( 'ciyashop_sticky_nav_content', 'ciyashop_sticky_nav', 20 );

if ( class_exists( 'WooCommerce' ) ) {
	add_action( 'ciyashop_header_wootools', 'ciyashop_header_wootools_cart', 10 );
	add_action( 'ciyashop_header_wootools', 'ciyashop_header_wootools_compare', 20 );
	add_action( 'ciyashop_header_wootools', 'ciyashop_header_wootools_wishlist', 30 );

	add_action( 'ciyashop_sticky_header_wootools', 'ciyashop_sticky_header_wootools_cart', 10 );
	add_action( 'ciyashop_sticky_header_wootools', 'ciyashop_sticky_header_wootools_compare', 20 );
	add_action( 'ciyashop_sticky_header_wootools', 'ciyashop_sticky_header_wootools_wishlist', 30 );

	add_action( 'ciyashop_sticky_footer_mobile_device', 'ciyashop_sticky_footer_mobile_home', 10 );
	add_action( 'ciyashop_sticky_footer_mobile_device', 'ciyashop_sticky_footer_mobile_wishlist', 20 );
	add_action( 'ciyashop_sticky_footer_mobile_device', 'ciyashop_sticky_footer_mobile_account', 30 );
	add_action( 'ciyashop_sticky_footer_mobile_device', 'ciyashop_sticky_footer_mobile_cart', 40 );

	add_filter( 'woocommerce_add_to_cart_fragments', 'ciyashop_cart_fragment' );
}

add_action( 'ciyashop_header_search', 'ciyashop_header_search_content', 10 );
add_action( 'ciyashop_search_popup_content', 'ciyashop_search_form', 10 );

/**
 * Content
 *
 * @see ciyashop_page_header()
 */
add_action( 'ciyashop_content_top', 'ciyashop_page_header', 20 );

/**
 * Footer
 *
 * @see ciyashop_footer_main()
 * @see ciyashop_bak_to_top()
 * @see ciyashop_cookie_notice()
 */
add_action( 'ciyashop_footer', 'ciyashop_footer_main', 10 );
add_action( 'ciyashop_after_footer', 'ciyashop_bak_to_top', 10 );
add_action( 'ciyashop_after_page_wrapper', 'ciyashop_cookie_notice', 10 );

/**
 * Pages
 *
 * @see  ciyashop_display_comments()
 */
add_action( 'ciyashop_page_after', 'ciyashop_display_comments', 10 );

/**
 * Footer
 *
 * @see  ciyashop_display_comments()
 */
add_action( 'wp_footer', 'ciyashop_login_form' );

add_action( 'pre_get_posts', 'pre_get_portfolio' );
/**
 * Portfolio query preset
 *
 * @param mixed $query query object.
 * @return $query
 */
function pre_get_portfolio( $query ) {
	global $ciyashop_options;

	if ( ! is_admin() && is_post_type_archive( 'portfolio' ) && $query->is_main_query() ) {
		$query->set( 'post_type', 'portfolio' );
		$query->set( 'paged', get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1 );
		$query->set( 'orderby', ( isset( $ciyashop_options['portfolio_order_by'] ) ) ? $ciyashop_options['portfolio_order_by'] : 'publish_date' );
		$query->set( 'order', ( isset( $ciyashop_options['portfolio_order'] ) ) ? $ciyashop_options['portfolio_order'] : 'ASC' );
		$query->set( 'posts_per_page', ( isset( $ciyashop_options['portfolio_per_page'] ) ) ? $ciyashop_options['portfolio_per_page'] : 6 );
	}

	return $query;
}
