<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Theme function
 *
 * @package CiyaShop
 */

/**
 * Add action hook to generate color customize.
 */
add_action( 'init', 'ciyashop_version_generate_color_customize_css', 99 );

if ( ! function_exists( 'ciyashop_version_generate_color_customize_css' ) ) {
	/**
	 * Generate color customize css.
	 */
	function ciyashop_version_generate_color_customize_css() {
		global $ciyashop_options;

		if ( ! class_exists( 'Redux' ) ) {
			return;
		}

		$header_schema        = '';
		$upload               = wp_upload_dir();
		$color_customize_file = $upload['basedir'] . '/ciyashop/color_customize.css';

		if ( ! file_exists( $color_customize_file ) ) {
			update_option( 'theme_version', false );
		}

		$theme_version = get_option( 'theme_version', false );

		if ( ! version_compare( THEME_VERSION, $theme_version ) ) {
			return;
		}

		// Generate the color customizer CSS.
		$primary_color   = isset( $ciyashop_options['primary_color'] ) ? esc_html( $ciyashop_options['primary_color'] ) : '';
		$secondary_color = isset( $ciyashop_options['secondary_color'] ) ? esc_html( $ciyashop_options['secondary_color'] ) : '';
		$tertiary_color  = isset( $ciyashop_options['tertiary_color'] ) ? esc_html( $ciyashop_options['tertiary_color'] ) : '';

		if ( isset( $ciyashop_options['header_type_select'] ) && 'predefined' !== $ciyashop_options['header_type_select'] ) {
			$header_schema = ciyashop_get_custom_header_schema();
		}

		$color_customize = ciyashop_get_color_scheme_colors( $primary_color, $secondary_color, $tertiary_color, $header_schema );
		ciyashop_generate_color_customize_css( $color_customize );

		update_option( 'theme_version', THEME_VERSION );
	}
}


/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails
 *
 * @param array $attr attributes.
 * @param array $attachment attachment.
 * @param int   $size size.
 */
function ciyashop_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( 'post-thumbnails' === $size ) {
		is_active_sidebar( 'sidebar-1' ) && $attr['sizes']   = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 60vw, (max-width: 1362px) 62vw, 840px'; // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found
		! is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 88vw, 1200px'; // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'ciyashop_post_thumbnail_sizes_attr', 10, 3 );

/**
 * Layout
 */
function ciyashop_site_layout() {
	global $ciyashop_options;

	$site_layout = 'fullwidth';

	if ( isset( $ciyashop_options['site_layout'] ) && ! empty( $ciyashop_options['site_layout'] ) ) {
		$site_layout = $ciyashop_options['site_layout'];
	}

	/**
	 * Filter the layout of the site.
	 *
	 * @since 1.0.0
	 *
	 * @param     string       $site_layout         Site layout.
	 * @param     array        $ciyashop_options    An array of theme options.
	 *
	 * @visible true
	 */
	$site_layout = apply_filters( 'ciyashop_site_layout', $site_layout, $ciyashop_options );

	return $site_layout;
}

/**
 * Logo Settings
 */
function ciyashop_logo_type() {
	global $ciyashop_options;

	$logo_type = 'image';
	if ( isset( $ciyashop_options['logo_type'] ) && ! empty( $ciyashop_options['logo_type'] ) ) {
		$logo_type = $ciyashop_options['logo_type'];
	}

	/**
	 * Filter the type of logo.
	 *
	 * @since 1.0.0
	 *
	 * @param     string       $logo_type           Logo Type.
	 * @param     array        $ciyashop_options    An array of theme options.
	 *
	 * @visible true
	 */
	$logo_type = apply_filters( 'ciyashop_logo_type', $logo_type, $ciyashop_options );

	return $logo_type;
}

/**
 * Logo URL
 */
function ciyashop_logo_url() {
	global $ciyashop_options;

	$logo_url = get_parent_theme_file_uri( '/images/logo.png' );

	if ( isset( $ciyashop_options['site-logo'] ) && ! empty( $ciyashop_options['site-logo']['url'] ) ) {
		$logo_url = $ciyashop_options['site-logo']['url'];
	}

	// Replace with Mobile log if on mobile device.
	if ( wp_is_mobile() && ciyashop_mobile_logo_url() ) {
		$logo_url = ciyashop_mobile_logo_url();
	}

	/**
	 * Filter the url of logo.
	 *
	 * @since 1.0.0
	 *
	 * @param     string       $logo_url            Logo URL.
	 * @param     array        $ciyashop_options    An array of theme options.
	 *
	 * @visible true
	 */
	$logo_url = apply_filters( 'ciyashop_logo_url', $logo_url, $ciyashop_options );

	return $logo_url;
}

/**
 * Sticky Logo URL
 */
function ciyashop_sticky_logo_url() {
	global $ciyashop_options;

	$logo_url = get_parent_theme_file_uri( '/images/logo-sticky.png' );

	if ( isset( $ciyashop_options['sticky-logo'] ) && ! empty( $ciyashop_options['sticky-logo']['url'] ) ) {
		$logo_url = $ciyashop_options['sticky-logo']['url'];
	}

	/**
	 * Filter the url of sticky logo.
	 *
	 * @since 1.0.0
	 *
	 * @param     string       $logo_url            Sticky logo URL.
	 * @param     array        $ciyashop_options    An array of theme options.
	 *
	 * @visible true
	 */
	$logo_url = apply_filters( 'ciyashop_sticky_logo_url', $logo_url, $ciyashop_options );

	return $logo_url;
}

/**
 * Mobile Logo URL
 */
function ciyashop_mobile_logo_url() {
	global $ciyashop_options;

	$logo_url = false;

	if ( isset( $ciyashop_options['mobile-logo'] ) && ! empty( $ciyashop_options['mobile-logo']['url'] ) ) {
		$logo_url = $ciyashop_options['mobile-logo']['url'];
	}

	/**
	 * Filter the url of mobile logo.
	 *
	 * @since 1.0.0
	 *
	 * @param     string       $logo_url            Mobile logo URL.
	 * @param     array        $ciyashop_options    An array of theme options.
	 *
	 * @visible true
	 */
	$logo_url = apply_filters( 'ciyashop_mobile_logo_url', $logo_url, $ciyashop_options );

	return $logo_url;
}


if ( ! function_exists( 'ciyashop_get_site_mobile_logo' ) ) :
	/**
	 * Site Mobile logo settings
	 */
	function ciyashop_get_site_mobile_logo() {
		global $ciyashop_options;

		if ( isset( $ciyashop_options['logo_type'] ) && 'image' === $ciyashop_options['logo_type'] && isset( $ciyashop_options['mobile-logo'] ) && isset( $ciyashop_options['mobile-logo']['url'] ) ) {
			return $ciyashop_options['mobile-logo']['url'];
		}

		return false;
	}
endif;

if ( ! function_exists( 'ciyashop_get_site_sticky_logo' ) ) :
	/**
	 * Site sticky logo settings
	 */
	function ciyashop_get_site_sticky_logo() {
		global $ciyashop_options;

		if ( isset( $ciyashop_options['logo_type'] ) && 'image' === $ciyashop_options['logo_type'] && isset( $ciyashop_options['sticky-logo'] ) && isset( $ciyashop_options['sticky-logo']['url'] ) ) {
			return $ciyashop_options['sticky-logo']['url'];
		}

		return false;
	}
endif;

/**
 * Site multi lang settings
 */
function ciyashop_get_multi_lang() {
	global $ciyashop_options;

	/**
	 * Checl WPML sitepress multilingual plugin activate
	 */
	if ( ciyashop_check_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) && function_exists( 'icl_get_languages' ) ) {
		$ls_settings = get_option( 'icl_sitepress_settings' );
		$languages   = icl_get_languages();
		if ( ! empty( $languages ) ) {
			?>
			<div class="language" id="drop">
				<?php
				/* Display Current language */
				foreach ( $languages as $k => $al ) {
					if ( 1 === (int) $al['active'] ) {
						$flag_url_stat = ciyashop_wpml_custom_flag_exists( $al['country_flag_url'] );
						?>
						<a href="#">
							<?php
							if ( $al['country_flag_url'] && $flag_url_stat ) {
								?>
								<img src="<?php echo esc_url( $al['country_flag_url'] ); ?>" height="12" alt="<?php echo esc_attr( $al['language_code'] ); ?>" width="18" />&nbsp;
								<?php
							}
							echo icl_disp_language( $al['native_name'], $al['translated_name'] ) . '&nbsp;<i class="fa fa-angle-down">&nbsp;</i>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
							?>
						</a>
						<?php
						unset( $languages[ $k ] );
						break;
					}
				}
				?>
				<ul class="drop-content">
					<?php
					foreach ( $languages as $l ) {
						if ( ! $l['active'] ) {
							$flag_url_stat = ciyashop_wpml_custom_flag_exists( $l['country_flag_url'] );
							?>
							<li>
								<a href="<?php echo esc_url( $l['url'] ); ?>">
									<?php
									if ( $l['country_flag_url'] && $flag_url_stat ) {
										?>
										<img src="<?php echo esc_url( $l['country_flag_url'] ); ?>" height="12" alt="<?php echo esc_attr( $l['language_code'] ); ?>" width="18" />
										<?php
									}
									if ( isset( $ls_settings['icl_lso_flags'] ) && 1 === (int) $ls_settings['icl_lso_flags'] ) {
										echo icl_disp_language( $l['native_name'], $l['translated_name'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
									} else {
										echo icl_disp_language( $l['native_name'] ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
									}
									?>
								</a>
							</li>
							<?php
						}
					}
					?>
				</ul>
			</div>
			<?php
		}
	}
}

/**
 * Site login options settings
 *
 * @param string   $items items.
 * @param stdClass $args arguments.
 */
function ciyashop_get_login_options( $items, $args ) {
	/**
	 * Filters active plugins.
	 *
	 * @param array $active_plugins List of active plugins.
	 *
	 * @visible false
	 * @ignore
	 */
	if ( function_exists( 'WC' ) ) {
		if ( ! is_user_logged_in() && 'top_menu' === $args->theme_location ) {
			$items .= '<li><a href="javascript:void(0);" data-toggle="modal" data-target="#pgs_login_form"><i class="fa fa-sign-in">&nbsp;</i> ' . esc_html__( 'Login', 'ciyashop' ) . '</a></li>';
		} elseif ( is_user_logged_in() && 'top_menu' === $args->theme_location ) {
			$items .= '<li><a href="' . esc_url( wp_logout_url( home_url() ) ) . '" title="' . esc_attr__( 'Logout', 'ciyashop' ) . '" class="logout"><i class="fa fa-sign-out">&nbsp;</i>  ' . esc_html__( 'Logout', 'ciyashop' ) . '</a></li>';
		}
	}
	return $items;
}
add_filter( 'wp_nav_menu_items', 'ciyashop_get_login_options', 10, 2 );

/**
 * Ciyashop footer copyright
 */
function ciyashop_footer_copyright() {
	global $ciyashop_options;

	$allowed_tags = wp_kses_allowed_html( 'post' );

	$footer_copyright = sprintf(
		esc_html__( 'Copyright', 'ciyashop' ) . ' &copy; <span class="copy_year">%1$s</span>, <a href="%2$s" title="%3$s">%4$s</a> ' . esc_html__( 'All Rights Reserved.', 'ciyashop' ),
		gmdate( 'Y' ),
		esc_url( home_url( '/' ) ),
		esc_attr( get_bloginfo( 'name', 'display' ) ),
		esc_html( get_bloginfo( 'name', 'display' ) )
	);

	if ( isset( $ciyashop_options['footer_copyright'] ) && ! empty( $ciyashop_options['footer_copyright'] ) ) {
		$footer_copyright = $ciyashop_options['footer_copyright'];
		$footer_copyright = do_shortcode( $footer_copyright );
	}

	/**
	 * Filter the footer copyright content.
	 *
	 * @since 1.0.0
	 *
	 * @param     string       $footer_copyright    Footer copyright content.
	 * @param     array        $ciyashop_options    An array of theme options.
	 *
	 * @visible true
	 */
	$footer_copyright = apply_filters( 'ciyashop_footer_copyright', $footer_copyright, $ciyashop_options );

	/**
	 * Filter the content before copyright content.
	 *
	 * @since 1.0.0
	 *
	 * @param     string       $before_copyright    Before copyright content. Default null.
	 * @param     array        $ciyashop_options    An array of theme options.
	 *
	 * @visible true
	 */
	$footer_copyright_before = apply_filters( 'ciyashop_footer_copyright_before', '' );

	/**
	 * Filter the content after copyright content.
	 *
	 * @since 1.0.0
	 *
	 * @param     string       $after_copyright     After copyright content. Default null.
	 * @param     array        $ciyashop_options    An array of theme options.
	 *
	 * @visible true
	 */
	$footer_copyright_after = apply_filters( 'ciyashop_footer_copyright_after', '' );

	$footer_copyright = $footer_copyright_before . $footer_copyright . $footer_copyright_after;

	echo wp_kses(
		$footer_copyright,
		array(
			'a'      => $allowed_tags['a'],
			'br'     => $allowed_tags['br'],
			'em'     => $allowed_tags['em'],
			'strong' => $allowed_tags['strong'],
			'span'   => $allowed_tags['span'],
			'div'    => $allowed_tags['div'],
		)
	);
}

/**
 * Footer Creadits
 */
function ciyashop_footer_credits() {
	global $ciyashop_options;

	$ciyashop_credits = sprintf(
		// Translators: %s is the theme credit link.
		esc_html__( 'Developed and designed by %s', 'ciyashop' ),
		'<a href="' . esc_url( 'http://www.potenzaglobalsolutions.com/' ) . '">' . esc_html__( 'Potenza Global Solutions', 'ciyashop' ) . '</a>'
	);

	/**
	 * Filter the footer credit content.
	 *
	 * @since 1.0.0
	 *
	 * @param     string       $ciyashop_credits    Credit content.
	 * @param     array        $ciyashop_options    An array of theme options.
	 *
	 * @visible true
	 */
	$ciyashop_credits = apply_filters( 'ciyashop_credits', $ciyashop_credits, $ciyashop_options );

	echo wp_kses( $ciyashop_credits, ciyashop_allowed_html( array( 'i', 'a', 'span', 'div', 'ul', 'ol', 'li' ) ) );
}

/**
 * Get product listing page sidebar settings.
 */
function ciyashop_shop_page_sidebar() {
	global $ciyashop_options;

	$shop_sidebar = ( isset( $ciyashop_options['shop_sidebar'] ) && ! empty( $ciyashop_options['shop_sidebar'] ) ) ? $ciyashop_options['shop_sidebar'] : 'left';

	return $shop_sidebar;
}

/**
 * Get product page sidebar settings.
 */
function ciyashop_product_page_sidebar() {
	global $ciyashop_options;

	$product_sidebar = ( isset( $ciyashop_options['product-page-sidebar'] ) && ! empty( $ciyashop_options['product-page-sidebar'] ) ) ? $ciyashop_options['product-page-sidebar'] : 'left';

	return $product_sidebar;
}

/**
 * Get sidebar on Mobile.
 */
function ciyashop_show_sidebar_on_mobile() {
	global $ciyashop_options;

	$show_sidebar_on_mobile = true;

	if ( ! $ciyashop_options || ! isset( $ciyashop_options['show_sidebar_on_mobile'] ) || is_null( $ciyashop_options['show_sidebar_on_mobile'] ) ) {
		return $show_sidebar_on_mobile;
	}

	return (bool) $ciyashop_options['show_sidebar_on_mobile'];
}

/**
 * Get sidebar position on Mobile.
 */
function ciyashop_mobile_sidebar_position() {
	global $ciyashop_options;

	if ( isset( $ciyashop_options['shop_sidebar_position_mobile'] ) && ! empty( $ciyashop_options['shop_sidebar_position_mobile'] ) ) {
		return $ciyashop_options['shop_sidebar_position_mobile'];
	}
	return 'bottom';
}
/**
 * Ciyashop device type
 */
function ciyashop_device_type() {
	return wp_is_mobile() ? 'mobile' : 'desktop';
}

// Change number or products per row to 3.
add_filter( 'loop_shop_columns', 'ciyashop_loop_columns' );
if ( ! function_exists( 'ciyashop_loop_columns' ) ) {
	/**
	 * Ciyashop loop columns
	 */
	function ciyashop_loop_columns() {
		global $ciyashop_options;
		$pro_col_sel = 4;
		if ( isset( $ciyashop_options['pro-col-sel'] ) && ! empty( $ciyashop_options['pro-col-sel'] ) ) {
			$pro_col_sel = $ciyashop_options['pro-col-sel'];
		}
		return $pro_col_sel; // 3 products per row
	}
}

add_filter( 'bcn_breadcrumb_title', 'ciyashop_bcn_breadcrumb_title', 10, 3 );
/**
 * Change site name to home in breadcrumb
 *
 * @param string $title .
 * @param string $type .
 * @param string $id .
 */
function ciyashop_bcn_breadcrumb_title( $title, $type, $id ) {
	if ( 'home' === $type[0] ) {
		$title = esc_html__( 'Home', 'ciyashop' );
	}
	return $title;
}
/**
 * Class builder
 *
 * @param string $class .
 */
function ciyashop_class_builder( $class = '' ) {
	$classes = array();

	if ( $class ) {
		if ( ! is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}
		$classes = array_map( 'esc_attr', $class );
	} else {
		// Ensure that we always coerce class to being an array.
		$class = array();
	}
	$classes = array_map( 'esc_attr', $classes );

	return implode( ' ', array_filter( array_unique( $classes ) ) );
}
/**
 * Header type
 */
function ciyashop_header_type() {

	global $ciyashop_options;

	if ( isset( $ciyashop_options['header_type_select'] ) && 'header_builder' === $ciyashop_options['header_type_select'] ) {
		$ciyashop_header_type = 'custom';
	} else {
		$ciyashop_header_type = ( isset( $ciyashop_options['header_type'] ) && $ciyashop_options['header_type'] ) ? $ciyashop_options['header_type'] : 'menu-center';
	}

	/**
	 * Filter header_type.
	 *
	 * @since 1.0.0
	 *
	 * @param     string       $header_type         Header Type.
	 * @param     array        $ciyashop_options    An array of theme options.
	 *
	 * @visible true
	 */
	$ciyashop_header_type = apply_filters( 'ciyashop_header_type', $ciyashop_header_type, $ciyashop_options );

	return $ciyashop_header_type;
}

if ( ! function_exists( 'ciyashop_footer_type' ) ) {
	/**
	 * Get Footer Type
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	function ciyashop_footer_type() {

		global $ciyashop_options, $post;

		$ciyashop_footer_type = ( ! empty( $ciyashop_options['footer_type_select'] ) ) ? $ciyashop_options['footer_type_select'] : 'default';

		/**
		 * Filter header_type_select.
		 *
		 * @since 1.0.0
		 *
		 * @param string $ciyashop_footer_type Footer Type.
		 * @param array $ciyashop_options An array of theme options.
		 *
		 * @visible true
		 */
		$ciyashop_footer_type = apply_filters( 'ciyashop_footer_type', $ciyashop_footer_type, $ciyashop_options );

		return $ciyashop_footer_type;
	}
}

if ( ! function_exists( 'ciyashop_get_custom_footer_data' ) ) {
	/**
	 * Get Footer data
	 *
	 * @since  1.0.0
	 *
	 * @param  int $footer_id footer id.
	 * @return array
	 */
	function ciyashop_get_custom_footer_data( $footer_id = '' ) {
		global $ciyashop_options, $wpdb;

		if ( ! $footer_id ) {
			if ( function_exists( 'ciyashop_footer_type' ) && ciyashop_footer_type() ) {
				if ( 'footer_builder' === ciyashop_footer_type() ) {
					if ( isset( $ciyashop_options['custom_footers'] ) ) {
						if ( $ciyashop_options['custom_footers'] ) {
							$footer_id = $ciyashop_options['custom_footers'];
						} else {
							return false;
						}
					}
				}
			}

			if ( ! $footer_id ) {
				return false;
			}
		}

		$footer_layout_data = $wpdb->get_results(
			$wpdb->prepare(
				'
				SELECT * FROM ' . $wpdb->prefix . 'cs_footer_builder
				WHERE id = %d
				',
				$footer_id
			)
		);

		if ( ! $footer_layout_data ) {
			return false;
		}
		$footer_data = unserialize( $footer_layout_data[0]->value );

		return $footer_data;
	}
}

/**
 * Categories menu status
 */
function ciyashop_categories_menu_status() {

	global $ciyashop_options;

	if ( isset( $ciyashop_options['categories_menu_status'] ) && ! empty( $ciyashop_options['categories_menu_status'] ) ) {
		$ciyashop_header_type   = ciyashop_header_type();
		$categories_menu_status = in_array( $ciyashop_header_type, array( 'default', 'logo-center', 'topbar-with-main-header' ), true ) ? $ciyashop_options['categories_menu_status'] : 'disable';
	} else {
		$categories_menu_status = 'disable';
	}

	/**
	 * Filters the status of the categories menu.
	 *
	 * @since 1.0.0
	 *
	 * @param     string       $menu_status         Categories menu status.
	 * @param     array        $ciyashop_options    An array of theme options.
	 *
	 * @visible true
	 */
	$categories_menu_status = apply_filters( 'ciyashop_categories_menu_status', $categories_menu_status, $ciyashop_options );

	return $categories_menu_status;
}
/**
 * Topbar enable
 */
function ciyashop_topbar_enable() {

	global $ciyashop_options;

	$topbar_enable_stat = ( isset( $ciyashop_options['topbar_enable'] ) ) ? $ciyashop_options['topbar_enable'] : false;

	if ( $topbar_enable_stat ) {
		$topbar_enable = 'enable';
	} else {
		$topbar_enable = 'disable';
	}

	/**
	 * Filters whether to enable topbar.
	 *
	 * @since 1.0.0
	 *
	 * @param     boolean      $topbar_enable       Whether to enable the topbar.
	 * @param     array        $ciyashop_options    An array of theme options.
	 *
	 * @visible true
	 */
	$topbar_enable = apply_filters( 'ciyashop_topbar_enable', $topbar_enable, $ciyashop_options );

	return $topbar_enable;
}
/**
 * Topbar mobile enable
 */
function ciyashop_topbar_mobile_enable() {

	global $ciyashop_options;

	$topbar_mobile_enable_stat = ( isset( $ciyashop_options['topbar_mobile_enable'] ) ) ? $ciyashop_options['topbar_mobile_enable'] : true;

	if ( $topbar_mobile_enable_stat && 'enable' === ciyashop_topbar_enable() ) {
		$topbar_mobile_enable = 'enable';
	} else {
		$topbar_mobile_enable = 'disable';
	}

	/**
	 * Filters whether to enable topbar in mobile.
	 *
	 * @since 1.0.0
	 *
	 * @param     string      $enable_topbar        Whether to enable topbar in mobile.
	 * @param     array       $ciyashop_options     An array of theme options.
	 *
	 * @visible true
	 */
	$topbar_mobile_enable = apply_filters( 'ciyashop_topbar_mobile_enable', $topbar_mobile_enable, $ciyashop_options );

	return $topbar_mobile_enable;
}
/**
 * Social profiles
 */
function ciyashop_social_profiles() {
	global $ciyashop_options;

	/**
	 * Filters the list of social profiles.
	 *
	 * @param    array    $social_profiles        An array of social profiles.
	 *
	 * @visible true
	 */
	$social_profiles = apply_filters(
		'ciyashop_social_profiles',
		array(
			'facebook'   => array(
				'key'  => 'facebook',
				'name' => esc_html__( 'Facebook', 'ciyashop' ),
				'icon' => '<i class="fa fa-facebook"></i>',
			),
			'twitter'    => array(
				'key'  => 'twitter',
				'name' => esc_html__( 'Twitter', 'ciyashop' ),
				'icon' => '<i class="fa fa-twitter"></i>',
			),
			'googleplus' => array(
				'key'  => 'googleplus',
				'name' => esc_html__( 'Google+', 'ciyashop' ),
				'icon' => '<i class="fa fa-google-plus"></i>',
			),
			'dribbble'   => array(
				'key'  => 'Dribbble',
				'name' => esc_html__( 'Dribbble', 'ciyashop' ),
				'icon' => '<i class="fa fa-dribbble"></i>',
			),
			'vimeo'      => array(
				'key'  => 'vimeo',
				'name' => esc_html__( 'Vimeo', 'ciyashop' ),
				'icon' => '<i class="fa fa-vimeo"></i>',
			),
			'pinterest'  => array(
				'key'  => 'pinterest',
				'name' => esc_html__( 'Pinterest', 'ciyashop' ),
				'icon' => '<i class="fa fa-pinterest"></i>',
			),
			'behance'    => array(
				'key'  => 'behance',
				'name' => esc_html__( 'Behance', 'ciyashop' ),
				'icon' => '<i class="fa fa-behance"></i>',
			),
			'linkedin'   => array(
				'key'  => 'linkedin',
				'name' => esc_html__( 'Linkedin', 'ciyashop' ),
				'icon' => '<i class="fa fa-linkedin"></i>',
			),
			'youtube'    => array(
				'key'  => 'youtube',
				'name' => esc_html__( 'YouTube', 'ciyashop' ),
				'icon' => '<i class="fa fa-youtube-play"></i>',
			),
			'instagram'  => array(
				'key'  => 'instagram',
				'name' => esc_html__( 'instagram', 'ciyashop' ),
				'icon' => '<i class="fa fa-instagram"></i>',
			),
		)
	);

	if ( ! isset( $ciyashop_options['social_media_icons'] ) || empty( $ciyashop_options['social_media_icons'] ) ) {
		return '';
	}
	$total_social_icon = count( $ciyashop_options['social_media_icons']['redux_repeater_data'] );

	$social_profiles_data = array();
	for ( $i = 0; $i < $total_social_icon; $i++ ) {
		if ( 'custom' === $ciyashop_options['social_media_icons']['social_media_type'][ $i ] ) {
			$social_icon_href = $ciyashop_options['social_media_icons']['social_media_url'][ $i ];
			$social_icon      = $ciyashop_options['social_media_icons']['custom_soical_icon'][ $i ];
			$social_title     = $ciyashop_options['social_media_icons']['custom_social_title'][ $i ];

			if ( ! empty( $social_icon_href ) && ! empty( $social_icon ) ) {
				$social_profiles_data[] = array(
					'link'  => esc_url( $social_icon_href ),
					'icon'  => '<i class="' . esc_attr( 'fa ' . $social_icon ) . '"></i>',
					'title' => esc_attr( $social_title ),
				);
			}
		} else {
			$social_icon_href = isset( $ciyashop_options['social_media_icons']['social_media_url'][ $i ] ) ? $ciyashop_options['social_media_icons']['social_media_url'][ $i ] : '';
			$social_icon      = $ciyashop_options['social_media_icons']['social_media_type'][ $i ];
			$social_title     = ucfirst( $ciyashop_options['social_media_icons']['social_media_type'][ $i ] );

			if ( ! empty( $social_icon_href ) && isset( $social_profiles[ $social_icon ]['icon'] ) ) {
				$social_profiles_data[] = array(
					'link'  => esc_url( $social_icon_href ),
					'icon'  => $social_profiles[ $social_icon ]['icon'],
					'title' => esc_attr( $social_title ),
				);
			}
		}
	}
	return $social_profiles_data;
}

/**
 * Opening hours
 */
function ciyashop_opening_hours() {
	global $ciyashop_options;

	$ciyashop_opening_hours_data = array(

		esc_html__( 'Monday', 'ciyashop' )    => isset( $ciyashop_options['mon_time'] ) ? $ciyashop_options['mon_time'] : '',
		esc_html__( 'Tuesday', 'ciyashop' )   => isset( $ciyashop_options['tue_time'] ) ? $ciyashop_options['tue_time'] : '',
		esc_html__( 'Wednesday', 'ciyashop' ) => isset( $ciyashop_options['wed_time'] ) ? $ciyashop_options['wed_time'] : '',
		esc_html__( 'Thursday', 'ciyashop' )  => isset( $ciyashop_options['thu_time'] ) ? $ciyashop_options['thu_time'] : '',
		esc_html__( 'Friday', 'ciyashop' )    => isset( $ciyashop_options['fri_time'] ) ? $ciyashop_options['fri_time'] : '',
		esc_html__( 'Saturday', 'ciyashop' )  => isset( $ciyashop_options['sat_time'] ) ? $ciyashop_options['sat_time'] : '',
		esc_html__( 'Sunday', 'ciyashop' )    => isset( $ciyashop_options['sun_time'] ) ? $ciyashop_options['sun_time'] : '',

	);
	return $ciyashop_opening_hours_data;
}

/**
 * Return whether Visual Composer is enabled on a page/post or not.
 *
 * @param string $post_id .
 * $post_id = numeric post_id.
 * return true/false.
 */
function ciyashop_is_vc_enabled( $post_id = '' ) {
	global $post;

	if ( ! empty( $post_id ) ) {
		$post = get_post( $post_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
	}

	if ( is_search() || ( empty( $post ) ) ) {
		return;
	}

	if ( empty( $post_id ) ) {
		$post_id = $post->ID;
	}
	$vc_enabled = get_post_meta( $post_id, '_wpb_vc_js_status', true );
	return ( 'true' === $vc_enabled ) ? true : false;
}

/**
 * Converts a multidimensional array of CSS rules into a CSS string.
 *
 * @param array $rules .
 * @param int   $indent .
 *   An array of CSS rules in the form of:
 *   array('selector'=>array('property' => 'value')). Also supports selector
 *   nesting, e.g.,
 *   array('selector' => array('selector'=>array('property' => 'value'))).
 *
 * @return string
 *   A CSS string of rules. This is not wrapped in style tags.
 *
 * source : http://www.grasmash.com/article/convert-nested-php-array-css-string
 */
function ciyashop_generate_css_properties( $rules, $indent = 0 ) {
	$css    = '';
	$prefix = str_repeat( '  ', $indent );
	foreach ( $rules as $key => $value ) {
		if ( is_array( $value ) ) {
			$selector   = $key;
			$properties = $value;

			$css .= $prefix . "$selector {\n";
			$css .= $prefix . ciyashop_generate_css_properties( $properties, $indent + 1 );
			$css .= $prefix . "}\n";
		} else {
			$property = $key;
			$css     .= $prefix . "$property: $value;\n";
		}
	}
	return $css;
}

add_action( 'wp_footer', 'ciyashop_promo_popup' );
/**
 * Promo popup
 */
function ciyashop_promo_popup() {
	global $ciyashop_options;
	$content_css = '';

	if ( isset( $ciyashop_options['promo_popup'] ) && $ciyashop_options['promo_popup'] ) {

		$content_style = array();

		// Background.
		if ( isset( $ciyashop_options['popup-background']['background-color'] ) && ! empty( $ciyashop_options['popup-background']['background-color'] ) && 'transparent' !== $ciyashop_options['popup-background']['background-color'] ) {
			$content_style['background-color'] = 'background-color:' . $ciyashop_options['popup-background']['background-color'];
		}
		if ( isset( $ciyashop_options['popup-background']['background-repeat'] ) && ! empty( $ciyashop_options['popup-background']['background-repeat'] ) ) {
			$content_style['background-repeat'] = 'background-repeat:' . $ciyashop_options['popup-background']['background-repeat'];
		}
		if ( isset( $ciyashop_options['popup-background']['background-size'] ) && ! empty( $ciyashop_options['popup-background']['background-size'] ) ) {
			$content_style['background-size'] = 'background-size:' . $ciyashop_options['popup-background']['background-size'];
		}
		if ( isset( $ciyashop_options['popup-background']['background-attachment'] ) && ! empty( $ciyashop_options['popup-background']['background-attachment'] ) ) {
			$content_style['background-attachment'] = 'background-attachment:' . $ciyashop_options['popup-background']['background-attachment'];
		}
		if ( isset( $ciyashop_options['popup-background']['background-position'] ) && ! empty( $ciyashop_options['popup-background']['background-position'] ) ) {
			$content_style['background-position'] = 'background-position:' . $ciyashop_options['popup-background']['background-position'];
		}
		if ( isset( $ciyashop_options['popup-background']['background-image'] ) && ! empty( $ciyashop_options['popup-background']['background-image'] ) ) {
			$content_style['background-image'] = 'background-image:url(' . $ciyashop_options['popup-background']['background-image'] . ')';
		}
		if ( $content_style && is_array( $content_style ) && ! empty( $content_style ) ) {
			$content_css = implode( ';', array_filter( array_unique( $content_style ) ) );
		}
		?>
		<div class="ciyashop-promo-popup mfp-hide">
			<div class="ciyashop-popup-inner" style="<?php echo esc_attr( $content_css ); ?>">
				<?php echo do_shortcode( $ciyashop_options['popup_text'] ); ?>
			</div>
		</div>
		<?php
	}
}

// Source : https://support.advancedcustomfields.com/forums/topic/color-picker-values/.
/**
 * Convert hexdec color string to rgb(a) string.
 *
 * @param string $color .
 * @param bool   $opacity .
 */
function ciyashop_hex2rgba( $color = '', $opacity = false ) {

	$default = 'rgb(0,0,0)';

	// Return default if no color provided.
	if ( empty( $color ) ) {
		return $default;
	}

	// Sanitize $color if "#" is provided.
	if ( '#' === (string) $color[0] ) {
		$color = substr( $color, 1 );
	}

	// Check if color has 6 or 3 characters and get values.
	if ( 6 === (int) strlen( $color ) ) {
		$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
	} elseif ( 3 === (int) strlen( $color ) ) {
		$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
	} else {
		return $default;
	}

	// Convert hexadec to rgb.
	$rgb = array_map( 'hexdec', $hex );

	// Check if opacity is set(rgba or rgb).
	if ( $opacity ) {
		if ( abs( $opacity ) > 1 ) {
			$opacity = 1.0;
		}
		$output = 'rgba(' . implode( ',', $rgb ) . ',' . $opacity . ')';
	} else {
		$output = 'rgb(' . implode( ',', $rgb ) . ')';
	}

	// Return rgb(a) color string.
	return $output;
}
/**
 * Hex2rgb
 *
 * @param string $color .
 */
function ciyashop_hex2rgb( $color ) {
	if ( '#' === (string) $color[0] ) {
		$color = substr( $color, 1 );
	}
	if ( 6 === (int) strlen( $color ) ) {
		list( $r, $g, $b ) = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
	} elseif ( 3 === (int) strlen( $color ) ) {
		list( $r, $g, $b ) = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
	} else {
		return false;
	}

	$r = hexdec( $r );
	$g = hexdec( $g );
	$b = hexdec( $b );

	return array(
		'r' => $r,
		'g' => $g,
		'b' => $b,
	);
}
/**
 * Sticky_header
 */
function ciyashop_sticky_header() {
	global $ciyashop_options;

	$sticky_header = isset( $ciyashop_options['sticky_header'] ) ? $ciyashop_options['sticky_header'] : true;

	/**
	 * Filters whether to enable sticky header.
	 *
	 * @since 1.0.0
	 *
	 * @param     boolean       $enable_sticky      Whether to enable the sticky header.
	 * @param     array         $ciyashop_options   An array of theme options.
	 *
	 * @visible true
	 */
	$sticky_header = apply_filters( 'ciyashop_sticky_header', $sticky_header, $ciyashop_options );

	return $sticky_header;
}
/**
 * Mobile_sticky_header
 */
function ciyashop_mobile_sticky_header() {
	global $ciyashop_options;

	$ciyashop_sticky_header = ciyashop_sticky_header();

	if ( ! $ciyashop_sticky_header ) {
		return false;
	}

	$mobile_sticky_header = isset( $ciyashop_options['mobile_sticky_header'] ) ? $ciyashop_options['mobile_sticky_header'] : true;

	/**
	 * Filters whether to enable sticky header in mobile.
	 *
	 * @since 1.0.0
	 *
	 * @param     boolean       $enable_sticky      Whether to enable the sticky header in mobile.
	 * @param     array         $ciyashop_options   An array of theme options.
	 *
	 * @visible true
	 */
	$mobile_sticky_header = apply_filters( 'ciyashop_mobile_sticky_header', $mobile_sticky_header, $ciyashop_options );

	return $mobile_sticky_header;
}

/**
 * Display Related Post in single blog post
 */
function ciyashop_related_posts() {
	global $ciyashop_options;

	$related_posts = ( isset( $ciyashop_options['related_posts'] ) ) ? $ciyashop_options['related_posts'] : false;

	if ( 0 === (int) $related_posts ) {
		return;
	}

	$category_terms = wp_get_post_terms( get_the_ID(), 'category' );
	$cat_id         = array();
	if ( ! empty( $category_terms ) ) {
		foreach ( $category_terms as $value ) {
			$cat_id[] = $value->term_id;
		}
	}
	$args               = array(
		'post_type'      => 'post',
		'posts_per_page' => 10,
		'post__not_in'   => array( get_the_ID() ),
		'tax_query'      => array(
			array(
				'taxonomy' => 'category',
				'field'    => 'id',
				'terms'    => $cat_id,
			),
		),
	);
	$related_post_query = new WP_Query( $args );

	$owl_options_args = array(
		'items'              => 3,
		'loop'               => false,
		'dots'               => false,
		'nav'                => true,
		'margin'             => 10,
		'autoplay'           => true,
		'autoplayHoverPause' => true,
		'smartSpeed'         => 1000,
		'responsive'         => array(
			0    => array(
				'items' => 1,
			),
			480  => array(
				'items' => 1,
			),
			577  => array(
				'items' => 2,
			),
			980  => array(
				'items' => 3,
			),
			1200 => array(
				'items' => 3,
			),
		),
		'navText'            => array(
			"<i class='fas fa-angle-left fa-2x'></i>",
			"<i class='fas fa-angle-right fa-2x'></i>",
		),
		'lazyLoad'           => ( isset( $ciyashop_options['enable_lazyload'] ) && $ciyashop_options['enable_lazyload'] ) ? true : false,
	);

	$owl_options = wp_json_encode( $owl_options_args );
	if ( $related_post_query->have_posts() ) {
		?>
		<div class="related-posts">
			<h3 class="title"><?php esc_html_e( 'Related Posts', 'ciyashop' ); ?></h3>
			<div class="related-posts-section">
				<div class="owl-carousel owl-theme owl-carousel-options" data-owl_options="<?php echo esc_attr( $owl_options ); ?>">
				<?php
				while ( $related_post_query->have_posts() ) {
					$related_post_query->the_post();
					?>
					<div class="item">
						<div class="related-post-info">
							<div class="post-image clearfix<?php echo ( ! has_post_thumbnail() ) ? ' no-post-image' : ''; ?>">
								<?php
								if ( has_post_thumbnail() ) {
									if ( isset( $ciyashop_options['enable_lazyload'] ) && $ciyashop_options['enable_lazyload'] ) {
										echo '<img class="owl-lazy img-fluid" src="' . esc_url( LOADER_IMAGE ) . '" data-src="' . esc_url( get_the_post_thumbnail_url( '', 'ciyashop-latest-post-thumbnail' ) ) . '" alt="' . esc_attr( get_the_title() ) . '" />';
									} else {
										echo '<img class="img-fluid" src="' . esc_url( get_the_post_thumbnail_url( '', 'ciyashop-latest-post-thumbnail' ) ) . '" alt="' . esc_attr( get_the_title() ) . '" />';
									}
								}
								?>
							</div>
							<span class="post-<?php echo ( ! has_post_thumbnail() ) ? 'no-image-text' : 'image-text'; ?>">
								<?php the_title( '<h5 class="title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h5>' ); ?>
							</span>
						</div>
					</div>
					<?php
				}
				?>
				</div>
			</div>
		</div>
		<?php
		wp_reset_postdata();
	}
}

/**
 * Display Author box in single blog post
 */
function ciyashop_authorbox() {
	global $ciyashop_options;

	$author_details = ( isset( $ciyashop_options['author_details'] ) ) ? $ciyashop_options['author_details'] : true;

	if ( 0 === (int) $author_details ) {
		return;
	}

	if ( is_singular() && get_the_author_meta( 'description' ) ) : // If a user has filled out their description and this is a multi-author blog, show a bio on their entries.
		?>
		<div class="author-info">
			<div class="author-avatar media-left">
				<?php
				/**
				 * Filters avatar size of author.
				 *
				 * @since 1.0.0
				 *
				 * @param     int       $avatar_size      Avatar size. Default 68.
				 *
				 * @visible true
				 */
				$author_bio_avatar_size = apply_filters( 'ciyashop_author_bio_avatar_size', 68 );
				echo get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size );
				?>
			</div><!-- .author-avatar -->
			<div class="author-description media-body">
				<h5 class="title author-title">
					<?php
					printf(
						/* translators: %s: Name of athor */
						esc_html__( 'About %s', 'ciyashop' ),
						get_the_author()
					);
					?>
				</h5>
				<p><?php the_author_meta( 'description' ); ?></p>
			</div><!-- .author-description -->
		</div><!-- .author-info -->
		<?php
	endif;
}
/**
 * Get excerpt max charlength
 *
 * @param string $charlength .
 * @param string $excerpt .
 */
function ciyashop_get_excerpt_max_charlength( $charlength, $excerpt = null ) {
	if ( empty( $excerpt ) ) {
		$excerpt = get_the_excerpt();
	}
	$charlength++;

	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex   = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut   = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );

		$new_excerpt = '';
		if ( $excut < 0 ) {
			$new_excerpt = mb_substr( $subex, 0, $excut );
		} else {
			$new_excerpt = $subex;
		}
		$new_excerpt .= '[...]';
		return $new_excerpt;
	}

	return $excerpt;
}
/**
 * The excerpt max charlength
 *
 * @param string $charlength .
 * @param string $excerpt .
 */
function ciyashop_the_excerpt_max_charlength( $charlength, $excerpt = null ) {
	$ciyashop_excerpt = ciyashop_get_excerpt_max_charlength( $charlength, $excerpt );
	echo wp_kses_post( $ciyashop_excerpt );
}
/**
 * Validate CSS
 *
 * @param string $str .
 * @param array  $units .
 */
function ciyashop_validate_css_unit( $str = '', $units = array() ) {

	// bail early if any param is empty.
	if ( ! is_string( $str ) || empty( $str ) || ! is_array( $units ) || empty( $units ) ) {
		return false;
	}

	// prepare units string.
	$units_str = implode( '|', $units );

	// prepare regex.
	$reg_ex = '/^(auto|0)$|^[+-]?[0-9]+.?([0-9]+)?(' . $units_str . ')$/';

	// check match.
	preg_match_all( $reg_ex, $str, $matches, PREG_SET_ORDER, 0 );

	// check if matched found.
	if ( count( $matches ) > 0 ) {
		return true;
	}
	return false;
}

add_action( 'customize_save_response', 'ciyashop_customize_save_response', 10, 2 );
/**
 * Update Theme option favicon icon from customizer site icon
 *
 * @param string $response .
 * @param string $data .
 */
function ciyashop_customize_save_response( $response, $data ) {
	global $ciyashop_globals;

	if ( isset( $_POST['customized'] ) ) {
		$post_values = json_decode( sanitize_text_field( wp_unslash( $_POST['customized'] ) ), true );

		if ( isset( $post_values['site_icon'] ) && ! empty( $post_values['site_icon'] ) ) {
			$opt_name         = $ciyashop_globals['theme_option'];
			$ciyashop_options = get_option( $opt_name );
			$site_icon        = $post_values['site_icon'];
			$img              = wp_get_attachment_image_src( $site_icon, 'full' );
			$thumbnail        = wp_get_attachment_image_src( $site_icon, 'thumbnail' );

			$ciyashop_options['favicon_icon']['url']       = $img[0];
			$ciyashop_options['favicon_icon']['id']        = $site_icon;
			$ciyashop_options['favicon_icon']['width']     = $img[1];
			$ciyashop_options['favicon_icon']['height']    = $img[2];
			$ciyashop_options['favicon_icon']['thumbnail'] = $thumbnail[0];
			update_option( $opt_name, $ciyashop_options );
		}
	}

	return $response;
}

add_filter( 'redux/options/ciyashop_options/ajax_save/response', 'ciyashop_option_save' );
/**
 * Update customizer site icon from theme option favicon icon
 *
 * @param string $response .
 */
function ciyashop_option_save( $response ) {
	if ( isset( $response['options']['favicon_icon'] ) && ! empty( $response['options']['favicon_icon'] ) ) {
		$site_icon    = get_option( 'site_icon' );
		$favicon_icon = $response['options']['favicon_icon']['id'];
		if ( $favicon_icon !== $site_icon ) {
			update_option( 'site_icon', $favicon_icon );
		}
	}

	return $response;
}

/**
 * Get Product categories
 */
function ciyashop_get_product_categories() {
	$cat_titles = array();

	if ( is_admin() ) {
		$args = array(
			'type'         => 'post',
			'orderby'      => 'id',
			'order'        => 'DESC',
			'hide_empty'   => false,
			'hierarchical' => 1,
			'taxonomy'     => 'product_cat',
			'pad_counts'   => false,
		);

		$post_categories = get_categories( $args );

		foreach ( $post_categories as $cat ) {
			$cat_titles[ $cat->term_id ] = $cat->name;
		}
	}
	return $cat_titles;
}

if ( ! function_exists( 'ciyashop_array_unshift_assoc' ) ) {
	/**
	 * Array unshift
	 *
	 * @param array  $arr array.
	 * @param string $key key.
	 * @param string $val val.
	 */
	function ciyashop_array_unshift_assoc( &$arr, $key, $val ) {
		$arr         = array_reverse( $arr, true );
		$arr[ $key ] = $val;
		$arr         = array_reverse( $arr, true );
	}
}

/**
 * Allowed HTML
 *
 * @param string $allowed_els .
 */
function ciyashop_allowed_html( $allowed_els = '' ) {

	// bail early if parameter is empty.
	if ( empty( $allowed_els ) ) {
		return array();
	}

	if ( is_string( $allowed_els ) ) {
		$allowed_els = explode( ',', $allowed_els );
	}

	$allowed_html = array();

	$allowed_tags = wp_kses_allowed_html( 'post' );
	$svg_args     = array();
	foreach ( $allowed_els as $el ) {
		$el = trim( $el );
		if ( array_key_exists( $el, $allowed_tags ) ) {
			$allowed_html[ $el ] = $allowed_tags[ $el ];
		}
		if ( 'svg' === $el ) {
			$svg_args = array(
				'svg'   => array(
					'class'           => true,
					'aria-hidden'     => true,
					'aria-labelledby' => true,
					'role'            => true,
					'xmlns'           => true,
					'width'           => true,
					'height'          => true,
					'viewbox'         => true,
				),
				'g'     => array(
					'fill' => true,
				),
				'title' => array(
					'title' => true,
				),
				'path'  => array(
					'd'    => true,
					'fill' => true,
				),
			);
		}
	}
	if ( ! empty( $svg_args ) ) {
		$allowed_html = array_merge( $allowed_html, $svg_args );
	}
	return $allowed_html;
}

// Ciyashop auto complate search.
add_action( 'wp_ajax_ciyashop_auto_complete_search', 'ciyashop_auto_complete_search' );
add_action( 'wp_ajax_nopriv_ciyashop_auto_complete_search', 'ciyashop_auto_complete_search' );

if ( ! function_exists( 'ciyashop_auto_complete_search' ) ) {
	/**
	 * Auto compelete search
	 */
	function ciyashop_auto_complete_search() {
		global $ciyashop_options, $post;

		$ciyashop_nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $ciyashop_nonce, 'ciyashop_nonce' ) ) {
			return false;
			exit();
		}

		$search_keyword  = isset( $_POST['search_keyword'] ) ? sanitize_text_field( wp_unslash( $_POST['search_keyword'] ) ) : '';
		$search_category = isset( $_POST['search_category'] ) ? sanitize_text_field( wp_unslash( $_POST['search_category'] ) ) : '';

		$post_titles = array();
		$data        = array();

		$taxonomy  = ( 'product' === $ciyashop_options['search_content_type'] ) ? 'product_cat' : 'category';
		$post_type = ( ! empty( $ciyashop_options['search_content_type'] ) ) ? $ciyashop_options['search_content_type'] : 'post';

		if ( 'all' === $post_type ) {
			$post_type = 'any';
		}

		$args = array(
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'posts_per_page' => -1, // phpcs:ignore WPThemeReview.CoreFunctionality.PostsPerPage.posts_per_page_posts_per_page
		);

		if ( ! empty( $search_keyword ) ) {
			$args['s'] = $search_keyword;
		}

		if ( ! empty( $search_category ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => $taxonomy,
				'field'    => 'id',
				'terms'    => $search_category,
			);
		}

		/**
		 * Filters the autocomplete search arguments.
		 *
		 * @since 3.4.4
		 *
		 * @param     array       $args         An array of search arguments.
		 *
		 * @visible true
		 */
		$args = apply_filters( 'ciyashop_auto_complete_search_args', $args );

		$posts = new WP_Query( $args );

		if ( $posts->have_posts() ) {

			while ( $posts->have_posts() ) :
				$posts->the_post();
				$img    = get_the_post_thumbnail( $posts->ID, 'ciyashop-thumbnail-small' );
				$data[] = array(
					'post_title' => $post->post_title,
					'post_img'   => $img,
					'post_link'  => get_the_permalink( $post->ID ),
				);
			endwhile;

			wp_reset_postdata();
		}

		/**
		 * Filters the autocomplete search result.
		 *
		 * @since 3.4.4
		 *
		 * @param     array       $args         An array of search data.
		 * @param     array       $args         An array of search arguments.
		 *
		 * @visible true
		 */
		$data = apply_filters( 'ciyashop_auto_complete_search_data', $data, $args );

		echo wp_json_encode( $data );
		exit();
	}
}

add_filter( 'woocommerce_widget_cart_is_hidden', 'ciyashop_widget_cart_is_hidden' );
/**
 * Widget cart is hidden
 */
function ciyashop_widget_cart_is_hidden() {
	return false;
}
/**
 * @param string $plugin Plugin file.
 */
function ciyashop_check_plugin_active( $plugin = '' ) {

	if ( empty( $plugin ) ) {
		return false;
	}

	return ( in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) || ( function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( $plugin ) ) );
}

add_action( 'ciyashop_before_footer', 'ciyashop_sticky_footer_mobile_device' );
/**
 * Sticky footer mobile device
 */
function ciyashop_sticky_footer_mobile_device() {
	global $ciyashop_options;

	if ( ( isset( $ciyashop_options['woocommerce_mobile_sticky_footer'] ) && $ciyashop_options['woocommerce_mobile_sticky_footer'] ) && wp_is_mobile() ) {
		get_template_part( 'template-parts/footer/sticky-footer-mobile' );
	}
}
/**
 * Is activated
 */
function ciyashop_is_activated() {
	$purchase_token = get_option( 'ciyashop_pgs_token' );
	if ( $purchase_token && ! empty( $purchase_token ) ) {
		return $purchase_token;
	}
	return false;
}

/**
 * Display admin notice if PGS Core is not updated
 */
function ciyashop_pgscore_update_notice() {

	$current_theme = wp_get_theme();

	if ( ! defined( 'PGSCORE_PATH' ) ) {
		return;
	}

	if (
		( ! defined( 'PGSCORE_VERSION' ) || ( defined( 'PGSCORE_VERSION' ) && version_compare( PGSCORE_VERSION, '4.7.0', '<' ) ) )
	) {
		$ciyashop_token = ciyashop_is_activated();
		if ( $ciyashop_token ) {
			$title     = esc_html__( 'Update PGS Core', 'ciyashop' );
			$msg       = '<p>' . __( 'Please ensure to update the PGS Core plugin to the latest version to enable features. If you have done customization, please take a complete backup of the website before updating the PGS Core plugin. In the latest version of the plugin, the dependency of Advanced Custom Fields Pro has been removed. So, the theme will work without using the ACF Pro plugin.', 'ciyashop' );
			$btn_title = esc_html__( 'Update PGS Core', 'ciyashop' );
			$url       = add_query_arg(
				array(
					'page'          => 'theme-plugins',
					'plugin_status' => 'update',
				),
				admin_url( 'themes.php' )
			);
		} else {
			$title     = esc_html__( 'Activate Theme', 'ciyashop' );
			$msg       = esc_html__( 'Please activate theme to enable the theme features.', 'ciyashop' );
			$btn_title = esc_html__( 'Activate Theme', 'ciyashop' );
			$url       = add_query_arg(
				array(
					'page' => 'ciyashop-panel',
				),
				admin_url( 'admin.php' )
			);

		}
		?>
		<div id="setting-error-pgscore_update" class="notice notice-error">
			<h2 class="setting-error-pgscore_update-header"><?php echo esc_html( $title ); ?></h2>
			<div class="setting-error-pgscore_update-desc"><?php echo wp_kses( $msg, array( 'p' => array() ) ); ?></div>
			<div class="setting-error-pgscore_update-action"><a href="<?php echo esc_url( $url ); ?>" class="button-primary"><?php echo esc_html( $btn_title ); ?></a></div>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'ciyashop_pgscore_update_notice' );

if ( ! function_exists( 'ciyashop_get_wishlist_number' ) ) {
	/**
	 * Function returns numbers of items in the wishlist
	 */
	function ciyashop_get_wishlist_number() {

		$ciyashop_nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $ciyashop_nonce, 'ciyashop_nonce' ) ) {
			wp_die();
		}

		if ( ! class_exists( 'YITH_WCWL' ) ) {
			wp_die();
		}
		$count_products = YITH_WCWL()->count_products();

		echo esc_html( $count_products );
		wp_die();
	}
	add_action( 'wp_ajax_ciyashop_get_wishlist_number', 'ciyashop_get_wishlist_number' );
	add_action( 'wp_ajax_nopriv_ciyashop_get_wishlist_number', 'ciyashop_get_wishlist_number' );
}

add_filter( 'ciyashop_content_container_classes', 'ciyashop_main_content_container_classes' );
/**
 * Portfolio container class
 *
 * @param mixed $classes  Classes.
 */
function ciyashop_main_content_container_classes( $classes ) {
	global $ciyashop_options;

	if ( is_post_type_archive( 'portfolio' ) && 'full_width' === $ciyashop_options['portfolio_sidebar'] && 1 === (int) $ciyashop_options['portfolio_fullscreen'] ) {
		$classes = array( 'container-fluid' );
	}
	return $classes;
}

/**
 * Related Portfolio
 */
function ciyashop_related_portfolio() {
	global $ciyashop_options;

	if ( isset( $ciyashop_options['display_related_portfolio'] ) && 1 === (int) $ciyashop_options['display_related_portfolio'] ) {
		get_template_part( 'template-parts/portfolio/single-related' );
	}
}

/**
 * Register Metabox
 */
function ciyashop_custome_sidebar_metabox() {
	add_meta_box(
		'pgs_custome_sidebar',
		__( 'Custome Sidebar', 'ciyashop' ),
		'pgs_custome_sidebar_callback',
		'page',
		'side'
	);
}

add_action( 'add_meta_boxes', 'ciyashop_custome_sidebar_metabox' ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped

/**
 * Metabox Dropdown
 *
 * @param WP_Post $post Post object.
 */
function pgs_custome_sidebar_callback( $post ) {
	wp_nonce_field( 'pgs_custom_sidebar_metabox_nonce', 'pgs_custom_sidebar_nonce' );
	$value = get_post_meta( $post->ID, 'pgs_custom_sidebar', true );
	?>
	<select name="pgs_custom_sidebar" id="pgs_custom_sidebar">
		<option value="">
			<?php echo esc_html( 'Default' ); ?>
		</option>
		<?php foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) { ?>
		<option value="<?php echo esc_attr( $sidebar['id'] ); ?>" <?php selected( $value, $sidebar['id'] ); ?>>
			<?php echo esc_html( ucwords( $sidebar['name'] ) ); ?>
		</option>
			<?php
		}
		?>
	</select>
	<?php
}

if ( ! function_exists( 'ciyashop_save_custome_sidebar_metabox' ) ) {
	/**
	 * Metabox Save
	 *
	 * @param int $post_id   Post ID.
	 */
	function ciyashop_save_custome_sidebar_metabox( $post_id ) {
		if ( ! isset( $_POST['pgs_custom_sidebar_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['pgs_custom_sidebar_nonce'] ) ), 'pgs_custom_sidebar_metabox_nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_POST['pgs_custom_sidebar'] ) ) {
			update_post_meta( $post_id, 'pgs_custom_sidebar', sanitize_text_field( wp_unslash( $_POST['pgs_custom_sidebar'] ) ) );
		}
	}
}
add_action( 'save_post', 'ciyashop_save_custome_sidebar_metabox' );

if ( ! function_exists( 'redux_search_options' ) ) {
	/**
	 * Redux Search Option
	 */
	function redux_search_options() {

		global $ciyashop_globals, $ciyashop_options, $pgscore_globals;

		if ( class_exists( 'Redux' ) ) {

			$option_fields = array();

			// Sections.
			$ciyashop_redux_sections = Redux::$sections;

			if ( empty( $ciyashop_redux_sections ) ) {
				return;
			}

			$redux_data = get_plugin_data( WP_PLUGIN_DIR . '/redux-framework/redux-framework.php' );

			$ciyashop_redux_sections = $ciyashop_redux_sections[ $ciyashop_globals['options_name'] ];

			if ( version_compare( $redux_data['Version'], '4.0', '<=' ) ) {
				$sections_count = ( Redux_Helpers::isWpDebug() ) ? count( $ciyashop_redux_sections ) + 2 : count( $ciyashop_redux_sections ) + 1;
			} else {
				$sections_count = ( Redux_Helpers::is_wp_debug() ) ? count( $ciyashop_redux_sections ) + 2 : count( $ciyashop_redux_sections ) + 1;
			}

			// Fields.
			$ciyashop_redux_fields = Redux::$fields;
			$ciyashop_redux_fields = $ciyashop_redux_fields[ $ciyashop_globals['options_name'] ];

			if ( version_compare( $redux_data['Version'], '4.0', '<=' ) ) {
				$fields_count = ( Redux_Helpers::isWpDebug() ) ? count( $ciyashop_redux_fields ) + 2 : count( $ciyashop_redux_fields ) + 1;
			} else {
				$fields_count = ( Redux_Helpers::is_wp_debug() ) ? count( $ciyashop_redux_fields ) + 2 : count( $ciyashop_redux_fields ) + 1;
			}

			$ciyashop_redux_sections['import/export'] = array(
				'title'    => 'Import / Export',
				'desc'     => '',
				'id'       => 'import/export',
				'icon'     => 'el el-refresh',
				'priority' => $sections_count,
			);

			$ciyashop_redux_fields['redux_import_export'] = array(
				'id'         => 'redux_import_export',
				'type'       => 'import_export',
				'full_width' => '1',
				'section_id' => 'import/export',
				'priority'   => $fields_count,
			);

			$ciyashop_redux_fields_new = array();

			if ( ! empty( $ciyashop_redux_fields ) && is_array( $ciyashop_redux_fields ) ) {
				foreach ( $ciyashop_redux_fields as $ciyashop_redux_field_k => $ciyashop_redux_field_data ) {
					$ciyashop_redux_fields_new[ $ciyashop_redux_field_data['section_id'] ][] = $ciyashop_redux_field_data;
				}
			}

			if ( ! empty( $ciyashop_redux_sections ) && is_array( $ciyashop_redux_sections ) ) {

				$section_id = 0;
				$index      = 0;

				$current_icon = '';

				foreach ( $ciyashop_redux_sections as $section ) {

					if ( isset( $section['title'] ) ) {
						$option_fields[ $index ]['title']      = $section['title'];
						$option_fields[ $index ]['id']         = $section['id'];
						$option_fields[ $index ]['path']       = $section['title'];
						$option_fields[ $index ]['section_id'] = $section['priority'];

						if ( isset( $section['icon'] ) ) {
							$current_icon = $option_fields[ $index ]['icon'] = $section['icon']; // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found
						} else {
							$option_fields[ $index ]['icon'] = $current_icon;
						}

						$index++;

						$section_fields = array();
						if ( isset( $ciyashop_redux_fields_new[ $section['id'] ] ) ) {
							$section_fields = $ciyashop_redux_fields_new[ $section['id'] ];
							foreach ( $section_fields as $field ) {
								$field_title = isset( $field['title'] ) ? trim( $field['title'] ) : false;
								if ( $field_title ) {
									$option_fields[ $index ]['title']      = $field['title'];
									$option_fields[ $index ]['id']         = $field['id'];
									$option_fields[ $index ]['path']       = $section['title'] . ' &raquo; ' . $field['title'];
									$option_fields[ $index ]['section_id'] = $section['priority'];

									if ( isset( $section['icon'] ) ) {
										$option_fields[ $index ]['icon'] = $section['icon'];
									} else {
										$option_fields[ $index ]['icon'] = $current_icon;
									}
									$index++;
								}
							}
						}
					}
				}
			}

			$localize_data['search_option_placeholder_text'] = esc_js( __( 'Search for Theme options', 'ciyashop' ) );
			$localize_data['reduxThemeOptions']              = $option_fields;

			/**
			 * Filters the list of theme option search items.
			 *
			 * @param     array       $localize_data      Theme option search localize data.
			 *
			 * @visible false
			 * @ignore
			 */
			return apply_filters( 'ciyashop_admin_search_options_localize_data', $localize_data );
		}
	}
}

if ( ! function_exists( 'ciyashop_iconpicker_icons' ) ) {
	/**
	 * Iconpicker icons
	 */
	function ciyashop_iconpicker_icons() {
		$fontawesome_icons = array(
			'Accessibility'       => array(
				array( 'fab fa-accessible-icon' => 'Accessible Icon (accessibility,handicap,person,wheelchair,wheelchair-alt)' ),
				array( 'fas fa-american-sign-language-interpreting' => 'American Sign Language Interpreting (asl,deaf,finger,hand,interpret,speak)' ),
				array( 'fas fa-assistive-listening-systems' => 'Assistive Listening Systems (amplify,audio,deaf,ear,headset,hearing,sound)' ),
				array( 'fas fa-audio-description' => 'Audio Description (blind,narration,video,visual)' ),
				array( 'fas fa-blind' => 'Blind (cane,disability,person,sight)' ),
				array( 'fas fa-braille' => 'Braille (alphabet,blind,dots,raised,vision)' ),
				array( 'fas fa-closed-captioning' => 'Closed Captioning (cc,deaf,hearing,subtitle,subtitling,text,video)' ),
				array( 'far fa-closed-captioning' => 'Closed Captioning (cc,deaf,hearing,subtitle,subtitling,text,video)' ),
				array( 'fas fa-deaf' => 'Deaf (ear,hearing,sign language)' ),
				array( 'fas fa-low-vision' => 'Low Vision (blind,eye,sight)' ),
				array( 'fas fa-phone-volume' => 'Phone Volume (call,earphone,number,sound,support,telephone,voice,volume-control-phone)' ),
				array( 'fas fa-question-circle' => 'Question Circle (help,information,support,unknown)' ),
				array( 'far fa-question-circle' => 'Question Circle (help,information,support,unknown)' ),
				array( 'fas fa-sign-language' => 'Sign Language (Translate,asl,deaf,hands)' ),
				array( 'fas fa-tty' => 'TTY (communication,deaf,telephone,teletypewriter,text)' ),
				array( 'fas fa-universal-access' => 'Universal Access (accessibility,hearing,person,seeing,visual impairment)' ),
				array( 'fas fa-wheelchair' => 'Wheelchair (accessible,handicap,person)' ),
			),
			'Alert'               => array(
				array( 'fas fa-bell' => 'bell (alarm,alert,chime,notification,reminder)' ),
				array( 'far fa-bell' => 'bell (alarm,alert,chime,notification,reminder)' ),
				array( 'fas fa-bell-slash' => 'Bell Slash (alert,cancel,disabled,notification,off,reminder)' ),
				array( 'far fa-bell-slash' => 'Bell Slash (alert,cancel,disabled,notification,off,reminder)' ),
				array( 'fas fa-exclamation' => 'exclamation (alert,danger,error,important,notice,notification,notify,problem,warning)' ),
				array( 'fas fa-exclamation-circle' => 'Exclamation Circle (alert,danger,error,important,notice,notification,notify,problem,warning)' ),
				array( 'fas fa-exclamation-triangle' => 'Exclamation Triangle (alert,danger,error,important,notice,notification,notify,problem,warning)' ),
				array( 'fas fa-radiation' => 'Radiation (danger,dangerous,deadly,hazard,nuclear,radioactive,warning)' ),
				array( 'fas fa-radiation-alt' => 'Alternate Radiation (danger,dangerous,deadly,hazard,nuclear,radioactive,warning)' ),
				array( 'fas fa-skull-crossbones' => 'Skull & Crossbones (Dungeons & Dragons,alert,bones,d&d,danger,dead,deadly,death,dnd,fantasy,halloween,holiday,jolly-roger,pirate,poison,skeleton,warning)' ),
			),
			'Animals'             => array(
				array( 'fas fa-cat' => 'Cat (feline,halloween,holiday,kitten,kitty,meow,pet)' ),
				array( 'fas fa-crow' => 'Crow (bird,bullfrog,fauna,halloween,holiday,toad)' ),
				array( 'fas fa-dog' => 'Dog (animal,canine,fauna,mammal,pet,pooch,puppy,woof)' ),
				array( 'fas fa-dove' => 'Dove (bird,fauna,flying,peace,war)' ),
				array( 'fas fa-dragon' => 'Dragon (Dungeons & Dragons,d&d,dnd,fantasy,fire,lizard,serpent)' ),
				array( 'fas fa-feather' => 'Feather (bird,light,plucked,quill,write)' ),
				array( 'fas fa-feather-alt' => 'Alternate Feather (bird,light,plucked,quill,write)' ),
				array( 'fas fa-fish' => 'Fish (fauna,gold,seafood,swimming)' ),
				array( 'fas fa-frog' => 'Frog (amphibian,bullfrog,fauna,hop,kermit,kiss,prince,ribbit,toad,wart)' ),
				array( 'fas fa-hippo' => 'Hippo (animal,fauna,hippopotamus,hungry,mammal)' ),
				array( 'fas fa-horse' => 'Horse (equus,fauna,mammmal,mare,neigh,pony)' ),
				array( 'fas fa-horse-head' => 'Horse Head (equus,fauna,mammmal,mare,neigh,pony)' ),
				array( 'fas fa-kiwi-bird' => 'Kiwi Bird (bird,fauna,new zealand)' ),
				array( 'fas fa-otter' => 'Otter (animal,badger,fauna,fur,mammal,marten)' ),
				array( 'fas fa-paw' => 'Paw (animal,cat,dog,pet,print)' ),
				array( 'fas fa-spider' => 'Spider (arachnid,bug,charlotte,crawl,eight,halloween)' ),
			),
			'Arrows'              => array(
				array( 'fas fa-angle-double-down' => 'Angle Double Down (arrows,caret,download,expand)' ),
				array( 'fas fa-angle-double-left' => 'Angle Double Left (arrows,back,caret,laquo,previous,quote)' ),
				array( 'fas fa-angle-double-right' => 'Angle Double Right (arrows,caret,forward,more,next,quote,raquo)' ),
				array( 'fas fa-angle-double-up' => 'Angle Double Up (arrows,caret,collapse,upload)' ),
				array( 'fas fa-angle-down' => 'angle-down (arrow,caret,download,expand)' ),
				array( 'fas fa-angle-left' => 'angle-left (arrow,back,caret,less,previous)' ),
				array( 'fas fa-angle-right' => 'angle-right (arrow,care,forward,more,next)' ),
				array( 'fas fa-angle-up' => 'angle-up (arrow,caret,collapse,upload)' ),
				array( 'fas fa-arrow-alt-circle-down' => 'Alternate Arrow Circle Down (arrow-circle-o-down,download)' ),
				array( 'far fa-arrow-alt-circle-down' => 'Alternate Arrow Circle Down (arrow-circle-o-down,download)' ),
				array( 'fas fa-arrow-alt-circle-left' => 'Alternate Arrow Circle Left (arrow-circle-o-left,back,previous)' ),
				array( 'far fa-arrow-alt-circle-left' => 'Alternate Arrow Circle Left (arrow-circle-o-left,back,previous)' ),
				array( 'fas fa-arrow-alt-circle-right' => 'Alternate Arrow Circle Right (arrow-circle-o-right,forward,next)' ),
				array( 'far fa-arrow-alt-circle-right' => 'Alternate Arrow Circle Right (arrow-circle-o-right,forward,next)' ),
				array( 'fas fa-arrow-alt-circle-up' => 'Alternate Arrow Circle Up (arrow-circle-o-up)' ),
				array( 'far fa-arrow-alt-circle-up' => 'Alternate Arrow Circle Up (arrow-circle-o-up)' ),
				array( 'fas fa-arrow-circle-down' => 'Arrow Circle Down (download)' ),
				array( 'fas fa-arrow-circle-left' => 'Arrow Circle Left (back,previous)' ),
				array( 'fas fa-arrow-circle-right' => 'Arrow Circle Right (forward,next)' ),
				array( 'fas fa-arrow-circle-up' => 'Arrow Circle Up (upload)' ),
				array( 'fas fa-arrow-down' => 'arrow-down (download)' ),
				array( 'fas fa-arrow-left' => 'arrow-left (back,previous)' ),
				array( 'fas fa-arrow-right' => 'arrow-right (forward,next)' ),
				array( 'fas fa-arrow-up' => 'arrow-up (forward,upload)' ),
				array( 'fas fa-arrows-alt' => 'Alternate Arrows (arrow,arrows,bigger,enlarge,expand,fullscreen,move,position,reorder,resize)' ),
				array( 'fas fa-arrows-alt-h' => 'Alternate Arrows Horizontal (arrows-h,expand,horizontal,landscape,resize,wide)' ),
				array( 'fas fa-arrows-alt-v' => 'Alternate Arrows Vertical (arrows-v,expand,portrait,resize,tall,vertical)' ),
				array( 'fas fa-caret-down' => 'Caret Down (arrow,dropdown,expand,menu,more,triangle)' ),
				array( 'fas fa-caret-left' => 'Caret Left (arrow,back,previous,triangle)' ),
				array( 'fas fa-caret-right' => 'Caret Right (arrow,forward,next,triangle)' ),
				array( 'fas fa-caret-square-down' => 'Caret Square Down (arrow,caret-square-o-down,dropdown,expand,menu,more,triangle)' ),
				array( 'far fa-caret-square-down' => 'Caret Square Down (arrow,caret-square-o-down,dropdown,expand,menu,more,triangle)' ),
				array( 'fas fa-caret-square-left' => 'Caret Square Left (arrow,back,caret-square-o-left,previous,triangle)' ),
				array( 'far fa-caret-square-left' => 'Caret Square Left (arrow,back,caret-square-o-left,previous,triangle)' ),
				array( 'fas fa-caret-square-right' => 'Caret Square Right (arrow,caret-square-o-right,forward,next,triangle)' ),
				array( 'far fa-caret-square-right' => 'Caret Square Right (arrow,caret-square-o-right,forward,next,triangle)' ),
				array( 'fas fa-caret-square-up' => 'Caret Square Up (arrow,caret-square-o-up,collapse,triangle,upload)' ),
				array( 'far fa-caret-square-up' => 'Caret Square Up (arrow,caret-square-o-up,collapse,triangle,upload)' ),
				array( 'fas fa-caret-up' => 'Caret Up (arrow,collapse,triangle)' ),
				array( 'fas fa-cart-arrow-down' => 'Shopping Cart Arrow Down (download,save,shopping)' ),
				array( 'fas fa-chart-line' => 'Line Chart (activity,analytics,chart,dashboard,gain,graph,increase,line)' ),
				array( 'fas fa-chevron-circle-down' => 'Chevron Circle Down (arrow,download,dropdown,menu,more)' ),
				array( 'fas fa-chevron-circle-left' => 'Chevron Circle Left (arrow,back,previous)' ),
				array( 'fas fa-chevron-circle-right' => 'Chevron Circle Right (arrow,forward,next)' ),
				array( 'fas fa-chevron-circle-up' => 'Chevron Circle Up (arrow,collapse,upload)' ),
				array( 'fas fa-chevron-down' => 'chevron-down (arrow,download,expand)' ),
				array( 'fas fa-chevron-left' => 'chevron-left (arrow,back,bracket,previous)' ),
				array( 'fas fa-chevron-right' => 'chevron-right (arrow,bracket,forward,next)' ),
				array( 'fas fa-chevron-up' => 'chevron-up (arrow,collapse,upload)' ),
				array( 'fas fa-cloud-download-alt' => 'Alternate Cloud Download (download,export,save)' ),
				array( 'fas fa-cloud-upload-alt' => 'Alternate Cloud Upload (cloud-upload,import,save,upload)' ),
				array( 'fas fa-compress-arrows-alt' => 'Alternate Compress Arrows (collapse,fullscreen,minimize,move,resize,shrink,smaller)' ),
				array( 'fas fa-download' => 'Download (export,hard drive,save,transfer)' ),
				array( 'fas fa-exchange-alt' => 'Alternate Exchange (arrow,arrows,exchange,reciprocate,return,swap,transfer)' ),
				array( 'fas fa-expand-arrows-alt' => 'Alternate Expand Arrows (arrows-alt,bigger,enlarge,move,resize)' ),
				array( 'fas fa-external-link-alt' => 'Alternate External Link (external-link,new,open,share)' ),
				array( 'fas fa-external-link-square-alt' => 'Alternate External Link Square (external-link-square,new,open,share)' ),
				array( 'fas fa-hand-point-down' => 'Hand Pointing Down (finger,hand-o-down,point)' ),
				array( 'far fa-hand-point-down' => 'Hand Pointing Down (finger,hand-o-down,point)' ),
				array( 'fas fa-hand-point-left' => 'Hand Pointing Left (back,finger,hand-o-left,left,point,previous)' ),
				array( 'far fa-hand-point-left' => 'Hand Pointing Left (back,finger,hand-o-left,left,point,previous)' ),
				array( 'fas fa-hand-point-right' => 'Hand Pointing Right (finger,forward,hand-o-right,next,point,right)' ),
				array( 'far fa-hand-point-right' => 'Hand Pointing Right (finger,forward,hand-o-right,next,point,right)' ),
				array( 'fas fa-hand-point-up' => 'Hand Pointing Up (finger,hand-o-up,point)' ),
				array( 'far fa-hand-point-up' => 'Hand Pointing Up (finger,hand-o-up,point)' ),
				array( 'fas fa-hand-pointer' => 'Pointer (Hand) (arrow,cursor,select)' ),
				array( 'far fa-hand-pointer' => 'Pointer (Hand) (arrow,cursor,select)' ),
				array( 'fas fa-history' => 'History (Rewind,clock,reverse,time,time machine)' ),
				array( 'fas fa-level-down-alt' => 'Alternate Level Down (arrow,level-down)' ),
				array( 'fas fa-level-up-alt' => 'Alternate Level Up (arrow,level-up)' ),
				array( 'fas fa-location-arrow' => 'location-arrow (address,compass,coordinate,direction,gps,map,navigation,place)' ),
				array( 'fas fa-long-arrow-alt-down' => 'Alternate Long Arrow Down (download,long-arrow-down)' ),
				array( 'fas fa-long-arrow-alt-left' => 'Alternate Long Arrow Left (back,long-arrow-left,previous)' ),
				array( 'fas fa-long-arrow-alt-right' => 'Alternate Long Arrow Right (forward,long-arrow-right,next)' ),
				array( 'fas fa-long-arrow-alt-up' => 'Alternate Long Arrow Up (long-arrow-up,upload)' ),
				array( 'fas fa-mouse-pointer' => 'Mouse Pointer (arrow,cursor,select)' ),
				array( 'fas fa-play' => 'play (audio,music,playing,sound,start,video)' ),
				array( 'fas fa-random' => 'random (arrows,shuffle,sort,swap,switch,transfer)' ),
				array( 'fas fa-recycle' => 'Recycle (Waste,compost,garbage,reuse,trash)' ),
				array( 'fas fa-redo' => 'Redo (forward,refresh,reload,repeat)' ),
				array( 'fas fa-redo-alt' => 'Alternate Redo (forward,refresh,reload,repeat)' ),
				array( 'fas fa-reply' => 'Reply (mail,message,respond)' ),
				array( 'fas fa-reply-all' => 'reply-all (mail,message,respond)' ),
				array( 'fas fa-retweet' => 'Retweet (refresh,reload,share,swap)' ),
				array( 'fas fa-share' => 'Share (forward,save,send,social)' ),
				array( 'fas fa-share-square' => 'Share Square (forward,save,send,social)' ),
				array( 'far fa-share-square' => 'Share Square (forward,save,send,social)' ),
				array( 'fas fa-sign-in-alt' => 'Alternate Sign In (arrow,enter,join,log in,login,sign in,sign up,sign-in,signin,signup)' ),
				array( 'fas fa-sign-out-alt' => 'Alternate Sign Out (arrow,exit,leave,log out,logout,sign-out)' ),
				array( 'fas fa-sort' => 'Sort (filter,order)' ),
				array( 'fas fa-sort-alpha-down' => 'Sort Alphabetical Down (alphabetical,arrange,filter,order,sort-alpha-asc)' ),
				array( 'fas fa-sort-alpha-down-alt' => 'Alternate Sort Alphabetical Down (alphabetical,arrange,filter,order,sort-alpha-asc)' ),
				array( 'fas fa-sort-alpha-up' => 'Sort Alphabetical Up (alphabetical,arrange,filter,order,sort-alpha-desc)' ),
				array( 'fas fa-sort-alpha-up-alt' => 'Alternate Sort Alphabetical Up (alphabetical,arrange,filter,order,sort-alpha-desc)' ),
				array( 'fas fa-sort-amount-down' => 'Sort Amount Down (arrange,filter,number,order,sort-amount-asc)' ),
				array( 'fas fa-sort-amount-down-alt' => 'Alternate Sort Amount Down (arrange,filter,order,sort-amount-asc)' ),
				array( 'fas fa-sort-amount-up' => 'Sort Amount Up (arrange,filter,order,sort-amount-desc)' ),
				array( 'fas fa-sort-amount-up-alt' => 'Alternate Sort Amount Up (arrange,filter,order,sort-amount-desc)' ),
				array( 'fas fa-sort-down' => 'Sort Down (Descending) (arrow,descending,filter,order,sort-desc)' ),
				array( 'fas fa-sort-numeric-down' => 'Sort Numeric Down (arrange,filter,numbers,order,sort-numeric-asc)' ),
				array( 'fas fa-sort-numeric-down-alt' => 'Alternate Sort Numeric Down (arrange,filter,numbers,order,sort-numeric-asc)' ),
				array( 'fas fa-sort-numeric-up' => 'Sort Numeric Up (arrange,filter,numbers,order,sort-numeric-desc)' ),
				array( 'fas fa-sort-numeric-up-alt' => 'Alternate Sort Numeric Up (arrange,filter,numbers,order,sort-numeric-desc)' ),
				array( 'fas fa-sort-up' => 'Sort Up (Ascending) (arrow,ascending,filter,order,sort-asc)' ),
				array( 'fas fa-sync' => 'Sync (exchange,refresh,reload,rotate,swap)' ),
				array( 'fas fa-sync-alt' => 'Alternate Sync (exchange,refresh,reload,rotate,swap)' ),
				array( 'fas fa-text-height' => 'text-height (edit,font,format,text,type)' ),
				array( 'fas fa-text-width' => 'Text Width (edit,font,format,text,type)' ),
				array( 'fas fa-undo' => 'Undo (back,control z,exchange,oops,return,rotate,swap)' ),
				array( 'fas fa-undo-alt' => 'Alternate Undo (back,control z,exchange,oops,return,swap)' ),
				array( 'fas fa-upload' => 'Upload (hard drive,import,publish)' ),
			),
			'Audio & Video'       => array(
				array( 'fas fa-audio-description' => 'Audio Description (blind,narration,video,visual)' ),
				array( 'fas fa-backward' => 'backward (previous,rewind)' ),
				array( 'fas fa-broadcast-tower' => 'Broadcast Tower (airwaves,antenna,radio,reception,waves)' ),
				array( 'fas fa-circle' => 'Circle (circle-thin,diameter,dot,ellipse,notification,round)' ),
				array( 'far fa-circle' => 'Circle (circle-thin,diameter,dot,ellipse,notification,round)' ),
				array( 'fas fa-closed-captioning' => 'Closed Captioning (cc,deaf,hearing,subtitle,subtitling,text,video)' ),
				array( 'far fa-closed-captioning' => 'Closed Captioning (cc,deaf,hearing,subtitle,subtitling,text,video)' ),
				array( 'fas fa-compress' => 'Compress (collapse,fullscreen,minimize,move,resize,shrink,smaller)' ),
				array( 'fas fa-compress-arrows-alt' => 'Alternate Compress Arrows (collapse,fullscreen,minimize,move,resize,shrink,smaller)' ),
				array( 'fas fa-eject' => 'eject (abort,cancel,cd,discharge)' ),
				array( 'fas fa-expand' => 'Expand (arrow,bigger,enlarge,resize)' ),
				array( 'fas fa-expand-arrows-alt' => 'Alternate Expand Arrows (arrows-alt,bigger,enlarge,move,resize)' ),
				array( 'fas fa-fast-backward' => 'fast-backward (beginning,first,previous,rewind,start)' ),
				array( 'fas fa-fast-forward' => 'fast-forward (end,last,next)' ),
				array( 'fas fa-file-audio' => 'Audio File (document,mp3,music,page,play,sound)' ),
				array( 'far fa-file-audio' => 'Audio File (document,mp3,music,page,play,sound)' ),
				array( 'fas fa-file-video' => 'Video File (document,m4v,movie,mp4,play)' ),
				array( 'far fa-file-video' => 'Video File (document,m4v,movie,mp4,play)' ),
				array( 'fas fa-film' => 'Film (cinema,movie,strip,video)' ),
				array( 'fas fa-forward' => 'forward (forward,next,skip)' ),
				array( 'fas fa-headphones' => 'headphones (audio,listen,music,sound,speaker)' ),
				array( 'fas fa-microphone' => 'microphone (audio,podcast,record,sing,sound,voice)' ),
				array( 'fas fa-microphone-alt' => 'Alternate Microphone (audio,podcast,record,sing,sound,voice)' ),
				array( 'fas fa-microphone-alt-slash' => 'Alternate Microphone Slash (audio,disable,mute,podcast,record,sing,sound,voice)' ),
				array( 'fas fa-microphone-slash' => 'Microphone Slash (audio,disable,mute,podcast,record,sing,sound,voice)' ),
				array( 'fas fa-music' => 'Music (lyrics,melody,note,sing,sound)' ),
				array( 'fas fa-pause' => 'pause (hold,wait)' ),
				array( 'fas fa-pause-circle' => 'Pause Circle (hold,wait)' ),
				array( 'far fa-pause-circle' => 'Pause Circle (hold,wait)' ),
				array( 'fas fa-phone-volume' => 'Phone Volume (call,earphone,number,sound,support,telephone,voice,volume-control-phone)' ),
				array( 'fas fa-photo-video' => 'Photo Video (av,film,image,library,media)' ),
				array( 'fas fa-play' => 'play (audio,music,playing,sound,start,video)' ),
				array( 'fas fa-play-circle' => 'Play Circle (audio,music,playing,sound,start,video)' ),
				array( 'far fa-play-circle' => 'Play Circle (audio,music,playing,sound,start,video)' ),
				array( 'fas fa-podcast' => 'Podcast (audio,broadcast,music,sound)' ),
				array( 'fas fa-random' => 'random (arrows,shuffle,sort,swap,switch,transfer)' ),
				array( 'fas fa-redo' => 'Redo (forward,refresh,reload,repeat)' ),
				array( 'fas fa-redo-alt' => 'Alternate Redo (forward,refresh,reload,repeat)' ),
				array( 'fas fa-rss' => 'rss (blog,feed,journal,news,writing)' ),
				array( 'fas fa-rss-square' => 'RSS Square (blog,feed,journal,news,writing)' ),
				array( 'fas fa-step-backward' => 'step-backward (beginning,first,previous,rewind,start)' ),
				array( 'fas fa-step-forward' => 'step-forward (end,last,next)' ),
				array( 'fas fa-stop' => 'stop (block,box,square)' ),
				array( 'fas fa-stop-circle' => 'Stop Circle (block,box,circle,square)' ),
				array( 'far fa-stop-circle' => 'Stop Circle (block,box,circle,square)' ),
				array( 'fas fa-sync' => 'Sync (exchange,refresh,reload,rotate,swap)' ),
				array( 'fas fa-sync-alt' => 'Alternate Sync (exchange,refresh,reload,rotate,swap)' ),
				array( 'fas fa-tv' => 'Television (computer,display,monitor,television)' ),
				array( 'fas fa-undo' => 'Undo (back,control z,exchange,oops,return,rotate,swap)' ),
				array( 'fas fa-undo-alt' => 'Alternate Undo (back,control z,exchange,oops,return,swap)' ),
				array( 'fas fa-video' => 'Video (camera,film,movie,record,video-camera)' ),
				array( 'fas fa-volume-down' => 'Volume Down (audio,lower,music,quieter,sound,speaker)' ),
				array( 'fas fa-volume-mute' => 'Volume Mute (audio,music,quiet,sound,speaker)' ),
				array( 'fas fa-volume-off' => 'Volume Off (audio,ban,music,mute,quiet,silent,sound)' ),
				array( 'fas fa-volume-up' => 'Volume Up (audio,higher,louder,music,sound,speaker)' ),
				array( 'fab fa-youtube' => 'YouTube (film,video,youtube-play,youtube-square)' ),
			),
			'Automotive'          => array(
				array( 'fas fa-air-freshener' => 'Air Freshener (car,deodorize,fresh,pine,scent)' ),
				array( 'fas fa-ambulance' => 'ambulance (emergency,emt,er,help,hospital,support,vehicle)' ),
				array( 'fas fa-bus' => 'Bus (public transportation,transportation,travel,vehicle)' ),
				array( 'fas fa-bus-alt' => 'Bus Alt (mta,public transportation,transportation,travel,vehicle)' ),
				array( 'fas fa-car' => 'Car (auto,automobile,sedan,transportation,travel,vehicle)' ),
				array( 'fas fa-car-alt' => 'Alternate Car (auto,automobile,sedan,transportation,travel,vehicle)' ),
				array( 'fas fa-car-battery' => 'Car Battery (auto,electric,mechanic,power)' ),
				array( 'fas fa-car-crash' => 'Car Crash (accident,auto,automobile,insurance,sedan,transportation,vehicle,wreck)' ),
				array( 'fas fa-car-side' => 'Car Side (auto,automobile,sedan,transportation,travel,vehicle)' ),
				array( 'fas fa-charging-station' => 'Charging Station (electric,ev,tesla,vehicle)' ),
				array( 'fas fa-gas-pump' => 'Gas Pump (car,fuel,gasoline,petrol)' ),
				array( 'fas fa-motorcycle' => 'Motorcycle (bike,machine,transportation,vehicle)' ),
				array( 'fas fa-oil-can' => 'Oil Can (auto,crude,gasoline,grease,lubricate,petroleum)' ),
				array( 'fas fa-shuttle-van' => 'Shuttle Van (airport,machine,public-transportation,transportation,travel,vehicle)' ),
				array( 'fas fa-tachometer-alt' => 'Alternate Tachometer (dashboard,fast,odometer,speed,speedometer)' ),
				array( 'fas fa-taxi' => 'Taxi (cab,cabbie,car,car service,lyft,machine,transportation,travel,uber,vehicle)' ),
				array( 'fas fa-truck' => 'truck (cargo,delivery,shipping,vehicle)' ),
				array( 'fas fa-truck-monster' => 'Truck Monster (offroad,vehicle,wheel)' ),
				array( 'fas fa-truck-pickup' => 'Truck Side (cargo,vehicle)' ),
			),
			'Autumn'              => array(
				array( 'fas fa-apple-alt' => 'Fruit Apple (fall,fruit,fuji,macintosh,orchard,seasonal,vegan)' ),
				array( 'fas fa-campground' => 'Campground (camping,fall,outdoors,teepee,tent,tipi)' ),
				array( 'fas fa-cloud-sun' => 'Cloud with Sun (clear,day,daytime,fall,outdoors,overcast,partly cloudy)' ),
				array( 'fas fa-drumstick-bite' => 'Drumstick with Bite Taken Out (bone,chicken,leg,meat,poultry,turkey)' ),
				array( 'fas fa-football-ball' => 'Football Ball (ball,fall,nfl,pigskin,seasonal)' ),
				array( 'fas fa-hiking' => 'Hiking (activity,backpack,fall,fitness,outdoors,person,seasonal,walking)' ),
				array( 'fas fa-mountain' => 'Mountain (glacier,hiking,hill,landscape,travel,view)' ),
				array( 'fas fa-tractor' => 'Tractor (agriculture,farm,vehicle)' ),
				array( 'fas fa-tree' => 'Tree (bark,fall,flora,forest,nature,plant,seasonal)' ),
				array( 'fas fa-wind' => 'Wind (air,blow,breeze,fall,seasonal,weather)' ),
				array( 'fas fa-wine-bottle' => 'Wine Bottle (alcohol,beverage,cabernet,drink,glass,grapes,merlot,sauvignon)' ),
			),
			'Beverage'            => array(
				array( 'fas fa-beer' => 'beer (alcohol,ale,bar,beverage,brewery,drink,lager,liquor,mug,stein)' ),
				array( 'fas fa-blender' => 'Blender (cocktail,milkshake,mixer,puree,smoothie)' ),
				array( 'fas fa-cocktail' => 'Cocktail (alcohol,beverage,drink,gin,glass,margarita,martini,vodka)' ),
				array( 'fas fa-coffee' => 'Coffee (beverage,breakfast,cafe,drink,fall,morning,mug,seasonal,tea)' ),
				array( 'fas fa-flask' => 'Flask (beaker,experimental,labs,science)' ),
				array( 'fas fa-glass-cheers' => 'Glass Cheers (alcohol,bar,beverage,celebration,champagne,clink,drink,holiday,new year\'s eve,party,toast)' ),
				array( 'fas fa-glass-martini' => 'Martini Glass (alcohol,bar,beverage,drink,liquor)' ),
				array( 'fas fa-glass-martini-alt' => 'Alternate Glass Martini (alcohol,bar,beverage,drink,liquor)' ),
				array( 'fas fa-glass-whiskey' => 'Glass Whiskey (alcohol,bar,beverage,bourbon,drink,liquor,neat,rye,scotch,whisky)' ),
				array( 'fas fa-mug-hot' => 'Mug Hot (caliente,cocoa,coffee,cup,drink,holiday,hot chocolate,steam,tea,warmth)' ),
				array( 'fas fa-wine-bottle' => 'Wine Bottle (alcohol,beverage,cabernet,drink,glass,grapes,merlot,sauvignon)' ),
				array( 'fas fa-wine-glass' => 'Wine Glass (alcohol,beverage,cabernet,drink,grapes,merlot,sauvignon)' ),
				array( 'fas fa-wine-glass-alt' => 'Alternate Wine Glas (alcohol,beverage,cabernet,drink,grapes,merlot,sauvignon)' ),
			),
			'Brands'              => array(
				array( 'fab fa-creative-commons' => 'Creative Commons' ),
				array( 'fab fa-twitter-square' => 'Twitter Square (social network,tweet)' ),
				array( 'fab fa-facebook-square' => 'Facebook Square (social network)' ),
				array( 'fab fa-linkedin' => 'LinkedIn (linkedin-square)' ),
				array( 'fab fa-github-square' => 'GitHub Square (octocat)' ),
				array( 'fab fa-twitter' => 'Twitter (social network,tweet)' ),
				array( 'fab fa-facebook-f' => 'Facebook F (facebook)' ),
				array( 'fab fa-github' => 'GitHub (octocat)' ),
				array( 'fab fa-pinterest' => 'Pinterest' ),
				array( 'fab fa-pinterest-square' => 'Pinterest Square' ),
				array( 'fab fa-google-plus-square' => 'Google Plus Square (social network)' ),
				array( 'fab fa-google-plus-g' => 'Google Plus G (google-plus,social network)' ),
				array( 'fab fa-linkedin-in' => 'LinkedIn In (linkedin)' ),
				array( 'fab fa-github-alt' => 'Alternate GitHub (octocat)' ),
				array( 'fab fa-maxcdn' => 'MaxCDN' ),
				array( 'fab fa-html5' => 'HTML 5 Logo' ),
				array( 'fab fa-css3' => 'CSS 3 Logo (code)' ),
				array( 'fab fa-youtube-square' => 'YouTube Square' ),
				array( 'fab fa-xing' => 'Xing' ),
				array( 'fab fa-xing-square' => 'Xing Square' ),
				array( 'fab fa-dropbox' => 'Dropbox' ),
				array( 'fab fa-stack-overflow' => 'Stack Overflow' ),
				array( 'fab fa-instagram' => 'Instagram' ),
				array( 'fab fa-flickr' => 'Flickr' ),
				array( 'fab fa-adn' => 'App.net' ),
				array( 'fab fa-bitbucket' => 'Bitbucket (atlassian,bitbucket-square,git)' ),
				array( 'fab fa-tumblr' => 'Tumblr' ),
				array( 'fab fa-tumblr-square' => 'Tumblr Square' ),
				array( 'fab fa-apple' => 'Apple (fruit,ios,mac,operating system,os,osx)' ),
				array( 'fab fa-windows' => 'Windows (microsoft,operating system,os)' ),
				array( 'fab fa-android' => 'Android (robot)' ),
				array( 'fab fa-linux' => 'Linux (tux)' ),
				array( 'fab fa-dribbble' => 'Dribbble' ),
				array( 'fab fa-skype' => 'Skype' ),
				array( 'fab fa-foursquare' => 'Foursquare' ),
				array( 'fab fa-trello' => 'Trello (atlassian)' ),
				array( 'fab fa-gratipay' => 'Gratipay (Gittip) (favorite,heart,like,love)' ),
				array( 'fab fa-vk' => 'VK' ),
				array( 'fab fa-weibo' => 'Weibo' ),
				array( 'fab fa-renren' => 'Renren' ),
				array( 'fab fa-pagelines' => 'Pagelines (eco,flora,leaf,leaves,nature,plant,tree)' ),
				array( 'fab fa-stack-exchange' => 'Stack Exchange' ),
				array( 'fab fa-vimeo-square' => 'Vimeo Square' ),
				array( 'fab fa-slack' => 'Slack Logo (anchor,hash,hashtag)' ),
				array( 'fab fa-wordpress' => 'WordPress Logo' ),
				array( 'fab fa-openid' => 'OpenID' ),
				array( 'fab fa-yahoo' => 'Yahoo Logo' ),
				array( 'fab fa-google' => 'Google Logo' ),
				array( 'fab fa-reddit' => 'reddit Logo' ),
				array( 'fab fa-reddit-square' => 'reddit Square' ),
				array( 'fab fa-stumbleupon-circle' => 'StumbleUpon Circle' ),
				array( 'fab fa-stumbleupon' => 'StumbleUpon Logo' ),
				array( 'fab fa-delicious' => 'Delicious' ),
				array( 'fab fa-digg' => 'Digg Logo' ),
				array( 'fab fa-pied-piper-pp' => 'Pied Piper PP Logo (Old)' ),
				array( 'fab fa-pied-piper-alt' => 'Alternate Pied Piper Logo' ),
				array( 'fab fa-drupal' => 'Drupal Logo' ),
				array( 'fab fa-joomla' => 'Joomla Logo' ),
				array( 'fab fa-behance' => 'Behance' ),
				array( 'fab fa-behance-square' => 'Behance Square' ),
				array( 'fab fa-deviantart' => 'deviantART' ),
				array( 'fab fa-vine' => 'Vine' ),
				array( 'fab fa-codepen' => 'Codepen' ),
				array( 'fab fa-jsfiddle' => 'jsFiddle' ),
				array( 'fab fa-rebel' => 'Rebel Alliance' ),
				array( 'fab fa-empire' => 'Galactic Empire' ),
				array( 'fab fa-git-square' => 'Git Square' ),
				array( 'fab fa-git' => 'Git' ),
				array( 'fab fa-hacker-news' => 'Hacker News' ),
				array( 'fab fa-tencent-weibo' => 'Tencent Weibo' ),
				array( 'fab fa-qq' => 'QQ' ),
				array( 'fab fa-weixin' => 'Weixin (WeChat)' ),
				array( 'fab fa-slideshare' => 'Slideshare' ),
				array( 'fab fa-yelp' => 'Yelp' ),
				array( 'fab fa-lastfm' => 'last.fm' ),
				array( 'fab fa-lastfm-square' => 'last.fm Square' ),
				array( 'fab fa-ioxhost' => 'ioxhost' ),
				array( 'fab fa-angellist' => 'AngelList' ),
				array( 'fab fa-font-awesome' => 'Font Awesome (meanpath)' ),
				array( 'fab fa-buysellads' => 'BuySellAds' ),
				array( 'fab fa-connectdevelop' => 'Connect Develop' ),
				array( 'fab fa-dashcube' => 'DashCube' ),
				array( 'fab fa-forumbee' => 'Forumbee' ),
				array( 'fab fa-leanpub' => 'Leanpub' ),
				array( 'fab fa-sellsy' => 'Sellsy' ),
				array( 'fab fa-shirtsinbulk' => 'Shirts in Bulk' ),
				array( 'fab fa-simplybuilt' => 'SimplyBuilt' ),
				array( 'fab fa-skyatlas' => 'skyatlas' ),
				array( 'fab fa-facebook' => 'Facebook (facebook-official,social network)' ),
				array( 'fab fa-pinterest-p' => 'Pinterest P' ),
				array( 'fab fa-whatsapp' => 'What\'s App' ),
				array( 'fab fa-viacoin' => 'Viacoin' ),
				array( 'fab fa-medium' => 'Medium' ),
				array( 'fab fa-y-combinator' => 'Y Combinator' ),
				array( 'fab fa-optin-monster' => 'Optin Monster' ),
				array( 'fab fa-opencart' => 'OpenCart' ),
				array( 'fab fa-expeditedssl' => 'ExpeditedSSL' ),
				array( 'fab fa-tripadvisor' => 'TripAdvisor' ),
				array( 'fab fa-odnoklassniki' => 'Odnoklassniki' ),
				array( 'fab fa-odnoklassniki-square' => 'Odnoklassniki Square' ),
				array( 'fab fa-get-pocket' => 'Get Pocket' ),
				array( 'fab fa-wikipedia-w' => 'Wikipedia W' ),
				array( 'fab fa-safari' => 'Safari (browser)' ),
				array( 'fab fa-chrome' => 'Chrome (browser)' ),
				array( 'fab fa-firefox' => 'Firefox (browser)' ),
				array( 'fab fa-opera' => 'Opera' ),
				array( 'fab fa-internet-explorer' => 'Internet-explorer (browser,ie)' ),
				array( 'fab fa-contao' => 'Contao' ),
				array( 'fab fa-500px' => '500px' ),
				array( 'fab fa-amazon' => 'Amazon' ),
				array( 'fab fa-houzz' => 'Houzz' ),
				array( 'fab fa-vimeo-v' => 'Vimeo (vimeo)' ),
				array( 'fab fa-black-tie' => 'Font Awesome Black Tie' ),
				array( 'fab fa-fonticons' => 'Fonticons' ),
				array( 'fab fa-reddit-alien' => 'reddit Alien' ),
				array( 'fab fa-edge' => 'Edge Browser (browser,ie)' ),
				array( 'fab fa-codiepie' => 'Codie Pie' ),
				array( 'fab fa-modx' => 'MODX' ),
				array( 'fab fa-fort-awesome' => 'Fort Awesome (castle)' ),
				array( 'fab fa-usb' => 'USB' ),
				array( 'fab fa-product-hunt' => 'Product Hunt' ),
				array( 'fab fa-mixcloud' => 'Mixcloud' ),
				array( 'fab fa-scribd' => 'Scribd' ),
				array( 'fab fa-gitlab' => 'GitLab (Axosoft)' ),
				array( 'fab fa-wpbeginner' => 'WPBeginner' ),
				array( 'fab fa-wpforms' => 'WPForms' ),
				array( 'fab fa-envira' => 'Envira Gallery (leaf)' ),
				array( 'fab fa-glide' => 'Glide' ),
				array( 'fab fa-glide-g' => 'Glide G' ),
				array( 'fab fa-viadeo' => 'Video' ),
				array( 'fab fa-viadeo-square' => 'Video Square' ),
				array( 'fab fa-snapchat' => 'Snapchat' ),
				array( 'fab fa-snapchat-ghost' => 'Snapchat Ghost' ),
				array( 'fab fa-snapchat-square' => 'Snapchat Square' ),
				array( 'fab fa-pied-piper' => 'Pied Piper Logo' ),
				array( 'fab fa-first-order' => 'First Order' ),
				array( 'fab fa-yoast' => 'Yoast' ),
				array( 'fab fa-themeisle' => 'ThemeIsle' ),
				array( 'fab fa-google-plus' => 'Google Plus (google-plus-circle,google-plus-official)' ),
				array( 'fab fa-linode' => 'Linode' ),
				array( 'fab fa-quora' => 'Quora' ),
				array( 'fab fa-free-code-camp' => 'Free Code Camp' ),
				array( 'fab fa-telegram' => 'Telegram' ),
				array( 'fab fa-bandcamp' => 'Bandcamp' ),
				array( 'fab fa-grav' => 'Grav' ),
				array( 'fab fa-etsy' => 'Etsy' ),
				array( 'fab fa-imdb' => 'IMDB' ),
				array( 'fab fa-ravelry' => 'Ravelry' ),
				array( 'fab fa-sellcast' => 'Sellcast (eercast)' ),
				array( 'fab fa-superpowers' => 'Superpowers' ),
				array( 'fab fa-wpexplorer' => 'WPExplorer' ),
				array( 'fab fa-meetup' => 'Meetup' ),
			),
			'Buildings'           => array(
				array( 'fas fa-archway' => 'Archway (arc,monument,road,street,tunnel)' ),
				array( 'fas fa-building' => 'Building (apartment,business,city,company,office,work)' ),
				array( 'far fa-building' => 'Building (apartment,business,city,company,office,work)' ),
				array( 'fas fa-campground' => 'Campground (camping,fall,outdoors,teepee,tent,tipi)' ),
				array( 'fas fa-church' => 'Church (building,cathedral,chapel,community,religion)' ),
				array( 'fas fa-city' => 'City (buildings,busy,skyscrapers,urban,windows)' ),
				array( 'fas fa-clinic-medical' => 'Medical Clinic (doctor,general practitioner,hospital,infirmary,medicine,office,outpatient)' ),
				array( 'fas fa-dungeon' => 'Dungeon (Dungeons & Dragons,building,d&d,dnd,door,entrance,fantasy,gate)' ),
				array( 'fas fa-gopuram' => 'Gopuram (building,entrance,hinduism,temple,tower)' ),
				array( 'fas fa-home' => 'home (abode,building,house,main)' ),
				array( 'fas fa-hospital' => 'hospital (building,emergency room,medical center)' ),
				array( 'far fa-hospital' => 'hospital (building,emergency room,medical center)' ),
				array( 'fas fa-hospital-alt' => 'Alternate Hospital (building,emergency room,medical center)' ),
				array( 'fas fa-hotel' => 'Hotel (building,inn,lodging,motel,resort,travel)' ),
				array( 'fas fa-house-damage' => 'Damaged House (building,devastation,disaster,home,insurance)' ),
				array( 'fas fa-igloo' => 'Igloo (dome,dwelling,eskimo,home,house,ice,snow)' ),
				array( 'fas fa-industry' => 'Industry (building,factory,industrial,manufacturing,mill,warehouse)' ),
				array( 'fas fa-kaaba' => 'Kaaba (building,cube,islam,muslim)' ),
				array( 'fas fa-landmark' => 'Landmark (building,historic,memorable,monument,politics)' ),
				array( 'fas fa-monument' => 'Monument (building,historic,landmark,memorable)' ),
				array( 'fas fa-mosque' => 'Mosque (building,islam,landmark,muslim)' ),
				array( 'fas fa-place-of-worship' => 'Place of Worship (building,church,holy,mosque,synagogue)' ),
				array( 'fas fa-school' => 'School (building,education,learn,student,teacher)' ),
				array( 'fas fa-store' => 'Store (building,buy,purchase,shopping)' ),
				array( 'fas fa-store-alt' => 'Alternate Store (building,buy,purchase,shopping)' ),
				array( 'fas fa-synagogue' => 'Synagogue (building,jewish,judaism,religion,star of david,temple)' ),
				array( 'fas fa-torii-gate' => 'Torii Gate (building,shintoism)' ),
				array( 'fas fa-university' => 'University (bank,building,college,higher education - students,institution)' ),
				array( 'fas fa-vihara' => 'Vihara (buddhism,buddhist,building,monastery)' ),
				array( 'fas fa-warehouse' => 'Warehouse (building,capacity,garage,inventory,storage)' ),
			),
			'Business'            => array(
				array( 'fas fa-address-book' => 'Address Book (contact,directory,index,little black book,rolodex)' ),
				array( 'far fa-address-book' => 'Address Book (contact,directory,index,little black book,rolodex)' ),
				array( 'fas fa-address-card' => 'Address Card (about,contact,id,identification,postcard,profile)' ),
				array( 'far fa-address-card' => 'Address Card (about,contact,id,identification,postcard,profile)' ),
				array( 'fas fa-archive' => 'Archive (box,package,save,storage)' ),
				array( 'fas fa-balance-scale' => 'Balance Scale (balanced,justice,legal,measure,weight)' ),
				array( 'fas fa-balance-scale-left' => 'Balance Scale (Left-Weighted) (justice,legal,measure,unbalanced,weight)' ),
				array( 'fas fa-balance-scale-right' => 'Balance Scale (Right-Weighted) (justice,legal,measure,unbalanced,weight)' ),
				array( 'fas fa-birthday-cake' => 'Birthday Cake (anniversary,bakery,candles,celebration,dessert,frosting,holiday,party,pastry)' ),
				array( 'fas fa-book' => 'book (diary,documentation,journal,library,read)' ),
				array( 'fas fa-briefcase' => 'Briefcase (bag,business,luggage,office,work)' ),
				array( 'fas fa-building' => 'Building (apartment,business,city,company,office,work)' ),
				array( 'far fa-building' => 'Building (apartment,business,city,company,office,work)' ),
				array( 'fas fa-bullhorn' => 'bullhorn (announcement,broadcast,louder,megaphone,share)' ),
				array( 'fas fa-bullseye' => 'Bullseye (archery,goal,objective,target)' ),
				array( 'fas fa-business-time' => 'Business Time (alarm,briefcase,business socks,clock,flight of the conchords,reminder,wednesday)' ),
				array( 'fas fa-calculator' => 'Calculator (abacus,addition,arithmetic,counting,math,multiplication,subtraction)' ),
				array( 'fas fa-calendar' => 'Calendar (calendar-o,date,event,schedule,time,when)' ),
				array( 'far fa-calendar' => 'Calendar (calendar-o,date,event,schedule,time,when)' ),
				array( 'fas fa-calendar-alt' => 'Alternate Calendar (calendar,date,event,schedule,time,when)' ),
				array( 'far fa-calendar-alt' => 'Alternate Calendar (calendar,date,event,schedule,time,when)' ),
				array( 'fas fa-certificate' => 'certificate (badge,star,verified)' ),
				array( 'fas fa-chart-area' => 'Area Chart (analytics,area,chart,graph)' ),
				array( 'fas fa-chart-bar' => 'Bar Chart (analytics,bar,chart,graph)' ),
				array( 'far fa-chart-bar' => 'Bar Chart (analytics,bar,chart,graph)' ),
				array( 'fas fa-chart-line' => 'Line Chart (activity,analytics,chart,dashboard,gain,graph,increase,line)' ),
				array( 'fas fa-chart-pie' => 'Pie Chart (analytics,chart,diagram,graph,pie)' ),
				array( 'fas fa-city' => 'City (buildings,busy,skyscrapers,urban,windows)' ),
				array( 'fas fa-clipboard' => 'Clipboard (copy,notes,paste,record)' ),
				array( 'far fa-clipboard' => 'Clipboard (copy,notes,paste,record)' ),
				array( 'fas fa-coffee' => 'Coffee (beverage,breakfast,cafe,drink,fall,morning,mug,seasonal,tea)' ),
				array( 'fas fa-columns' => 'Columns (browser,dashboard,organize,panes,split)' ),
				array( 'fas fa-compass' => 'Compass (directions,directory,location,menu,navigation,safari,travel)' ),
				array( 'far fa-compass' => 'Compass (directions,directory,location,menu,navigation,safari,travel)' ),
				array( 'fas fa-copy' => 'Copy (clone,duplicate,file,files-o,paper,paste)' ),
				array( 'far fa-copy' => 'Copy (clone,duplicate,file,files-o,paper,paste)' ),
				array( 'fas fa-copyright' => 'Copyright (brand,mark,register,trademark)' ),
				array( 'far fa-copyright' => 'Copyright (brand,mark,register,trademark)' ),
				array( 'fas fa-cut' => 'Cut (clip,scissors,snip)' ),
				array( 'fas fa-edit' => 'Edit (edit,pen,pencil,update,write)' ),
				array( 'far fa-edit' => 'Edit (edit,pen,pencil,update,write)' ),
				array( 'fas fa-envelope' => 'Envelope (e-mail,email,letter,mail,message,notification,support)' ),
				array( 'far fa-envelope' => 'Envelope (e-mail,email,letter,mail,message,notification,support)' ),
				array( 'fas fa-envelope-open' => 'Envelope Open (e-mail,email,letter,mail,message,notification,support)' ),
				array( 'far fa-envelope-open' => 'Envelope Open (e-mail,email,letter,mail,message,notification,support)' ),
				array( 'fas fa-envelope-square' => 'Envelope Square (e-mail,email,letter,mail,message,notification,support)' ),
				array( 'fas fa-eraser' => 'eraser (art,delete,remove,rubber)' ),
				array( 'fas fa-fax' => 'Fax (business,communicate,copy,facsimile,send)' ),
				array( 'fas fa-file' => 'File (document,new,page,pdf,resume)' ),
				array( 'far fa-file' => 'File (document,new,page,pdf,resume)' ),
				array( 'fas fa-file-alt' => 'Alternate File (document,file-text,invoice,new,page,pdf)' ),
				array( 'far fa-file-alt' => 'Alternate File (document,file-text,invoice,new,page,pdf)' ),
				array( 'fas fa-folder' => 'Folder (archive,directory,document,file)' ),
				array( 'far fa-folder' => 'Folder (archive,directory,document,file)' ),
				array( 'fas fa-folder-minus' => 'Folder Minus (archive,delete,directory,document,file,negative,remove)' ),
				array( 'fas fa-folder-open' => 'Folder Open (archive,directory,document,empty,file,new)' ),
				array( 'far fa-folder-open' => 'Folder Open (archive,directory,document,empty,file,new)' ),
				array( 'fas fa-folder-plus' => 'Folder Plus (add,archive,create,directory,document,file,new,positive)' ),
				array( 'fas fa-glasses' => 'Glasses (hipster,nerd,reading,sight,spectacles,vision)' ),
				array( 'fas fa-globe' => 'Globe (all,coordinates,country,earth,global,gps,language,localize,location,map,online,place,planet,translate,travel,world)' ),
				array( 'fas fa-highlighter' => 'Highlighter (edit,marker,sharpie,update,write)' ),
				array( 'fas fa-industry' => 'Industry (building,factory,industrial,manufacturing,mill,warehouse)' ),
				array( 'fas fa-landmark' => 'Landmark (building,historic,memorable,monument,politics)' ),
				array( 'fas fa-marker' => 'Marker (design,edit,sharpie,update,write)' ),
				array( 'fas fa-paperclip' => 'Paperclip (attach,attachment,connect,link)' ),
				array( 'fas fa-paste' => 'Paste (clipboard,copy,document,paper)' ),
				array( 'fas fa-pen' => 'Pen (design,edit,update,write)' ),
				array( 'fas fa-pen-alt' => 'Alternate Pen (design,edit,update,write)' ),
				array( 'fas fa-pen-fancy' => 'Pen Fancy (design,edit,fountain pen,update,write)' ),
				array( 'fas fa-pen-nib' => 'Pen Nib (design,edit,fountain pen,update,write)' ),
				array( 'fas fa-pen-square' => 'Pen Square (edit,pencil-square,update,write)' ),
				array( 'fas fa-pencil-alt' => 'Alternate Pencil (design,edit,pencil,update,write)' ),
				array( 'fas fa-percent' => 'Percent (discount,fraction,proportion,rate,ratio)' ),
				array( 'fas fa-phone' => 'Phone (call,earphone,number,support,telephone,voice)' ),
				array( 'fas fa-phone-alt' => 'Alternate Phone (call,earphone,number,support,telephone,voice)' ),
				array( 'fas fa-phone-slash' => 'Phone Slash (call,cancel,earphone,mute,number,support,telephone,voice)' ),
				array( 'fas fa-phone-square' => 'Phone Square (call,earphone,number,support,telephone,voice)' ),
				array( 'fas fa-phone-square-alt' => 'Alternate Phone Square (call,earphone,number,support,telephone,voice)' ),
				array( 'fas fa-phone-volume' => 'Phone Volume (call,earphone,number,sound,support,telephone,voice,volume-control-phone)' ),
				array( 'fas fa-print' => 'print (business,copy,document,office,paper)' ),
				array( 'fas fa-project-diagram' => 'Project Diagram (chart,graph,network,pert)' ),
				array( 'fas fa-registered' => 'Registered Trademark (copyright,mark,trademark)' ),
				array( 'far fa-registered' => 'Registered Trademark (copyright,mark,trademark)' ),
				array( 'fas fa-save' => 'Save (disk,download,floppy,floppy-o)' ),
				array( 'far fa-save' => 'Save (disk,download,floppy,floppy-o)' ),
				array( 'fas fa-sitemap' => 'Sitemap (directory,hierarchy,ia,information architecture,organization)' ),
				array( 'fas fa-socks' => 'Socks (business socks,business time,clothing,feet,flight of the conchords,wednesday)' ),
				array( 'fas fa-sticky-note' => 'Sticky Note (message,note,paper,reminder,sticker)' ),
				array( 'far fa-sticky-note' => 'Sticky Note (message,note,paper,reminder,sticker)' ),
				array( 'fas fa-stream' => 'Stream (flow,list,timeline)' ),
				array( 'fas fa-table' => 'table (data,excel,spreadsheet)' ),
				array( 'fas fa-tag' => 'tag (discount,label,price,shopping)' ),
				array( 'fas fa-tags' => 'tags (discount,label,price,shopping)' ),
				array( 'fas fa-tasks' => 'Tasks (checklist,downloading,downloads,loading,progress,project management,settings,to do)' ),
				array( 'fas fa-thumbtack' => 'Thumbtack (coordinates,location,marker,pin,thumb-tack)' ),
				array( 'fas fa-trademark' => 'Trademark (copyright,register,symbol)' ),
				array( 'fas fa-wallet' => 'Wallet (billfold,cash,currency,money)' ),
			),
			'Camping'             => array(
				array( 'fas fa-binoculars' => 'Binoculars (glasses,magnify,scenic,spyglass,view)' ),
				array( 'fas fa-campground' => 'Campground (camping,fall,outdoors,teepee,tent,tipi)' ),
				array( 'fas fa-compass' => 'Compass (directions,directory,location,menu,navigation,safari,travel)' ),
				array( 'far fa-compass' => 'Compass (directions,directory,location,menu,navigation,safari,travel)' ),
				array( 'fas fa-fire' => 'fire (burn,caliente,flame,heat,hot,popular)' ),
				array( 'fas fa-fire-alt' => 'Alternate Fire (burn,caliente,flame,heat,hot,popular)' ),
				array( 'fas fa-first-aid' => 'First Aid (emergency,emt,health,medical,rescue)' ),
				array( 'fas fa-frog' => 'Frog (amphibian,bullfrog,fauna,hop,kermit,kiss,prince,ribbit,toad,wart)' ),
				array( 'fas fa-hiking' => 'Hiking (activity,backpack,fall,fitness,outdoors,person,seasonal,walking)' ),
				array( 'fas fa-map' => 'Map (address,coordinates,destination,gps,localize,location,map,navigation,paper,pin,place,point of interest,position,route,travel)' ),
				array( 'far fa-map' => 'Map (address,coordinates,destination,gps,localize,location,map,navigation,paper,pin,place,point of interest,position,route,travel)' ),
				array( 'fas fa-map-marked' => 'Map Marked (address,coordinates,destination,gps,localize,location,map,navigation,paper,pin,place,point of interest,position,route,travel)' ),
				array( 'fas fa-map-marked-alt' => 'Alternate Map Marked (address,coordinates,destination,gps,localize,location,map,navigation,paper,pin,place,point of interest,position,route,travel)' ),
				array( 'fas fa-map-signs' => 'Map Signs (directions,directory,map,signage,wayfinding)' ),
				array( 'fas fa-mountain' => 'Mountain (glacier,hiking,hill,landscape,travel,view)' ),
				array( 'fas fa-route' => 'Route (directions,navigation,travel)' ),
				array( 'fas fa-toilet-paper' => 'Toilet Paper (bathroom,halloween,holiday,lavatory,prank,restroom,roll)' ),
				array( 'fas fa-tree' => 'Tree (bark,fall,flora,forest,nature,plant,seasonal)' ),
			),
			'Charity'             => array(
				array( 'fas fa-dollar-sign' => 'Dollar Sign ($,cost,dollar-sign,money,price,usd)' ),
				array( 'fas fa-donate' => 'Donate (contribute,generosity,gift,give)' ),
				array( 'fas fa-dove' => 'Dove (bird,fauna,flying,peace,war)' ),
				array( 'fas fa-gift' => 'gift (christmas,generosity,giving,holiday,party,present,wrapped,xmas)' ),
				array( 'fas fa-globe' => 'Globe (all,coordinates,country,earth,global,gps,language,localize,location,map,online,place,planet,translate,travel,world)' ),
				array( 'fas fa-hand-holding-heart' => 'Hand Holding Heart (carry,charity,gift,lift,package)' ),
				array( 'fas fa-hand-holding-usd' => 'Hand Holding US Dollar ($,carry,dollar sign,donation,giving,lift,money,price)' ),
				array( 'fas fa-hands-helping' => 'Helping Hands (aid,assistance,handshake,partnership,volunteering)' ),
				array( 'fas fa-handshake' => 'Handshake (agreement,greeting,meeting,partnership)' ),
				array( 'far fa-handshake' => 'Handshake (agreement,greeting,meeting,partnership)' ),
				array( 'fas fa-heart' => 'Heart (favorite,like,love,relationship,valentine)' ),
				array( 'far fa-heart' => 'Heart (favorite,like,love,relationship,valentine)' ),
				array( 'fas fa-leaf' => 'leaf (eco,flora,nature,plant,vegan)' ),
				array( 'fas fa-parachute-box' => 'Parachute Box (aid,assistance,rescue,supplies)' ),
				array( 'fas fa-piggy-bank' => 'Piggy Bank (bank,save,savings)' ),
				array( 'fas fa-ribbon' => 'Ribbon (badge,cause,lapel,pin)' ),
				array( 'fas fa-seedling' => 'Seedling (flora,grow,plant,vegan)' ),
			),
			'Chat'                => array(
				array( 'fas fa-comment' => 'comment (bubble,chat,commenting,conversation,feedback,message,note,notification,sms,speech,texting)' ),
				array( 'far fa-comment' => 'comment (bubble,chat,commenting,conversation,feedback,message,note,notification,sms,speech,texting)' ),
				array( 'fas fa-comment-alt' => 'Alternate Comment (bubble,chat,commenting,conversation,feedback,message,note,notification,sms,speech,texting)' ),
				array( 'far fa-comment-alt' => 'Alternate Comment (bubble,chat,commenting,conversation,feedback,message,note,notification,sms,speech,texting)' ),
				array( 'fas fa-comment-dots' => 'Comment Dots (bubble,chat,commenting,conversation,feedback,message,more,note,notification,reply,sms,speech,texting)' ),
				array( 'far fa-comment-dots' => 'Comment Dots (bubble,chat,commenting,conversation,feedback,message,more,note,notification,reply,sms,speech,texting)' ),
				array( 'fas fa-comment-medical' => 'Alternate Medical Chat (advice,bubble,chat,commenting,conversation,diagnose,feedback,message,note,notification,prescription,sms,speech,texting)' ),
				array( 'fas fa-comment-slash' => 'Comment Slash (bubble,cancel,chat,commenting,conversation,feedback,message,mute,note,notification,quiet,sms,speech,texting)' ),
				array( 'fas fa-comments' => 'comments (bubble,chat,commenting,conversation,feedback,message,note,notification,sms,speech,texting)' ),
				array( 'far fa-comments' => 'comments (bubble,chat,commenting,conversation,feedback,message,note,notification,sms,speech,texting)' ),
				array( 'fas fa-frown' => 'Frowning Face (disapprove,emoticon,face,rating,sad)' ),
				array( 'far fa-frown' => 'Frowning Face (disapprove,emoticon,face,rating,sad)' ),
				array( 'fas fa-icons' => 'Icons (bolt,emoji,heart,image,music,photo,symbols)' ),
				array( 'fas fa-meh' => 'Neutral Face (emoticon,face,neutral,rating)' ),
				array( 'far fa-meh' => 'Neutral Face (emoticon,face,neutral,rating)' ),
				array( 'fas fa-phone' => 'Phone (call,earphone,number,support,telephone,voice)' ),
				array( 'fas fa-phone-alt' => 'Alternate Phone (call,earphone,number,support,telephone,voice)' ),
				array( 'fas fa-phone-slash' => 'Phone Slash (call,cancel,earphone,mute,number,support,telephone,voice)' ),
				array( 'fas fa-poo' => 'Poo (crap,poop,shit,smile,turd)' ),
				array( 'fas fa-quote-left' => 'quote-left (mention,note,phrase,text,type)' ),
				array( 'fas fa-quote-right' => 'quote-right (mention,note,phrase,text,type)' ),
				array( 'fas fa-smile' => 'Smiling Face (approve,emoticon,face,happy,rating,satisfied)' ),
				array( 'far fa-smile' => 'Smiling Face (approve,emoticon,face,happy,rating,satisfied)' ),
				array( 'fas fa-sms' => 'SMS (chat,conversation,message,mobile,notification,phone,sms,texting)' ),
				array( 'fas fa-video' => 'Video (camera,film,movie,record,video-camera)' ),
				array( 'fas fa-video-slash' => 'Video Slash (add,create,film,new,positive,record,video)' ),
			),
			'Chess'               => array(
				array( 'fas fa-chess' => 'Chess (board,castle,checkmate,game,king,rook,strategy,tournament)' ),
				array( 'fas fa-chess-bishop' => 'Chess Bishop (board,checkmate,game,strategy)' ),
				array( 'fas fa-chess-board' => 'Chess Board (board,checkmate,game,strategy)' ),
				array( 'fas fa-chess-king' => 'Chess King (board,checkmate,game,strategy)' ),
				array( 'fas fa-chess-knight' => 'Chess Knight (board,checkmate,game,horse,strategy)' ),
				array( 'fas fa-chess-pawn' => 'Chess Pawn (board,checkmate,game,strategy)' ),
				array( 'fas fa-chess-queen' => 'Chess Queen (board,checkmate,game,strategy)' ),
				array( 'fas fa-chess-rook' => 'Chess Rook (board,castle,checkmate,game,strategy)' ),
				array( 'fas fa-square-full' => 'Square Full (block,box,shape)' ),
			),
			'Childhood'           => array(
				array( 'fas fa-apple-alt' => 'Fruit Apple (fall,fruit,fuji,macintosh,orchard,seasonal,vegan)' ),
				array( 'fas fa-baby' => 'Baby (child,diaper,doll,human,infant,kid,offspring,person,sprout)' ),
				array( 'fas fa-baby-carriage' => 'Baby Carriage (buggy,carrier,infant,push,stroller,transportation,walk,wheels)' ),
				array( 'fas fa-bath' => 'Bath (clean,shower,tub,wash)' ),
				array( 'fas fa-biking' => 'Biking (bicycle,bike,cycle,cycling,ride,wheel)' ),
				array( 'fas fa-birthday-cake' => 'Birthday Cake (anniversary,bakery,candles,celebration,dessert,frosting,holiday,party,pastry)' ),
				array( 'fas fa-cookie' => 'Cookie (baked good,chips,chocolate,eat,snack,sweet,treat)' ),
				array( 'fas fa-cookie-bite' => 'Cookie Bite (baked good,bitten,chips,chocolate,eat,snack,sweet,treat)' ),
				array( 'fas fa-gamepad' => 'Gamepad (arcade,controller,d-pad,joystick,video,video game)' ),
				array( 'fas fa-ice-cream' => 'Ice Cream (chocolate,cone,dessert,frozen,scoop,sorbet,vanilla,yogurt)' ),
				array( 'fas fa-mitten' => 'Mitten (clothing,cold,glove,hands,knitted,seasonal,warmth)' ),
				array( 'fas fa-robot' => 'Robot (android,automate,computer,cyborg)' ),
				array( 'fas fa-school' => 'School (building,education,learn,student,teacher)' ),
				array( 'fas fa-shapes' => 'Shapes (blocks,build,circle,square,triangle)' ),
				array( 'fas fa-snowman' => 'Snowman (decoration,frost,frosty,holiday)' ),
			),
			'Clothing'            => array(
				array( 'fas fa-graduation-cap' => 'Graduation Cap (ceremony,college,graduate,learning,school,student)' ),
				array( 'fas fa-hat-cowboy' => 'Cowboy Hat (buckaroo,horse,jackeroo,john b.,old west,pardner,ranch,rancher,rodeo,western,wrangler)' ),
				array( 'fas fa-hat-cowboy-side' => 'Cowboy Hat Side (buckaroo,horse,jackeroo,john b.,old west,pardner,ranch,rancher,rodeo,western,wrangler)' ),
				array( 'fas fa-hat-wizard' => 'Wizard\'s Hat (Dungeons & Dragons,accessory,buckle,clothing,d&d,dnd,fantasy,halloween,head,holiday,mage,magic,pointy,witch)' ),
				array( 'fas fa-mitten' => 'Mitten (clothing,cold,glove,hands,knitted,seasonal,warmth)' ),
				array( 'fas fa-shoe-prints' => 'Shoe Prints (feet,footprints,steps,walk)' ),
				array( 'fas fa-socks' => 'Socks (business socks,business time,clothing,feet,flight of the conchords,wednesday)' ),
				array( 'fas fa-tshirt' => 'T-Shirt (clothing,fashion,garment,shirt)' ),
				array( 'fas fa-user-tie' => 'User Tie (avatar,business,clothing,formal,professional,suit)' ),
			),
			'Code'                => array(
				array( 'fas fa-archive' => 'Archive (box,package,save,storage)' ),
				array( 'fas fa-barcode' => 'barcode (info,laser,price,scan,upc)' ),
				array( 'fas fa-bath' => 'Bath (clean,shower,tub,wash)' ),
				array( 'fas fa-bug' => 'Bug (beetle,error,insect,report)' ),
				array( 'fas fa-code' => 'Code (brackets,code,development,html)' ),
				array( 'fas fa-code-branch' => 'Code Branch (branch,code-fork,fork,git,github,rebase,svn,vcs,version)' ),
				array( 'fas fa-coffee' => 'Coffee (beverage,breakfast,cafe,drink,fall,morning,mug,seasonal,tea)' ),
				array( 'fas fa-file' => 'File (document,new,page,pdf,resume)' ),
				array( 'far fa-file' => 'File (document,new,page,pdf,resume)' ),
				array( 'fas fa-file-alt' => 'Alternate File (document,file-text,invoice,new,page,pdf)' ),
				array( 'far fa-file-alt' => 'Alternate File (document,file-text,invoice,new,page,pdf)' ),
				array( 'fas fa-file-code' => 'Code File (css,development,document,html)' ),
				array( 'far fa-file-code' => 'Code File (css,development,document,html)' ),
				array( 'fas fa-filter' => 'Filter (funnel,options,separate,sort)' ),
				array( 'fas fa-fire-extinguisher' => 'fire-extinguisher (burn,caliente,fire fighter,flame,heat,hot,rescue)' ),
				array( 'fas fa-folder' => 'Folder (archive,directory,document,file)' ),
				array( 'far fa-folder' => 'Folder (archive,directory,document,file)' ),
				array( 'fas fa-folder-open' => 'Folder Open (archive,directory,document,empty,file,new)' ),
				array( 'far fa-folder-open' => 'Folder Open (archive,directory,document,empty,file,new)' ),
				array( 'fas fa-keyboard' => 'Keyboard (accessory,edit,input,text,type,write)' ),
				array( 'far fa-keyboard' => 'Keyboard (accessory,edit,input,text,type,write)' ),
				array( 'fas fa-laptop-code' => 'Laptop Code (computer,cpu,dell,demo,develop,device,mac,macbook,machine,pc)' ),
				array( 'fas fa-microchip' => 'Microchip (cpu,hardware,processor,technology)' ),
				array( 'fas fa-project-diagram' => 'Project Diagram (chart,graph,network,pert)' ),
				array( 'fas fa-qrcode' => 'qrcode (barcode,info,information,scan)' ),
				array( 'fas fa-shield-alt' => 'Alternate Shield (achievement,award,block,defend,security,winner)' ),
				array( 'fas fa-sitemap' => 'Sitemap (directory,hierarchy,ia,information architecture,organization)' ),
				array( 'fas fa-stream' => 'Stream (flow,list,timeline)' ),
				array( 'fas fa-terminal' => 'Terminal (code,command,console,development,prompt)' ),
				array( 'fas fa-user-secret' => 'User Secret (clothing,coat,hat,incognito,person,privacy,spy,whisper)' ),
				array( 'fas fa-window-close' => 'Window Close (browser,cancel,computer,development)' ),
				array( 'far fa-window-close' => 'Window Close (browser,cancel,computer,development)' ),
				array( 'fas fa-window-maximize' => 'Window Maximize (browser,computer,development,expand)' ),
				array( 'far fa-window-maximize' => 'Window Maximize (browser,computer,development,expand)' ),
				array( 'fas fa-window-minimize' => 'Window Minimize (browser,collapse,computer,development)' ),
				array( 'far fa-window-minimize' => 'Window Minimize (browser,collapse,computer,development)' ),
				array( 'fas fa-window-restore' => 'Window Restore (browser,computer,development)' ),
				array( 'far fa-window-restore' => 'Window Restore (browser,computer,development)' ),
			),
			'Communication'       => array(
				array( 'fas fa-address-book' => 'Address Book (contact,directory,index,little black book,rolodex)' ),
				array( 'far fa-address-book' => 'Address Book (contact,directory,index,little black book,rolodex)' ),
				array( 'fas fa-address-card' => 'Address Card (about,contact,id,identification,postcard,profile)' ),
				array( 'far fa-address-card' => 'Address Card (about,contact,id,identification,postcard,profile)' ),
				array( 'fas fa-american-sign-language-interpreting' => 'American Sign Language Interpreting (asl,deaf,finger,hand,interpret,speak)' ),
				array( 'fas fa-assistive-listening-systems' => 'Assistive Listening Systems (amplify,audio,deaf,ear,headset,hearing,sound)' ),
				array( 'fas fa-at' => 'At (address,author,e-mail,email,handle)' ),
				array( 'fas fa-bell' => 'bell (alarm,alert,chime,notification,reminder)' ),
				array( 'far fa-bell' => 'bell (alarm,alert,chime,notification,reminder)' ),
				array( 'fas fa-bell-slash' => 'Bell Slash (alert,cancel,disabled,notification,off,reminder)' ),
				array( 'far fa-bell-slash' => 'Bell Slash (alert,cancel,disabled,notification,off,reminder)' ),
				array( 'fab fa-bluetooth' => 'Bluetooth' ),
				array( 'fab fa-bluetooth-b' => 'Bluetooth' ),
				array( 'fas fa-broadcast-tower' => 'Broadcast Tower (airwaves,antenna,radio,reception,waves)' ),
				array( 'fas fa-bullhorn' => 'bullhorn (announcement,broadcast,louder,megaphone,share)' ),
				array( 'fas fa-chalkboard' => 'Chalkboard (blackboard,learning,school,teaching,whiteboard,writing)' ),
				array( 'fas fa-comment' => 'comment (bubble,chat,commenting,conversation,feedback,message,note,notification,sms,speech,texting)' ),
				array( 'far fa-comment' => 'comment (bubble,chat,commenting,conversation,feedback,message,note,notification,sms,speech,texting)' ),
				array( 'fas fa-comment-alt' => 'Alternate Comment (bubble,chat,commenting,conversation,feedback,message,note,notification,sms,speech,texting)' ),
				array( 'far fa-comment-alt' => 'Alternate Comment (bubble,chat,commenting,conversation,feedback,message,note,notification,sms,speech,texting)' ),
				array( 'fas fa-comments' => 'comments (bubble,chat,commenting,conversation,feedback,message,note,notification,sms,speech,texting)' ),
				array( 'far fa-comments' => 'comments (bubble,chat,commenting,conversation,feedback,message,note,notification,sms,speech,texting)' ),
				array( 'fas fa-envelope' => 'Envelope (e-mail,email,letter,mail,message,notification,support)' ),
				array( 'far fa-envelope' => 'Envelope (e-mail,email,letter,mail,message,notification,support)' ),
				array( 'fas fa-envelope-open' => 'Envelope Open (e-mail,email,letter,mail,message,notification,support)' ),
				array( 'far fa-envelope-open' => 'Envelope Open (e-mail,email,letter,mail,message,notification,support)' ),
				array( 'fas fa-envelope-square' => 'Envelope Square (e-mail,email,letter,mail,message,notification,support)' ),
				array( 'fas fa-fax' => 'Fax (business,communicate,copy,facsimile,send)' ),
				array( 'fas fa-inbox' => 'inbox (archive,desk,email,mail,message)' ),
				array( 'fas fa-language' => 'Language (dialect,idiom,localize,speech,translate,vernacular)' ),
				array( 'fas fa-microphone' => 'microphone (audio,podcast,record,sing,sound,voice)' ),
				array( 'fas fa-microphone-alt' => 'Alternate Microphone (audio,podcast,record,sing,sound,voice)' ),
				array( 'fas fa-microphone-alt-slash' => 'Alternate Microphone Slash (audio,disable,mute,podcast,record,sing,sound,voice)' ),
				array( 'fas fa-microphone-slash' => 'Microphone Slash (audio,disable,mute,podcast,record,sing,sound,voice)' ),
				array( 'fas fa-mobile' => 'Mobile Phone (apple,call,cell phone,cellphone,device,iphone,number,screen,telephone)' ),
				array( 'fas fa-mobile-alt' => 'Alternate Mobile (apple,call,cell phone,cellphone,device,iphone,number,screen,telephone)' ),
				array( 'fas fa-paper-plane' => 'Paper Plane (air,float,fold,mail,paper,send)' ),
				array( 'far fa-paper-plane' => 'Paper Plane (air,float,fold,mail,paper,send)' ),
				array( 'fas fa-phone' => 'Phone (call,earphone,number,support,telephone,voice)' ),
				array( 'fas fa-phone-alt' => 'Alternate Phone (call,earphone,number,support,telephone,voice)' ),
				array( 'fas fa-phone-slash' => 'Phone Slash (call,cancel,earphone,mute,number,support,telephone,voice)' ),
				array( 'fas fa-phone-square' => 'Phone Square (call,earphone,number,support,telephone,voice)' ),
				array( 'fas fa-phone-square-alt' => 'Alternate Phone Square (call,earphone,number,support,telephone,voice)' ),
				array( 'fas fa-phone-volume' => 'Phone Volume (call,earphone,number,sound,support,telephone,voice,volume-control-phone)' ),
				array( 'fas fa-rss' => 'rss (blog,feed,journal,news,writing)' ),
				array( 'fas fa-rss-square' => 'RSS Square (blog,feed,journal,news,writing)' ),
				array( 'fas fa-tty' => 'TTY (communication,deaf,telephone,teletypewriter,text)' ),
				array( 'fas fa-voicemail' => 'Voicemail (answer,inbox,message,phone)' ),
				array( 'fas fa-wifi' => 'WiFi (connection,hotspot,internet,network,wireless)' ),
			),
			'Computers'           => array(
				array( 'fas fa-database' => 'Database (computer,development,directory,memory,storage)' ),
				array( 'fas fa-desktop' => 'Desktop (computer,cpu,demo,desktop,device,imac,machine,monitor,pc,screen)' ),
				array( 'fas fa-download' => 'Download (export,hard drive,save,transfer)' ),
				array( 'fas fa-ethernet' => 'Ethernet (cable,cat 5,cat 6,connection,hardware,internet,network,wired)' ),
				array( 'fas fa-hdd' => 'HDD (cpu,hard drive,harddrive,machine,save,storage)' ),
				array( 'far fa-hdd' => 'HDD (cpu,hard drive,harddrive,machine,save,storage)' ),
				array( 'fas fa-headphones' => 'headphones (audio,listen,music,sound,speaker)' ),
				array( 'fas fa-keyboard' => 'Keyboard (accessory,edit,input,text,type,write)' ),
				array( 'far fa-keyboard' => 'Keyboard (accessory,edit,input,text,type,write)' ),
				array( 'fas fa-laptop' => 'Laptop (computer,cpu,dell,demo,device,mac,macbook,machine,pc)' ),
				array( 'fas fa-memory' => 'Memory (DIMM,RAM,hardware,storage,technology)' ),
				array( 'fas fa-microchip' => 'Microchip (cpu,hardware,processor,technology)' ),
				array( 'fas fa-mobile' => 'Mobile Phone (apple,call,cell phone,cellphone,device,iphone,number,screen,telephone)' ),
				array( 'fas fa-mobile-alt' => 'Alternate Mobile (apple,call,cell phone,cellphone,device,iphone,number,screen,telephone)' ),
				array( 'fas fa-mouse' => 'Mouse (click,computer,cursor,input,peripheral)' ),
				array( 'fas fa-plug' => 'Plug (connect,electric,online,power)' ),
				array( 'fas fa-power-off' => 'Power Off (cancel,computer,on,reboot,restart)' ),
				array( 'fas fa-print' => 'print (business,copy,document,office,paper)' ),
				array( 'fas fa-satellite' => 'Satellite (communications,hardware,orbit,space)' ),
				array( 'fas fa-satellite-dish' => 'Satellite Dish (SETI,communications,hardware,receiver,saucer,signal)' ),
				array( 'fas fa-save' => 'Save (disk,download,floppy,floppy-o)' ),
				array( 'far fa-save' => 'Save (disk,download,floppy,floppy-o)' ),
				array( 'fas fa-sd-card' => 'Sd Card (image,memory,photo,save)' ),
				array( 'fas fa-server' => 'Server (computer,cpu,database,hardware,network)' ),
				array( 'fas fa-sim-card' => 'SIM Card (hard drive,hardware,portable,storage,technology,tiny)' ),
				array( 'fas fa-stream' => 'Stream (flow,list,timeline)' ),
				array( 'fas fa-tablet' => 'tablet (apple,device,ipad,kindle,screen)' ),
				array( 'fas fa-tablet-alt' => 'Alternate Tablet (apple,device,ipad,kindle,screen)' ),
				array( 'fas fa-tv' => 'Television (computer,display,monitor,television)' ),
				array( 'fas fa-upload' => 'Upload (hard drive,import,publish)' ),
			),
			'Construction'        => array(
				array( 'fas fa-brush' => 'Brush (art,bristles,color,handle,paint)' ),
				array( 'fas fa-drafting-compass' => 'Drafting Compass (design,map,mechanical drawing,plot,plotting)' ),
				array( 'fas fa-dumpster' => 'Dumpster (alley,bin,commercial,trash,waste)' ),
				array( 'fas fa-hammer' => 'Hammer (admin,fix,repair,settings,tool)' ),
				array( 'fas fa-hard-hat' => 'Hard Hat (construction,hardhat,helmet,safety)' ),
				array( 'fas fa-paint-roller' => 'Paint Roller (acrylic,art,brush,color,fill,paint,pigment,watercolor)' ),
				array( 'fas fa-pencil-alt' => 'Alternate Pencil (design,edit,pencil,update,write)' ),
				array( 'fas fa-pencil-ruler' => 'Pencil Ruler (design,draft,draw,pencil)' ),
				array( 'fas fa-ruler' => 'Ruler (design,draft,length,measure,planning)' ),
				array( 'fas fa-ruler-combined' => 'Ruler Combined (design,draft,length,measure,planning)' ),
				array( 'fas fa-ruler-horizontal' => 'Ruler Horizontal (design,draft,length,measure,planning)' ),
				array( 'fas fa-ruler-vertical' => 'Ruler Vertical (design,draft,length,measure,planning)' ),
				array( 'fas fa-screwdriver' => 'Screwdriver (admin,fix,mechanic,repair,settings,tool)' ),
				array( 'fas fa-toolbox' => 'Toolbox (admin,container,fix,repair,settings,tools)' ),
				array( 'fas fa-tools' => 'Tools (admin,fix,repair,screwdriver,settings,tools,wrench)' ),
				array( 'fas fa-truck-pickup' => 'Truck Side (cargo,vehicle)' ),
				array( 'fas fa-wrench' => 'Wrench (construction,fix,mechanic,plumbing,settings,spanner,tool,update)' ),
			),
			'Currency'            => array(
				array( 'fab fa-bitcoin' => 'Bitcoin' ),
				array( 'fab fa-btc' => 'BTC' ),
				array( 'fas fa-dollar-sign' => 'Dollar Sign ($,cost,dollar-sign,money,price,usd)' ),
				array( 'fab fa-ethereum' => 'Ethereum' ),
				array( 'fas fa-euro-sign' => 'Euro Sign (currency,dollar,exchange,money)' ),
				array( 'fab fa-gg' => 'GG Currency' ),
				array( 'fab fa-gg-circle' => 'GG Currency Circle' ),
				array( 'fas fa-hryvnia' => 'Hryvnia (currency,money,ukraine,ukrainian)' ),
				array( 'fas fa-lira-sign' => 'Turkish Lira Sign (currency,money,try,turkish)' ),
				array( 'fas fa-money-bill' => 'Money Bill (buy,cash,checkout,money,payment,price,purchase)' ),
				array( 'fas fa-money-bill-alt' => 'Alternate Money Bill (buy,cash,checkout,money,payment,price,purchase)' ),
				array( 'far fa-money-bill-alt' => 'Alternate Money Bill (buy,cash,checkout,money,payment,price,purchase)' ),
				array( 'fas fa-money-bill-wave' => 'Wavy Money Bill (buy,cash,checkout,money,payment,price,purchase)' ),
				array( 'fas fa-money-bill-wave-alt' => 'Alternate Wavy Money Bill (buy,cash,checkout,money,payment,price,purchase)' ),
				array( 'fas fa-money-check' => 'Money Check (bank check,buy,checkout,cheque,money,payment,price,purchase)' ),
				array( 'fas fa-money-check-alt' => 'Alternate Money Check (bank check,buy,checkout,cheque,money,payment,price,purchase)' ),
				array( 'fas fa-pound-sign' => 'Pound Sign (currency,gbp,money)' ),
				array( 'fas fa-ruble-sign' => 'Ruble Sign (currency,money,rub)' ),
				array( 'fas fa-rupee-sign' => 'Indian Rupee Sign (currency,indian,inr,money)' ),
				array( 'fas fa-shekel-sign' => 'Shekel Sign (currency,ils,money)' ),
				array( 'fas fa-tenge' => 'Tenge (currency,kazakhstan,money,price)' ),
				array( 'fas fa-won-sign' => 'Won Sign (currency,krw,money)' ),
				array( 'fas fa-yen-sign' => 'Yen Sign (currency,jpy,money)' ),
			),
			'Date & Time'         => array(
				array( 'fas fa-bell' => 'bell (alarm,alert,chime,notification,reminder)' ),
				array( 'far fa-bell' => 'bell (alarm,alert,chime,notification,reminder)' ),
				array( 'fas fa-bell-slash' => 'Bell Slash (alert,cancel,disabled,notification,off,reminder)' ),
				array( 'far fa-bell-slash' => 'Bell Slash (alert,cancel,disabled,notification,off,reminder)' ),
				array( 'fas fa-calendar' => 'Calendar (calendar-o,date,event,schedule,time,when)' ),
				array( 'far fa-calendar' => 'Calendar (calendar-o,date,event,schedule,time,when)' ),
				array( 'fas fa-calendar-alt' => 'Alternate Calendar (calendar,date,event,schedule,time,when)' ),
				array( 'far fa-calendar-alt' => 'Alternate Calendar (calendar,date,event,schedule,time,when)' ),
				array( 'fas fa-calendar-check' => 'Calendar Check (accept,agree,appointment,confirm,correct,date,done,event,ok,schedule,select,success,tick,time,todo,when)' ),
				array( 'far fa-calendar-check' => 'Calendar Check (accept,agree,appointment,confirm,correct,date,done,event,ok,schedule,select,success,tick,time,todo,when)' ),
				array( 'fas fa-calendar-minus' => 'Calendar Minus (calendar,date,delete,event,negative,remove,schedule,time,when)' ),
				array( 'far fa-calendar-minus' => 'Calendar Minus (calendar,date,delete,event,negative,remove,schedule,time,when)' ),
				array( 'fas fa-calendar-plus' => 'Calendar Plus (add,calendar,create,date,event,new,positive,schedule,time,when)' ),
				array( 'far fa-calendar-plus' => 'Calendar Plus (add,calendar,create,date,event,new,positive,schedule,time,when)' ),
				array( 'fas fa-calendar-times' => 'Calendar Times (archive,calendar,date,delete,event,remove,schedule,time,when,x)' ),
				array( 'far fa-calendar-times' => 'Calendar Times (archive,calendar,date,delete,event,remove,schedule,time,when,x)' ),
				array( 'fas fa-clock' => 'Clock (date,late,schedule,time,timer,timestamp,watch)' ),
				array( 'far fa-clock' => 'Clock (date,late,schedule,time,timer,timestamp,watch)' ),
				array( 'fas fa-hourglass' => 'Hourglass (hour,minute,sand,stopwatch,time)' ),
				array( 'far fa-hourglass' => 'Hourglass (hour,minute,sand,stopwatch,time)' ),
				array( 'fas fa-hourglass-end' => 'Hourglass End (hour,minute,sand,stopwatch,time)' ),
				array( 'fas fa-hourglass-half' => 'Hourglass Half (hour,minute,sand,stopwatch,time)' ),
				array( 'fas fa-hourglass-start' => 'Hourglass Start (hour,minute,sand,stopwatch,time)' ),
				array( 'fas fa-stopwatch' => 'Stopwatch (clock,reminder,time)' ),
			),
			'Design'              => array(
				array( 'fas fa-adjust' => 'adjust (contrast,dark,light,saturation)' ),
				array( 'fas fa-bezier-curve' => 'Bezier Curve (curves,illustrator,lines,path,vector)' ),
				array( 'fas fa-brush' => 'Brush (art,bristles,color,handle,paint)' ),
				array( 'fas fa-clone' => 'Clone (arrange,copy,duplicate,paste)' ),
				array( 'far fa-clone' => 'Clone (arrange,copy,duplicate,paste)' ),
				array( 'fas fa-copy' => 'Copy (clone,duplicate,file,files-o,paper,paste)' ),
				array( 'far fa-copy' => 'Copy (clone,duplicate,file,files-o,paper,paste)' ),
				array( 'fas fa-crop' => 'crop (design,frame,mask,resize,shrink)' ),
				array( 'fas fa-crop-alt' => 'Alternate Crop (design,frame,mask,resize,shrink)' ),
				array( 'fas fa-crosshairs' => 'Crosshairs (aim,bullseye,gpd,picker,position)' ),
				array( 'fas fa-cut' => 'Cut (clip,scissors,snip)' ),
				array( 'fas fa-drafting-compass' => 'Drafting Compass (design,map,mechanical drawing,plot,plotting)' ),
				array( 'fas fa-draw-polygon' => 'Draw Polygon (anchors,lines,object,render,shape)' ),
				array( 'fas fa-edit' => 'Edit (edit,pen,pencil,update,write)' ),
				array( 'far fa-edit' => 'Edit (edit,pen,pencil,update,write)' ),
				array( 'fas fa-eraser' => 'eraser (art,delete,remove,rubber)' ),
				array( 'fas fa-eye' => 'Eye (look,optic,see,seen,show,sight,views,visible)' ),
				array( 'far fa-eye' => 'Eye (look,optic,see,seen,show,sight,views,visible)' ),
				array( 'fas fa-eye-dropper' => 'Eye Dropper (beaker,clone,color,copy,eyedropper,pipette)' ),
				array( 'fas fa-eye-slash' => 'Eye Slash (blind,hide,show,toggle,unseen,views,visible,visiblity)' ),
				array( 'far fa-eye-slash' => 'Eye Slash (blind,hide,show,toggle,unseen,views,visible,visiblity)' ),
				array( 'fas fa-fill' => 'Fill (bucket,color,paint,paint bucket)' ),
				array( 'fas fa-fill-drip' => 'Fill Drip (bucket,color,drop,paint,paint bucket,spill)' ),
				array( 'fas fa-highlighter' => 'Highlighter (edit,marker,sharpie,update,write)' ),
				array( 'fas fa-icons' => 'Icons (bolt,emoji,heart,image,music,photo,symbols)' ),
				array( 'fas fa-layer-group' => 'Layer Group (arrange,develop,layers,map,stack)' ),
				array( 'fas fa-magic' => 'magic (autocomplete,automatic,mage,magic,spell,wand,witch,wizard)' ),
				array( 'fas fa-marker' => 'Marker (design,edit,sharpie,update,write)' ),
				array( 'fas fa-object-group' => 'Object Group (combine,copy,design,merge,select)' ),
				array( 'far fa-object-group' => 'Object Group (combine,copy,design,merge,select)' ),
				array( 'fas fa-object-ungroup' => 'Object Ungroup (copy,design,merge,select,separate)' ),
				array( 'far fa-object-ungroup' => 'Object Ungroup (copy,design,merge,select,separate)' ),
				array( 'fas fa-paint-brush' => 'Paint Brush (acrylic,art,brush,color,fill,paint,pigment,watercolor)' ),
				array( 'fas fa-paint-roller' => 'Paint Roller (acrylic,art,brush,color,fill,paint,pigment,watercolor)' ),
				array( 'fas fa-palette' => 'Palette (acrylic,art,brush,color,fill,paint,pigment,watercolor)' ),
				array( 'fas fa-paste' => 'Paste (clipboard,copy,document,paper)' ),
				array( 'fas fa-pen' => 'Pen (design,edit,update,write)' ),
				array( 'fas fa-pen-alt' => 'Alternate Pen (design,edit,update,write)' ),
				array( 'fas fa-pen-fancy' => 'Pen Fancy (design,edit,fountain pen,update,write)' ),
				array( 'fas fa-pen-nib' => 'Pen Nib (design,edit,fountain pen,update,write)' ),
				array( 'fas fa-pencil-alt' => 'Alternate Pencil (design,edit,pencil,update,write)' ),
				array( 'fas fa-pencil-ruler' => 'Pencil Ruler (design,draft,draw,pencil)' ),
				array( 'fas fa-ruler-combined' => 'Ruler Combined (design,draft,length,measure,planning)' ),
				array( 'fas fa-ruler-horizontal' => 'Ruler Horizontal (design,draft,length,measure,planning)' ),
				array( 'fas fa-ruler-vertical' => 'Ruler Vertical (design,draft,length,measure,planning)' ),
				array( 'fas fa-save' => 'Save (disk,download,floppy,floppy-o)' ),
				array( 'far fa-save' => 'Save (disk,download,floppy,floppy-o)' ),
				array( 'fas fa-splotch' => 'Splotch (Ink,blob,blotch,glob,stain)' ),
				array( 'fas fa-spray-can' => 'Spray Can (Paint,aerosol,design,graffiti,tag)' ),
				array( 'fas fa-stamp' => 'Stamp (art,certificate,imprint,rubber,seal)' ),
				array( 'fas fa-swatchbook' => 'Swatchbook (Pantone,color,design,hue,palette)' ),
				array( 'fas fa-tint' => 'tint (color,drop,droplet,raindrop,waterdrop)' ),
				array( 'fas fa-tint-slash' => 'Tint Slash (color,drop,droplet,raindrop,waterdrop)' ),
				array( 'fas fa-vector-square' => 'Vector Square (anchors,lines,object,render,shape)' ),
			),
			'Editors'             => array(
				array( 'fas fa-align-center' => 'align-center (format,middle,paragraph,text)' ),
				array( 'fas fa-align-justify' => 'align-justify (format,paragraph,text)' ),
				array( 'fas fa-align-left' => 'align-left (format,paragraph,text)' ),
				array( 'fas fa-align-right' => 'align-right (format,paragraph,text)' ),
				array( 'fas fa-bold' => 'bold (emphasis,format,text)' ),
				array( 'fas fa-border-all' => 'Border All (cell,grid,outline,stroke,table)' ),
				array( 'fas fa-border-none' => 'Border None (cell,grid,outline,stroke,table)' ),
				array( 'fas fa-border-style' => 'Border Style' ),
				array( 'fas fa-clipboard' => 'Clipboard (copy,notes,paste,record)' ),
				array( 'far fa-clipboard' => 'Clipboard (copy,notes,paste,record)' ),
				array( 'fas fa-clone' => 'Clone (arrange,copy,duplicate,paste)' ),
				array( 'far fa-clone' => 'Clone (arrange,copy,duplicate,paste)' ),
				array( 'fas fa-columns' => 'Columns (browser,dashboard,organize,panes,split)' ),
				array( 'fas fa-copy' => 'Copy (clone,duplicate,file,files-o,paper,paste)' ),
				array( 'far fa-copy' => 'Copy (clone,duplicate,file,files-o,paper,paste)' ),
				array( 'fas fa-cut' => 'Cut (clip,scissors,snip)' ),
				array( 'fas fa-edit' => 'Edit (edit,pen,pencil,update,write)' ),
				array( 'far fa-edit' => 'Edit (edit,pen,pencil,update,write)' ),
				array( 'fas fa-eraser' => 'eraser (art,delete,remove,rubber)' ),
				array( 'fas fa-file' => 'File (document,new,page,pdf,resume)' ),
				array( 'far fa-file' => 'File (document,new,page,pdf,resume)' ),
				array( 'fas fa-file-alt' => 'Alternate File (document,file-text,invoice,new,page,pdf)' ),
				array( 'far fa-file-alt' => 'Alternate File (document,file-text,invoice,new,page,pdf)' ),
				array( 'fas fa-font' => 'font (alphabet,glyph,text,type,typeface)' ),
				array( 'fas fa-glasses' => 'Glasses (hipster,nerd,reading,sight,spectacles,vision)' ),
				array( 'fas fa-heading' => 'heading (format,header,text,title)' ),
				array( 'fas fa-highlighter' => 'Highlighter (edit,marker,sharpie,update,write)' ),
				array( 'fas fa-i-cursor' => 'I Beam Cursor (editing,i-beam,type,writing)' ),
				array( 'fas fa-icons' => 'Icons (bolt,emoji,heart,image,music,photo,symbols)' ),
				array( 'fas fa-indent' => 'Indent (align,justify,paragraph,tab)' ),
				array( 'fas fa-italic' => 'italic (edit,emphasis,font,format,text,type)' ),
				array( 'fas fa-link' => 'Link (attach,attachment,chain,connect)' ),
				array( 'fas fa-list' => 'List (checklist,completed,done,finished,ol,todo,ul)' ),
				array( 'fas fa-list-alt' => 'Alternate List (checklist,completed,done,finished,ol,todo,ul)' ),
				array( 'far fa-list-alt' => 'Alternate List (checklist,completed,done,finished,ol,todo,ul)' ),
				array( 'fas fa-list-ol' => 'list-ol (checklist,completed,done,finished,numbers,ol,todo,ul)' ),
				array( 'fas fa-list-ul' => 'list-ul (checklist,completed,done,finished,ol,todo,ul)' ),
				array( 'fas fa-marker' => 'Marker (design,edit,sharpie,update,write)' ),
				array( 'fas fa-outdent' => 'Outdent (align,justify,paragraph,tab)' ),
				array( 'fas fa-paper-plane' => 'Paper Plane (air,float,fold,mail,paper,send)' ),
				array( 'far fa-paper-plane' => 'Paper Plane (air,float,fold,mail,paper,send)' ),
				array( 'fas fa-paperclip' => 'Paperclip (attach,attachment,connect,link)' ),
				array( 'fas fa-paragraph' => 'paragraph (edit,format,text,writing)' ),
				array( 'fas fa-paste' => 'Paste (clipboard,copy,document,paper)' ),
				array( 'fas fa-pen' => 'Pen (design,edit,update,write)' ),
				array( 'fas fa-pen-alt' => 'Alternate Pen (design,edit,update,write)' ),
				array( 'fas fa-pen-fancy' => 'Pen Fancy (design,edit,fountain pen,update,write)' ),
				array( 'fas fa-pen-nib' => 'Pen Nib (design,edit,fountain pen,update,write)' ),
				array( 'fas fa-pencil-alt' => 'Alternate Pencil (design,edit,pencil,update,write)' ),
				array( 'fas fa-print' => 'print (business,copy,document,office,paper)' ),
				array( 'fas fa-quote-left' => 'quote-left (mention,note,phrase,text,type)' ),
				array( 'fas fa-quote-right' => 'quote-right (mention,note,phrase,text,type)' ),
				array( 'fas fa-redo' => 'Redo (forward,refresh,reload,repeat)' ),
				array( 'fas fa-redo-alt' => 'Alternate Redo (forward,refresh,reload,repeat)' ),
				array( 'fas fa-remove-format' => 'Remove Format (cancel,font,format,remove,style,text)' ),
				array( 'fas fa-reply' => 'Reply (mail,message,respond)' ),
				array( 'fas fa-reply-all' => 'reply-all (mail,message,respond)' ),
				array( 'fas fa-screwdriver' => 'Screwdriver (admin,fix,mechanic,repair,settings,tool)' ),
				array( 'fas fa-share' => 'Share (forward,save,send,social)' ),
				array( 'fas fa-spell-check' => 'Spell Check (dictionary,edit,editor,grammar,text)' ),
				array( 'fas fa-strikethrough' => 'Strikethrough (cancel,edit,font,format,text,type)' ),
				array( 'fas fa-subscript' => 'subscript (edit,font,format,text,type)' ),
				array( 'fas fa-superscript' => 'superscript (edit,exponential,font,format,text,type)' ),
				array( 'fas fa-sync' => 'Sync (exchange,refresh,reload,rotate,swap)' ),
				array( 'fas fa-sync-alt' => 'Alternate Sync (exchange,refresh,reload,rotate,swap)' ),
				array( 'fas fa-table' => 'table (data,excel,spreadsheet)' ),
				array( 'fas fa-tasks' => 'Tasks (checklist,downloading,downloads,loading,progress,project management,settings,to do)' ),
				array( 'fas fa-text-height' => 'text-height (edit,font,format,text,type)' ),
				array( 'fas fa-text-width' => 'Text Width (edit,font,format,text,type)' ),
				array( 'fas fa-th' => 'th (blocks,boxes,grid,squares)' ),
				array( 'fas fa-th-large' => 'th-large (blocks,boxes,grid,squares)' ),
				array( 'fas fa-th-list' => 'th-list (checklist,completed,done,finished,ol,todo,ul)' ),
				array( 'fas fa-tools' => 'Tools (admin,fix,repair,screwdriver,settings,tools,wrench)' ),
				array( 'fas fa-trash' => 'Trash (delete,garbage,hide,remove)' ),
				array( 'fas fa-trash-alt' => 'Alternate Trash (delete,garbage,hide,remove,trash-o)' ),
				array( 'far fa-trash-alt' => 'Alternate Trash (delete,garbage,hide,remove,trash-o)' ),
				array( 'fas fa-trash-restore' => 'Trash Restore (back,control z,oops,undo)' ),
				array( 'fas fa-trash-restore-alt' => 'Alternative Trash Restore (back,control z,oops,undo)' ),
				array( 'fas fa-underline' => 'Underline (edit,emphasis,format,text,writing)' ),
				array( 'fas fa-undo' => 'Undo (back,control z,exchange,oops,return,rotate,swap)' ),
				array( 'fas fa-undo-alt' => 'Alternate Undo (back,control z,exchange,oops,return,swap)' ),
				array( 'fas fa-unlink' => 'unlink (attachment,chain,chain-broken,remove)' ),
				array( 'fas fa-wrench' => 'Wrench (construction,fix,mechanic,plumbing,settings,spanner,tool,update)' ),
			),
			'Education'           => array(
				array( 'fas fa-apple-alt' => 'Fruit Apple (fall,fruit,fuji,macintosh,orchard,seasonal,vegan)' ),
				array( 'fas fa-atom' => 'Atom (atheism,chemistry,ion,nuclear,science)' ),
				array( 'fas fa-award' => 'Award (honor,praise,prize,recognition,ribbon,trophy)' ),
				array( 'fas fa-bell' => 'bell (alarm,alert,chime,notification,reminder)' ),
				array( 'far fa-bell' => 'bell (alarm,alert,chime,notification,reminder)' ),
				array( 'fas fa-bell-slash' => 'Bell Slash (alert,cancel,disabled,notification,off,reminder)' ),
				array( 'far fa-bell-slash' => 'Bell Slash (alert,cancel,disabled,notification,off,reminder)' ),
				array( 'fas fa-book-open' => 'Book Open (flyer,library,notebook,open book,pamphlet,reading)' ),
				array( 'fas fa-book-reader' => 'Book Reader (flyer,library,notebook,open book,pamphlet,reading)' ),
				array( 'fas fa-chalkboard' => 'Chalkboard (blackboard,learning,school,teaching,whiteboard,writing)' ),
				array( 'fas fa-chalkboard-teacher' => 'Chalkboard Teacher (blackboard,instructor,learning,professor,school,whiteboard,writing)' ),
				array( 'fas fa-graduation-cap' => 'Graduation Cap (ceremony,college,graduate,learning,school,student)' ),
				array( 'fas fa-laptop-code' => 'Laptop Code (computer,cpu,dell,demo,develop,device,mac,macbook,machine,pc)' ),
				array( 'fas fa-microscope' => 'Microscope (electron,lens,optics,science,shrink)' ),
				array( 'fas fa-music' => 'Music (lyrics,melody,note,sing,sound)' ),
				array( 'fas fa-school' => 'School (building,education,learn,student,teacher)' ),
				array( 'fas fa-shapes' => 'Shapes (blocks,build,circle,square,triangle)' ),
				array( 'fas fa-theater-masks' => 'Theater Masks (comedy,perform,theatre,tragedy)' ),
				array( 'fas fa-user-graduate' => 'User Graduate (cap,clothing,commencement,gown,graduation,person,student)' ),
			),
			'Emoji'               => array(
				array( 'fas fa-angry' => 'Angry Face (disapprove,emoticon,face,mad,upset)' ),
				array( 'far fa-angry' => 'Angry Face (disapprove,emoticon,face,mad,upset)' ),
				array( 'fas fa-dizzy' => 'Dizzy Face (dazed,dead,disapprove,emoticon,face)' ),
				array( 'far fa-dizzy' => 'Dizzy Face (dazed,dead,disapprove,emoticon,face)' ),
				array( 'fas fa-flushed' => 'Flushed Face (embarrassed,emoticon,face)' ),
				array( 'far fa-flushed' => 'Flushed Face (embarrassed,emoticon,face)' ),
				array( 'fas fa-frown' => 'Frowning Face (disapprove,emoticon,face,rating,sad)' ),
				array( 'far fa-frown' => 'Frowning Face (disapprove,emoticon,face,rating,sad)' ),
				array( 'fas fa-frown-open' => 'Frowning Face With Open Mouth (disapprove,emoticon,face,rating,sad)' ),
				array( 'far fa-frown-open' => 'Frowning Face With Open Mouth (disapprove,emoticon,face,rating,sad)' ),
				array( 'fas fa-grimace' => 'Grimacing Face (cringe,emoticon,face,teeth)' ),
				array( 'far fa-grimace' => 'Grimacing Face (cringe,emoticon,face,teeth)' ),
				array( 'fas fa-grin' => 'Grinning Face (emoticon,face,laugh,smile)' ),
				array( 'far fa-grin' => 'Grinning Face (emoticon,face,laugh,smile)' ),
				array( 'fas fa-grin-alt' => 'Alternate Grinning Face (emoticon,face,laugh,smile)' ),
				array( 'far fa-grin-alt' => 'Alternate Grinning Face (emoticon,face,laugh,smile)' ),
				array( 'fas fa-grin-beam' => 'Grinning Face With Smiling Eyes (emoticon,face,laugh,smile)' ),
				array( 'far fa-grin-beam' => 'Grinning Face With Smiling Eyes (emoticon,face,laugh,smile)' ),
				array( 'fas fa-grin-beam-sweat' => 'Grinning Face With Sweat (embarass,emoticon,face,smile)' ),
				array( 'far fa-grin-beam-sweat' => 'Grinning Face With Sweat (embarass,emoticon,face,smile)' ),
				array( 'fas fa-grin-hearts' => 'Smiling Face With Heart-Eyes (emoticon,face,love,smile)' ),
				array( 'far fa-grin-hearts' => 'Smiling Face With Heart-Eyes (emoticon,face,love,smile)' ),
				array( 'fas fa-grin-squint' => 'Grinning Squinting Face (emoticon,face,laugh,smile)' ),
				array( 'far fa-grin-squint' => 'Grinning Squinting Face (emoticon,face,laugh,smile)' ),
				array( 'fas fa-grin-squint-tears' => 'Rolling on the Floor Laughing (emoticon,face,happy,smile)' ),
				array( 'far fa-grin-squint-tears' => 'Rolling on the Floor Laughing (emoticon,face,happy,smile)' ),
				array( 'fas fa-grin-stars' => 'Star-Struck (emoticon,face,star-struck)' ),
				array( 'far fa-grin-stars' => 'Star-Struck (emoticon,face,star-struck)' ),
				array( 'fas fa-grin-tears' => 'Face With Tears of Joy (LOL,emoticon,face)' ),
				array( 'far fa-grin-tears' => 'Face With Tears of Joy (LOL,emoticon,face)' ),
				array( 'fas fa-grin-tongue' => 'Face With Tongue (LOL,emoticon,face)' ),
				array( 'far fa-grin-tongue' => 'Face With Tongue (LOL,emoticon,face)' ),
				array( 'fas fa-grin-tongue-squint' => 'Squinting Face With Tongue (LOL,emoticon,face)' ),
				array( 'far fa-grin-tongue-squint' => 'Squinting Face With Tongue (LOL,emoticon,face)' ),
				array( 'fas fa-grin-tongue-wink' => 'Winking Face With Tongue (LOL,emoticon,face)' ),
				array( 'far fa-grin-tongue-wink' => 'Winking Face With Tongue (LOL,emoticon,face)' ),
				array( 'fas fa-grin-wink' => 'Grinning Winking Face (emoticon,face,flirt,laugh,smile)' ),
				array( 'far fa-grin-wink' => 'Grinning Winking Face (emoticon,face,flirt,laugh,smile)' ),
				array( 'fas fa-kiss' => 'Kissing Face (beso,emoticon,face,love,smooch)' ),
				array( 'far fa-kiss' => 'Kissing Face (beso,emoticon,face,love,smooch)' ),
				array( 'fas fa-kiss-beam' => 'Kissing Face With Smiling Eyes (beso,emoticon,face,love,smooch)' ),
				array( 'far fa-kiss-beam' => 'Kissing Face With Smiling Eyes (beso,emoticon,face,love,smooch)' ),
				array( 'fas fa-kiss-wink-heart' => 'Face Blowing a Kiss (beso,emoticon,face,love,smooch)' ),
				array( 'far fa-kiss-wink-heart' => 'Face Blowing a Kiss (beso,emoticon,face,love,smooch)' ),
				array( 'fas fa-laugh' => 'Grinning Face With Big Eyes (LOL,emoticon,face,laugh,smile)' ),
				array( 'far fa-laugh' => 'Grinning Face With Big Eyes (LOL,emoticon,face,laugh,smile)' ),
				array( 'fas fa-laugh-beam' => 'Laugh Face with Beaming Eyes (LOL,emoticon,face,happy,smile)' ),
				array( 'far fa-laugh-beam' => 'Laugh Face with Beaming Eyes (LOL,emoticon,face,happy,smile)' ),
				array( 'fas fa-laugh-squint' => 'Laughing Squinting Face (LOL,emoticon,face,happy,smile)' ),
				array( 'far fa-laugh-squint' => 'Laughing Squinting Face (LOL,emoticon,face,happy,smile)' ),
				array( 'fas fa-laugh-wink' => 'Laughing Winking Face (LOL,emoticon,face,happy,smile)' ),
				array( 'far fa-laugh-wink' => 'Laughing Winking Face (LOL,emoticon,face,happy,smile)' ),
				array( 'fas fa-meh' => 'Neutral Face (emoticon,face,neutral,rating)' ),
				array( 'far fa-meh' => 'Neutral Face (emoticon,face,neutral,rating)' ),
				array( 'fas fa-meh-blank' => 'Face Without Mouth (emoticon,face,neutral,rating)' ),
				array( 'far fa-meh-blank' => 'Face Without Mouth (emoticon,face,neutral,rating)' ),
				array( 'fas fa-meh-rolling-eyes' => 'Face With Rolling Eyes (emoticon,face,neutral,rating)' ),
				array( 'far fa-meh-rolling-eyes' => 'Face With Rolling Eyes (emoticon,face,neutral,rating)' ),
				array( 'fas fa-sad-cry' => 'Crying Face (emoticon,face,tear,tears)' ),
				array( 'far fa-sad-cry' => 'Crying Face (emoticon,face,tear,tears)' ),
				array( 'fas fa-sad-tear' => 'Loudly Crying Face (emoticon,face,tear,tears)' ),
				array( 'far fa-sad-tear' => 'Loudly Crying Face (emoticon,face,tear,tears)' ),
				array( 'fas fa-smile' => 'Smiling Face (approve,emoticon,face,happy,rating,satisfied)' ),
				array( 'far fa-smile' => 'Smiling Face (approve,emoticon,face,happy,rating,satisfied)' ),
				array( 'fas fa-smile-beam' => 'Beaming Face With Smiling Eyes (emoticon,face,happy,positive)' ),
				array( 'far fa-smile-beam' => 'Beaming Face With Smiling Eyes (emoticon,face,happy,positive)' ),
				array( 'fas fa-smile-wink' => 'Winking Face (emoticon,face,happy,hint,joke)' ),
				array( 'far fa-smile-wink' => 'Winking Face (emoticon,face,happy,hint,joke)' ),
				array( 'fas fa-surprise' => 'Hushed Face (emoticon,face,shocked)' ),
				array( 'far fa-surprise' => 'Hushed Face (emoticon,face,shocked)' ),
				array( 'fas fa-tired' => 'Tired Face (angry,emoticon,face,grumpy,upset)' ),
				array( 'far fa-tired' => 'Tired Face (angry,emoticon,face,grumpy,upset)' ),
			),
			'Energy'              => array(
				array( 'fas fa-atom' => 'Atom (atheism,chemistry,ion,nuclear,science)' ),
				array( 'fas fa-battery-empty' => 'Battery Empty (charge,dead,power,status)' ),
				array( 'fas fa-battery-full' => 'Battery Full (charge,power,status)' ),
				array( 'fas fa-battery-half' => 'Battery 1/2 Full (charge,power,status)' ),
				array( 'fas fa-battery-quarter' => 'Battery 1/4 Full (charge,low,power,status)' ),
				array( 'fas fa-battery-three-quarters' => 'Battery 3/4 Full (charge,power,status)' ),
				array( 'fas fa-broadcast-tower' => 'Broadcast Tower (airwaves,antenna,radio,reception,waves)' ),
				array( 'fas fa-burn' => 'Burn (caliente,energy,fire,flame,gas,heat,hot)' ),
				array( 'fas fa-charging-station' => 'Charging Station (electric,ev,tesla,vehicle)' ),
				array( 'fas fa-fire' => 'fire (burn,caliente,flame,heat,hot,popular)' ),
				array( 'fas fa-fire-alt' => 'Alternate Fire (burn,caliente,flame,heat,hot,popular)' ),
				array( 'fas fa-gas-pump' => 'Gas Pump (car,fuel,gasoline,petrol)' ),
				array( 'fas fa-industry' => 'Industry (building,factory,industrial,manufacturing,mill,warehouse)' ),
				array( 'fas fa-leaf' => 'leaf (eco,flora,nature,plant,vegan)' ),
				array( 'fas fa-lightbulb' => 'Lightbulb (energy,idea,inspiration,light)' ),
				array( 'far fa-lightbulb' => 'Lightbulb (energy,idea,inspiration,light)' ),
				array( 'fas fa-plug' => 'Plug (connect,electric,online,power)' ),
				array( 'fas fa-poop' => 'Poop (crap,poop,shit,smile,turd)' ),
				array( 'fas fa-power-off' => 'Power Off (cancel,computer,on,reboot,restart)' ),
				array( 'fas fa-radiation' => 'Radiation (danger,dangerous,deadly,hazard,nuclear,radioactive,warning)' ),
				array( 'fas fa-radiation-alt' => 'Alternate Radiation (danger,dangerous,deadly,hazard,nuclear,radioactive,warning)' ),
				array( 'fas fa-seedling' => 'Seedling (flora,grow,plant,vegan)' ),
				array( 'fas fa-solar-panel' => 'Solar Panel (clean,eco-friendly,energy,green,sun)' ),
				array( 'fas fa-sun' => 'Sun (brighten,contrast,day,lighter,sol,solar,star,weather)' ),
				array( 'far fa-sun' => 'Sun (brighten,contrast,day,lighter,sol,solar,star,weather)' ),
				array( 'fas fa-water' => 'Water (lake,liquid,ocean,sea,swim,wet)' ),
				array( 'fas fa-wind' => 'Wind (air,blow,breeze,fall,seasonal,weather)' ),
			),
			'Files'               => array(
				array( 'fas fa-archive' => 'Archive (box,package,save,storage)' ),
				array( 'fas fa-clone' => 'Clone (arrange,copy,duplicate,paste)' ),
				array( 'far fa-clone' => 'Clone (arrange,copy,duplicate,paste)' ),
				array( 'fas fa-copy' => 'Copy (clone,duplicate,file,files-o,paper,paste)' ),
				array( 'far fa-copy' => 'Copy (clone,duplicate,file,files-o,paper,paste)' ),
				array( 'fas fa-cut' => 'Cut (clip,scissors,snip)' ),
				array( 'fas fa-file' => 'File (document,new,page,pdf,resume)' ),
				array( 'far fa-file' => 'File (document,new,page,pdf,resume)' ),
				array( 'fas fa-file-alt' => 'Alternate File (document,file-text,invoice,new,page,pdf)' ),
				array( 'far fa-file-alt' => 'Alternate File (document,file-text,invoice,new,page,pdf)' ),
				array( 'fas fa-file-archive' => 'Archive File (.zip,bundle,compress,compression,download,zip)' ),
				array( 'far fa-file-archive' => 'Archive File (.zip,bundle,compress,compression,download,zip)' ),
				array( 'fas fa-file-audio' => 'Audio File (document,mp3,music,page,play,sound)' ),
				array( 'far fa-file-audio' => 'Audio File (document,mp3,music,page,play,sound)' ),
				array( 'fas fa-file-code' => 'Code File (css,development,document,html)' ),
				array( 'far fa-file-code' => 'Code File (css,development,document,html)' ),
				array( 'fas fa-file-excel' => 'Excel File (csv,document,numbers,spreadsheets,table)' ),
				array( 'far fa-file-excel' => 'Excel File (csv,document,numbers,spreadsheets,table)' ),
				array( 'fas fa-file-image' => 'Image File (document,image,jpg,photo,png)' ),
				array( 'far fa-file-image' => 'Image File (document,image,jpg,photo,png)' ),
				array( 'fas fa-file-pdf' => 'PDF File (acrobat,document,preview,save)' ),
				array( 'far fa-file-pdf' => 'PDF File (acrobat,document,preview,save)' ),
				array( 'fas fa-file-powerpoint' => 'Powerpoint File (display,document,keynote,presentation)' ),
				array( 'far fa-file-powerpoint' => 'Powerpoint File (display,document,keynote,presentation)' ),
				array( 'fas fa-file-video' => 'Video File (document,m4v,movie,mp4,play)' ),
				array( 'far fa-file-video' => 'Video File (document,m4v,movie,mp4,play)' ),
				array( 'fas fa-file-word' => 'Word File (document,edit,page,text,writing)' ),
				array( 'far fa-file-word' => 'Word File (document,edit,page,text,writing)' ),
				array( 'fas fa-folder' => 'Folder (archive,directory,document,file)' ),
				array( 'far fa-folder' => 'Folder (archive,directory,document,file)' ),
				array( 'fas fa-folder-open' => 'Folder Open (archive,directory,document,empty,file,new)' ),
				array( 'far fa-folder-open' => 'Folder Open (archive,directory,document,empty,file,new)' ),
				array( 'fas fa-paste' => 'Paste (clipboard,copy,document,paper)' ),
				array( 'fas fa-photo-video' => 'Photo Video (av,film,image,library,media)' ),
				array( 'fas fa-save' => 'Save (disk,download,floppy,floppy-o)' ),
				array( 'far fa-save' => 'Save (disk,download,floppy,floppy-o)' ),
				array( 'fas fa-sticky-note' => 'Sticky Note (message,note,paper,reminder,sticker)' ),
				array( 'far fa-sticky-note' => 'Sticky Note (message,note,paper,reminder,sticker)' ),
			),
			'Finance'             => array(
				array( 'fas fa-balance-scale' => 'Balance Scale (balanced,justice,legal,measure,weight)' ),
				array( 'fas fa-balance-scale-left' => 'Balance Scale (Left-Weighted) (justice,legal,measure,unbalanced,weight)' ),
				array( 'fas fa-balance-scale-right' => 'Balance Scale (Right-Weighted) (justice,legal,measure,unbalanced,weight)' ),
				array( 'fas fa-book' => 'book (diary,documentation,journal,library,read)' ),
				array( 'fas fa-cash-register' => 'Cash Register (buy,cha-ching,change,checkout,commerce,leaerboard,machine,pay,payment,purchase,store)' ),
				array( 'fas fa-chart-line' => 'Line Chart (activity,analytics,chart,dashboard,gain,graph,increase,line)' ),
				array( 'fas fa-chart-pie' => 'Pie Chart (analytics,chart,diagram,graph,pie)' ),
				array( 'fas fa-coins' => 'Coins (currency,dime,financial,gold,money,penny)' ),
				array( 'fas fa-comment-dollar' => 'Comment Dollar (bubble,chat,commenting,conversation,feedback,message,money,note,notification,pay,sms,speech,spend,texting,transfer)' ),
				array( 'fas fa-comments-dollar' => 'Comments Dollar (bubble,chat,commenting,conversation,feedback,message,money,note,notification,pay,sms,speech,spend,texting,transfer)' ),
				array( 'fas fa-credit-card' => 'Credit Card (buy,checkout,credit-card-alt,debit,money,payment,purchase)' ),
				array( 'far fa-credit-card' => 'Credit Card (buy,checkout,credit-card-alt,debit,money,payment,purchase)' ),
				array( 'fas fa-donate' => 'Donate (contribute,generosity,gift,give)' ),
				array( 'fas fa-file-invoice' => 'File Invoice (account,bill,charge,document,payment,receipt)' ),
				array( 'fas fa-file-invoice-dollar' => 'File Invoice with US Dollar ($,account,bill,charge,document,dollar-sign,money,payment,receipt,usd)' ),
				array( 'fas fa-hand-holding-usd' => 'Hand Holding US Dollar ($,carry,dollar sign,donation,giving,lift,money,price)' ),
				array( 'fas fa-landmark' => 'Landmark (building,historic,memorable,monument,politics)' ),
				array( 'fas fa-money-bill' => 'Money Bill (buy,cash,checkout,money,payment,price,purchase)' ),
				array( 'fas fa-money-bill-alt' => 'Alternate Money Bill (buy,cash,checkout,money,payment,price,purchase)' ),
				array( 'far fa-money-bill-alt' => 'Alternate Money Bill (buy,cash,checkout,money,payment,price,purchase)' ),
				array( 'fas fa-money-bill-wave' => 'Wavy Money Bill (buy,cash,checkout,money,payment,price,purchase)' ),
				array( 'fas fa-money-bill-wave-alt' => 'Alternate Wavy Money Bill (buy,cash,checkout,money,payment,price,purchase)' ),
				array( 'fas fa-money-check' => 'Money Check (bank check,buy,checkout,cheque,money,payment,price,purchase)' ),
				array( 'fas fa-money-check-alt' => 'Alternate Money Check (bank check,buy,checkout,cheque,money,payment,price,purchase)' ),
				array( 'fas fa-percentage' => 'Percentage (discount,fraction,proportion,rate,ratio)' ),
				array( 'fas fa-piggy-bank' => 'Piggy Bank (bank,save,savings)' ),
				array( 'fas fa-receipt' => 'Receipt (check,invoice,money,pay,table)' ),
				array( 'fas fa-stamp' => 'Stamp (art,certificate,imprint,rubber,seal)' ),
				array( 'fas fa-wallet' => 'Wallet (billfold,cash,currency,money)' ),
			),
			'Fitness'             => array(
				array( 'fas fa-bicycle' => 'Bicycle (bike,gears,pedal,transportation,vehicle)' ),
				array( 'fas fa-biking' => 'Biking (bicycle,bike,cycle,cycling,ride,wheel)' ),
				array( 'fas fa-burn' => 'Burn (caliente,energy,fire,flame,gas,heat,hot)' ),
				array( 'fas fa-fire-alt' => 'Alternate Fire (burn,caliente,flame,heat,hot,popular)' ),
				array( 'fas fa-heart' => 'Heart (favorite,like,love,relationship,valentine)' ),
				array( 'far fa-heart' => 'Heart (favorite,like,love,relationship,valentine)' ),
				array( 'fas fa-heartbeat' => 'Heartbeat (ekg,electrocardiogram,health,lifeline,vital signs)' ),
				array( 'fas fa-hiking' => 'Hiking (activity,backpack,fall,fitness,outdoors,person,seasonal,walking)' ),
				array( 'fas fa-running' => 'Running (exercise,health,jog,person,run,sport,sprint)' ),
				array( 'fas fa-shoe-prints' => 'Shoe Prints (feet,footprints,steps,walk)' ),
				array( 'fas fa-skating' => 'Skating (activity,figure skating,fitness,ice,person,winter)' ),
				array( 'fas fa-skiing' => 'Skiing (activity,downhill,fast,fitness,olympics,outdoors,person,seasonal,slalom)' ),
				array( 'fas fa-skiing-nordic' => 'Skiing Nordic (activity,cross country,fitness,outdoors,person,seasonal)' ),
				array( 'fas fa-snowboarding' => 'Snowboarding (activity,fitness,olympics,outdoors,person)' ),
				array( 'fas fa-spa' => 'Spa (flora,massage,mindfulness,plant,wellness)' ),
				array( 'fas fa-swimmer' => 'Swimmer (athlete,head,man,olympics,person,pool,water)' ),
				array( 'fas fa-walking' => 'Walking (exercise,health,pedometer,person,steps)' ),
			),
			'Food'                => array(
				array( 'fas fa-apple-alt' => 'Fruit Apple (fall,fruit,fuji,macintosh,orchard,seasonal,vegan)' ),
				array( 'fas fa-bacon' => 'Bacon (blt,breakfast,ham,lard,meat,pancetta,pork,rasher)' ),
				array( 'fas fa-bone' => 'Bone (calcium,dog,skeletal,skeleton,tibia)' ),
				array( 'fas fa-bread-slice' => 'Bread Slice (bake,bakery,baking,dough,flour,gluten,grain,sandwich,sourdough,toast,wheat,yeast)' ),
				array( 'fas fa-candy-cane' => 'Candy Cane (candy,christmas,holiday,mint,peppermint,striped,xmas)' ),
				array( 'fas fa-carrot' => 'Carrot (bugs bunny,orange,vegan,vegetable)' ),
				array( 'fas fa-cheese' => 'Cheese (cheddar,curd,gouda,melt,parmesan,sandwich,swiss,wedge)' ),
				array( 'fas fa-cloud-meatball' => 'Cloud with (a chance of) Meatball (FLDSMDFR,food,spaghetti,storm)' ),
				array( 'fas fa-cookie' => 'Cookie (baked good,chips,chocolate,eat,snack,sweet,treat)' ),
				array( 'fas fa-drumstick-bite' => 'Drumstick with Bite Taken Out (bone,chicken,leg,meat,poultry,turkey)' ),
				array( 'fas fa-egg' => 'Egg (breakfast,chicken,easter,shell,yolk)' ),
				array( 'fas fa-fish' => 'Fish (fauna,gold,seafood,swimming)' ),
				array( 'fas fa-hamburger' => 'Hamburger (bacon,beef,burger,burger king,cheeseburger,fast food,grill,ground beef,mcdonalds,sandwich)' ),
				array( 'fas fa-hotdog' => 'Hot Dog (bun,chili,frankfurt,frankfurter,kosher,polish,sandwich,sausage,vienna,weiner)' ),
				array( 'fas fa-ice-cream' => 'Ice Cream (chocolate,cone,dessert,frozen,scoop,sorbet,vanilla,yogurt)' ),
				array( 'fas fa-lemon' => 'Lemon (citrus,lemonade,lime,tart)' ),
				array( 'far fa-lemon' => 'Lemon (citrus,lemonade,lime,tart)' ),
				array( 'fas fa-pepper-hot' => 'Hot Pepper (buffalo wings,capsicum,chili,chilli,habanero,jalapeno,mexican,spicy,tabasco,vegetable)' ),
				array( 'fas fa-pizza-slice' => 'Pizza Slice (cheese,chicago,italian,mozzarella,new york,pepperoni,pie,slice,teenage mutant ninja turtles,tomato)' ),
				array( 'fas fa-seedling' => 'Seedling (flora,grow,plant,vegan)' ),
				array( 'fas fa-stroopwafel' => 'Stroopwafel (caramel,cookie,dessert,sweets,waffle)' ),
			),
			'Fruits & Vegetables' => array(
				array( 'fas fa-apple-alt' => 'Fruit Apple (fall,fruit,fuji,macintosh,orchard,seasonal,vegan)' ),
				array( 'fas fa-carrot' => 'Carrot (bugs bunny,orange,vegan,vegetable)' ),
				array( 'fas fa-leaf' => 'leaf (eco,flora,nature,plant,vegan)' ),
				array( 'fas fa-lemon' => 'Lemon (citrus,lemonade,lime,tart)' ),
				array( 'far fa-lemon' => 'Lemon (citrus,lemonade,lime,tart)' ),
				array( 'fas fa-pepper-hot' => 'Hot Pepper (buffalo wings,capsicum,chili,chilli,habanero,jalapeno,mexican,spicy,tabasco,vegetable)' ),
				array( 'fas fa-seedling' => 'Seedling (flora,grow,plant,vegan)' ),
			),
			'Games'               => array(
				array( 'fas fa-chess' => 'Chess (board,castle,checkmate,game,king,rook,strategy,tournament)' ),
				array( 'fas fa-chess-bishop' => 'Chess Bishop (board,checkmate,game,strategy)' ),
				array( 'fas fa-chess-board' => 'Chess Board (board,checkmate,game,strategy)' ),
				array( 'fas fa-chess-king' => 'Chess King (board,checkmate,game,strategy)' ),
				array( 'fas fa-chess-knight' => 'Chess Knight (board,checkmate,game,horse,strategy)' ),
				array( 'fas fa-chess-pawn' => 'Chess Pawn (board,checkmate,game,strategy)' ),
				array( 'fas fa-chess-queen' => 'Chess Queen (board,checkmate,game,strategy)' ),
				array( 'fas fa-chess-rook' => 'Chess Rook (board,castle,checkmate,game,strategy)' ),
				array( 'fas fa-dice' => 'Dice (chance,gambling,game,roll)' ),
				array( 'fas fa-dice-d20' => 'Dice D20 (Dungeons & Dragons,chance,d&d,dnd,fantasy,gambling,game,roll)' ),
				array( 'fas fa-dice-d6' => 'Dice D6 (Dungeons & Dragons,chance,d&d,dnd,fantasy,gambling,game,roll)' ),
				array( 'fas fa-dice-five' => 'Dice Five (chance,gambling,game,roll)' ),
				array( 'fas fa-dice-four' => 'Dice Four (chance,gambling,game,roll)' ),
				array( 'fas fa-dice-one' => 'Dice One (chance,gambling,game,roll)' ),
				array( 'fas fa-dice-six' => 'Dice Six (chance,gambling,game,roll)' ),
				array( 'fas fa-dice-three' => 'Dice Three (chance,gambling,game,roll)' ),
				array( 'fas fa-dice-two' => 'Dice Two (chance,gambling,game,roll)' ),
				array( 'fas fa-gamepad' => 'Gamepad (arcade,controller,d-pad,joystick,video,video game)' ),
				array( 'fas fa-ghost' => 'Ghost (apparition,blinky,clyde,floating,halloween,holiday,inky,pinky,spirit)' ),
				array( 'fas fa-headset' => 'Headset (audio,gamer,gaming,listen,live chat,microphone,shot caller,sound,support,telemarketer)' ),
				array( 'fas fa-heart' => 'Heart (favorite,like,love,relationship,valentine)' ),
				array( 'far fa-heart' => 'Heart (favorite,like,love,relationship,valentine)' ),
				array( 'fab fa-playstation' => 'PlayStation' ),
				array( 'fas fa-puzzle-piece' => 'Puzzle Piece (add-on,addon,game,section)' ),
				array( 'fab fa-steam' => 'Steam' ),
				array( 'fab fa-steam-square' => 'Steam Square' ),
				array( 'fab fa-steam-symbol' => 'Steam Symbol' ),
				array( 'fab fa-twitch' => 'Twitch' ),
				array( 'fab fa-xbox' => 'Xbox' ),
			),
			'Genders'             => array(
				array( 'fas fa-genderless' => 'Genderless (androgynous,asexual,sexless)' ),
				array( 'fas fa-mars' => 'Mars (male)' ),
				array( 'fas fa-mars-double' => 'Mars Double' ),
				array( 'fas fa-mars-stroke' => 'Mars Stroke' ),
				array( 'fas fa-mars-stroke-h' => 'Mars Stroke Horizontal' ),
				array( 'fas fa-mars-stroke-v' => 'Mars Stroke Vertical' ),
				array( 'fas fa-mercury' => 'Mercury (transgender)' ),
				array( 'fas fa-neuter' => 'Neuter' ),
				array( 'fas fa-transgender' => 'Transgender (intersex)' ),
				array( 'fas fa-transgender-alt' => 'Alternate Transgender (intersex)' ),
				array( 'fas fa-venus' => 'Venus (female)' ),
				array( 'fas fa-venus-double' => 'Venus Double (female)' ),
				array( 'fas fa-venus-mars' => 'Venus Mars (Gender)' ),
			),
			'Halloween'           => array(
				array( 'fas fa-book-dead' => 'Book of the Dead (Dungeons & Dragons,crossbones,d&d,dark arts,death,dnd,documentation,evil,fantasy,halloween,holiday,necronomicon,read,skull,spell)' ),
				array( 'fas fa-broom' => 'Broom (clean,firebolt,fly,halloween,nimbus 2000,quidditch,sweep,witch)' ),
				array( 'fas fa-cat' => 'Cat (feline,halloween,holiday,kitten,kitty,meow,pet)' ),
				array( 'fas fa-cloud-moon' => 'Cloud with Moon (crescent,evening,lunar,night,partly cloudy,sky)' ),
				array( 'fas fa-crow' => 'Crow (bird,bullfrog,fauna,halloween,holiday,toad)' ),
				array( 'fas fa-ghost' => 'Ghost (apparition,blinky,clyde,floating,halloween,holiday,inky,pinky,spirit)' ),
				array( 'fas fa-hat-wizard' => 'Wizard\'s Hat (Dungeons & Dragons,accessory,buckle,clothing,d&d,dnd,fantasy,halloween,head,holiday,mage,magic,pointy,witch)' ),
				array( 'fas fa-mask' => 'Mask (carnivale,costume,disguise,halloween,secret,super hero)' ),
				array( 'fas fa-skull-crossbones' => 'Skull & Crossbones (Dungeons & Dragons,alert,bones,d&d,danger,dead,deadly,death,dnd,fantasy,halloween,holiday,jolly-roger,pirate,poison,skeleton,warning)' ),
				array( 'fas fa-spider' => 'Spider (arachnid,bug,charlotte,crawl,eight,halloween)' ),
				array( 'fas fa-toilet-paper' => 'Toilet Paper (bathroom,halloween,holiday,lavatory,prank,restroom,roll)' ),
			),
			'Hands'               => array(
				array( 'fas fa-allergies' => 'Allergies (allergy,freckles,hand,hives,pox,skin,spots)' ),
				array( 'fas fa-fist-raised' => 'Raised Fist (Dungeons & Dragons,d&d,dnd,fantasy,hand,ki,monk,resist,strength,unarmed combat)' ),
				array( 'fas fa-hand-holding' => 'Hand Holding (carry,lift)' ),
				array( 'fas fa-hand-holding-heart' => 'Hand Holding Heart (carry,charity,gift,lift,package)' ),
				array( 'fas fa-hand-holding-usd' => 'Hand Holding US Dollar ($,carry,dollar sign,donation,giving,lift,money,price)' ),
				array( 'fas fa-hand-lizard' => 'Lizard (Hand) (game,roshambo)' ),
				array( 'far fa-hand-lizard' => 'Lizard (Hand) (game,roshambo)' ),
				array( 'fas fa-hand-middle-finger' => 'Hand with Middle Finger Raised (flip the bird,gesture,hate,rude)' ),
				array( 'fas fa-hand-paper' => 'Paper (Hand) (game,halt,roshambo,stop)' ),
				array( 'far fa-hand-paper' => 'Paper (Hand) (game,halt,roshambo,stop)' ),
				array( 'fas fa-hand-peace' => 'Peace (Hand) (rest,truce)' ),
				array( 'far fa-hand-peace' => 'Peace (Hand) (rest,truce)' ),
				array( 'fas fa-hand-point-down' => 'Hand Pointing Down (finger,hand-o-down,point)' ),
				array( 'far fa-hand-point-down' => 'Hand Pointing Down (finger,hand-o-down,point)' ),
				array( 'fas fa-hand-point-left' => 'Hand Pointing Left (back,finger,hand-o-left,left,point,previous)' ),
				array( 'far fa-hand-point-left' => 'Hand Pointing Left (back,finger,hand-o-left,left,point,previous)' ),
				array( 'fas fa-hand-point-right' => 'Hand Pointing Right (finger,forward,hand-o-right,next,point,right)' ),
				array( 'far fa-hand-point-right' => 'Hand Pointing Right (finger,forward,hand-o-right,next,point,right)' ),
				array( 'fas fa-hand-point-up' => 'Hand Pointing Up (finger,hand-o-up,point)' ),
				array( 'far fa-hand-point-up' => 'Hand Pointing Up (finger,hand-o-up,point)' ),
				array( 'fas fa-hand-pointer' => 'Pointer (Hand) (arrow,cursor,select)' ),
				array( 'far fa-hand-pointer' => 'Pointer (Hand) (arrow,cursor,select)' ),
				array( 'fas fa-hand-rock' => 'Rock (Hand) (fist,game,roshambo)' ),
				array( 'far fa-hand-rock' => 'Rock (Hand) (fist,game,roshambo)' ),
				array( 'fas fa-hand-scissors' => 'Scissors (Hand) (cut,game,roshambo)' ),
				array( 'far fa-hand-scissors' => 'Scissors (Hand) (cut,game,roshambo)' ),
				array( 'fas fa-hand-spock' => 'Spock (Hand) (live long,prosper,salute,star trek,vulcan)' ),
				array( 'far fa-hand-spock' => 'Spock (Hand) (live long,prosper,salute,star trek,vulcan)' ),
				array( 'fas fa-hands' => 'Hands (carry,hold,lift)' ),
				array( 'fas fa-hands-helping' => 'Helping Hands (aid,assistance,handshake,partnership,volunteering)' ),
				array( 'fas fa-handshake' => 'Handshake (agreement,greeting,meeting,partnership)' ),
				array( 'far fa-handshake' => 'Handshake (agreement,greeting,meeting,partnership)' ),
				array( 'fas fa-praying-hands' => 'Praying Hands (kneel,preach,religion,worship)' ),
				array( 'fas fa-thumbs-down' => 'thumbs-down (disagree,disapprove,dislike,hand,social,thumbs-o-down)' ),
				array( 'far fa-thumbs-down' => 'thumbs-down (disagree,disapprove,dislike,hand,social,thumbs-o-down)' ),
				array( 'fas fa-thumbs-up' => 'thumbs-up (agree,approve,favorite,hand,like,ok,okay,social,success,thumbs-o-up,yes,you got it dude)' ),
				array( 'far fa-thumbs-up' => 'thumbs-up (agree,approve,favorite,hand,like,ok,okay,social,success,thumbs-o-up,yes,you got it dude)' ),
			),
			'Health'              => array(
				array( 'fab fa-accessible-icon' => 'Accessible Icon (accessibility,handicap,person,wheelchair,wheelchair-alt)' ),
				array( 'fas fa-ambulance' => 'ambulance (emergency,emt,er,help,hospital,support,vehicle)' ),
				array( 'fas fa-h-square' => 'H Square (directions,emergency,hospital,hotel,map)' ),
				array( 'fas fa-heart' => 'Heart (favorite,like,love,relationship,valentine)' ),
				array( 'far fa-heart' => 'Heart (favorite,like,love,relationship,valentine)' ),
				array( 'fas fa-heartbeat' => 'Heartbeat (ekg,electrocardiogram,health,lifeline,vital signs)' ),
				array( 'fas fa-hospital' => 'hospital (building,emergency room,medical center)' ),
				array( 'far fa-hospital' => 'hospital (building,emergency room,medical center)' ),
				array( 'fas fa-medkit' => 'medkit (first aid,firstaid,health,help,support)' ),
				array( 'fas fa-plus-square' => 'Plus Square (add,create,expand,new,positive,shape)' ),
				array( 'far fa-plus-square' => 'Plus Square (add,create,expand,new,positive,shape)' ),
				array( 'fas fa-prescription' => 'Prescription (drugs,medical,medicine,pharmacy,rx)' ),
				array( 'fas fa-stethoscope' => 'Stethoscope (diagnosis,doctor,general practitioner,hospital,infirmary,medicine,office,outpatient)' ),
				array( 'fas fa-user-md' => 'Doctor (job,medical,nurse,occupation,physician,profile,surgeon)' ),
				array( 'fas fa-wheelchair' => 'Wheelchair (accessible,handicap,person)' ),
			),
			'Holiday'             => array(
				array( 'fas fa-candy-cane' => 'Candy Cane (candy,christmas,holiday,mint,peppermint,striped,xmas)' ),
				array( 'fas fa-carrot' => 'Carrot (bugs bunny,orange,vegan,vegetable)' ),
				array( 'fas fa-cookie-bite' => 'Cookie Bite (baked good,bitten,chips,chocolate,eat,snack,sweet,treat)' ),
				array( 'fas fa-gift' => 'gift (christmas,generosity,giving,holiday,party,present,wrapped,xmas)' ),
				array( 'fas fa-gifts' => 'Gifts (christmas,generosity,giving,holiday,party,present,wrapped,xmas)' ),
				array( 'fas fa-glass-cheers' => 'Glass Cheers (alcohol,bar,beverage,celebration,champagne,clink,drink,holiday,new year\'s eve,party,toast)' ),
				array( 'fas fa-holly-berry' => 'Holly Berry (catwoman,christmas,decoration,flora,halle,holiday,ororo munroe,plant,storm,xmas)' ),
				array( 'fas fa-mug-hot' => 'Mug Hot (caliente,cocoa,coffee,cup,drink,holiday,hot chocolate,steam,tea,warmth)' ),
				array( 'fas fa-sleigh' => 'Sleigh (christmas,claus,fly,holiday,santa,sled,snow,xmas)' ),
				array( 'fas fa-snowman' => 'Snowman (decoration,frost,frosty,holiday)' ),
			),
			'Hotel'               => array(
				array( 'fas fa-baby-carriage' => 'Baby Carriage (buggy,carrier,infant,push,stroller,transportation,walk,wheels)' ),
				array( 'fas fa-bath' => 'Bath (clean,shower,tub,wash)' ),
				array( 'fas fa-bed' => 'Bed (lodging,rest,sleep,travel)' ),
				array( 'fas fa-briefcase' => 'Briefcase (bag,business,luggage,office,work)' ),
				array( 'fas fa-car' => 'Car (auto,automobile,sedan,transportation,travel,vehicle)' ),
				array( 'fas fa-cocktail' => 'Cocktail (alcohol,beverage,drink,gin,glass,margarita,martini,vodka)' ),
				array( 'fas fa-coffee' => 'Coffee (beverage,breakfast,cafe,drink,fall,morning,mug,seasonal,tea)' ),
				array( 'fas fa-concierge-bell' => 'Concierge Bell (attention,hotel,receptionist,service,support)' ),
				array( 'fas fa-dice' => 'Dice (chance,gambling,game,roll)' ),
				array( 'fas fa-dice-five' => 'Dice Five (chance,gambling,game,roll)' ),
				array( 'fas fa-door-closed' => 'Door Closed (enter,exit,locked)' ),
				array( 'fas fa-door-open' => 'Door Open (enter,exit,welcome)' ),
				array( 'fas fa-dumbbell' => 'Dumbbell (exercise,gym,strength,weight,weight-lifting)' ),
				array( 'fas fa-glass-martini' => 'Martini Glass (alcohol,bar,beverage,drink,liquor)' ),
				array( 'fas fa-glass-martini-alt' => 'Alternate Glass Martini (alcohol,bar,beverage,drink,liquor)' ),
				array( 'fas fa-hot-tub' => 'Hot Tub (bath,jacuzzi,massage,sauna,spa)' ),
				array( 'fas fa-hotel' => 'Hotel (building,inn,lodging,motel,resort,travel)' ),
				array( 'fas fa-infinity' => 'Infinity (eternity,forever,math)' ),
				array( 'fas fa-key' => 'key (lock,password,private,secret,unlock)' ),
				array( 'fas fa-luggage-cart' => 'Luggage Cart (bag,baggage,suitcase,travel)' ),
				array( 'fas fa-shower' => 'Shower (bath,clean,faucet,water)' ),
				array( 'fas fa-shuttle-van' => 'Shuttle Van (airport,machine,public-transportation,transportation,travel,vehicle)' ),
				array( 'fas fa-smoking' => 'Smoking (cancer,cigarette,nicotine,smoking status,tobacco)' ),
				array( 'fas fa-smoking-ban' => 'Smoking Ban (ban,cancel,no smoking,non-smoking)' ),
				array( 'fas fa-snowflake' => 'Snowflake (precipitation,rain,winter)' ),
				array( 'far fa-snowflake' => 'Snowflake (precipitation,rain,winter)' ),
				array( 'fas fa-spa' => 'Spa (flora,massage,mindfulness,plant,wellness)' ),
				array( 'fas fa-suitcase' => 'Suitcase (baggage,luggage,move,suitcase,travel,trip)' ),
				array( 'fas fa-suitcase-rolling' => 'Suitcase Rolling (baggage,luggage,move,suitcase,travel,trip)' ),
				array( 'fas fa-swimmer' => 'Swimmer (athlete,head,man,olympics,person,pool,water)' ),
				array( 'fas fa-swimming-pool' => 'Swimming Pool (ladder,recreation,swim,water)' ),
				array( 'fas fa-tv' => 'Television (computer,display,monitor,television)' ),
				array( 'fas fa-umbrella-beach' => 'Umbrella Beach (protection,recreation,sand,shade,summer,sun)' ),
				array( 'fas fa-utensils' => 'Utensils (cutlery,dining,dinner,eat,food,fork,knife,restaurant)' ),
				array( 'fas fa-wheelchair' => 'Wheelchair (accessible,handicap,person)' ),
				array( 'fas fa-wifi' => 'WiFi (connection,hotspot,internet,network,wireless)' ),
			),
			'Household'           => array(
				array( 'fas fa-bath' => 'Bath (clean,shower,tub,wash)' ),
				array( 'fas fa-bed' => 'Bed (lodging,rest,sleep,travel)' ),
				array( 'fas fa-blender' => 'Blender (cocktail,milkshake,mixer,puree,smoothie)' ),
				array( 'fas fa-chair' => 'Chair (furniture,seat,sit)' ),
				array( 'fas fa-couch' => 'Couch (chair,cushion,furniture,relax,sofa)' ),
				array( 'fas fa-door-closed' => 'Door Closed (enter,exit,locked)' ),
				array( 'fas fa-door-open' => 'Door Open (enter,exit,welcome)' ),
				array( 'fas fa-dungeon' => 'Dungeon (Dungeons & Dragons,building,d&d,dnd,door,entrance,fantasy,gate)' ),
				array( 'fas fa-fan' => 'Fan (ac,air conditioning,blade,blower,cool,hot)' ),
				array( 'fas fa-shower' => 'Shower (bath,clean,faucet,water)' ),
				array( 'fas fa-toilet-paper' => 'Toilet Paper (bathroom,halloween,holiday,lavatory,prank,restroom,roll)' ),
				array( 'fas fa-tv' => 'Television (computer,display,monitor,television)' ),
			),
			'Images'              => array(
				array( 'fas fa-adjust' => 'adjust (contrast,dark,light,saturation)' ),
				array( 'fas fa-bolt' => 'Lightning Bolt (electricity,lightning,weather,zap)' ),
				array( 'fas fa-camera' => 'camera (image,lens,photo,picture,record,shutter,video)' ),
				array( 'fas fa-camera-retro' => 'Retro Camera (image,lens,photo,picture,record,shutter,video)' ),
				array( 'fas fa-chalkboard' => 'Chalkboard (blackboard,learning,school,teaching,whiteboard,writing)' ),
				array( 'fas fa-clone' => 'Clone (arrange,copy,duplicate,paste)' ),
				array( 'far fa-clone' => 'Clone (arrange,copy,duplicate,paste)' ),
				array( 'fas fa-compress' => 'Compress (collapse,fullscreen,minimize,move,resize,shrink,smaller)' ),
				array( 'fas fa-compress-arrows-alt' => 'Alternate Compress Arrows (collapse,fullscreen,minimize,move,resize,shrink,smaller)' ),
				array( 'fas fa-expand' => 'Expand (arrow,bigger,enlarge,resize)' ),
				array( 'fas fa-eye' => 'Eye (look,optic,see,seen,show,sight,views,visible)' ),
				array( 'far fa-eye' => 'Eye (look,optic,see,seen,show,sight,views,visible)' ),
				array( 'fas fa-eye-dropper' => 'Eye Dropper (beaker,clone,color,copy,eyedropper,pipette)' ),
				array( 'fas fa-eye-slash' => 'Eye Slash (blind,hide,show,toggle,unseen,views,visible,visiblity)' ),
				array( 'far fa-eye-slash' => 'Eye Slash (blind,hide,show,toggle,unseen,views,visible,visiblity)' ),
				array( 'fas fa-file-image' => 'Image File (document,image,jpg,photo,png)' ),
				array( 'far fa-file-image' => 'Image File (document,image,jpg,photo,png)' ),
				array( 'fas fa-film' => 'Film (cinema,movie,strip,video)' ),
				array( 'fas fa-id-badge' => 'Identification Badge (address,contact,identification,license,profile)' ),
				array( 'far fa-id-badge' => 'Identification Badge (address,contact,identification,license,profile)' ),
				array( 'fas fa-id-card' => 'Identification Card (contact,demographics,document,identification,issued,profile)' ),
				array( 'far fa-id-card' => 'Identification Card (contact,demographics,document,identification,issued,profile)' ),
				array( 'fas fa-image' => 'Image (album,landscape,photo,picture)' ),
				array( 'far fa-image' => 'Image (album,landscape,photo,picture)' ),
				array( 'fas fa-images' => 'Images (album,landscape,photo,picture)' ),
				array( 'far fa-images' => 'Images (album,landscape,photo,picture)' ),
				array( 'fas fa-photo-video' => 'Photo Video (av,film,image,library,media)' ),
				array( 'fas fa-portrait' => 'Portrait (id,image,photo,picture,selfie)' ),
				array( 'fas fa-sliders-h' => 'Horizontal Sliders (adjust,settings,sliders,toggle)' ),
				array( 'fas fa-tint' => 'tint (color,drop,droplet,raindrop,waterdrop)' ),
			),
			'Interfaces'          => array(
				array( 'fas fa-award' => 'Award (honor,praise,prize,recognition,ribbon,trophy)' ),
				array( 'fas fa-ban' => 'ban (abort,ban,block,cancel,delete,hide,prohibit,remove,stop,trash)' ),
				array( 'fas fa-barcode' => 'barcode (info,laser,price,scan,upc)' ),
				array( 'fas fa-bars' => 'Bars (checklist,drag,hamburger,list,menu,nav,navigation,ol,reorder,settings,todo,ul)' ),
				array( 'fas fa-beer' => 'beer (alcohol,ale,bar,beverage,brewery,drink,lager,liquor,mug,stein)' ),
				array( 'fas fa-bell' => 'bell (alarm,alert,chime,notification,reminder)' ),
				array( 'far fa-bell' => 'bell (alarm,alert,chime,notification,reminder)' ),
				array( 'fas fa-bell-slash' => 'Bell Slash (alert,cancel,disabled,notification,off,reminder)' ),
				array( 'far fa-bell-slash' => 'Bell Slash (alert,cancel,disabled,notification,off,reminder)' ),
				array( 'fas fa-blog' => 'Blog (journal,log,online,personal,post,web 2.0,wordpress,writing)' ),
				array( 'fas fa-bug' => 'Bug (beetle,error,insect,report)' ),
				array( 'fas fa-bullhorn' => 'bullhorn (announcement,broadcast,louder,megaphone,share)' ),
				array( 'fas fa-bullseye' => 'Bullseye (archery,goal,objective,target)' ),
				array( 'fas fa-calculator' => 'Calculator (abacus,addition,arithmetic,counting,math,multiplication,subtraction)' ),
				array( 'fas fa-calendar' => 'Calendar (calendar-o,date,event,schedule,time,when)' ),
				array( 'far fa-calendar' => 'Calendar (calendar-o,date,event,schedule,time,when)' ),
				array( 'fas fa-calendar-alt' => 'Alternate Calendar (calendar,date,event,schedule,time,when)' ),
				array( 'far fa-calendar-alt' => 'Alternate Calendar (calendar,date,event,schedule,time,when)' ),
				array( 'fas fa-calendar-check' => 'Calendar Check (accept,agree,appointment,confirm,correct,date,done,event,ok,schedule,select,success,tick,time,todo,when)' ),
				array( 'far fa-calendar-check' => 'Calendar Check (accept,agree,appointment,confirm,correct,date,done,event,ok,schedule,select,success,tick,time,todo,when)' ),
				array( 'fas fa-calendar-minus' => 'Calendar Minus (calendar,date,delete,event,negative,remove,schedule,time,when)' ),
				array( 'far fa-calendar-minus' => 'Calendar Minus (calendar,date,delete,event,negative,remove,schedule,time,when)' ),
				array( 'fas fa-calendar-plus' => 'Calendar Plus (add,calendar,create,date,event,new,positive,schedule,time,when)' ),
				array( 'far fa-calendar-plus' => 'Calendar Plus (add,calendar,create,date,event,new,positive,schedule,time,when)' ),
				array( 'fas fa-calendar-times' => 'Calendar Times (archive,calendar,date,delete,event,remove,schedule,time,when,x)' ),
				array( 'far fa-calendar-times' => 'Calendar Times (archive,calendar,date,delete,event,remove,schedule,time,when,x)' ),
				array( 'fas fa-certificate' => 'certificate (badge,star,verified)' ),
				array( 'fas fa-check' => 'Check (accept,agree,checkmark,confirm,correct,done,notice,notification,notify,ok,select,success,tick,todo,yes)' ),
				array( 'fas fa-check-circle' => 'Check Circle (accept,agree,confirm,correct,done,ok,select,success,tick,todo,yes)' ),
				array( 'far fa-check-circle' => 'Check Circle (accept,agree,confirm,correct,done,ok,select,success,tick,todo,yes)' ),
				array( 'fas fa-check-double' => 'Double Check (accept,agree,checkmark,confirm,correct,done,notice,notification,notify,ok,select,success,tick,todo)' ),
				array( 'fas fa-check-square' => 'Check Square (accept,agree,checkmark,confirm,correct,done,ok,select,success,tick,todo,yes)' ),
				array( 'far fa-check-square' => 'Check Square (accept,agree,checkmark,confirm,correct,done,ok,select,success,tick,todo,yes)' ),
				array( 'fas fa-circle' => 'Circle (circle-thin,diameter,dot,ellipse,notification,round)' ),
				array( 'far fa-circle' => 'Circle (circle-thin,diameter,dot,ellipse,notification,round)' ),
				array( 'fas fa-clipboard' => 'Clipboard (copy,notes,paste,record)' ),
				array( 'far fa-clipboard' => 'Clipboard (copy,notes,paste,record)' ),
				array( 'fas fa-clone' => 'Clone (arrange,copy,duplicate,paste)' ),
				array( 'far fa-clone' => 'Clone (arrange,copy,duplicate,paste)' ),
				array( 'fas fa-cloud' => 'Cloud (atmosphere,fog,overcast,save,upload,weather)' ),
				array( 'fas fa-cloud-download-alt' => 'Alternate Cloud Download (download,export,save)' ),
				array( 'fas fa-cloud-upload-alt' => 'Alternate Cloud Upload (cloud-upload,import,save,upload)' ),
				array( 'fas fa-coffee' => 'Coffee (beverage,breakfast,cafe,drink,fall,morning,mug,seasonal,tea)' ),
				array( 'fas fa-cog' => 'cog (gear,mechanical,settings,sprocket,wheel)' ),
				array( 'fas fa-cogs' => 'cogs (gears,mechanical,settings,sprocket,wheel)' ),
				array( 'fas fa-copy' => 'Copy (clone,duplicate,file,files-o,paper,paste)' ),
				array( 'far fa-copy' => 'Copy (clone,duplicate,file,files-o,paper,paste)' ),
				array( 'fas fa-cut' => 'Cut (clip,scissors,snip)' ),
				array( 'fas fa-database' => 'Database (computer,development,directory,memory,storage)' ),
				array( 'fas fa-dot-circle' => 'Dot Circle (bullseye,notification,target)' ),
				array( 'far fa-dot-circle' => 'Dot Circle (bullseye,notification,target)' ),
				array( 'fas fa-download' => 'Download (export,hard drive,save,transfer)' ),
				array( 'fas fa-edit' => 'Edit (edit,pen,pencil,update,write)' ),
				array( 'far fa-edit' => 'Edit (edit,pen,pencil,update,write)' ),
				array( 'fas fa-ellipsis-h' => 'Horizontal Ellipsis (dots,drag,kebab,list,menu,nav,navigation,ol,reorder,settings,ul)' ),
				array( 'fas fa-ellipsis-v' => 'Vertical Ellipsis (dots,drag,kebab,list,menu,nav,navigation,ol,reorder,settings,ul)' ),
				array( 'fas fa-envelope' => 'Envelope (e-mail,email,letter,mail,message,notification,support)' ),
				array( 'far fa-envelope' => 'Envelope (e-mail,email,letter,mail,message,notification,support)' ),
				array( 'fas fa-envelope-open' => 'Envelope Open (e-mail,email,letter,mail,message,notification,support)' ),
				array( 'far fa-envelope-open' => 'Envelope Open (e-mail,email,letter,mail,message,notification,support)' ),
				array( 'fas fa-eraser' => 'eraser (art,delete,remove,rubber)' ),
				array( 'fas fa-exclamation' => 'exclamation (alert,danger,error,important,notice,notification,notify,problem,warning)' ),
				array( 'fas fa-exclamation-circle' => 'Exclamation Circle (alert,danger,error,important,notice,notification,notify,problem,warning)' ),
				array( 'fas fa-exclamation-triangle' => 'Exclamation Triangle (alert,danger,error,important,notice,notification,notify,problem,warning)' ),
				array( 'fas fa-external-link-alt' => 'Alternate External Link (external-link,new,open,share)' ),
				array( 'fas fa-external-link-square-alt' => 'Alternate External Link Square (external-link-square,new,open,share)' ),
				array( 'fas fa-eye' => 'Eye (look,optic,see,seen,show,sight,views,visible)' ),
				array( 'far fa-eye' => 'Eye (look,optic,see,seen,show,sight,views,visible)' ),
				array( 'fas fa-eye-slash' => 'Eye Slash (blind,hide,show,toggle,unseen,views,visible,visiblity)' ),
				array( 'far fa-eye-slash' => 'Eye Slash (blind,hide,show,toggle,unseen,views,visible,visiblity)' ),
				array( 'fas fa-file' => 'File (document,new,page,pdf,resume)' ),
				array( 'far fa-file' => 'File (document,new,page,pdf,resume)' ),
				array( 'fas fa-file-alt' => 'Alternate File (document,file-text,invoice,new,page,pdf)' ),
				array( 'far fa-file-alt' => 'Alternate File (document,file-text,invoice,new,page,pdf)' ),
				array( 'fas fa-file-download' => 'File Download (document,export,save)' ),
				array( 'fas fa-file-export' => 'File Export (download,save)' ),
				array( 'fas fa-file-import' => 'File Import (copy,document,send,upload)' ),
				array( 'fas fa-file-upload' => 'File Upload (document,import,page,save)' ),
				array( 'fas fa-filter' => 'Filter (funnel,options,separate,sort)' ),
				array( 'fas fa-fingerprint' => 'Fingerprint (human,id,identification,lock,smudge,touch,unique,unlock)' ),
				array( 'fas fa-flag' => 'flag (country,notice,notification,notify,pole,report,symbol)' ),
				array( 'far fa-flag' => 'flag (country,notice,notification,notify,pole,report,symbol)' ),
				array( 'fas fa-flag-checkered' => 'flag-checkered (notice,notification,notify,pole,racing,report,symbol)' ),
				array( 'fas fa-folder' => 'Folder (archive,directory,document,file)' ),
				array( 'far fa-folder' => 'Folder (archive,directory,document,file)' ),
				array( 'fas fa-folder-open' => 'Folder Open (archive,directory,document,empty,file,new)' ),
				array( 'far fa-folder-open' => 'Folder Open (archive,directory,document,empty,file,new)' ),
				array( 'fas fa-frown' => 'Frowning Face (disapprove,emoticon,face,rating,sad)' ),
				array( 'far fa-frown' => 'Frowning Face (disapprove,emoticon,face,rating,sad)' ),
				array( 'fas fa-glasses' => 'Glasses (hipster,nerd,reading,sight,spectacles,vision)' ),
				array( 'fas fa-grip-horizontal' => 'Grip Horizontal (affordance,drag,drop,grab,handle)' ),
				array( 'fas fa-grip-lines' => 'Grip Lines (affordance,drag,drop,grab,handle)' ),
				array( 'fas fa-grip-lines-vertical' => 'Grip Lines Vertical (affordance,drag,drop,grab,handle)' ),
				array( 'fas fa-grip-vertical' => 'Grip Vertical (affordance,drag,drop,grab,handle)' ),
				array( 'fas fa-hashtag' => 'Hashtag (Twitter,instagram,pound,social media,tag)' ),
				array( 'fas fa-heart' => 'Heart (favorite,like,love,relationship,valentine)' ),
				array( 'far fa-heart' => 'Heart (favorite,like,love,relationship,valentine)' ),
				array( 'fas fa-history' => 'History (Rewind,clock,reverse,time,time machine)' ),
				array( 'fas fa-home' => 'home (abode,building,house,main)' ),
				array( 'fas fa-i-cursor' => 'I Beam Cursor (editing,i-beam,type,writing)' ),
				array( 'fas fa-info' => 'Info (details,help,information,more,support)' ),
				array( 'fas fa-info-circle' => 'Info Circle (details,help,information,more,support)' ),
				array( 'fas fa-language' => 'Language (dialect,idiom,localize,speech,translate,vernacular)' ),
				array( 'fas fa-magic' => 'magic (autocomplete,automatic,mage,magic,spell,wand,witch,wizard)' ),
				array( 'fas fa-marker' => 'Marker (design,edit,sharpie,update,write)' ),
				array( 'fas fa-medal' => 'Medal (award,ribbon,star,trophy)' ),
				array( 'fas fa-meh' => 'Neutral Face (emoticon,face,neutral,rating)' ),
				array( 'far fa-meh' => 'Neutral Face (emoticon,face,neutral,rating)' ),
				array( 'fas fa-microphone' => 'microphone (audio,podcast,record,sing,sound,voice)' ),
				array( 'fas fa-microphone-alt' => 'Alternate Microphone (audio,podcast,record,sing,sound,voice)' ),
				array( 'fas fa-microphone-slash' => 'Microphone Slash (audio,disable,mute,podcast,record,sing,sound,voice)' ),
				array( 'fas fa-minus' => 'minus (collapse,delete,hide,minify,negative,remove,trash)' ),
				array( 'fas fa-minus-circle' => 'Minus Circle (delete,hide,negative,remove,shape,trash)' ),
				array( 'fas fa-minus-square' => 'Minus Square (collapse,delete,hide,minify,negative,remove,shape,trash)' ),
				array( 'far fa-minus-square' => 'Minus Square (collapse,delete,hide,minify,negative,remove,shape,trash)' ),
				array( 'fas fa-paste' => 'Paste (clipboard,copy,document,paper)' ),
				array( 'fas fa-pen' => 'Pen (design,edit,update,write)' ),
				array( 'fas fa-pen-alt' => 'Alternate Pen (design,edit,update,write)' ),
				array( 'fas fa-pen-fancy' => 'Pen Fancy (design,edit,fountain pen,update,write)' ),
				array( 'fas fa-pencil-alt' => 'Alternate Pencil (design,edit,pencil,update,write)' ),
				array( 'fas fa-plus' => 'plus (add,create,expand,new,positive,shape)' ),
				array( 'fas fa-plus-circle' => 'Plus Circle (add,create,expand,new,positive,shape)' ),
				array( 'fas fa-plus-square' => 'Plus Square (add,create,expand,new,positive,shape)' ),
				array( 'far fa-plus-square' => 'Plus Square (add,create,expand,new,positive,shape)' ),
				array( 'fas fa-poo' => 'Poo (crap,poop,shit,smile,turd)' ),
				array( 'fas fa-qrcode' => 'qrcode (barcode,info,information,scan)' ),
				array( 'fas fa-question' => 'Question (help,information,support,unknown)' ),
				array( 'fas fa-question-circle' => 'Question Circle (help,information,support,unknown)' ),
				array( 'far fa-question-circle' => 'Question Circle (help,information,support,unknown)' ),
				array( 'fas fa-quote-left' => 'quote-left (mention,note,phrase,text,type)' ),
				array( 'fas fa-quote-right' => 'quote-right (mention,note,phrase,text,type)' ),
				array( 'fas fa-redo' => 'Redo (forward,refresh,reload,repeat)' ),
				array( 'fas fa-redo-alt' => 'Alternate Redo (forward,refresh,reload,repeat)' ),
				array( 'fas fa-reply' => 'Reply (mail,message,respond)' ),
				array( 'fas fa-reply-all' => 'reply-all (mail,message,respond)' ),
				array( 'fas fa-rss' => 'rss (blog,feed,journal,news,writing)' ),
				array( 'fas fa-rss-square' => 'RSS Square (blog,feed,journal,news,writing)' ),
				array( 'fas fa-save' => 'Save (disk,download,floppy,floppy-o)' ),
				array( 'far fa-save' => 'Save (disk,download,floppy,floppy-o)' ),
				array( 'fas fa-screwdriver' => 'Screwdriver (admin,fix,mechanic,repair,settings,tool)' ),
				array( 'fas fa-search' => 'Search (bigger,enlarge,find,magnify,preview,zoom)' ),
				array( 'fas fa-search-minus' => 'Search Minus (minify,negative,smaller,zoom,zoom out)' ),
				array( 'fas fa-search-plus' => 'Search Plus (bigger,enlarge,magnify,positive,zoom,zoom in)' ),
				array( 'fas fa-share' => 'Share (forward,save,send,social)' ),
				array( 'fas fa-share-alt' => 'Alternate Share (forward,save,send,social)' ),
				array( 'fas fa-share-alt-square' => 'Alternate Share Square (forward,save,send,social)' ),
				array( 'fas fa-share-square' => 'Share Square (forward,save,send,social)' ),
				array( 'far fa-share-square' => 'Share Square (forward,save,send,social)' ),
				array( 'fas fa-shield-alt' => 'Alternate Shield (achievement,award,block,defend,security,winner)' ),
				array( 'fas fa-sign-in-alt' => 'Alternate Sign In (arrow,enter,join,log in,login,sign in,sign up,sign-in,signin,signup)' ),
				array( 'fas fa-sign-out-alt' => 'Alternate Sign Out (arrow,exit,leave,log out,logout,sign-out)' ),
				array( 'fas fa-signal' => 'signal (bars,graph,online,reception,status)' ),
				array( 'fas fa-sitemap' => 'Sitemap (directory,hierarchy,ia,information architecture,organization)' ),
				array( 'fas fa-sliders-h' => 'Horizontal Sliders (adjust,settings,sliders,toggle)' ),
				array( 'fas fa-smile' => 'Smiling Face (approve,emoticon,face,happy,rating,satisfied)' ),
				array( 'far fa-smile' => 'Smiling Face (approve,emoticon,face,happy,rating,satisfied)' ),
				array( 'fas fa-sort' => 'Sort (filter,order)' ),
				array( 'fas fa-sort-alpha-down' => 'Sort Alphabetical Down (alphabetical,arrange,filter,order,sort-alpha-asc)' ),
				array( 'fas fa-sort-alpha-down-alt' => 'Alternate Sort Alphabetical Down (alphabetical,arrange,filter,order,sort-alpha-asc)' ),
				array( 'fas fa-sort-alpha-up' => 'Sort Alphabetical Up (alphabetical,arrange,filter,order,sort-alpha-desc)' ),
				array( 'fas fa-sort-alpha-up-alt' => 'Alternate Sort Alphabetical Up (alphabetical,arrange,filter,order,sort-alpha-desc)' ),
				array( 'fas fa-sort-amount-down' => 'Sort Amount Down (arrange,filter,number,order,sort-amount-asc)' ),
				array( 'fas fa-sort-amount-down-alt' => 'Alternate Sort Amount Down (arrange,filter,order,sort-amount-asc)' ),
				array( 'fas fa-sort-amount-up' => 'Sort Amount Up (arrange,filter,order,sort-amount-desc)' ),
				array( 'fas fa-sort-amount-up-alt' => 'Alternate Sort Amount Up (arrange,filter,order,sort-amount-desc)' ),
				array( 'fas fa-sort-down' => 'Sort Down (Descending) (arrow,descending,filter,order,sort-desc)' ),
				array( 'fas fa-sort-numeric-down' => 'Sort Numeric Down (arrange,filter,numbers,order,sort-numeric-asc)' ),
				array( 'fas fa-sort-numeric-down-alt' => 'Alternate Sort Numeric Down (arrange,filter,numbers,order,sort-numeric-asc)' ),
				array( 'fas fa-sort-numeric-up' => 'Sort Numeric Up (arrange,filter,numbers,order,sort-numeric-desc)' ),
				array( 'fas fa-sort-numeric-up-alt' => 'Alternate Sort Numeric Up (arrange,filter,numbers,order,sort-numeric-desc)' ),
				array( 'fas fa-sort-up' => 'Sort Up (Ascending) (arrow,ascending,filter,order,sort-asc)' ),
				array( 'fas fa-star' => 'Star (achievement,award,favorite,important,night,rating,score)' ),
				array( 'far fa-star' => 'Star (achievement,award,favorite,important,night,rating,score)' ),
				array( 'fas fa-star-half' => 'star-half (achievement,award,rating,score,star-half-empty,star-half-full)' ),
				array( 'far fa-star-half' => 'star-half (achievement,award,rating,score,star-half-empty,star-half-full)' ),
				array( 'fas fa-sync' => 'Sync (exchange,refresh,reload,rotate,swap)' ),
				array( 'fas fa-sync-alt' => 'Alternate Sync (exchange,refresh,reload,rotate,swap)' ),
				array( 'fas fa-thumbs-down' => 'thumbs-down (disagree,disapprove,dislike,hand,social,thumbs-o-down)' ),
				array( 'far fa-thumbs-down' => 'thumbs-down (disagree,disapprove,dislike,hand,social,thumbs-o-down)' ),
				array( 'fas fa-thumbs-up' => 'thumbs-up (agree,approve,favorite,hand,like,ok,okay,social,success,thumbs-o-up,yes,you got it dude)' ),
				array( 'far fa-thumbs-up' => 'thumbs-up (agree,approve,favorite,hand,like,ok,okay,social,success,thumbs-o-up,yes,you got it dude)' ),
				array( 'fas fa-times' => 'Times (close,cross,error,exit,incorrect,notice,notification,notify,problem,wrong,x)' ),
				array( 'fas fa-times-circle' => 'Times Circle (close,cross,exit,incorrect,notice,notification,notify,problem,wrong,x)' ),
				array( 'far fa-times-circle' => 'Times Circle (close,cross,exit,incorrect,notice,notification,notify,problem,wrong,x)' ),
				array( 'fas fa-toggle-off' => 'Toggle Off (switch)' ),
				array( 'fas fa-toggle-on' => 'Toggle On (switch)' ),
				array( 'fas fa-tools' => 'Tools (admin,fix,repair,screwdriver,settings,tools,wrench)' ),
				array( 'fas fa-trash' => 'Trash (delete,garbage,hide,remove)' ),
				array( 'fas fa-trash-alt' => 'Alternate Trash (delete,garbage,hide,remove,trash-o)' ),
				array( 'far fa-trash-alt' => 'Alternate Trash (delete,garbage,hide,remove,trash-o)' ),
				array( 'fas fa-trash-restore' => 'Trash Restore (back,control z,oops,undo)' ),
				array( 'fas fa-trash-restore-alt' => 'Alternative Trash Restore (back,control z,oops,undo)' ),
				array( 'fas fa-trophy' => 'trophy (achievement,award,cup,game,winner)' ),
				array( 'fas fa-undo' => 'Undo (back,control z,exchange,oops,return,rotate,swap)' ),
				array( 'fas fa-undo-alt' => 'Alternate Undo (back,control z,exchange,oops,return,swap)' ),
				array( 'fas fa-upload' => 'Upload (hard drive,import,publish)' ),
				array( 'fas fa-user' => 'User (account,avatar,head,human,man,person,profile)' ),
				array( 'far fa-user' => 'User (account,avatar,head,human,man,person,profile)' ),
				array( 'fas fa-user-alt' => 'Alternate User (account,avatar,head,human,man,person,profile)' ),
				array( 'fas fa-user-circle' => 'User Circle (account,avatar,head,human,man,person,profile)' ),
				array( 'far fa-user-circle' => 'User Circle (account,avatar,head,human,man,person,profile)' ),
				array( 'fas fa-volume-down' => 'Volume Down (audio,lower,music,quieter,sound,speaker)' ),
				array( 'fas fa-volume-mute' => 'Volume Mute (audio,music,quiet,sound,speaker)' ),
				array( 'fas fa-volume-off' => 'Volume Off (audio,ban,music,mute,quiet,silent,sound)' ),
				array( 'fas fa-volume-up' => 'Volume Up (audio,higher,louder,music,sound,speaker)' ),
				array( 'fas fa-wifi' => 'WiFi (connection,hotspot,internet,network,wireless)' ),
				array( 'fas fa-wrench' => 'Wrench (construction,fix,mechanic,plumbing,settings,spanner,tool,update)' ),
			),
			'Logistics'           => array(
				array( 'fas fa-box' => 'Box (archive,container,package,storage)' ),
				array( 'fas fa-boxes' => 'Boxes (archives,inventory,storage,warehouse)' ),
				array( 'fas fa-clipboard-check' => 'Clipboard with Check (accept,agree,confirm,done,ok,select,success,tick,todo,yes)' ),
				array( 'fas fa-clipboard-list' => 'Clipboard List (checklist,completed,done,finished,intinerary,ol,schedule,tick,todo,ul)' ),
				array( 'fas fa-dolly' => 'Dolly (carry,shipping,transport)' ),
				array( 'fas fa-dolly-flatbed' => 'Dolly Flatbed (carry,inventory,shipping,transport)' ),
				array( 'fas fa-hard-hat' => 'Hard Hat (construction,hardhat,helmet,safety)' ),
				array( 'fas fa-pallet' => 'Pallet (archive,box,inventory,shipping,warehouse)' ),
				array( 'fas fa-shipping-fast' => 'Shipping Fast (express,fedex,mail,overnight,package,ups)' ),
				array( 'fas fa-truck' => 'truck (cargo,delivery,shipping,vehicle)' ),
				array( 'fas fa-warehouse' => 'Warehouse (building,capacity,garage,inventory,storage)' ),
			),
			'Maps'                => array(
				array( 'fas fa-ambulance' => 'ambulance (emergency,emt,er,help,hospital,support,vehicle)' ),
				array( 'fas fa-anchor' => 'Anchor (berth,boat,dock,embed,link,maritime,moor,secure)' ),
				array( 'fas fa-balance-scale' => 'Balance Scale (balanced,justice,legal,measure,weight)' ),
				array( 'fas fa-balance-scale-left' => 'Balance Scale (Left-Weighted) (justice,legal,measure,unbalanced,weight)' ),
				array( 'fas fa-balance-scale-right' => 'Balance Scale (Right-Weighted) (justice,legal,measure,unbalanced,weight)' ),
				array( 'fas fa-bath' => 'Bath (clean,shower,tub,wash)' ),
				array( 'fas fa-bed' => 'Bed (lodging,rest,sleep,travel)' ),
				array( 'fas fa-beer' => 'beer (alcohol,ale,bar,beverage,brewery,drink,lager,liquor,mug,stein)' ),
				array( 'fas fa-bell' => 'bell (alarm,alert,chime,notification,reminder)' ),
				array( 'far fa-bell' => 'bell (alarm,alert,chime,notification,reminder)' ),
				array( 'fas fa-bell-slash' => 'Bell Slash (alert,cancel,disabled,notification,off,reminder)' ),
				array( 'far fa-bell-slash' => 'Bell Slash (alert,cancel,disabled,notification,off,reminder)' ),
				array( 'fas fa-bicycle' => 'Bicycle (bike,gears,pedal,transportation,vehicle)' ),
				array( 'fas fa-binoculars' => 'Binoculars (glasses,magnify,scenic,spyglass,view)' ),
				array( 'fas fa-birthday-cake' => 'Birthday Cake (anniversary,bakery,candles,celebration,dessert,frosting,holiday,party,pastry)' ),
				array( 'fas fa-blind' => 'Blind (cane,disability,person,sight)' ),
				array( 'fas fa-bomb' => 'Bomb (error,explode,fuse,grenade,warning)' ),
				array( 'fas fa-book' => 'book (diary,documentation,journal,library,read)' ),
				array( 'fas fa-bookmark' => 'bookmark (favorite,marker,read,remember,save)' ),
				array( 'far fa-bookmark' => 'bookmark (favorite,marker,read,remember,save)' ),
				array( 'fas fa-briefcase' => 'Briefcase (bag,business,luggage,office,work)' ),
				array( 'fas fa-building' => 'Building (apartment,business,city,company,office,work)' ),
				array( 'far fa-building' => 'Building (apartment,business,city,company,office,work)' ),
				array( 'fas fa-car' => 'Car (auto,automobile,sedan,transportation,travel,vehicle)' ),
				array( 'fas fa-coffee' => 'Coffee (beverage,breakfast,cafe,drink,fall,morning,mug,seasonal,tea)' ),
				array( 'fas fa-crosshairs' => 'Crosshairs (aim,bullseye,gpd,picker,position)' ),
				array( 'fas fa-directions' => 'Directions (map,navigation,sign,turn)' ),
				array( 'fas fa-dollar-sign' => 'Dollar Sign ($,cost,dollar-sign,money,price,usd)' ),
				array( 'fas fa-draw-polygon' => 'Draw Polygon (anchors,lines,object,render,shape)' ),
				array( 'fas fa-eye' => 'Eye (look,optic,see,seen,show,sight,views,visible)' ),
				array( 'far fa-eye' => 'Eye (look,optic,see,seen,show,sight,views,visible)' ),
				array( 'fas fa-eye-slash' => 'Eye Slash (blind,hide,show,toggle,unseen,views,visible,visiblity)' ),
				array( 'far fa-eye-slash' => 'Eye Slash (blind,hide,show,toggle,unseen,views,visible,visiblity)' ),
				array( 'fas fa-fighter-jet' => 'fighter-jet (airplane,fast,fly,goose,maverick,plane,quick,top gun,transportation,travel)' ),
				array( 'fas fa-fire' => 'fire (burn,caliente,flame,heat,hot,popular)' ),
				array( 'fas fa-fire-alt' => 'Alternate Fire (burn,caliente,flame,heat,hot,popular)' ),
				array( 'fas fa-fire-extinguisher' => 'fire-extinguisher (burn,caliente,fire fighter,flame,heat,hot,rescue)' ),
				array( 'fas fa-flag' => 'flag (country,notice,notification,notify,pole,report,symbol)' ),
				array( 'far fa-flag' => 'flag (country,notice,notification,notify,pole,report,symbol)' ),
				array( 'fas fa-flag-checkered' => 'flag-checkered (notice,notification,notify,pole,racing,report,symbol)' ),
				array( 'fas fa-flask' => 'Flask (beaker,experimental,labs,science)' ),
				array( 'fas fa-gamepad' => 'Gamepad (arcade,controller,d-pad,joystick,video,video game)' ),
				array( 'fas fa-gavel' => 'Gavel (hammer,judge,law,lawyer,opinion)' ),
				array( 'fas fa-gift' => 'gift (christmas,generosity,giving,holiday,party,present,wrapped,xmas)' ),
				array( 'fas fa-glass-martini' => 'Martini Glass (alcohol,bar,beverage,drink,liquor)' ),
				array( 'fas fa-globe' => 'Globe (all,coordinates,country,earth,global,gps,language,localize,location,map,online,place,planet,translate,travel,world)' ),
				array( 'fas fa-graduation-cap' => 'Graduation Cap (ceremony,college,graduate,learning,school,student)' ),
				array( 'fas fa-h-square' => 'H Square (directions,emergency,hospital,hotel,map)' ),
				array( 'fas fa-heart' => 'Heart (favorite,like,love,relationship,valentine)' ),
				array( 'far fa-heart' => 'Heart (favorite,like,love,relationship,valentine)' ),
				array( 'fas fa-heartbeat' => 'Heartbeat (ekg,electrocardiogram,health,lifeline,vital signs)' ),
				array( 'fas fa-helicopter' => 'Helicopter (airwolf,apache,chopper,flight,fly,travel)' ),
				array( 'fas fa-home' => 'home (abode,building,house,main)' ),
				array( 'fas fa-hospital' => 'hospital (building,emergency room,medical center)' ),
				array( 'far fa-hospital' => 'hospital (building,emergency room,medical center)' ),
				array( 'fas fa-image' => 'Image (album,landscape,photo,picture)' ),
				array( 'far fa-image' => 'Image (album,landscape,photo,picture)' ),
				array( 'fas fa-images' => 'Images (album,landscape,photo,picture)' ),
				array( 'far fa-images' => 'Images (album,landscape,photo,picture)' ),
				array( 'fas fa-industry' => 'Industry (building,factory,industrial,manufacturing,mill,warehouse)' ),
				array( 'fas fa-info' => 'Info (details,help,information,more,support)' ),
				array( 'fas fa-info-circle' => 'Info Circle (details,help,information,more,support)' ),
				array( 'fas fa-key' => 'key (lock,password,private,secret,unlock)' ),
				array( 'fas fa-landmark' => 'Landmark (building,historic,memorable,monument,politics)' ),
				array( 'fas fa-layer-group' => 'Layer Group (arrange,develop,layers,map,stack)' ),
				array( 'fas fa-leaf' => 'leaf (eco,flora,nature,plant,vegan)' ),
				array( 'fas fa-lemon' => 'Lemon (citrus,lemonade,lime,tart)' ),
				array( 'far fa-lemon' => 'Lemon (citrus,lemonade,lime,tart)' ),
				array( 'fas fa-life-ring' => 'Life Ring (coast guard,help,overboard,save,support)' ),
				array( 'far fa-life-ring' => 'Life Ring (coast guard,help,overboard,save,support)' ),
				array( 'fas fa-lightbulb' => 'Lightbulb (energy,idea,inspiration,light)' ),
				array( 'far fa-lightbulb' => 'Lightbulb (energy,idea,inspiration,light)' ),
				array( 'fas fa-location-arrow' => 'location-arrow (address,compass,coordinate,direction,gps,map,navigation,place)' ),
				array( 'fas fa-low-vision' => 'Low Vision (blind,eye,sight)' ),
				array( 'fas fa-magnet' => 'magnet (Attract,lodestone,tool)' ),
				array( 'fas fa-male' => 'Male (human,man,person,profile,user)' ),
				array( 'fas fa-map' => 'Map (address,coordinates,destination,gps,localize,location,map,navigation,paper,pin,place,point of interest,position,route,travel)' ),
				array( 'far fa-map' => 'Map (address,coordinates,destination,gps,localize,location,map,navigation,paper,pin,place,point of interest,position,route,travel)' ),
				array( 'fas fa-map-marker' => 'map-marker (address,coordinates,destination,gps,localize,location,map,navigation,paper,pin,place,point of interest,position,route,travel)' ),
				array( 'fas fa-map-marker-alt' => 'Alternate Map Marker (address,coordinates,destination,gps,localize,location,map,navigation,paper,pin,place,point of interest,position,route,travel)' ),
				array( 'fas fa-map-pin' => 'Map Pin (address,agree,coordinates,destination,gps,localize,location,map,marker,navigation,pin,place,position,travel)' ),
				array( 'fas fa-map-signs' => 'Map Signs (directions,directory,map,signage,wayfinding)' ),
				array( 'fas fa-medkit' => 'medkit (first aid,firstaid,health,help,support)' ),
				array( 'fas fa-money-bill' => 'Money Bill (buy,cash,checkout,money,payment,price,purchase)' ),
				array( 'fas fa-money-bill-alt' => 'Alternate Money Bill (buy,cash,checkout,money,payment,price,purchase)' ),
				array( 'far fa-money-bill-alt' => 'Alternate Money Bill (buy,cash,checkout,money,payment,price,purchase)' ),
				array( 'fas fa-motorcycle' => 'Motorcycle (bike,machine,transportation,vehicle)' ),
				array( 'fas fa-music' => 'Music (lyrics,melody,note,sing,sound)' ),
				array( 'fas fa-newspaper' => 'Newspaper (article,editorial,headline,journal,journalism,news,press)' ),
				array( 'far fa-newspaper' => 'Newspaper (article,editorial,headline,journal,journalism,news,press)' ),
				array( 'fas fa-parking' => 'Parking (auto,car,garage,meter)' ),
				array( 'fas fa-paw' => 'Paw (animal,cat,dog,pet,print)' ),
				array( 'fas fa-phone' => 'Phone (call,earphone,number,support,telephone,voice)' ),
				array( 'fas fa-phone-alt' => 'Alternate Phone (call,earphone,number,support,telephone,voice)' ),
				array( 'fas fa-phone-square' => 'Phone Square (call,earphone,number,support,telephone,voice)' ),
				array( 'fas fa-phone-square-alt' => 'Alternate Phone Square (call,earphone,number,support,telephone,voice)' ),
				array( 'fas fa-phone-volume' => 'Phone Volume (call,earphone,number,sound,support,telephone,voice,volume-control-phone)' ),
				array( 'fas fa-plane' => 'plane (airplane,destination,fly,location,mode,travel,trip)' ),
				array( 'fas fa-plug' => 'Plug (connect,electric,online,power)' ),
				array( 'fas fa-plus' => 'plus (add,create,expand,new,positive,shape)' ),
				array( 'fas fa-plus-square' => 'Plus Square (add,create,expand,new,positive,shape)' ),
				array( 'far fa-plus-square' => 'Plus Square (add,create,expand,new,positive,shape)' ),
				array( 'fas fa-print' => 'print (business,copy,document,office,paper)' ),
				array( 'fas fa-recycle' => 'Recycle (Waste,compost,garbage,reuse,trash)' ),
				array( 'fas fa-restroom' => 'Restroom (bathroom,john,loo,potty,washroom,waste,wc)' ),
				array( 'fas fa-road' => 'road (highway,map,pavement,route,street,travel)' ),
				array( 'fas fa-rocket' => 'rocket (aircraft,app,jet,launch,nasa,space)' ),
				array( 'fas fa-route' => 'Route (directions,navigation,travel)' ),
				array( 'fas fa-search' => 'Search (bigger,enlarge,find,magnify,preview,zoom)' ),
				array( 'fas fa-search-minus' => 'Search Minus (minify,negative,smaller,zoom,zoom out)' ),
				array( 'fas fa-search-plus' => 'Search Plus (bigger,enlarge,magnify,positive,zoom,zoom in)' ),
				array( 'fas fa-ship' => 'Ship (boat,sea,water)' ),
				array( 'fas fa-shoe-prints' => 'Shoe Prints (feet,footprints,steps,walk)' ),
				array( 'fas fa-shopping-bag' => 'Shopping Bag (buy,checkout,grocery,payment,purchase)' ),
				array( 'fas fa-shopping-basket' => 'Shopping Basket (buy,checkout,grocery,payment,purchase)' ),
				array( 'fas fa-shopping-cart' => 'shopping-cart (buy,checkout,grocery,payment,purchase)' ),
				array( 'fas fa-shower' => 'Shower (bath,clean,faucet,water)' ),
				array( 'fas fa-snowplow' => 'Snowplow (clean up,cold,road,storm,winter)' ),
				array( 'fas fa-street-view' => 'Street View (directions,location,map,navigation)' ),
				array( 'fas fa-subway' => 'Subway (machine,railway,train,transportation,vehicle)' ),
				array( 'fas fa-suitcase' => 'Suitcase (baggage,luggage,move,suitcase,travel,trip)' ),
				array( 'fas fa-tag' => 'tag (discount,label,price,shopping)' ),
				array( 'fas fa-tags' => 'tags (discount,label,price,shopping)' ),
				array( 'fas fa-taxi' => 'Taxi (cab,cabbie,car,car service,lyft,machine,transportation,travel,uber,vehicle)' ),
				array( 'fas fa-thumbtack' => 'Thumbtack (coordinates,location,marker,pin,thumb-tack)' ),
				array( 'fas fa-ticket-alt' => 'Alternate Ticket (movie,pass,support,ticket)' ),
				array( 'fas fa-tint' => 'tint (color,drop,droplet,raindrop,waterdrop)' ),
				array( 'fas fa-traffic-light' => 'Traffic Light (direction,road,signal,travel)' ),
				array( 'fas fa-train' => 'Train (bullet,commute,locomotive,railway,subway)' ),
				array( 'fas fa-tram' => 'Tram (crossing,machine,mountains,seasonal,transportation)' ),
				array( 'fas fa-tree' => 'Tree (bark,fall,flora,forest,nature,plant,seasonal)' ),
				array( 'fas fa-trophy' => 'trophy (achievement,award,cup,game,winner)' ),
				array( 'fas fa-truck' => 'truck (cargo,delivery,shipping,vehicle)' ),
				array( 'fas fa-tty' => 'TTY (communication,deaf,telephone,teletypewriter,text)' ),
				array( 'fas fa-umbrella' => 'Umbrella (protection,rain,storm,wet)' ),
				array( 'fas fa-university' => 'University (bank,building,college,higher education - students,institution)' ),
				array( 'fas fa-utensil-spoon' => 'Utensil Spoon (cutlery,dining,scoop,silverware,spoon)' ),
				array( 'fas fa-utensils' => 'Utensils (cutlery,dining,dinner,eat,food,fork,knife,restaurant)' ),
				array( 'fas fa-wheelchair' => 'Wheelchair (accessible,handicap,person)' ),
				array( 'fas fa-wifi' => 'WiFi (connection,hotspot,internet,network,wireless)' ),
				array( 'fas fa-wine-glass' => 'Wine Glass (alcohol,beverage,cabernet,drink,grapes,merlot,sauvignon)' ),
				array( 'fas fa-wrench' => 'Wrench (construction,fix,mechanic,plumbing,settings,spanner,tool,update)' ),
			),
			'Maritime'            => array(
				array( 'fas fa-anchor' => 'Anchor (berth,boat,dock,embed,link,maritime,moor,secure)' ),
				array( 'fas fa-binoculars' => 'Binoculars (glasses,magnify,scenic,spyglass,view)' ),
				array( 'fas fa-compass' => 'Compass (directions,directory,location,menu,navigation,safari,travel)' ),
				array( 'far fa-compass' => 'Compass (directions,directory,location,menu,navigation,safari,travel)' ),
				array( 'fas fa-dharmachakra' => 'Dharmachakra (buddhism,buddhist,wheel of dharma)' ),
				array( 'fas fa-frog' => 'Frog (amphibian,bullfrog,fauna,hop,kermit,kiss,prince,ribbit,toad,wart)' ),
				array( 'fas fa-ship' => 'Ship (boat,sea,water)' ),
				array( 'fas fa-skull-crossbones' => 'Skull & Crossbones (Dungeons & Dragons,alert,bones,d&d,danger,dead,deadly,death,dnd,fantasy,halloween,holiday,jolly-roger,pirate,poison,skeleton,warning)' ),
				array( 'fas fa-swimmer' => 'Swimmer (athlete,head,man,olympics,person,pool,water)' ),
				array( 'fas fa-water' => 'Water (lake,liquid,ocean,sea,swim,wet)' ),
				array( 'fas fa-wind' => 'Wind (air,blow,breeze,fall,seasonal,weather)' ),
			),
			'Marketing'           => array(
				array( 'fas fa-ad' => 'Ad (advertisement,media,newspaper,promotion,publicity)' ),
				array( 'fas fa-bullhorn' => 'bullhorn (announcement,broadcast,louder,megaphone,share)' ),
				array( 'fas fa-bullseye' => 'Bullseye (archery,goal,objective,target)' ),
				array( 'fas fa-comment-dollar' => 'Comment Dollar (bubble,chat,commenting,conversation,feedback,message,money,note,notification,pay,sms,speech,spend,texting,transfer)' ),
				array( 'fas fa-comments-dollar' => 'Comments Dollar (bubble,chat,commenting,conversation,feedback,message,money,note,notification,pay,sms,speech,spend,texting,transfer)' ),
				array( 'fas fa-envelope-open-text' => 'Envelope Open-text (e-mail,email,letter,mail,message,notification,support)' ),
				array( 'fas fa-funnel-dollar' => 'Funnel Dollar (filter,money,options,separate,sort)' ),
				array( 'fas fa-lightbulb' => 'Lightbulb (energy,idea,inspiration,light)' ),
				array( 'far fa-lightbulb' => 'Lightbulb (energy,idea,inspiration,light)' ),
				array( 'fas fa-mail-bulk' => 'Mail Bulk (archive,envelope,letter,post office,postal,postcard,send,stamp,usps)' ),
				array( 'fas fa-poll' => 'Poll (results,survey,trend,vote,voting)' ),
				array( 'fas fa-poll-h' => 'Poll H (results,survey,trend,vote,voting)' ),
				array( 'fas fa-search-dollar' => 'Search Dollar (bigger,enlarge,find,magnify,money,preview,zoom)' ),
				array( 'fas fa-search-location' => 'Search Location (bigger,enlarge,find,magnify,preview,zoom)' ),
			),
			'Mathematics'         => array(
				array( 'fas fa-calculator' => 'Calculator (abacus,addition,arithmetic,counting,math,multiplication,subtraction)' ),
				array( 'fas fa-divide' => 'Divide (arithmetic,calculus,division,math)' ),
				array( 'fas fa-equals' => 'Equals (arithmetic,even,match,math)' ),
				array( 'fas fa-greater-than' => 'Greater Than (arithmetic,compare,math)' ),
				array( 'fas fa-greater-than-equal' => 'Greater Than Equal To (arithmetic,compare,math)' ),
				array( 'fas fa-infinity' => 'Infinity (eternity,forever,math)' ),
				array( 'fas fa-less-than' => 'Less Than (arithmetic,compare,math)' ),
				array( 'fas fa-less-than-equal' => 'Less Than Equal To (arithmetic,compare,math)' ),
				array( 'fas fa-minus' => 'minus (collapse,delete,hide,minify,negative,remove,trash)' ),
				array( 'fas fa-not-equal' => 'Not Equal (arithmetic,compare,math)' ),
				array( 'fas fa-percentage' => 'Percentage (discount,fraction,proportion,rate,ratio)' ),
				array( 'fas fa-plus' => 'plus (add,create,expand,new,positive,shape)' ),
				array( 'fas fa-square-root-alt' => 'Alternate Square Root (arithmetic,calculus,division,math)' ),
				array( 'fas fa-subscript' => 'subscript (edit,font,format,text,type)' ),
				array( 'fas fa-superscript' => 'superscript (edit,exponential,font,format,text,type)' ),
				array( 'fas fa-times' => 'Times (close,cross,error,exit,incorrect,notice,notification,notify,problem,wrong,x)' ),
				array( 'fas fa-wave-square' => 'Square Wave (frequency,pulse,signal)' ),
			),
			'Medical'             => array(
				array( 'fas fa-allergies' => 'Allergies (allergy,freckles,hand,hives,pox,skin,spots)' ),
				array( 'fas fa-ambulance' => 'ambulance (emergency,emt,er,help,hospital,support,vehicle)' ),
				array( 'fas fa-band-aid' => 'Band-Aid (bandage,boo boo,first aid,ouch)' ),
				array( 'fas fa-biohazard' => 'Biohazard (danger,dangerous,hazmat,medical,radioactive,toxic,waste,zombie)' ),
				array( 'fas fa-bone' => 'Bone (calcium,dog,skeletal,skeleton,tibia)' ),
				array( 'fas fa-bong' => 'Bong (aparatus,cannabis,marijuana,pipe,smoke,smoking)' ),
				array( 'fas fa-book-medical' => 'Medical Book (diary,documentation,health,history,journal,library,read,record)' ),
				array( 'fas fa-brain' => 'Brain (cerebellum,gray matter,intellect,medulla oblongata,mind,noodle,wit)' ),
				array( 'fas fa-briefcase-medical' => 'Medical Briefcase (doctor,emt,first aid,health)' ),
				array( 'fas fa-burn' => 'Burn (caliente,energy,fire,flame,gas,heat,hot)' ),
				array( 'fas fa-cannabis' => 'Cannabis (bud,chronic,drugs,endica,endo,ganja,marijuana,mary jane,pot,reefer,sativa,spliff,weed,whacky-tabacky)' ),
				array( 'fas fa-capsules' => 'Capsules (drugs,medicine,pills,prescription)' ),
				array( 'fas fa-clinic-medical' => 'Medical Clinic (doctor,general practitioner,hospital,infirmary,medicine,office,outpatient)' ),
				array( 'fas fa-comment-medical' => 'Alternate Medical Chat (advice,bubble,chat,commenting,conversation,diagnose,feedback,message,note,notification,prescription,sms,speech,texting)' ),
				array( 'fas fa-crutch' => 'Crutch (cane,injury,mobility,wheelchair)' ),
				array( 'fas fa-diagnoses' => 'Diagnoses (analyze,detect,diagnosis,examine,medicine)' ),
				array( 'fas fa-dna' => 'DNA (double helix,genetic,helix,molecule,protein)' ),
				array( 'fas fa-file-medical' => 'Medical File (document,health,history,prescription,record)' ),
				array( 'fas fa-file-medical-alt' => 'Alternate Medical File (document,health,history,prescription,record)' ),
				array( 'fas fa-file-prescription' => 'File Prescription (document,drugs,medical,medicine,rx)' ),
				array( 'fas fa-first-aid' => 'First Aid (emergency,emt,health,medical,rescue)' ),
				array( 'fas fa-heart' => 'Heart (favorite,like,love,relationship,valentine)' ),
				array( 'far fa-heart' => 'Heart (favorite,like,love,relationship,valentine)' ),
				array( 'fas fa-heartbeat' => 'Heartbeat (ekg,electrocardiogram,health,lifeline,vital signs)' ),
				array( 'fas fa-hospital' => 'hospital (building,emergency room,medical center)' ),
				array( 'far fa-hospital' => 'hospital (building,emergency room,medical center)' ),
				array( 'fas fa-hospital-alt' => 'Alternate Hospital (building,emergency room,medical center)' ),
				array( 'fas fa-hospital-symbol' => 'Hospital Symbol (clinic,emergency,map)' ),
				array( 'fas fa-id-card-alt' => 'Alternate Identification Card (contact,demographics,document,identification,issued,profile)' ),
				array( 'fas fa-joint' => 'Joint (blunt,cannabis,doobie,drugs,marijuana,roach,smoke,smoking,spliff)' ),
				array( 'fas fa-laptop-medical' => 'Laptop Medical (computer,device,ehr,electronic health records,history)' ),
				array( 'fas fa-microscope' => 'Microscope (electron,lens,optics,science,shrink)' ),
				array( 'fas fa-mortar-pestle' => 'Mortar Pestle (crush,culinary,grind,medical,mix,pharmacy,prescription,spices)' ),
				array( 'fas fa-notes-medical' => 'Medical Notes (clipboard,doctor,ehr,health,history,records)' ),
				array( 'fas fa-pager' => 'Pager (beeper,cellphone,communication)' ),
				array( 'fas fa-pills' => 'Pills (drugs,medicine,prescription,tablets)' ),
				array( 'fas fa-plus' => 'plus (add,create,expand,new,positive,shape)' ),
				array( 'fas fa-poop' => 'Poop (crap,poop,shit,smile,turd)' ),
				array( 'fas fa-prescription' => 'Prescription (drugs,medical,medicine,pharmacy,rx)' ),
				array( 'fas fa-prescription-bottle' => 'Prescription Bottle (drugs,medical,medicine,pharmacy,rx)' ),
				array( 'fas fa-prescription-bottle-alt' => 'Alternate Prescription Bottle (drugs,medical,medicine,pharmacy,rx)' ),
				array( 'fas fa-procedures' => 'Procedures (EKG,bed,electrocardiogram,health,hospital,life,patient,vital)' ),
				array( 'fas fa-radiation' => 'Radiation (danger,dangerous,deadly,hazard,nuclear,radioactive,warning)' ),
				array( 'fas fa-radiation-alt' => 'Alternate Radiation (danger,dangerous,deadly,hazard,nuclear,radioactive,warning)' ),
				array( 'fas fa-smoking' => 'Smoking (cancer,cigarette,nicotine,smoking status,tobacco)' ),
				array( 'fas fa-smoking-ban' => 'Smoking Ban (ban,cancel,no smoking,non-smoking)' ),
				array( 'fas fa-star-of-life' => 'Star of Life (doctor,emt,first aid,health,medical)' ),
				array( 'fas fa-stethoscope' => 'Stethoscope (diagnosis,doctor,general practitioner,hospital,infirmary,medicine,office,outpatient)' ),
				array( 'fas fa-syringe' => 'Syringe (doctor,immunizations,medical,needle)' ),
				array( 'fas fa-tablets' => 'Tablets (drugs,medicine,pills,prescription)' ),
				array( 'fas fa-teeth' => 'Teeth (bite,dental,dentist,gums,mouth,smile,tooth)' ),
				array( 'fas fa-teeth-open' => 'Teeth Open (dental,dentist,gums bite,mouth,smile,tooth)' ),
				array( 'fas fa-thermometer' => 'Thermometer (mercury,status,temperature)' ),
				array( 'fas fa-tooth' => 'Tooth (bicuspid,dental,dentist,molar,mouth,teeth)' ),
				array( 'fas fa-user-md' => 'Doctor (job,medical,nurse,occupation,physician,profile,surgeon)' ),
				array( 'fas fa-user-nurse' => 'Nurse (doctor,midwife,practitioner,surgeon)' ),
				array( 'fas fa-vial' => 'Vial (experiment,lab,sample,science,test,test tube)' ),
				array( 'fas fa-vials' => 'Vials (experiment,lab,sample,science,test,test tube)' ),
				array( 'fas fa-weight' => 'Weight (health,measurement,scale,weight)' ),
				array( 'fas fa-x-ray' => 'X-Ray (health,medical,radiological images,radiology,skeleton)' ),
			),
			'Moving'              => array(
				array( 'fas fa-archive' => 'Archive (box,package,save,storage)' ),
				array( 'fas fa-box-open' => 'Box Open (archive,container,package,storage,unpack)' ),
				array( 'fas fa-couch' => 'Couch (chair,cushion,furniture,relax,sofa)' ),
				array( 'fas fa-dolly' => 'Dolly (carry,shipping,transport)' ),
				array( 'fas fa-people-carry' => 'People Carry (box,carry,fragile,help,movers,package)' ),
				array( 'fas fa-route' => 'Route (directions,navigation,travel)' ),
				array( 'fas fa-sign' => 'Sign (directions,real estate,signage,wayfinding)' ),
				array( 'fas fa-suitcase' => 'Suitcase (baggage,luggage,move,suitcase,travel,trip)' ),
				array( 'fas fa-tape' => 'Tape (design,package,sticky)' ),
				array( 'fas fa-truck-loading' => 'Truck Loading (box,cargo,delivery,inventory,moving,rental,vehicle)' ),
				array( 'fas fa-truck-moving' => 'Truck Moving (cargo,inventory,rental,vehicle)' ),
				array( 'fas fa-wine-glass' => 'Wine Glass (alcohol,beverage,cabernet,drink,grapes,merlot,sauvignon)' ),
			),
			'Music'               => array(
				array( 'fas fa-drum' => 'Drum (instrument,music,percussion,snare,sound)' ),
				array( 'fas fa-drum-steelpan' => 'Drum Steelpan (calypso,instrument,music,percussion,reggae,snare,sound,steel,tropical)' ),
				array( 'fas fa-file-audio' => 'Audio File (document,mp3,music,page,play,sound)' ),
				array( 'far fa-file-audio' => 'Audio File (document,mp3,music,page,play,sound)' ),
				array( 'fas fa-guitar' => 'Guitar (acoustic,instrument,music,rock,rock and roll,song,strings)' ),
				array( 'fas fa-headphones' => 'headphones (audio,listen,music,sound,speaker)' ),
				array( 'fas fa-headphones-alt' => 'Alternate Headphones (audio,listen,music,sound,speaker)' ),
				array( 'fas fa-microphone' => 'microphone (audio,podcast,record,sing,sound,voice)' ),
				array( 'fas fa-microphone-alt' => 'Alternate Microphone (audio,podcast,record,sing,sound,voice)' ),
				array( 'fas fa-microphone-alt-slash' => 'Alternate Microphone Slash (audio,disable,mute,podcast,record,sing,sound,voice)' ),
				array( 'fas fa-microphone-slash' => 'Microphone Slash (audio,disable,mute,podcast,record,sing,sound,voice)' ),
				array( 'fas fa-music' => 'Music (lyrics,melody,note,sing,sound)' ),
				array( 'fab fa-napster' => 'Napster' ),
				array( 'fas fa-play' => 'play (audio,music,playing,sound,start,video)' ),
				array( 'fas fa-record-vinyl' => 'Record Vinyl (LP,album,analog,music,phonograph,sound)' ),
				array( 'fas fa-sliders-h' => 'Horizontal Sliders (adjust,settings,sliders,toggle)' ),
				array( 'fab fa-soundcloud' => 'SoundCloud' ),
				array( 'fab fa-spotify' => 'Spotify' ),
				array( 'fas fa-volume-down' => 'Volume Down (audio,lower,music,quieter,sound,speaker)' ),
				array( 'fas fa-volume-mute' => 'Volume Mute (audio,music,quiet,sound,speaker)' ),
				array( 'fas fa-volume-off' => 'Volume Off (audio,ban,music,mute,quiet,silent,sound)' ),
				array( 'fas fa-volume-up' => 'Volume Up (audio,higher,louder,music,sound,speaker)' ),
			),
			'Objects'             => array(
				array( 'fas fa-ambulance' => 'ambulance (emergency,emt,er,help,hospital,support,vehicle)' ),
				array( 'fas fa-anchor' => 'Anchor (berth,boat,dock,embed,link,maritime,moor,secure)' ),
				array( 'fas fa-archive' => 'Archive (box,package,save,storage)' ),
				array( 'fas fa-award' => 'Award (honor,praise,prize,recognition,ribbon,trophy)' ),
				array( 'fas fa-baby-carriage' => 'Baby Carriage (buggy,carrier,infant,push,stroller,transportation,walk,wheels)' ),
				array( 'fas fa-balance-scale' => 'Balance Scale (balanced,justice,legal,measure,weight)' ),
				array( 'fas fa-balance-scale-left' => 'Balance Scale (Left-Weighted) (justice,legal,measure,unbalanced,weight)' ),
				array( 'fas fa-balance-scale-right' => 'Balance Scale (Right-Weighted) (justice,legal,measure,unbalanced,weight)' ),
				array( 'fas fa-bath' => 'Bath (clean,shower,tub,wash)' ),
				array( 'fas fa-bed' => 'Bed (lodging,rest,sleep,travel)' ),
				array( 'fas fa-beer' => 'beer (alcohol,ale,bar,beverage,brewery,drink,lager,liquor,mug,stein)' ),
				array( 'fas fa-bell' => 'bell (alarm,alert,chime,notification,reminder)' ),
				array( 'far fa-bell' => 'bell (alarm,alert,chime,notification,reminder)' ),
				array( 'fas fa-bicycle' => 'Bicycle (bike,gears,pedal,transportation,vehicle)' ),
				array( 'fas fa-binoculars' => 'Binoculars (glasses,magnify,scenic,spyglass,view)' ),
				array( 'fas fa-birthday-cake' => 'Birthday Cake (anniversary,bakery,candles,celebration,dessert,frosting,holiday,party,pastry)' ),
				array( 'fas fa-blender' => 'Blender (cocktail,milkshake,mixer,puree,smoothie)' ),
				array( 'fas fa-bomb' => 'Bomb (error,explode,fuse,grenade,warning)' ),
				array( 'fas fa-book' => 'book (diary,documentation,journal,library,read)' ),
				array( 'fas fa-book-dead' => 'Book of the Dead (Dungeons & Dragons,crossbones,d&d,dark arts,death,dnd,documentation,evil,fantasy,halloween,holiday,necronomicon,read,skull,spell)' ),
				array( 'fas fa-bookmark' => 'bookmark (favorite,marker,read,remember,save)' ),
				array( 'far fa-bookmark' => 'bookmark (favorite,marker,read,remember,save)' ),
				array( 'fas fa-briefcase' => 'Briefcase (bag,business,luggage,office,work)' ),
				array( 'fas fa-broadcast-tower' => 'Broadcast Tower (airwaves,antenna,radio,reception,waves)' ),
				array( 'fas fa-bug' => 'Bug (beetle,error,insect,report)' ),
				array( 'fas fa-building' => 'Building (apartment,business,city,company,office,work)' ),
				array( 'far fa-building' => 'Building (apartment,business,city,company,office,work)' ),
				array( 'fas fa-bullhorn' => 'bullhorn (announcement,broadcast,louder,megaphone,share)' ),
				array( 'fas fa-bullseye' => 'Bullseye (archery,goal,objective,target)' ),
				array( 'fas fa-bus' => 'Bus (public transportation,transportation,travel,vehicle)' ),
				array( 'fas fa-calculator' => 'Calculator (abacus,addition,arithmetic,counting,math,multiplication,subtraction)' ),
				array( 'fas fa-calendar' => 'Calendar (calendar-o,date,event,schedule,time,when)' ),
				array( 'far fa-calendar' => 'Calendar (calendar-o,date,event,schedule,time,when)' ),
				array( 'fas fa-calendar-alt' => 'Alternate Calendar (calendar,date,event,schedule,time,when)' ),
				array( 'far fa-calendar-alt' => 'Alternate Calendar (calendar,date,event,schedule,time,when)' ),
				array( 'fas fa-camera' => 'camera (image,lens,photo,picture,record,shutter,video)' ),
				array( 'fas fa-camera-retro' => 'Retro Camera (image,lens,photo,picture,record,shutter,video)' ),
				array( 'fas fa-candy-cane' => 'Candy Cane (candy,christmas,holiday,mint,peppermint,striped,xmas)' ),
				array( 'fas fa-car' => 'Car (auto,automobile,sedan,transportation,travel,vehicle)' ),
				array( 'fas fa-carrot' => 'Carrot (bugs bunny,orange,vegan,vegetable)' ),
				array( 'fas fa-church' => 'Church (building,cathedral,chapel,community,religion)' ),
				array( 'fas fa-clipboard' => 'Clipboard (copy,notes,paste,record)' ),
				array( 'far fa-clipboard' => 'Clipboard (copy,notes,paste,record)' ),
				array( 'fas fa-cloud' => 'Cloud (atmosphere,fog,overcast,save,upload,weather)' ),
				array( 'fas fa-coffee' => 'Coffee (beverage,breakfast,cafe,drink,fall,morning,mug,seasonal,tea)' ),
				array( 'fas fa-cog' => 'cog (gear,mechanical,settings,sprocket,wheel)' ),
				array( 'fas fa-cogs' => 'cogs (gears,mechanical,settings,sprocket,wheel)' ),
				array( 'fas fa-compass' => 'Compass (directions,directory,location,menu,navigation,safari,travel)' ),
				array( 'far fa-compass' => 'Compass (directions,directory,location,menu,navigation,safari,travel)' ),
				array( 'fas fa-cookie' => 'Cookie (baked good,chips,chocolate,eat,snack,sweet,treat)' ),
				array( 'fas fa-cookie-bite' => 'Cookie Bite (baked good,bitten,chips,chocolate,eat,snack,sweet,treat)' ),
				array( 'fas fa-copy' => 'Copy (clone,duplicate,file,files-o,paper,paste)' ),
				array( 'far fa-copy' => 'Copy (clone,duplicate,file,files-o,paper,paste)' ),
				array( 'fas fa-cube' => 'Cube (3d,block,dice,package,square,tesseract)' ),
				array( 'fas fa-cubes' => 'Cubes (3d,block,dice,package,pyramid,square,stack,tesseract)' ),
				array( 'fas fa-cut' => 'Cut (clip,scissors,snip)' ),
				array( 'fas fa-dice' => 'Dice (chance,gambling,game,roll)' ),
				array( 'fas fa-dice-d20' => 'Dice D20 (Dungeons & Dragons,chance,d&d,dnd,fantasy,gambling,game,roll)' ),
				array( 'fas fa-dice-d6' => 'Dice D6 (Dungeons & Dragons,chance,d&d,dnd,fantasy,gambling,game,roll)' ),
				array( 'fas fa-dice-five' => 'Dice Five (chance,gambling,game,roll)' ),
				array( 'fas fa-dice-four' => 'Dice Four (chance,gambling,game,roll)' ),
				array( 'fas fa-dice-one' => 'Dice One (chance,gambling,game,roll)' ),
				array( 'fas fa-dice-six' => 'Dice Six (chance,gambling,game,roll)' ),
				array( 'fas fa-dice-three' => 'Dice Three (chance,gambling,game,roll)' ),
				array( 'fas fa-dice-two' => 'Dice Two (chance,gambling,game,roll)' ),
				array( 'fas fa-digital-tachograph' => 'Digital Tachograph (data,distance,speed,tachometer)' ),
				array( 'fas fa-door-closed' => 'Door Closed (enter,exit,locked)' ),
				array( 'fas fa-door-open' => 'Door Open (enter,exit,welcome)' ),
				array( 'fas fa-drum' => 'Drum (instrument,music,percussion,snare,sound)' ),
				array( 'fas fa-drum-steelpan' => 'Drum Steelpan (calypso,instrument,music,percussion,reggae,snare,sound,steel,tropical)' ),
				array( 'fas fa-envelope' => 'Envelope (e-mail,email,letter,mail,message,notification,support)' ),
				array( 'far fa-envelope' => 'Envelope (e-mail,email,letter,mail,message,notification,support)' ),
				array( 'fas fa-envelope-open' => 'Envelope Open (e-mail,email,letter,mail,message,notification,support)' ),
				array( 'far fa-envelope-open' => 'Envelope Open (e-mail,email,letter,mail,message,notification,support)' ),
				array( 'fas fa-eraser' => 'eraser (art,delete,remove,rubber)' ),
				array( 'fas fa-eye' => 'Eye (look,optic,see,seen,show,sight,views,visible)' ),
				array( 'far fa-eye' => 'Eye (look,optic,see,seen,show,sight,views,visible)' ),
				array( 'fas fa-eye-dropper' => 'Eye Dropper (beaker,clone,color,copy,eyedropper,pipette)' ),
				array( 'fas fa-fax' => 'Fax (business,communicate,copy,facsimile,send)' ),
				array( 'fas fa-feather' => 'Feather (bird,light,plucked,quill,write)' ),
				array( 'fas fa-feather-alt' => 'Alternate Feather (bird,light,plucked,quill,write)' ),
				array( 'fas fa-fighter-jet' => 'fighter-jet (airplane,fast,fly,goose,maverick,plane,quick,top gun,transportation,travel)' ),
				array( 'fas fa-file' => 'File (document,new,page,pdf,resume)' ),
				array( 'far fa-file' => 'File (document,new,page,pdf,resume)' ),
				array( 'fas fa-file-alt' => 'Alternate File (document,file-text,invoice,new,page,pdf)' ),
				array( 'far fa-file-alt' => 'Alternate File (document,file-text,invoice,new,page,pdf)' ),
				array( 'fas fa-file-prescription' => 'File Prescription (document,drugs,medical,medicine,rx)' ),
				array( 'fas fa-film' => 'Film (cinema,movie,strip,video)' ),
				array( 'fas fa-fire' => 'fire (burn,caliente,flame,heat,hot,popular)' ),
				array( 'fas fa-fire-alt' => 'Alternate Fire (burn,caliente,flame,heat,hot,popular)' ),
				array( 'fas fa-fire-extinguisher' => 'fire-extinguisher (burn,caliente,fire fighter,flame,heat,hot,rescue)' ),
				array( 'fas fa-flag' => 'flag (country,notice,notification,notify,pole,report,symbol)' ),
				array( 'far fa-flag' => 'flag (country,notice,notification,notify,pole,report,symbol)' ),
				array( 'fas fa-flag-checkered' => 'flag-checkered (notice,notification,notify,pole,racing,report,symbol)' ),
				array( 'fas fa-flask' => 'Flask (beaker,experimental,labs,science)' ),
				array( 'fas fa-futbol' => 'Futbol (ball,football,mls,soccer)' ),
				array( 'far fa-futbol' => 'Futbol (ball,football,mls,soccer)' ),
				array( 'fas fa-gamepad' => 'Gamepad (arcade,controller,d-pad,joystick,video,video game)' ),
				array( 'fas fa-gavel' => 'Gavel (hammer,judge,law,lawyer,opinion)' ),
				array( 'fas fa-gem' => 'Gem (diamond,jewelry,sapphire,stone,treasure)' ),
				array( 'far fa-gem' => 'Gem (diamond,jewelry,sapphire,stone,treasure)' ),
				array( 'fas fa-gift' => 'gift (christmas,generosity,giving,holiday,party,present,wrapped,xmas)' ),
				array( 'fas fa-gifts' => 'Gifts (christmas,generosity,giving,holiday,party,present,wrapped,xmas)' ),
				array( 'fas fa-glass-cheers' => 'Glass Cheers (alcohol,bar,beverage,celebration,champagne,clink,drink,holiday,new year\'s eve,party,toast)' ),
				array( 'fas fa-glass-martini' => 'Martini Glass (alcohol,bar,beverage,drink,liquor)' ),
				array( 'fas fa-glass-whiskey' => 'Glass Whiskey (alcohol,bar,beverage,bourbon,drink,liquor,neat,rye,scotch,whisky)' ),
				array( 'fas fa-glasses' => 'Glasses (hipster,nerd,reading,sight,spectacles,vision)' ),
				array( 'fas fa-globe' => 'Globe (all,coordinates,country,earth,global,gps,language,localize,location,map,online,place,planet,translate,travel,world)' ),
				array( 'fas fa-graduation-cap' => 'Graduation Cap (ceremony,college,graduate,learning,school,student)' ),
				array( 'fas fa-guitar' => 'Guitar (acoustic,instrument,music,rock,rock and roll,song,strings)' ),
				array( 'fas fa-hat-wizard' => 'Wizard\'s Hat (Dungeons & Dragons,accessory,buckle,clothing,d&d,dnd,fantasy,halloween,head,holiday,mage,magic,pointy,witch)' ),
				array( 'fas fa-hdd' => 'HDD (cpu,hard drive,harddrive,machine,save,storage)' ),
				array( 'far fa-hdd' => 'HDD (cpu,hard drive,harddrive,machine,save,storage)' ),
				array( 'fas fa-headphones' => 'headphones (audio,listen,music,sound,speaker)' ),
				array( 'fas fa-headphones-alt' => 'Alternate Headphones (audio,listen,music,sound,speaker)' ),
				array( 'fas fa-headset' => 'Headset (audio,gamer,gaming,listen,live chat,microphone,shot caller,sound,support,telemarketer)' ),
				array( 'fas fa-heart' => 'Heart (favorite,like,love,relationship,valentine)' ),
				array( 'far fa-heart' => 'Heart (favorite,like,love,relationship,valentine)' ),
				array( 'fas fa-heart-broken' => 'Heart Broken (breakup,crushed,dislike,dumped,grief,love,lovesick,relationship,sad)' ),
				array( 'fas fa-helicopter' => 'Helicopter (airwolf,apache,chopper,flight,fly,travel)' ),
				array( 'fas fa-highlighter' => 'Highlighter (edit,marker,sharpie,update,write)' ),
				array( 'fas fa-holly-berry' => 'Holly Berry (catwoman,christmas,decoration,flora,halle,holiday,ororo munroe,plant,storm,xmas)' ),
				array( 'fas fa-home' => 'home (abode,building,house,main)' ),
				array( 'fas fa-hospital' => 'hospital (building,emergency room,medical center)' ),
				array( 'far fa-hospital' => 'hospital (building,emergency room,medical center)' ),
				array( 'fas fa-hourglass' => 'Hourglass (hour,minute,sand,stopwatch,time)' ),
				array( 'far fa-hourglass' => 'Hourglass (hour,minute,sand,stopwatch,time)' ),
				array( 'fas fa-igloo' => 'Igloo (dome,dwelling,eskimo,home,house,ice,snow)' ),
				array( 'fas fa-image' => 'Image (album,landscape,photo,picture)' ),
				array( 'far fa-image' => 'Image (album,landscape,photo,picture)' ),
				array( 'fas fa-images' => 'Images (album,landscape,photo,picture)' ),
				array( 'far fa-images' => 'Images (album,landscape,photo,picture)' ),
				array( 'fas fa-industry' => 'Industry (building,factory,industrial,manufacturing,mill,warehouse)' ),
				array( 'fas fa-key' => 'key (lock,password,private,secret,unlock)' ),
				array( 'fas fa-keyboard' => 'Keyboard (accessory,edit,input,text,type,write)' ),
				array( 'far fa-keyboard' => 'Keyboard (accessory,edit,input,text,type,write)' ),
				array( 'fas fa-laptop' => 'Laptop (computer,cpu,dell,demo,device,mac,macbook,machine,pc)' ),
				array( 'fas fa-leaf' => 'leaf (eco,flora,nature,plant,vegan)' ),
				array( 'fas fa-lemon' => 'Lemon (citrus,lemonade,lime,tart)' ),
				array( 'far fa-lemon' => 'Lemon (citrus,lemonade,lime,tart)' ),
				array( 'fas fa-life-ring' => 'Life Ring (coast guard,help,overboard,save,support)' ),
				array( 'far fa-life-ring' => 'Life Ring (coast guard,help,overboard,save,support)' ),
				array( 'fas fa-lightbulb' => 'Lightbulb (energy,idea,inspiration,light)' ),
				array( 'far fa-lightbulb' => 'Lightbulb (energy,idea,inspiration,light)' ),
				array( 'fas fa-lock' => 'lock (admin,lock,open,password,private,protect,security)' ),
				array( 'fas fa-lock-open' => 'Lock Open (admin,lock,open,password,private,protect,security)' ),
				array( 'fas fa-magic' => 'magic (autocomplete,automatic,mage,magic,spell,wand,witch,wizard)' ),
				array( 'fas fa-magnet' => 'magnet (Attract,lodestone,tool)' ),
				array( 'fas fa-map' => 'Map (address,coordinates,destination,gps,localize,location,map,navigation,paper,pin,place,point of interest,position,route,travel)' ),
				array( 'far fa-map' => 'Map (address,coordinates,destination,gps,localize,location,map,navigation,paper,pin,place,point of interest,position,route,travel)' ),
				array( 'fas fa-map-marker' => 'map-marker (address,coordinates,destination,gps,localize,location,map,navigation,paper,pin,place,point of interest,position,route,travel)' ),
				array( 'fas fa-map-marker-alt' => 'Alternate Map Marker (address,coordinates,destination,gps,localize,location,map,navigation,paper,pin,place,point of interest,position,route,travel)' ),
				array( 'fas fa-map-pin' => 'Map Pin (address,agree,coordinates,destination,gps,localize,location,map,marker,navigation,pin,place,position,travel)' ),
				array( 'fas fa-map-signs' => 'Map Signs (directions,directory,map,signage,wayfinding)' ),
				array( 'fas fa-marker' => 'Marker (design,edit,sharpie,update,write)' ),
				array( 'fas fa-medal' => 'Medal (award,ribbon,star,trophy)' ),
				array( 'fas fa-medkit' => 'medkit (first aid,firstaid,health,help,support)' ),
				array( 'fas fa-memory' => 'Memory (DIMM,RAM,hardware,storage,technology)' ),
				array( 'fas fa-microchip' => 'Microchip (cpu,hardware,processor,technology)' ),
				array( 'fas fa-microphone' => 'microphone (audio,podcast,record,sing,sound,voice)' ),
				array( 'fas fa-microphone-alt' => 'Alternate Microphone (audio,podcast,record,sing,sound,voice)' ),
				array( 'fas fa-mitten' => 'Mitten (clothing,cold,glove,hands,knitted,seasonal,warmth)' ),
				array( 'fas fa-mobile' => 'Mobile Phone (apple,call,cell phone,cellphone,device,iphone,number,screen,telephone)' ),
				array( 'fas fa-mobile-alt' => 'Alternate Mobile (apple,call,cell phone,cellphone,device,iphone,number,screen,telephone)' ),
				array( 'fas fa-money-bill' => 'Money Bill (buy,cash,checkout,money,payment,price,purchase)' ),
				array( 'fas fa-money-bill-alt' => 'Alternate Money Bill (buy,cash,checkout,money,payment,price,purchase)' ),
				array( 'far fa-money-bill-alt' => 'Alternate Money Bill (buy,cash,checkout,money,payment,price,purchase)' ),
				array( 'fas fa-money-check' => 'Money Check (bank check,buy,checkout,cheque,money,payment,price,purchase)' ),
				array( 'fas fa-money-check-alt' => 'Alternate Money Check (bank check,buy,checkout,cheque,money,payment,price,purchase)' ),
				array( 'fas fa-moon' => 'Moon (contrast,crescent,dark,lunar,night)' ),
				array( 'far fa-moon' => 'Moon (contrast,crescent,dark,lunar,night)' ),
				array( 'fas fa-motorcycle' => 'Motorcycle (bike,machine,transportation,vehicle)' ),
				array( 'fas fa-mug-hot' => 'Mug Hot (caliente,cocoa,coffee,cup,drink,holiday,hot chocolate,steam,tea,warmth)' ),
				array( 'fas fa-newspaper' => 'Newspaper (article,editorial,headline,journal,journalism,news,press)' ),
				array( 'far fa-newspaper' => 'Newspaper (article,editorial,headline,journal,journalism,news,press)' ),
				array( 'fas fa-paint-brush' => 'Paint Brush (acrylic,art,brush,color,fill,paint,pigment,watercolor)' ),
				array( 'fas fa-paper-plane' => 'Paper Plane (air,float,fold,mail,paper,send)' ),
				array( 'far fa-paper-plane' => 'Paper Plane (air,float,fold,mail,paper,send)' ),
				array( 'fas fa-paperclip' => 'Paperclip (attach,attachment,connect,link)' ),
				array( 'fas fa-paste' => 'Paste (clipboard,copy,document,paper)' ),
				array( 'fas fa-paw' => 'Paw (animal,cat,dog,pet,print)' ),
				array( 'fas fa-pen' => 'Pen (design,edit,update,write)' ),
				array( 'fas fa-pen-alt' => 'Alternate Pen (design,edit,update,write)' ),
				array( 'fas fa-pen-fancy' => 'Pen Fancy (design,edit,fountain pen,update,write)' ),
				array( 'fas fa-pen-nib' => 'Pen Nib (design,edit,fountain pen,update,write)' ),
				array( 'fas fa-pencil-alt' => 'Alternate Pencil (design,edit,pencil,update,write)' ),
				array( 'fas fa-phone' => 'Phone (call,earphone,number,support,telephone,voice)' ),
				array( 'fas fa-phone-alt' => 'Alternate Phone (call,earphone,number,support,telephone,voice)' ),
				array( 'fas fa-plane' => 'plane (airplane,destination,fly,location,mode,travel,trip)' ),
				array( 'fas fa-plug' => 'Plug (connect,electric,online,power)' ),
				array( 'fas fa-print' => 'print (business,copy,document,office,paper)' ),
				array( 'fas fa-puzzle-piece' => 'Puzzle Piece (add-on,addon,game,section)' ),
				array( 'fas fa-ring' => 'Ring (Dungeons & Dragons,Gollum,band,binding,d&d,dnd,engagement,fantasy,gold,jewelry,marriage,precious)' ),
				array( 'fas fa-road' => 'road (highway,map,pavement,route,street,travel)' ),
				array( 'fas fa-rocket' => 'rocket (aircraft,app,jet,launch,nasa,space)' ),
				array( 'fas fa-ruler-combined' => 'Ruler Combined (design,draft,length,measure,planning)' ),
				array( 'fas fa-ruler-horizontal' => 'Ruler Horizontal (design,draft,length,measure,planning)' ),
				array( 'fas fa-ruler-vertical' => 'Ruler Vertical (design,draft,length,measure,planning)' ),
				array( 'fas fa-satellite' => 'Satellite (communications,hardware,orbit,space)' ),
				array( 'fas fa-satellite-dish' => 'Satellite Dish (SETI,communications,hardware,receiver,saucer,signal)' ),
				array( 'fas fa-save' => 'Save (disk,download,floppy,floppy-o)' ),
				array( 'far fa-save' => 'Save (disk,download,floppy,floppy-o)' ),
				array( 'fas fa-school' => 'School (building,education,learn,student,teacher)' ),
				array( 'fas fa-screwdriver' => 'Screwdriver (admin,fix,mechanic,repair,settings,tool)' ),
				array( 'fas fa-scroll' => 'Scroll (Dungeons & Dragons,announcement,d&d,dnd,fantasy,paper,script)' ),
				array( 'fas fa-sd-card' => 'Sd Card (image,memory,photo,save)' ),
				array( 'fas fa-search' => 'Search (bigger,enlarge,find,magnify,preview,zoom)' ),
				array( 'fas fa-shield-alt' => 'Alternate Shield (achievement,award,block,defend,security,winner)' ),
				array( 'fas fa-shopping-bag' => 'Shopping Bag (buy,checkout,grocery,payment,purchase)' ),
				array( 'fas fa-shopping-basket' => 'Shopping Basket (buy,checkout,grocery,payment,purchase)' ),
				array( 'fas fa-shopping-cart' => 'shopping-cart (buy,checkout,grocery,payment,purchase)' ),
				array( 'fas fa-shower' => 'Shower (bath,clean,faucet,water)' ),
				array( 'fas fa-sim-card' => 'SIM Card (hard drive,hardware,portable,storage,technology,tiny)' ),
				array( 'fas fa-skull-crossbones' => 'Skull & Crossbones (Dungeons & Dragons,alert,bones,d&d,danger,dead,deadly,death,dnd,fantasy,halloween,holiday,jolly-roger,pirate,poison,skeleton,warning)' ),
				array( 'fas fa-sleigh' => 'Sleigh (christmas,claus,fly,holiday,santa,sled,snow,xmas)' ),
				array( 'fas fa-snowflake' => 'Snowflake (precipitation,rain,winter)' ),
				array( 'far fa-snowflake' => 'Snowflake (precipitation,rain,winter)' ),
				array( 'fas fa-snowplow' => 'Snowplow (clean up,cold,road,storm,winter)' ),
				array( 'fas fa-space-shuttle' => 'Space Shuttle (astronaut,machine,nasa,rocket,transportation)' ),
				array( 'fas fa-star' => 'Star (achievement,award,favorite,important,night,rating,score)' ),
				array( 'far fa-star' => 'Star (achievement,award,favorite,important,night,rating,score)' ),
				array( 'fas fa-sticky-note' => 'Sticky Note (message,note,paper,reminder,sticker)' ),
				array( 'far fa-sticky-note' => 'Sticky Note (message,note,paper,reminder,sticker)' ),
				array( 'fas fa-stopwatch' => 'Stopwatch (clock,reminder,time)' ),
				array( 'fas fa-stroopwafel' => 'Stroopwafel (caramel,cookie,dessert,sweets,waffle)' ),
				array( 'fas fa-subway' => 'Subway (machine,railway,train,transportation,vehicle)' ),
				array( 'fas fa-suitcase' => 'Suitcase (baggage,luggage,move,suitcase,travel,trip)' ),
				array( 'fas fa-sun' => 'Sun (brighten,contrast,day,lighter,sol,solar,star,weather)' ),
				array( 'far fa-sun' => 'Sun (brighten,contrast,day,lighter,sol,solar,star,weather)' ),
				array( 'fas fa-tablet' => 'tablet (apple,device,ipad,kindle,screen)' ),
				array( 'fas fa-tablet-alt' => 'Alternate Tablet (apple,device,ipad,kindle,screen)' ),
				array( 'fas fa-tachometer-alt' => 'Alternate Tachometer (dashboard,fast,odometer,speed,speedometer)' ),
				array( 'fas fa-tag' => 'tag (discount,label,price,shopping)' ),
				array( 'fas fa-tags' => 'tags (discount,label,price,shopping)' ),
				array( 'fas fa-taxi' => 'Taxi (cab,cabbie,car,car service,lyft,machine,transportation,travel,uber,vehicle)' ),
				array( 'fas fa-thumbtack' => 'Thumbtack (coordinates,location,marker,pin,thumb-tack)' ),
				array( 'fas fa-ticket-alt' => 'Alternate Ticket (movie,pass,support,ticket)' ),
				array( 'fas fa-toilet' => 'Toilet (bathroom,flush,john,loo,pee,plumbing,poop,porcelain,potty,restroom,throne,washroom,waste,wc)' ),
				array( 'fas fa-toolbox' => 'Toolbox (admin,container,fix,repair,settings,tools)' ),
				array( 'fas fa-tools' => 'Tools (admin,fix,repair,screwdriver,settings,tools,wrench)' ),
				array( 'fas fa-train' => 'Train (bullet,commute,locomotive,railway,subway)' ),
				array( 'fas fa-tram' => 'Tram (crossing,machine,mountains,seasonal,transportation)' ),
				array( 'fas fa-trash' => 'Trash (delete,garbage,hide,remove)' ),
				array( 'fas fa-trash-alt' => 'Alternate Trash (delete,garbage,hide,remove,trash-o)' ),
				array( 'far fa-trash-alt' => 'Alternate Trash (delete,garbage,hide,remove,trash-o)' ),
				array( 'fas fa-tree' => 'Tree (bark,fall,flora,forest,nature,plant,seasonal)' ),
				array( 'fas fa-trophy' => 'trophy (achievement,award,cup,game,winner)' ),
				array( 'fas fa-truck' => 'truck (cargo,delivery,shipping,vehicle)' ),
				array( 'fas fa-tv' => 'Television (computer,display,monitor,television)' ),
				array( 'fas fa-umbrella' => 'Umbrella (protection,rain,storm,wet)' ),
				array( 'fas fa-university' => 'University (bank,building,college,higher education - students,institution)' ),
				array( 'fas fa-unlock' => 'unlock (admin,lock,password,private,protect)' ),
				array( 'fas fa-unlock-alt' => 'Alternate Unlock (admin,lock,password,private,protect)' ),
				array( 'fas fa-utensil-spoon' => 'Utensil Spoon (cutlery,dining,scoop,silverware,spoon)' ),
				array( 'fas fa-utensils' => 'Utensils (cutlery,dining,dinner,eat,food,fork,knife,restaurant)' ),
				array( 'fas fa-wallet' => 'Wallet (billfold,cash,currency,money)' ),
				array( 'fas fa-weight' => 'Weight (health,measurement,scale,weight)' ),
				array( 'fas fa-wheelchair' => 'Wheelchair (accessible,handicap,person)' ),
				array( 'fas fa-wine-glass' => 'Wine Glass (alcohol,beverage,cabernet,drink,grapes,merlot,sauvignon)' ),
				array( 'fas fa-wrench' => 'Wrench (construction,fix,mechanic,plumbing,settings,spanner,tool,update)' ),
			),
			'Payments & Shopping' => array(
				array( 'fab fa-alipay' => 'Alipay' ),
				array( 'fab fa-amazon-pay' => 'Amazon Pay' ),
				array( 'fab fa-apple-pay' => 'Apple Pay' ),
				array( 'fas fa-bell' => 'bell (alarm,alert,chime,notification,reminder)' ),
				array( 'far fa-bell' => 'bell (alarm,alert,chime,notification,reminder)' ),
				array( 'fab fa-bitcoin' => 'Bitcoin' ),
				array( 'fas fa-bookmark' => 'bookmark (favorite,marker,read,remember,save)' ),
				array( 'far fa-bookmark' => 'bookmark (favorite,marker,read,remember,save)' ),
				array( 'fab fa-btc' => 'BTC' ),
				array( 'fas fa-bullhorn' => 'bullhorn (announcement,broadcast,louder,megaphone,share)' ),
				array( 'fas fa-camera' => 'camera (image,lens,photo,picture,record,shutter,video)' ),
				array( 'fas fa-camera-retro' => 'Retro Camera (image,lens,photo,picture,record,shutter,video)' ),
				array( 'fas fa-cart-arrow-down' => 'Shopping Cart Arrow Down (download,save,shopping)' ),
				array( 'fas fa-cart-plus' => 'Add to Shopping Cart (add,create,new,positive,shopping)' ),
				array( 'fab fa-cc-amazon-pay' => 'Amazon Pay Credit Card' ),
				array( 'fab fa-cc-amex' => 'American Express Credit Card (amex)' ),
				array( 'fab fa-cc-apple-pay' => 'Apple Pay Credit Card' ),
				array( 'fab fa-cc-diners-club' => 'Diner\'s Club Credit Card' ),
				array( 'fab fa-cc-discover' => 'Discover Credit Card' ),
				array( 'fab fa-cc-jcb' => 'JCB Credit Card' ),
				array( 'fab fa-cc-mastercard' => 'MasterCard Credit Card' ),
				array( 'fab fa-cc-paypal' => 'Paypal Credit Card' ),
				array( 'fab fa-cc-stripe' => 'Stripe Credit Card' ),
				array( 'fab fa-cc-visa' => 'Visa Credit Card' ),
				array( 'fas fa-certificate' => 'certificate (badge,star,verified)' ),
				array( 'fas fa-credit-card' => 'Credit Card (buy,checkout,credit-card-alt,debit,money,payment,purchase)' ),
				array( 'far fa-credit-card' => 'Credit Card (buy,checkout,credit-card-alt,debit,money,payment,purchase)' ),
				array( 'fab fa-ethereum' => 'Ethereum' ),
				array( 'fas fa-gem' => 'Gem (diamond,jewelry,sapphire,stone,treasure)' ),
				array( 'far fa-gem' => 'Gem (diamond,jewelry,sapphire,stone,treasure)' ),
				array( 'fas fa-gift' => 'gift (christmas,generosity,giving,holiday,party,present,wrapped,xmas)' ),
				array( 'fab fa-google-wallet' => 'Google Wallet' ),
				array( 'fas fa-handshake' => 'Handshake (agreement,greeting,meeting,partnership)' ),
				array( 'far fa-handshake' => 'Handshake (agreement,greeting,meeting,partnership)' ),
				array( 'fas fa-heart' => 'Heart (favorite,like,love,relationship,valentine)' ),
				array( 'far fa-heart' => 'Heart (favorite,like,love,relationship,valentine)' ),
				array( 'fas fa-key' => 'key (lock,password,private,secret,unlock)' ),
				array( 'fas fa-money-check' => 'Money Check (bank check,buy,checkout,cheque,money,payment,price,purchase)' ),
				array( 'fas fa-money-check-alt' => 'Alternate Money Check (bank check,buy,checkout,cheque,money,payment,price,purchase)' ),
				array( 'fab fa-paypal' => 'Paypal' ),
				array( 'fas fa-receipt' => 'Receipt (check,invoice,money,pay,table)' ),
				array( 'fas fa-shopping-bag' => 'Shopping Bag (buy,checkout,grocery,payment,purchase)' ),
				array( 'fas fa-shopping-basket' => 'Shopping Basket (buy,checkout,grocery,payment,purchase)' ),
				array( 'fas fa-shopping-cart' => 'shopping-cart (buy,checkout,grocery,payment,purchase)' ),
				array( 'fas fa-star' => 'Star (achievement,award,favorite,important,night,rating,score)' ),
				array( 'far fa-star' => 'Star (achievement,award,favorite,important,night,rating,score)' ),
				array( 'fab fa-stripe' => 'Stripe' ),
				array( 'fab fa-stripe-s' => 'Stripe S' ),
				array( 'fas fa-tag' => 'tag (discount,label,price,shopping)' ),
				array( 'fas fa-tags' => 'tags (discount,label,price,shopping)' ),
				array( 'fas fa-thumbs-down' => 'thumbs-down (disagree,disapprove,dislike,hand,social,thumbs-o-down)' ),
				array( 'far fa-thumbs-down' => 'thumbs-down (disagree,disapprove,dislike,hand,social,thumbs-o-down)' ),
				array( 'fas fa-thumbs-up' => 'thumbs-up (agree,approve,favorite,hand,like,ok,okay,social,success,thumbs-o-up,yes,you got it dude)' ),
				array( 'far fa-thumbs-up' => 'thumbs-up (agree,approve,favorite,hand,like,ok,okay,social,success,thumbs-o-up,yes,you got it dude)' ),
				array( 'fas fa-trophy' => 'trophy (achievement,award,cup,game,winner)' ),
			),
			'Pharmacy'            => array(
				array( 'fas fa-band-aid' => 'Band-Aid (bandage,boo boo,first aid,ouch)' ),
				array( 'fas fa-book-medical' => 'Medical Book (diary,documentation,health,history,journal,library,read,record)' ),
				array( 'fas fa-cannabis' => 'Cannabis (bud,chronic,drugs,endica,endo,ganja,marijuana,mary jane,pot,reefer,sativa,spliff,weed,whacky-tabacky)' ),
				array( 'fas fa-capsules' => 'Capsules (drugs,medicine,pills,prescription)' ),
				array( 'fas fa-clinic-medical' => 'Medical Clinic (doctor,general practitioner,hospital,infirmary,medicine,office,outpatient)' ),
				array( 'fas fa-eye-dropper' => 'Eye Dropper (beaker,clone,color,copy,eyedropper,pipette)' ),
				array( 'fas fa-file-medical' => 'Medical File (document,health,history,prescription,record)' ),
				array( 'fas fa-file-prescription' => 'File Prescription (document,drugs,medical,medicine,rx)' ),
				array( 'fas fa-first-aid' => 'First Aid (emergency,emt,health,medical,rescue)' ),
				array( 'fas fa-flask' => 'Flask (beaker,experimental,labs,science)' ),
				array( 'fas fa-history' => 'History (Rewind,clock,reverse,time,time machine)' ),
				array( 'fas fa-joint' => 'Joint (blunt,cannabis,doobie,drugs,marijuana,roach,smoke,smoking,spliff)' ),
				array( 'fas fa-laptop-medical' => 'Laptop Medical (computer,device,ehr,electronic health records,history)' ),
				array( 'fas fa-mortar-pestle' => 'Mortar Pestle (crush,culinary,grind,medical,mix,pharmacy,prescription,spices)' ),
				array( 'fas fa-notes-medical' => 'Medical Notes (clipboard,doctor,ehr,health,history,records)' ),
				array( 'fas fa-pills' => 'Pills (drugs,medicine,prescription,tablets)' ),
				array( 'fas fa-prescription' => 'Prescription (drugs,medical,medicine,pharmacy,rx)' ),
				array( 'fas fa-prescription-bottle' => 'Prescription Bottle (drugs,medical,medicine,pharmacy,rx)' ),
				array( 'fas fa-prescription-bottle-alt' => 'Alternate Prescription Bottle (drugs,medical,medicine,pharmacy,rx)' ),
				array( 'fas fa-receipt' => 'Receipt (check,invoice,money,pay,table)' ),
				array( 'fas fa-skull-crossbones' => 'Skull & Crossbones (Dungeons & Dragons,alert,bones,d&d,danger,dead,deadly,death,dnd,fantasy,halloween,holiday,jolly-roger,pirate,poison,skeleton,warning)' ),
				array( 'fas fa-syringe' => 'Syringe (doctor,immunizations,medical,needle)' ),
				array( 'fas fa-tablets' => 'Tablets (drugs,medicine,pills,prescription)' ),
				array( 'fas fa-thermometer' => 'Thermometer (mercury,status,temperature)' ),
				array( 'fas fa-vial' => 'Vial (experiment,lab,sample,science,test,test tube)' ),
				array( 'fas fa-vials' => 'Vials (experiment,lab,sample,science,test,test tube)' ),
			),
			'Political'           => array(
				array( 'fas fa-award' => 'Award (honor,praise,prize,recognition,ribbon,trophy)' ),
				array( 'fas fa-balance-scale' => 'Balance Scale (balanced,justice,legal,measure,weight)' ),
				array( 'fas fa-balance-scale-left' => 'Balance Scale (Left-Weighted) (justice,legal,measure,unbalanced,weight)' ),
				array( 'fas fa-balance-scale-right' => 'Balance Scale (Right-Weighted) (justice,legal,measure,unbalanced,weight)' ),
				array( 'fas fa-bullhorn' => 'bullhorn (announcement,broadcast,louder,megaphone,share)' ),
				array( 'fas fa-check-double' => 'Double Check (accept,agree,checkmark,confirm,correct,done,notice,notification,notify,ok,select,success,tick,todo)' ),
				array( 'fas fa-democrat' => 'Democrat (american,democratic party,donkey,election,left,left-wing,liberal,politics,usa)' ),
				array( 'fas fa-donate' => 'Donate (contribute,generosity,gift,give)' ),
				array( 'fas fa-dove' => 'Dove (bird,fauna,flying,peace,war)' ),
				array( 'fas fa-fist-raised' => 'Raised Fist (Dungeons & Dragons,d&d,dnd,fantasy,hand,ki,monk,resist,strength,unarmed combat)' ),
				array( 'fas fa-flag-usa' => 'United States of America Flag (betsy ross,country,old glory,stars,stripes,symbol)' ),
				array( 'fas fa-handshake' => 'Handshake (agreement,greeting,meeting,partnership)' ),
				array( 'far fa-handshake' => 'Handshake (agreement,greeting,meeting,partnership)' ),
				array( 'fas fa-person-booth' => 'Person Entering Booth (changing,changing room,election,human,person,vote,voting)' ),
				array( 'fas fa-piggy-bank' => 'Piggy Bank (bank,save,savings)' ),
				array( 'fas fa-republican' => 'Republican (american,conservative,election,elephant,politics,republican party,right,right-wing,usa)' ),
				array( 'fas fa-vote-yea' => 'Vote Yea (accept,cast,election,politics,positive,yes)' ),
			),
			'Religion'            => array(
				array( 'fas fa-ankh' => 'Ankh (amulet,copper,coptic christianity,copts,crux ansata,egypt,venus)' ),
				array( 'fas fa-atom' => 'Atom (atheism,chemistry,ion,nuclear,science)' ),
				array( 'fas fa-bible' => 'Bible (book,catholicism,christianity,god,holy)' ),
				array( 'fas fa-church' => 'Church (building,cathedral,chapel,community,religion)' ),
				array( 'fas fa-cross' => 'Cross (catholicism,christianity,church,jesus)' ),
				array( 'fas fa-dharmachakra' => 'Dharmachakra (buddhism,buddhist,wheel of dharma)' ),
				array( 'fas fa-dove' => 'Dove (bird,fauna,flying,peace,war)' ),
				array( 'fas fa-gopuram' => 'Gopuram (building,entrance,hinduism,temple,tower)' ),
				array( 'fas fa-hamsa' => 'Hamsa (amulet,christianity,islam,jewish,judaism,muslim,protection)' ),
				array( 'fas fa-hanukiah' => 'Hanukiah (candle,hanukkah,jewish,judaism,light)' ),
				array( 'fas fa-haykal' => 'Haykal (bahai,bahá\'í,star)' ),
				array( 'fas fa-jedi' => 'Jedi (crest,force,sith,skywalker,star wars,yoda)' ),
				array( 'fas fa-journal-whills' => 'Journal of the Whills (book,force,jedi,sith,star wars,yoda)' ),
				array( 'fas fa-kaaba' => 'Kaaba (building,cube,islam,muslim)' ),
				array( 'fas fa-khanda' => 'Khanda (chakkar,sikh,sikhism,sword)' ),
				array( 'fas fa-menorah' => 'Menorah (candle,hanukkah,jewish,judaism,light)' ),
				array( 'fas fa-mosque' => 'Mosque (building,islam,landmark,muslim)' ),
				array( 'fas fa-om' => 'Om (buddhism,hinduism,jainism,mantra)' ),
				array( 'fas fa-pastafarianism' => 'Pastafarianism (agnosticism,atheism,flying spaghetti monster,fsm)' ),
				array( 'fas fa-peace' => 'Peace (serenity,tranquility,truce,war)' ),
				array( 'fas fa-place-of-worship' => 'Place of Worship (building,church,holy,mosque,synagogue)' ),
				array( 'fas fa-pray' => 'Pray (kneel,preach,religion,worship)' ),
				array( 'fas fa-praying-hands' => 'Praying Hands (kneel,preach,religion,worship)' ),
				array( 'fas fa-quran' => 'Quran (book,islam,muslim,religion)' ),
				array( 'fas fa-star-and-crescent' => 'Star and Crescent (islam,muslim,religion)' ),
				array( 'fas fa-star-of-david' => 'Star of David (jewish,judaism,religion)' ),
				array( 'fas fa-synagogue' => 'Synagogue (building,jewish,judaism,religion,star of david,temple)' ),
				array( 'fas fa-torah' => 'Torah (book,jewish,judaism,religion,scroll)' ),
				array( 'fas fa-torii-gate' => 'Torii Gate (building,shintoism)' ),
				array( 'fas fa-vihara' => 'Vihara (buddhism,buddhist,building,monastery)' ),
				array( 'fas fa-yin-yang' => 'Yin Yang (daoism,opposites,taoism)' ),
			),
			'Science'             => array(
				array( 'fas fa-atom' => 'Atom (atheism,chemistry,ion,nuclear,science)' ),
				array( 'fas fa-biohazard' => 'Biohazard (danger,dangerous,hazmat,medical,radioactive,toxic,waste,zombie)' ),
				array( 'fas fa-brain' => 'Brain (cerebellum,gray matter,intellect,medulla oblongata,mind,noodle,wit)' ),
				array( 'fas fa-burn' => 'Burn (caliente,energy,fire,flame,gas,heat,hot)' ),
				array( 'fas fa-capsules' => 'Capsules (drugs,medicine,pills,prescription)' ),
				array( 'fas fa-clipboard-check' => 'Clipboard with Check (accept,agree,confirm,done,ok,select,success,tick,todo,yes)' ),
				array( 'fas fa-dna' => 'DNA (double helix,genetic,helix,molecule,protein)' ),
				array( 'fas fa-eye-dropper' => 'Eye Dropper (beaker,clone,color,copy,eyedropper,pipette)' ),
				array( 'fas fa-filter' => 'Filter (funnel,options,separate,sort)' ),
				array( 'fas fa-fire' => 'fire (burn,caliente,flame,heat,hot,popular)' ),
				array( 'fas fa-fire-alt' => 'Alternate Fire (burn,caliente,flame,heat,hot,popular)' ),
				array( 'fas fa-flask' => 'Flask (beaker,experimental,labs,science)' ),
				array( 'fas fa-frog' => 'Frog (amphibian,bullfrog,fauna,hop,kermit,kiss,prince,ribbit,toad,wart)' ),
				array( 'fas fa-magnet' => 'magnet (Attract,lodestone,tool)' ),
				array( 'fas fa-microscope' => 'Microscope (electron,lens,optics,science,shrink)' ),
				array( 'fas fa-mortar-pestle' => 'Mortar Pestle (crush,culinary,grind,medical,mix,pharmacy,prescription,spices)' ),
				array( 'fas fa-pills' => 'Pills (drugs,medicine,prescription,tablets)' ),
				array( 'fas fa-prescription-bottle' => 'Prescription Bottle (drugs,medical,medicine,pharmacy,rx)' ),
				array( 'fas fa-radiation' => 'Radiation (danger,dangerous,deadly,hazard,nuclear,radioactive,warning)' ),
				array( 'fas fa-radiation-alt' => 'Alternate Radiation (danger,dangerous,deadly,hazard,nuclear,radioactive,warning)' ),
				array( 'fas fa-seedling' => 'Seedling (flora,grow,plant,vegan)' ),
				array( 'fas fa-skull-crossbones' => 'Skull & Crossbones (Dungeons & Dragons,alert,bones,d&d,danger,dead,deadly,death,dnd,fantasy,halloween,holiday,jolly-roger,pirate,poison,skeleton,warning)' ),
				array( 'fas fa-syringe' => 'Syringe (doctor,immunizations,medical,needle)' ),
				array( 'fas fa-tablets' => 'Tablets (drugs,medicine,pills,prescription)' ),
				array( 'fas fa-temperature-high' => 'High Temperature (cook,mercury,summer,thermometer,warm)' ),
				array( 'fas fa-temperature-low' => 'Low Temperature (cold,cool,mercury,thermometer,winter)' ),
				array( 'fas fa-vial' => 'Vial (experiment,lab,sample,science,test,test tube)' ),
				array( 'fas fa-vials' => 'Vials (experiment,lab,sample,science,test,test tube)' ),
			),
			'Science Fiction'     => array(
				array( 'fab fa-galactic-republic' => 'Galactic Republic (politics,star wars)' ),
				array( 'fab fa-galactic-senate' => 'Galactic Senate (star wars)' ),
				array( 'fas fa-globe' => 'Globe (all,coordinates,country,earth,global,gps,language,localize,location,map,online,place,planet,translate,travel,world)' ),
				array( 'fas fa-jedi' => 'Jedi (crest,force,sith,skywalker,star wars,yoda)' ),
				array( 'fab fa-jedi-order' => 'Jedi Order (star wars)' ),
				array( 'fas fa-journal-whills' => 'Journal of the Whills (book,force,jedi,sith,star wars,yoda)' ),
				array( 'fas fa-meteor' => 'Meteor (armageddon,asteroid,comet,shooting star,space)' ),
				array( 'fas fa-moon' => 'Moon (contrast,crescent,dark,lunar,night)' ),
				array( 'far fa-moon' => 'Moon (contrast,crescent,dark,lunar,night)' ),
				array( 'fab fa-old-republic' => 'Old Republic (politics,star wars)' ),
				array( 'fas fa-robot' => 'Robot (android,automate,computer,cyborg)' ),
				array( 'fas fa-rocket' => 'rocket (aircraft,app,jet,launch,nasa,space)' ),
				array( 'fas fa-satellite' => 'Satellite (communications,hardware,orbit,space)' ),
				array( 'fas fa-satellite-dish' => 'Satellite Dish (SETI,communications,hardware,receiver,saucer,signal)' ),
				array( 'fas fa-space-shuttle' => 'Space Shuttle (astronaut,machine,nasa,rocket,transportation)' ),
				array( 'fas fa-user-astronaut' => 'User Astronaut (avatar,clothing,cosmonaut,nasa,space,suit)' ),
			),
			'Security'            => array(
				array( 'fas fa-ban' => 'ban (abort,ban,block,cancel,delete,hide,prohibit,remove,stop,trash)' ),
				array( 'fas fa-bug' => 'Bug (beetle,error,insect,report)' ),
				array( 'fas fa-door-closed' => 'Door Closed (enter,exit,locked)' ),
				array( 'fas fa-door-open' => 'Door Open (enter,exit,welcome)' ),
				array( 'fas fa-dungeon' => 'Dungeon (Dungeons & Dragons,building,d&d,dnd,door,entrance,fantasy,gate)' ),
				array( 'fas fa-eye' => 'Eye (look,optic,see,seen,show,sight,views,visible)' ),
				array( 'far fa-eye' => 'Eye (look,optic,see,seen,show,sight,views,visible)' ),
				array( 'fas fa-eye-slash' => 'Eye Slash (blind,hide,show,toggle,unseen,views,visible,visiblity)' ),
				array( 'far fa-eye-slash' => 'Eye Slash (blind,hide,show,toggle,unseen,views,visible,visiblity)' ),
				array( 'fas fa-file-contract' => 'File Contract (agreement,binding,document,legal,signature)' ),
				array( 'fas fa-file-signature' => 'File Signature (John Hancock,contract,document,name)' ),
				array( 'fas fa-fingerprint' => 'Fingerprint (human,id,identification,lock,smudge,touch,unique,unlock)' ),
				array( 'fas fa-id-badge' => 'Identification Badge (address,contact,identification,license,profile)' ),
				array( 'far fa-id-badge' => 'Identification Badge (address,contact,identification,license,profile)' ),
				array( 'fas fa-id-card' => 'Identification Card (contact,demographics,document,identification,issued,profile)' ),
				array( 'far fa-id-card' => 'Identification Card (contact,demographics,document,identification,issued,profile)' ),
				array( 'fas fa-id-card-alt' => 'Alternate Identification Card (contact,demographics,document,identification,issued,profile)' ),
				array( 'fas fa-key' => 'key (lock,password,private,secret,unlock)' ),
				array( 'fas fa-lock' => 'lock (admin,lock,open,password,private,protect,security)' ),
				array( 'fas fa-lock-open' => 'Lock Open (admin,lock,open,password,private,protect,security)' ),
				array( 'fas fa-mask' => 'Mask (carnivale,costume,disguise,halloween,secret,super hero)' ),
				array( 'fas fa-passport' => 'Passport (document,id,identification,issued,travel)' ),
				array( 'fas fa-shield-alt' => 'Alternate Shield (achievement,award,block,defend,security,winner)' ),
				array( 'fas fa-unlock' => 'unlock (admin,lock,password,private,protect)' ),
				array( 'fas fa-unlock-alt' => 'Alternate Unlock (admin,lock,password,private,protect)' ),
				array( 'fas fa-user-lock' => 'User Lock (admin,lock,person,private,unlock)' ),
				array( 'fas fa-user-secret' => 'User Secret (clothing,coat,hat,incognito,person,privacy,spy,whisper)' ),
				array( 'fas fa-user-shield' => 'User Shield (admin,person,private,protect,safe)' ),
			),
			'Shapes'              => array(
				array( 'fas fa-bookmark' => 'bookmark (favorite,marker,read,remember,save)' ),
				array( 'far fa-bookmark' => 'bookmark (favorite,marker,read,remember,save)' ),
				array( 'fas fa-calendar' => 'Calendar (calendar-o,date,event,schedule,time,when)' ),
				array( 'far fa-calendar' => 'Calendar (calendar-o,date,event,schedule,time,when)' ),
				array( 'fas fa-certificate' => 'certificate (badge,star,verified)' ),
				array( 'fas fa-circle' => 'Circle (circle-thin,diameter,dot,ellipse,notification,round)' ),
				array( 'far fa-circle' => 'Circle (circle-thin,diameter,dot,ellipse,notification,round)' ),
				array( 'fas fa-cloud' => 'Cloud (atmosphere,fog,overcast,save,upload,weather)' ),
				array( 'fas fa-comment' => 'comment (bubble,chat,commenting,conversation,feedback,message,note,notification,sms,speech,texting)' ),
				array( 'far fa-comment' => 'comment (bubble,chat,commenting,conversation,feedback,message,note,notification,sms,speech,texting)' ),
				array( 'fas fa-file' => 'File (document,new,page,pdf,resume)' ),
				array( 'far fa-file' => 'File (document,new,page,pdf,resume)' ),
				array( 'fas fa-folder' => 'Folder (archive,directory,document,file)' ),
				array( 'far fa-folder' => 'Folder (archive,directory,document,file)' ),
				array( 'fas fa-heart' => 'Heart (favorite,like,love,relationship,valentine)' ),
				array( 'far fa-heart' => 'Heart (favorite,like,love,relationship,valentine)' ),
				array( 'fas fa-heart-broken' => 'Heart Broken (breakup,crushed,dislike,dumped,grief,love,lovesick,relationship,sad)' ),
				array( 'fas fa-map-marker' => 'map-marker (address,coordinates,destination,gps,localize,location,map,navigation,paper,pin,place,point of interest,position,route,travel)' ),
				array( 'fas fa-play' => 'play (audio,music,playing,sound,start,video)' ),
				array( 'fas fa-shapes' => 'Shapes (blocks,build,circle,square,triangle)' ),
				array( 'fas fa-square' => 'Square (block,box,shape)' ),
				array( 'far fa-square' => 'Square (block,box,shape)' ),
				array( 'fas fa-star' => 'Star (achievement,award,favorite,important,night,rating,score)' ),
				array( 'far fa-star' => 'Star (achievement,award,favorite,important,night,rating,score)' ),
			),
			'Shopping'            => array(
				array( 'fas fa-barcode' => 'barcode (info,laser,price,scan,upc)' ),
				array( 'fas fa-cart-arrow-down' => 'Shopping Cart Arrow Down (download,save,shopping)' ),
				array( 'fas fa-cart-plus' => 'Add to Shopping Cart (add,create,new,positive,shopping)' ),
				array( 'fas fa-cash-register' => 'Cash Register (buy,cha-ching,change,checkout,commerce,leaerboard,machine,pay,payment,purchase,store)' ),
				array( 'fas fa-gift' => 'gift (christmas,generosity,giving,holiday,party,present,wrapped,xmas)' ),
				array( 'fas fa-gifts' => 'Gifts (christmas,generosity,giving,holiday,party,present,wrapped,xmas)' ),
				array( 'fas fa-person-booth' => 'Person Entering Booth (changing,changing room,election,human,person,vote,voting)' ),
				array( 'fas fa-receipt' => 'Receipt (check,invoice,money,pay,table)' ),
				array( 'fas fa-shipping-fast' => 'Shipping Fast (express,fedex,mail,overnight,package,ups)' ),
				array( 'fas fa-shopping-bag' => 'Shopping Bag (buy,checkout,grocery,payment,purchase)' ),
				array( 'fas fa-shopping-basket' => 'Shopping Basket (buy,checkout,grocery,payment,purchase)' ),
				array( 'fas fa-shopping-cart' => 'shopping-cart (buy,checkout,grocery,payment,purchase)' ),
				array( 'fas fa-store' => 'Store (building,buy,purchase,shopping)' ),
				array( 'fas fa-store-alt' => 'Alternate Store (building,buy,purchase,shopping)' ),
				array( 'fas fa-truck' => 'truck (cargo,delivery,shipping,vehicle)' ),
				array( 'fas fa-tshirt' => 'T-Shirt (clothing,fashion,garment,shirt)' ),
			),
			'Social'              => array(
				array( 'fas fa-bell' => 'bell (alarm,alert,chime,notification,reminder)' ),
				array( 'far fa-bell' => 'bell (alarm,alert,chime,notification,reminder)' ),
				array( 'fas fa-birthday-cake' => 'Birthday Cake (anniversary,bakery,candles,celebration,dessert,frosting,holiday,party,pastry)' ),
				array( 'fas fa-camera' => 'camera (image,lens,photo,picture,record,shutter,video)' ),
				array( 'fas fa-comment' => 'comment (bubble,chat,commenting,conversation,feedback,message,note,notification,sms,speech,texting)' ),
				array( 'far fa-comment' => 'comment (bubble,chat,commenting,conversation,feedback,message,note,notification,sms,speech,texting)' ),
				array( 'fas fa-comment-alt' => 'Alternate Comment (bubble,chat,commenting,conversation,feedback,message,note,notification,sms,speech,texting)' ),
				array( 'far fa-comment-alt' => 'Alternate Comment (bubble,chat,commenting,conversation,feedback,message,note,notification,sms,speech,texting)' ),
				array( 'fas fa-envelope' => 'Envelope (e-mail,email,letter,mail,message,notification,support)' ),
				array( 'far fa-envelope' => 'Envelope (e-mail,email,letter,mail,message,notification,support)' ),
				array( 'fas fa-hashtag' => 'Hashtag (Twitter,instagram,pound,social media,tag)' ),
				array( 'fas fa-heart' => 'Heart (favorite,like,love,relationship,valentine)' ),
				array( 'far fa-heart' => 'Heart (favorite,like,love,relationship,valentine)' ),
				array( 'fas fa-icons' => 'Icons (bolt,emoji,heart,image,music,photo,symbols)' ),
				array( 'fas fa-image' => 'Image (album,landscape,photo,picture)' ),
				array( 'far fa-image' => 'Image (album,landscape,photo,picture)' ),
				array( 'fas fa-images' => 'Images (album,landscape,photo,picture)' ),
				array( 'far fa-images' => 'Images (album,landscape,photo,picture)' ),
				array( 'fas fa-map-marker' => 'map-marker (address,coordinates,destination,gps,localize,location,map,navigation,paper,pin,place,point of interest,position,route,travel)' ),
				array( 'fas fa-map-marker-alt' => 'Alternate Map Marker (address,coordinates,destination,gps,localize,location,map,navigation,paper,pin,place,point of interest,position,route,travel)' ),
				array( 'fas fa-photo-video' => 'Photo Video (av,film,image,library,media)' ),
				array( 'fas fa-poll' => 'Poll (results,survey,trend,vote,voting)' ),
				array( 'fas fa-poll-h' => 'Poll H (results,survey,trend,vote,voting)' ),
				array( 'fas fa-retweet' => 'Retweet (refresh,reload,share,swap)' ),
				array( 'fas fa-share' => 'Share (forward,save,send,social)' ),
				array( 'fas fa-share-alt' => 'Alternate Share (forward,save,send,social)' ),
				array( 'fas fa-share-square' => 'Share Square (forward,save,send,social)' ),
				array( 'far fa-share-square' => 'Share Square (forward,save,send,social)' ),
				array( 'fas fa-star' => 'Star (achievement,award,favorite,important,night,rating,score)' ),
				array( 'far fa-star' => 'Star (achievement,award,favorite,important,night,rating,score)' ),
				array( 'fas fa-thumbs-down' => 'thumbs-down (disagree,disapprove,dislike,hand,social,thumbs-o-down)' ),
				array( 'far fa-thumbs-down' => 'thumbs-down (disagree,disapprove,dislike,hand,social,thumbs-o-down)' ),
				array( 'fas fa-thumbs-up' => 'thumbs-up (agree,approve,favorite,hand,like,ok,okay,social,success,thumbs-o-up,yes,you got it dude)' ),
				array( 'far fa-thumbs-up' => 'thumbs-up (agree,approve,favorite,hand,like,ok,okay,social,success,thumbs-o-up,yes,you got it dude)' ),
				array( 'fas fa-thumbtack' => 'Thumbtack (coordinates,location,marker,pin,thumb-tack)' ),
				array( 'fas fa-user' => 'User (account,avatar,head,human,man,person,profile)' ),
				array( 'far fa-user' => 'User (account,avatar,head,human,man,person,profile)' ),
				array( 'fas fa-user-circle' => 'User Circle (account,avatar,head,human,man,person,profile)' ),
				array( 'far fa-user-circle' => 'User Circle (account,avatar,head,human,man,person,profile)' ),
				array( 'fas fa-user-friends' => 'User Friends (group,people,person,team,users)' ),
				array( 'fas fa-user-plus' => 'User Plus (add,avatar,positive,sign up,signup,team)' ),
				array( 'fas fa-users' => 'Users (friends,group,people,persons,profiles,team)' ),
				array( 'fas fa-video' => 'Video (camera,film,movie,record,video-camera)' ),
			),
			'Spinners'            => array(
				array( 'fas fa-asterisk' => 'asterisk (annotation,details,reference,star)' ),
				array( 'fas fa-atom' => 'Atom (atheism,chemistry,ion,nuclear,science)' ),
				array( 'fas fa-certificate' => 'certificate (badge,star,verified)' ),
				array( 'fas fa-circle-notch' => 'Circle Notched (circle-o-notch,diameter,dot,ellipse,round,spinner)' ),
				array( 'fas fa-cog' => 'cog (gear,mechanical,settings,sprocket,wheel)' ),
				array( 'fas fa-compact-disc' => 'Compact Disc (album,bluray,cd,disc,dvd,media,movie,music,record,video,vinyl)' ),
				array( 'fas fa-compass' => 'Compass (directions,directory,location,menu,navigation,safari,travel)' ),
				array( 'far fa-compass' => 'Compass (directions,directory,location,menu,navigation,safari,travel)' ),
				array( 'fas fa-crosshairs' => 'Crosshairs (aim,bullseye,gpd,picker,position)' ),
				array( 'fas fa-dharmachakra' => 'Dharmachakra (buddhism,buddhist,wheel of dharma)' ),
				array( 'fas fa-fan' => 'Fan (ac,air conditioning,blade,blower,cool,hot)' ),
				array( 'fas fa-haykal' => 'Haykal (bahai,bahá\'í,star)' ),
				array( 'fas fa-life-ring' => 'Life Ring (coast guard,help,overboard,save,support)' ),
				array( 'far fa-life-ring' => 'Life Ring (coast guard,help,overboard,save,support)' ),
				array( 'fas fa-palette' => 'Palette (acrylic,art,brush,color,fill,paint,pigment,watercolor)' ),
				array( 'fas fa-ring' => 'Ring (Dungeons & Dragons,Gollum,band,binding,d&d,dnd,engagement,fantasy,gold,jewelry,marriage,precious)' ),
				array( 'fas fa-slash' => 'Slash (cancel,close,mute,off,stop,x)' ),
				array( 'fas fa-snowflake' => 'Snowflake (precipitation,rain,winter)' ),
				array( 'far fa-snowflake' => 'Snowflake (precipitation,rain,winter)' ),
				array( 'fas fa-spinner' => 'Spinner (circle,loading,progress)' ),
				array( 'fas fa-stroopwafel' => 'Stroopwafel (caramel,cookie,dessert,sweets,waffle)' ),
				array( 'fas fa-sun' => 'Sun (brighten,contrast,day,lighter,sol,solar,star,weather)' ),
				array( 'far fa-sun' => 'Sun (brighten,contrast,day,lighter,sol,solar,star,weather)' ),
				array( 'fas fa-sync' => 'Sync (exchange,refresh,reload,rotate,swap)' ),
				array( 'fas fa-sync-alt' => 'Alternate Sync (exchange,refresh,reload,rotate,swap)' ),
				array( 'fas fa-yin-yang' => 'Yin Yang (daoism,opposites,taoism)' ),
			),
			'Sports'              => array(
				array( 'fas fa-baseball-ball' => 'Baseball Ball (foul,hardball,league,leather,mlb,softball,sport)' ),
				array( 'fas fa-basketball-ball' => 'Basketball Ball (dribble,dunk,hoop,nba)' ),
				array( 'fas fa-biking' => 'Biking (bicycle,bike,cycle,cycling,ride,wheel)' ),
				array( 'fas fa-bowling-ball' => 'Bowling Ball (alley,candlepin,gutter,lane,strike,tenpin)' ),
				array( 'fas fa-dumbbell' => 'Dumbbell (exercise,gym,strength,weight,weight-lifting)' ),
				array( 'fas fa-football-ball' => 'Football Ball (ball,fall,nfl,pigskin,seasonal)' ),
				array( 'fas fa-futbol' => 'Futbol (ball,football,mls,soccer)' ),
				array( 'far fa-futbol' => 'Futbol (ball,football,mls,soccer)' ),
				array( 'fas fa-golf-ball' => 'Golf Ball (caddy,eagle,putt,tee)' ),
				array( 'fas fa-hockey-puck' => 'Hockey Puck (ice,nhl,sport)' ),
				array( 'fas fa-quidditch' => 'Quidditch (ball,bludger,broom,golden snitch,harry potter,hogwarts,quaffle,sport,wizard)' ),
				array( 'fas fa-running' => 'Running (exercise,health,jog,person,run,sport,sprint)' ),
				array( 'fas fa-skating' => 'Skating (activity,figure skating,fitness,ice,person,winter)' ),
				array( 'fas fa-skiing' => 'Skiing (activity,downhill,fast,fitness,olympics,outdoors,person,seasonal,slalom)' ),
				array( 'fas fa-skiing-nordic' => 'Skiing Nordic (activity,cross country,fitness,outdoors,person,seasonal)' ),
				array( 'fas fa-snowboarding' => 'Snowboarding (activity,fitness,olympics,outdoors,person)' ),
				array( 'fas fa-swimmer' => 'Swimmer (athlete,head,man,olympics,person,pool,water)' ),
				array( 'fas fa-table-tennis' => 'Table Tennis (ball,paddle,ping pong)' ),
				array( 'fas fa-volleyball-ball' => 'Volleyball Ball (beach,olympics,sport)' ),
			),
			'Spring'              => array(
				array( 'fas fa-allergies' => 'Allergies (allergy,freckles,hand,hives,pox,skin,spots)' ),
				array( 'fas fa-broom' => 'Broom (clean,firebolt,fly,halloween,nimbus 2000,quidditch,sweep,witch)' ),
				array( 'fas fa-cloud-sun' => 'Cloud with Sun (clear,day,daytime,fall,outdoors,overcast,partly cloudy)' ),
				array( 'fas fa-cloud-sun-rain' => 'Cloud with Sun and Rain (day,overcast,precipitation,storm,summer,sunshower)' ),
				array( 'fas fa-frog' => 'Frog (amphibian,bullfrog,fauna,hop,kermit,kiss,prince,ribbit,toad,wart)' ),
				array( 'fas fa-rainbow' => 'Rainbow (gold,leprechaun,prism,rain,sky)' ),
				array( 'fas fa-seedling' => 'Seedling (flora,grow,plant,vegan)' ),
				array( 'fas fa-umbrella' => 'Umbrella (protection,rain,storm,wet)' ),
			),
			'Status'              => array(
				array( 'fas fa-ban' => 'ban (abort,ban,block,cancel,delete,hide,prohibit,remove,stop,trash)' ),
				array( 'fas fa-battery-empty' => 'Battery Empty (charge,dead,power,status)' ),
				array( 'fas fa-battery-full' => 'Battery Full (charge,power,status)' ),
				array( 'fas fa-battery-half' => 'Battery 1/2 Full (charge,power,status)' ),
				array( 'fas fa-battery-quarter' => 'Battery 1/4 Full (charge,low,power,status)' ),
				array( 'fas fa-battery-three-quarters' => 'Battery 3/4 Full (charge,power,status)' ),
				array( 'fas fa-bell' => 'bell (alarm,alert,chime,notification,reminder)' ),
				array( 'far fa-bell' => 'bell (alarm,alert,chime,notification,reminder)' ),
				array( 'fas fa-bell-slash' => 'Bell Slash (alert,cancel,disabled,notification,off,reminder)' ),
				array( 'far fa-bell-slash' => 'Bell Slash (alert,cancel,disabled,notification,off,reminder)' ),
				array( 'fas fa-calendar' => 'Calendar (calendar-o,date,event,schedule,time,when)' ),
				array( 'far fa-calendar' => 'Calendar (calendar-o,date,event,schedule,time,when)' ),
				array( 'fas fa-calendar-alt' => 'Alternate Calendar (calendar,date,event,schedule,time,when)' ),
				array( 'far fa-calendar-alt' => 'Alternate Calendar (calendar,date,event,schedule,time,when)' ),
				array( 'fas fa-calendar-check' => 'Calendar Check (accept,agree,appointment,confirm,correct,date,done,event,ok,schedule,select,success,tick,time,todo,when)' ),
				array( 'far fa-calendar-check' => 'Calendar Check (accept,agree,appointment,confirm,correct,date,done,event,ok,schedule,select,success,tick,time,todo,when)' ),
				array( 'fas fa-calendar-day' => 'Calendar with Day Focus (date,detail,event,focus,schedule,single day,time,today,when)' ),
				array( 'fas fa-calendar-minus' => 'Calendar Minus (calendar,date,delete,event,negative,remove,schedule,time,when)' ),
				array( 'far fa-calendar-minus' => 'Calendar Minus (calendar,date,delete,event,negative,remove,schedule,time,when)' ),
				array( 'fas fa-calendar-plus' => 'Calendar Plus (add,calendar,create,date,event,new,positive,schedule,time,when)' ),
				array( 'far fa-calendar-plus' => 'Calendar Plus (add,calendar,create,date,event,new,positive,schedule,time,when)' ),
				array( 'fas fa-calendar-times' => 'Calendar Times (archive,calendar,date,delete,event,remove,schedule,time,when,x)' ),
				array( 'far fa-calendar-times' => 'Calendar Times (archive,calendar,date,delete,event,remove,schedule,time,when,x)' ),
				array( 'fas fa-calendar-week' => 'Calendar with Week Focus (date,detail,event,focus,schedule,single week,time,today,when)' ),
				array( 'fas fa-cart-arrow-down' => 'Shopping Cart Arrow Down (download,save,shopping)' ),
				array( 'fas fa-cart-plus' => 'Add to Shopping Cart (add,create,new,positive,shopping)' ),
				array( 'fas fa-comment' => 'comment (bubble,chat,commenting,conversation,feedback,message,note,notification,sms,speech,texting)' ),
				array( 'far fa-comment' => 'comment (bubble,chat,commenting,conversation,feedback,message,note,notification,sms,speech,texting)' ),
				array( 'fas fa-comment-alt' => 'Alternate Comment (bubble,chat,commenting,conversation,feedback,message,note,notification,sms,speech,texting)' ),
				array( 'far fa-comment-alt' => 'Alternate Comment (bubble,chat,commenting,conversation,feedback,message,note,notification,sms,speech,texting)' ),
				array( 'fas fa-comment-slash' => 'Comment Slash (bubble,cancel,chat,commenting,conversation,feedback,message,mute,note,notification,quiet,sms,speech,texting)' ),
				array( 'fas fa-compass' => 'Compass (directions,directory,location,menu,navigation,safari,travel)' ),
				array( 'far fa-compass' => 'Compass (directions,directory,location,menu,navigation,safari,travel)' ),
				array( 'fas fa-door-closed' => 'Door Closed (enter,exit,locked)' ),
				array( 'fas fa-door-open' => 'Door Open (enter,exit,welcome)' ),
				array( 'fas fa-exclamation' => 'exclamation (alert,danger,error,important,notice,notification,notify,problem,warning)' ),
				array( 'fas fa-exclamation-circle' => 'Exclamation Circle (alert,danger,error,important,notice,notification,notify,problem,warning)' ),
				array( 'fas fa-exclamation-triangle' => 'Exclamation Triangle (alert,danger,error,important,notice,notification,notify,problem,warning)' ),
				array( 'fas fa-eye' => 'Eye (look,optic,see,seen,show,sight,views,visible)' ),
				array( 'far fa-eye' => 'Eye (look,optic,see,seen,show,sight,views,visible)' ),
				array( 'fas fa-eye-slash' => 'Eye Slash (blind,hide,show,toggle,unseen,views,visible,visiblity)' ),
				array( 'far fa-eye-slash' => 'Eye Slash (blind,hide,show,toggle,unseen,views,visible,visiblity)' ),
				array( 'fas fa-file' => 'File (document,new,page,pdf,resume)' ),
				array( 'far fa-file' => 'File (document,new,page,pdf,resume)' ),
				array( 'fas fa-file-alt' => 'Alternate File (document,file-text,invoice,new,page,pdf)' ),
				array( 'far fa-file-alt' => 'Alternate File (document,file-text,invoice,new,page,pdf)' ),
				array( 'fas fa-folder' => 'Folder (archive,directory,document,file)' ),
				array( 'far fa-folder' => 'Folder (archive,directory,document,file)' ),
				array( 'fas fa-folder-open' => 'Folder Open (archive,directory,document,empty,file,new)' ),
				array( 'far fa-folder-open' => 'Folder Open (archive,directory,document,empty,file,new)' ),
				array( 'fas fa-gas-pump' => 'Gas Pump (car,fuel,gasoline,petrol)' ),
				array( 'fas fa-info' => 'Info (details,help,information,more,support)' ),
				array( 'fas fa-info-circle' => 'Info Circle (details,help,information,more,support)' ),
				array( 'fas fa-lightbulb' => 'Lightbulb (energy,idea,inspiration,light)' ),
				array( 'far fa-lightbulb' => 'Lightbulb (energy,idea,inspiration,light)' ),
				array( 'fas fa-lock' => 'lock (admin,lock,open,password,private,protect,security)' ),
				array( 'fas fa-lock-open' => 'Lock Open (admin,lock,open,password,private,protect,security)' ),
				array( 'fas fa-map-marker' => 'map-marker (address,coordinates,destination,gps,localize,location,map,navigation,paper,pin,place,point of interest,position,route,travel)' ),
				array( 'fas fa-map-marker-alt' => 'Alternate Map Marker (address,coordinates,destination,gps,localize,location,map,navigation,paper,pin,place,point of interest,position,route,travel)' ),
				array( 'fas fa-microphone' => 'microphone (audio,podcast,record,sing,sound,voice)' ),
				array( 'fas fa-microphone-alt' => 'Alternate Microphone (audio,podcast,record,sing,sound,voice)' ),
				array( 'fas fa-microphone-alt-slash' => 'Alternate Microphone Slash (audio,disable,mute,podcast,record,sing,sound,voice)' ),
				array( 'fas fa-microphone-slash' => 'Microphone Slash (audio,disable,mute,podcast,record,sing,sound,voice)' ),
				array( 'fas fa-minus' => 'minus (collapse,delete,hide,minify,negative,remove,trash)' ),
				array( 'fas fa-minus-circle' => 'Minus Circle (delete,hide,negative,remove,shape,trash)' ),
				array( 'fas fa-minus-square' => 'Minus Square (collapse,delete,hide,minify,negative,remove,shape,trash)' ),
				array( 'far fa-minus-square' => 'Minus Square (collapse,delete,hide,minify,negative,remove,shape,trash)' ),
				array( 'fas fa-parking' => 'Parking (auto,car,garage,meter)' ),
				array( 'fas fa-phone' => 'Phone (call,earphone,number,support,telephone,voice)' ),
				array( 'fas fa-phone-alt' => 'Alternate Phone (call,earphone,number,support,telephone,voice)' ),
				array( 'fas fa-phone-slash' => 'Phone Slash (call,cancel,earphone,mute,number,support,telephone,voice)' ),
				array( 'fas fa-plus' => 'plus (add,create,expand,new,positive,shape)' ),
				array( 'fas fa-plus-circle' => 'Plus Circle (add,create,expand,new,positive,shape)' ),
				array( 'fas fa-plus-square' => 'Plus Square (add,create,expand,new,positive,shape)' ),
				array( 'far fa-plus-square' => 'Plus Square (add,create,expand,new,positive,shape)' ),
				array( 'fas fa-print' => 'print (business,copy,document,office,paper)' ),
				array( 'fas fa-question' => 'Question (help,information,support,unknown)' ),
				array( 'fas fa-question-circle' => 'Question Circle (help,information,support,unknown)' ),
				array( 'far fa-question-circle' => 'Question Circle (help,information,support,unknown)' ),
				array( 'fas fa-shield-alt' => 'Alternate Shield (achievement,award,block,defend,security,winner)' ),
				array( 'fas fa-shopping-cart' => 'shopping-cart (buy,checkout,grocery,payment,purchase)' ),
				array( 'fas fa-sign-in-alt' => 'Alternate Sign In (arrow,enter,join,log in,login,sign in,sign up,sign-in,signin,signup)' ),
				array( 'fas fa-sign-out-alt' => 'Alternate Sign Out (arrow,exit,leave,log out,logout,sign-out)' ),
				array( 'fas fa-signal' => 'signal (bars,graph,online,reception,status)' ),
				array( 'fas fa-smoking-ban' => 'Smoking Ban (ban,cancel,no smoking,non-smoking)' ),
				array( 'fas fa-star' => 'Star (achievement,award,favorite,important,night,rating,score)' ),
				array( 'far fa-star' => 'Star (achievement,award,favorite,important,night,rating,score)' ),
				array( 'fas fa-star-half' => 'star-half (achievement,award,rating,score,star-half-empty,star-half-full)' ),
				array( 'far fa-star-half' => 'star-half (achievement,award,rating,score,star-half-empty,star-half-full)' ),
				array( 'fas fa-star-half-alt' => 'Alternate Star Half (achievement,award,rating,score,star-half-empty,star-half-full)' ),
				array( 'fas fa-stream' => 'Stream (flow,list,timeline)' ),
				array( 'fas fa-thermometer-empty' => 'Thermometer Empty (cold,mercury,status,temperature)' ),
				array( 'fas fa-thermometer-full' => 'Thermometer Full (fever,hot,mercury,status,temperature)' ),
				array( 'fas fa-thermometer-half' => 'Thermometer 1/2 Full (mercury,status,temperature)' ),
				array( 'fas fa-thermometer-quarter' => 'Thermometer 1/4 Full (mercury,status,temperature)' ),
				array( 'fas fa-thermometer-three-quarters' => 'Thermometer 3/4 Full (mercury,status,temperature)' ),
				array( 'fas fa-thumbs-down' => 'thumbs-down (disagree,disapprove,dislike,hand,social,thumbs-o-down)' ),
				array( 'far fa-thumbs-down' => 'thumbs-down (disagree,disapprove,dislike,hand,social,thumbs-o-down)' ),
				array( 'fas fa-thumbs-up' => 'thumbs-up (agree,approve,favorite,hand,like,ok,okay,social,success,thumbs-o-up,yes,you got it dude)' ),
				array( 'far fa-thumbs-up' => 'thumbs-up (agree,approve,favorite,hand,like,ok,okay,social,success,thumbs-o-up,yes,you got it dude)' ),
				array( 'fas fa-tint' => 'tint (color,drop,droplet,raindrop,waterdrop)' ),
				array( 'fas fa-tint-slash' => 'Tint Slash (color,drop,droplet,raindrop,waterdrop)' ),
				array( 'fas fa-toggle-off' => 'Toggle Off (switch)' ),
				array( 'fas fa-toggle-on' => 'Toggle On (switch)' ),
				array( 'fas fa-unlock' => 'unlock (admin,lock,password,private,protect)' ),
				array( 'fas fa-unlock-alt' => 'Alternate Unlock (admin,lock,password,private,protect)' ),
				array( 'fas fa-user' => 'User (account,avatar,head,human,man,person,profile)' ),
				array( 'far fa-user' => 'User (account,avatar,head,human,man,person,profile)' ),
				array( 'fas fa-user-alt' => 'Alternate User (account,avatar,head,human,man,person,profile)' ),
				array( 'fas fa-user-alt-slash' => 'Alternate User Slash (account,avatar,head,human,man,person,profile)' ),
				array( 'fas fa-user-slash' => 'User Slash (ban,delete,remove)' ),
				array( 'fas fa-video' => 'Video (camera,film,movie,record,video-camera)' ),
				array( 'fas fa-video-slash' => 'Video Slash (add,create,film,new,positive,record,video)' ),
				array( 'fas fa-volume-down' => 'Volume Down (audio,lower,music,quieter,sound,speaker)' ),
				array( 'fas fa-volume-mute' => 'Volume Mute (audio,music,quiet,sound,speaker)' ),
				array( 'fas fa-volume-off' => 'Volume Off (audio,ban,music,mute,quiet,silent,sound)' ),
				array( 'fas fa-volume-up' => 'Volume Up (audio,higher,louder,music,sound,speaker)' ),
				array( 'fas fa-wifi' => 'WiFi (connection,hotspot,internet,network,wireless)' ),
			),
			'Summer'              => array(
				array( 'fas fa-anchor' => 'Anchor (berth,boat,dock,embed,link,maritime,moor,secure)' ),
				array( 'fas fa-biking' => 'Biking (bicycle,bike,cycle,cycling,ride,wheel)' ),
				array( 'fas fa-fish' => 'Fish (fauna,gold,seafood,swimming)' ),
				array( 'fas fa-hotdog' => 'Hot Dog (bun,chili,frankfurt,frankfurter,kosher,polish,sandwich,sausage,vienna,weiner)' ),
				array( 'fas fa-ice-cream' => 'Ice Cream (chocolate,cone,dessert,frozen,scoop,sorbet,vanilla,yogurt)' ),
				array( 'fas fa-lemon' => 'Lemon (citrus,lemonade,lime,tart)' ),
				array( 'far fa-lemon' => 'Lemon (citrus,lemonade,lime,tart)' ),
				array( 'fas fa-sun' => 'Sun (brighten,contrast,day,lighter,sol,solar,star,weather)' ),
				array( 'far fa-sun' => 'Sun (brighten,contrast,day,lighter,sol,solar,star,weather)' ),
				array( 'fas fa-swimmer' => 'Swimmer (athlete,head,man,olympics,person,pool,water)' ),
				array( 'fas fa-swimming-pool' => 'Swimming Pool (ladder,recreation,swim,water)' ),
				array( 'fas fa-umbrella-beach' => 'Umbrella Beach (protection,recreation,sand,shade,summer,sun)' ),
				array( 'fas fa-volleyball-ball' => 'Volleyball Ball (beach,olympics,sport)' ),
				array( 'fas fa-water' => 'Water (lake,liquid,ocean,sea,swim,wet)' ),
			),
			'Tabletop Gaming'     => array(
				array( 'fab fa-acquisitions-incorporated' => 'Acquisitions Incorporated (Dungeons & Dragons,d&d,dnd,fantasy,game,gaming,tabletop)' ),
				array( 'fas fa-book-dead' => 'Book of the Dead (Dungeons & Dragons,crossbones,d&d,dark arts,death,dnd,documentation,evil,fantasy,halloween,holiday,necronomicon,read,skull,spell)' ),
				array( 'fab fa-critical-role' => 'Critical Role (Dungeons & Dragons,d&d,dnd,fantasy,game,gaming,tabletop)' ),
				array( 'fab fa-d-and-d' => 'Dungeons & Dragons' ),
				array( 'fab fa-d-and-d-beyond' => 'D&D Beyond (Dungeons & Dragons,d&d,dnd,fantasy,gaming,tabletop)' ),
				array( 'fas fa-dice-d20' => 'Dice D20 (Dungeons & Dragons,chance,d&d,dnd,fantasy,gambling,game,roll)' ),
				array( 'fas fa-dice-d6' => 'Dice D6 (Dungeons & Dragons,chance,d&d,dnd,fantasy,gambling,game,roll)' ),
				array( 'fas fa-dragon' => 'Dragon (Dungeons & Dragons,d&d,dnd,fantasy,fire,lizard,serpent)' ),
				array( 'fas fa-dungeon' => 'Dungeon (Dungeons & Dragons,building,d&d,dnd,door,entrance,fantasy,gate)' ),
				array( 'fab fa-fantasy-flight-games' => 'Fantasy Flight-games (Dungeons & Dragons,d&d,dnd,fantasy,game,gaming,tabletop)' ),
				array( 'fas fa-fist-raised' => 'Raised Fist (Dungeons & Dragons,d&d,dnd,fantasy,hand,ki,monk,resist,strength,unarmed combat)' ),
				array( 'fas fa-hat-wizard' => 'Wizard\'s Hat (Dungeons & Dragons,accessory,buckle,clothing,d&d,dnd,fantasy,halloween,head,holiday,mage,magic,pointy,witch)' ),
				array( 'fab fa-penny-arcade' => 'Penny Arcade (Dungeons & Dragons,d&d,dnd,fantasy,game,gaming,pax,tabletop)' ),
				array( 'fas fa-ring' => 'Ring (Dungeons & Dragons,Gollum,band,binding,d&d,dnd,engagement,fantasy,gold,jewelry,marriage,precious)' ),
				array( 'fas fa-scroll' => 'Scroll (Dungeons & Dragons,announcement,d&d,dnd,fantasy,paper,script)' ),
				array( 'fas fa-skull-crossbones' => 'Skull & Crossbones (Dungeons & Dragons,alert,bones,d&d,danger,dead,deadly,death,dnd,fantasy,halloween,holiday,jolly-roger,pirate,poison,skeleton,warning)' ),
				array( 'fab fa-wizards-of-the-coast' => 'Wizards of the Coast (Dungeons & Dragons,d&d,dnd,fantasy,game,gaming,tabletop)' ),
			),
			'Toggle'              => array(
				array( 'fas fa-bullseye' => 'Bullseye (archery,goal,objective,target)' ),
				array( 'fas fa-check-circle' => 'Check Circle (accept,agree,confirm,correct,done,ok,select,success,tick,todo,yes)' ),
				array( 'far fa-check-circle' => 'Check Circle (accept,agree,confirm,correct,done,ok,select,success,tick,todo,yes)' ),
				array( 'fas fa-circle' => 'Circle (circle-thin,diameter,dot,ellipse,notification,round)' ),
				array( 'far fa-circle' => 'Circle (circle-thin,diameter,dot,ellipse,notification,round)' ),
				array( 'fas fa-dot-circle' => 'Dot Circle (bullseye,notification,target)' ),
				array( 'far fa-dot-circle' => 'Dot Circle (bullseye,notification,target)' ),
				array( 'fas fa-microphone' => 'microphone (audio,podcast,record,sing,sound,voice)' ),
				array( 'fas fa-microphone-slash' => 'Microphone Slash (audio,disable,mute,podcast,record,sing,sound,voice)' ),
				array( 'fas fa-star' => 'Star (achievement,award,favorite,important,night,rating,score)' ),
				array( 'far fa-star' => 'Star (achievement,award,favorite,important,night,rating,score)' ),
				array( 'fas fa-star-half' => 'star-half (achievement,award,rating,score,star-half-empty,star-half-full)' ),
				array( 'far fa-star-half' => 'star-half (achievement,award,rating,score,star-half-empty,star-half-full)' ),
				array( 'fas fa-star-half-alt' => 'Alternate Star Half (achievement,award,rating,score,star-half-empty,star-half-full)' ),
				array( 'fas fa-toggle-off' => 'Toggle Off (switch)' ),
				array( 'fas fa-toggle-on' => 'Toggle On (switch)' ),
				array( 'fas fa-wifi' => 'WiFi (connection,hotspot,internet,network,wireless)' ),
			),
			'Travel'              => array(
				array( 'fas fa-archway' => 'Archway (arc,monument,road,street,tunnel)' ),
				array( 'fas fa-atlas' => 'Atlas (book,directions,geography,globe,map,travel,wayfinding)' ),
				array( 'fas fa-bed' => 'Bed (lodging,rest,sleep,travel)' ),
				array( 'fas fa-bus' => 'Bus (public transportation,transportation,travel,vehicle)' ),
				array( 'fas fa-bus-alt' => 'Bus Alt (mta,public transportation,transportation,travel,vehicle)' ),
				array( 'fas fa-cocktail' => 'Cocktail (alcohol,beverage,drink,gin,glass,margarita,martini,vodka)' ),
				array( 'fas fa-concierge-bell' => 'Concierge Bell (attention,hotel,receptionist,service,support)' ),
				array( 'fas fa-dumbbell' => 'Dumbbell (exercise,gym,strength,weight,weight-lifting)' ),
				array( 'fas fa-glass-martini' => 'Martini Glass (alcohol,bar,beverage,drink,liquor)' ),
				array( 'fas fa-glass-martini-alt' => 'Alternate Glass Martini (alcohol,bar,beverage,drink,liquor)' ),
				array( 'fas fa-globe-africa' => 'Globe with Africa shown (all,country,earth,global,gps,language,localize,location,map,online,place,planet,translate,travel,world)' ),
				array( 'fas fa-globe-americas' => 'Globe with Americas shown (all,country,earth,global,gps,language,localize,location,map,online,place,planet,translate,travel,world)' ),
				array( 'fas fa-globe-asia' => 'Globe with Asia shown (all,country,earth,global,gps,language,localize,location,map,online,place,planet,translate,travel,world)' ),
				array( 'fas fa-globe-europe' => 'Globe with Europe shown (all,country,earth,global,gps,language,localize,location,map,online,place,planet,translate,travel,world)' ),
				array( 'fas fa-hot-tub' => 'Hot Tub (bath,jacuzzi,massage,sauna,spa)' ),
				array( 'fas fa-hotel' => 'Hotel (building,inn,lodging,motel,resort,travel)' ),
				array( 'fas fa-luggage-cart' => 'Luggage Cart (bag,baggage,suitcase,travel)' ),
				array( 'fas fa-map' => 'Map (address,coordinates,destination,gps,localize,location,map,navigation,paper,pin,place,point of interest,position,route,travel)' ),
				array( 'far fa-map' => 'Map (address,coordinates,destination,gps,localize,location,map,navigation,paper,pin,place,point of interest,position,route,travel)' ),
				array( 'fas fa-map-marked' => 'Map Marked (address,coordinates,destination,gps,localize,location,map,navigation,paper,pin,place,point of interest,position,route,travel)' ),
				array( 'fas fa-map-marked-alt' => 'Alternate Map Marked (address,coordinates,destination,gps,localize,location,map,navigation,paper,pin,place,point of interest,position,route,travel)' ),
				array( 'fas fa-monument' => 'Monument (building,historic,landmark,memorable)' ),
				array( 'fas fa-passport' => 'Passport (document,id,identification,issued,travel)' ),
				array( 'fas fa-plane' => 'plane (airplane,destination,fly,location,mode,travel,trip)' ),
				array( 'fas fa-plane-arrival' => 'Plane Arrival (airplane,arriving,destination,fly,land,landing,location,mode,travel,trip)' ),
				array( 'fas fa-plane-departure' => 'Plane Departure (airplane,departing,destination,fly,location,mode,take off,taking off,travel,trip)' ),
				array( 'fas fa-shuttle-van' => 'Shuttle Van (airport,machine,public-transportation,transportation,travel,vehicle)' ),
				array( 'fas fa-spa' => 'Spa (flora,massage,mindfulness,plant,wellness)' ),
				array( 'fas fa-suitcase' => 'Suitcase (baggage,luggage,move,suitcase,travel,trip)' ),
				array( 'fas fa-suitcase-rolling' => 'Suitcase Rolling (baggage,luggage,move,suitcase,travel,trip)' ),
				array( 'fas fa-swimmer' => 'Swimmer (athlete,head,man,olympics,person,pool,water)' ),
				array( 'fas fa-swimming-pool' => 'Swimming Pool (ladder,recreation,swim,water)' ),
				array( 'fas fa-taxi' => 'Taxi (cab,cabbie,car,car service,lyft,machine,transportation,travel,uber,vehicle)' ),
				array( 'fas fa-tram' => 'Tram (crossing,machine,mountains,seasonal,transportation)' ),
				array( 'fas fa-tv' => 'Television (computer,display,monitor,television)' ),
				array( 'fas fa-umbrella-beach' => 'Umbrella Beach (protection,recreation,sand,shade,summer,sun)' ),
				array( 'fas fa-wine-glass' => 'Wine Glass (alcohol,beverage,cabernet,drink,grapes,merlot,sauvignon)' ),
				array( 'fas fa-wine-glass-alt' => 'Alternate Wine Glas (alcohol,beverage,cabernet,drink,grapes,merlot,sauvignon)' ),
			),
			'Users & People'      => array(
				array( 'fab fa-accessible-icon' => 'Accessible Icon (accessibility,handicap,person,wheelchair,wheelchair-alt)' ),
				array( 'fas fa-address-book' => 'Address Book (contact,directory,index,little black book,rolodex)' ),
				array( 'far fa-address-book' => 'Address Book (contact,directory,index,little black book,rolodex)' ),
				array( 'fas fa-address-card' => 'Address Card (about,contact,id,identification,postcard,profile)' ),
				array( 'far fa-address-card' => 'Address Card (about,contact,id,identification,postcard,profile)' ),
				array( 'fas fa-baby' => 'Baby (child,diaper,doll,human,infant,kid,offspring,person,sprout)' ),
				array( 'fas fa-bed' => 'Bed (lodging,rest,sleep,travel)' ),
				array( 'fas fa-biking' => 'Biking (bicycle,bike,cycle,cycling,ride,wheel)' ),
				array( 'fas fa-blind' => 'Blind (cane,disability,person,sight)' ),
				array( 'fas fa-chalkboard-teacher' => 'Chalkboard Teacher (blackboard,instructor,learning,professor,school,whiteboard,writing)' ),
				array( 'fas fa-child' => 'Child (boy,girl,kid,toddler,young)' ),
				array( 'fas fa-female' => 'Female (human,person,profile,user,woman)' ),
				array( 'fas fa-frown' => 'Frowning Face (disapprove,emoticon,face,rating,sad)' ),
				array( 'far fa-frown' => 'Frowning Face (disapprove,emoticon,face,rating,sad)' ),
				array( 'fas fa-hiking' => 'Hiking (activity,backpack,fall,fitness,outdoors,person,seasonal,walking)' ),
				array( 'fas fa-id-badge' => 'Identification Badge (address,contact,identification,license,profile)' ),
				array( 'far fa-id-badge' => 'Identification Badge (address,contact,identification,license,profile)' ),
				array( 'fas fa-id-card' => 'Identification Card (contact,demographics,document,identification,issued,profile)' ),
				array( 'far fa-id-card' => 'Identification Card (contact,demographics,document,identification,issued,profile)' ),
				array( 'fas fa-id-card-alt' => 'Alternate Identification Card (contact,demographics,document,identification,issued,profile)' ),
				array( 'fas fa-male' => 'Male (human,man,person,profile,user)' ),
				array( 'fas fa-meh' => 'Neutral Face (emoticon,face,neutral,rating)' ),
				array( 'far fa-meh' => 'Neutral Face (emoticon,face,neutral,rating)' ),
				array( 'fas fa-people-carry' => 'People Carry (box,carry,fragile,help,movers,package)' ),
				array( 'fas fa-person-booth' => 'Person Entering Booth (changing,changing room,election,human,person,vote,voting)' ),
				array( 'fas fa-poo' => 'Poo (crap,poop,shit,smile,turd)' ),
				array( 'fas fa-portrait' => 'Portrait (id,image,photo,picture,selfie)' ),
				array( 'fas fa-power-off' => 'Power Off (cancel,computer,on,reboot,restart)' ),
				array( 'fas fa-pray' => 'Pray (kneel,preach,religion,worship)' ),
				array( 'fas fa-restroom' => 'Restroom (bathroom,john,loo,potty,washroom,waste,wc)' ),
				array( 'fas fa-running' => 'Running (exercise,health,jog,person,run,sport,sprint)' ),
				array( 'fas fa-skating' => 'Skating (activity,figure skating,fitness,ice,person,winter)' ),
				array( 'fas fa-skiing' => 'Skiing (activity,downhill,fast,fitness,olympics,outdoors,person,seasonal,slalom)' ),
				array( 'fas fa-skiing-nordic' => 'Skiing Nordic (activity,cross country,fitness,outdoors,person,seasonal)' ),
				array( 'fas fa-smile' => 'Smiling Face (approve,emoticon,face,happy,rating,satisfied)' ),
				array( 'far fa-smile' => 'Smiling Face (approve,emoticon,face,happy,rating,satisfied)' ),
				array( 'fas fa-snowboarding' => 'Snowboarding (activity,fitness,olympics,outdoors,person)' ),
				array( 'fas fa-street-view' => 'Street View (directions,location,map,navigation)' ),
				array( 'fas fa-swimmer' => 'Swimmer (athlete,head,man,olympics,person,pool,water)' ),
				array( 'fas fa-user' => 'User (account,avatar,head,human,man,person,profile)' ),
				array( 'far fa-user' => 'User (account,avatar,head,human,man,person,profile)' ),
				array( 'fas fa-user-alt' => 'Alternate User (account,avatar,head,human,man,person,profile)' ),
				array( 'fas fa-user-alt-slash' => 'Alternate User Slash (account,avatar,head,human,man,person,profile)' ),
				array( 'fas fa-user-astronaut' => 'User Astronaut (avatar,clothing,cosmonaut,nasa,space,suit)' ),
				array( 'fas fa-user-check' => 'User Check (accept,check,person,verified)' ),
				array( 'fas fa-user-circle' => 'User Circle (account,avatar,head,human,man,person,profile)' ),
				array( 'far fa-user-circle' => 'User Circle (account,avatar,head,human,man,person,profile)' ),
				array( 'fas fa-user-clock' => 'User Clock (alert,person,remind,time)' ),
				array( 'fas fa-user-cog' => 'User Cog (admin,cog,person,settings)' ),
				array( 'fas fa-user-edit' => 'User Edit (edit,pen,pencil,person,update,write)' ),
				array( 'fas fa-user-friends' => 'User Friends (group,people,person,team,users)' ),
				array( 'fas fa-user-graduate' => 'User Graduate (cap,clothing,commencement,gown,graduation,person,student)' ),
				array( 'fas fa-user-injured' => 'User Injured (cast,injury,ouch,patient,person,sling)' ),
				array( 'fas fa-user-lock' => 'User Lock (admin,lock,person,private,unlock)' ),
				array( 'fas fa-user-md' => 'Doctor (job,medical,nurse,occupation,physician,profile,surgeon)' ),
				array( 'fas fa-user-minus' => 'User Minus (delete,negative,remove)' ),
				array( 'fas fa-user-ninja' => 'User Ninja (assassin,avatar,dangerous,deadly,sneaky)' ),
				array( 'fas fa-user-nurse' => 'Nurse (doctor,midwife,practitioner,surgeon)' ),
				array( 'fas fa-user-plus' => 'User Plus (add,avatar,positive,sign up,signup,team)' ),
				array( 'fas fa-user-secret' => 'User Secret (clothing,coat,hat,incognito,person,privacy,spy,whisper)' ),
				array( 'fas fa-user-shield' => 'User Shield (admin,person,private,protect,safe)' ),
				array( 'fas fa-user-slash' => 'User Slash (ban,delete,remove)' ),
				array( 'fas fa-user-tag' => 'User Tag (avatar,discount,label,person,role,special)' ),
				array( 'fas fa-user-tie' => 'User Tie (avatar,business,clothing,formal,professional,suit)' ),
				array( 'fas fa-user-times' => 'Remove User (archive,delete,remove,x)' ),
				array( 'fas fa-users' => 'Users (friends,group,people,persons,profiles,team)' ),
				array( 'fas fa-users-cog' => 'Users Cog (admin,cog,group,person,settings,team)' ),
				array( 'fas fa-walking' => 'Walking (exercise,health,pedometer,person,steps)' ),
				array( 'fas fa-wheelchair' => 'Wheelchair (accessible,handicap,person)' ),
			),
			'Vehicles'            => array(
				array( 'fab fa-accessible-icon' => 'Accessible Icon (accessibility,handicap,person,wheelchair,wheelchair-alt)' ),
				array( 'fas fa-ambulance' => 'ambulance (emergency,emt,er,help,hospital,support,vehicle)' ),
				array( 'fas fa-baby-carriage' => 'Baby Carriage (buggy,carrier,infant,push,stroller,transportation,walk,wheels)' ),
				array( 'fas fa-bicycle' => 'Bicycle (bike,gears,pedal,transportation,vehicle)' ),
				array( 'fas fa-bus' => 'Bus (public transportation,transportation,travel,vehicle)' ),
				array( 'fas fa-bus-alt' => 'Bus Alt (mta,public transportation,transportation,travel,vehicle)' ),
				array( 'fas fa-car' => 'Car (auto,automobile,sedan,transportation,travel,vehicle)' ),
				array( 'fas fa-car-alt' => 'Alternate Car (auto,automobile,sedan,transportation,travel,vehicle)' ),
				array( 'fas fa-car-crash' => 'Car Crash (accident,auto,automobile,insurance,sedan,transportation,vehicle,wreck)' ),
				array( 'fas fa-car-side' => 'Car Side (auto,automobile,sedan,transportation,travel,vehicle)' ),
				array( 'fas fa-fighter-jet' => 'fighter-jet (airplane,fast,fly,goose,maverick,plane,quick,top gun,transportation,travel)' ),
				array( 'fas fa-helicopter' => 'Helicopter (airwolf,apache,chopper,flight,fly,travel)' ),
				array( 'fas fa-horse' => 'Horse (equus,fauna,mammmal,mare,neigh,pony)' ),
				array( 'fas fa-motorcycle' => 'Motorcycle (bike,machine,transportation,vehicle)' ),
				array( 'fas fa-paper-plane' => 'Paper Plane (air,float,fold,mail,paper,send)' ),
				array( 'far fa-paper-plane' => 'Paper Plane (air,float,fold,mail,paper,send)' ),
				array( 'fas fa-plane' => 'plane (airplane,destination,fly,location,mode,travel,trip)' ),
				array( 'fas fa-rocket' => 'rocket (aircraft,app,jet,launch,nasa,space)' ),
				array( 'fas fa-ship' => 'Ship (boat,sea,water)' ),
				array( 'fas fa-shopping-cart' => 'shopping-cart (buy,checkout,grocery,payment,purchase)' ),
				array( 'fas fa-shuttle-van' => 'Shuttle Van (airport,machine,public-transportation,transportation,travel,vehicle)' ),
				array( 'fas fa-sleigh' => 'Sleigh (christmas,claus,fly,holiday,santa,sled,snow,xmas)' ),
				array( 'fas fa-snowplow' => 'Snowplow (clean up,cold,road,storm,winter)' ),
				array( 'fas fa-space-shuttle' => 'Space Shuttle (astronaut,machine,nasa,rocket,transportation)' ),
				array( 'fas fa-subway' => 'Subway (machine,railway,train,transportation,vehicle)' ),
				array( 'fas fa-taxi' => 'Taxi (cab,cabbie,car,car service,lyft,machine,transportation,travel,uber,vehicle)' ),
				array( 'fas fa-tractor' => 'Tractor (agriculture,farm,vehicle)' ),
				array( 'fas fa-train' => 'Train (bullet,commute,locomotive,railway,subway)' ),
				array( 'fas fa-tram' => 'Tram (crossing,machine,mountains,seasonal,transportation)' ),
				array( 'fas fa-truck' => 'truck (cargo,delivery,shipping,vehicle)' ),
				array( 'fas fa-truck-monster' => 'Truck Monster (offroad,vehicle,wheel)' ),
				array( 'fas fa-truck-pickup' => 'Truck Side (cargo,vehicle)' ),
				array( 'fas fa-wheelchair' => 'Wheelchair (accessible,handicap,person)' ),
			),
			'Weather'             => array(
				array( 'fas fa-bolt' => 'Lightning Bolt (electricity,lightning,weather,zap)' ),
				array( 'fas fa-cloud' => 'Cloud (atmosphere,fog,overcast,save,upload,weather)' ),
				array( 'fas fa-cloud-meatball' => 'Cloud with (a chance of) Meatball (FLDSMDFR,food,spaghetti,storm)' ),
				array( 'fas fa-cloud-moon' => 'Cloud with Moon (crescent,evening,lunar,night,partly cloudy,sky)' ),
				array( 'fas fa-cloud-moon-rain' => 'Cloud with Moon and Rain (crescent,evening,lunar,night,partly cloudy,precipitation,rain,sky,storm)' ),
				array( 'fas fa-cloud-rain' => 'Cloud with Rain (precipitation,rain,sky,storm)' ),
				array( 'fas fa-cloud-showers-heavy' => 'Cloud with Heavy Showers (precipitation,rain,sky,storm)' ),
				array( 'fas fa-cloud-sun' => 'Cloud with Sun (clear,day,daytime,fall,outdoors,overcast,partly cloudy)' ),
				array( 'fas fa-cloud-sun-rain' => 'Cloud with Sun and Rain (day,overcast,precipitation,storm,summer,sunshower)' ),
				array( 'fas fa-meteor' => 'Meteor (armageddon,asteroid,comet,shooting star,space)' ),
				array( 'fas fa-moon' => 'Moon (contrast,crescent,dark,lunar,night)' ),
				array( 'far fa-moon' => 'Moon (contrast,crescent,dark,lunar,night)' ),
				array( 'fas fa-poo-storm' => 'Poo Storm (bolt,cloud,euphemism,lightning,mess,poop,shit,turd)' ),
				array( 'fas fa-rainbow' => 'Rainbow (gold,leprechaun,prism,rain,sky)' ),
				array( 'fas fa-smog' => 'Smog (dragon,fog,haze,pollution,smoke,weather)' ),
				array( 'fas fa-snowflake' => 'Snowflake (precipitation,rain,winter)' ),
				array( 'far fa-snowflake' => 'Snowflake (precipitation,rain,winter)' ),
				array( 'fas fa-sun' => 'Sun (brighten,contrast,day,lighter,sol,solar,star,weather)' ),
				array( 'far fa-sun' => 'Sun (brighten,contrast,day,lighter,sol,solar,star,weather)' ),
				array( 'fas fa-temperature-high' => 'High Temperature (cook,mercury,summer,thermometer,warm)' ),
				array( 'fas fa-temperature-low' => 'Low Temperature (cold,cool,mercury,thermometer,winter)' ),
				array( 'fas fa-umbrella' => 'Umbrella (protection,rain,storm,wet)' ),
				array( 'fas fa-water' => 'Water (lake,liquid,ocean,sea,swim,wet)' ),
				array( 'fas fa-wind' => 'Wind (air,blow,breeze,fall,seasonal,weather)' ),
			),
			'Winter'              => array(
				array( 'fas fa-glass-whiskey' => 'Glass Whiskey (alcohol,bar,beverage,bourbon,drink,liquor,neat,rye,scotch,whisky)' ),
				array( 'fas fa-icicles' => 'Icicles (cold,frozen,hanging,ice,seasonal,sharp)' ),
				array( 'fas fa-igloo' => 'Igloo (dome,dwelling,eskimo,home,house,ice,snow)' ),
				array( 'fas fa-mitten' => 'Mitten (clothing,cold,glove,hands,knitted,seasonal,warmth)' ),
				array( 'fas fa-skating' => 'Skating (activity,figure skating,fitness,ice,person,winter)' ),
				array( 'fas fa-skiing' => 'Skiing (activity,downhill,fast,fitness,olympics,outdoors,person,seasonal,slalom)' ),
				array( 'fas fa-skiing-nordic' => 'Skiing Nordic (activity,cross country,fitness,outdoors,person,seasonal)' ),
				array( 'fas fa-snowboarding' => 'Snowboarding (activity,fitness,olympics,outdoors,person)' ),
				array( 'fas fa-snowplow' => 'Snowplow (clean up,cold,road,storm,winter)' ),
				array( 'fas fa-tram' => 'Tram (crossing,machine,mountains,seasonal,transportation)' ),
			),
			'Writing'             => array(
				array( 'fas fa-archive' => 'Archive (box,package,save,storage)' ),
				array( 'fas fa-blog' => 'Blog (journal,log,online,personal,post,web 2.0,wordpress,writing)' ),
				array( 'fas fa-book' => 'book (diary,documentation,journal,library,read)' ),
				array( 'fas fa-bookmark' => 'bookmark (favorite,marker,read,remember,save)' ),
				array( 'far fa-bookmark' => 'bookmark (favorite,marker,read,remember,save)' ),
				array( 'fas fa-edit' => 'Edit (edit,pen,pencil,update,write)' ),
				array( 'far fa-edit' => 'Edit (edit,pen,pencil,update,write)' ),
				array( 'fas fa-envelope' => 'Envelope (e-mail,email,letter,mail,message,notification,support)' ),
				array( 'far fa-envelope' => 'Envelope (e-mail,email,letter,mail,message,notification,support)' ),
				array( 'fas fa-envelope-open' => 'Envelope Open (e-mail,email,letter,mail,message,notification,support)' ),
				array( 'far fa-envelope-open' => 'Envelope Open (e-mail,email,letter,mail,message,notification,support)' ),
				array( 'fas fa-eraser' => 'eraser (art,delete,remove,rubber)' ),
				array( 'fas fa-file' => 'File (document,new,page,pdf,resume)' ),
				array( 'far fa-file' => 'File (document,new,page,pdf,resume)' ),
				array( 'fas fa-file-alt' => 'Alternate File (document,file-text,invoice,new,page,pdf)' ),
				array( 'far fa-file-alt' => 'Alternate File (document,file-text,invoice,new,page,pdf)' ),
				array( 'fas fa-folder' => 'Folder (archive,directory,document,file)' ),
				array( 'far fa-folder' => 'Folder (archive,directory,document,file)' ),
				array( 'fas fa-folder-open' => 'Folder Open (archive,directory,document,empty,file,new)' ),
				array( 'far fa-folder-open' => 'Folder Open (archive,directory,document,empty,file,new)' ),
				array( 'fas fa-keyboard' => 'Keyboard (accessory,edit,input,text,type,write)' ),
				array( 'far fa-keyboard' => 'Keyboard (accessory,edit,input,text,type,write)' ),
				array( 'fas fa-newspaper' => 'Newspaper (article,editorial,headline,journal,journalism,news,press)' ),
				array( 'far fa-newspaper' => 'Newspaper (article,editorial,headline,journal,journalism,news,press)' ),
				array( 'fas fa-paper-plane' => 'Paper Plane (air,float,fold,mail,paper,send)' ),
				array( 'far fa-paper-plane' => 'Paper Plane (air,float,fold,mail,paper,send)' ),
				array( 'fas fa-paperclip' => 'Paperclip (attach,attachment,connect,link)' ),
				array( 'fas fa-paragraph' => 'paragraph (edit,format,text,writing)' ),
				array( 'fas fa-pen' => 'Pen (design,edit,update,write)' ),
				array( 'fas fa-pen-alt' => 'Alternate Pen (design,edit,update,write)' ),
				array( 'fas fa-pen-square' => 'Pen Square (edit,pencil-square,update,write)' ),
				array( 'fas fa-pencil-alt' => 'Alternate Pencil (design,edit,pencil,update,write)' ),
				array( 'fas fa-quote-left' => 'quote-left (mention,note,phrase,text,type)' ),
				array( 'fas fa-quote-right' => 'quote-right (mention,note,phrase,text,type)' ),
				array( 'fas fa-sticky-note' => 'Sticky Note (message,note,paper,reminder,sticker)' ),
				array( 'far fa-sticky-note' => 'Sticky Note (message,note,paper,reminder,sticker)' ),
				array( 'fas fa-thumbtack' => 'Thumbtack (coordinates,location,marker,pin,thumb-tack)' ),
			),
			'Other'               => array(
				array( 'fas fa-backspace' => 'Backspace (command,delete,erase,keyboard,undo)' ),
				array( 'fas fa-blender-phone' => 'Blender Phone (appliance,cocktail,communication,fantasy,milkshake,mixer,puree,silly,smoothie)' ),
				array( 'fas fa-crown' => 'Crown (award,favorite,king,queen,royal,tiara)' ),
				array( 'fas fa-dumpster-fire' => 'Dumpster Fire (alley,bin,commercial,danger,dangerous,euphemism,flame,heat,hot,trash,waste)' ),
				array( 'fas fa-file-csv' => 'File CSV (document,excel,numbers,spreadsheets,table)' ),
				array( 'fas fa-network-wired' => 'Wired Network (computer,connect,ethernet,internet,intranet)' ),
				array( 'fas fa-signature' => 'Signature (John Hancock,cursive,name,writing)' ),
				array( 'fas fa-skull' => 'Skull (bones,skeleton,x-ray,yorick)' ),
				array( 'fas fa-vr-cardboard' => 'Cardboard VR (3d,augment,google,reality,virtual)' ),
				array( 'fas fa-weight-hanging' => 'Hanging Weight (anvil,heavy,measurement)' ),
			),
		);
		return $fontawesome_icons;
	}
}

if ( ! function_exists( 'ciyashop_breadcrumbs' ) ) {
	/**
	 * Ciyashop breadcrumbs
	 *
	 * @param string $class .
	 */
	function ciyashop_breadcrumbs( $class = '' ) {
		global $ciyashop_options;

		// Do not display on the homepage.
		if ( is_front_page() ) {
			return;
		}

		// Get the query & post information.
		global $post, $wp_query;

		$breadcrums_id    = 'ciyashop_breadcrumbs';
		$breadcrums_class = 'ciyashop_breadcrumbs page-breadcrumb breadcrumbs';
		$home_title       = __( 'Home', 'ciyashop' );
		$parents          = '';

		if ( $class ) {
			$breadcrums_class .= ' ' . $class;
		}

		// If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat).
		$custom_taxonomy = array();

		$output  = '';
		$output .= '<ul id="' . $breadcrums_id . '" class="' . $breadcrums_class . '">';

		// Home page.
		$output .= '<li class="home"><span class="item-element"><a class="bread-link bread-home" href="' . get_home_url() . '" title="' . $home_title . '">' . $home_title . '</a></span></li>';

		if ( is_home() ) {
			$page_id = get_queried_object_id();
			$output .= '<li class="current-item item-home-page"><span class="item-element">';
			$output .= get_the_title( $page_id );
			$output .= '</span></li>';
		}
		if ( is_archive() && ! is_tax() && ! is_category() && ! is_tag() && ! is_author() ) {
			$output .= '<li class="current-item item-archive">';
			if ( is_day() ) :
				$output .= __( 'Daily Archives:', 'ciyashop' ) . ' <span class="item-element">' . get_the_date() . '</span>';
			elseif ( is_month() ) :
				$output .= __( 'Monthly Archives:', 'ciyashop' ) . ' <span class="item-element">' . get_the_date( _x( 'F Y', 'monthly archives date format', 'ciyashop' ) ) . '</span>';
			elseif ( is_year() ) :
				$output .= __( 'Yearly Archives:', 'ciyashop' ) . ' <span class="item-element">' . get_the_date( _x( 'Y', 'yearly archives date format', 'ciyashop' ) ) . '</span>';
			else :
				$output .= post_type_archive_title( $prefix = '', false ); // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found
			endif;
			$output .= '</li>';
		} elseif ( is_archive() && is_tax() && ! is_category() && ! is_tag() ) {
			// If post is a custom post type.
			$post_type = get_post_type();

			// If it is a custom post type display name and link.
			if ( 'post' !== $post_type ) {
				$post_type_object  = get_post_type_object( $post_type );
				$post_type_archive = get_post_type_archive_link( $post_type );
				$labels            = isset( $post_type_object->labels->name ) ? $post_type_object->labels->name : __( 'Archive Page', 'ciyashop' );

				$output .= '<li class="item-cat item-custom-post-type-' . $post_type . '"><span class="item-element"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $labels . '">' . $labels . '</a></span></li>';
			}

			$custom_tax_name = get_queried_object()->name;
			$output         .= '<li class="current-item item-archive"><span class="item-element">' . $custom_tax_name . '</span></li>';
		} elseif ( is_single() ) {
			// If post is a custom post type.
			$post_type = get_post_type();

			// If it is a custom post type display name and link.
			if ( 'post' !== $post_type ) {
				$post_type_object  = get_post_type_object( $post_type );
				$post_type_archive = get_post_type_archive_link( $post_type );
				$output           .= '<li class="item-cat item-custom-post-type-' . $post_type . '"><span class="item-element"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></span></li>';
			}

			// Get post category info.
			$category = get_the_category();
			if ( 'product' === $post_type ) {
				$category = get_the_terms( get_the_ID(), 'product_cat' );
			}
			if ( ! empty( $category ) ) {
				// Get last category post is in.
				$values_of_category = array_values( $category );
				$last_category      = end( $values_of_category );
				// Get parent any categories and create array.
				$get_cat_parents = rtrim( get_category_parents( $last_category->term_id, true, ',' ), ',' );
				$cat_parents     = explode( ',', $get_cat_parents );
				$cat_display     = '';
				if ( 'product' === $post_type ) {
					$cats_id = array_column( get_the_terms( get_the_ID(), 'product_cat' ), 'term_id' );
					// Show only onw category.
					$cats_id = reset( $cats_id );
					if ( ! empty( $cats_id ) ) {
						$get_cat_parents = ( ! empty( $cats_id ) ) ? get_ancestors( $cats_id, 'product_cat' ) : '';
						$cat_link        = get_term_link( $cats_id );
						$last_cat        = get_term_by( 'id', $cats_id, 'product_cat' );
						if ( ! empty( $get_cat_parents ) ) {
							// Show only one category.
							$get_cat_parents  = get_term_by( 'id', $get_cat_parents[0], 'product_cat' );
							$parents_cat_link = get_term_link( $get_cat_parents->term_id, 'product_cat' );
							$cat_display     .= '<li class="item-cat"><span class="item-element"><a href="' . $parents_cat_link . '">' . $get_cat_parents->name . '</a></span></li>';
						}
						$cat_display .= '<li class="item-cat"><span class="item-element"><a href="' . $cat_link . '">' . $last_cat->name . '</a></span></li>';
					}
				}

				// Loop through parent categories and store in variable $cat_display.
				if ( ! empty( $cat_parents ) && is_array( $cat_parents ) ) {
					foreach ( $cat_parents as $parents ) {
						$cat_display .= ( empty( $parents ) ) ? '' : '<li class="item-cat"><span class="item-element">' . $parents . '</span></li>';
					}
				}
			}

			// If it's a custom post type within a custom taxonomy.
			foreach ( $custom_taxonomy as $taxonomy ) {
				$taxonomy_exists = taxonomy_exists( $taxonomy );
				if ( empty( $last_category ) && ! empty( $taxonomy ) && $taxonomy_exists ) {
					$taxonomy_terms = get_the_terms( $post->ID, $taxonomy );
					if ( isset( $taxonomy_terms[0] ) ) {
						$cat_id       = $taxonomy_terms[0]->term_id;
						$cat_nicename = $taxonomy_terms[0]->slug;
						$cat_link     = get_term_link( $taxonomy_terms[0]->term_id, $taxonomy );
						$cat_name     = $taxonomy_terms[0]->name;
					}
				}
			}

			// Check if the post is in a category.
			if ( ! empty( $last_category ) ) {
				$output .= $cat_display;
				$output .= '<li class="current-item item-' . $post->ID . '"><span class="item-element">' . get_the_title() . '</span></li>';
			} elseif ( ! empty( $cat_id ) ) {
				// Else if post is in a custom taxonomy.
				$output .= '<li class="item-cat item-cat-' . $cat_id . ' item-cat-' . $cat_nicename . '"><span class="item-element"><a class="bread-cat bread-cat-' . $cat_id . ' bread-cat-' . $cat_nicename . '" href="' . $cat_link . '" title="' . $cat_name . '">' . $cat_name . '</a></span></li>';
				$output .= '<li class="current-item item-' . $post->ID . '"><span class="item-element">' . get_the_title() . '</span></li>';
			} else {
				$output .= '<li class="current-item item-' . $post->ID . '"><span class="item-element">' . get_the_title() . '</span></li>';
			}
		} elseif ( is_category() ) {
			// Category page.
			$bread_cat_id   = get_cat_id( single_cat_title( '', false ) );
			$bread_cat      = get_category_parents( $bread_cat_id, 'true', ';' );
			$bread_cat      = explode( ';', $bread_cat );
			$bread_cat_size = sizeof( $bread_cat );
			$i              = 1;

			foreach ( $bread_cat as $bread ) {
				if ( $bread ) {
					if ( (int) $bread_cat_size === $i + 1 ) {
						$output .= '<li class="current-item item-cat"><span class="item-element">' . $bread . '</span></li>';
					} else {
						$output .= '<li class="current-item item-cat"><span class="item-element">' . $bread . '</span></li>';
					}
					$i++;
				}
			}
		} elseif ( is_page() ) {
			// Standard page.
			if ( $post->post_parent ) {
				// If child page, get parents.
				$anc = get_post_ancestors( $post->ID );

				// Get parents in the right order.
				$anc = array_reverse( $anc );

				// Parent page loop.
				foreach ( $anc as $ancestor ) {
					$parents .= '<li class="item-parent item-parent-' . $ancestor . '"><span class="item-element"><a class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink( $ancestor ) . '" title="' . get_the_title( $ancestor ) . '">' . get_the_title( $ancestor ) . '</a></span></li>';
				}

				// Display parent pages.
				$output .= $parents;

				// Current page.
				$output .= '<li class="current-item item-' . $post->ID . '"><span class="item-element">' . get_the_title() . '</span></li>';
			} else {
				// Just display current page if not parents.
				$output .= '<li class="current-item item-' . $post->ID . '"><span class="item-element">' . get_the_title() . '</span></li>';
			}
		} elseif ( is_tag() ) {
			// Tag page.

			// Get tag information.
			$term_id  = get_query_var( 'tag_id' );
			$taxonomy = 'post_tag';
			$args     = 'include=' . $term_id;
			$terms    = get_terms( $taxonomy, $args );
			if ( array_filter( $terms ) ) {
				$get_term_id   = $terms[0]->term_id;
				$get_term_slug = $terms[0]->slug;
				$get_term_name = $terms[0]->name;

				// Display the tag name.
				$output .= '<li class="current-item item-tag-' . $get_term_id . ' item-tag-' . $get_term_slug . '"><span class="item-element">' . $get_term_name . '</span></li>';
			}
		} elseif ( is_day() ) {
			// Day archive.

			// Year link.
			$output .= '<li class="item-year item-year-' . get_the_time( 'Y' ) . '"><span class="item-element"><a class="bread-year bread-year-' . get_the_time( 'Y' ) . '" href="' . get_year_link( get_the_time( 'Y' ) ) . '" title="' . get_the_time( 'Y' ) . '">' . get_the_time( 'Y' ) . '&nbsp;' . __( 'Archives', 'ciyashop' ) . '</a></span></li>';

			// Month link.
			$output .= '<li class="item-month item-month-' . get_the_time( 'm' ) . '"><span class="item-element"><a class="bread-month bread-month-' . get_the_time( 'm' ) . '" href="' . get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) . '" title="' . get_the_time( 'M' ) . '">' . get_the_time( 'M' ) . '&nbsp;' . __( '.Archives', 'ciyashop' ) . '</a></span></li>';

			// Day display.
			$output .= '<li class="current-item item-' . get_the_time( 'j' ) . '"><span class="item-element">' . get_the_time( 'jS' ) . ' ' . get_the_time( 'M' ) . '&nbsp;' . __( 'Archives', 'ciyashop' ) . '</span></li>';
		} elseif ( is_month() ) {
			// Month Archive.

			// Year link.
			$output .= '<li class="item-year item-year-' . get_the_time( 'Y' ) . '"><span class="item-element"><a class="bread-year bread-year-' . get_the_time( 'Y' ) . '" href="' . get_year_link( get_the_time( 'Y' ) ) . '" title="' . get_the_time( 'Y' ) . '">' . get_the_time( 'Y' ) . '&nbsp;' . __( 'Archives', 'ciyashop' ) . '</a></span></li>';

			// Month display.
			$output .= '<li class="item-month item-month-' . get_the_time( 'm' ) . '"><span class="item-element">' . get_the_time( 'M' ) . '&nbsp;' . __( 'Archives', 'ciyashop' ) . '</span></li>';
		} elseif ( is_year() ) {
			// Display year archive.
			$output .= '<li class="current-item item-current-' . get_the_time( 'Y' ) . '"><span class="item-element">' . get_the_time( 'Y' ) . '&nbsp;' . __( 'Archives', 'ciyashop' ) . '</span></li>';
		} elseif ( is_author() ) {
			// Auhor archive.
			// Get the author information.
			global $author;
			$userdata = get_userdata( $author );
			// Display author name.
			$output .= '<li class="current-item item-current-' . $userdata->user_nicename . '"><span class="item-element">' . ' ' . $userdata->display_name . '</span></li>';
		} elseif ( get_query_var( 'paged' ) && ! is_home() ) {
			// Paginated archives.
			$output .= '<li class="current-item item-current-' . get_query_var( 'paged' ) . '"><span class="item-element">' . __( 'Page', 'ciyashop' ) . ' ' . get_query_var( 'paged' ) . '</span></li>';
		} elseif ( is_search() ) {
			// Search results page.
			$output .= '<li class="current-item item-current-' . get_search_query() . '"><span class="item-element">' . __( 'Search results for', 'ciyashop' ) . ': ' . get_search_query() . '</span></li>';
		}

		$output .= '</ul>';

		return $output;
	}
}

if ( ! function_exists( 'ciyashop_create_google_fonts_url' ) ) {
	/**
	 * Create Google Fonts url
	 */
	function ciyashop_create_google_fonts_url( $fonts ) {

		$link     = '';
		$subsets  = array();
		$protocol = ( is_ssl() ) ? 'https:' : 'http:';

		foreach ( $fonts as  $font ) {
			if ( ! empty( $font['font-family'] ) ) {

				if ( ! empty( $link ) ) {
					$link .= '%7C'; // Append a new font to the string.
				}

				$link .= $font['font-family'];

				if ( ! empty( $font['font-style'] ) || ! empty( $font['font-weight'] ) || ! empty( $font['all-styles'] ) ) {
					$link .= ':';

					if ( ! empty( $font['all-styles'] ) ) {
						$link .= implode( ',', $font['all-styles'] );
					} elseif ( ! empty( $font['font-weight'] ) || ! empty( $font['font-style'] ) ) {

						if ( ! empty( $font['font-weight'] ) ) {
							$link .= $font['font-weight'];
						}

						if ( ! empty( $font['font-weight'] ) ) {
							$link .= $font['font-style'];
						}
					}
				}

				if ( ! empty( $font['subsets'] ) ) {
					if ( is_array( $font['subsets'] ) ) {
						foreach ( $font['subsets'] as $subset ) {
							if ( ! in_array( $subset, $subsets, true ) ) {
								array_push( $subsets, $subset );
							}
						}
					} else {
						array_push( $subsets, $font['subsets'] );
					}
				}
			}
		}

		if ( ! empty( $subsets ) ) {
			$link .= '&subset=' . implode( ',', $subsets );
		}

		if ( empty( $link ) ) {
			return;
		}

		return $protocol . '//fonts.googleapis.com/css?family=' . str_replace( '|', '%7C', $link );
	}
}

if ( ! function_exists( 'ciyashop_get_tooltip_position' ) ) {
	/**
	 * Get tooltip position
	 */
	function ciyashop_get_tooltip_position() {
		global $ciyashop_options;

		$product_hover_style = ciyashop_product_hover_style();
		$positions           = array();

		switch ( $product_hover_style ) {
			case 'default':
				$cart_position       = 0;
				$wishlist_position   = 'top';
				$compare_position    = 'top';
				$quick_view_position = 'right';
				break;
			case 'icon-top-left':
				$cart_position       = 'right';
				$wishlist_position   = 'left';
				$compare_position    = 'right';
				$quick_view_position = 'right';
				break;
			case 'icons-top-right':
				$cart_position       = 'left';
				$wishlist_position   = 'left';
				$compare_position    = 'left';
				$quick_view_position = 'left';
				break;
			case 'image-icon-left':
				$cart_position       = 'right';
				$wishlist_position   = 'right';
				$compare_position    = 'right';
				$quick_view_position = 0;
				break;
			case 'image-icon-bottom':
				$cart_position       = 'top';
				$wishlist_position   = 'top';
				$compare_position    = 'top';
				$quick_view_position = 0;
				break;
			case 'image-center':
				$cart_position       = 'top';
				$wishlist_position   = 'top';
				$compare_position    = 'top';
				$quick_view_position = 0;
				break;
			case 'image-left':
				$cart_position       = 0;
				$wishlist_position   = 'right';
				$compare_position    = 'right';
				$quick_view_position = 'right';
				break;
			case 'button-standard':
				$cart_position       = 0;
				$wishlist_position   = 'top';
				$compare_position    = 'top';
				$quick_view_position = 'top';
				break;
			case 'icons-left':
				$cart_position       = 0;
				$wishlist_position   = 'right';
				$compare_position    = 'right';
				$quick_view_position = 'right';
				break;
			case 'icons-rounded':
				$cart_position       = 0;
				$wishlist_position   = 'right';
				$compare_position    = 'right';
				$quick_view_position = 'right';
				break;
			case 'icons-bottom-right':
				$cart_position       = 'left';
				$wishlist_position   = 'left';
				$compare_position    = 'left';
				$quick_view_position = 'left';
				break;
			case 'image-bottom':
				$cart_position       = 'top';
				$wishlist_position   = 'top';
				$compare_position    = 'top';
				$quick_view_position = 'top';
				break;
			case 'image-bottom-2':
				$cart_position       = 'top';
				$wishlist_position   = 'top';
				$compare_position    = 'top';
				$quick_view_position = 'top';
				break;
			case 'image-bottom-bar':
				$cart_position       = 0;
				$wishlist_position   = 'top';
				$compare_position    = 'top';
				$quick_view_position = 0;
				break;
			case 'info-bottom':
				$cart_position       = 0;
				$wishlist_position   = 'top';
				$compare_position    = 'top';
				$quick_view_position = 0;
				break;
			case 'info-bottom-bar':
				$cart_position       = 0;
				$wishlist_position   = 'top';
				$compare_position    = 'top';
				$quick_view_position = 0;
				break;
			case 'hover-summary':
				$cart_position       = 0;
				$wishlist_position   = 'right';
				$compare_position    = 'top';
				$quick_view_position = 'top';
				break;
			case 'minimal-hover-cart':
				$cart_position       = 0;
				$wishlist_position   = 'top';
				$compare_position    = 'top';
				$quick_view_position = 'top';
				break;
			case 'minimal':
				$cart_position       = 0;
				$wishlist_position   = 'top';
				$compare_position    = 'top';
				$quick_view_position = 0;
				break;
			case 'info-transparent-center':
				$cart_position       = 0;
				$wishlist_position   = 'top';
				$compare_position    = 'top';
				$quick_view_position = 'top';
				break;
			case 'icons-transparent-center':
				$cart_position       = 0;
				$wishlist_position   = 'top';
				$compare_position    = 'top';
				$quick_view_position = 'top';
				break;
			case 'standard-info-transparent':
				$cart_position       = 'left';
				$wishlist_position   = 'left';
				$compare_position    = 'left';
				$quick_view_position = 'left';
				break;
			case 'standard-quick-shop':
				$cart_position       = 0;
				$wishlist_position   = 'left';
				$compare_position    = 'left';
				$quick_view_position = 'left';
				break;
			default:
				$cart_position       = 'top';
				$wishlist_position   = 'top';
				$compare_position    = 'top';
				$quick_view_position = 'top';
		}

		$positions = array(
			'cart_icon'       => $cart_position,
			'wishlist_icon'   => $wishlist_position,
			'compare_icon'    => $compare_position,
			'quick_view_icon' => $quick_view_position,
		);

		return $positions;
	}
}

add_action( 'ciyashop_before_page_wrapper', 'ciyashop_before_page_wrapper_check' );

if ( ! function_exists( 'ciyashop_before_page_wrapper_check' ) ) {
	/**
	 * Before page wrapper check
	 */
	function ciyashop_before_page_wrapper_check() {
		$ciyashop_theme_options = get_option( 'ciyashop_options' );
		global $cs_product_list_styles, $ciyashop_options;

		if ( isset( $ciyashop_options['product_hover_style'] ) && $ciyashop_options['product_hover_style'] ) {
			switch ( $ciyashop_options['product_hover_style'] ) {
				case $cs_product_list_styles['default']:
					add_action( 'woocommerce_before_shop_loop_item_title', 'ciyashop_product_actions_add_quick_view_link', 20 );
					break;
				case $cs_product_list_styles['image-icon-left']:
					add_action( 'woocommerce_before_shop_loop_item_title', 'ciyashop_product_actions_add_quick_view_link', 20 );
					break;
				case $cs_product_list_styles['image-icon-bottom']:
					add_action( 'woocommerce_before_shop_loop_item_title', 'ciyashop_product_actions_add_quick_view_link', 20 );
					break;
				case $cs_product_list_styles['icons-center']:
					add_action( 'woocommerce_before_shop_loop_item_title', 'ciyashop_product_actions_add_quick_view_link', 20 );
					break;
				case $cs_product_list_styles['minimal']:
					add_action( 'woocommerce_before_shop_loop_item_title', 'ciyashop_product_actions_add_quick_view_link', 20 );
					break;
				case $cs_product_list_styles['icons-bottom-bar']:
					add_action( 'woocommerce_before_shop_loop_item_title', 'ciyashop_product_actions_add_quick_view_link', 20 );
					break;
				case $cs_product_list_styles['info-bottom']:
					add_action( 'woocommerce_before_shop_loop_item_title', 'ciyashop_product_actions_add_quick_view_link', 20 );
					break;
				case $cs_product_list_styles['info-bottom-bar']:
					add_action( 'woocommerce_before_shop_loop_item_title', 'ciyashop_product_actions_add_quick_view_link', 20 );
					break;
				default:
					add_action( 'ciyashop_product_actions', 'ciyashop_product_actions_add_quick_view_link', 20 );
			}
		}
	}
}

if ( ! function_exists( 'ciyashop_product_actions_add_quick_view_link' ) ) {
	/**
	 * Product actions add quick view link
	 */
	function ciyashop_product_actions_add_quick_view_link() {
		global $post, $ciyashop_options;

		if ( ! $ciyashop_options['quick_view'] ) {
			return;
		}

		$position                 = ciyashop_get_tooltip_position();
		$quick_view_icon_position = isset( $position['quick_view_icon'] ) ? $position['quick_view_icon'] : '';
		?>
		<div class="product-action product-action-quick-view">
			<?php
			if ( $quick_view_icon_position ) {
				?>
				<a href="<?php echo esc_url( get_the_permalink( $post->ID ) ); ?>" class="open-quick-view" data-id="<?php echo esc_attr( $post->ID ); ?>" data-toggle='tooltip' data-original-title="<?php esc_attr_e( 'Quick view', 'ciyashop' ); ?>" data-placement='<?php echo esc_attr( $quick_view_icon_position ); ?>'>
					<?php esc_html_e( 'Quick View', 'ciyashop' ); ?>
				</a>
				<?php
			} else {
				?>
				<a href="<?php echo esc_url( get_the_permalink( $post->ID ) ); ?>" class="open-quick-view" data-id="<?php echo esc_attr( $post->ID ); ?>">
					<?php esc_html_e( 'Quick View', 'ciyashop' ); ?>
				</a>
				<?php
			}
			?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'ciyashop_get_selected_menu_id' ) ) {
	/**
	 * Get selected menu id
	 */
	function ciyashop_get_selected_menu_id() {

		$nav_menus = wp_get_nav_menus( array( 'orderby' => 'name' ) );

		$menu_count = count( $nav_menus );

		$nav_menu_selected_id = isset( $_REQUEST['menu'] ) ? (int) $_REQUEST['menu'] : 0;

		$add_new_screen = ( isset( $_GET['menu'] ) && 0 == $_GET['menu'] ) ? true : false; // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison

		// Get recently edited nav menu.
		$recently_edited = absint( get_user_option( 'nav_menu_recently_edited' ) );
		if ( empty( $recently_edited ) && is_nav_menu( $nav_menu_selected_id ) ) {
			$recently_edited = $nav_menu_selected_id;
		}

		// Use $recently_edited if none are selected.
		if ( empty( $nav_menu_selected_id ) && ! isset( $_GET['menu'] ) && is_nav_menu( $recently_edited ) ) {
			$nav_menu_selected_id = $recently_edited;
		}

		// On deletion of menu, if another menu exists, show it.
		if ( ! $add_new_screen && 0 < $menu_count && isset( $_GET['action'] ) && 'delete' === $_GET['action'] ) {
			$nav_menu_selected_id = $nav_menus[0]->term_id;
		}

		if ( empty( $nav_menu_selected_id ) && ! empty( $nav_menus ) && ! $add_new_screen ) {
			// if we have no selection yet, and we have menus, set to the first one in the list.
			$nav_menu_selected_id = $nav_menus[0]->term_id;
		}

		return $nav_menu_selected_id;

	}
}

add_action( 'wp_enqueue_scripts', 'ciyashop_404_wpbakery_css', 999 );
/**
 * Function to output shortcodes CSS and custom CSS on 404 page.
 *
 * @since 3.5.0
 */
function ciyashop_404_wpbakery_css() {
	global $ciyashop_options;

	if ( ! is_404() ) {
		return;
	}

	if ( ! isset( $ciyashop_options['fourofour_page_content_source'] ) || ( isset( $ciyashop_options['fourofour_page_content_source'] ) && 'page' !== $ciyashop_options['fourofour_page_content_source'] ) ) {
		return;
	}

	if ( ! isset( $ciyashop_options['fourofour_page_content_source'] ) || ( isset( $ciyashop_options['fourofour_page_content_page'] ) && '' === $ciyashop_options['fourofour_page_content_page'] ) ) {
		return;
	}

	$fourofour_content_page_id = $ciyashop_options['fourofour_page_content_page'];
	$fourofour_content_page    = get_post( $fourofour_content_page_id );

	if ( ! $fourofour_content_page || 'publish' !== $fourofour_content_page->post_status ) {
		return;
	}

	$wpbakery_css = '';

	if ( $fourofour_content_page_id ) {
		if ( 'true' === vc_get_param( 'preview' ) && wp_revisions_enabled( get_post( $fourofour_content_page_id ) ) ) {
			$latest_revision = wp_get_post_revisions( $fourofour_content_page_id );
			if ( ! empty( $latest_revision ) ) {
				$array_values              = array_values( $latest_revision );
				$fourofour_content_page_id = $array_values[0]->ID;
			}
		}

		$shortcodes_custom_css = get_metadata( 'post', $fourofour_content_page_id, '_wpb_shortcodes_custom_css', true );
		if ( ! empty( $shortcodes_custom_css ) ) {
			$shortcodes_custom_css = wp_strip_all_tags( $shortcodes_custom_css );
			$wpbakery_css         .= $shortcodes_custom_css;
		}

		$post_custom_css = get_metadata( 'post', $fourofour_content_page_id, '_wpb_post_custom_css', true );
		if ( ! empty( $post_custom_css ) ) {
			$post_custom_css = wp_strip_all_tags( $post_custom_css );
			$wpbakery_css   .= $post_custom_css;
		}
	}
	if ( ! empty( $wpbakery_css ) ) {
		wp_add_inline_style( 'ciyashop-style', $wpbakery_css );
	}
}

/**
 * Check whether custom language flag exists.
 *
 * @param string $flag_url Language flag URL.
 *
 * @return bool
 */
function ciyashop_wpml_custom_flag_exists( $flag_url = '' ) {

	if ( empty( $flag_url ) ) {
		return $flag_url;
	}

	$uploads = wp_upload_dir( null, false );
	$basedir = $uploads['basedir'];
	$baseurl = $uploads['baseurl'];

	if ( false === strpos( $flag_url, $baseurl ) ) {
		return true;
	}

	$flag_path = str_replace( $baseurl, $basedir, $flag_url );

	return ( file_exists( $flag_path ) );
}
