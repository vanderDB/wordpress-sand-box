<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * VC custom heading
 *
 * @package CiyaShop
 */

/**
 * VC extend custom heading.
 */
function ciyashop_extend_vc_custom_heading() {
	// Get VC gallery shortcode config.
	$shortcode_vc_custom_heading_tmp = vc_get_shortcode( 'vc_custom_heading' );

	$shortcode_vc_custom_heading_tmp['params'][] = array(
		'type'        => 'dropdown',
		'heading'     => __( 'Font Weight', 'ciyashop' ),
		'param_name'  => 'font_weight',
		'value'       => array_flip(
			array(
				''        => esc_html__( 'Select Font Weight', 'ciyashop' ),
				'normal'  => esc_html__( 'Normal', 'ciyashop' ),
				'bold'    => esc_html__( 'Bold', 'ciyashop' ),
				'bolder'  => esc_html__( 'Bolder', 'ciyashop' ),
				'lighter' => esc_html__( 'Lighter', 'ciyashop' ),
				'100'     => esc_html__( '100', 'ciyashop' ),
				'200'     => esc_html__( '200', 'ciyashop' ),
				'300'     => esc_html__( '300', 'ciyashop' ),
				'400'     => esc_html__( '400', 'ciyashop' ),
				'500'     => esc_html__( '500', 'ciyashop' ),
				'600'     => esc_html__( '600', 'ciyashop' ),
				'700'     => esc_html__( '700', 'ciyashop' ),
				'800'     => esc_html__( '800', 'ciyashop' ),
				'900'     => esc_html__( '900', 'ciyashop' ),
				'initial' => esc_html__( 'Initial', 'ciyashop' ),
				'inherit' => esc_html__( 'Inherit', 'ciyashop' ),
			)
		),
		'std'         => '',
		'description' => __( 'Select font weight.', 'ciyashop' ),
		'dependency'  => array(
			'element' => 'use_theme_fonts',
			'value'   => 'yes',
		),
		'group'       => esc_html__( 'Custom Font Settings', 'ciyashop' ),
	);
	$shortcode_vc_custom_heading_tmp['params'][] = array(
		'type'        => 'dropdown',
		'heading'     => __( 'Text Transform', 'ciyashop' ),
		'param_name'  => 'text_transform',
		'value'       => array_flip(
			array(
				''           => esc_html__( 'Select Text Transform', 'ciyashop' ),
				'capitalize' => esc_html__( 'Capitalize', 'ciyashop' ),
				'uppercase'  => esc_html__( 'Uppercase', 'ciyashop' ),
				'lowercase'  => esc_html__( 'Lowercase', 'ciyashop' ),
				'initial'    => esc_html__( 'Initial', 'ciyashop' ),
				'inherit'    => esc_html__( 'Inherit', 'ciyashop' ),
			)
		),
		'std'         => '',
		'description' => __( 'Select text transform.', 'ciyashop' ),
		'group'       => esc_html__( 'Custom Font Settings', 'ciyashop' ),
	);
	$shortcode_vc_custom_heading_tmp['params'][] = array(
		'type'        => 'dropdown',
		'heading'     => __( 'Letter Spacing', 'ciyashop' ),
		'param_name'  => 'letter_spacing',
		'value'       => array_flip(
			array(
				''        => esc_html__( 'Select Letter Spacing', 'ciyashop' ),
				'normal'  => esc_html__( 'Normal', 'ciyashop' ),
				'custom'  => esc_html__( 'Custom', 'ciyashop' ),
				'initial' => esc_html__( 'Initial', 'ciyashop' ),
				'inherit' => esc_html__( 'Inherit', 'ciyashop' ),
			)
		),
		'std'         => '',
		'description' => __( 'Select letter spacing.', 'ciyashop' ),
		'group'       => esc_html__( 'Custom Font Settings', 'ciyashop' ),
	);

	$shortcode_vc_custom_heading_tmp['params'][] = array(
		'type'        => 'textfield',
		'heading'     => __( 'Letter Spacing (Custom)', 'ciyashop' ),
		'param_name'  => 'letter_spacing_custom',
		'description' => esc_html__( 'Enter custom letter spacing i.e. 10px, 1em, or 100%. If you want to add value in decimal like ".5em", then use complete decimal format like "0.5em". Allowed units are px, em, and %.', 'ciyashop' ),
		'group'       => esc_html__( 'Custom Font Settings', 'ciyashop' ),
		'dependency'  => array(
			'element' => 'letter_spacing',
			'value'   => 'custom',
		),
	);

	// VC doesn't like even the thought of you changing the shortcode base, and errors out, so we unset it.
	unset( $shortcode_vc_custom_heading_tmp['base'] );

	// Update the actual parameter.
	vc_map_update( 'vc_custom_heading', $shortcode_vc_custom_heading_tmp );
}
add_action( 'init', 'ciyashop_extend_vc_custom_heading', 100 );
