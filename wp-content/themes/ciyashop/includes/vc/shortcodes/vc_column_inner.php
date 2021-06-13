<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Extend vc column inner
 *
 * @package CiyaShop
 */

add_action( 'init', 'ciyashop_extend_vc_column_inner', 1000 );
/**
 * Extend vc column inner
 */
function ciyashop_extend_vc_column_inner() {
	if ( ! function_exists( 'vc_add_params' ) ) {
		return;
	}

	// Get shortcode config.
	$shortcode_tmp = vc_get_shortcode( 'vc_column_inner' );

	$replacing_params = array(
		'css'   => array(
			'type'       => 'css_editor',
			'heading'    => esc_html__( 'Large Devices (&#8805;1200px)', 'ciyashop' ),
			'param_name' => 'css',
			'group'      => esc_html__( 'Design Options', 'ciyashop' ),
		),
		'el_id' => array(
			'type'        => 'el_id',
			'heading'     => esc_html__( 'Row ID', 'ciyashop' ),
			'param_name'  => 'el_id',
			'description' => sprintf(
				/* translators: $s: Set Link */
				esc_html__( 'Enter row ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>)', 'ciyashop' ),
				'http://www.w3schools.com/tags/att_global_id.asp'
			)
			. '<br><span class="ciyashop-red">Important : If Row ID starts with number (while generated automatically), it will be prefixed with "<strong>vc_row_</strong>".</span>',
			'settings'    => array(
				'auto_generate' => true,
			),
		),
	);

	foreach ( $shortcode_tmp['params'] as $param_key => $param_data ) {
		if ( array_key_exists( $param_data['param_name'], $replacing_params ) ) {
			$shortcode_tmp['params'][ $param_key ] = $replacing_params[ $param_data['param_name'] ];
		}
	}

	// Design Options.
	$design_options = array(
		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Enable Responsive Settings?', 'ciyashop' ),
			'param_name'  => 'pgscore_enable_responsive_settings',
			'group'       => esc_attr__( 'Design Options', 'ciyashop' ),
			'description' => esc_html__( 'Select this checkbox to set different value in responsive views. If any value is not passed in responsive settings, it will use default or value set from it\'s higher settings.', 'ciyashop' ),
		),
		array(
			'type'             => 'css_editor',
			'heading'          => esc_html__( 'Medium Devices (&#8805;992px)', 'ciyashop' ),
			'param_name'       => 'element_css_md',
			'group'            => esc_attr__( 'Design Options', 'ciyashop' ),
			'abc'              => 'xyz',
			'edit_field_class' => 'css_editor_padding_margin_border',
			'dependency'       => array(
				'element' => 'pgscore_enable_responsive_settings',
				'value'   => 'true',
			),
		),
		array(
			'type'             => 'css_editor',
			'heading'          => esc_html__( 'Small Devices (&#8805;768px)', 'ciyashop' ),
			'param_name'       => 'element_css_sm',
			'group'            => esc_attr__( 'Design Options', 'ciyashop' ),
			'edit_field_class' => 'css_editor_padding_margin_border',
			'dependency'       => array(
				'element' => 'pgscore_enable_responsive_settings',
				'value'   => 'true',
			),
		),
		array(
			'type'             => 'css_editor',
			'heading'          => esc_html__( 'Extra Small Devices (<768px)', 'ciyashop' ),
			'param_name'       => 'element_css_xs',
			'group'            => esc_attr__( 'Design Options', 'ciyashop' ),
			'edit_field_class' => 'css_editor_padding_margin_border',
			'dependency'       => array(
				'element' => 'pgscore_enable_responsive_settings',
				'value'   => 'true',
			),
		),
	);

	// Background Options.
	$background_options = array(
		array(
			'type'       => 'dropdown',
			'heading'    => esc_html__( 'Row Background Color Type', 'ciyashop' ),
			'param_name' => 'pgscore_bg_type',
			'value'      => array(
				esc_attr__( 'None', 'ciyashop' ) => 'row-background-none',
				esc_attr__( 'Light Background', 'ciyashop' ) => 'row-background-light',
				esc_attr__( 'Dark Background', 'ciyashop' ) => 'row-background-dark',
			),
			'group'      => esc_attr__( 'Background', 'ciyashop' ),
		),
		array(
			'type'       => 'dropdown',
			'class'      => '',
			'heading'    => 'Background Position',
			'param_name' => 'pgscore_background_position',
			'value'      => array(
				esc_html__( 'Select Background Position', 'ciyashop' ) => '',
				esc_html__( 'Left Top', 'ciyashop' )      => 'left-top',
				esc_html__( 'Left Center', 'ciyashop' )   => 'left-center',
				esc_html__( 'Left Bottom', 'ciyashop' )   => 'left-bottom',
				esc_html__( 'Right Top', 'ciyashop' )     => 'right-top',
				esc_html__( 'Right Center', 'ciyashop' )  => 'right-center',
				esc_html__( 'Right Bottom', 'ciyashop' )  => 'right-bottom',
				esc_html__( 'Center Top', 'ciyashop' )    => 'center-top',
				esc_html__( 'Center Center', 'ciyashop' ) => 'center-center',
				esc_html__( 'Center Bottom', 'ciyashop' ) => 'center-bottom',
			),
			'group'      => esc_attr__( 'Background', 'ciyashop' ),
		),
	);

	// Overlay Options.
	$overlay_options = array(
		array(
			'type'       => 'checkbox',
			'heading'    => esc_html__( 'Enable Overlay?', 'ciyashop' ),
			'param_name' => 'pgscore_enable_overlay',
			'group'      => esc_attr__( 'Background', 'ciyashop' ),
		),
		array(
			'type'        => 'colorpicker',
			'heading'     => esc_html__( 'Overlay Color', 'ciyashop' ),
			'param_name'  => 'pgscore_overlay_color',
			'description' => esc_html__( 'Select overlay color.', 'ciyashop' ),
			'dependency'  => array(
				'element' => 'pgscore_enable_overlay',
				'value'   => 'true',
			),
			'group'       => esc_attr__( 'Background', 'ciyashop' ),
		),
	);

	// Overlay Options.
	$half_overlap_options = array(
		array(
			'type'        => 'checkbox',
			'heading'     => esc_html__( 'Vertical Half Overlap', 'ciyashop' ),
			'description' => esc_html__( 'This will enable to place current row above previous and next rows.', 'ciyashop' ),
			'param_name'  => 'pgscore_half_overlap',
			'group'       => esc_attr__( 'Extras', 'ciyashop' ),
		),
	);

	// Text alignment.
	$text_alignment = array(
		array(
			'type'             => 'dropdown',
			'heading'          => __( 'Content Alignment', 'ciyashop' ),
			'param_name'       => 'alignment',
			'value'            => array(
				__( 'Default', 'ciyashop' ) => '',
				__( 'Left', 'ciyashop' )    => 'left',
				__( 'Center', 'ciyashop' )  => 'center',
				__( 'Right', 'ciyashop' )   => 'right',
				__( 'Justify', 'ciyashop' ) => 'justify',
			),
			'std'              => '',
			'description'      => __( 'Specify custom alignment for column content.', 'ciyashop' ),
			'edit_field_class' => 'vc_col-sm-6 vc_column',
			'weight'           => -1020,
			'group'            => __( 'Extras', 'ciyashop' ),
		),
	);

	// MPC Fix
	// Remove Overlay settings if Massive Addons is active.
	global $mpc_paths;
	if ( $mpc_paths ) {
		$overlay_options      = array();
		$half_overlap_options = array();
		$text_alignment       = array();
	}

	// Merge Parameters.
	$shortcode_tmp['params'] = array_merge(
		$shortcode_tmp['params'],
		$design_options,
		$background_options,
		$text_alignment,
		$overlay_options,
		$half_overlap_options
	);

	// VC doesn't like even the thought of you changing the shortcode base, and errors out, so we unset it.
	unset( $shortcode_tmp['base'] );

	// Update the actual parameter.
	vc_map_update( 'vc_column_inner', $shortcode_tmp );
}
