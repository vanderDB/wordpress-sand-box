<?php
/**
 * Extend vc tta tour
 *
 * @package CiyaShop
 */

add_action( 'init', 'ciyashop_extend_vc_tta_tour', 9999 );
/**
 * Extend vc tta tour
 */
function ciyashop_extend_vc_tta_tour() {
	if ( ! function_exists( 'vc_add_params' ) ) {
		return;
	}

	$shortcode_handle = 'vc_tta_tour';

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
		'heading'     => esc_html__( 'Icon Alignment', 'ciyashop' ),
		'param_name'  => 'pgs_icon_alignment_top_bottom',
		'description' => esc_html__( 'Check this to display icon top/bottom of title based on left/right alignment.', 'ciyashop' ),
		'group'       => esc_html__( 'Additional Settings', 'ciyashop' ),
	);

	$additional_params = apply_filters( "pgscore_extend_{$shortcode_handle}_additional_params", $additional_params );

	$atts['params'] = array_merge( $atts['params'], $additional_params );

	unset( $atts['base'] );
	vc_map_update( $shortcode_handle, $atts );
}
