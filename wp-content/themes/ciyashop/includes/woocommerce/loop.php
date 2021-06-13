<?php
/**
 * WC Loop.
 *
 * @package CiyaShop
 */

add_action( 'woocommerce_before_single_product', 'ciyashop_woocommerce_init_loop' );
/**
 * Wc init loop
 */
function ciyashop_woocommerce_init_loop() {
	global $ciyashop_options;
}

/**
 * Products Loop Customization
 *
 * @param array $classes classes.
 */
function ciyashop_products_loop_classes( $classes = array() ) {
	global $ciyashop_options;

	if ( ! empty( $classes ) && ! is_array( $classes ) ) {
		$classes = explode( ' ', $classes );
	}

	$classes[] = 'products-loop';
	$classes[] = 'row';

	if ( is_product() ) {
		$classes[] = 'owl-carousel';
	} else {
		$column        = ciyashop_loop_columns();
		$gridlist_view = isset( $_COOKIE['gridlist_view'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['gridlist_view'] ) ) : 'products-loop-column-' . $column;

		if ( is_shop() || is_product_taxonomy() ) {
			if ( 'list' !== $gridlist_view ) {
				$classes[] = 'grid';
			}

			if ( isset( $ciyashop_options['product_pagination'] ) && 'infinite_scroll' === $ciyashop_options['product_pagination'] ) {
				$classes[] = 'product-infinite_scroll';
			}

			$classes[] = $gridlist_view;
		}
	}

	if ( isset( $ciyashop_options['products_columns_mobile'] ) ) {
		$xs_columns = (int) $ciyashop_options['products_columns_mobile'];
	} else {
		$xs_columns = 1;
	}
	$classes[] = 'ciyashop-products-shortcode';
	$classes[] = 'mobile-col-' . $xs_columns;

	if ( is_cart() ) {
		$classes[] = 'owl-carousel';
	}

	$classes = apply_filters( 'ciyashop_products_loop_classes', $classes );
	$classes = ciyashop_class_builder( $classes );

	return $classes;
}


add_filter( 'woocommerce_shop_loop_item_title', 'ciyashop_woocommerce_shop_loop_item_inner_start', 13 );
/**
 * Inner div for standard quick shop hover style
 */
function ciyashop_woocommerce_shop_loop_item_inner_start() {
	global $ciyashop_options;
	if ( isset( $ciyashop_options['product_hover_style'] ) && 'standard-quick-shop' === $ciyashop_options['product_hover_style'] ) {
		?>
		<div class="ciyashop-product-variations-wrapper-inner"> 
		<?php
	}
}

add_filter( 'woocommerce_shop_loop_item_title', 'ciyashop_woocommerce_shop_loop_item_inner_end', 41 );
/**
 * Inner End
 */
function ciyashop_woocommerce_shop_loop_item_inner_end() {
	global $ciyashop_options;
	if ( isset( $ciyashop_options['product_hover_style'] ) && 'standard-quick-shop' === $ciyashop_options['product_hover_style'] ) {
		?>
	</div> 
		<?php
	}
}

add_filter( 'woocommerce_shop_loop_item_title', 'ciyashop_woocommerce_shop_loop_item_category', 13 );
/**
 * Add product category title
 */
function ciyashop_woocommerce_shop_loop_item_category() {
	global $product, $ciyashop_options;
	$product_cats = get_the_terms( $product->get_id(), 'product_cat' );

	if ( ( $product_cats && ! is_wp_error( $product_cats ) ) && ( isset( $ciyashop_options['product_hover_style'] ) && 'minimal-hover-cart' !== $ciyashop_options['product_hover_style'] ) ) {

		// convert objects to array.
		$product_cats_new = wp_json_encode( $product_cats );
		$product_cats_new = json_decode( $product_cats_new, true );

		$product_cats_ids = array_column( $product_cats_new, 'term_id' );

		// Category Index.
		$cat_index = 0;

		if ( defined( 'WPSEO_FILE' ) ) {
			$primary_cat_id = get_post_meta( $product->get_id(), '_yoast_wpseo_primary_product_cat', true );
			if ( ! empty( $primary_cat_id ) && in_array( $primary_cat_id, $product_cats_ids, true ) ) {
				$cat_index = array_search( $primary_cat_id, $product_cats_ids, true );
			}
		}
		?>
		<span class="ciyashop-product-category">
			<a href="<?php echo esc_url( get_term_link( $product_cats[ $cat_index ]->term_id ), 'product_cat' ); ?>">
				<?php echo esc_html( $product_cats[ $cat_index ]->name ); ?>
			</a>
		</span><!-- .product-category-name-->
		<?php
	}
}

/**
 * Set Product List Elements
 */

/**
 * Remove Default List Elements
 */

// Remove Default Title Display.
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );

// Remove Woocommerce Rating For Change Position.
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

// Remove woocommerce price for change position.
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );

add_action( 'ciyashop_before_page_wrapper', 'ciyashop_set_product_list_elements', 40 );
/**
 * Add Product List Elements
 */
function ciyashop_set_product_list_elements() {
	global $ciyashop_options, $cs_product_list_styles;

	/**
	 * Add link to product title
	 */
	if ( isset( $ciyashop_options['product_hover_style'] ) && 'minimal-hover-cart' === $ciyashop_options['product_hover_style'] ) {
		add_action( 'woocommerce_before_shop_loop_item_title', 'ciyashop_woocommerce_shop_loop_item_title', 20 );
	} else {
		add_filter( 'woocommerce_shop_loop_item_title', 'ciyashop_woocommerce_shop_loop_item_title', 15 );
	}

	/**
	 * Add woocommerce rating to new position
	 */
	if ( isset( $ciyashop_options['product_hover_style'] ) && in_array( $ciyashop_options['product_hover_style'], array( $cs_product_list_styles['icons-bottom-bar'], $cs_product_list_styles['info-bottom'], $cs_product_list_styles['info-bottom-bar'] ), true ) ) {
		add_action( 'woocommerce_shop_loop_item_title', 'ciyashop_wc_shop_loop_item_rating', 12 );
	} else {
		add_action( 'woocommerce_shop_loop_item_title', 'ciyashop_wc_shop_loop_item_rating', 30 );
	}

	/**
	 * ******************************************************************
	 *
	 * Add woocommerce price to new position
	 *
	 * ******************************************************************
	 */

	if ( isset( $ciyashop_options['product_hover_style'] ) && 'standard-info-transparent' === $ciyashop_options['product_hover_style'] ) {
		add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_price', 27 );
	} elseif ( isset( $ciyashop_options['product_hover_style'] ) && 'hover-summary' === $ciyashop_options['product_hover_style'] ) {
		add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_price', 32 );
	} else {
		add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_price', 20 );
	}

	/**
	 * ******************************************************************
	 *
	 * Add new div for standard-info-transparent style
	 *
	 * ******************************************************************
	 */

	if ( isset( $ciyashop_options['product_hover_style'] ) && 'standard-info-transparent' === $ciyashop_options['product_hover_style'] ) {

		add_action( 'woocommerce_shop_loop_item_title', 'ciyashop_open_standard_info_container', 25 ); // open div.
		add_action( 'woocommerce_shop_loop_item_title', 'ciyashop_add_product_variation', 32 ); // add variation.
		add_action( 'woocommerce_shop_loop_item_title', 'ciyashop_close_standard_info_container', 35 ); // close div.
	}

	if ( isset( $ciyashop_options['product_hover_style'] ) && 'standard-quick-shop' === $ciyashop_options['product_hover_style'] ) {
		add_action( 'woocommerce_shop_loop_item_title', 'ciyashop_product_actions_add_variation_product_view', 10 ); // add variation.
	} else {
		add_action( 'woocommerce_before_shop_loop_item_title', 'ciyashop_product_actions_add_variation_product_view', 27 );
	}
}


/**
 * Callback Functions For Product List Elements Actions
 */

/**
 * Callback Function For Product Title Link Action
 */
function ciyashop_woocommerce_shop_loop_item_title() {
	global $product;
	?>
	<h3 class="product-name">
		<a href="<?php echo esc_url( get_the_permalink( get_the_ID() ) ); ?>">
			<?php echo esc_html( get_the_title( get_the_ID() ) ); ?>
		</a>
	</h3><!-- .product-name-->
	<?php
}

/**
 * Callback Function For Product Woocommerce Ratings
 */
function ciyashop_wc_shop_loop_item_rating() {
	global $product;
	$rating_count = $product->get_rating_count();

	if ( $rating_count <= 0 ) {
		return;
	}
	?>
	<div class="star-rating-wrapper">
		<?php

		/**
		 * Hook: ciyashop_loop_item_rating.
		 *
		 * @hooked woocommerce_template_loop_rating - 10
		 *
		 * @visible true
		 */
		do_action( 'ciyashop_loop_item_rating' );
		?>
	</div><!-- .star-rating-wrapper -->
	<?php
}
add_action( 'ciyashop_loop_item_rating', 'woocommerce_template_loop_rating' );


/**
 * Callback Functions For Product Woocommerce Standard-Info-Transparent List Style
 */
function ciyashop_open_standard_info_container() {
	?>
	<div class="standard-info">
	<?php
}
/**
 * Close standard info container
 */
function ciyashop_close_standard_info_container() {
	?>
	</div><!-- .standard-info -->
	<?php
}
/**
 * Add product variation
 */
function ciyashop_add_product_variation() {
	if ( ! is_product() ) {
		echo ciyashop_attr_variation_list(); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}
}

/**
 * Add Product Description
 */

/**
 * For List View
 */
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_single_excerpt', 5 );

// For Grid View.
add_action( 'woocommerce_shop_loop_item_title', 'ciyashop_woocommerce_shop_loop_item_description', 40 );
/**
 * Loop Item description
 */
function ciyashop_woocommerce_shop_loop_item_description() {
	global $product, $ciyashop_options;

	if ( isset( $ciyashop_options['product_hover_style'] ) && 'hover-summary' !== $ciyashop_options['product_hover_style'] ) {
		return;
	}

	if ( isset( $ciyashop_options['product_hover_style'] ) && 'yes' === $ciyashop_options['show_product_desc'] ) {
		?>
	<!--  Product List product description div starts -->
	<div class="ciyashop-product-description">
		<div class="ciyashop-description-inner">
		<?php echo ( 'product_contents' === $ciyashop_options['product_desc_source'] ) ? $product->get_description() : $product->get_short_description(); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>
		</div>
	</div><!-- .product-description-->
		<?php
	}
}

/**
 * Remove product link default callback
 */
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

/**
 * Extra wrappers to product loop.
 */
add_action( 'woocommerce_before_shop_loop_item', 'ciyashop_wc_before_shop_loop_item_add_innerdiv_start', 5 );                // .product-inner opening.
add_action( 'woocommerce_after_shop_loop_item', 'ciyashop_wc_before_shop_loop_item_add_innerdiv_end', 20 );                   // .product-inner closing.

add_action( 'woocommerce_before_shop_loop_item', 'ciyashop_wc_before_shop_loop_item_product_thumbnail_start', 6 );           // .product-thumbnail opening.
add_action( 'woocommerce_before_shop_loop_item_title', 'ciyashop_wc_before_shop_loop_item_product_thumbnail_end', 30 );      // .product-thumbnail closing.

add_action( 'woocommerce_before_shop_loop_item', 'ciyashop_wc_before_shop_loop_item_product_thumbnail_inner_start', 7 );     // .product-thumbnail-inner opening.
add_action( 'woocommerce_before_shop_loop_item_title', 'ciyashop_wc_before_shop_loop_item_product_thumbnail_inner_end', 12 );// .product-thumbnail-inner closing.

add_action( 'woocommerce_shop_loop_item_title', 'ciyashop_wc_before_shop_loop_item_title_product_info_open', 9 );            // .product-info opening.
add_action( 'woocommerce_after_shop_loop_item', 'ciyashop_woocommerce_after_shop_loop_item_product_info_close', 18 );         // .product-info closing.

/**
 * Inner Div Start
 */
function ciyashop_wc_before_shop_loop_item_add_innerdiv_start() {
	?>
	<div class="product-inner">
	<?php
}
/**
 * Inner Div End
 */
function ciyashop_wc_before_shop_loop_item_add_innerdiv_end() {
	?>
	</div><!-- .product-inner -->
	<?php
}
/**
 * Thambnail Start
 */
function ciyashop_wc_before_shop_loop_item_product_thumbnail_start() {
	global $ciyashop_options;
	if ( isset( $ciyashop_options['product_hover_style'] ) && 'hover-summary' === $ciyashop_options['product_hover_style'] ) {
		?>
		<!--  This will be used for "Summary" grid style only -->
		<div class="content-hover-block"></div>
		<?php
	}
	?>
	<div class="product-thumbnail">
	<?php
}

/**
 * Thumbnail End
 */
function ciyashop_wc_before_shop_loop_item_product_thumbnail_end() {
	?>
	</div><!-- .product-thumbnail -->
	<?php
}

/**
 * Thumbnail Inner start
 */
function ciyashop_wc_before_shop_loop_item_product_thumbnail_inner_start() {
	?>
	<div class="product-thumbnail-inner">
		<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">
			<div class="product-thumbnail-main">
			<?php
}
/**
 * Thumbnail Inner end
 */
function ciyashop_wc_before_shop_loop_item_product_thumbnail_inner_end() {
	global $ciyashop_options;
	?>
		</div>
		<?php
		$attachment_image = ciyashop_get_swap_image();
		if ( isset( $ciyashop_options['product_image_swap'] ) && 1 === (int) $ciyashop_options['product_image_swap'] && $attachment_image ) {
			echo '<div class="product-thumbnail-swap">';
				echo ciyashop_get_swap_image(); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
			echo '</div>';
		}
		?>
		</a>
	</div><!-- .product-thumbnail-inner -->
	<?php
}
/**
 * Product Info Open
 */
function ciyashop_wc_before_shop_loop_item_title_product_info_open() {
	?>
	<div class="product-info">
	<?php
}

/**
 * Product Info Close
 */
function ciyashop_woocommerce_after_shop_loop_item_product_info_close() {
	?>
	</div><!-- .product-info -->
	<?php
}

/**
 * Apply filter on woocommerce before loop item title
 */
add_action( 'woocommerce_shortcode_before_best_selling_products_loop', 'ciyashop_shop_loop_item_hover_style_init' );
add_action( 'woocommerce_shortcode_before_featured_products_loop', 'ciyashop_shop_loop_item_hover_style_init' );
add_action( 'woocommerce_shortcode_before_product_category_loop', 'ciyashop_shop_loop_item_hover_style_init' );
add_action( 'woocommerce_shortcode_before_sale_products_loop', 'ciyashop_shop_loop_item_hover_style_init' );
add_action( 'woocommerce_shortcode_before_products_loop', 'ciyashop_shop_loop_item_hover_style_init' );
add_action( 'woocommerce_before_shop_loop', 'ciyashop_shop_loop_item_hover_style_init' );
add_action( 'woocommerce_after_single_product_summary', 'ciyashop_shop_loop_item_hover_style_init', 0 );
add_action( 'dokan_store_profile_frame_after', 'ciyashop_shop_loop_item_hover_style_init' );

/**
 * Hover Style Init
 *
 * @param string $template_name template name.
 */
function ciyashop_shop_loop_item_hover_style_init( $template_name ) {

	global $ciyashop_options;

	$product_hover_style = ciyashop_product_hover_style();

	if ( in_array( $product_hover_style, array( 'default', 'icon-top-left', 'icons-top-right', 'image-center', 'image-icon-left', 'image-icon-bottom', 'icons-bottom-right', 'image-left', 'button-standard', 'icons-left', 'icons-rounded', 'image-bottom', 'image-bottom-bar', 'image-bottom-2', 'icons-transparent-center', 'standard-info-transparent', 'standard-quick-shop', 'hover-summary' ), true ) ) {
		add_action( 'woocommerce_before_shop_loop_item_title', 'ciyashop_product_actions', 25 );
	} elseif ( in_array( $product_hover_style, array( 'info-bottom', 'info-bottom-bar' ), true ) ) {
		add_action( 'woocommerce_after_shop_loop_item', 'ciyashop_product_actions', 19 );
	} elseif ( in_array( $product_hover_style, array( 'info-transparent-center' ), true ) ) {
		add_action( 'woocommerce_shop_loop_item_title', 'ciyashop_product_actions', 12 );
	} elseif ( in_array( $product_hover_style, array( 'minimal-hover-cart', 'minimal' ), true ) ) {
		add_action( 'woocommerce_shop_loop_item_title', 'ciyashop_product_actions', 18 );
	}

	// Show Hide Sale.
	add_filter( 'woocommerce_sale_flash', 'ciyashop_sale_flash_label', 10, 3 );

	// Show Hide Featured.
	add_filter( 'ciyashop_featured', 'ciyashop_featured_label', 10, 3 );

	add_filter( 'post_class', 'ciyashop_product_classes' );
}
/**
 * Product actions
 */
function ciyashop_product_actions() {
	/**
	 * Hook: ciyashop_before_product_actions.
	 *
	 * @hooked ciyashop_product_actions_wrapper_open - 10
	 *
	 * @visible true
	 */
	do_action( 'ciyashop_before_product_actions' );

	/**
	 * Hook: ciyashop_product_actions.
	 *
	 * @hooked woocommerce_template_loop_add_to_cart      - 10
	 * @hooked ciyashop_product_actions_add_wishlist_link - 20
	 * @hooked add_compare_link                           - 30
	 *
	 * @visible true
	 */
	do_action( 'ciyashop_product_actions' );

	/**
	 * Hook: ciyashop_after_product_actions.
	 *
	 * @hooked ciyashop_product_actions_wrapper_close - 10
	 *
	 * @visible true
	 */
	do_action( 'ciyashop_after_product_actions' );
}

add_action( 'ciyashop_before_product_actions', 'ciyashop_product_actions_wrapper_open' );
/**
 * Wrpper Open
 */
function ciyashop_product_actions_wrapper_open() {
	?>
	<div class="product-actions">
		<div class="product-actions-inner">
		<?php
}

add_action( 'ciyashop_after_product_actions', 'ciyashop_product_actions_wrapper_close' );
/**
 * Wrapper Close
 */
function ciyashop_product_actions_wrapper_close() {
	?>
		</div>
	</div>
	<?php
}

/**
 * Add product style class
 *
 * @param array $classes classes.
 */
function ciyashop_product_classes( $classes ) {
	global $post, $ciyashop_options;

	// Set Product Hover Style Class.
	$product_hover_style = ciyashop_product_hover_style();
	$classes[]           = 'product-hover-style-' . $product_hover_style;

	// Set Product Hover Button Shape Class.
	if ( in_array( $product_hover_style, array( 'image-center', 'image-icon-left', 'image-icon-bottom', 'image-left', 'image-bottom', 'info-bottom', 'image-bottom-2' ), true ) ) {
		$product_hover_button_shape = ciyashop_product_hover_button_shape();
		$classes[]                  = 'product-hover-button-shape-' . $product_hover_button_shape;
	}

	// Set Product Hover Button Style Class.
	if ( in_array( $product_hover_style, array( 'image-center', 'image-icon-left', 'image-left', 'image-icon-bottom', 'image-bottom' ), true ) ) {
		$product_hover_button_style = ciyashop_product_hover_button_style();
		$classes[]                  = 'product-hover-button-style-' . $product_hover_button_style;
	}

	// Set Product Hover style class for default style.
	if ( in_array( $product_hover_style, array( 'default', 'icon-top-left', 'icons-top-right', 'image-left', 'button-standard', 'icons-left', 'icons-rounded', 'icons-bottom-right', 'hover-summary', 'minimal-hover-cart', 'minimal', 'standard-info-transparent', 'standard-quick-shop', 'image-bottom-2' ), true ) ) {
		$product_hover_default_button_style = ciyashop_product_hover_default_button_style();
		$classes[]                          = 'product-hover-button-style-' . $product_hover_default_button_style;
	}

	// Set Product Hover Bar Style Class.
	if ( in_array( $product_hover_style, array( 'image-bottom-bar', 'info-bottom-bar' ), true ) ) {
		$product_hover_bar_style = ciyashop_product_hover_bar_style();
		$classes[]               = 'product-hover-bar-style-' . $product_hover_bar_style;
	}

	// Set Product Hover Bar Style Class.
	if ( in_array( $product_hover_style, array( 'image-bottom-bar', 'info-bottom', 'info-bottom-bar' ), true ) ) {
		$product_hover_add_to_cart_position = ciyashop_product_hover_add_to_cart_position();
		$classes[]                          = 'product-hover-act-position-' . $product_hover_add_to_cart_position;
	}

	// Product Title length.
	if ( isset( $ciyashop_options['product_title_length'] ) && ! empty( $ciyashop_options['product_title_length'] ) ) {
		$classes[] = 'product_title_type-' . $ciyashop_options['product_title_length'];
	}

	$icon_type = isset( $ciyashop_options['product_hover_icon_type'] ) && ! empty( $ciyashop_options['product_hover_icon_type'] ) ? $ciyashop_options['product_hover_icon_type'] : 'fill-icon';

	$classes[] = 'product_icon_type-' . $icon_type;

	$classes = apply_filters( 'ciyashop_product_classes', $classes, $post );

	return $classes;
}


if ( ! function_exists( 'ciyashop_product_availability' ) ) {
	/**
	 * Display stock lable
	 */
	function ciyashop_product_availability() {
		global $ciyashop_options, $product;
		
		$product_out_of_stock_icon = isset( $ciyashop_options['product-out-of-stock-icon'] ) ? $ciyashop_options['product-out-of-stock-icon'] : '';
		$product_in_stock_icon     = isset( $ciyashop_options['product-in-stock-icon'] ) ? $ciyashop_options['product-in-stock-icon'] : '';

		$availibility = $product->get_availability();
		if ( ( is_shop() && ! $product_out_of_stock_icon ) && ( is_shop() && ! $product_in_stock_icon ) ) {
			return;
		}

		if ( is_shop() || is_product_category() || is_product_tag() || is_product() || ! $product->is_in_stock() ) {
			if ( $product_out_of_stock_icon ) {
				if ( ! $product->is_in_stock() ) {
					echo wc_get_stock_html( $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			}
			if ( $product_in_stock_icon ) {
				if ( $product->is_in_stock() ) {
					echo wc_get_stock_html( $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			}
		}

	}
}
add_action( 'woocommerce_before_shop_loop_item_title', 'ciyashop_product_availability', 10 );
add_action( 'ciyashop_before_product_actions', 'ciyashop_product_availability', 5 );

add_action( 'wp_head', 'ciyashop_woocommerce_catalog_mode' );
/**
 * Catalog Mode
 */
function ciyashop_woocommerce_catalog_mode() {

	if ( class_exists( 'WooCommerce' ) ) {
		global $ciyashop_options;

		if ( isset( $ciyashop_options['woocommerce_catalog_mode'] ) && 1 === (int) $ciyashop_options['woocommerce_catalog_mode'] ) {
			remove_action( 'ciyashop_product_actions', 'woocommerce_template_loop_add_to_cart', 10 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			remove_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
			remove_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );
			remove_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
			remove_action( 'woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30 );
			remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );
			remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
			remove_action( 'woocommerce_single_product_summary', 'ciyashop_product_sticky_content', 31 );
			remove_action( 'ciyashop_before_page_wrapper', 'ciyashop_wc_set_add_to_cart_element', 20 );
			remove_action( 'ciyashop_header_wootools', 'ciyashop_header_wootools_cart', 10 );
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
			remove_action( 'ciyashop_sticky_header_wootools', 'ciyashop_sticky_header_wootools_cart', 10 );
			add_filter( 'yith_wcwl_wishlist_params', 'ciyashop_wishlist_catalog_mode_cart_hide', 10, 5 );
			add_filter( 'woocommerce_is_purchasable', '__return_false' );
		}
	}
}

if ( ! function_exists( 'ciyashop_wishlist_catalog_mode_cart_hide' ) ) {
	/**
	 * Catalog Mode cart hide
	 *
	 * @param array $additional_params additional parameters.
	 * @param array $action action.
	 * @param array $action_params action parameters.
	 * @param int   $pagination pagination.
	 * @param int   $per_page per_page.
	 */
	function ciyashop_wishlist_catalog_mode_cart_hide( $additional_params, $action, $action_params, $pagination, $per_page ) {
		// Hide add to cart for wishlist.
		$additional_params['show_add_to_cart'] = false;
		return $additional_params;
	}
}

add_action( 'template_redirect', 'ciyashop_woocommerce_catalog_mode_redirection' );

/**
 * Catalog Mode On Redirect to shop page
 */
function ciyashop_woocommerce_catalog_mode_redirection() {
	if ( class_exists( 'WooCommerce' ) ) {
		global $ciyashop_options;
		if ( ( isset( $ciyashop_options['woocommerce_catalog_mode'] ) && 1 === (int) $ciyashop_options['woocommerce_catalog_mode'] ) || isset( $ciyashop_options['hide_price_for_guest_user'] ) && $ciyashop_options['hide_price_for_guest_user'] && ! is_user_logged_in() ) {
			if ( is_cart() || is_checkout() ) {
				wp_safe_redirect( get_permalink( wc_get_page_id( 'shop' ) ) );
			}
		}
	}
}

add_filter( 'woocommerce_get_price_html', 'ciyashop_hide_price', 99, 2 );
if ( ! function_exists( 'ciyashop_hide_price' ) ) {
	/**
	 * Hide Price
	 *
	 * @param int   $price price.
	 * @param array $product product.
	 */
	function ciyashop_hide_price( $price, $product ) {
		global $ciyashop_options;
		if ( isset( $ciyashop_options['woocommerce_catalog_mode'] ) && 1 === (int) $ciyashop_options['woocommerce_catalog_mode'] ) {
			if ( isset( $ciyashop_options['woocommerce_price_hide'] ) && 1 === (int) $ciyashop_options['woocommerce_price_hide'] ) {
				$price = '';
			}
		}
		return $price;
	}
}

// Set products per page.
add_filter( 'loop_shop_per_page', 'ciyashop_woocommerce_loop_shop_per_page' );
/**
 * Shop per page
 *
 * @param int $posts_per_page post per page.
 */
function ciyashop_woocommerce_loop_shop_per_page( $posts_per_page ) {
	global $ciyashop_options;
	if ( isset( $ciyashop_options['products_per_page'] ) && ! empty( $ciyashop_options['products_per_page'] ) ) {
		$posts_per_page = $ciyashop_options['products_per_page'];
	}

	$per_page = ( isset( $_GET['per_page'] ) ) ? (int) $_GET['per_page'] : '';
	if ( $per_page ) {
		$posts_per_page = $per_page;
	}

	return $posts_per_page;
}

/**
 * Others
 */
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_single_excerpt', 5 );


add_filter( 'woocommerce_placeholder_img_src', 'ciyashop_custom_woocommerce_placeholder_img_src' );
/**
 * Change the placeholder image
 *
 * @param string $src src of image.
 */
function ciyashop_custom_woocommerce_placeholder_img_src( $src ) {
	$src               = get_parent_theme_file_uri( '/images/product-placeholder.jpg' );
	$placeholder_image = get_option( 'woocommerce_placeholder_image', 0 );

	if ( ! empty( $placeholder_image ) && ! wp_attachment_is_image( $placeholder_image ) ) {
		$src = $placeholder_image;
	} elseif ( ! empty( $placeholder_image ) && wp_attachment_is_image( $placeholder_image ) ) {
		$size  = ( is_product() || ( isset( $_REQUEST['action'] ) && 'ciyashop_quick_view' === $_REQUEST['action'] ) ) ? 'woocommerce_single' : 'woocommerce_thumbnail';
		$image = wp_get_attachment_image_src( $placeholder_image, $size );
		if ( ! empty( $image[0] ) ) {
			$src = $image[0];
		}
	}

	return $src;
}
