<?php
/**
 * Shortcode vc accordion
 *
 * @package CiyaShop
 */

add_action( 'init', 'ciyashop_extend_vc_tta_accordion', 9999 );
/**
 * Extend vc tta accordion
 */
function ciyashop_extend_vc_tta_accordion() {
	if ( ! function_exists( 'vc_add_params' ) ) {
		return;
	}

	$shortcode_handle = 'vc_tta_accordion';

	$replacing_params = array();

	$replacing_params = apply_filters( "pgscore_extend_{$shortcode_handle}_replacing_params", $replacing_params );

	$atts = vc_get_shortcode( $shortcode_handle );

	if ( ! empty( $replacing_params ) && is_array( $replacing_params ) ) {
		foreach ( $atts['params'] as $param_key => $param_data ) {
			if ( array_key_exists( $param_data['param_name'], $replacing_params ) ) {
				$atts['params'][ $param_key ] = $replacing_params[ $param_data['param_name'] ];
			}
		}
	}

	$additional_params = array();

	$additional_params[] = array(
		'type'        => 'checkbox',
		'heading'     => esc_html__( 'Boxed Icon', 'ciyashop' ),
		'param_name'  => 'pgscore_boxed_icon',
		'description' => esc_html__( 'Check this to display icon surrounded with box.', 'ciyashop' ),
		'group'       => esc_html__( 'Additional Settings', 'ciyashop' ),
	);

	$additional_params = apply_filters( "pgscore_extend_{$shortcode_handle}_additional_params", $additional_params );

	$atts['params'] = array_merge( $atts['params'], $additional_params );

	unset( $atts['base'] );
	vc_map_update( $shortcode_handle, $atts );
}


add_filter( 'vc_tta_accordion_general_classes', 'ciyashop_extend_vc_tta_accordion_general_classes', 999, 4 );
/**
 * Extend vc tta accordion general classes
 *
 * @param string $classes .
 * @param string $atts .
 */
function ciyashop_extend_vc_tta_accordion_general_classes( $classes, $atts ) {

	if ( isset( $atts['pgscore_boxed_icon'] ) ) {
		$atts_pgscore_boxed_icon = trim( $atts['pgscore_boxed_icon'] );
		if ( ! empty( $atts_pgscore_boxed_icon ) ) {
			$classes[] = 'pgscore_boxed_icon';
		}
	}

	return $classes;
}
