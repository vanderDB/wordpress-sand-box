<?php
/**
 * Template classes functions
 *
 * @package CiyaShop
 * @version 1.0.0
 */

/**
 * Content wrapper classes
 *
 * @param string $class class name.
 * @return void
 */
function ciyashop_content_wrapper_classes( $class = '' ) {
	$classes = array();

	$classes[] = ciyashop_class_builder( $class );

	/**
	 * Filters the list of CSS classes for content wrapper.
	 *
	 * @param    array    $classes    An array of content wrapper classes.
	 *
	 * @visible true
	 */
	$classes = apply_filters( 'ciyashop_content_wrapper_classes', $classes );

	$classes = ciyashop_class_builder( $classes );

	echo esc_attr( $classes );
}

/**
 * Conetnet container clasess
 *
 * @param string $class class.
 * @return void
 */
function ciyashop_content_container_classes( $class = '' ) {
	$classes = array();

	$classes[] = ciyashop_class_builder( $class );

	/**
	 * Filters the list of CSS classes for content container.
	 *
	 * @since 1.0.0
	 *
	 * @param    array    $classes    An array of content container classes.
	 *
	 * @visible true
	 */
	$classes = apply_filters( 'ciyashop_content_container_classes', $classes );

	$classes = ciyashop_class_builder( $classes );

	echo esc_attr( $classes );
}

if ( ! function_exists( 'ciyashop_classes_main_area' ) ) {

	/**
	 * Classes main area.
	 *
	 * @param mixed $class Custom classes.
	 */
	function ciyashop_classes_main_area( $class = '' ) {
		global $ciyashop_options;

		$blog_sidebar        = ( isset( $ciyashop_options['blog_sidebar'] ) && ! empty( $ciyashop_options['blog_sidebar'] ) ) ? $ciyashop_options['blog_sidebar'] : 'right_sidebar';
		$portfolio_sidebar   = ( isset( $ciyashop_options['portfolio_sidebar'] ) && ! empty( $ciyashop_options['portfolio_sidebar'] ) ) ? $ciyashop_options['portfolio_sidebar'] : 'full_width';
		$page_sidebar        = ( isset( $ciyashop_options['page_sidebar'] ) && ! empty( $ciyashop_options['page_sidebar'] ) ) ? $ciyashop_options['page_sidebar'] : 'right_sidebar';
		$search_page_sidebar = ( isset( $ciyashop_options['search_page_sidebar'] ) && ! empty( $ciyashop_options['search_page_sidebar'] ) ) ? $ciyashop_options['search_page_sidebar'] : 'right_sidebar';
		$cs_wishlist_page    = ( isset( $ciyashop_options['cs_wishlist_page'] ) && ! empty( $ciyashop_options['cs_wishlist_page'] ) ) ? $ciyashop_options['cs_wishlist_page'] : '';

		if ( get_the_ID() === (int) $cs_wishlist_page ) {
			$page_sidebar = 'full_width';
		}

		// Check if page sidebar settings for perticular page.
		if ( 'default' !== (string) get_post_meta( get_the_ID(), 'page_sidebar', true ) && get_post_meta( get_the_ID(), 'page_sidebar', true ) ) {
			$page_sidebar = get_post_meta( get_the_ID(), 'page_sidebar', true );
		}

		$main_area_classes   = array();
		$main_area_classes[] = ciyashop_class_builder( $class );

		if (
			( class_exists( 'WooCommerce' ) && ( is_checkout() || is_cart() || is_account_page() ) )
			|| is_page_template( 'templates/full-width.php' )
			|| is_page_template( 'templates/faq.php' )
			|| is_page_template( 'templates/team.php' )
			|| ( ! is_home() && ( is_front_page() && ciyashop_is_vc_enabled() ) )
		) {
			$main_area_classes['col'] = 'col-sm-12';
		} elseif ( is_post_type_archive( 'portfolio' ) && 'full_width' !== $portfolio_sidebar && is_active_sidebar( 'sidebar-1' ) ) {
			if ( 'left_sidebar' === $portfolio_sidebar ) {
				$main_area_classes['col'] = 'col-sm-12 col-md-12 col-lg-8 col-xl-9 order-xl-2 order-lg-2';
			} else {
				$main_area_classes['col'] = 'col-sm-12 col-md-12 col-lg-8 col-xl-9';
			}
		} elseif ( ( ( is_home() || is_archive() ) && 'full_width' !== $blog_sidebar && is_active_sidebar( 'sidebar-1' ) ) && ! is_post_type_archive( 'portfolio' ) ) {
			if ( 'left_sidebar' === $blog_sidebar ) {
				$main_area_classes['col'] = 'col-sm-12 col-md-12 col-lg-8 col-xl-9 order-xl-2 order-lg-2';
			} else {
				$main_area_classes['col'] = 'col-sm-12 col-md-12 col-lg-8 col-xl-9';
			}
		} elseif ( ( ( is_page() && 'full_width' !== $page_sidebar ) || ( is_search() && 'full_width' !== $search_page_sidebar ) ) && is_active_sidebar( 'sidebar-1' ) ) {
			if ( ( is_page() && 'left_sidebar' === $page_sidebar ) || ( is_search() && 'left_sidebar' === $search_page_sidebar ) ) {
				$main_area_classes['col'] = 'col-sm-12 col-md-12 col-lg-8 col-xl-9 order-xl-2 order-lg-2';
			} else {
				$main_area_classes['col'] = 'col-sm-12 col-md-12 col-lg-8 col-xl-9';
			}
		} else {
			$main_area_classes['col'] = 'col-sm-12 col-md-12 col-lg-12 col-xl-12';
		}

		/**
		 * Filters the list of CSS classes for main area.
		 *
		 * @since 1.0.0
		 *
		 * @param    array    $classes    An array of main area classes.
		 *
		 * @visible true
		 */
		$main_area_classes = apply_filters( 'ciyashop_classes_main_area', $main_area_classes );

		$main_area_classes = ciyashop_class_builder( $main_area_classes );

		echo esc_attr( $main_area_classes );
	}
}

/**
 * Page classes
 *
 * @param string $class classes.
 */
function ciyashop_page_classes( $class = '' ) {

	$classes = array();

	$classes[] = ciyashop_class_builder( $class );

	$show_header = ciyashop_show_header();

	if ( empty( $show_header ) && ( is_search() ) ) {
		$show_header = true;
	}

	$classes[] = 'hfeed';
	$classes[] = 'site';

	if ( empty( $show_header ) ) {
		$classes[] = 'page-header-hidden';
	}

	/**
	 * Filters the list of CSS classes for page.
	 *
	 * @since 1.0.0
	 *
	 * @param    array    $classes    An array of page classes.
	 *
	 * @visible true
	 */
	$classes = apply_filters( 'ciyashop_page_classes', $classes );

	$classes = ciyashop_class_builder( $classes );

	echo esc_attr( $classes );
}

/**
 * Header classes
 *
 * @param string $class classes.
 */
function ciyashop_header_classes( $class = '' ) {
	global $ciyashop_options;

	$classes = array( 'site-header' );

	$classes[] = ciyashop_class_builder( $class );

	$ciyashop_header_type = ciyashop_header_type();

	$classes[] = 'header-style-' . $ciyashop_header_type;

	$show_search      = ciyashop_show_search();
	$search_box_shape = ( isset( $ciyashop_options['search_box_shape'] ) && ! empty( $ciyashop_options['search_box_shape'] ) ) ? $ciyashop_options['search_box_shape'] : 'square';

	if ( 'default' === $ciyashop_header_type && $show_search ) {
		$classes[] = 'header-search-shape-' . $search_box_shape;
	}

	if ( 'logo-center' === $ciyashop_header_type ) {
		if ( $show_search ) {
			$classes[] = 'menu-with-search';
		} else {
			$classes[] = 'menu-without-search';
		}
	}

	if ( 'menu-center' === $ciyashop_header_type || 'menu-right' === $ciyashop_header_type || 'custom' === $ciyashop_header_type ) {
		if ( isset( $ciyashop_options['header_above_content'] ) && 1 === (int) $ciyashop_options['header_above_content'] ) {
			$classes[] = 'header-above-content';
		}
	}

	/**
	 * Filter the list of CSS classes for header.
	 *
	 * @since 1.0.0
	 *
	 * @param    array    $classes    An array of header classes.
	 *
	 * @visible true
	 */
	$classes = apply_filters( 'ciyashop_header_classes', $classes );

	$classes = ciyashop_class_builder( $classes );

	echo esc_attr( $classes );
}

/**
 * Topbar classes
 *
 * @param string $class classes.
 */
function ciyashop_topbar_classes( $class = '' ) {
	global $ciyashop_options;

	$classes = array( 'topbar' );

	$classes[] = ciyashop_class_builder( $class );

	// Topbar Background Color.
	$topbar_bg_type = isset( $ciyashop_options['topbar_bg_type'] ) && ! empty( $ciyashop_options['topbar_bg_type'] ) ? $ciyashop_options['topbar_bg_type'] : 'default';
	if ( isset( $ciyashop_options['topbar_bg_type'] ) && ! empty( $ciyashop_options['topbar_bg_type'] ) ) {
		$classes[] = 'topbar-bg-color-' . $topbar_bg_type;
	}

	if ( ciyashop_topbar_enable() === 'enable' ) {
		$classes[] = 'topbar-desktop-on';
	} else {
		$classes[] = 'topbar-desktop-off';
	}

	if ( ciyashop_topbar_mobile_enable() === 'enable' ) {
		$classes[] = 'topbar-mobile-on';
	} else {
		$classes[] = 'topbar-mobile-off';
	}

	/**
	 * Filter the list of CSS classes for topbar.
	 *
	 * @since 1.0.0
	 *
	 * @param    array    $classes    An array of topbar classes.
	 *
	 * @visible true
	 */
	$classes = apply_filters( 'ciyashop_topbar_classes', $classes );

	$classes = ciyashop_class_builder( $classes );

	return esc_attr( $classes );
}

/**
 * Topbar container classes
 *
 * @param string $class classes.
 */
function ciyashop_topbar_container_classes( $class = '' ) {
	global $ciyashop_options;

	$classes = array( 'container' );

	$ciyashop_header_type = ciyashop_header_type();

	if ( 'menu-center' === (string) $ciyashop_header_type || 'menu-right' === (string) $ciyashop_header_type || 'custom' === (string) $ciyashop_header_type ) {
		$header_width = isset( $ciyashop_options['header_width'] ) && ! empty( $ciyashop_options['header_width'] ) ? $ciyashop_options['header_width'] : 'full_width';
		if ( 'full_width' === (string) $header_width ) {
			$classes = array( 'container-fluid' );
		} else {
			$classes = array( 'container' );
		}
	}

	$classes[] = ciyashop_class_builder( $class );

	/**
	 * Filter the list of CSS classes for topbar container.
	 *
	 * @since 1.0.0
	 *
	 * @param    array    $classes    An array of topbar container classes.
	 *
	 * @visible true
	 */
	$classes = apply_filters( 'ciyashop_topbar_container_classes', $classes );

	$classes = ciyashop_class_builder( $classes );

	return esc_attr( $classes );
}

/**
 * Main header classes
 *
 * @param string $class classes.
 */
function ciyashop_header_main_classes( $class = '' ) {
	global $ciyashop_options;

	$classes = array();

	$classes[] = ciyashop_class_builder( $class );

	// Header (Main) Background Color.
	$header_main_bg_type = isset( $ciyashop_options['header_main_bg_type'] ) && ! empty( $ciyashop_options['header_main_bg_type'] ) ? $ciyashop_options['header_main_bg_type'] : 'default';
	if ( isset( $ciyashop_options['header_main_bg_type'] ) && ! empty( $ciyashop_options['header_main_bg_type'] ) ) {
		$classes[] = 'header-main-bg-color-' . $header_main_bg_type;
	}

	/**
	 * Filter the list of CSS classes for main header.
	 *
	 * @since 1.0.0
	 *
	 * @param    array    $classes    An array of main header classes.
	 *
	 * @visible true
	 */
	$classes = apply_filters( 'ciyashop_header_main_classes', $classes );

	$classes = ciyashop_class_builder( $classes );

	return $classes;
}

/**
 * Header main container classes
 *
 * @param string $class classes.
 */
function ciyashop_header_main_container_classes( $class = '' ) {
	global $ciyashop_options;

	$classes = array( 'container' );

	$ciyashop_header_type = ciyashop_header_type();

	if ( 'menu-center' === $ciyashop_header_type || 'menu-right' === $ciyashop_header_type || 'custom' === $ciyashop_header_type ) {
		$header_width = isset( $ciyashop_options['header_width'] ) && ! empty( $ciyashop_options['header_width'] ) ? $ciyashop_options['header_width'] : 'full_width';
		if ( 'full_width' === $header_width ) {
			$classes = array( 'container-fluid' );
		} else {
			$classes = array( 'container' );
		}
	}

	$classes[] = ciyashop_class_builder( $class );

	/**
	 * Filter the list of CSS classes for main header container.
	 *
	 * @since 1.0.0
	 *
	 * @param    array    $classes    An array of main header container classes.
	 *
	 * @visible true
	 */
	$classes = apply_filters( 'ciyashop_header_main_container_classes', $classes );

	$classes = ciyashop_class_builder( $classes );

	return esc_attr( $classes );
}

/**
 * Header nav classes
 *
 * @param string $class classes.
 */
function ciyashop_header_nav_classes( $class = '' ) {
	global $ciyashop_options;

	$classes = array();

	$classes[] = ciyashop_class_builder( $class );

	// Header (Main) Background Color.
	$header_nav_bg_type = isset( $ciyashop_options['header_nav_bg_type'] ) && ! empty( $ciyashop_options['header_nav_bg_type'] ) ? $ciyashop_options['header_nav_bg_type'] : 'default';
	if ( isset( $ciyashop_options['header_nav_bg_type'] ) && ! empty( $ciyashop_options['header_nav_bg_type'] ) ) {
		$classes[] = 'header-nav-bg-color-' . $header_nav_bg_type;
	}

	/**
	 * Filter the list of CSS classes for header navigation.
	 *
	 * @since 1.0.0
	 *
	 * @param    array    $classes    An array of header navigation classes.
	 *
	 * @visible true
	 */
	$classes = apply_filters( 'ciyashop_header_nav_classes', $classes );

	$classes = ciyashop_class_builder( $classes );

	echo esc_attr( $classes );
}

/**
 * Page header classes
 *
 * @param string $class classes.
 */
function ciyashop_page_header_classes( $class = '' ) {
	global $post, $ciyashop_options;

	if ( is_home() ) {
		$page_for_posts = get_option( 'page_for_posts' );
		$post           = get_post( $page_for_posts ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
	}

	$classes = array();

	$classes[] = ciyashop_class_builder( $class );

	// Set classes from Options.
	$banner_type = ( isset( $ciyashop_options['banner_type'] ) ) ? $ciyashop_options['banner_type'] : 'image';
	if ( empty( $banner_type ) ) {
		$banner_type = 'color';
	}

	$classes['header_intro_bg'] = 'header_intro_bg-' . $banner_type;

	if ( 'image' === $banner_type || 'video' === $banner_type ) {
		$banner_image_opacity = ( isset( $ciyashop_options['banner_image_opacity'] ) ) ? $ciyashop_options['banner_image_opacity'] : 'black';
		if ( ! empty( $banner_image_opacity ) ) {
			$classes['header_intro_opacity']      = 'header_intro_opacity';
			$classes['header_intro_opacity_type'] = 'header_intro_opacity-' . $banner_image_opacity;
		}
	}

	if ( ( is_page() || is_single() || is_home() ) && $post ) {

		$post_id = $post->ID;

		$header_settings_source = get_post_meta( $post_id, 'header_settings_source', true );

		if ( 'custom' === $header_settings_source ) {
			unset( $classes['header_intro_bg'] );
			unset( $classes['header_intro_opacity'] );
			unset( $classes['header_intro_opacity_type'] );

			$banner_type = get_post_meta( $post_id, 'banner_type', true );
			if ( empty( $banner_type ) ) {
				$banner_type = 'image';
			}

			$classes['header_intro_bg'] = 'header_intro_bg-' . $banner_type;

			if ( $banner_type && ( 'image' === $banner_type || 'video' === $banner_type ) ) {
				$classes['header_intro_opacity'] = 'header_intro_opacity';
				$background_opacity_color        = get_post_meta( $post_id, 'background_opacity_color', true );
				if ( $background_opacity_color ) {
					$classes['header_intro_opacity_type'] = 'header_intro_opacity-' . $background_opacity_color;
				}
			}
		}
	}

	/**
	 * Filter the list of CSS classes for page header.
	 *
	 * @since 1.0.0
	 *
	 * @param    array    $classes    An array of page header classes.
	 *
	 * @visible true
	 */
	$classes = apply_filters( 'ciyashop_page_header_classes', $classes );

	$classes = ciyashop_class_builder( $classes );

	echo esc_attr( $classes );
}

/**
 * Page header container classes
 *
 * @param string $class classes.
 */
function ciyashop_page_header_container_classes( $class = '' ) {
	global $ciyashop_options;

	$classes = array();

	$classes[] = ciyashop_class_builder( $class );

	/**
	 * Filter the list of CSS classes for page header container.
	 *
	 * @since 1.0.0
	 *
	 * @param    array    $classes    An array of page header container classes.
	 *
	 * @visible true
	 */
	$classes = apply_filters( 'ciyashop_page_header_container_classes', $classes );

	$classes = ciyashop_class_builder( $classes );

	return esc_attr( $classes );
}

/**
 * Page header row classes.
 *
 * @param string $class classes.
 */
function ciyashop_page_header_row_classes( $class = '' ) {
	global $ciyashop_options, $post;

	if ( is_search() ) {
		$post_id = 0;
	} else {
		if ( $post ) {
			$post_id = $post->ID;
		} else {
			$post_id = 0;
		}
	}

	$classes = array();

	$classes[] = ciyashop_class_builder( $class );

	$titlebar_view = ( isset( $ciyashop_options['titlebar_view'] ) ) ? $ciyashop_options['titlebar_view'] : 'default';

	$header_settings_source = get_post_meta( $post_id, 'header_settings_source', true );
	if ( 'custom' === $header_settings_source ) {
		$titlebar_view = get_post_meta( $post_id, 'titlebar_text_align', true );
	}

	if ( 'default' === $titlebar_view ) {
		$classes[] = 'intro-section-center';
	} elseif ( 'allleft' === $titlebar_view ) {
		$classes[] = 'intro-section-left';
	} elseif ( 'allright' === $titlebar_view ) {
		$classes[] = 'intro-section-right';
	}

	/**
	 * Filter the list of CSS classes for page header row.
	 *
	 * @since 1.0.0
	 *
	 * @param    array    $classes    An array of page header row classes.
	 *
	 * @visible true
	 */
	$classes = apply_filters( 'ciyashop_page_header_row_classes', $classes );

	$classes = ciyashop_class_builder( $classes );

	return esc_attr( $classes );
}

/**
 * Footer wrapper classes
 *
 * @param string  $class classes.
 * @param boolean $echo display or not.
 */
function ciyashop_footer_wrapper_classes( $class = '', $echo = true ) {
	global $ciyashop_options;

	$classes = array( 'footer-wrapper' );

	$classes[] = ciyashop_class_builder( $class );

	if ( function_exists( 'ciyashop_footer_type' ) && 'footer_builder' === ciyashop_footer_type() ) {
		$footer_data = ciyashop_get_custom_footer_data();
		
		if ( isset( $footer_data['common_settings']['sticky_footer'] ) && 'enable' === $footer_data['common_settings']['sticky_footer'] ) {
			$classes[] = 'ciyashop-sticky-footer';
		}
		
		if ( isset( $footer_data['common_settings']['footer_background_opacity'] ) && 'custom' === $footer_data['common_settings']['footer_background_opacity'] ) {
			$classes[] = 'footer_opacity_custom';
		}
	} else {
		
		if ( isset( $ciyashop_options['footer_background_opacity'] ) && 'custom' === $ciyashop_options['footer_background_opacity'] ) {
			$classes[] = 'footer_opacity_custom';
		}

		if ( isset( $ciyashop_options['sticky_footer'] ) && 'enable' === $ciyashop_options['sticky_footer'] ) {
			$classes[] = 'ciyashop-sticky-footer';
		}
	}

	/**
	 * Filter the list of CSS classes for footer wrapper.
	 *
	 * @since 1.0.0
	 *
	 * @param    array    $classes    An array of footer wrapper classes.
	 *
	 * @visible true
	 */
	$classes = apply_filters( 'ciyashop_footer_wrapper_classes', $classes );

	$classes = ciyashop_class_builder( $classes );

	if ( $echo ) {
		echo esc_attr( $classes );
	} else {
		return esc_attr( $classes );
	}
}

/**
 * Sticky header classes
 *
 * @param string $class classes.
 */
function ciyashop_header_sticky_classes( $class = '' ) {
	global $ciyashop_options;

	$classes = array( 'header-sticky' );

	$classes[] = ciyashop_class_builder( $class );

	if ( ciyashop_sticky_header() ) {
		$classes[] = 'header-sticky-desktop-on';
	} else {
		$classes[] = 'header-sticky-desktop-off';
	}

	if ( ciyashop_mobile_sticky_header() ) {
		$classes[] = 'header-sticky-mobile-on';
	} else {
		$classes[] = 'header-sticky-mobile-off';
	}

	/**
	 * Filter the list of CSS classes for sticky header.
	 *
	 * @since 1.0.0
	 *
	 * @param    array    $classes    An array of sticky header classes.
	 *
	 * @visible true
	 */
	$classes = apply_filters( 'ciyashop_header_sticky_classes', $classes );

	$classes = ciyashop_class_builder( $classes );

	return $classes;
}
