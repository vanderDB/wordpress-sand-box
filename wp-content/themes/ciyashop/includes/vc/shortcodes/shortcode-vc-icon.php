<?php
/**
 * Shortcode vc icon
 *
 * @package CiyaShop
 */

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( '-1' );
}

add_action( 'init', 'ciyashop_extend_vc_icon', 1000 );
/**
 * Eextend vc icon
 */
function ciyashop_extend_vc_icon() {
	if ( ! function_exists( 'vc_add_params' ) ) {
		return;
	}

	$replacing_params = array();

	$replacing_params['type'] = array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Icon library', 'ciyashop' ),
		'value'       => array(
			esc_html__( 'Font Awesome', 'ciyashop' ) => 'fontawesome',
			esc_html__( 'Open Iconic', 'ciyashop' )  => 'openiconic',
			esc_html__( 'Typicons', 'ciyashop' )     => 'typicons',
			esc_html__( 'Entypo', 'ciyashop' )       => 'entypo',
			esc_html__( 'Linecons', 'ciyashop' )     => 'linecons',
			esc_html__( 'Mono Social', 'ciyashop' )  => 'monosocial',
			esc_html__( 'Material', 'ciyashop' )     => 'material',
			esc_html__( 'Flaticon', 'ciyashop' )     => 'flaticon',
			esc_html__( 'Themify', 'ciyashop' )      => 'themefy',
		),
		'admin_label' => true,
		'param_name'  => 'type',
		'description' => esc_html__( 'Select icon library.', 'ciyashop' ),
	);

	$atts = vc_get_shortcode( 'vc_icon' );

	foreach ( $atts['params'] as $param_key => $param_data ) {
		if ( array_key_exists( $param_data['param_name'], $replacing_params ) ) {
			$atts['params'][ $param_key ] = $replacing_params[ $param_data['param_name'] ];
		}
	}

	// Add new fields.
	$params = array(
		array(
			'type'        => 'iconpicker',
			'heading'     => esc_html__( 'Icon', 'ciyashop' ),
			'param_name'  => 'icon_flaticon',
			'value'       => 'glyph-icon flaticon-right-arrow-1',
			'settings'    => array(
				'emptyIcon'    => false,
				'type'         => 'flaticon',
				'iconsPerPage' => 200,
			),
			'dependency'  => array(
				'element' => 'type',
				'value'   => 'flaticon',
			),
			'description' => esc_html__( 'Select icon from library.', 'ciyashop' ),
		),
		array(
			'type'       => 'iconpicker',
			'heading'    => esc_html__( 'Icon', 'ciyashop' ),
			'param_name' => 'icon_themefy',
			'value'      => 'ti-arrow-up',
			'settings'   => array(
				'emptyIcon'    => false,
				'type'         => 'themefy',
				'iconsPerPage' => 200,
			),
			'dependency' => array(
				'element' => 'type',
				'value'   => 'themefy',
			),
		),
	);

	array_splice( $atts['params'], 8, 0, $params );

	unset( $atts['base'] );
	vc_map_update( 'vc_icon', $atts );
}
