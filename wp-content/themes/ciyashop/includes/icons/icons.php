<?php
/**
 * Flaticon icons
 *
 * @package CiyaShop
 */

add_filter( 'vc_iconpicker-type-flaticon', 'ciyashop_vc_iconpicker_type_flaticon' );

/**
 * Flaticon icons from Flaticon
 *
 * @param string $icons - taken from filter - vc_map param field settings['source'].
 *     provided icons (default empty array). If array categorized it will.
 *     auto-enable category dropdown.
 *
 * @since 3.3.0
 * @return array - of icons for iconpicker, can be categorized, or not.
 */
function ciyashop_vc_iconpicker_type_flaticon( $icons ) {
	$icons_new = include get_parent_theme_file_path( '/includes/icons/flaticon/flaticon.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

	return array_merge( $icons, $icons_new );
}

add_filter( 'vc_iconpicker-type-themefy', 'ciyashop_vc_iconpicker_type_themefy' );

/**
 * Themefy icons from Themefy
 *
 * @param string $icons - taken from filter - vc_map param field settings['source']
 *     provided icons (default empty array). If array categorized it will
 *     auto-enable category dropdown.
 *
 * @since 3.3.0
 * @return array - of icons for iconpicker, can be categorized, or not.
 */
function ciyashop_vc_iconpicker_type_themefy( $icons ) {
	$icons_new = include get_parent_theme_file_path( '/includes/icons/themefy/themefy.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

	return array_merge( $icons, $icons_new );
}

/**
 * Register ALL fonts/styles.
 *
 * @since 3.3.0
 */
function ciyashop_vc_editor_register_custom_css() {
	wp_register_style( 'flaticon', get_parent_theme_file_uri( '/includes/icons/flaticon/flaticon.css' ), false, THEME_VERSION );
	wp_register_style( 'themefy', get_parent_theme_file_uri( '/includes/icons/themefy/themefy.css' ), false, THEME_VERSION );
}
add_action( 'vc_base_register_front_css', 'ciyashop_vc_editor_register_custom_css' );
add_action( 'vc_base_register_admin_css', 'ciyashop_vc_editor_register_custom_css' );
add_action( 'admin_enqueue_scripts', 'ciyashop_vc_editor_register_custom_css' );
add_action( 'wp_enqueue_scripts', 'ciyashop_vc_editor_register_custom_css' );


/**
 * Enqueue ALL fonts/styles for Editor(admin) mode. (to allow easy change icons)
 *
 * @since 3.3.0
 */
function ciyashop_vc_editor_enqueue_custom_css() {
	wp_enqueue_style( 'flaticon' );
	wp_enqueue_style( 'themefy' );
}
add_action( 'vc_backend_editor_enqueue_js_css', 'ciyashop_vc_editor_enqueue_custom_css' );
add_action( 'vc_frontend_editor_enqueue_js_css', 'ciyashop_vc_editor_enqueue_custom_css' );
add_action( 'admin_enqueue_scripts', 'ciyashop_vc_editor_enqueue_custom_css' );
add_action( 'wp_enqueue_scripts', 'ciyashop_vc_editor_enqueue_custom_css' );


/**
 * Enqueue ALL fonts/styles for front.
 *
 * @since 3.3.0
 * @param string $font .
 */
function ciyashop_enqueue_custon_vc_css( $font ) {

	wp_enqueue_style( $font );
}
add_action( 'vc_enqueue_font_icon_element', 'ciyashop_enqueue_custon_vc_css' );
