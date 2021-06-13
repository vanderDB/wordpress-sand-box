<?php
/**
 * Menu fields
 *
 * @package CiyaShop
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Menu fields
 */
function pgs_menu_fields() {

	global $wp_registered_sidebars;

	$sidebars[''] = esc_html__( 'Select widget area', 'ciyashop' );

	foreach ( $wp_registered_sidebars as $key => $sidebar ) {
		$sidebars[ $key ] = $sidebar['name'];
	}

	$pgs_menu_fields = array(
		array(
			'id'          => 'menu_anchor',
			'type'        => 'textfield',
			'title'       => esc_html__( 'Link Anchor', 'ciyashop' ),
			'desc'        => esc_html__( 'Enter the anchor where you want to navigate.', 'ciyashop' ),
			'placeholder' => esc_html__( '#example', 'ciyashop' ),
		),
		array(
			'id'      => 'menu_color_scheme',
			'type'    => 'dropdown',
			'title'   => esc_html__( 'Menu Color Scheme', 'ciyashop' ),
			'options' => array(
				'light' => esc_html__( 'Light', 'ciyashop' ),
				'dark'  => esc_html__( 'Dark', 'ciyashop' ),
			),
			'desc'    => esc_html__( 'Select the menu color scheme.', 'ciyashop' ),
			'depth'   => array( 0 ),
		),
		array(
			'id'      => 'menu_design',
			'type'    => 'dropdown',
			'title'   => esc_html__( 'Design Type', 'ciyashop' ),
			'options' => array(
				'dropdown'  => esc_html__( 'Dropdown', 'ciyashop' ),
				'mega-menu' => esc_html__( 'Mega menu', 'ciyashop' ),
			),
			'desc'    => esc_html__( 'Select the menu design type.', 'ciyashop' ),
			'depth'   => array( 0 ),
		),
		array(
			'id'      => 'mega_menu_design',
			'type'    => 'dropdown',
			'title'   => esc_html__( 'Mega Menu Type', 'ciyashop' ),
			'options' => array(
				'full-width'  => esc_html__( 'Full Width', 'ciyashop' ),
				'custom-size' => esc_html__( 'Custom Size', 'ciyashop' ),
			),
			'desc'    => esc_html__( 'Select the mega menu design type.', 'ciyashop' ),
			'depth'   => array( 0 ),
		),
		array(
			'id'    => 'mega_menu_width',
			'type'  => 'number',
			'title' => esc_html__( 'Mega Menu Width', 'ciyashop' ),
			'desc'  => esc_html__( 'Enter menu width.', 'ciyashop' ),
			'class' => 'col-6',
			'depth' => array( 0 ),
		),
		array(
			'id'    => 'mega_menu_height',
			'type'  => 'number',
			'title' => esc_html__( 'Mega Menu Height', 'ciyashop' ),
			'desc'  => esc_html__( 'Enter menu height.', 'ciyashop' ),
			'class' => 'col-6 last',
			'depth' => array( 0 ),
		),
		array(
			'id'          => 'html_block',
			'type'        => 'dropdown',
			'title'       => esc_html__( 'Static block', 'ciyashop' ),
			'options'     => ( function_exists( 'pgscore_get_static_blocks' ) ) ? pgscore_get_static_blocks() : array(),
			'desc'        => sprintf(
				wp_kses(
					/* translators: $s: static_block post edit link */
					__( 'Note : Make sure You have added Static Block in <a href="%1$s" target="_blank">Static Blocks</a> section. <strong>Don\'t create menu subitems if you using static block!</strong>', 'ciyashop' ),
					array(
						'a'      => array(
							'href'   => true,
							'target' => true,
						),
						'strong' => true,
					)
				),
				admin_url( 'edit.php?post_type=static_block' )
			),
			'first_empty' => true,
			'empty_label' => esc_html__( 'Select option', 'ciyashop' ),
			'depth'       => array( 0 ),
		),
		array(
			'id'      => 'menu_columns',
			'type'    => 'dropdown',
			'title'   => esc_html__( 'Columns', 'ciyashop' ),
			'options' => array(
				2 => esc_html__( '2 Column', 'ciyashop' ),
				3 => esc_html__( '3 Column', 'ciyashop' ),
				4 => esc_html__( '4 Column', 'ciyashop' ),
				5 => esc_html__( '5 Column', 'ciyashop' ),
				6 => esc_html__( '6 Column', 'ciyashop' ),
			),
			'desc'    => esc_html__( 'Select the column.', 'ciyashop' ),
			'depth'   => array( 0 ),
		),
		array(
			'id'      => 'menu_widget_area',
			'type'    => 'dropdown',
			'title'   => esc_html__( 'Widget area', 'ciyashop' ),
			'options' => $sidebars,
			'depth'   => array( 1 ),
		),
		array(
			'id'    => 'menu_open_on_click',
			'type'  => 'checkbox',
			'label' => esc_html__( 'Open Submenu On Click', 'ciyashop' ),
			'value' => 1,
			'desc'  => esc_html__( 'First click will open a sub menu, second click will close the sub menu.', 'ciyashop' ),
			'depth' => array( 0 ),
		),
		array(
			'id'    => 'enable_menu_link',
			'type'  => 'checkbox',
			'label' => esc_html__( 'Enable Menu Link', 'ciyashop' ),
			'value' => 1,
			'desc'  => esc_html__( 'First click will open a sub menu, second click will follow the link.', 'ciyashop' ),
			'depth' => array( 0 ),
		),
		array(
			'id'    => 'menu_icon',
			'type'  => 'iconpicker',
			'title' => esc_html__( 'Select Icon', 'ciyashop' ),
			'class' => 'col-4',
			'desc'  => esc_html__( 'Select or search menu icon', 'ciyashop' ),
		),
		array(
			'id'    => 'menu_label',
			'type'  => 'textfield',
			'title' => esc_html__( 'Label', 'ciyashop' ),
			'class' => 'col-4',
			'desc'  => esc_html__( 'Enter menu item label', 'ciyashop' ),
		),
		array(
			'id'      => 'menu_label_color',
			'type'    => 'dropdown',
			'title'   => esc_html__( 'Label Color', 'ciyashop' ),
			'options' => array(
				'red'    => esc_html__( 'Red Color', 'ciyashop' ),
				'green'  => esc_html__( 'Green Color', 'ciyashop' ),
				'orange' => esc_html__( 'Orange Color', 'ciyashop' ),
				'blue'   => esc_html__( 'Blue Color', 'ciyashop' ),
				'black'  => esc_html__( 'Black Color', 'ciyashop' ),
			),
			'class'   => 'col-4 last',
			'desc'    => esc_html__( 'Select menu label color.', 'ciyashop' ),
		),
		array(
			'id'    => 'menu_image',
			'type'  => 'image',
			'title' => esc_html__( 'Background Image', 'ciyashop' ),
			'desc'  => esc_html__( 'Select menu image.', 'ciyashop' ),
			'class' => 'clearfix',
			'depth' => array( 0, 1 ),
		),
		array(
			'id'      => 'menu_background_repeat',
			'type'    => 'dropdown',
			'title'   => esc_html__( 'Background Repeat', 'ciyashop' ),
			'options' => array(
				''          => esc_html__( 'Background-repeat', 'ciyashop' ),
				'no-repeat' => esc_html__( 'No Repeat', 'ciyashop' ),
				'repeat'    => esc_html__( 'Repeat All', 'ciyashop' ),
				'repeat-x'  => esc_html__( 'Repeat Horizontally', 'ciyashop' ),
				'repeat-y'  => esc_html__( 'Repeat Vertically', 'ciyashop' ),
				'inherit'   => esc_html__( 'Inherit', 'ciyashop' ),
			),
			'class'   => 'col-4',
			'desc'    => esc_html__( 'Select image background repeat type.', 'ciyashop' ),
			'depth'   => array( 0, 1 ),
		),
		array(
			'id'      => 'menu_background_size',
			'type'    => 'dropdown',
			'title'   => esc_html__( 'Background Size', 'ciyashop' ),
			'options' => array(
				'inherit' => esc_html__( 'Inherit', 'ciyashop' ),
				'cover'   => esc_html__( 'Cover', 'ciyashop' ),
				'contain' => esc_html__( 'Contain', 'ciyashop' ),
			),
			'class'   => 'col-4',
			'desc'    => esc_html__( 'Select image background size.', 'ciyashop' ),
			'depth'   => array( 0, 1 ),
		),
		array(
			'id'      => 'menu_background_position',
			'type'    => 'dropdown',
			'title'   => esc_html__( 'Background Position', 'ciyashop' ),
			'options' => array(
				''              => esc_html__( 'Background-position', 'ciyashop' ),
				'left top'      => esc_html__( 'Left Top', 'ciyashop' ),
				'left center'   => esc_html__( 'Left Center', 'ciyashop' ),
				'left bottom'   => esc_html__( 'Left Bottom', 'ciyashop' ),
				'center center' => esc_html__( 'Center Center', 'ciyashop' ),
				'center bottom' => esc_html__( 'Center Bottom', 'ciyashop' ),
				'right top'     => esc_html__( 'Right Top', 'ciyashop' ),
				'right center'  => esc_html__( 'Right Center', 'ciyashop' ),
				'right bottom'  => esc_html__( 'Right Bottom', 'ciyashop' ),
			),
			'class'   => 'col-4 last',
			'desc'    => esc_html__( 'Select image background position.', 'ciyashop' ),
			'depth'   => array( 0, 1 ),
		),
	);

	return $pgs_menu_fields;
}
