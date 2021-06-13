<?php
/**
 * Visual Composer
 *
 * @package CiyaShop
 */

/*
 * Prevent Visual Composer Redirection after plugin activation
 */
remove_action( 'admin_init', 'vc_page_welcome_redirect', 9999 );

/*
 * Force Visual Composer to initialize as "built into the theme". This will hide certain tabs under the Settings->Visual Composer page
 * http://tgmpluginactivation.com/faq/updating-bundled-visual-composer/
 * https://wpbakery.atlassian.net/wiki/pages/viewpage.action?pageId=524297
 */
add_action( 'vc_before_init', 'ciyashop_vc_set_as_theme' );
/**
 * Ciyashop vc set as theme
 */
function ciyashop_vc_set_as_theme() {
	vc_set_as_theme();

	$vc_supported_cpts = array(
		'page',
		'post',
	);
	vc_set_default_editor_post_types( $vc_supported_cpts );
}

add_action( 'admin_init', 'ciyashop_hide_vc_activation_nag' );
/**
 * Ciyashop hide vc activation nag
 */
function ciyashop_hide_vc_activation_nag() {
	if ( is_admin() ) {
		setcookie( 'vchideactivationmsg', '1', strtotime( '+3 years' ), '/' );
		setcookie( 'vchideactivationmsg_vc11', ( defined( 'WPB_VC_VERSION' ) ? WPB_VC_VERSION : '1' ), strtotime( '+3 years' ), '/' );
	}
}

/************************************************************************************
 *
 * Revolution Slider
 *
 *********************************************************************************** */

add_filter( 'theme_page_templates', 'ciyashop_remove_revslider_template', 11 );
/**
 * Remove Revolution Slider custom template.
 *
 * @param string $post_templates .
 */
function ciyashop_remove_revslider_template( $post_templates ) {
	unset( $post_templates['../public/views/revslider-page-template.php'] );

	return $post_templates;
}

/************************************************************************************
 *
 * Breadcrumb NavXT
 *
 *********************************************************************************** ssss*/

if ( function_exists( 'bcn_display' ) ) {
	/**
	 * Remove the blog from the 404 and search breadcrumb trail
	 *
	 * @param string $trail .
	 */
	function ciyashop_wpst_override_breadcrumb_trail( $trail ) {
		if ( is_404() || is_search() ) {
			unset( $trail->trail[1] );
			array_keys( $trail->trail );
		}
	}

	add_action( 'bcn_after_fill', 'ciyashop_wpst_override_breadcrumb_trail' );
}

/************************************************************************************
 *
 * Redux Framework
 *
 *********************************************************************************** */
add_filter( 'redux/ciyashop_options/aURL_filter', 'ciyashop_remove_redux_ads' );
/**
 * Remove Redux Framework ads in Backend
 *
 * @param string $redux_ads .
 */
function ciyashop_remove_redux_ads( $redux_ads ) {
	return false;
}
