<?php
/**
 * Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 * @package CiyaShop
 */

/**
 * If your child theme has more than one .css file (eg. ie.css, style.css, main.css) then
 * you will have to make sure to maintain all of the parent theme dependencies.
 *
 * Make sure you're using the correct handle for loading the parent theme's styles.
 * Failure to use the proper tag will result in a CSS file needlessly being loaded twice.
 * This will usually not affect the site appearance, but it's inefficient and extends your page's loading time.
 *
 * @link https://codex.wordpress.org/Child_Themes
 */
function ciyashop_child_enqueue_styles() { // phpcs:ignore WordPress.WhiteSpace.ControlStructureSpacing.NoSpaceAfterOpenParenthesis

	wp_enqueue_style( 'ciyashop-style', get_parent_theme_file_uri( '/css/style.css' ), array(), '3.5.2' );

	if ( is_rtl() ) {
		wp_enqueue_style( 'rtl-style', get_parent_theme_file_uri( '/rtl.css' ), array(), '3.5.2' );
	}

	wp_enqueue_style(
		'ciyashop-child-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'ciyashop-style' ),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'ciyashop_child_enqueue_styles', 11 );
/*
function woocommerce_custom_price_to_cart_item( $cart_object ) {
    if( !WC()->session->__isset( "reload_checkout" )) {
        foreach ( $cart_object->cart_contents as $key => $value ) {
            if( isset( $value["custom_price"] ) ) {
                $value['data']->set_price($value["custom_price"]);
            }
        }
    }
}add_action( 'woocommerce_before_calculate_totals', 'woocommerce_custom_price_to_cart_item', 99 );
*/