<?php
/**
 * WC classes
 *
 * @package CiyaShop
 */

/**
 * Product content class
 *
 * @param array $classes .
 */
function ciyashop_product_content_class( $classes = array() ) {
	$sidebar_layout = ciyashop_product_page_sidebar();
	$sidebar_id     = 'sidebar-product';

	if ( ! empty( $classes ) && ! is_array( $classes ) ) {
		$classes = explode( ' ', $classes );
	}

	if ( is_active_sidebar( $sidebar_id ) && 'left' === (string) $sidebar_layout ) {
		$classes[] = 'col-xl-9 col-lg-8 order-xl-2 order-lg-2';
	} elseif ( is_active_sidebar( $sidebar_id ) && 'right' === (string) $sidebar_layout ) {
		$classes[] = 'col-xl-9 col-lg-8';
	} else {
		$classes[] = 'col-xl-12';
	}

	$classes = ciyashop_class_builder( $classes );
	return $classes;
}
/**
 * Product sidebar class
 *
 * @param array $classes .
 */
function ciyashop_product_sidebar_class( $classes = array() ) {
	$sidebar_layout = ciyashop_product_page_sidebar();
	$sidebar_id     = 'sidebar-product';

	if ( ! empty( $classes ) && ! is_array( $classes ) ) {
		$classes = explode( ' ', $classes );
	}

	if ( is_active_sidebar( $sidebar_id ) && 'left' === (string) $sidebar_layout ) {
		$classes[] = 'sidebar col-xl-3 col-lg-4 order-xl-1 order-lg-1';
	} elseif ( is_active_sidebar( $sidebar_id ) && 'right' === (string) $sidebar_layout ) {
		$classes[] = 'sidebar col-xl-3 col-lg-4';
	}

	$classes = ciyashop_class_builder( $classes );

	/**
	 * Filters the CSS classes for product page sidebar.
	 *
	 * @param    string    $classes           Product page sidebar classes.
	 * @param    string    $sidebar_layout    Sidebar layout.
	 * @param    string    $sidebar_id        Sidebar ID.
	 *
	 * @visible true
	 */
	$classes = apply_filters( 'ciyashop_product_page_sidebar_classes', $classes, $sidebar_layout, $sidebar_id );

	return $classes;
}
/**
 * Shop content class
 *
 * @param array $classes .
 */
function ciyashop_shop_content_class( $classes = array() ) {
	$sidebar_layout          = ciyashop_shop_page_sidebar();
	$show_sidebar_on_mobile  = ciyashop_show_sidebar_on_mobile();
	$mobile_sidebar_position = ciyashop_mobile_sidebar_position();
	$device_type             = ciyashop_device_type();
	$sidebar_id              = 'sidebar-shop';

	if ( ! empty( $classes ) && ! is_array( $classes ) ) {
		$classes = explode( ' ', $classes );
	}

	$classes_new = array();

	if ( ! is_active_sidebar( $sidebar_id ) || 'no' === (string) $sidebar_layout ) {
		$classes_new[] = 'col-sm-12';
	} else {
		if ( 'left' === (string) $sidebar_layout ) {
			if ( 'bottom' === (string) $mobile_sidebar_position ) {
				$classes_new[] = 'col-xl-9 col-lg-8 order-xl-2 order-lg-2';
			} else {
				$classes_new[] = 'col-xl-9 col-lg-8';
			}
		} elseif ( 'right' === (string) $sidebar_layout ) {
			if ( 'top' === (string) $mobile_sidebar_position ) {
				$classes_new[] = 'col-xl-9 col-lg-8 order-xl-1 order-lg-1';
			} else {
				$classes_new[] = 'col-xl-9 col-lg-8';
			}
		} else {
			$classes_new[] = 'col-xl-9 col-lg-8';
		}
	}

	$classes = array_merge( $classes, $classes_new );

	$classes = ciyashop_class_builder( $classes );
	return $classes;
}
/**
 * Shop sidebar class
 *
 * @param array $classes .
 */
function ciyashop_shop_sidebar_class( $classes = array() ) {
	$sidebar_layout          = ciyashop_shop_page_sidebar();
	$show_sidebar_on_mobile  = ciyashop_show_sidebar_on_mobile();
	$mobile_sidebar_position = ciyashop_mobile_sidebar_position();
	$device_type             = ciyashop_device_type();
	$sidebar_id              = 'sidebar-shop';

	if ( ! empty( $classes ) && ! is_array( $classes ) ) {
		$classes = explode( ' ', $classes );
	}

	$classes_new = array();

	if ( 'left' === (string) $sidebar_layout ) {
		if ( 'bottom' === (string) $mobile_sidebar_position ) {
			$classes_new[] = 'col-xl-3 col-lg-4 order-xl-1 order-lg-1';
		} else {
			$classes_new[] = 'col-xl-3 col-lg-4';
		}
	} elseif ( 'right' === (string) $sidebar_layout ) {
		if ( 'top' === (string) $mobile_sidebar_position ) {
			$classes_new[] = 'col-xl-3 col-lg-4 order-xl-2 order-lg-2';
		} else {
			$classes_new[] = 'col-xl-3 col-lg-4';

		}
	} else {
		$classes_new[] = 'col-xl-3 col-lg-4';
	}

	$classes_new[] = $device_type;

	if ( false === (bool) $show_sidebar_on_mobile ) {
		$classes_new[] = 'd-none d-md-block d-lg-block d-xl-block';
	}

	$classes = array_merge( $classes, $classes_new );

	$classes = ciyashop_class_builder( $classes );
	return $classes;
}
/**
 * WC_wrapper_class
 *
 * @param string $class .
 */
function ciyashop_wc_wrapper_class( $class = '' ) {
	if ( is_product() ) {
		$content_class = ciyashop_product_content_class( $class );
	} else {
		$content_class = ciyashop_shop_content_class( $class );
	}

	/**
	 * Filters the CSS classes for WooCommerce wrapper.
	 *
	 * @param    string    $classes    WooCOmmerce wrapper classes.
	 *
	 * @visible true
	 */
	return apply_filters( 'ciyashop_wc_wrapper_class', $content_class );
}
/**
 * Product top left classes
 *
 * @param string $classes .
 */
function ciyashop_product_top_left_classes( $classes = array() ) {
	if ( ! empty( $classes ) && ! is_array( $classes ) ) {
		$classes = explode( ' ', $classes );
	}

	$product_page_width = ciyashop_product_page_width();
	$sidebar_layout     = ciyashop_product_page_sidebar();
	$sidebar_id         = 'sidebar-product';

	$classes_new = array();

	if ( 'wide' === (string) $product_page_width && ( 'no' === (string) $sidebar_layout || ! is_active_sidebar( $sidebar_id ) ) ) {
		$classes_new[] = 'col-xl-4';
	} else {
		$classes_new[] = 'col-xl-6';
		$classes_new[] = 'col-lg-6';
		$classes_new[] = 'col-md-6';
	}

	$classes = array_merge( $classes, $classes_new );

	apply_filters( 'ciyashop_product_top_left_classes', $classes );

	$classes = ciyashop_class_builder( $classes );

	echo esc_attr( $classes );
}
/**
 * Product top right classes
 *
 * @param array $classes .
 */
function ciyashop_product_top_right_classes( $classes = array() ) {
	if ( ! empty( $classes ) && ! is_array( $classes ) ) {
		$classes = explode( ' ', $classes );
	}

	$product_page_width = ciyashop_product_page_width();
	$sidebar_layout     = ciyashop_shop_page_sidebar();
	$sidebar_id         = 'sidebar-product';

	$classes_new = array();

	if ( 'wide' === (string) $product_page_width && ( 'no' === (string) $sidebar_layout || ! is_active_sidebar( $sidebar_id ) ) ) {
		$classes_new[] = 'col-xl-8';
	} else {
		$classes_new[] = 'col-xl-6';
		$classes_new[] = 'col-lg-6';
		$classes_new[] = 'col-md-6';
	}

	$classes = array_merge( $classes, $classes_new );

	apply_filters( 'ciyashop_product_top_right_classes', $classes );

	$classes = ciyashop_class_builder( $classes );

	echo esc_attr( $classes );
}

if ( ! function_exists( 'ciyashop_produt_clear_filters_btn' ) ) {
	/**
	 * Clear all filters button
	 */
	function ciyashop_produt_clear_filters_btn() {

		$reset       = false;
		$request_url = ( isset( $_SERVER['HTTPS'] ) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http' ) . '://' . sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) . esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidatedNotSanitized // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
		$avl_filters = array( 'product_cat', 'rating_filter', 'min_price', 'max_price', 'orderby', 'per_page' );
		
		$attribute_taxonomies = wc_get_attribute_taxonomies();
		$attributes           = array();
		if ( ! empty( $attribute_taxonomies ) ) {
			foreach ( $attribute_taxonomies as $attr ) {
				$attributes[] = 'filter_' . $attr->attribute_name;
			}
			$avl_filters = array_merge( $avl_filters, $attributes );
		}

		foreach ( $avl_filters as $filter ) {
			if ( strpos( $request_url, $filter ) ) {
				$reset = true;
			}
		}

		if ( $reset ) {
			$reset_url = strtok( $request_url, '?' );
			?>
			<div class="ciyashop-clear-filters-wrapp">
				<a class="ciyashop-clear-filters" href="<?php echo esc_url( $reset_url ); ?>"><i class="vc_icon_element-icon fa fa-refresh"></i><?php echo esc_html__( 'Clear', 'ciyashop' ); ?></a>
			</div>
			<?php
		}
	}
	add_action( 'ciyashop_before_active_filters_widgets', 'ciyashop_produt_clear_filters_btn' );
}
