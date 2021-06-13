<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package CiyaShop
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function ciyashop_body_classes( $classes ) {

	global $ciyashop_options;

	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	if ( function_exists( 'WC' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
		if ( ( isset( $ciyashop_options['off_canvas_shop_sidebar'] ) && $ciyashop_options['off_canvas_shop_sidebar'] ) || ( wp_is_mobile() && isset( $ciyashop_options['off_canvas_mobile_shop_sidebar'] ) && $ciyashop_options['off_canvas_mobile_shop_sidebar'] ) ) {
			$classes[] = 'shop-off_canvas_sidebar';
		}
	}

	if ( function_exists( 'WC' ) ) {
		$classes[] = 'woocommerce-active';

		if ( ( isset( $ciyashop_options['woocommerce_checkout_layout'] ) && $ciyashop_options['woocommerce_checkout_layout'] ) && ( function_exists( 'is_checkout' ) && is_checkout() ) ) {
			$classes[] = 'woocommerce-checkout-layout-' . $ciyashop_options['woocommerce_checkout_layout'];
		}
		if ( function_exists( 'ciyashop_footer_type' ) && 'footer_builder' === ciyashop_footer_type() ) {
			$footer_data = ciyashop_get_custom_footer_data();
			
			if ( isset( $footer_data['common_settings']['sticky_footer'] ) && 'enable' === $footer_data['common_settings']['sticky_footer'] ) {
				$classes[] = 'sticky-footer-enable';
			}
		} else {
			if ( isset( $ciyashop_options['sticky_footer'] ) && 'enable' === $ciyashop_options['sticky_footer'] ) {
				$classes[] = 'sticky-footer-enable';
			}
		}

		if ( class_exists( 'YITH_WCWL' ) ) {

			$wishlist_page_id = yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) );

			if ( ! ciyashop_is_woocommerce_page() && ! is_page( $wishlist_page_id ) ) {
				if ( in_array( 'woocommerce-page', $classes, true ) ) {
					$key = array_search( 'woocommerce-page', $classes, true );
					if ( false !== $key ) { // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found
						unset( $classes[ $key ] );
					}
				}
			}
		} else {
			$wishlist_page_id = ( isset( $ciyashop_options['cs_wishlist_page'] ) && ! empty( $ciyashop_options['cs_wishlist_page'] ) ) ? $ciyashop_options['cs_wishlist_page'] : '';

			if ( class_exists( 'SitePress' ) ) {
				$wishlist_page_id = apply_filters( 'wpml_object_id', $wishlist_page_id, 'page' );
			}

			if ( $wishlist_page_id && is_page( $wishlist_page_id ) ) {
				$classes[] = 'woocommerce';
				$classes[] = 'woocommerce-page';
				$classes[] = 'cs-wishlist-page';
			}
		}
	}

	// Ajax add to cart.
	$ajax_add_to_cart = isset( $ciyashop_options['ajax_add_to_cart'] ) ? $ciyashop_options['ajax_add_to_cart'] : true;
	if ( $ajax_add_to_cart ) {
		$classes[] = 'cs-ajax-add-to-cart';
	}

	// Dark theme layout.
	if ( isset( $ciyashop_options['dark_layout'] ) && $ciyashop_options['dark_layout'] ) {
		$classes[] = 'theme-dark';
	}
	
	// Dark theme layout.
	if ( isset( $ciyashop_options['disable_hover_effect_mobile'] ) && $ciyashop_options['disable_hover_effect_mobile'] ) {
		$classes[] = 'hover-effect-mobile-disabled';
	}

	if ( wp_is_mobile() ) {
		if ( isset( $ciyashop_options['woocommerce_mobile_sticky_footer'] ) && $ciyashop_options['woocommerce_mobile_sticky_footer'] ) {
			$classes[] = 'footer-device-active';
		} else {
			$classes[] = 'footer-device-inactive';
		}
		$classes[] = 'device-type-mobile';

	} else {
		$classes[] = 'device-type-desktop';
	}

	if ( function_exists( 'is_product' ) && is_product() ) {
		if ( isset( $ciyashop_options['single_sticky_add_to_cart'] ) && $ciyashop_options['single_sticky_add_to_cart'] && ( isset( $ciyashop_options['hide_price_for_guest_user'] ) && ! $ciyashop_options['hide_price_for_guest_user'] || is_user_logged_in() ) ) {
			$classes[] = 'cart_sticky-on';
		} else {
			$classes[] = 'cart_sticky-off';
		}
	}

	if ( isset( $ciyashop_options['scroll_to_cart'] ) && 1 === (int) $ciyashop_options['scroll_to_cart'] ) {
		$classes[] = 'scroll_to_cart-on';
	}
	
	if ( isset( $ciyashop_options['product_filter_with'] ) && 'ajax' === $ciyashop_options['product_filter_with'] ) {
		$classes[] = 'cs-filter-with-ajax';
	}

	$classes[] = 'ciyashop-site-layout-' . ciyashop_site_layout();

	return $classes;
}
add_filter( 'body_class', 'ciyashop_body_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function ciyashop_pingback_header() {
	if ( is_singular() && pings_open() ) {
		?>
		<link rel="pingback" href="<?php echo esc_url( bloginfo( 'pingback_url' ) ); ?>">
		<?php
	}
}
add_action( 'wp_head', 'ciyashop_pingback_header' );

add_action( 'init', 'ciyashop_mpc_check_class' );
/**
 * Ciyashop mpc check class
 */
function ciyashop_mpc_check_class() {
	if ( defined( 'MPC_MASSIVE_FULL' ) || ciyashop_check_plugin_active( 'mpc-massive/mpc-massive.php' ) ) {
		add_filter( 'admin_body_class', 'ciyashop_add_mpc_check_class' );
		add_filter( 'body_class', 'ciyashop_add_mpc_check_class' );
	}
}

/**
 * Add class to body in Admin
 *
 * @param string $classes .
 */
function ciyashop_add_mpc_check_class( $classes ) {

	if ( is_array( $classes ) ) {
		$classes[] = 'cs_mpc_active';
	} else {
		$classes = "$classes cs_mpc_active";
	}

	return $classes;
}
