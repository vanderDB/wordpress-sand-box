<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Enqueue scripts and styles.
 * /

/**
 * Register Google fonts for CiyaShop.
 */
function ciyashop_google_fonts_url() {

	$fonts_url = '';
	$fonts     = array();
	$font_args = array();
	$base_url  = '//fonts.googleapis.com/css';

	/* translators: If there are characters in your language that are not supported by Montserrat, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Montserrat font: on or off', 'ciyashop' ) ) {
		$fonts['family']['Montserrat'] = 'Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i,800,900';
	}

	/* translators: If there are characters in your language that are not supported by Lato, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Lato font: on or off', 'ciyashop' ) ) {
		$fonts['family']['Lato'] = 'Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic';
	}

	$fonts['subsets'] = 'devanagari,latin-ext';

	/**
	 * Filters custom Google Fonts, loaded in the theme.
	 *
	 * @param array    $fonts      Array of fonts parameters.
	 *
	 * @visible true
	 */
	$fonts = apply_filters( 'ciyashop_google_fonts', $fonts );

	/* Prepapre URL if font family defined. */
	if ( ! empty( $fonts['family'] ) ) {

		/* format family to string */
		if ( is_array( $fonts['family'] ) ) {
			$fonts['family'] = implode( '|', $fonts['family'] );
		}

		$font_args['family'] = rawurlencode( trim( $fonts['family'] ) );

		if ( ! empty( $fonts['subsets'] ) ) {

			/* format subsets to string */
			if ( is_array( $fonts['subsets'] ) ) {
				$fonts['subsets'] = implode( ',', $fonts['subsets'] );
			}

			$font_args['subsets'] = rawurlencode( trim( $fonts['subsets'] ) );
		}

		$fonts_url = add_query_arg( $font_args, $base_url );
	}

	return esc_url_raw( $fonts_url );
}

/**
 * Enqueue scripts and styles.
 * */
function ciyashop_scripts() {

	global $ciyashop_options;

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	$upload    = wp_upload_dir();
	$style_url = $upload['baseurl'] . '/ciyashop';
	$header_id = isset( $ciyashop_options['custom_headers'] ) ? $ciyashop_options['custom_headers'] : '';

	$color_customize_css     = $style_url . '/color_customize.css';
	$color_customize_version = get_option( 'color_customize_version', THEME_VERSION );

	$select2_css_src = get_parent_theme_file_uri( '/css/select2.min.css' );
	$select2_js_src  = get_parent_theme_file_uri( '/js/select2/select2.min.js' );

	if ( wp_style_is( 'font-awesome', 'registered' ) ) {
		wp_deregister_style( 'font-awesome' );
	}
	wp_enqueue_style( 'font-awesome', get_parent_theme_file_uri( '/fonts/font-awesome/css/all.min.css' ), array(), '5.12.0' );
	wp_enqueue_style( 'font-awesome-shims', get_parent_theme_file_uri( '/fonts/font-awesome/css/v4-shims.min.css' ), array(), '5.12.0' );

	// Extra Google Fonts.
	if ( isset( $ciyashop_options['cs_google_fonts_repeater'] ) && ! empty( $ciyashop_options['cs_google_fonts_repeater'] ) ) {
		if ( isset( $ciyashop_options['cs_google_fonts_repeater']['cs_google_fonts'] ) && ! empty( $ciyashop_options['cs_google_fonts_repeater']['cs_google_fonts'] ) ) {
			$google_fonts     = $ciyashop_options['cs_google_fonts_repeater']['cs_google_fonts'];
			$google_fonts_url = ciyashop_create_google_fonts_url( $google_fonts );
			wp_enqueue_style( 'cs-google-fonts', $google_fonts_url, array(), '3.5.2' );
		}
	}

	// Stylesheets.
	if ( ! class_exists( 'Redux' ) ) {
		wp_enqueue_style( 'ciyashop-google-fonts', ciyashop_google_fonts_url(), array(), '3.5.2' );// Google Fonts.
	}

	if ( is_rtl() ) {
		wp_enqueue_style( 'bootstrap', get_parent_theme_file_uri( '/css/bootstrap-rtl.min.css' ), array(), '4.0.0' );
	} else {
		wp_enqueue_style( 'bootstrap', get_parent_theme_file_uri( '/css/bootstrap.min.css' ), array(), '4.1.1' );
	}

	wp_enqueue_style( 'select2', $select2_css_src, array(), '3.5.2' );
	wp_enqueue_style( 'jquery-ui', get_parent_theme_file_uri( '/css/jquery-ui/jquery-ui.min.css' ), array(), '1.11.4' );
	wp_enqueue_style( 'owl-carousel', get_parent_theme_file_uri( '/css/owl-carousel.min.css' ), array(), '2.2.0' );
	wp_enqueue_style( 'magnific-popup', get_parent_theme_file_uri( '/css/magnific-popup.min.css' ), array(), '3.5.2' );
	wp_enqueue_style( 'slick', get_parent_theme_file_uri( '/css/slick-slider/slick.min.css' ), array(), '1.0.10' );
	wp_enqueue_style( 'slick-theme', get_parent_theme_file_uri( '/css/slick-slider/slick-theme.min.css' ), array(), '1.0.10' );
	wp_enqueue_style( 'slicknav', get_parent_theme_file_uri( '/css/slicknav.min.css' ), array(), '1.0.10' );

	if ( $header_id ) {
		wp_enqueue_style( 'custom-header-style', get_parent_theme_file_uri( '/css/header-style.min.css' ), array(), THEME_VERSION );
	}

	if ( class_exists( 'WPBakeryVisualComposerAbstract' ) ) {
		wp_enqueue_style( 'ciyashop-shortcodes-style', get_parent_theme_file_uri( '/css/shortcodes' . $suffix . '.css' ), array(), THEME_VERSION );
	}

	if ( did_action( 'elementor/loaded' ) ) {
		wp_enqueue_style( 'ciyashop-elementor-widget-style', get_parent_theme_file_uri( '/css/elementor-widget' . $suffix . '.css' ), array(), THEME_VERSION );
	}
	wp_enqueue_style( 'ciyashop-style', get_parent_theme_file_uri( '/css/style' . $suffix . '.css' ), array(), THEME_VERSION );
	
	if ( class_exists( 'WPBakeryVisualComposerAbstract' ) ) {
		wp_enqueue_style( 'ciyashop-responsive-shortcode', get_parent_theme_file_uri( '/css/responsive-shortcode' . $suffix . '.css' ), array( 'ciyashop-style' ), THEME_VERSION );
	}

	if ( did_action( 'elementor/loaded' ) ) {
		wp_enqueue_style( 'ciyashop-responsive-elementor-widget', get_parent_theme_file_uri( '/css/responsive-elementor-widget' . $suffix . '.css' ), array( 'ciyashop-style' ), THEME_VERSION );
	}
	$color_customize_css = apply_filters( 'ciyashop_color_customize_css', $color_customize_css );
	wp_enqueue_style( 'ciyashop-responsive', get_parent_theme_file_uri( '/css/responsive' . $suffix . '.css' ), array( 'ciyashop-style' ), THEME_VERSION );
	wp_enqueue_style( 'ciyashop-color-customize', $color_customize_css, array(), $color_customize_version );// Color Customizer style.

	wp_enqueue_script( 'popper', get_parent_theme_file_uri( '/js/popper.min.js' ), array(), '1.14.3', true );

	// Scripts.
	if ( is_rtl() ) {
		wp_enqueue_script( 'bootstrap', get_parent_theme_file_uri( '/js/bootstrap/bootstrap-rtl.min.js' ), array( 'jquery' ), '4.0.0', true );
	} else {
		wp_enqueue_script( 'bootstrap', get_parent_theme_file_uri( '/js/bootstrap/bootstrap.min.js' ), array( 'jquery' ), '4.1.1', true );
	}

	if ( isset( $ciyashop_options['blog_layout'] ) && 'masonry' === (string) $ciyashop_options['blog_layout'] && ( is_home() || is_author() || is_category() || is_archive() || is_tag() || is_tax() || is_date() || is_day() || is_month() || is_year() ) ) {
		wp_enqueue_script( 'masonry' );
	}

	wp_enqueue_script( 'owl-carousel', get_parent_theme_file_uri( '/js/owl-carousel.min.js' ), array(), '2.3.4', true );
	wp_enqueue_script( 'select2', $select2_js_src, array(), '4.0.6', true );
	wp_enqueue_script( 'jquery.countdown', get_parent_theme_file_uri( '/js/countdown.min.js' ), array(), '2.2.0', true );
	wp_enqueue_script( 'nanoscroller-js', get_parent_theme_file_uri( '/js/jquery.nanoscroller.min.js' ), array(), '0.8.7', true );
	wp_enqueue_script( 'slick-min-js', get_parent_theme_file_uri( '/js/slick.min.js' ), array(), '1.0.10', true );
	wp_enqueue_script( 'shuffle-js', get_parent_theme_file_uri( '/js/shuffle.min.js' ), array(), '3.5.2', true );
	wp_enqueue_script( 'slicknav', get_parent_theme_file_uri( '/js/slicknav.min.js' ), array(), '1.0.10', true );
	wp_enqueue_script( 'stickyjs', get_parent_theme_file_uri( '/js/sticky.min.js' ), array( 'jquery' ), '1.0.4', true );
	wp_enqueue_script( 'magnific-popup', get_parent_theme_file_uri( '/js/magnific-popup.min.js' ), array( 'jquery' ), '1.1.0', true );
	wp_enqueue_script( 'js-cookie', get_parent_theme_file_uri( '/js/cookie.min.js' ), array( 'jquery' ), '2.1.4', true );
	wp_enqueue_script( 'jquery-ui-autocomplete' );
	wp_register_script( 'ciyashop-jquery-lazy', get_parent_theme_file_uri( '/js/jquery.lazy/jquery.lazy.min.js' ), array( 'jquery' ), '1.7.9', true );
	wp_register_script( 'ciyashop-jquery-lazy-plugins', get_parent_theme_file_uri( '/js/jquery.lazy/jquery.lazy.plugins.min.js' ), array( 'jquery', 'ciyashop-jquery-lazy' ), '1.4', true );
	
	if ( isset( $ciyashop_options['show_compare'] ) && $ciyashop_options['show_compare'] ) {
		wp_enqueue_script( 'jquery-ui-sortable' );
	}

	if ( isset( $ciyashop_options['enable_lazyload'] ) && $ciyashop_options['enable_lazyload'] ) {
		wp_enqueue_script( 'ciyashop-jquery-lazy' );
		wp_enqueue_script( 'ciyashop-jquery-lazy-plugins' );
	}

	wp_register_script( 'ciyashop-main_js', get_parent_theme_file_uri( '/js/main' . $suffix . '.js' ), array( 'jquery' ), THEME_VERSION, true );

	if ( class_exists( 'WooCommerce' ) && is_product() ) {
		wp_enqueue_script( 'ciyashop-single-product', get_parent_theme_file_uri( '/js/wc/ciyashop-single-product' . $suffix . '.js' ), array( 'jquery', 'ciyashop-main_js', 'zoom' ), '3.5.2', true );
		wp_enqueue_script( 'ciyashop-add-to-cart-variation', get_parent_theme_file_uri( '/js/wc/ciyashop-add-to-cart-variation' . $suffix . '.js' ), array( 'jquery', 'ciyashop-main_js' ), '3.5.2', true );
	}

	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_script( 'wc-add-to-cart-variation' );
	}

	// product variation on shop page.
	if ( class_exists( 'WooCommerce' ) && isset( $ciyashop_options['cs_display_variation_on_list'] ) && $ciyashop_options['cs_display_variation_on_list'] ) {
		wp_enqueue_script( 'wc-add-to-cart-variation', false, array(), '3.5.2', true );
	}

	// Localize the script with new data.
	$show_sticky_header = false;
	if ( wp_is_mobile() ) {
		if ( ciyashop_sticky_header() && ciyashop_mobile_sticky_header() ) {
			$show_sticky_header = true;
		}
	} else {
		if ( ciyashop_sticky_header() ) {
			$show_sticky_header = true;
		}
	}

	// Promo Pupup variables.
	$promopopup_main   = 0;
	$promopopup_mobile = 1;

	if ( isset( $ciyashop_options['promo_popup'] ) && 1 === (int) $ciyashop_options['promo_popup'] ) {
		$promopopup_main = 1;
	}

	if ( 1 === (int) $promopopup_main && ( isset( $ciyashop_options['promo_popup_hide_mobile'] ) && 0 === (int) $ciyashop_options['promo_popup_hide_mobile'] ) ) {
		$promopopup_mobile = 0;
	}

	$ciyashop_l10n = array(
		'ajax_url'                 => admin_url( 'admin-ajax.php' ),
		'ciyashop_nonce'           => wp_create_nonce( 'ciyashop_nonce' ),
		'pgs_compare'              => esc_html__( 'Compare', 'ciyashop' ),
		'pgs_wishlist'             => esc_html__( 'Wishlist', 'ciyashop' ),
		'add_to_wishlist'          => isset( $ciyashop_options['add_to_wishlist_text'] ) ? $ciyashop_options['add_to_wishlist_text'] : esc_html__( 'Add to Wishlist', 'ciyashop' ),
		'browse_wishlist'          => isset( $ciyashop_options['browse_wishlist_text'] ) ? $ciyashop_options['browse_wishlist_text'] : esc_html__( 'Browse Wishlist', 'ciyashop' ),
		'product_added_text'       => isset( $ciyashop_options['product_added_text'] ) ? $ciyashop_options['product_added_text'] : esc_html__( 'Product added!', 'ciyashop' ),
		'already_in_wishlist_text' => isset( $ciyashop_options['already_in_wishlist_text'] ) ? $ciyashop_options['already_in_wishlist_text'] : esc_html__( 'The product is already in the wishlist!', 'ciyashop' ),
		'main_promopopup'          => esc_js( $promopopup_main ),
		'promopopup_hide_mobile'   => esc_js( $promopopup_mobile ),
		'sticky_header'            => ciyashop_sticky_header() ? 1 : 0,
		'sticky_header_mobile'     => ciyashop_mobile_sticky_header() ? 1 : 0,
		'device_type'              => wp_is_mobile() ? 'mobile' : 'desktop',
		'show_sticky_header'       => $show_sticky_header ? 1 : 0,
		'home_url'                 => esc_url( get_home_url() ),
		'lang'                     => defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : '',
		'load_more'                => esc_html__( 'Load more...', 'ciyashop' ),
		'loading'                  => esc_html__( 'Loading...', 'ciyashop' ),
		'no_more_product_to_load'  => esc_html__( 'No more product to load', 'ciyashop' ),
		'cart_hash_key'            => apply_filters( 'woocommerce_cart_hash_key', 'wc_cart_hash_' . md5( get_current_blog_id() . '_' . get_site_url( get_current_blog_id(), '/' ) . get_template() ) ),
		'fragment_name'            => apply_filters( 'woocommerce_cart_fragment_name', 'wc_fragments_' . md5( get_current_blog_id() . '_' . get_site_url( get_current_blog_id(), '/' ) . get_template() ) ),
	);

	/**
	 * Localizes a CiyaShop main script with data for a JavaScript variable.
	 *
	 * @param array    $l10n      Array of data.
	 *
	 * @visible true
	 */
	$ciyashop_l10n = apply_filters( 'ciyashop_l10n', $ciyashop_l10n );

	wp_localize_script( 'ciyashop-main_js', 'ciyashop_l10n', $ciyashop_l10n );

	// Enqueued script with localized data.
	wp_enqueue_script( 'ciyashop-main_js' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Add custom CSS.

	$custom_css_all           = '';
	$custom_css               = ( isset( $ciyashop_options['custom_css'] ) ) ? trim( wp_strip_all_tags( $ciyashop_options['custom_css'] ) ) : '';
	$custom_css_desktop       = ( isset( $ciyashop_options['custom_css_desktop'] ) ) ? trim( wp_strip_all_tags( $ciyashop_options['custom_css_desktop'] ) ) : '';
	$custom_css_tab_landscape = ( isset( $ciyashop_options['custom_css_tab_landscape'] ) ) ? trim( wp_strip_all_tags( $ciyashop_options['custom_css_tab_landscape'] ) ) : '';
	$custom_css_tab_portrait  = ( isset( $ciyashop_options['custom_css_tab_portrait'] ) ) ? trim( wp_strip_all_tags( $ciyashop_options['custom_css_tab_portrait'] ) ) : '';
	$custom_css_mobile        = ( isset( $ciyashop_options['custom_css_mobile'] ) ) ? trim( wp_strip_all_tags( $ciyashop_options['custom_css_mobile'] ) ) : '';

	if ( $custom_css ) {
		$custom_css_all .= $custom_css;
	}

	// Desktop.
	if ( $custom_css_desktop ) {
		$custom_css_all .= '@media (min-width: 1200px) { ' . $custom_css_desktop . ' }';
	}

	// Tablet Landscape.
	if ( $custom_css_tab_landscape ) {
		$custom_css_all .= '@media all and (max-width:1199px) and (min-width:992px) { ' . $custom_css_tab_landscape . ' }';
	}

	// Tablet Portrait.
	if ( $custom_css_tab_portrait ) {
		$custom_css_all .= '@media all and (max-width:992px) and (min-width:768px) {' . $custom_css_tab_portrait . ' }';
	}

	// Mobile.
	if ( $custom_css_mobile ) {
		$custom_css_all .= '@media all and (max-width:767px) { ' . $custom_css_mobile . ' }';
	}

	if ( ! empty( $custom_css_all ) ) {
		wp_add_inline_style( 'ciyashop-color-customize', $custom_css_all );
	}

	// Add custom Javascript.
	if ( isset( $ciyashop_options['custom_js'] ) && ! empty( $ciyashop_options['custom_js'] ) ) {
		$custom_js = trim( wp_strip_all_tags( $ciyashop_options['custom_js'] ) );
		if ( ! empty( $custom_js ) ) {
			wp_add_inline_script( 'ciyashop-main_js', $custom_js );
		}
	}

}
add_action( 'wp_enqueue_scripts', 'ciyashop_scripts' );

/**
 * Enqueue admin scripts and styles.
 *
 * @param string $hook .
 */
function ciyashop_admin_enqueue_scripts( $hook ) {

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	// Javascript.
	wp_register_script( 'ciyashop_admin_js', get_parent_theme_file_uri( '/js/admin' . $suffix . '.js' ), array( 'jquery' ), THEME_VERSION, true );

	$menu_id             = ciyashop_get_selected_menu_id();
	$cs_mega_menu_enable = get_post_meta( $menu_id, 'cs_megamenu_enable', true );
	$cs_mega_menu        = false;

	if ( ( ! empty( $cs_mega_menu_enable ) && 'true' === (string) $cs_mega_menu_enable ) && ! class_exists( 'Mega_Menu' ) ) {
		$cs_mega_menu = true;
	}

	// Localize the script with new data.
	$translation_array = array(
		'theme_options_url'      => admin_url( 'themes.php?page=ciyashop-options' ),
		'cs_mega_menu_enable'    => $cs_mega_menu,
		'cs_mega_menu_nonce'     => wp_create_nonce( 'cs_mega_menu_nonce' ),
		'menu_settings'          => esc_html__( 'Menu Settings', 'ciyashop' ),
		'cs_menu_save_nonce'     => wp_create_nonce( 'cs_menu_save_nonce' ),
		'cs_menu_get_item_nonce' => wp_create_nonce( 'cs_menu_get_item_nonce' ),
		'cid_msg'                => esc_html__( 'Please enter Client ID to proceed.', 'ciyashop' ),
		'ciyashop_admin_nonce'   => wp_create_nonce( 'ciyashop_admin_nonce' ),
	);

	wp_localize_script( 'ciyashop_admin_js', 'ciyashop_admin', $translation_array );
	$redux_search_options = redux_search_options();
	if ( $redux_search_options ) {
		wp_localize_script( 'ciyashop_admin_js', 'ciyashop_search_config', $redux_search_options );
	}
	wp_localize_script( 'ciyashop_admin_js', 'ciyashop_icons', ciyashop_iconpicker_icons() );
	wp_enqueue_script( 'ciyashop_admin_js' );

	// CSS.
	wp_register_style( 'jquery-ui', get_parent_theme_file_uri( '/css/jquery-ui/jquery-ui.min.css' ), array(), '1.11.4' );
	wp_register_style( 'font-awesome-shims', get_parent_theme_file_uri( '/fonts/font-awesome/css/v4-shims.min.css' ), array(), '5.12.0' );
	wp_register_style( 'font-awesome', get_parent_theme_file_uri( '/fonts/font-awesome/css/all.min.css' ), array(), '5.12.0' );
	wp_register_style( 'cs-bootstrap', get_parent_theme_file_uri( '/css/admin/cs-bootstrap' . $suffix . '.css' ), array(), '4.1.1' );
	wp_register_style( 'ciyashop-admin-style', get_parent_theme_file_uri( '/css/admin/admin_style' . $suffix . '.css' ), array( 'jquery-ui', 'cs-bootstrap', 'font-awesome-shims', 'font-awesome', 'cs-iconpicker', 'cs-grey-iconpicker' ), THEME_VERSION );

	wp_enqueue_style( 'cs-iconpicker', get_parent_theme_file_uri( '/css/admin/fonticonpicker/jquery.fonticonpicker' . $suffix . '.css' ), array(), '3.5.2' );
	wp_enqueue_style( 'cs-grey-iconpicker', get_parent_theme_file_uri( '/css/admin/fonticonpicker/jquery.fonticonpicker.grey' . $suffix . '.css' ), array(), '3.5.2' );
	wp_enqueue_script( 'ciyashop_icon_picker', get_parent_theme_file_uri( '/js/jquery.fonticonpicker.min.js' ), array( 'jquery' ), '3.5.2', true );

	if ( 'toplevel_page_header-builder' === $hook || 'header-builder_page_header-layout' === $hook ) {
		wp_enqueue_style( 'select2', get_parent_theme_file_uri( '/css/select2.min.css' ), array(), '3.5.2' );
		wp_enqueue_script( 'select2', get_parent_theme_file_uri( '/js/select2/select2.min.js' ), array(), '3.5.2', true );
	}

	// load ciyashop-admin-style only when it's not on Revolution Slider page.
	if ( ! isset( $_GET['page'] ) || 'revslider' !== $_GET['page'] ) {
		wp_enqueue_style( 'ciyashop-admin-style' );
	}

	if ( class_exists( 'WooCommerce' ) ) {
		$custom_css = '.redux-group-tab-link-li .el-shopping-cart:before {
			font-family: WooCommerce !important;
			content: "\e03d";
		}';
		wp_add_inline_style( 'ciyashop-admin-style', $custom_css );
	}
}
add_action( 'admin_enqueue_scripts', 'ciyashop_admin_enqueue_scripts' );
