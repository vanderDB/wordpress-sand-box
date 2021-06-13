<?php
/**
 * WC function front
 *
 * @package CiyaShop
 */

/**
 * Product tab layout
 */
function ciyashop_product_tab_layout() {
	global $ciyashop_options;

	$product_tab_layout = ( isset( $ciyashop_options['product-tab-layout'] ) && ! empty( $ciyashop_options['product-tab-layout'] ) ) ? $ciyashop_options['product-tab-layout'] : 'default';

	/**
	 * Filters the product tab layout.
	 *
	 * @param string    $tab_layout      Layout of product tab.
	 *
	 * @visible true
	 */
	$product_tab_layout = apply_filters( 'ciyashop_product_tab_layout', $product_tab_layout, $ciyashop_options );

	return $product_tab_layout;
}
/**
 * Product page width
 */
function ciyashop_product_page_width() {
	global $ciyashop_options;

	$product_page_width = ( isset( $ciyashop_options['product-page-width'] ) && ! empty( $ciyashop_options['product-page-width'] ) ) ? $ciyashop_options['product-page-width'] : 'fixed';

	/**
	 * Filters the width of the product page.
	 *
	 * @param string    $page_width      Width of product page.
	 *
	 * @visible true
	 */
	$product_page_width = apply_filters( 'ciyashop_product_page_width', $product_page_width, $ciyashop_options );

	return $product_page_width;
}
/**
 * Shop page width
 */
function ciyashop_shop_page_width() {
	global $ciyashop_options;

	$shop_page_width = ( isset( $ciyashop_options['shop-page-width'] ) && ! empty( $ciyashop_options['shop-page-width'] ) ) ? $ciyashop_options['shop-page-width'] : 'fixed';

	/**
	 * Filters the width of the page.
	 *
	 * @param string    $page_width      Width of page.
	 *
	 * @visible true
	 */
	$shop_page_width = apply_filters( 'ciyashop_shop_page_width', $shop_page_width, $ciyashop_options );

	return $shop_page_width;
}

add_action( 'woocommerce_single_product_summary', 'ciyashop_product_sale_countdown', 21 );
add_action( 'woocommerce_after_shop_loop_item_title', 'ciyashop_product_sale_countdown', 11 );
if ( ! function_exists( 'ciyashop_product_sale_countdown' ) ) {
	/**
	 * Product sale countdown
	 */
	function ciyashop_product_sale_countdown() {
		global $post, $ciyashop_options, $product;

		if ( is_single() && 'product' === get_post_type() && isset( $ciyashop_options['product_countdown'] ) && 0 === (int) $ciyashop_options['product_countdown'] ) {
			return;
		} elseif ( ! is_single() && isset( $ciyashop_options['shop_countdown'] ) && 0 === (int) $ciyashop_options['shop_countdown'] ) {
			return;
		}

		if ( ! $product->is_on_sale() ) {
			return;
		}

		$sale_date = get_post_meta( $post->ID, '_sale_price_dates_to', true );

		if ( ! $sale_date ) {
			return;
		}

		$counter_data = array(
			'days'    => esc_html__( 'Day', 'ciyashop' ),
			'hours'   => esc_html__( 'Hrs', 'ciyashop' ),
			'minutes' => esc_html__( 'Min', 'ciyashop' ),
			'seconds' => esc_html__( 'Sec', 'ciyashop' ),
		);

		$counter_data = wp_json_encode( $counter_data );
		?>
		<div class="woo-product-countdown-wrapper">
			<?php
			if ( is_single() && ! empty( $ciyashop_options['product_countdown_title'] ) ) {
				?>
				<h6><?php echo esc_html( $ciyashop_options['product_countdown_title'] ); ?></h6>
				<?php
			}
			?>
			<div class="woo-product-countdown woo-timer deal-counter-wrapper">
				<div class="deal-counter" data-countdown-date="<?php echo esc_attr( gmdate( 'Y/m/d', $sale_date ) ); ?>" data-counter_data="<?php echo esc_attr( $counter_data ); ?>"></div>
			</div>
		</div>
		<?php

	}
}
/**
 * Single product style
 */
function ciyashop_single_product_style() {
	global $ciyashop_options;

	$style = isset( $ciyashop_options['product_page_style'] ) && ! empty( $ciyashop_options['product_page_style'] ) ? $ciyashop_options['product_page_style'] : 'classic';

	/**
	 * Filters the style of the product.
	 *
	 * @param string    $style      Style of the product.
	 *
	 * @visible true
	 */
	$style = apply_filters( 'ciyashop_single_product_style', $style );

	return $style;
}
/**
 * Single product thumbnail position
 */
function ciyashop_single_product_thumbnail_position() {
	global $ciyashop_options;

	$product_style = ciyashop_single_product_style();

	if ( 'wide_gallery' === $product_style ) {
		return 'none';
	}

	$thumbnail_position = isset( $ciyashop_options['product_page_thumbnail_position'] ) && ! empty( $ciyashop_options['product_page_thumbnail_position'] ) ? $ciyashop_options['product_page_thumbnail_position'] : 'bottom';

	/**
	 * Filters the product gallery thumbnail position.
	 *
	 * @param string    $thumbnail_position      Position of thumbanils in product gallery.
	 *
	 * @visible true
	 */
	$thumbnail_position = apply_filters( 'ciyashop_single_product_thumbnail_position', $thumbnail_position );

	return $thumbnail_position;
}

if ( ! function_exists( 'ciyashop_header_cart_count' ) ) {
	/**
	 * Cart Link
	 * Displayed a link to the cart including the number of items present and the cart total
	 *
	 * @return void
	 * @since  1.0.0
	 */
	function ciyashop_header_cart_count() {
		$cart_count = WC()->cart->get_cart_contents_count();

		if ( $cart_count > 9 ) {
			$cart_count = '9+';
		}
		?>
		<span class="cart-count count"><?php echo esc_html( $cart_count ); ?></span>
		<?php
	}
}

if ( ! function_exists( 'ciyashop_cart_fragment' ) ) {
	/**
	 * Cart Fragments
	 * Ensure cart contents update when products are added to the cart via AJAX
	 *
	 * @param  array $fragments Fragments to refresh via AJAX.
	 * @return array            Fragments to refresh via AJAX
	 */
	function ciyashop_cart_fragment( $fragments ) {
		global $woocommerce;

		ob_start();
		ciyashop_header_cart_count();
		$fragments['.woo-tools-cart .cart-count'] = ob_get_clean();

		ob_start();
		?>
		<span class="woo-cart-subtotal"><?php echo WC()->cart->get_cart_subtotal(); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></span>
		<?php
		$fragments['.woo-cart-subtotal'] = ob_get_clean();

		ob_start();
		?>
		<span class="woo-cart-count">
			<?php echo WC()->cart->get_cart_contents_count(); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>
			<span><?php echo esc_html( _n( 'item', 'items', WC()->cart->get_cart_contents_count(), 'ciyashop' ) ); ?></span>
		</span>
		<?php
		$fragments['.woo-cart-count'] = ob_get_clean();

		return $fragments;
	}
}
/**
 * Sale flash label
 *
 * @param string $sale_html .
 * @param string $post .
 * @param string $product .
 */
function ciyashop_sale_flash_label( $sale_html, $post, $product ) {

	global $ciyashop_options;

	// Reset label.
	$sale_html = '';

	$product_sale          = isset( $ciyashop_options['product-sale'] ) && ! empty( $ciyashop_options['product-sale'] ) ? $ciyashop_options['product-sale'] : false;
	$product_sale_textperc = isset( $ciyashop_options['product_sale_textperc'] ) && ! empty( $ciyashop_options['product_sale_textperc'] ) ? $ciyashop_options['product_sale_textperc'] : 'text';
	$product_sale_label    = isset( $ciyashop_options['product-sale-label'] ) && ! empty( $ciyashop_options['product-sale-label'] ) ? $ciyashop_options['product-sale-label'] : esc_html__( 'Sale', 'ciyashop' );

	if ( 'percent' === $product_sale_textperc ) {

		$percentage = 0;

		if ( $product->is_type( 'simple' ) || $product->is_type( 'external' ) ) {

			$regular_price = $product->get_regular_price();
			$sale_price    = $product->get_sale_price();

			if ( $regular_price ) {
				$percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
			}
				$percentage = '-' . $percentage;

		} elseif ( $product->is_type( 'variable' ) ) {

			$available_variations = $product->get_available_variations();

			if ( $available_variations ) {

				$percents = array();
				foreach ( $available_variations as $variations ) {

					$regular_price = $variations['display_regular_price'];
					$sale_price    = $variations['display_price'];

					if ( $regular_price ) {
						$percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
						$percents[] = $percentage;
					}
				}

				$max_discount = max( $percents );

				/* translators: %s: discount percentage */
				$percentage = sprintf( esc_html__( 'Up to -%s', 'ciyashop' ), $max_discount );
			}
		}

		$sale_label = $percentage . '%';
	} else {
		$sale_label = $product_sale_label;
	}

	if ( $product_sale ) {
		$sale_html = '<span class="onsale">' . esc_html( $sale_label ) . '</span>';
	}

	return $sale_html;
}
/**
 * Featured label
 *
 * @param string $featured_html .
 * @param string $post .
 * @param string $product .
 */
function ciyashop_featured_label( $featured_html, $post, $product ) {

	global $ciyashop_options;

	// Reset label.
	$featured_html = '';

	$product_hot       = isset( $ciyashop_options['product-hot'] ) && ! empty( $ciyashop_options['product-hot'] ) ? $ciyashop_options['product-hot'] : false;
	$product_hot_label = isset( $ciyashop_options['product-hot-label'] ) && ! empty( $ciyashop_options['product-hot-label'] ) ? $ciyashop_options['product-hot-label'] : esc_html__( 'Hot', 'ciyashop' );

	if ( $product_hot ) {
		$featured_html = '<span class="featured">' . esc_html( $product_hot_label ) . '</span>';
	}

	return $featured_html;
}

/**
 * Return cart item key based on product id
 *
 * @param string $product_id .
 */
function ciyashop_wc_get_cart_item_key( $product_id = '' ) {
	if ( ! $product_id ) {
		return false;
	}

	global $woocommerce;

	foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) {
		if ( (string) $cart_item['product_id'] === (string) $product_id ) {
			return $cart_item_key;
		}
	}

	return false;
}
/**
 * Woocommerce page
 */
function ciyashop_is_woocommerce_page() {
	global $post;

	if ( class_exists( 'WooCommerce' ) ) {

		if ( is_woocommerce() || is_cart() || is_checkout() || is_checkout_pay_page() || is_account_page() || is_view_order_page() || is_edit_account_page()
		|| is_order_received_page() || is_add_payment_method_page() || is_lost_password_page() ) {
			return true;
		}

		if ( isset( $post->post_content ) && has_shortcode( $post->post_content, 'yith_wcwl_wishlist' ) ) {
			return true;
		}
	}

	return false;
}
/**
 * Get swap image
 *
 * @param string $size .
 */
function ciyashop_get_swap_image( $size = 'woocommerce_thumbnail' ) {
	global $product, $ciyashop_options;

	$image_src      = '';
	$attachment_ids = $product->get_gallery_image_ids();
	
	if ( isset( $ciyashop_options['disable_hover_effect_mobile'] ) && $ciyashop_options['disable_hover_effect_mobile'] && wp_is_mobile() ) {
		return false;
	}
	
	if ( count( $attachment_ids ) >= 1 ) {
		$attachment_id    = $attachment_ids[0];
		$product_thumbnai = wp_get_attachment_image( $attachment_id, $size );
		return $product_thumbnai;
	}

	return false;
}

add_action( 'init', 'ciyashop_hide_price_add_cart_not_logged_in', 11 );
/**
 * Hide price add cart not logged in
 */
function ciyashop_hide_price_add_cart_not_logged_in() {
	global $ciyashop_options;

	if ( isset( $ciyashop_options['hide_price_for_guest_user'] ) && $ciyashop_options['hide_price_for_guest_user'] ) {
		if ( ! is_user_logged_in() ) {
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
			remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
			remove_action( 'ciyashop_product_actions', 'woocommerce_template_loop_add_to_cart', 10 );
		}

		add_filter(
			'woocommerce_get_price_html',
			function( $price ) {
				if ( is_user_logged_in() ) {
					return $price;
				}
				return '';
			}
		);
	};
}

add_action( 'woocommerce_account_dashboard', 'ciyashop_account_dasboard', 10 );
if ( ! function_exists( 'ciyashop_account_dasboard' ) ) {
	/**
	 * Account dasboard
	 */
	function ciyashop_account_dasboard() {
		$dashbord_items = array(
			'dashboard' => array(
				'id'   => get_option( 'woocommerce_myaccount_page_id' ),
				'link' => get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ),
				'name' => __( 'Dashboard', 'ciyashop' ),
				'icon' => 'fa fa-tachometer',
			),
			'oreders'   => array(
				'id'   => get_option( 'woocommerce_myaccount_orders_endpoint' ),
				'link' => wc_get_account_endpoint_url( get_option( 'woocommerce_myaccount_orders_endpoint' ) ),
				'name' => __( 'Orders', 'ciyashop' ),
				'icon' => 'fa fa-file-text',
			),
			'downloads' => array(
				'id'   => get_option( 'woocommerce_myaccount_downloads_endpoint' ),
				'link' => wc_get_account_endpoint_url( get_option( 'woocommerce_myaccount_downloads_endpoint' ) ),
				'name' => __( 'Downloads', 'ciyashop' ),
				'icon' => 'fa fa-download',
			),
			'address'   => array(
				'id'   => get_option( 'woocommerce_myaccount_edit_address_endpoint' ),
				'link' => wc_get_account_endpoint_url( get_option( 'woocommerce_myaccount_edit_address_endpoint' ) ),
				'name' => __( 'Address', 'ciyashop' ),
				'icon' => 'fa fa-map-marker',
			),
			'account'   => array(
				'id'   => get_option( 'woocommerce_myaccount_edit_account_endpoint' ),
				'link' => wc_get_account_endpoint_url( get_option( 'woocommerce_myaccount_edit_account_endpoint' ) ),
				'name' => __( 'Account Details', 'ciyashop' ),
				'icon' => 'fa fa-user-circle-o',
			),
			'logout'    => array(
				'id'   => get_option( 'woocommerce_logout_endpoint' ),
				'link' => wc_get_account_endpoint_url( get_option( 'woocommerce_logout_endpoint' ) ),
				'name' => __( 'Logout', 'ciyashop' ),
				'icon' => 'fa fa-sign-out',
			),
		);

		/**
		 * Deprecated Filter: Filters the list of WooCommerce My Account dashboard items.
		 *
		 * @param string    $dashbord_items      Asn array of dashboard items.
		 *
		 * @visible false
		 * @ignore
		 */
		$dashbord_items = apply_filters_deprecated( 'ciyashop_account_dashbords', array( $dashbord_items, $dashbord_items ), '3.1.0', 'ciyashop_myaccount_dashbord_items' );

		/**
		 * Filters the list of WooCommerce My Account dashboard items.
		 *
		 * @param string    $dashbord_items      Asn array of dashboard items.
		 *
		 * @visible true
		 */
		$dashbord_items = apply_filters( 'ciyashop_myaccount_dashbord_items', $dashbord_items );
		?>
		<div class="myaccount-grid-navigation">
			<div class="row">
			<?php
			if ( ! empty( $dashbord_items ) ) {
				foreach ( $dashbord_items as $dashbord_item ) {
					if ( ! empty( $dashbord_item['id'] ) ) {
						?>
						<div class="col-sm-4">
							<div class="grid-navigation">
								<a href="<?php echo esc_url( $dashbord_item['link'] ); ?>">
									<i class="<?php echo esc_attr( $dashbord_item['icon'] ); ?>"></i>
									<span><?php echo esc_html( $dashbord_item['name'] ); ?></span>
								</a>
							</div>
						</div>
						<?php
					}
				}
			}
			?>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'ciyashop_side_shopping_cart' ) ) {
	/**
	 * Side shopping cart
	 */
	function ciyashop_side_shopping_cart() {
		global $ciyashop_options;

		$product_after_add_to_cart = isset( $ciyashop_options['product_after_add_to_cart_action'] ) ? $ciyashop_options['product_after_add_to_cart_action'] : '';

		if ( ! $product_after_add_to_cart || 'none' === $product_after_add_to_cart ) {
			return;
		}

		if ( 'side_widget' === $product_after_add_to_cart ) {
			?>
			<div class="side_shopping_cart-overlay"></div>
			<div class="side_shopping_cart-wrapper">
				<div class="side_shopping_cart-heading">
					<h3 class="side_shopping_cart-title"><?php esc_html_e( 'Shopping cart', 'ciyashop' ); ?></h3>
					<a href="#" class="close-side_shopping_cart"><?php esc_html_e( 'close', 'ciyashop' ); ?></a>
				</div>
				<div class="side_shopping_cart">
					<div class="side_shopping_cart-contents">
					<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
					</div>
				</div>
			</div>
			<?php
		} elseif ( 'popup' === $product_after_add_to_cart ) {
			?>
			<div id="after_add_to_cart_message-popup" class="after_add_to_cart_message-wrapper mfp-hide">
				<div class="cart_message-inner" style="<?php // echo esc_attr($content_css );. ?>">
					<h4><?php esc_html_e( 'The product has been added to your cart.', 'ciyashop' ); ?></h4>
					<a href="#" class="close-popup"><?php esc_html_e( 'Continue shopping', 'ciyashop' ); ?></a>
					<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="view-cart"><?php esc_html_e( 'View Cart', 'ciyashop' ); ?></a>
				</div>
			</div>
			<?php
		}
	}
	add_action( 'wp_footer', 'ciyashop_side_shopping_cart', 100 );
}

add_filter( 'yith_wcwl_wishlist_params', 'ciyashop_yith_wcwl_wishlist_params', 10, 5 );
/**
 * Hide cart for guest user for wishlist
 *
 * @param string $additional_params .
 * @param string $action .
 * @param string $action_params .
 * @param string $pagination .
 * @param string $per_page .
 */
function ciyashop_yith_wcwl_wishlist_params( $additional_params, $action, $action_params, $pagination, $per_page ) {
	global $ciyashop_options;

	if ( isset( $ciyashop_options['hide_price_for_guest_user'] ) && $ciyashop_options['hide_price_for_guest_user'] && ! is_user_logged_in() ) {
		$additional_params['show_add_to_cart'] = false;
		$additional_params['show_price']       = false;
		$additional_params['show_last_column'] = ( ( $additional_params['show_dateadded'] && is_user_logged_in() ) || $additional_params['show_add_to_cart'] || $additional_params['repeat_remove_button'] );
	}

	return $additional_params;
}

add_filter( 'yith_woocompare_filter_table_fields', 'ciyashop_yith_woocompare_filter_table_fields', 10, 2 );
/**
 * Hide cart for guest user for compare
 *
 * @param string $fields .
 * @param string $products .
 */
function ciyashop_yith_woocompare_filter_table_fields( $fields, $products ) {
	global $ciyashop_options;

	if ( isset( $ciyashop_options['hide_price_for_guest_user'] ) && $ciyashop_options['hide_price_for_guest_user'] && ! is_user_logged_in() ) {
		unset( $fields['price'] );
		unset( $fields['add-to-cart'] );
	}

	return $fields;
}

// Remove Duplicate Heading from single product page tabs.
add_filter(
	'woocommerce_product_description_heading',
	function() {
		return '';
	}
);
add_filter(
	'woocommerce_product_additional_information_heading',
	function() {
		return '';
	}
);

/**
 * Get the shop banner image URL.
 *
 * @return string|false
 */
function ciyashop_shop_banner_img_src() {
	global $ciyashop_options, $wp_query;

	$pro_shop_banner_show = isset( $ciyashop_options['pro-shop-banner_show'] ) ? (bool) $ciyashop_options['pro-shop-banner_show'] : false;
	$pro_shop_banner      = isset( $ciyashop_options['pro-shop-banner'] ) ? $ciyashop_options['pro-shop-banner'] : '';
	$image_url            = false;
	$page_type            = 'shop';
	$term_id              = '';

	if ( isset( $pro_shop_banner['url'] ) && ! empty( $pro_shop_banner['url'] ) ) {
		$pro_shop_banner_url = $pro_shop_banner['url'];
	}

	if ( $pro_shop_banner && is_array( $pro_shop_banner ) && isset( $pro_shop_banner['url'] ) && ! empty( $pro_shop_banner['url'] ) ) {
		$image_url = $pro_shop_banner['url'];
	}

	if ( is_product_category() ) {
		$page_type          = 'product_category';
		$term_id            = $wp_query->get_queried_object()->term_id;
		$car_shop_banner_id = get_term_meta( $term_id, 'product_cat_banner_image', true );

		if ( $car_shop_banner_id ) {
			$image_data = wp_get_attachment_image_src( $car_shop_banner_id, 'full', false );
			if ( $image_data ) {
				$image_url = $image_data[0];
			}
		}
	} elseif ( is_product_tag() ) {
		$page_type          = 'product_tag';
		$term_id            = $wp_query->get_queried_object()->term_id;
		$tab_shop_banner_id = get_term_meta( $term_id, 'product_tag_banner_image', true );

		if ( $tab_shop_banner_id ) {
			$image_data = wp_get_attachment_image_src( $tab_shop_banner_id, 'full', false );
			if ( $image_data ) {
				$image_url = $image_data[0];
			}
		}
	}

	$image_url = ( $pro_shop_banner_show && $image_url ) ? $image_url : false;

	/**
	 * Shop page banner image.
	 *
	 * @param string|false $image_url URL of banner image or false.
	 * @param string       $page_type Type of shop page... shop, product_category, or product_tag.
	 * @param string       $term_id Applicable if $page_type is set to "product_category" or "product_tag".
	 */
	return apply_filters( 'ciyashop_shop_banner_img_src', $image_url, $page_type, $term_id );
}
