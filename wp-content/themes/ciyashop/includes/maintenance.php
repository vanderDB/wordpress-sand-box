<?php
/**
 * Check to see if the current page is the login/register page
 * Use this in conjunction with is_admin() to separate the front-end from the back-end of your theme
 *
 * @return bool
 * @package CiyaShop
 */

if ( ! function_exists( 'ciyashop_is_login_page' ) ) {
	/**
	 * Ciyashop is login page
	 */
	function ciyashop_is_login_page() {
		return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ), true );
	}
}

add_action( 'init', 'ciyashop_under_maintenance', 21 );
/**
 * Check if site is in undermaintatance.
 */
function ciyashop_under_maintenance() {
	global $ciyashop_options;

	if ( is_admin() || ciyashop_is_login_page() || ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) ) {
		return;
	}

	/**
	 * Fires before maintenance content.
	 *
	 * @visible true
	 */
	do_action( 'ciyashop_maintenance_before' );

	$enable_maintenance = isset( $ciyashop_options['enable_maintenance'] ) ? $ciyashop_options['enable_maintenance'] : 0;

	if ( $enable_maintenance ) {
		add_filter( 'body_class', 'ciyashop_maintenance_body_class' );

		$maintenance_mode = $ciyashop_options['maintenance_mode'];
		if ( empty( $maintenance_mode ) ) {
			$maintenance_mode = 'maintenance';
		}
		if ( ! ( current_user_can( 'administrator' ) || current_user_can( 'manage_network' ) ) ) {
			get_template_part( 'template-parts/maintenance/maintenance' );
			exit();
		}
	}
}
/**
 * Ciyashop maintenance body class
 *
 * @param string $classes .
 */
function ciyashop_maintenance_body_class( $classes ) {
	global $ciyashop_options;

	if ( is_admin() || ciyashop_is_login_page() ) {
		return $classes;
	}

	$enable_maintenance = isset( $ciyashop_options['enable_maintenance'] ) ? $ciyashop_options['enable_maintenance'] : 0;
	if ( ! current_user_can( 'administrator' ) && $enable_maintenance ) {

		$maintenance_mode = $ciyashop_options['maintenance_mode'];
		if ( empty( $maintenance_mode ) ) {
			$maintenance_mode = 'maintenance';
		}

		$classes[] = 'tc_maintenance';
		$classes[] = 'tc_maintenance_mode-' . $maintenance_mode;
	}
	return $classes;
}
