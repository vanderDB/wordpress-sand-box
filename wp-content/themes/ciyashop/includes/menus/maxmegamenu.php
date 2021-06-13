<?php
/**
 * Max megamenu
 *
 * @package CiyaShop
 */

/**
 * Megamenu add theme ciyashop
 *
 * @param string $themes .
 */
function ciyashop_megamenu_add_theme_ciyashop( $themes ) {
	$themes['ciyashop-default'] = array(
		'title'                                    => esc_html__( 'CiyaShop (Default Menu)', 'ciyashop' ),
		'container_background_from'                => 'rgba(255, 255, 255, 0)',
		'container_background_to'                  => 'rgba(255, 255, 255, 0)',
		'arrow_up'                                 => 'dash-f343',
		'arrow_down'                               => 'dash-f347',
		'arrow_left'                               => 'dash-f341',
		'arrow_right'                              => 'dash-f345',
		'menu_item_background_hover_from'          => 'rgba(255, 255, 255, 0)',
		'menu_item_background_hover_to'            => 'rgba(255, 255, 255, 0)',
		'menu_item_link_height'                    => '60px',
		'menu_item_link_color'                     => 'rgb(50, 50, 50)',
		'menu_item_link_text_transform'            => 'uppercase',
		'menu_item_link_color_hover'               => 'rgb(4, 211, 159)',
		'menu_item_border_color'                   => 'rgb(255, 255, 255)',
		'menu_item_border_color_hover'             => 'rgb(255, 255, 255)',
		'menu_item_divider_color'                  => 'rgba(255, 255, 255, 0)',
		'panel_background_from'                    => 'rgb(255, 255, 255)',
		'panel_background_to'                      => 'rgb(255, 255, 255)',
		'panel_border_color'                       => 'rgb(233, 233, 233)',
		'panel_border_left'                        => '0px',
		'panel_border_right'                       => '0px',
		'panel_border_top'                         => '0px',
		'panel_border_bottom'                      => '0px',
		'panel_header_color'                       => 'rgb(0, 0, 0)',
		'panel_header_padding_bottom'              => '0px',
		'panel_header_margin_bottom'               => '10px',
		'panel_header_border_color'                => 'rgba(0, 0, 0, 0)',
		'panel_padding_top'                        => '25px',
		'panel_padding_bottom'                     => '25px',
		'panel_widget_padding_left'                => '25px',
		'panel_widget_padding_right'               => '25px',
		'panel_widget_padding_top'                 => '0px',
		'panel_widget_padding_bottom'              => '0px',
		'panel_font_size'                          => '14px',
		'panel_font_color'                         => 'rgb(150, 150, 150)',
		'panel_font_family'                        => 'inherit',
		'panel_second_level_font_color'            => 'rgb(50, 50, 50)',
		'panel_second_level_font_color_hover'      => 'rgb(4, 211, 159)',
		'panel_second_level_text_transform'        => 'normal',
		'panel_second_level_font'                  => 'inherit',
		'panel_second_level_font_size'             => '16px',
		'panel_second_level_font_weight'           => 'bold',
		'panel_second_level_font_weight_hover'     => 'bold',
		'panel_second_level_text_decoration'       => 'none',
		'panel_second_level_text_decoration_hover' => 'none',
		'panel_second_level_margin_bottom'         => '15px',
		'panel_second_level_border_color'          => 'rgba(0, 0, 0, 0.04)',
		'panel_third_level_font_color'             => 'rgb(150, 150, 150)',
		'panel_third_level_font_color_hover'       => 'rgb(4, 211, 159)',
		'panel_third_level_text_transform'         => 'normal',
		'panel_third_level_font'                   => 'inherit',
		'panel_third_level_font_size'              => '14px',
		'panel_third_level_padding_top'            => '3px',
		'panel_third_level_padding_bottom'         => '3px',
		'flyout_width'                             => '210px',
		'flyout_menu_background_from'              => 'rgb(255, 255, 255)',
		'flyout_menu_background_to'                => 'rgb(255, 255, 255)',
		'flyout_border_color'                      => 'rgb(233, 233, 233)',
		'flyout_border_left'                       => '0px',
		'flyout_border_right'                      => '0px',
		'flyout_border_top'                        => '0px',
		'flyout_border_bottom'                     => '0px',
		'flyout_menu_item_divider'                 => 'on',
		'flyout_menu_item_divider_color'           => 'rgba(0, 0, 0, 0.0)',
		'flyout_padding_top'                       => '5px',
		'flyout_padding_bottom'                    => '5px',
		'flyout_link_padding_left'                 => '15px',
		'flyout_link_padding_right'                => '15px',
		'flyout_link_padding_top'                  => '8px',
		'flyout_link_padding_bottom'               => '8px',
		'flyout_link_height'                       => '22px',
		'flyout_background_from'                   => 'rgba(255, 255, 255, 0)',
		'flyout_background_to'                     => 'rgba(255, 255, 255, 0)',
		'flyout_background_hover_from'             => 'rgba(255, 255, 255, 0)',
		'flyout_background_hover_to'               => 'rgba(255, 255, 255, 0)',
		'flyout_link_size'                         => '14px',
		'flyout_link_color'                        => 'rgb(150, 150, 150)',
		'flyout_link_color_hover'                  => 'rgb(4, 211, 159)',
		'flyout_link_family'                       => 'inherit',
		'line_height'                              => '1.9',
		'shadow'                                   => 'on',
		'shadow_horizontal'                        => '0px',
		'shadow_vertical'                          => '10px',
		'shadow_blur'                              => '25px',
		'shadow_spread'                            => '-3px',
		'shadow_color'                             => 'rgba(0, 0, 0, 0.1)',
		'toggle_background_from'                   => 'rgb(154, 242, 181)',
		'toggle_background_to'                     => 'rgb(154, 242, 181)',
		'toggle_font_color'                        => 'rgb(255, 255, 255)',
		'mobile_background_from'                   => 'rgb(231, 151, 246)',
		'mobile_background_to'                     => 'rgb(231, 151, 246)',
		'mobile_menu_item_link_font_size'          => '14px',
		'mobile_menu_item_link_color'              => '#ffffff',
		'mobile_menu_item_link_text_align'         => 'left',
		'custom_css'                               => '/** Push menu onto new line **/ 
#{$wrap} { 
	clear: both; 
}',
	);

	$themes['ciyashop-categories'] = array(
		'title'                                    => esc_html__( 'CiyaShop (Categories Menu)', 'ciyashop' ),
		'container_background_from'                => 'rgb(255, 255, 255)',
		'container_background_to'                  => 'rgb(255, 255, 255)',
		'container_padding_top'                    => '10px',
		'container_padding_bottom'                 => '10px',
		'arrow_up'                                 => 'dash-f345',
		'arrow_down'                               => 'dash-f345',
		'arrow_left'                               => 'dash-f345',
		'arrow_right'                              => 'dash-f345',
		'menu_item_background_hover_from'          => 'rgba(255, 255, 255, 0)',
		'menu_item_background_hover_to'            => 'rgba(255, 255, 255, 0)',
		'menu_item_link_height'                    => 'inherit',
		'menu_item_link_color'                     => 'rgb(50, 50, 50)',
		'menu_item_link_color_hover'               => 'rgb(4, 211, 159)',
		'menu_item_link_padding_left'              => '20px',
		'menu_item_link_padding_right'             => '20px',
		'menu_item_link_padding_top'               => '7px',
		'menu_item_link_padding_bottom'            => '7px',
		'menu_item_border_color'                   => 'rgb(255, 255, 255)',
		'menu_item_border_color_hover'             => 'rgb(255, 255, 255)',
		'menu_item_divider'                        => 'on',
		'menu_item_divider_color'                  => 'rgba(255, 255, 255, 0)',
		'panel_background_from'                    => 'rgb(255, 255, 255)',
		'panel_background_to'                      => 'rgb(255, 255, 255)',
		'panel_width'                              => '600px',
		'panel_border_color'                       => 'rgb(233, 233, 233)',
		'panel_border_left'                        => '1px',
		'panel_border_right'                       => '1px',
		'panel_border_top'                         => '1px',
		'panel_border_bottom'                      => '1px',
		'panel_header_border_color'                => '#555',
		'panel_padding_left'                       => '15px',
		'panel_padding_right'                      => '15px',
		'panel_padding_top'                        => '15px',
		'panel_padding_bottom'                     => '15px',
		'panel_font_size'                          => '14px',
		'panel_font_color'                         => '#666',
		'panel_font_family'                        => 'inherit',
		'panel_second_level_font_color'            => 'rgb(50, 50, 50)',
		'panel_second_level_font_color_hover'      => 'rgb(4, 211, 159)',
		'panel_second_level_text_transform'        => 'uppercase',
		'panel_second_level_font'                  => 'inherit',
		'panel_second_level_font_size'             => '14px',
		'panel_second_level_font_weight'           => 'bold',
		'panel_second_level_font_weight_hover'     => 'bold',
		'panel_second_level_text_decoration'       => 'none',
		'panel_second_level_text_decoration_hover' => 'none',
		'panel_second_level_margin_bottom'         => '10px',
		'panel_second_level_border_color'          => 'rgba(0, 0, 0, 0.04)',
		'panel_third_level_font_color'             => 'rgb(150, 150, 150)',
		'panel_third_level_font_color_hover'       => 'rgb(4, 211, 159)',
		'panel_third_level_font'                   => 'inherit',
		'panel_third_level_font_size'              => '13px',
		'panel_third_level_padding_top'            => '3px',
		'panel_third_level_padding_bottom'         => '3px',
		'flyout_width'                             => '250px',
		'flyout_menu_background_from'              => 'rgb(255, 255, 255)',
		'flyout_menu_background_to'                => 'rgb(255, 255, 255)',
		'flyout_border_color'                      => 'rgb(233, 233, 233)',
		'flyout_border_left'                       => '1px',
		'flyout_border_right'                      => '1px',
		'flyout_border_top'                        => '1px',
		'flyout_border_bottom'                     => '1px',
		'flyout_padding_top'                       => '15px',
		'flyout_padding_bottom'                    => '15px',
		'flyout_link_padding_left'                 => '25px',
		'flyout_link_padding_right'                => '25px',
		'flyout_link_padding_top'                  => '2px',
		'flyout_link_padding_bottom'               => '2px',
		'flyout_link_height'                       => '30px',
		'flyout_background_from'                   => 'rgba(255, 255, 255, 0)',
		'flyout_background_to'                     => 'rgba(255, 255, 255, 0)',
		'flyout_background_hover_from'             => 'rgba(255, 255, 255, 0)',
		'flyout_background_hover_to'               => 'rgba(255, 255, 255, 0)',
		'flyout_link_size'                         => '13px',
		'flyout_link_color'                        => 'rgb(150, 150, 150)',
		'flyout_link_color_hover'                  => 'rgb(4, 211, 159)',
		'flyout_link_family'                       => 'inherit',
		'shadow'                                   => 'on',
		'shadow_horizontal'                        => '2px',
		'shadow_vertical'                          => '4px',
		'shadow_blur'                              => '3px',
		'shadow_color'                             => 'rgba(0, 0, 0, 0.04)',
		'toggle_background_from'                   => '#222',
		'toggle_background_to'                     => '#222',
		'toggle_font_color'                        => 'rgb(50, 50, 50)',
		'mobile_background_from'                   => '#222',
		'mobile_background_to'                     => '#222',
		'mobile_menu_item_link_font_size'          => '14px',
		'mobile_menu_item_link_color'              => '#ffffff',
		'mobile_menu_item_link_text_align'         => 'left',
		'custom_css'                               => '/** Push menu onto new line **/ 
#{$wrap} { 
	clear: both; 
}',
	);

	$themes['ciyashop_shortcode_vertical'] = array(
		'title'                                    => esc_html__( 'CiyaShop (Shortcode - Vertical Menu)', 'ciyashop' ),
		'container_background_from'                => 'rgb(116, 185, 62)',
		'container_background_to'                  => 'rgb(116, 185, 62)',
		'arrow_up'                                 => 'dash-f345',
		'arrow_down'                               => 'dash-f345',
		'arrow_left'                               => 'dash-f345',
		'arrow_right'                              => 'dash-f345',
		'menu_item_background_hover_from'          => 'rgba(255, 255, 255, 0)',
		'menu_item_background_hover_to'            => 'rgba(255, 255, 255, 0)',
		'menu_item_link_height'                    => 'inherit',
		'menu_item_link_color'                     => 'rgb(255, 255, 255)',
		'menu_item_link_text_transform'            => 'uppercase',
		'menu_item_link_color_hover'               => 'rgb(255, 255, 255)',
		'menu_item_link_padding_left'              => '20px',
		'menu_item_link_padding_right'             => '20px',
		'menu_item_link_padding_top'               => '11px',
		'menu_item_link_padding_bottom'            => '10px',
		'menu_item_border_color'                   => 'rgba(255, 255, 255, 0.2)',
		'menu_item_border_bottom'                  => '1px',
		'menu_item_border_color_hover'             => 'rgba(255, 255, 255, 0.2)',
		'panel_background_from'                    => 'rgb(255, 255, 255)',
		'panel_background_to'                      => 'rgb(255, 255, 255)',
		'panel_width'                              => '600px',
		'panel_border_color'                       => 'rgb(233, 233, 233)',
		'panel_border_left'                        => '1px',
		'panel_border_right'                       => '1px',
		'panel_border_top'                         => '1px',
		'panel_border_bottom'                      => '1px',
		'panel_header_border_color'                => '#555',
		'panel_padding_left'                       => '15px',
		'panel_padding_right'                      => '15px',
		'panel_padding_top'                        => '15px',
		'panel_padding_bottom'                     => '15px',
		'panel_font_size'                          => '14px',
		'panel_font_color'                         => '#666',
		'panel_font_family'                        => 'inherit',
		'panel_second_level_font_color'            => 'rgb(50, 50, 50)',
		'panel_second_level_font_color_hover'      => 'rgb(4, 211, 159)',
		'panel_second_level_text_transform'        => 'uppercase',
		'panel_second_level_font'                  => 'inherit',
		'panel_second_level_font_size'             => '14px',
		'panel_second_level_font_weight'           => 'bold',
		'panel_second_level_font_weight_hover'     => 'bold',
		'panel_second_level_text_decoration'       => 'none',
		'panel_second_level_text_decoration_hover' => 'none',
		'panel_second_level_margin_bottom'         => '10px',
		'panel_second_level_border_color'          => 'rgba(0, 0, 0, 0.04)',
		'panel_third_level_font_color'             => 'rgb(150, 150, 150)',
		'panel_third_level_font_color_hover'       => 'rgb(4, 211, 159)',
		'panel_third_level_font'                   => 'inherit',
		'panel_third_level_font_size'              => '13px',
		'panel_third_level_padding_top'            => '3px',
		'panel_third_level_padding_bottom'         => '3px',
		'flyout_width'                             => '250px',
		'flyout_menu_background_from'              => 'rgb(255, 255, 255)',
		'flyout_menu_background_to'                => 'rgb(255, 255, 255)',
		'flyout_border_color'                      => 'rgb(233, 233, 233)',
		'flyout_border_left'                       => '1px',
		'flyout_border_right'                      => '1px',
		'flyout_border_top'                        => '1px',
		'flyout_border_bottom'                     => '1px',
		'flyout_padding_top'                       => '15px',
		'flyout_padding_bottom'                    => '15px',
		'flyout_link_padding_left'                 => '25px',
		'flyout_link_padding_right'                => '25px',
		'flyout_link_padding_top'                  => '2px',
		'flyout_link_padding_bottom'               => '2px',
		'flyout_link_height'                       => '30px',
		'flyout_background_from'                   => 'rgba(255, 255, 255, 0)',
		'flyout_background_to'                     => 'rgba(255, 255, 255, 0)',
		'flyout_background_hover_from'             => 'rgba(255, 255, 255, 0)',
		'flyout_background_hover_to'               => 'rgba(255, 255, 255, 0)',
		'flyout_link_size'                         => '13px',
		'flyout_link_color'                        => 'rgb(150, 150, 150)',
		'flyout_link_color_hover'                  => 'rgb(4, 211, 159)',
		'flyout_link_family'                       => 'inherit',
		'shadow'                                   => 'on',
		'shadow_horizontal'                        => '2px',
		'shadow_vertical'                          => '4px',
		'shadow_blur'                              => '3px',
		'shadow_color'                             => 'rgba(0, 0, 0, 0.4)',
		'toggle_background_from'                   => '#222',
		'toggle_background_to'                     => '#222',
		'toggle_font_color'                        => 'rgb(255, 255, 255)',
		'mobile_background_from'                   => '#222',
		'mobile_background_to'                     => '#222',
		'mobile_menu_item_link_font_size'          => '14px',
		'mobile_menu_item_link_color'              => '#ffffff',
		'mobile_menu_item_link_text_align'         => 'left',
		'custom_css'                               => '/** Push menu onto new line **/ 
#{$wrap} { 
    clear: both; 
}',
	);

	return $themes;
}
add_filter( 'megamenu_themes', 'ciyashop_megamenu_add_theme_ciyashop' );
/**
 * Megamenu override default theme
 *
 * @param string $value .
 */
function ciyashop_megamenu_override_default_theme( $value ) {

	// change 'primary' to your menu location ID.
	$value['primary']['theme']          = 'ciyashop-default';
	$value['categories_menu']['theme']  = 'ciyashop-categories';
	$value['shortcode_v_menu']['theme'] = 'ciyashop_shortcode_vertical';

	return $value;
}
add_filter( 'default_option_megamenu_settings', 'ciyashop_megamenu_override_default_theme' );

add_filter( 'megamenu_nav_menu_args', 'ciyashop_megamenu_add_menu_slug_class', 10, 3 );
/**
 * Megamenu add menu slug class
 *
 * @param string $defaults .
 * @param string $menu_id .
 * @param string $current_theme_location .
 */
function ciyashop_megamenu_add_menu_slug_class( $defaults, $menu_id, $current_theme_location ) {

	$theme = '';

	$settings = get_option( 'megamenu_settings' );
	if ( isset( $settings[ $current_theme_location ] ) && is_array( $settings[ $current_theme_location ] ) && ! empty( $settings[ $current_theme_location ] ) ) {
		$theme_settings = $settings[ $current_theme_location ];
		if ( isset( $theme_settings['theme'] ) && ! empty( $theme_settings['theme'] ) ) {
			$theme = $theme_settings['theme'];
		}
	}

	if ( $theme ) {
		$defaults['container_class'] = $defaults['container_class'] . " mega-menu-wrap-theme-{$theme}";
		$defaults['menu_class']      = $defaults['menu_class'] . " mega-menu-theme-{$theme}";
	}

	return $defaults;
}
