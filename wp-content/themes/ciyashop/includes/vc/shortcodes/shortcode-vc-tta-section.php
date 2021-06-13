<?php
/**
 * Shortcode vc section
 *
 * @package CiyaShop
 */

add_action( 'init', 'ciyashop_extend_vc_tta_section', 1000 );
/**
 * Extend vc tta section
 */
function ciyashop_extend_vc_tta_section() {
	if ( ! function_exists( 'vc_add_params' ) ) {
		return;
	}

	$replacing_params               = array();
	$replacing_params['i_position'] = array(
		'type'        => 'dropdown',
		'param_name'  => 'i_position',
		'value'       => array(
			esc_html__( 'Before title', 'ciyashop' ) => 'left',
			esc_html__( 'After title', 'ciyashop' )  => 'right',
		),
		'dependency'  => array(
			'element' => 'add_icon',
			'value'   => 'true',
		),
		'heading'     => esc_html__( 'Icon position', 'ciyashop' ),
		'description' => esc_html__( 'Select icon position.', 'ciyashop' ),
	);
	$replacing_params['i_type']     = array(
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
		),
		'admin_label' => true,
		'param_name'  => 'i_type',
		'description' => esc_html__( 'Select icon library.', 'ciyashop' ),
	);
	$replacing_params['el_class']   = array(
		'type'        => 'iconpicker',
		'heading'     => esc_html__( 'Icon', 'ciyashop' ),
		'param_name'  => 'i_icon_flaticon',
		'value'       => 'glyph-icon flaticon-right-arrow-1',
		'settings'    => array(
			'emptyIcon'    => false,
			'type'         => 'flaticon',
			'iconsPerPage' => 4000,
		),
		'dependency'  => array(
			'element' => 'i_type',
			'value'   => 'flaticon',
		),
		'description' => esc_html__( 'Select icon from library.', 'ciyashop' ),
	);

	$atts = vc_get_shortcode( 'vc_tta_section' );
	foreach ( $atts['params'] as $param_key => $param_data ) {
		if ( array_key_exists( $param_data['param_name'], $replacing_params ) ) {
			$atts['params'][ $param_key ] = $replacing_params[ $param_data['param_name'] ];
		}
	}

	$params = array(
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Extra class name', 'ciyashop' ),
			'param_name'  => 'el_class',
			'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'ciyashop' ),
		),
	);

	$atts['params'] = array_merge( $atts['params'], $params );
	unset( $atts['base'] );
	vc_map_update( 'vc_tta_section', $atts );
}

add_filter( 'vc-tta-get-params-tabs-list', 'ciyashop_extend_vc_tta_tour_template', 999, 4 );
/**
 * Extend_vc_tta_tour_template
 *
 * @param string $html .
 * @param string $atts .
 * @param string $content .
 * @param string $that .
 */
function ciyashop_extend_vc_tta_tour_template( $html, $atts, $content, $that ) {
	if ( isset( $atts['tab_position'] ) ) {
		if ( 'left' === $atts['tab_position'] || 'right' === $atts['tab_position'] ) {
			if ( isset( $atts['pgs_icon_alignment_top_bottom'] ) && 'true' === (string) $atts['pgs_icon_alignment_top_bottom'] ) {
				$html[0] = '<div class="vc_tta-tabs-container pgs_icon_alignment_top_bottom">';
			}
		} elseif ( 'top' === $atts['tab_position'] || 'bottom' === $atts['tab_position'] ) {
			if ( isset( $atts['pgs_full_width_tabs'] ) && 'true' === (string) $atts['pgs_full_width_tabs'] ) {
				$html[0] = '<div class="vc_tta-tabs-container pgs_full_width_tabs">';
			}
		}
	}

	return $html;
}
