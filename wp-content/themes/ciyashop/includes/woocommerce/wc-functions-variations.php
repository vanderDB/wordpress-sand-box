<?php
/**
 * WooCommerce Product Attributes Swatches Starts
 *
 * @package CiyaShop
 */

if ( ! function_exists( 'ciyashop_get_swatches' ) ) {
	/**
	 * Get swatches
	 *
	 * @param string $id .
	 * @param string $attr_name .
	 * @param string $options .
	 * @param string $available_variations .
	 */
	function ciyashop_get_swatches( $id, $attr_name, $options, $available_variations ) {
		$swatches = array();
		foreach ( $options as $key => $value ) {
			$swatch = ciyashop_has_swatch( $id, $attr_name, $value );
			if ( ! empty( $swatch ) ) {
				$swatches[ $key ] = $swatch;
			}
		}
		return $swatches;
	}
}

if ( ! function_exists( 'ciyashop_has_swatch' ) ) {
	/**
	 * Has swatch
	 *
	 * @param string $id .
	 * @param string $attr_name .
	 * @param string $value .
	 */
	function ciyashop_has_swatch( $id, $attr_name, $value ) {
		$swatches = array();
		$color    = '';
		$image    = '';
		$term     = get_term_by( 'slug', $value, $attr_name );

		if ( is_object( $term ) ) {
			$color = get_term_meta( $term->term_id, 'color_preview', true );
			$image = get_term_meta( $term->term_id, 'image_preview', true );
		}

		if ( ! empty( $color ) ) {
			$swatches['color'] = $color;
		}

		if ( ! empty( $image ) ) {
			$swatches['image'] = $image;
		}
		return $swatches;
	}
}

/**
 * Variable Product Display Layout In Product List
 */
function ciyashop_product_actions_add_variation_product_view() {
	global $ciyashop_options, $product;

	$id = $product->get_id();

	if ( isset( $ciyashop_options['hide_price_for_guest_user'] ) && $ciyashop_options['hide_price_for_guest_user'] && ! is_user_logged_in() ) {
		return;
	}

	if ( empty( $id ) || ! $product->is_type( 'variable' ) || ! isset( $ciyashop_options['cs_display_variation_on_list'] ) || false === (bool) $ciyashop_options['cs_display_variation_on_list'] ) {
		return;
	}
	?>
	<div class="ciyashop-product-variations-wrapper ciyashop-swatches" data-id="<?php echo esc_attr( $id ); ?>">
		<?php
		if ( isset( $ciyashop_options['product_hover_style'] ) && 'standard-quick-shop' !== (bool) $ciyashop_options['product_hover_style'] ) {
			?>
			<span class="ciyashop-variations-close"><i class="fa fa-close"></i></span>
			<?php
		}
		?>
		<div class="ciyashop-variations-form">
		<?php
		if ( 'standard-quick-shop' === $ciyashop_options['product_hover_style'] ) {
			ciyashop_load_variations( $id );
		}
		?>
		</div>
	</div>
	<?php
}

if ( ! function_exists( 'ciyashop_load_variations' ) ) {
	add_action( 'wp_ajax_ciyashop_load_variations', 'ciyashop_load_variations' );
	add_action( 'wp_ajax_nopriv_ciyashop_load_variations', 'ciyashop_load_variations' );
	/**
	 * Load Variabtion On Ajax Call On Product Listing Page
	 *
	 * @param string $id .
	 */
	function ciyashop_load_variations( $id = false ) {
		global $post, $sitepress;

		if ( wp_doing_ajax() ) {
			$ciyashop_nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';
			if ( ! wp_verify_nonce( $ciyashop_nonce, 'ciyashop_nonce' ) ) {
				return;
			}
		}

		if ( isset( $_POST['id'] ) ) {
			$id = (int) sanitize_text_field( wp_unslash( $_POST['id'] ) );
		}

		if ( ! $id || ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$lang = isset( $_GET['lang'] ) ? sanitize_text_field( wp_unslash( $_GET['lang'] ) ) : '';

		if ( defined( 'ICL_LANGUAGE_CODE' ) && $lang && isset( $sitepress ) ) {
			$sitepress->switch_lang( $lang, true );
		}

		$args           = array(
			'post__in'  => array( $id ),
			'post_type' => 'product',
		);
		$variable_posts = get_posts( $args );

		foreach ( $variable_posts as $post ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride
			setup_postdata( $post );
			if ( ! is_product() ) {
				woocommerce_template_single_add_to_cart();
			}
		endforeach;

		if ( wp_doing_ajax() ) {
			wp_die();
		}
	}
}

add_action( 'wp_ajax_ciyashop_ajax_add_to_cart_action', 'ciyashop_ajax_add_to_cart_action' );
add_action( 'wp_ajax_nopriv_ciyashop_ajax_add_to_cart_action', 'ciyashop_ajax_add_to_cart_action' );
if ( ! function_exists( 'ciyashop_ajax_add_to_cart_action' ) ) {
	/**
	 * Add To Cart Ajax Call On Product Variations On Product Listing
	 */
	function ciyashop_ajax_add_to_cart_action() {
		// Get messages.
		ob_start();
		wc_print_notices();
		$notices = ob_get_clean();

		// Get mini cart.
		ob_start();
		woocommerce_mini_cart();
		$mini_cart = ob_get_clean();

		// Fragments and mini cart are returned.
		ob_start();
		$cart_count = WC()->cart->get_cart_contents_count();
		if ( $cart_count > 9 ) {
			$cart_count = '9+';
		}
		?>
		<span class="cart-count count"><?php echo esc_html( $cart_count ); ?></span>
		<?php

		$fragments['div.widget_shopping_cart_content'] = '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>';
		$fragments['.woo-tools-cart .cart-count']      = ob_get_clean();

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

		$data = array(
			'notices'   => $notices,
			'fragments' => $fragments,
			/**
			 * Filters WooCommers's Add to Cart hash.
			 *
			 * @param strin    $hash      Add to Cart hash.
			 *
			 * @visible false
			 * @ignore
			 */
			'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( wp_json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() ),
		);

		wp_send_json( $data );
		wp_die();
	}
}

if ( ! function_exists( 'ciyashop_attr_variation_list' ) ) {
	/**
	 * Show attribute variations list
	 *
	 * @param string $attribute_name .
	 */
	function ciyashop_attr_variation_list( $attribute_name = false ) {
		global $product, $ciyashop_options;

		$id = $product->get_id();

		if ( empty( $id ) || ! $product->is_type( 'variable' ) ) {
			return false;
		}

		if ( ! $attribute_name ) {
			$attribute_name = ( isset( $ciyashop_options['cs_grid_swatches_attribute'] ) && ! empty( $ciyashop_options['cs_grid_swatches_attribute'] ) ) ? $ciyashop_options['cs_grid_swatches_attribute'] : null;
		}

		if ( empty( $attribute_name ) || null === (bool) $attribute_name ) {
			return false;
		}

		$available_variations = $product->get_available_variations();
		if ( empty( $available_variations ) ) {
			return false;
		}
		$attributes_to_show = ciyashop_get_attr_variations( $attribute_name, $available_variations, false, $id );

		if ( empty( $attributes_to_show ) ) {
			return false;
		}
		ob_start();
		?>
		<div class="cs-variations-on-grid">
		<?php
			$terms                = wc_get_product_terms( $product->get_id(), $attribute_name, array( 'fields' => 'slugs' ) );
			$swatches_to_show_tmp = $attributes_to_show;
			$attributes_to_show   = array();

		foreach ( $terms as $id => $slug ) {
			if ( ! isset( $swatches_to_show_tmp[ $slug ] ) ) {
				continue;
			}
			$attributes_to_show[ $slug ] = $swatches_to_show_tmp[ $slug ];
		}

		foreach ( $attributes_to_show as $key => $swatch ) {
			$style = '';
			$data  = '';
			$class = 'ciyashop-grid-swatch ';

			if ( ( ! empty( $swatch['color'] ) && ! empty( $swatch['image'] ) ) || ( empty( $swatch['color'] ) && ! empty( $swatch['image'] ) ) ) {
				$img_url = wp_get_attachment_image_src( $swatch['image'], 'ciyashop-brand-logo' );
				$style   = 'background-image: url(' . $img_url[0] . ');';
				$class  .= 'swatch-image ';
			} elseif ( ! empty( $swatch['color'] ) ) {
				$style  = 'background-color:' . $swatch['color'] . ';';
				$class .= 'swatch-colored ';
			} else {
				$class .= 'ciyashop-text-only ';
			}

			if ( isset( $swatch['image_src'] ) ) {
				$class .= 'ciyashop-attr-has-image ';
				$data  .= 'data-image-src="' . esc_attr( $swatch['image_src'] ) . '"';
				$data  .= ' data-image-srcset="' . esc_attr( $swatch['image_srcset'] ) . '"';
				$data  .= ' data-image-sizes="' . esc_attr( $swatch['image_sizes'] ) . '"';

				if ( ! $swatch['is_in_stock'] ) {
					$class .= 'attribute-out-of-stock ';
				}
			}
			$data .= ' data-toggle="tooltip"';
			$term  = get_term_by( 'slug', $key, $attribute_name );
			?>

				<a class="<?php echo esc_attr( $class ); ?>" style="<?php echo esc_attr( $style ); ?>" title="<?php echo esc_attr( ucwords( $term->name ) ); ?>" <?php echo $data; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>>
					<?php echo esc_html( ucwords( $term->name ) ); ?>
				</a>
			<?php
		}
		?>
		</div>
		<?php
		return ob_get_clean();
	}
}

if ( ! function_exists( 'ciyashop_get_attr_variations' ) ) {
	/**
	 * Get product options variations
	 *
	 * @param string $attribute_name .
	 * @param string $available_variations .
	 * @param bool   $option .
	 * @param bool   $product_id .
	 */
	function ciyashop_get_attr_variations( $attribute_name, $available_variations, $option = false, $product_id = false ) {
		$attributes_to_show = array();

		foreach ( $available_variations as $key => $variation ) {
			$option_variation = array();

			$attr_key = 'attribute_' . $attribute_name;
			if ( ! isset( $variation['attributes'][ $attr_key ] ) ) {
				return;
			}

			$val = $variation['attributes'][ $attr_key ];
			if ( ! empty( $variation['image']['src'] ) ) {
				if ( isset( $variation['image']['thumb_src'] ) && ! empty( $variation['image']['thumb_src'] ) ) {
					$image_srcset = $variation['image']['srcset'];
					$image_srcset = $image_srcset . ', ' . $variation['image']['thumb_src'] . ' ' . $variation['image']['thumb_src_w'] . 'w';
				} else {
					$image_srcset = $variation['image']['srcset'];
				}

				$option_variation = array(
					'variation_id' => $variation['variation_id'],
					'image_src'    => isset( $variation['image']['thumb_src'] ) ? $variation['image']['thumb_src'] : $variation['image']['src'],
					'image_srcset' => $image_srcset,
					'image_sizes'  => $variation['image']['sizes'],
					'is_in_stock'  => $variation['is_in_stock'],
				);
			}

			// Get only one variation by attribute option value.
			if ( $option ) {
				if ( (string) $val !== (string) $option ) {
					continue;
				} else {
					return $option_variation;
				}
			} else {
				// Get all variations with swatches to show by attribute name.
				$swatch                     = ciyashop_has_swatch( $product_id, $attribute_name, $val );
				$attributes_to_show[ $val ] = array_merge( $swatch, $option_variation );
			}
		}
		return $attributes_to_show;
	}
}

/*************************************************************
::		WooCommerce Product Attributes Swatches Ends 	    ::
************************************************************* */
