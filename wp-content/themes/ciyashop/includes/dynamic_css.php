<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Generate Dynamic Css property base on theme setting
 *
 * @package CiyaShop
 */

add_action( 'wp_enqueue_scripts', 'ciyashop_dynamic_css', 50 );
/**
 * Ciyashop dynamic css
 */
function ciyashop_dynamic_css() {
	global $dynamic_css, $ciyashop_options, $ciyashop_body_typo, $ciyashop_h1_typo, $ciyashop_h2_typo, $ciyashop_h3_typo, $ciyashop_h4_typo, $ciyashop_h5_typo, $ciyashop_h6_typo;
	$dynamic_css = array();

	/**
	 * Hook: ciyashop_dynamic_css_init.
	 *
	 * @visible false
	 * @ignore
	 */
	do_action( 'ciyashop_dynamic_css_init', $ciyashop_options );

	require_once get_parent_theme_file_path( '/includes/dynamic_css_helper.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

	$post_id = ciyashop_dynamic_css_page_id();

	/**
	 * Fires before ciyashop_dynamic_css action hook.
	 *
	 * @visible false
	 * @ignore
	 */
	do_action( 'ciyashop_before_dynamic_css', $ciyashop_options );

	/**
	 * Hook: ciyashop_dynamic_css
	 *
	 * @Functions hooked in to ciyashop_dynamic_css action hook.
	 * @hooked ciyashop_site_layout_css            - 10
	 * @hooked ciyashop_logo_css                   - 20
	 * @hooked ciyashop_menu_font_css              - 25
	 * @hooked ciyashop_topbar_css                 - 30
	 * @hooked ciyashop_preloader_css              - 35
	 * @hooked ciyashop_header_main_css            - 40
	 * @hooked ciyashop_header_nav_css             - 50
	 * @hooked ciyashop_header_sticky_css          - 60
	 * @hooked ciyashop_page_header_css            - 70
	 * @hooked ciyashop_shop_categories_header_css - 75
	 * @hooked ciyashop_content_css                - 80
	 * @hooked ciyashop_footer_css                 - 90
	 * @hooked ciyashop_dynamic_css_fix            - 100
	 *
	 * @param array $ciyashop_options       Contains ciyashop options data.
	 *
	 * @visible false
	 * @ignore
	 */
	do_action( 'ciyashop_dynamic_css', $ciyashop_options );

	/**
	 * Fires after ciyashop_dynamic_css action hook.
	 *
	 * @visible false
	 * @ignore
	 */
	do_action( 'ciyashop_after_dynamic_css', $ciyashop_options );

	$parsed_css = ciyashop_generate_css_properties( $dynamic_css );

	if ( ! empty( $parsed_css ) ) {
		wp_add_inline_style( 'ciyashop-style', $parsed_css );
	}
}
/**
 * Ciyashop dynamic css page id
 */
function ciyashop_dynamic_css_page_id() {

	global $post;

	$post_id = 0;

	if ( $post ) {
		$post_id = $post->ID;
	}

	if ( is_404() ) {
		$post_id = 0;
	} elseif ( is_search() ) {
		$post_id = 0;
	}

	if ( function_exists( 'is_shop' ) && is_shop() ) {
		$post_id = get_option( 'woocommerce_shop_page_id' );

	}

	if ( is_home() && ! is_front_page() ) {
		$page_for_posts = get_option( 'page_for_posts' );
		if ( $page_for_posts ) {
			$post_id = $page_for_posts;
		}
	}

	return $post_id;
}

add_action( 'ciyashop_dynamic_css', 'ciyashop_site_layout_css', 10 );
add_action( 'ciyashop_dynamic_css', 'ciyashop_logo_css', 20 );
add_action( 'ciyashop_dynamic_css', 'ciyashop_menu_font_css', 25 );
add_action( 'ciyashop_dynamic_css', 'ciyashop_topbar_css', 30 );
add_action( 'ciyashop_dynamic_css', 'ciyashop_preloader_css', 35 );
add_action( 'ciyashop_dynamic_css', 'ciyashop_header_main_css', 40 );
add_action( 'ciyashop_dynamic_css', 'ciyashop_header_nav_css', 50 );
add_action( 'ciyashop_dynamic_css', 'ciyashop_header_sticky_css', 60 );
add_action( 'ciyashop_dynamic_css', 'ciyashop_page_header_css', 70 );
add_action( 'ciyashop_dynamic_css', 'ciyashop_shop_categories_header_css', 70 );
add_action( 'ciyashop_dynamic_css', 'ciyashop_content_css', 80 );
add_action( 'ciyashop_dynamic_css', 'ciyashop_footer_css', 90 );
add_action( 'ciyashop_dynamic_css', 'ciyashop_dynamic_css_fix', 100 );

/**
 * Ciyashop site layout css
 *
 * @param string $ciyashop_options .
 */
function ciyashop_site_layout_css( $ciyashop_options ) {
	global $dynamic_css;

	$post_id = ciyashop_dynamic_css_page_id();

	// Site Layout CSS.
	$site_layout = ciyashop_site_layout();

	$fixed_width     = '1300';
	$container_width = '1300';
	$auto_padding    = ( $fixed_width - $container_width ) / 2;
	$auto_margin     = ( $auto_padding ) + 15;

	$body_background = ( isset( $ciyashop_options['body_background'] ) && is_array( $ciyashop_options['body_background'] ) && ! empty( $ciyashop_options['body_background'] ) ) ? $ciyashop_options['body_background'] : array();

	// Site layout body background option.
	if ( 'fullwidth' !== $site_layout && ! empty( $body_background ) ) {

		$body_background_params = array(
			'background-color',
			'background-repeat',
			'background-size',
			'background-attachment',
			'background-position',
			'background-image',
		);

		$body_background_css = array();
		foreach ( $body_background_params as $body_background_param ) {
			if ( isset( $body_background[ $body_background_param ] ) && ! empty( $body_background[ $body_background_param ] ) ) {
				if ( 'background-image' === $body_background_param ) {
					$body_background_css[ $body_background_param ] = 'url(\'' . $body_background[ $body_background_param ] . '\')';
				} else {
					$body_background_css[ $body_background_param ] = $body_background[ $body_background_param ];
				}
			}
		}

		/**
		 * Filters body background css.
		 *
		 * @param string    $body_background_css      Body backgroud CSS.
		 *
		 * @visible false
		 * @ignore
		 */
		$body_background_css = apply_filters( 'ciyashop_body_background_css', $body_background_css, $ciyashop_options );

		if ( ! empty( $body_background_css ) ) {
			$dynamic_css['body'] = $body_background_css;
		}
	}

	$dynamic_css['.ciyashop-site-layout-boxed #page,.ciyashop-site-layout-framed #page,.ciyashop-site-layout-rounded #page']['max-width'] = "{$fixed_width}px";
	if ( 'fullwidth' !== $site_layout ) {
		$dynamic_css['.ciyashop-site-layout-boxed #page,.ciyashop-site-layout-framed #page,.ciyashop-site-layout-rounded #page']['margin-left']  = 'auto';
		$dynamic_css['.ciyashop-site-layout-boxed #page,.ciyashop-site-layout-framed #page,.ciyashop-site-layout-rounded #page']['margin-right'] = 'auto';
	}
	$dynamic_css['.ciyashop-site-layout-boxed .vc_row[data-vc-full-width="true"]:not([data-vc-stretch-content="true"])']['padding-right'] = "{$auto_padding}px !important";
	$dynamic_css['.ciyashop-site-layout-boxed .vc_row[data-vc-full-width="true"]:not([data-vc-stretch-content="true"])']['padding-left']  = "{$auto_padding}px !important";
	$dynamic_css['.ciyashop-site-layout-boxed .vc_row[data-vc-full-width="true"]']['margin-left']  = "-{$auto_margin}px !important";
	$dynamic_css['.ciyashop-site-layout-boxed .vc_row[data-vc-full-width="true"]']['margin-right'] = "-{$auto_margin}px !important";
}
/**
 * Ciyashop logo css
 *
 * @param string $ciyashop_options .
 */
function ciyashop_logo_css( $ciyashop_options ) {
	global $dynamic_css;

	$post_id = ciyashop_dynamic_css_page_id();

	// Logo type "Text" font and color setttings.
	if ( isset( $ciyashop_options['logo_type'] ) && 'text' === $ciyashop_options['logo_type'] ) {

		$logo_text_typo_attrs = array(
			'font-family',
			'font-options',
			'font-weight',
			'font-style',
			'font-size',
			'color',
		);

		if ( isset( $ciyashop_options['logo_font'] ) && ! empty( $ciyashop_options['logo_font'] ) ) {
			foreach ( $logo_text_typo_attrs as $logo_text_typo_attr ) {
				if ( isset( $ciyashop_options['logo_font'][ $logo_text_typo_attr ] ) && ! empty( $ciyashop_options['logo_font'][ $logo_text_typo_attr ] ) ) {
					$dynamic_css['.site-title .logo-text'][ $logo_text_typo_attr ] = $ciyashop_options['logo_font'][ $logo_text_typo_attr ];
				}
			}
		}

		/* Mobile Site Logo text settings*/
		if ( ! empty( $ciyashop_options['logo_font'] ) && isset( $ciyashop_options['logo_font']['font-family'] ) ) {
			$dynamic_css['.device-type-mobile .site-title .logo-text']['font-family'] = $ciyashop_options['logo_font']['font-family'];
		}
		if ( ! empty( $ciyashop_options['mobile_logo_font'] ) && isset( $ciyashop_options['mobile_logo_font']['font-size'] ) ) {
			$dynamic_css['.device-type-mobile .site-title .logo-text']['font-size'] = $ciyashop_options['mobile_logo_font']['font-size'];
		}
		if ( ! empty( $ciyashop_options['mobile_logo_font'] ) && isset( $ciyashop_options['mobile_logo_font']['color'] ) ) {
			$dynamic_css['.device-type-mobile .site-title .logo-text']['color'] = $ciyashop_options['mobile_logo_font']['color'];
		}

		/* Sticky Site Logo text settings*/
		if ( ! empty( $ciyashop_options['logo_font'] ) && isset( $ciyashop_options['logo_font']['font-family'] ) ) {
			$dynamic_css['.sticky-site-title.h1 .logo-text']['font-family'] = $ciyashop_options['logo_font']['font-family'];
		}
		if ( ! empty( $ciyashop_options['sticky_logo_font'] ) && isset( $ciyashop_options['sticky_logo_font']['font-size'] ) ) {
			$dynamic_css['.sticky-site-title.h1 .logo-text']['font-size'] = $ciyashop_options['sticky_logo_font']['font-size'];
		}
		if ( ! empty( $ciyashop_options['sticky_logo_font'] ) && isset( $ciyashop_options['sticky_logo_font']['color'] ) ) {
			$dynamic_css['.sticky-site-title.h1 .logo-text']['color'] = $ciyashop_options['sticky_logo_font']['color'];
		}
	}

	/*End Site logo font settings */

	/* Site Logo Height*/
	if ( isset( $ciyashop_options['site-logo-max-height'] ) && ! empty( $ciyashop_options['site-logo-max-height'] ) ) {

		$site_logo_dimension = ciyashop_parse_redux_dimension( $ciyashop_options['site-logo-max-height'], 'px' );

		if ( $site_logo_dimension ) {

			if ( isset( $site_logo_dimension['height'] ) ) {
				$dynamic_css['.site-header .site-title img']['max-height'] = $site_logo_dimension['height'];
			}
		}
	}

	/* Site Mobile Logo Height*/
	if ( isset( $ciyashop_options['mobile-logo-max-height'] ) && ! empty( $ciyashop_options['mobile-logo-max-height'] ) ) {

		$mobile_logo_dimension = ciyashop_parse_redux_dimension( $ciyashop_options['mobile-logo-max-height'], 'px' );

		if ( $mobile_logo_dimension ) {

			if ( isset( $mobile_logo_dimension['height'] ) ) {
				$dynamic_css['.device-type-mobile .site-header .site-title img']['max-height'] = $mobile_logo_dimension['height'];
			}
		}
	}

	/*Sticky Logo height */
	if ( isset( $ciyashop_options['sticky-logo-max-height'] ) && ! empty( $ciyashop_options['sticky-logo-max-height'] ) ) {

		$sticky_logo_dimension = ciyashop_parse_redux_dimension( $ciyashop_options['sticky-logo-max-height'], 'px' );

		if ( $sticky_logo_dimension ) {

			if ( isset( $sticky_logo_dimension['height'] ) ) {
				$dynamic_css['.site-header .sticky-site-title img']['max-height'] = $sticky_logo_dimension['height'];
			}
		}
	}
}
/**
 * Ciyashop preloader css
 *
 * @param string $ciyashop_options .
 */
function ciyashop_preloader_css( $ciyashop_options ) {
	global $dynamic_css;

	if ( isset( $ciyashop_options['preloader_background_color'] ) && ! empty( $ciyashop_options['preloader_background_color'] ) ) {
		$dynamic_css['#preloader']['background-color'] = $ciyashop_options['preloader_background_color'];
	}
}
/**
 * Ciyashop menu font css
 *
 * @param string $ciyashop_options .
 */
function ciyashop_menu_font_css( $ciyashop_options ) {
	global $dynamic_css;

	if ( isset( $ciyashop_options['menu_font_style_enable'] ) && 'custom' === $ciyashop_options['menu_font_style_enable'] ) {
		$menu_text_typo_attrs = array(
			'font-family',
			'font-weight',
			'letter-spacing',
			'line-height',
			'font-style',
			'font-size',
		);

		if ( isset( $ciyashop_options['menu_fonts'] ) && ! empty( $ciyashop_options['menu_fonts'] ) ) {
			foreach ( $menu_text_typo_attrs as $menu_text_typo_attr ) {
				if ( isset( $ciyashop_options['menu_fonts'][ $menu_text_typo_attr ] ) && ! empty( $ciyashop_options['menu_fonts'][ $menu_text_typo_attr ] ) ) {
					$dynamic_css['.primary-nav .primary-menu > li a, .main-navigation-sticky .primary-menu > li a, .header-style-custom .primary-nav .primary-menu > li a, .header-style-custom .main-navigation-sticky .primary-menu > li a'][ $menu_text_typo_attr ] = $ciyashop_options['menu_fonts'][ $menu_text_typo_attr ];
				}
			}
		}

		if ( isset( $ciyashop_options['sub_menu_fonts'] ) && ! empty( $ciyashop_options['sub_menu_fonts'] ) ) {
			if ( isset( $ciyashop_options['menu_fonts']['font-family'] ) && ! empty( $ciyashop_options['menu_fonts']['font-family'] ) ) {
				$dynamic_css['.primary-nav .primary-menu > li .sub-menu > li a, .main-navigation-sticky .primary-menu > li .sub-menu > li a, .header-style-custom .primary-nav .primary-menu > li .sub-menu > li a, .header-style-custom .main-navigation-sticky .primary-menu > li .sub-menu > li a']['font-family'] = $ciyashop_options['menu_fonts']['font-family'];
			}
			foreach ( $menu_text_typo_attrs as $menu_text_typo_attr ) {
				if ( isset( $ciyashop_options['sub_menu_fonts'][ $menu_text_typo_attr ] ) && ! empty( $ciyashop_options['sub_menu_fonts'][ $menu_text_typo_attr ] ) ) {
					$dynamic_css['.primary-nav .primary-menu > li .sub-menu > li a, .main-navigation-sticky .primary-menu > li .sub-menu > li a'][ $menu_text_typo_attr ] = $ciyashop_options['sub_menu_fonts'][ $menu_text_typo_attr ];
				}
			}
		}
	}
}
/**
 * Ciyashop topbar css
 *
 * @param string $ciyashop_options .
 */
function ciyashop_topbar_css( $ciyashop_options ) {
	global $dynamic_css;

	$post_id = ciyashop_dynamic_css_page_id();

	if ( isset( $ciyashop_options['header_type_select'] ) && 'header_builder' === $ciyashop_options['header_type_select'] ) {
		return false;
	}

	$topbar_bg_type = isset( $ciyashop_options['topbar_bg_type'] ) && ! empty( $ciyashop_options['topbar_bg_type'] ) ? $ciyashop_options['topbar_bg_type'] : 'default';

	if ( 'default' !== $topbar_bg_type ) {
		if ( 'custom' === $topbar_bg_type ) {
			$topbar_bg_color = isset( $ciyashop_options['topbar_bg_color'] ) && ! empty( $ciyashop_options['topbar_bg_color'] ) ? $ciyashop_options['topbar_bg_color'] : array( 'rgba' => '#ffffff' );
			$dynamic_css['.header-style-right-topbar-main #masthead-inner > .topbar, #masthead-inner > .topbar, header.site-header .header-main-top .topbar, .header-style-right-topbar-main #masthead-inner > .topbar.topbar-bg-color-custom']['background-color'] = $topbar_bg_color['rgba'];
		}
		$topbar_text_color = isset( $ciyashop_options['topbar_text_color'] ) && ! empty( $ciyashop_options['topbar_text_color'] ) ? $ciyashop_options['topbar_text_color'] : '#323232';
		$dynamic_css['.topbar .ciyashop-woocommerce-currency-switcher, .topbar-link .language span, .topbar .topbar-link > ul > li .language i, .topbar .topbar-link .language .drop-content li a, .header-style-topbar-with-main-header .ciyashop-woocommerce-currency-switcher,  .topbar .select2-container--default .select2-selection--single .select2-selection__rendered, .header-style-menu-center .topbar .select2-container--default .select2-selection--single .select2-selection__rendered, .header-style-menu-right .topbar .select2-container--default .select2-selection--single .select2-selection__rendered, .header-style-topbar-with-main-header .header-main .select2-container--default .select2-selection--single .select2-selection__rendered, .header-style-right-topbar-main .topbar .select2-container--default .select2-selection--single .select2-selection__rendered, .header-style-right-topbar-main #masthead-inner > .topbar.topbar-bg-color-custom .topbar-link > ul > li a i, .header-style-right-topbar-main #masthead-inner > .topbar.topbar-bg-color-custom .select2-container--default .select2-selection--single .select2-selection__rendered, .topbar .select2-container--default .select2-selection--single .select2-selection__arrow b, .header-style-menu-center .topbar .select2-container--default .select2-selection--single .select2-selection__arrow b']['color'] = $topbar_text_color;

		$dynamic_css['.topbar .select2-container--default .select2-selection--single .select2-selection__arrow b, .header-style-menu-center .topbar .select2-container--default .select2-selection--single .select2-selection__arrow b, .header-style-menu-right .topbar .select2-container--default .select2-selection--single .select2-selection__arrow b, .header-style-topbar-with-main-header .header-main .select2-container--default .select2-selection--single .select2-selection__arrow b, .header-style-right-topbar-main .topbar .select2-container--default .select2-selection--single .select2-selection__arrow b, .header-style-right-topbar-main #masthead-inner > .topbar.topbar-bg-color-custom .select2-container--default .select2-selection--single .select2-selection__arrow b']['border-top-color'] = $topbar_text_color;
		$dynamic_css['.topbar .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b, .header-style-menu-center .topbar .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b, .header-style-menu-right .topbar .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b, .header-style-topbar-with-main-header .header-main .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b, .header-style-right-topbar-main .topbar .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b, .header-style-right-topbar-main #masthead-inner > .topbar.topbar-bg-color-custom .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b']['border-bottom-color'] = $topbar_text_color;

		$topbar_link_color = isset( $ciyashop_options['topbar_link_color'] ) && ! empty( $ciyashop_options['topbar_link_color'] ) ? $ciyashop_options['topbar_link_color'] : array(
			'regular' => '#323232',
			'hover'   => '#04d39f',
		);
		if ( isset( $topbar_link_color['regular'] ) && ! empty( $topbar_link_color['regular'] ) ) {
			$dynamic_css['.topbar .topbar-link > ul > li a, .header-style-topbar-with-main-header .topbar-link > ul > li a, .header-style-right-topbar-main #masthead-inner > .topbar.topbar-bg-color-custom .topbar-link > ul > li a']['color'] = $topbar_link_color['regular'];
		}
		if ( isset( $topbar_link_color['hover'] ) && ! empty( $topbar_link_color['hover'] ) ) {
			$dynamic_css['.site-header .topbar a:hover, .topbar .topbar-link .language .drop-content li a:hover, .header-style-topbar-with-main-header .topbar-link > ul > li a:hover i, .header-style-topbar-with-main-header .topbar-link > ul > li a:hover, .header-style-right-topbar-main .header-main-bg-color-default .topbar-link > ul > li a:hover, .header-style-right-topbar-main #masthead-inner > .topbar.topbar-bg-color-custom .topbar-link > ul > li a:hover i, .header-style-right-topbar-main #masthead-inner > .topbar.topbar-bg-color-custom .topbar-link > ul > li a:hover']['color'] = $topbar_link_color['hover'];
		}
	}
}
/**
 * Ciyashop header main css
 *
 * @param string $ciyashop_options .
 */
function ciyashop_header_main_css( $ciyashop_options ) {
	global $dynamic_css;

	if ( isset( $ciyashop_options['header_type_select'] ) && 'header_builder' === $ciyashop_options['header_type_select'] ) {
		return;
	}

	$post_id = ciyashop_dynamic_css_page_id();

	$header_main_bg_type = isset( $ciyashop_options['header_main_bg_type'] ) && ! empty( $ciyashop_options['header_main_bg_type'] ) ? $ciyashop_options['header_main_bg_type'] : 'default';

	if ( 'default' !== $header_main_bg_type ) {
		if ( 'custom' === $header_main_bg_type ) {
			$header_main_bg_color = isset( $ciyashop_options['header_main_bg_color'] ) && ! empty( $ciyashop_options['header_main_bg_color'] ) ? $ciyashop_options['header_main_bg_color'] : array( 'rgba' => '#FFFFFF' );
			$dynamic_css['
				header.site-header .header-main, .header-mobile, .header-style-right-topbar-main .header-main-bottom,
				.header-above-content .header-main-bg-color-custom .woo-tools-cart .cart-link .count,
				.header-above-content .header-main-bg-color-custom .woo-tools-wishlist .ciyashop-wishlist-count']['background-color'] = $header_main_bg_color['rgba'];
			$dynamic_css['.header-main-bg-color-custom .woo-tools-cart .cart-link .count, .header-main-bg-color-custom .woo-tools-wishlist .ciyashop-wishlist-count']['color'] = $header_main_bg_color['rgba'];
		}
		$header_main_text_color = isset( $ciyashop_options['header_main_text_color'] ) && ! empty( $ciyashop_options['header_main_text_color'] ) ? $ciyashop_options['header_main_text_color'] : '#323232';
		$dynamic_css['.header-main, .header-main .woo-tools-actions > li i, .header-main .search-button-wrap .search-button, .header-mobile .woo-tools-actions > li i, .header-mobile .mobile-butoon-search > a, .header-mobile .mobile-butoon-menu > a']['color'] = $header_main_text_color;
		$dynamic_css['.header-mobile .mobile-butoon-menu span, .header-mobile .mobile-butoon-menu span:before, .header-mobile .mobile-butoon-menu span:after']['background-color'] = $header_main_text_color;

		$header_main_link_color = isset( $ciyashop_options['header_main_link_color'] ) && ! empty( $ciyashop_options['header_main_link_color'] ) ? $ciyashop_options['header_main_link_color'] : array(
			'regular' => '#323232',
			'hover'   => '#04d39f',
		);
		if ( isset( $header_main_link_color['regular'] ) && ! empty( $header_main_link_color['regular'] ) ) {
			$dynamic_css['
				.header-main a, 
				.header-style-menu-center .primary-nav .primary-menu > li > a, 
				.header-style-menu-right .primary-nav .primary-menu > li > a, 
				.header-style-right-topbar-main .header-nav .primary-nav .primary-menu > li > a, 
				.header-above-content .header-main-bg-color-custom .woo-tools-cart .cart-link .count,
				.header-above-content .header-main-bg-color-custom .woo-tools-wishlist .ciyashop-wishlist-count']['color'] = $header_main_link_color['regular'];
		}
		if ( isset( $header_main_link_color['hover'] ) && ! empty( $header_main_link_color['hover'] ) ) {
			$dynamic_css['.header-main a:hover, .woo-tools-actions > li i:hover, .site-header .search-button-wrap .search-button:hover, .header-style-menu-center .header-main-bg-color-custom .primary-nav .primary-menu > li > a:hover, .header-style-right-topbar-main .header-main-bg-color-custom .primary-nav .primary-menu > li > a:hover, 
.header-style-right-topbar-main .header-main.header-main-bg-color-custom .primary-nav .primary-menu > li.current-menu-item > a, 
.header-style-right-topbar-main .header-main.header-main-bg-color-custom .primary-nav .primary-menu > li.current-menu-item > a:before,
.header-style-right-topbar-main .header-main.header-main-bg-color-custom .primary-nav .primary-menu > li.current-menu-item > a,
.header-style-right-topbar-main .header-main.header-main-bg-color-custom .primary-nav .primary-menu > li.current-menu-ancestor > a,
.header-style-right-topbar-main .header-main.header-main-bg-color-custom .primary-nav .primary-menu > li.current-menu-ancestor > a, 
.header-style-right-topbar-main .header-main-bg-color-custom .primary-nav .primary-menu > li.current-menu-ancestor > a, 
.header-style-right-topbar-main .header-main-bg-color-custom .primary-nav .primary-menu > li.current-menu-ancestor > a:before, 
.header-style-logo-center .header-main .woo-tools-actions > li > a:hover i, .header-main.header-main-bg-color-custom .woo-tools-actions > li i:hover, 
.header-style-default .header-main.header-nav-bg-color-custom .primary-menu > li:hover > a, 
.header-style-default .header-main.header-nav-bg-color-custom .primary-menu > li > a:hover,
.header-style-logo-center .header-main.header-nav-bg-color-custom .primary-menu > li:hover > a, 
.header-style-logo-center .header-main.header-nav-bg-color-custom .primary-menu > li > a:hover,
.header-style-menu-center .header-main.header-main-bg-color-custom .primary-menu > li:hover > a, 
.header-style-menu-center .header-main.header-main-bg-color-custom .primary-menu > li > a:hover,
.header-style-menu-right .header-main.header-main-bg-color-custom .primary-menu > li:hover > a, 
.header-style-menu-right .header-main.header-main-bg-color-custom .primary-menu > li > a:hover,
.header-style-topbar-with-main-header .header-main.header-nav-bg-color-custom .primary-menu > li:hover > a, 
.header-style-topbar-with-main-header .header-main.header-nav-bg-color-custom .primary-menu > li > a:hover,
.header-style-right-topbar-main .header-main.header-main-bg-color-custom .primary-menu > li:hover > a, 
.header-style-right-topbar-main .header-main.header-main-bg-color-custom .primary-menu > li > a:hover,
.header-style-menu-center .header-main.header-main-bg-color-custom .primary-menu > li.current-menu-item > a, 
.header-style-menu-center .header-main.header-main-bg-color-custom .primary-menu > li.current-menu-ancestor > a,
.header-style-menu-right .header-main.header-main-bg-color-custom .primary-menu > li.current-menu-item > a, 
.header-style-menu-right .header-main.header-main-bg-color-custom .primary-menu > li.current-menu-ancestor > a']['color'] = $header_main_link_color['hover'];
		}
		if ( isset( $header_main_link_color['hover'] ) && ! empty( $header_main_link_color['hover'] ) ) {
			$dynamic_css['
			.header-style-menu-center .primary-nav .primary-menu > li > a:after,
			.header-style-menu-right .primary-nav .primary-menu > li > a:after, 
			.header-main-bg-color-custom .woo-tools-cart .cart-link .count,
			.header-main-bg-color-custom .woo-tools-wishlist .ciyashop-wishlist-count
			']['background-color'] = $header_main_link_color['hover'];
		}
	}

}
/**
 * Ciyashop header nav css
 *
 * @param string $ciyashop_options .
 */
function ciyashop_header_nav_css( $ciyashop_options ) {
	global $dynamic_css;

	$post_id = ciyashop_dynamic_css_page_id();

	$header_nav_bg_type = isset( $ciyashop_options['header_nav_bg_type'] ) && ! empty( $ciyashop_options['header_nav_bg_type'] ) ? $ciyashop_options['header_nav_bg_type'] : 'default';

	if ( 'default' !== $header_nav_bg_type ) {
		if ( 'custom' === $header_nav_bg_type ) {
			$header_nav_bg_color = isset( $ciyashop_options['header_nav_bg_color'] ) && ! empty( $ciyashop_options['header_nav_bg_color'] ) ? $ciyashop_options['header_nav_bg_color'] : array( 'rgba' => '#04d39f' );
			$dynamic_css['.site-header .header-nav']['background-color'] = $header_nav_bg_color['rgba'];
		}
		$header_nav_text_color = isset( $ciyashop_options['header_nav_text_color'] ) && ! empty( $ciyashop_options['header_nav_text_color'] ) ? $ciyashop_options['header_nav_text_color'] : '#FFFFFF';
		$dynamic_css['.header-nav, .header-style-topbar-with-main-header .header-nav .woo-tools-actions > li i, .header-style-topbar-with-main-header .header-nav .search-button-wrap .search-button']['color'] = $header_nav_text_color;

		$header_nav_link_color = isset( $ciyashop_options['header_nav_link_color'] ) && ! empty( $ciyashop_options['header_nav_link_color'] ) ? $ciyashop_options['header_nav_link_color'] : array(
			'regular' => '#323232',
			'hover'   => '#04d39f',
		);
		if ( isset( $header_nav_link_color['regular'] ) && ! empty( $header_nav_link_color['regular'] ) ) {
			$dynamic_css['.header-nav .primary-nav .primary-menu > li a']['color'] = $header_nav_link_color['regular'];
		}
		if ( isset( $header_nav_link_color['hover'] ) && ! empty( $header_nav_link_color['hover'] ) ) {
			$dynamic_css['.primary-nav .primary-menu > li a:hover, 
.site-header .header-nav .search-button-wrap .search-button:hover, 
.header-style-topbar-with-main-header .header-nav .woo-tools-actions > li i:hover,  
.header-nav.header-main-bg-color-default .primary-nav .primary-menu > li.current-menu-ancestor > a, 
.header-style-default .header-nav.header-nav-bg-color-custom .primary-menu > li:hover > a, 
.header-style-default .header-nav.header-nav-bg-color-custom .primary-menu > li > a:hover,
.header-style-default .header-nav.header-nav-bg-color-custom .primary-menu > li.current-menu-item > a,
.header-style-default .header-nav.header-nav-bg-color-custom .primary-menu > li.current-menu-ancestor > a,
.header-style-logo-center .header-nav.header-nav-bg-color-custom .primary-menu > li:hover > a, 
.header-style-logo-center .header-nav.header-nav-bg-color-custom .primary-menu > li > a:hover,
.header-style-logo-center .header-nav.header-nav-bg-color-custom .primary-menu > li.current-menu-item > a,
.header-style-logo-center .header-nav.header-nav-bg-color-custom .primary-menu > li.current-menu-ancestor > a,
.header-style-topbar-with-main-header .header-nav.header-nav-bg-color-custom .primary-menu > li:hover > a, 
.header-style-topbar-with-main-header .header-nav.header-nav-bg-color-custom .primary-menu > li > a:hover,
.header-style-topbar-with-main-header .header-nav.header-nav-bg-color-custom .primary-menu > li.current-menu-item > a,
.header-style-topbar-with-main-header .header-nav.header-nav-bg-color-custom .primary-menu > li.current-menu-ancestor > a,
.header-nav.header-main-bg-color-default .primary-nav .primary-menu > li .sub-menu li:hover > a, 
.header-nav.header-main-bg-color-default .primary-nav .primary-menu > li .sub-menu li > a:hover, 
.header-nav.header-main-bg-color-default .primary-nav .primary-menu > li .sub-menu li.current-menu-ancestor > a, 
.header-nav.header-main-bg-color-default .primary-nav .primary-menu > li .sub-menu li.current-menu-parent > a, 
.header-nav.header-main-bg-color-default .primary-nav .primary-menu > li .sub-menu li.current-menu-item > a ']['color'] = $header_nav_link_color['hover'];
		}
		if ( isset( $header_nav_link_color['hover'] ) && ! empty( $header_nav_link_color['hover'] ) ) {
			$dynamic_css['body .header-style-default .primary-nav .primary-menu > li:before, body .header-style-topbar-with-main-header .primary-nav .primary-menu > li:before, body .header-style-logo-center .primary-nav .primary-menu > li:before']['background-color'] = $header_nav_link_color['hover'];
		}
	}
}
/**
 * Ciyashop header sticky css
 *
 * @param string $ciyashop_options .
 */
function ciyashop_header_sticky_css( $ciyashop_options ) {
	global $dynamic_css;

	$post_id = ciyashop_dynamic_css_page_id();

	// Sticky header background color.
	if ( isset( $ciyashop_options['sticky_header_color'] ) && ! empty( $ciyashop_options['sticky_header_color'] ) ) {
		$dynamic_css['#header-sticky']['background-color'] = $ciyashop_options['sticky_header_color'];
	}

	// Sticky header text color.
	if ( isset( $ciyashop_options['sticky_header_text_color'] ) && ! empty( $ciyashop_options['sticky_header_text_color'] ) ) {
		$dynamic_css['#header-sticky, .main-navigation-sticky .primary-menu > li > a, .main-navigation-sticky #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-item > a.mega-menu-link, .header-sticky-inner .woo-tools-actions > li i']['color'] = $ciyashop_options['sticky_header_text_color'];
	}
	if ( isset( $ciyashop_options['sticky_header_text_color'] ) && ! empty( $ciyashop_options['sticky_header_text_color'] ) ) {
		$dynamic_css['#header-sticky #site-navigation-sticky-mobile .slicknav_menu .slicknav_icon-bar']['background-color'] = $ciyashop_options['sticky_header_text_color'];
	}

	// Sticky header link color.
	if ( isset( $ciyashop_options['sticky_header_link_color'] ) && ! empty( $ciyashop_options['sticky_header_link_color'] ) ) {
		$dynamic_css['.main-navigation-sticky .primary-menu > li:hover > a, .main-navigation-sticky .primary-menu > li > a:hover, .main-navigation-sticky .primary-menu > li.current-menu-item > a, .main-navigation-sticky .primary-menu > li.current-menu-ancestor > a, .main-navigation-sticky .primary-menu > li.current-menu-ancestor > a:before, .main-navigation-sticky #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-item > a.mega-menu-link:hover, .site-header .header-sticky #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-item.mega-current_page_item > a.mega-menu-link,
.site-header .header-sticky #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-item.mega-current-menu-ancestor > a.mega-menu-link, 
.main-navigation-sticky .primary-menu > li.current-menu-item > a:before, 
.main-navigation-sticky .primary-menu > li.current-menu-ancestor > a:before, 
.main-navigation-sticky .primary-menu > li.current_page_item > a,
.header-sticky-inner .woo-tools-actions > li i:hover']['color'] = $ciyashop_options['sticky_header_link_color'];
	}
	if ( isset( $ciyashop_options['sticky_header_link_color'] ) && ! empty( $ciyashop_options['sticky_header_link_color'] ) ) {
		$dynamic_css['
			#header-sticky-sticky-wrapper .primary-menu > li:before, 
			.header-sticky-inner .woo-tools-cart .cart-link .count, 
			.header-sticky-inner .woo-tools-wishlist .ciyashop-wishlist-count']['background-color'] = $ciyashop_options['sticky_header_link_color'];
	}
}
/**
 * Ciyashop page header css
 *
 * @param string $ciyashop_options .
 */
function ciyashop_page_header_css( $ciyashop_options ) {
	global $dynamic_css;

	$post_id = ciyashop_dynamic_css_page_id();

	// Page header height.
	$pageheader_height = ( isset( $ciyashop_options['pageheader_height'] ) ) ? $ciyashop_options['pageheader_height'] : '150';
	if ( ! empty( $pageheader_height ) ) {
		$dynamic_css['.inner-intro']['height'] = $pageheader_height . 'px';
	}

	// Generate Banner CSS from Options.
	$banner_type = ( isset( $ciyashop_options['banner_type'] ) ) ? $ciyashop_options['banner_type'] : 'image';

	if ( 'image' === $banner_type ) {

		$banner_image_bg_url = get_parent_theme_file_uri( '/images/page-header.jpg' );

		if ( isset( $ciyashop_options['banner_image'] ) && ! empty( $ciyashop_options['banner_image'] ) ) {
			if ( isset( $ciyashop_options['banner_image']['background-image'] ) && ! empty( $ciyashop_options['banner_image']['background-image'] ) ) {
				$banner_image_bg_url = $ciyashop_options['banner_image']['background-image'];
			}

			// Set other properties.
			if ( isset( $ciyashop_options['banner_image']['background-repeat'] ) && ! empty( $ciyashop_options['banner_image']['background-repeat'] ) ) {
				$dynamic_css['.header_intro_bg-image']['background-repeat'] = $ciyashop_options['banner_image']['background-repeat'];
			}
			if ( isset( $ciyashop_options['banner_image']['background-size'] ) && ! empty( $ciyashop_options['banner_image']['background-size'] ) ) {
				$dynamic_css['.header_intro_bg-image']['background-size'] = $ciyashop_options['banner_image']['background-size'];
			}
			if ( isset( $ciyashop_options['banner_image']['background-attachment'] ) && ! empty( $ciyashop_options['banner_image']['background-attachment'] ) ) {
				$dynamic_css['.header_intro_bg-image']['background-attachment'] = $ciyashop_options['banner_image']['background-attachment'];
			}
			if ( isset( $ciyashop_options['banner_image']['background-position'] ) && ! empty( $ciyashop_options['banner_image']['background-position'] ) ) {
				$dynamic_css['.header_intro_bg-image']['background-position'] = $ciyashop_options['banner_image']['background-position'];
			}
		}

		$dynamic_css['.header_intro_bg-image']['background-image'] = 'url(\'' . $banner_image_bg_url . '\')';

	} elseif ( 'color' === $banner_type ) {
		if ( ! empty( $ciyashop_options['banner_image_color'] ) ) {
			$dynamic_css['.header_intro_bg-color']['background-color'] = $ciyashop_options['banner_image_color'];
		} else {
			$dynamic_css['.header_intro_bg-color']['background-color'] = '#000000';
		}
	}

	if ( 'video' === $banner_type || 'image' === $banner_type ) {
		$banner_image_opacity = ( isset( $ciyashop_options['banner_image_opacity'] ) ) ? $ciyashop_options['banner_image_opacity'] : 'black';
		if ( ! empty( $banner_image_opacity ) && 'custom' === $banner_image_opacity ) {
			$banner_image_opacity_custom_color = $ciyashop_options['banner_image_opacity_custom_color'];
			if ( ! empty( $banner_image_opacity_custom_color ) ) {
				if ( isset( $banner_image_opacity_custom_color['rgba'] ) ) {
					$header_intro_opacity_background_color = $banner_image_opacity_custom_color['rgba'];
				} else {
					$header_intro_opacity_background_color = ciyashop_hex2rgba( $banner_image_opacity_custom_color['color'], $banner_image_opacity_custom_color['alpha'] );
				}
				$dynamic_css['.header_intro_opacity::before']['background-color'] = $header_intro_opacity_background_color;
			}
		}
	}

	// Generate Banner CSS from Singple Page.
	if ( is_page() || is_single() || ( function_exists( 'is_shop' ) && is_shop() ) || ( is_home() && ! is_front_page() ) ) {
		$header_settings_source = get_post_meta( $post_id, 'header_settings_source', true );

		if ( 'custom' === $header_settings_source ) {
			// Unset data set from options.
			$banner_type = '';
			unset( $dynamic_css['.header_intro_bg-image'] );
			unset( $dynamic_css['.header_intro_opacity::before'] );
			unset( $dynamic_css['.header_intro_bg-color'] );

			$banner_type = get_post_meta( $post_id, 'banner_type', true );
			if ( empty( $banner_type ) ) {
				$banner_type = 'image';
			}

			if ( $banner_type && 'image' === $banner_type ) {
				// Default Image.
				$banner_image_bg_url    = get_parent_theme_file_uri( '/images/page-header.jpg' );
				$banner_image_bg_custom = get_post_meta( $post_id, 'banner_image_bg_custom', true );

				if ( $banner_image_bg_custom && isset( $banner_image_bg_custom['background-image'] ) && ! empty( $banner_image_bg_custom['background-image'] ) ) {
					$banner_image_bg_url = $banner_image_bg_custom['background-image'];

					// Set other properties.
					if ( isset( $banner_image_bg_custom['background-repeat'] ) && ! empty( $banner_image_bg_custom['background-repeat'] ) ) {
						$dynamic_css['.header_intro_bg-image']['background-repeat'] = $banner_image_bg_custom['background-repeat'];
					}
					if ( isset( $banner_image_bg_custom['background-size'] ) && ! empty( $banner_image_bg_custom['background-size'] ) ) {
						$dynamic_css['.header_intro_bg-image']['background-size'] = $banner_image_bg_custom['background-size'];
					}
					if ( isset( $banner_image_bg_custom['background-attachment'] ) && ! empty( $banner_image_bg_custom['background-attachment'] ) ) {
						$dynamic_css['.header_intro_bg-image']['background-attachment'] = $banner_image_bg_custom['background-attachment'];
					}
					if ( isset( $banner_image_bg_custom['background-position'] ) && ! empty( $banner_image_bg_custom['background-position'] ) ) {
						$dynamic_css['.header_intro_bg-image']['background-position'] = $banner_image_bg_custom['background-position'];
					}
				}
				$dynamic_css['.header_intro_bg-image']['background-image'] = 'url(\'' . $banner_image_bg_url . '\')';

			} elseif ( $banner_type && 'color' === $banner_type ) {
				$banner_image_color = get_post_meta( $post_id, 'banner_image_color', true );

				if ( $banner_image_color ) {
					$dynamic_css['.header_intro_bg-color']['background-color'] = $banner_image_color;
				}
			}

			if ( $banner_type && ( 'image' === $banner_type || 'video' === $banner_type ) ) {
				$background_opacity_color = get_post_meta( $post_id, 'background_opacity_color', true );
				if ( $background_opacity_color && 'custom' === $background_opacity_color ) {
					$banner_image_opacity_custom_color   = get_post_meta( $post_id, 'banner_image_opacity_custom_color', true );
					$banner_image_opacity_custom_opacity = get_post_meta( $post_id, 'banner_image_opacity_custom_opacity', true );
					if ( empty( $banner_image_opacity_custom_color ) ) {
						$banner_image_opacity_custom_color = '#191919';
					}
					if ( empty( $banner_image_opacity_custom_opacity ) ) {
						$banner_image_opacity_custom_opacity = .8;
					}
					$banner_color = ciyashop_hex2rgba( $banner_image_opacity_custom_color, $banner_image_opacity_custom_opacity );
					$dynamic_css['.header_intro_opacity::before']['background-color'] = $banner_color;
				}
			}

			/*Page Header Section Height */
			$page_header_height = get_post_meta( $post_id, 'page_header_height', true );
			if ( ! empty( $page_header_height ) ) {
				$dynamic_css['.inner-intro']['height'] = $page_header_height . 'px';
			}
		}
	}
}
/**
 * Ciyashop shop category header css
 *
 * @param string $ciyashop_options .
 */
function ciyashop_shop_categories_header_css( $ciyashop_options ) {
	global $dynamic_css;

	if ( class_exists( 'WooCommerce' ) && ( isset( $ciyashop_options['shop_categories_style'] ) && 'style-1' !== $ciyashop_options['shop_categories_style'] ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {

		$banner_image_bg_url        = '';
		$banner_image_bg_repeat     = '';
		$banner_image_bg_size       = '';
		$banner_image_bg_attachment = '';
		$banner_image_bg_position   = '';
		$banner_image_bg_color      = '';

		if ( isset( $ciyashop_options['shop_categories_background']['background-image'] ) && $ciyashop_options['shop_categories_background']['background-image'] ) {
			$banner_image_bg_url = $ciyashop_options['shop_categories_background']['background-image'];
		}
		if ( isset( $ciyashop_options['shop_categories_background']['background-repeat'] ) && $ciyashop_options['shop_categories_background']['background-repeat'] ) {
			$banner_image_bg_repeat = $ciyashop_options['shop_categories_background']['background-repeat'];
		}
		if ( isset( $ciyashop_options['shop_categories_background']['background-size'] ) && $ciyashop_options['shop_categories_background']['background-size'] ) {
			$banner_image_bg_size = $ciyashop_options['shop_categories_background']['background-size'];
		}
		if ( isset( $ciyashop_options['shop_categories_background']['background-attachment'] ) && $ciyashop_options['shop_categories_background']['background-attachment'] ) {
			$banner_image_bg_attachment = $ciyashop_options['shop_categories_background']['background-attachment'];
		}
		if ( isset( $ciyashop_options['shop_categories_background']['background-position'] ) && $ciyashop_options['shop_categories_background']['background-position'] ) {
			$banner_image_bg_position = $ciyashop_options['shop_categories_background']['background-position'];
		}
		if ( isset( $ciyashop_options['shop_categories_background']['background-color'] ) && $ciyashop_options['shop_categories_background']['background-color'] ) {
			$banner_image_bg_color = $ciyashop_options['shop_categories_background']['background-color'];
		}

		if ( $banner_image_bg_url ) {
			if ( $banner_image_bg_repeat ) {
				$dynamic_css['.woocommerce-categories-slider']['background-repeat'] = $banner_image_bg_repeat;
			}
			if ( $banner_image_bg_size ) {
				$dynamic_css['.woocommerce-categories-slider']['background-size'] = $banner_image_bg_size;
			}
			if ( $banner_image_bg_attachment ) {
				$dynamic_css['.woocommerce-categories-slider']['background-attachment'] = $banner_image_bg_attachment;
			}
			if ( $banner_image_bg_position ) {
				$dynamic_css['.woocommerce-categories-slider']['background-position'] = $banner_image_bg_position;
			}
		}

		if ( $banner_image_bg_color ) {
			$dynamic_css['.woocommerce-categories-slider']['background-color'] = $banner_image_bg_color;
		}

		if ( isset( $ciyashop_options['shop_categories_opacity_color'] ) && ! empty( $ciyashop_options['shop_categories_opacity_color'] ) ) {
			if ( isset( $ciyashop_options['shop_categories_opacity_color']['rgba'] ) ) {
				$dynamic_css['.woocommerce-categories-wrapper .woocommerce-categories-slider-style-2:before, .woocommerce-categories-wrapper .woocommerce-categories-slider-style-3:before']['background-color'] = $ciyashop_options['shop_categories_opacity_color']['rgba'];
			} else {
				$dynamic_css['.woocommerce-categories-wrapper .woocommerce-categories-slider-style-2:before, .woocommerce-categories-wrapper .woocommerce-categories-slider-style-3:before']['background-color'] = ciyashop_hex2rgba( $ciyashop_options['shop_categories_opacity_color']['color'], $ciyashop_options['shop_categories_opacity_color']['alpha'] );
			}
		}
	}
}
/**
 * Ciyashop content css
 *
 * @param string $ciyashop_options .
 */
function ciyashop_content_css( $ciyashop_options ) {
	global $dynamic_css, $ciyashop_options, $ciyashop_body_typo, $ciyashop_h1_typo, $ciyashop_h2_typo, $ciyashop_h3_typo, $ciyashop_h4_typo, $ciyashop_h5_typo, $ciyashop_h6_typo;

	$post_id = ciyashop_dynamic_css_page_id();

	/* Body Typography*/
	if ( isset( $ciyashop_options['typography-body'] ) && ! empty( $ciyashop_options['typography-body'] ) && ! empty( $ciyashop_body_typo['family'] ) ) {

		/*Body Font family, font weight, letter spacing */
		if ( isset( $ciyashop_options['typography-body']['font-family'] ) && ! empty( $ciyashop_options['typography-body']['font-family'] ) ) {
			if ( isset( $ciyashop_options['typography-body']['font-backup'] ) && ! empty( $ciyashop_options['typography-body']['font-backup'] ) ) {
				$dynamic_css[ $ciyashop_body_typo['family'] ]['font-family'] = '"' . $ciyashop_options['typography-body']['font-family'] . '", ' . $ciyashop_options['typography-body']['font-backup'];
			} else {
				$dynamic_css[ $ciyashop_body_typo['family'] ]['font-family'] = $ciyashop_options['typography-body']['font-family'];
			}
		}

		if ( isset( $ciyashop_options['typography-body']['font-style'] ) && ! empty( $ciyashop_options['typography-body']['font-style'] ) ) {
			$dynamic_css[ $ciyashop_body_typo['family'] ]['font-style'] = $ciyashop_options['typography-body']['font-style'];
		}

		if ( isset( $ciyashop_options['typography-body']['font-weight'] ) && ! empty( $ciyashop_options['typography-body']['font-weight'] ) ) {
			$dynamic_css[ $ciyashop_body_typo['family'] ]['font-weight'] = $ciyashop_options['typography-body']['font-weight'];
		}

		if ( isset( $ciyashop_options['typography-body']['letter-spacing'] ) && ! empty( $ciyashop_options['typography-body']['letter-spacing'] ) ) {
			$dynamic_css[ $ciyashop_body_typo['family'] ]['letter-spacing'] = $ciyashop_options['typography-body']['letter-spacing'];
		}

		/* Body Line Height */
		if ( isset( $ciyashop_options['typography-body']['line-height'] ) && ! empty( $ciyashop_options['typography-body']['line-height'] ) ) {
			$dynamic_css[ $ciyashop_body_typo['line-height'] ]['line-height'] = $ciyashop_options['typography-body']['line-height'];
		}
		/* Body Font Size */
		if ( isset( $ciyashop_options['typography-body']['font-size'] ) && ! empty( $ciyashop_options['typography-body']['font-size'] ) ) {
			$dynamic_css[ $ciyashop_body_typo['font-size'] ]['font-size'] = $ciyashop_options['typography-body']['font-size'];
		}
	}

	/* h1 Typography*/
	if ( isset( $ciyashop_options['h1-typography'] ) && ! empty( $ciyashop_options['h1-typography'] ) && ! empty( $ciyashop_h1_typo['family'] ) ) {

		/* h1 Typography Font family, font weight, letter spacing */
		if ( isset( $ciyashop_options['h1-typography']['font-backup'] ) && ! empty( $ciyashop_options['h1-typography']['font-backup'] ) ) {
			$dynamic_css[ $ciyashop_h1_typo['family'] ]['font-family'] = '"' . $ciyashop_options['h1-typography']['font-family'] . '", ' . $ciyashop_options['h1-typography']['font-backup'];
		} elseif ( ! empty( $ciyashop_options['ciyashop_h1_typo']['font-family'] ) ) {
			$dynamic_css[ $ciyashop_h1_typo['family'] ]['font-family'] = $ciyashop_options['ciyashop_h1_typo']['font-family'];
		}

		if ( isset( $ciyashop_options['h1-typography']['font-style'] ) && ! empty( $ciyashop_options['h1-typography']['font-style'] ) ) {
			$dynamic_css[ $ciyashop_h1_typo['family'] ]['font-style'] = $ciyashop_options['h1-typography']['font-style'];
		}

		if ( isset( $ciyashop_options['h1-typography']['font-weight'] ) && ! empty( $ciyashop_options['h1-typography']['font-weight'] ) ) {
			$dynamic_css[ $ciyashop_h1_typo['family'] ]['font-weight'] = $ciyashop_options['h1-typography']['font-weight'];
		}
		if ( isset( $ciyashop_options['h1-typography']['letter-spacing'] ) && ! empty( $ciyashop_options['h1-typography']['letter-spacing'] ) ) {
			$dynamic_css[ $ciyashop_h1_typo['family'] ]['letter-spacing'] = $ciyashop_options['h1-typography']['letter-spacing'];
		}

		/* h1 Typography Line Height */
		if ( isset( $ciyashop_options['h1-typography']['line-height'] ) && ! empty( $ciyashop_options['h1-typography']['line-height'] ) ) {
			$dynamic_css[ $ciyashop_h1_typo['line-height'] ]['line-height'] = $ciyashop_options['h1-typography']['line-height'];
		}
		/* h1 Typography Font Size */
		if ( isset( $ciyashop_options['h1-typography']['font-size'] ) && ! empty( $ciyashop_options['h1-typography']['font-size'] ) ) {
			$dynamic_css[ $ciyashop_h1_typo['font-size'] ]['font-size'] = $ciyashop_options['h1-typography']['font-size'];
		}
	}

	/* h2 Typography*/
	if ( isset( $ciyashop_options['h2-typography'] ) && ! empty( $ciyashop_options['h2-typography'] ) && ! empty( $ciyashop_h2_typo['family'] ) ) {

		/* h2 Typography Font family, font weight, letter spacing */
		if ( isset( $ciyashop_options['h2-typography']['font-backup'] ) && ! empty( $ciyashop_options['h2-typography']['font-backup'] ) ) {
			$dynamic_css[ $ciyashop_h2_typo['family'] ]['font-family'] = '"' . $ciyashop_options['h2-typography']['font-family'] . '", ' . $ciyashop_options['h2-typography']['font-backup'];
		} else {
			$dynamic_css[ $ciyashop_h2_typo['family'] ]['font-family'] = $ciyashop_options['h2-typography']['font-family'];
		}

		if ( isset( $ciyashop_options['h2-typography']['font-style'] ) && ! empty( $ciyashop_options['h2-typography']['font-style'] ) ) {
			$dynamic_css[ $ciyashop_h2_typo['family'] ]['font-style'] = $ciyashop_options['h2-typography']['font-style'];
		}

		if ( isset( $ciyashop_options['h2-typography']['font-weight'] ) && ! empty( $ciyashop_options['h2-typography']['font-weight'] ) ) {
			$dynamic_css[ $ciyashop_h2_typo['family'] ]['font-weight'] = $ciyashop_options['h2-typography']['font-weight'];
		}
		if ( isset( $ciyashop_options['h2-typography']['letter-spacing'] ) && ! empty( $ciyashop_options['h2-typography']['letter-spacing'] ) ) {
			$dynamic_css[ $ciyashop_h2_typo['family'] ]['letter-spacing'] = $ciyashop_options['h2-typography']['letter-spacing'];
		}

		/* h2 Typography Line Height */
		if ( isset( $ciyashop_options['h2-typography']['line-height'] ) && ! empty( $ciyashop_options['h2-typography']['line-height'] ) ) {
			$dynamic_css[ $ciyashop_h2_typo['line-height'] ]['line-height'] = $ciyashop_options['h2-typography']['line-height'];
		}
		/* h2 Typography Font Size */
		if ( isset( $ciyashop_options['h2-typography']['font-size'] ) && ! empty( $ciyashop_options['h2-typography']['font-size'] ) ) {
			$dynamic_css[ $ciyashop_h2_typo['font-size'] ]['font-size'] = $ciyashop_options['h2-typography']['font-size'];
		}
	}

	/* h3 Typography*/
	if ( isset( $ciyashop_options['h3-typography'] ) && ! empty( $ciyashop_options['h3-typography'] ) && ! empty( $ciyashop_h3_typo['family'] ) ) {

		/* h3 Typography Font family, font weight, letter spacing */
		if ( isset( $ciyashop_options['h3-typography']['font-backup'] ) && ! empty( $ciyashop_options['h3-typography']['font-backup'] ) ) {
			$dynamic_css[ $ciyashop_h3_typo['family'] ]['font-family'] = '"' . $ciyashop_options['h3-typography']['font-family'] . '", ' . $ciyashop_options['h3-typography']['font-backup'];
		} else {
			$dynamic_css[ $ciyashop_h3_typo['family'] ]['font-family'] = $ciyashop_options['h3-typography']['font-family'];
		}

		if ( isset( $ciyashop_options['h3-typography']['font-style'] ) && ! empty( $ciyashop_options['h3-typography']['font-style'] ) ) {
			$dynamic_css[ $ciyashop_h3_typo['family'] ]['font-style'] = $ciyashop_options['h3-typography']['font-style'];
		}

		if ( isset( $ciyashop_options['h3-typography']['font-weight'] ) && ! empty( $ciyashop_options['h3-typography']['font-weight'] ) ) {
			$dynamic_css[ $ciyashop_h3_typo['family'] ]['font-weight'] = $ciyashop_options['h3-typography']['font-weight'];
		}

		if ( isset( $ciyashop_options['h3-typography']['letter-spacing'] ) && ! empty( $ciyashop_options['h3-typography']['letter-spacing'] ) ) {
			$dynamic_css[ $ciyashop_h3_typo['family'] ]['letter-spacing'] = $ciyashop_options['h3-typography']['letter-spacing'];
		}
		/* h3 Typography Line Height */
		if ( isset( $ciyashop_options['h3-typography']['line-height'] ) && ! empty( $ciyashop_options['h3-typography']['line-height'] ) ) {
			$dynamic_css[ $ciyashop_h3_typo['line-height'] ]['line-height'] = $ciyashop_options['h3-typography']['line-height'];
		}
		/* h3 Typography Font Size */
		if ( isset( $ciyashop_options['h3-typography']['font-size'] ) && ! empty( $ciyashop_options['h3-typography']['font-size'] ) ) {
			$dynamic_css[ $ciyashop_h3_typo['font-size'] ]['font-size'] = $ciyashop_options['h3-typography']['font-size'];
		}
	}

	/* h4 Typography*/
	if ( isset( $ciyashop_options['h4-typography'] ) && ! empty( $ciyashop_options['h4-typography'] ) && ! empty( $ciyashop_h4_typo['family'] ) ) {

		/* h4 Typography Font family, font weight, letter spacing */
		if ( isset( $ciyashop_options['h4-typography']['font-backup'] ) && ! empty( $ciyashop_options['h4-typography']['font-backup'] ) ) {
			$dynamic_css[ $ciyashop_h4_typo['family'] ]['font-family'] = '"' . $ciyashop_options['h4-typography']['font-family'] . '", ' . $ciyashop_options['h4-typography']['font-backup'];
		} else {
			$dynamic_css[ $ciyashop_h4_typo['family'] ]['font-family'] = $ciyashop_options['h4-typography']['font-family'];
		}

		if ( isset( $ciyashop_options['h4-typography']['font-style'] ) && ! empty( $ciyashop_options['h4-typography']['font-style'] ) ) {
			$dynamic_css[ $ciyashop_h4_typo['family'] ]['font-style'] = $ciyashop_options['h4-typography']['font-style'];
		}

		if ( isset( $ciyashop_options['h4-typography']['font-weight'] ) && ! empty( $ciyashop_options['h4-typography']['font-weight'] ) ) {
			$dynamic_css[ $ciyashop_h4_typo['family'] ]['font-weight'] = $ciyashop_options['h4-typography']['font-weight'];
		}
		if ( isset( $ciyashop_options['h4-typography']['letter-spacing'] ) && ! empty( $ciyashop_options['h4-typography']['letter-spacing'] ) ) {
			$dynamic_css[ $ciyashop_h4_typo['family'] ]['letter-spacing'] = $ciyashop_options['h4-typography']['letter-spacing'];
		}
		/* h4 Typography Line Height */
		if ( isset( $ciyashop_options['h4-typography']['line-height'] ) && ! empty( $ciyashop_options['h4-typography']['line-height'] ) ) {
			$dynamic_css[ $ciyashop_h4_typo['line-height'] ]['line-height'] = $ciyashop_options['h4-typography']['line-height'];
		}
		/* h4 Typography Font Size */
		if ( isset( $ciyashop_options['h4-typography']['font-size'] ) && ! empty( $ciyashop_options['h4-typography']['font-size'] ) ) {
			$dynamic_css[ $ciyashop_h4_typo['font-size'] ]['font-size'] = $ciyashop_options['h4-typography']['font-size'];
		}
	}

	/* h5 Typography*/
	if ( isset( $ciyashop_options['h5-typography'] ) && ! empty( $ciyashop_options['h5-typography'] ) && ! empty( $ciyashop_h5_typo['family'] ) ) {

		/* h5 Typography Font family, font weight, letter spacing */
		if ( isset( $ciyashop_options['h5-typography']['font-backup'] ) && ! empty( $ciyashop_options['h5-typography']['font-backup'] ) ) {
			$dynamic_css[ $ciyashop_h5_typo['family'] ]['font-family'] = '"' . $ciyashop_options['h5-typography']['font-family'] . '", ' . $ciyashop_options['h5-typography']['font-backup'];
		} else {
			$dynamic_css[ $ciyashop_h5_typo['family'] ]['font-family'] = $ciyashop_options['h5-typography']['font-family'];
		}

		if ( isset( $ciyashop_options['h5-typography']['font-style'] ) && ! empty( $ciyashop_options['h5-typography']['font-style'] ) ) {
			$dynamic_css[ $ciyashop_h5_typo['family'] ]['font-style'] = $ciyashop_options['h5-typography']['font-style'];
		}

		if ( isset( $ciyashop_options['h5-typography']['font-weight'] ) && ! empty( $ciyashop_options['h5-typography']['font-weight'] ) ) {
			$dynamic_css[ $ciyashop_h5_typo['family'] ]['font-weight'] = $ciyashop_options['h5-typography']['font-weight'];
		}
		if ( isset( $ciyashop_options['h5-typography']['letter-spacing'] ) && ! empty( $ciyashop_options['h5-typography']['letter-spacing'] ) ) {
			$dynamic_css[ $ciyashop_h5_typo['family'] ]['letter-spacing'] = $ciyashop_options['h5-typography']['letter-spacing'];
		}
		/* h5 Typography Line Height */
		if ( isset( $ciyashop_options['h5-typography']['line-height'] ) && ! empty( $ciyashop_options['h5-typography']['line-height'] ) ) {
			$dynamic_css[ $ciyashop_h5_typo['line-height'] ]['line-height'] = $ciyashop_options['h5-typography']['line-height'];
		}
		/* h5 Typography Font Size */
		if ( isset( $ciyashop_options['h5-typography']['font-size'] ) && ! empty( $ciyashop_options['h5-typography']['font-size'] ) ) {
			$dynamic_css[ $ciyashop_h5_typo['font-size'] ]['font-size'] = $ciyashop_options['h5-typography']['font-size'];
		}
	}

	/* h6 Typography*/
	if ( isset( $ciyashop_options['h6-typography'] ) && ! empty( $ciyashop_options['h6-typography'] ) && ! empty( $ciyashop_h6_typo['family'] ) ) {

		/* h6 Typography Font family, font weight, letter spacing */
		if ( isset( $ciyashop_options['h6-typography']['font-backup'] ) && ! empty( $ciyashop_options['h6-typography']['font-backup'] ) ) {
			$dynamic_css[ $ciyashop_h6_typo['family'] ]['font-family'] = '"' . $ciyashop_options['h6-typography']['font-family'] . '", ' . $ciyashop_options['h6-typography']['font-backup'];
		} else {
			$dynamic_css[ $ciyashop_h6_typo['family'] ]['font-family'] = $ciyashop_options['h6-typography']['font-family'];
		}

		if ( isset( $ciyashop_options['h6-typography']['font-style'] ) && ! empty( $ciyashop_options['h6-typography']['font-style'] ) ) {
			$dynamic_css[ $ciyashop_h6_typo['family'] ]['font-style'] = $ciyashop_options['h6-typography']['font-style'];
		}

		if ( isset( $ciyashop_options['h6-typography']['font-weight'] ) && ! empty( $ciyashop_options['h6-typography']['font-weight'] ) ) {
			$dynamic_css[ $ciyashop_h6_typo['family'] ]['font-weight'] = $ciyashop_options['h6-typography']['font-weight'];
		}
		if ( isset( $ciyashop_options['h6-typography']['letter-spacing'] ) && ! empty( $ciyashop_options['h6-typography']['letter-spacing'] ) ) {
			$dynamic_css[ $ciyashop_h6_typo['family'] ]['letter-spacing'] = $ciyashop_options['h6-typography']['letter-spacing'];
		}
		/* h6 Typography Line Height */
		if ( isset( $ciyashop_options['h6-typography']['line-height'] ) && ! empty( $ciyashop_options['h6-typography']['line-height'] ) ) {
			$dynamic_css[ $ciyashop_h6_typo['line-height'] ]['line-height'] = $ciyashop_options['h6-typography']['line-height'];
		}
		/* h6 Typography Font Size */
		if ( isset( $ciyashop_options['h6-typography']['font-size'] ) && ! empty( $ciyashop_options['h6-typography']['font-size'] ) ) {
			$dynamic_css[ $ciyashop_h6_typo['font-size'] ]['font-size'] = $ciyashop_options['h6-typography']['font-size'];
		}
	}
}
/**
 * Ciyashop footer css
 *
 * @param string $ciyashop_options .
 */
function ciyashop_footer_css( $ciyashop_options ) {
	global $dynamic_css;

	$footer_bg_image                  = '';
	$footer_bg_color                  = '';
	$footer_bg_image_repeat           = '';
	$footer_bg_attachment             = '';
	$footer_bg_image_size             = '';
	$footer_bg_image_position         = '';
	$footer_opacity_custom_background = '';
	$footer_heading_color             = '';
	$footer_text_color                = '';
	$footer_link_color                = '';
	$footer_link_hover_color          = '';
	$copyright_back_color             = '';
	$copyright_text_color             = '';
	$copyright_link_color             = '';

	if ( function_exists( 'ciyashop_footer_type' ) && 'footer_builder' === ciyashop_footer_type() ) {
		$ciyashop_get_custom_footer_data = ciyashop_get_custom_footer_data();
			
		if ( isset( $ciyashop_get_custom_footer_data['common_settings'] ) && $ciyashop_get_custom_footer_data['common_settings'] ) {
			$footer_data = $ciyashop_get_custom_footer_data['common_settings'];
			
			if ( isset( $footer_data['footer_background_image'] ) && $footer_data['footer_background_image'] ) {
				if ( isset( $footer_data['footer_background_image']['bg_src'] ) && $footer_data['footer_background_image']['bg_src'] ) {
					$footer_bg_image = $footer_data['footer_background_image']['bg_src'];
				}
				
				if ( isset( $footer_data['footer_background_image']['bg_color'] ) && $footer_data['footer_background_image']['bg_color'] ) {
					$footer_bg_color = $footer_data['footer_background_image']['bg_color'];
				}
				
				if ( isset( $footer_data['footer_background_image']['bg_attachment'] ) && $footer_data['footer_background_image']['bg_attachment'] ) {
					$footer_bg_attachment = $footer_data['footer_background_image']['bg_attachment'];
				}

				if ( isset( $footer_data['footer_background_image']['bg_repeat'] ) && $footer_data['footer_background_image']['bg_repeat'] ) {
					$footer_bg_image_repeat = $footer_data['footer_background_image']['bg_repeat'];
				}

				if ( isset( $footer_data['footer_background_image']['bg_size'] ) && $footer_data['footer_background_image']['bg_size'] ) {
					$footer_bg_image_size = $footer_data['footer_background_image']['bg_size'];
				}

				if ( isset( $footer_data['footer_background_image']['bg_position'] ) && $footer_data['footer_background_image']['bg_position'] ) {
					$footer_bg_image_position = $footer_data['footer_background_image']['bg_position'];
				}
				
				if ( isset( $footer_data['footer_background_overlay'] ) && 'custom' === $footer_data['footer_background_opacity'] && ! empty( $footer_data['footer_background_overlay'] ) ) {
					$footer_opacity_custom_background = $footer_data['footer_background_overlay'];
				}
			}
			
			if ( isset( $footer_data['footer_heading_color'] ) && $footer_data['footer_heading_color'] ) {
				$footer_heading_color = $footer_data['footer_heading_color'];
			}
			
			if ( isset( $footer_data['footer_text_color'] ) && $footer_data['footer_text_color'] ) {
				$footer_text_color = $footer_data['footer_text_color'];
			}
			
			if ( isset( $footer_data['footer_link_color'] ) && $footer_data['footer_link_color'] ) {
				$footer_link_color = $footer_data['footer_link_color'];
			}
			
			if ( isset( $footer_data['footer_link_hover_color'] ) && $footer_data['footer_link_hover_color'] ) {
				$footer_link_hover_color = $footer_data['footer_link_hover_color'];
			}

			if ( isset( $footer_data['enable_copyright_footer'] ) && 'yes' === $footer_data['enable_copyright_footer'] ) {
				if ( isset( $footer_data['copyright_back_color'] ) && $footer_data['copyright_back_color'] ) {
					$copyright_back_color = $footer_data['copyright_back_color'];
				}
				
				if ( isset( $footer_data['copyright_text_color'] ) && $footer_data['copyright_text_color'] ) {
					$copyright_text_color = $footer_data['copyright_text_color'];
				}
				
				if ( isset( $footer_data['copyright_link_color'] ) && $footer_data['copyright_link_color'] ) {
					$copyright_link_color = $footer_data['copyright_link_color'];
				}
			}
		}
	} else {

		if ( isset( $ciyashop_options['footer_background_type'] ) && 'image' === $ciyashop_options['footer_background_type'] && ! empty( $ciyashop_options['footer_background_image'] ) ) {

			$footer_bg_image = $ciyashop_options['footer_background_image']['background-image'];

			if ( isset( $ciyashop_options['footer_background_image']['background-repeat'] ) ) {
				$footer_bg_image_repeat = $ciyashop_options['footer_background_image']['background-repeat'];
			}
			if ( isset( $ciyashop_options['footer_background_image']['background-attachment'] ) ) {
				$footer_bg_attachment = $ciyashop_options['footer_background_image']['background-attachment'];
			}
			if ( isset( $ciyashop_options['footer_background_image']['background-size'] ) ) {
				$footer_bg_image_size = $ciyashop_options['footer_background_image']['background-size'];
			}
			if ( isset( $ciyashop_options['footer_background_image']['background-position'] ) ) {
				$footer_bg_image_position = $ciyashop_options['footer_background_image']['background-position'];
			}

			if ( isset( $ciyashop_options['footer_background_overlay'] ) && 'custom' === $ciyashop_options['footer_background_opacity'] && ! empty( $ciyashop_options['footer_background_overlay'] ) ) {
				if ( isset( $ciyashop_options['footer_background_overlay']['rgba'] ) ) {
					$footer_opacity_custom_background = $ciyashop_options['footer_background_overlay']['rgba'];
				} else {
					$footer_opacity_custom_background = ciyashop_hex2rgba( $ciyashop_options['footer_background_overlay']['color'], $ciyashop_options['footer_background_overlay']['alpha'] );
				}
			}
		}
		
		
		if ( isset( $ciyashop_options['footer_background_type'] ) && 'color' === $ciyashop_options['footer_background_type'] ) {
			$footer_bg_color = $ciyashop_options['footer_background_color'];
		}

		/*Footer Heading Color */
		if ( isset( $ciyashop_options['footer_heading_color'] ) && ! empty( $ciyashop_options['footer_heading_color'] ) ) {
			$footer_heading_color = $ciyashop_options['footer_heading_color'];

		}
		/*Footer Text Color */
		if ( isset( $ciyashop_options['footer_text_color'] ) && ! empty( $ciyashop_options['footer_text_color'] ) ) {
			$footer_text_color = $ciyashop_options['footer_text_color'];
		}

		/*Footer Link Color */
		if ( isset( $ciyashop_options['footer_link_color'] ) && ! empty( $ciyashop_options['footer_link_color'] ) ) {
			$footer_link_color = $ciyashop_options['footer_link_color'];
		}
		
		/* Copywrite footer backgroud color*/
		if ( isset( $ciyashop_options['copyright_back_color'] ) && ! empty( $ciyashop_options['copyright_back_color'] ) ) {
			if ( isset( $ciyashop_options['copyright_back_color']['rgba'] ) ) {
				$copyright_back_color = $ciyashop_options['copyright_back_color']['rgba'];
			} else {
				$copyright_back_color = ciyashop_hex2rgba( $ciyashop_options['copyright_back_color']['color'], $ciyashop_options['copyright_back_color']['alpha'] );
			}
		}

		/* Footer CopyWrite Text Color */
		if ( isset( $ciyashop_options['copyright_text_color'] ) && ! empty( $ciyashop_options['copyright_text_color'] ) ) {
			$copyright_text_color = $ciyashop_options['copyright_text_color'];
		}

		/* Footer CopyWrite Link Color */
		if ( isset( $ciyashop_options['copyright_link_color'] ) && ! empty( $ciyashop_options['copyright_link_color'] ) ) {
			$copyright_link_color = $ciyashop_options['copyright_link_color'];
		}
	}
	
	if ( $footer_bg_image ) {
		$dynamic_css['footer.site-footer']['background-image'] = 'url(' . $footer_bg_image . ')';
		
		if ( $footer_bg_image_repeat ) {
			$dynamic_css['footer.site-footer']['background-repeat'] = $footer_bg_image_repeat;
		}
		
		if ( $footer_bg_attachment ) {
			$dynamic_css['footer.site-footer']['background-attachment'] = $footer_bg_attachment;
		}
		
		if ( $footer_bg_image_size ) {
			$dynamic_css['footer.site-footer']['background-size'] = $footer_bg_image_size;
		}
		
		if ( $footer_bg_image_position ) {
			$dynamic_css['footer.site-footer']['background-position'] = $footer_bg_image_position;
		}
	}
	
	if ( $footer_opacity_custom_background ) {
		$dynamic_css['.site-footer .footer-wrapper.footer_opacity_custom']['background-color'] = $footer_opacity_custom_background;
	}

	if ( $footer_bg_color ) {
		$dynamic_css['footer.site-footer']['background-color'] = $footer_bg_color;
	}
	
	if ( $footer_heading_color ) {
		$dynamic_css['.site-footer .widget .widget-title']['color'] = $footer_heading_color;
	}

	/*Footer Text Color */
	if ( $footer_text_color ) {
		$dynamic_css['.site-footer h1,
		.site-footer h2,
		.site-footer h3,
		.site-footer h4,
		.site-footer h5,
		.site-footer h6,
		.site-footer,
		.site-footer a:hover,
		.site-footer .widget ul li a,
		.site-footer .widget_archive ul li:before, 
		.site-footer .widget_meta ul li:before,
		.site-footer .widget select,
		.site-footer table th,
		.site-footer table caption,
		.site-footer input[type=text], 
		.site-footer input[type=email], 
		.site-footer input[type=search], 
		.site-footer input[type=password], 
		.site-footer textarea,
		.site-footer .widget_rss ul li,
		.site-footer .widget_search .search-button,
		.site-footer .widget_tag_cloud .tagcloud a.tag-cloud-link,
		.site-footer .widget_pgs_contact_widget ul li,
		.site-footer .widget_pgs_bestseller_widget .item-detail del .amount,
		.site-footer .widget_pgs_featured_products_widget .item-detail del .amount,
		.site-footer .widget_recent_entries .recent-post .recent-post-info a,
		.site-footer .woocommerce .widget_shopping_cart .total strong, 
		.site-footer .woocommerce.widget_shopping_cart .total strong,
		.site-footer .widget-woocommerce-currency-rates ul.woocs_currency_rates li strong,
		.site-footer .woocommerce-currency-switcher-form a.dd-selected:not([href]):not([tabindex]),
		.site-footer .widget_product_tag_cloud .tagcloud a,
		.site-footer .select2-container--default .select2-selection--single .select2-selection__rendered,
		.site-footer .widget.widget_recent_comments ul li a,
		.site-footer .woocommerce ul.product_list_widget li a,
		.site-footer blockquote,
		.pgs-opening-hours ul li']['color'] = $footer_text_color;
	}

	/*Footer Link Color */
	if ( $footer_link_color ) {
		$dynamic_css['.site-footer a,
		.site-footer .widget ul li > a:hover,
		.site-footer .widget_archive ul li,
		.site-footer .widget_categories ul li .widget_categories-post-count,
		.site-footer .widget_search .search-button:hover,
		.site-footer .widget_pgs_contact_widget ul li i,
		.site-footer .widget_pgs_bestseller_widget .item-detail .amount,
		.site-footer .widget_pgs_featured_products_widget .item-detail .amount,
		.site-footer .widget.widget_recent_comments ul li a:hover,
		.site-footer .widget_recent_entries .recent-post .recent-post-info .post-date i,
		.site-footer .widget_recent_entries .recent-post .recent-post-info a:hover,
		.site-footer .woocommerce .widget_shopping_cart .total .amount, 
		.site-footer .woocommerce.widget_shopping_cart .total .amount,
		.site-footer .widget-woocommerce-currency-rates ul.woocs_currency_rates li,
		.site-footer .WOOCS_SELECTOR .dd-desc,
		.site-footer .widget_product_categories ul li .count,
		.site-footer .widget_products ins,
		.woocommerce .site-footer .widget_top_rated_products ul.product_list_widget li ins,
		.widget_top_rated_products ins,
		.site-footer .woocommerce ul.cart_list li a:hover, 
		.site-footer .woocommerce ul.product_list_widget li a:hover,
		.pgs-opening-hours ul li i']['color'] = $footer_link_color;
	}
	
	if ( $copyright_back_color ) {
		$dynamic_css['.site-footer .site-info']['background'] = $copyright_back_color;
	}
	
	if ( $copyright_text_color ) {
		$dynamic_css['.site-footer .site-info, .site-footer .footer-widget a']['color'] = $copyright_text_color;
	}
	
	if ( $copyright_link_color ) {
		$dynamic_css['.site-footer .footer-widget a:hover']['color'] = $copyright_link_color;
	}
}
/**
 * Ciyashop dynamic css
 */
function ciyashop_dynamic_css_fix() {
	global $ciyashop_options;

	if ( ! class_exists( 'Redux' ) ) {
		return;
	}

	$header_schema                  = '';
	$ciyashop_dynamic_css_generated = get_option( 'ciyashop_dynamic_css_generated', false );

	if ( $ciyashop_dynamic_css_generated ) {
		return;
	}

	// Generate the color customizer CSS.
	$primary_color   = esc_html( $ciyashop_options['primary_color'] );
	$secondary_color = esc_html( $ciyashop_options['secondary_color'] );
	$tertiary_color  = esc_html( $ciyashop_options['tertiary_color'] );

	if ( isset( $ciyashop_options['header_type_select'] ) && 'predefined' !== $ciyashop_options['header_type_select'] ) {
		$header_schema = ciyashop_get_custom_header_schema();
	}

	$color_customize = ciyashop_get_color_scheme_colors( $primary_color, $secondary_color, $tertiary_color, $header_schema );
	ciyashop_generate_color_customize_css( $color_customize );

	$current_time = current_time( 'mysql' );
	update_option( 'ciyashop_dynamic_css_generated', $current_time );
}

/**
 * Ciyashop temp css
 *
 * @param string $ciyashop_options .
 */
function ciyashop_temp_css( $ciyashop_options ) {
	global $dynamic_css;

	$post_id = ciyashop_dynamic_css_page_id();
}
/**
 * Ciyashop menu font css
 *
 * @param array  $dimension_data .
 * @param string $units .
 */
function ciyashop_parse_redux_dimension( $dimension_data = array(), $units = 'px' ) {

	// bail early if no data found.
	if ( empty( $dimension_data ) ) {
		return false;
	}

	$new_dimenstion = array();

	if ( ! isset( $dimension_data['units'] ) || empty( $dimension_data['units'] ) ) {
		$dimension_data['units'] = $units;
	}

	if ( isset( $dimension_data['height'] ) && ! empty( $dimension_data['height'] ) ) {

		if ( substr( $dimension_data['height'], -2 ) != $dimension_data['units'] ) { // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
			$new_dimenstion['height'] = $dimension_data['height'] . $dimension_data['units'];
		} else {
			$new_dimenstion['height'] = $dimension_data['height'];
		}
	}

	if ( isset( $dimension_data['width'] ) && ! empty( $dimension_data['width'] ) ) {

		if ( substr( $dimension_data['width'], -2 ) != $dimension_data['units'] ) { // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
			$new_dimenstion['width'] = $dimension_data['width'] . $dimension_data['units'];
		} else {
			$new_dimenstion['width'] = $dimension_data['width'];
		}
	}

	if ( empty( $new_dimenstion ) ) {
		return false;
	}

	return $new_dimenstion;
}
