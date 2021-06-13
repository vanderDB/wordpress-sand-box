<?php
/**
 * Single product
 *
 * @package CiyaShop
 */

add_action( 'woocommerce_before_single_product', 'ciyashop_woocommerce_init_single_product' );
/**
 * Woocommerce init single product
 */
function ciyashop_woocommerce_init_single_product() {
	global $ciyashop_options;

	// Show Hide Short Descriptions.
	if ( isset( $ciyashop_options['product-short-description'] ) && 0 === (int) $ciyashop_options['product-short-description'] ) {
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
	}

	// Show Hide Hot.
	if ( isset( $ciyashop_options['product-short-description'] ) && 0 === (int) $ciyashop_options['product-short-description'] ) {
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
	}
}

add_action( 'woocommerce_single_product_summary', 'ciyashop_product_size_guide', 25 );
/**
 * Show Size Guide Image for product
 */
function ciyashop_product_size_guide() {
	global $post;

	$size_guide_image_data = '';
	$size_guide_type       = get_post_meta( $post->ID, 'select_size_guides', true );
	$size_guide_id         = get_post_meta( $post->ID, 'size_guide_tables', true );
	$size_guide_text       = __( 'Size Guide', 'ciyashop' );
	$size_guide_text       = apply_filters( 'ciyashop_size_guide_text', $size_guide_text );

	if ( ! empty( $size_guide_type ) && 'table' === (string) $size_guide_type ) {
		?>
		<div id="pgs_sizeguidetable" class="pgs-content-popup mfp-hide pgs-sizeguide">
			<?php
			$size_guide_post         = get_post( $size_guide_id ); // specific post.
			$size_guide_post_content = apply_filters( 'the_content', $size_guide_post->post_content );

			$size_guide_table = get_post_meta( $size_guide_id, 'ciyashop_sguide', true );
			?>
			<h4 class="pgs-sizeguide-title"><?php echo get_the_title( $size_guide_id ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></h4>
			<?php
			if ( ! empty( $size_guide_post_content ) ) {
				echo wp_kses_post( $size_guide_post_content );
			}
			?>
			<div class="pgs-sizeguide-table table-responsive">
			<?php
			if ( $size_guide_table ) {
				echo '<table border="0" class="table">';
				foreach ( $size_guide_table as $tr_key => $tr_value ) {
					if ( 0 === (int) $tr_key ) {
						echo '<thead>';
							echo '<tr>';
						foreach ( $tr_value as $th ) {
							echo '<th>';
								echo esc_html( $th );
							echo '</th>';
						}
							echo '</tr>';
						echo '</thead>';
					} else {
						echo '<tbody>';
							echo '<tr>';
						foreach ( $tr_value as $th ) {
							echo '<td>';
								echo esc_html( $th );
							echo '</td>';
						}
							echo '</tr>';
						echo '</tbody>';
					}
				}
				echo '</table>';
			}
			?>
			</div>
		</div>
		<div class="product-size-guide">
			<a class="open-product-size-guide pgs-sizeguide-popup pgs-sizeguide-btn" href="#pgs_sizeguidetable"><?php echo esc_html( $size_guide_text ); ?></a>
		</div>
		<?php
	} else {

		$size_guide_image_id   = get_post_meta( $post->ID, 'size_guide_image', true );
		$size_guide_image_data = ( ! empty( $size_guide_image_id ) ) ? wp_get_attachment_image_src( $size_guide_image_id, 'full' ) : '';
		$mfp_options_args      = array(
			'type' => 'image',
		);

		$mfp_options = '';
		if ( is_array( $mfp_options_args ) && ! empty( $mfp_options_args ) ) {
			$mfp_options = wp_json_encode( $mfp_options_args );
		}

		if ( ! empty( $size_guide_image_data ) ) {
			?>
			<div class="product-size-guide">
				<a class="open-product-size-guide mfp-popup-link" href="<?php echo esc_url( $size_guide_image_data[0] ); ?>" data-mfp_options=<?php echo esc_attr( $mfp_options ); ?>>
					<?php esc_html_e( 'Size Guide', 'ciyashop' ); ?>
				</a>
			</div>
			<?php
		}
	}
}

add_action( 'woocommerce_single_product_summary', 'ciyashop_product_sticky_content', 31 );
/**
 * Add sticky content on product single page
 */
function ciyashop_product_sticky_content() {
	global $product, $ciyashop_options;

	$product_sticky_content = isset( $ciyashop_options['product_sticky_content'] ) ? $ciyashop_options['product_sticky_content'] : '';

	if ( ! $product_sticky_content ) {
		return;
	}

	if ( $product->is_type( 'variable' ) || $product->is_type( 'grouped' ) ) {
		return;
	}

	?>
	<div class="woo-product-sticky-content">
		<div class="woo-product-title_sticky">
			<h5 class="woo-product_title"><?php the_title(); ?></h5>
		</div>
		<div class="woo-product-cart_sticky">
		<?php woocommerce_template_loop_add_to_cart(); ?>
		</div>
	</div>
	<?php
}

/**********************************************************
 *
 * Single Product Container Width
 *
 ********************************************************* */
add_filter( 'ciyashop_content_container_classes', 'ciyashop_product_page_width_class' );

/**********************************************************
 *
 * Previous/Next Links
 *
 ********************************************************* */
add_action( 'woocommerce_after_main_content', 'ciyashop_single_product_nav' );


/**********************************************************
 *
 * Product Page Style
 *
 ********************************************************* */
add_filter( 'wc_get_template_part', 'ciyashop_single_product_style_template', 10, 3 );
add_filter( 'post_class', 'ciyashop_single_product_style_class', 99, 3 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
add_action( 'woocommerce_before_single_product_summary', 'ciyashop_show_product_images', 20 );
add_action( 'ciyashop_content_top', 'ciyashop_single_product_style_wide_image_gallery', 30 );
add_filter( 'wp_get_attachment_image_attributes', 'ciyashop_single_product_images_srcset', 10, 3 );
add_filter( 'ciyashop_product_images_wrapper_classes', 'ciyashop_single_product_image_gallery_classes' );
add_filter( 'ciyashop_product_image_gallery_wrapper_classes', 'ciyashop_single_product_image_gallery_wrapper_classes' );

/**********************************************************
 *
 * Single Product Actions
 *
 ********************************************************* */
add_action( 'woocommerce_single_product_summary', 'ciyashop_product_summary_actions_start', 30 );
add_action( 'woocommerce_single_product_summary', 'ciyashop_product_summary_actions_end', 36 );

/**********************************************************
 *
 * Tabs Customization
 *
 ********************************************************* */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
add_action( 'woocommerce_before_single_product', 'ciyashop_product_tabs_position', 9 );

/**
 * Product tabs position
 */
function ciyashop_product_tabs_position() {

	$style = ciyashop_single_product_style();

	if ( 'sticky_gallery' === (string) $style ) {
		add_action( 'ciyashop_after_single_product_summary', 'ciyashop_product_tabs_output', 10 );
	} else {
		add_action( 'woocommerce_after_single_product_summary', 'ciyashop_product_tabs_output', 10 );
	}
}

/**********************************************************
 *
 * Product Video
 *
 ********************************************************* */
add_action( 'ciyashop_product_gallery_buttons', 'ciyashop_product_gallery_button_zoom', 10 );
add_action( 'ciyashop_product_gallery_buttons', 'ciyashop_product_gallery_button_video', 20 );
add_action( 'ciyashop_product_gallery_buttons', 'ciyashop_product_gallery_button_smart_product_image', 30 );

/**********************************************************
 *
 * Product Video
 *
 ********************************************************* */
add_action( 'woocommerce_after_main_content', 'ciyashop_product_360_gallery_output', 5 );


/**********************************************************
 *
 * Related Products
 *
 ********************************************************* */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
add_action( 'woocommerce_after_single_product_summary', 'ciyashop_woocommerce_output_related_products', 20 );
add_filter( 'woocommerce_output_related_products_args', 'ciyashop_woocommerce_output_related_products_args' );

/**********************************************************
 *
 * Cross Sells Products
 *
 ********************************************************* */
// show_up_sells.
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product_summary', 'ciyashop_woocommerce_upsell_display', 15 );
add_filter( 'woocommerce_upsells_total', 'ciyashop_woocommerce_upsells_total' );

/**********************************************************
 *
 * Mix
 *
 ********************************************************* */
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
add_action( 'ciyashop_before_product_gallery_wrapper', 'woocommerce_show_product_sale_flash', 10 );

add_action( 'ciyashop_product_gallery_buttons', 'ciyashop_product_gallery_button_zoom', 10 );
add_action( 'ciyashop_product_gallery_buttons', 'ciyashop_product_gallery_button_zoom', 10 );
add_action( 'ciyashop_product_gallery_buttons', 'ciyashop_product_gallery_button_zoom', 10 );

/**
 * Product summary actions start
 */
function ciyashop_product_summary_actions_start() {
	?>
	<div class="product-summary-actions">
	<?php
}
/**
 * Product summary actions end
 */
function ciyashop_product_summary_actions_end() {
	?>
	</div>
	<?php
}
/**
 * Product tabs output
 */
function ciyashop_product_tabs_output() {

	$product_tab_layout = ciyashop_product_tab_layout();

	if ( 'accordion' === (string) $product_tab_layout ) {
		add_action( 'ciyashop_product_tabs_content', 'ciyashop_product_tabs_accordion', 10 );
	} else {
		add_action( 'ciyashop_product_tabs_content', 'ciyashop_product_tabs', 10 );
	}

	$tabs_args = array(
		'tab_layout'       => $product_tab_layout,
		'tab_layout_class' => ( 'default_center' === (string) $product_tab_layout ) ? 'default tab-align-center' : false,
	);

	/**
	 * Hook: ciyashop_product_tabs_content.
	 *
	 * @param array    $tabs_args       Tabs arguments.
	 *
	 * @visible true
	 */
	do_action( 'ciyashop_product_tabs_content', $tabs_args );
}

if ( ! function_exists( 'ciyashop_product_tabs' ) ) {

	/**
	 * Output the product tabs (classic).
	 *
	 * @param string $tabs_args .
	 * @subpackage  Product/Tabs
	 */
	function ciyashop_product_tabs( $tabs_args ) {
		wc_get_template( 'single-product/tabs/tabs.php', $tabs_args );
	}
}

if ( ! function_exists( 'ciyashop_product_tabs_accordion' ) ) {

	/**
	 * Output the product tabs (classic).
	 *
	 * @param string $tabs_args .
	 * @subpackage  Product/Tabs
	 */
	function ciyashop_product_tabs_accordion( $tabs_args ) {
		wc_get_template( 'single-product/tabs/tabs-accordion.php', $tabs_args );
	}
}
/**
 * Product page width class
 *
 * @param string $classes .
 */
function ciyashop_product_page_width_class( $classes ) {

	if ( is_product() ) {
		$product_page_width = ciyashop_product_page_width();

		/**
		 * Unset 'container-fluid' class
		 */
		$cf_index = array_search( 'container-fluid', $classes, true );
		if ( $cf_index ) {
			unset( $classes[ $cf_index ] );
		}

		/**
		 * Unset 'container' class
		 */
		$c_index = array_search( 'container', $classes, true );
		if ( $c_index ) {
			unset( $classes[ $c_index ] );
		}

		if ( 'wide' === (string) $product_page_width ) {
			$classes = array( 'container-fluid' );
		} else {
			$classes = array( 'container' );
		}
	}

	return $classes;
}
/**
 * Single product style template
 *
 * @param string $template .
 * @param string $slug .
 * @param string $name .
 */
function ciyashop_single_product_style_template( $template, $slug, $name ) {

	if ( 'content' === (string) $slug && 'single-product' === (string) $name ) {
		global $ciyashop_options;

		$product_page_style = ciyashop_single_product_style();

		if ( 'classic' !== (string) $product_page_style ) {
			$name = 'single-product-' . $product_page_style;
		}

		$template = '';

		// Look in yourtheme/slug-name.php and yourtheme/woocommerce/slug-name.php.
		if ( $name && ! WC_TEMPLATE_DEBUG_MODE ) {
			$template = locate_template( array( "{$slug}-{$name}.php", WC()->template_path() . "{$slug}-{$name}.php" ) );
		}

		// Get default slug-name.php.
		if ( ! $template && $name && file_exists( WC()->plugin_path() . "/templates/{$slug}-{$name}.php" ) ) {
			$template = WC()->plugin_path() . "/templates/{$slug}-{$name}.php";
		}

		// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/woocommerce/slug.php.
		if ( ! $template && ! WC_TEMPLATE_DEBUG_MODE ) {
			$template = locate_template( array( "{$slug}.php", WC()->template_path() . "{$slug}.php" ) );
		}
	}

	return $template;
}

/**
 * Product Page Style
 *
 * @param string $classes .
 * @param string $class .
 * @param string $post_id .
 */
function ciyashop_single_product_style_class( $classes, $class, $post_id ) {
	global $post, $product, $ciyashop_options;

	if ( ! is_product() ) {
		return $classes;
	}

	$product_page_style = ciyashop_single_product_style();

	$class = 'product_page_style-' . $product_page_style;

	/**
	 * Filters the class of product style.
	 *
	 * @param string    $class          Product page style class.
	 * @param array     $classes        An array of post classes.
	 * @param array     $class          An array of additional classes added to the post.
	 * @param int       $post_id        The post ID.
	 *
	 * @visible false
	 * @ignore
	 */
	$class = apply_filters( 'ciyashop_single_product_style_class', $class, $classes, $class, $post_id );

	$classes[] = $class;

	return $classes;
}

if ( ! function_exists( 'ciyashop_show_product_images' ) ) {

	/**
	 * Output the product image before the single product summary.
	 *
	 * @subpackage  Product
	 */
	function ciyashop_show_product_images() {

		wc_get_template( 'single-product/product-image.php' );

	}
}
/**
 * Single product style wide image gallery
 */
function ciyashop_single_product_style_wide_image_gallery() {
	$style = ciyashop_single_product_style();

	// Show Hide Sale.
	add_filter( 'woocommerce_sale_flash', 'ciyashop_sale_flash_label', 10, 3 );

	// Show Hide Featured.
	add_filter( 'ciyashop_featured', 'ciyashop_featured_label', 10, 3 );

	if ( 'wide_gallery' === (string) $style && is_product() ) {
		add_action( 'ciyashop_content_top', 'ciyashop_show_product_images', 35 );
	}
}
/**
 * Single product images srcset
 *
 * @param array $attr .
 * @param array $attachment .
 * @param array $size .
 */
function ciyashop_single_product_images_srcset( $attr, $attachment, $size ) {

	if ( is_singular( 'product' ) ) {
		unset( $attr['srcset'] );
		unset( $attr['sizes'] );
	}

	return $attr;
}
/**
 * Single product image gallery classes
 *
 * @param string $classes .
 */
function ciyashop_single_product_image_gallery_classes( $classes ) {
	global $product;
	$attachment_ids = $product->get_gallery_image_ids();

	$style              = ciyashop_single_product_style();
	$thumbnail_position = ciyashop_single_product_thumbnail_position();

	if ( 'wide_gallery' === (string) $style ) {
		$classes[] = 'ciyashop-gallery-style-wide_gallery';
	} else {
		$classes[] = 'ciyashop-gallery-style-default';

		if ( ! empty( $attachment_ids ) || has_post_thumbnail( $product->get_id() ) ) {
			$classes[] = "ciyashop-gallery-thumb_position-$thumbnail_position";
			$classes[] = 'ciyashop-gallery-thumb_vh-' . ( 'bottom' === (string) $thumbnail_position ? 'horizontal' : 'vertical' );
		}
	}

	return $classes;
}
/**
 * Single product image gallery wrapper classes
 *
 * @param string $classes .
 */
function ciyashop_single_product_image_gallery_wrapper_classes( $classes ) {

	$style = ciyashop_single_product_style();

	if ( 'wide_gallery' === (string) $style ) {
		$classes[] = 'owl-carousel';
		$classes[] = 'owl-theme';
	}

	return $classes;
}
/**
 * Woocommerce output related products
 */
function ciyashop_woocommerce_output_related_products() {

	global $ciyashop_options;

	$related_products_stat = false;

	if ( empty( $ciyashop_options ) ) {
		$related_products_stat = true;
	} elseif ( isset( $ciyashop_options['show_related_products'] ) && 1 === (int) $ciyashop_options['show_related_products'] ) {
		$related_products_stat = true;
	}
	if ( $related_products_stat ) {
		woocommerce_output_related_products();
	}
}
/**
 * Woocommerce output related products args
 *
 * @param array $args .
 */
function ciyashop_woocommerce_output_related_products_args( $args ) {
	global $ciyashop_options;

	$related_products_per_page = isset( $ciyashop_options['related_products_per_page'] ) && ! empty( $ciyashop_options['related_products_per_page'] ) ? $ciyashop_options['related_products_per_page'] : 6;

	$args['posts_per_page'] = $related_products_per_page;

	return $args;
}
/**
 * Woocommerce upsell display
 */
function ciyashop_woocommerce_upsell_display() {
	global $ciyashop_options;

	$woocommerce_upsell_display = false;

	if ( empty( $ciyashop_options ) ) {
		$woocommerce_upsell_display = true;
	} elseif ( isset( $ciyashop_options['show_up_sells'] ) && 1 === (int) $ciyashop_options['show_up_sells'] ) {
		$woocommerce_upsell_display = true;
	}
	if ( $woocommerce_upsell_display ) {
		woocommerce_upsell_display();
	}
}
/**
 * Woocommerce upsells total
 *
 * @param string $posts_per_page .
 */
function ciyashop_woocommerce_upsells_total( $posts_per_page ) {
	global $ciyashop_options;

	$up_sells_products_per_page = isset( $ciyashop_options['up_sells_products_per_page'] ) && ! empty( $ciyashop_options['up_sells_products_per_page'] ) ? $ciyashop_options['up_sells_products_per_page'] : 6;

	$posts_per_page = $up_sells_products_per_page;

	return $posts_per_page;
}
/**
 * Product gallery button zoom
 */
function ciyashop_product_gallery_button_zoom() {
	global $post, $product;

	$gallery_images = $product->get_gallery_image_ids();

	if ( ! has_post_thumbnail( $post ) && empty( $gallery_images ) ) {
		return;
	}

	$link_html_prefix = '<div class="ciyashop-product-gallery_button ciyashop-product-gallery_button-zoom">';
	$link_html        = '<a href="#" class="ciyashop-product-gallery_button-link-zoom"><i class="fa fa-arrows-alt"></i></a>';
	$link_html_suffix = '</div>';

	/**
	 * Filters the link of zoom button on product gallery.
	 *
	 * @param string    $link_html               Link of product gallery zoom button.
	 * @param WP_Post   $post                    The Post object.
	 *
	 * @visible false
	 * @ignore
	 */
	$link_html = $link_html_prefix . apply_filters( 'ciyashop_product_gallery_button_zoom', $link_html, $post ) . $link_html_suffix;

	echo wp_kses( $link_html, ciyashop_allowed_html( array( 'a', 'div', 'i' ) ) );
}
/**
 * Product gallery button video
 */
function ciyashop_product_gallery_button_video() {
	global $post;

	$link_html = '';

	$product_video = ciyashop_get_product_video();

	if ( $product_video ) {
		$link_html .= '<div class="ciyashop-product-gallery_button ciyashop-product-gallery_button-video">';
		$link_html .= '<a href="' . esc_url( $product_video['video_link'] ) . '" class="ciyashop-product-gallery_button-link-video ' . esc_attr( $product_video['video_classes'] ) . '"><i class="fa fa-video-camera"></i></a>';
		$link_html .= '</div>';
	}

	/**
	 * Filters the link of video button on product gallery.
	 *
	 * @param string    $link_html               Link of product gallery video button.
	 * @param WP_Post   $post                    The Post object.
	 *
	 * @visible false
	 * @ignore
	 */
	$link_html = apply_filters( 'ciyashop_product_gallery_button_video', $link_html, $post );

	if ( ! empty( $link_html ) ) {
		echo wp_kses( $link_html, ciyashop_allowed_html( array( 'a', 'div', 'i' ) ) );
	}
}
/**
 * Product gallery button smart product image
 */
function ciyashop_product_gallery_button_smart_product_image() {
	global $post, $ciyashop_options;
	$product_360_data = ciyashop_get_product_360();

	// When smart image view plugin install.
	if ( $product_360_data ) {
		?>
		<div class="ciyashop-product-gallery_button ciyashop-product-gallery_button-360degree">
			<a id="<?php echo esc_attr( $product_360_data['id'] ); ?>" href="<?php echo esc_url( $product_360_data['link'] ); ?>" class="ciyashop-product-gallery_button-link-360degree">
				<i class="fa fa-repeat"></i>
			</a>
		</div>
		<?php

	} else {

		if ( ! isset( $ciyashop_options['smart-product'] ) || ( isset( $ciyashop_options['smart-product'] ) && ! $ciyashop_options['smart-product'] ) ) {
			return false;
		}

		$ciyashop_smart_product = get_post_meta( $post->ID, 'ciyashop_smart_product_id', true );
		if ( empty( $ciyashop_smart_product ) ) {
			return false;
		}
		?>
		<div id="smart_product_popup" class="mfp-hide">
			<div
				id="ciyashop-360-view"
				class="cloudimage-360"
				data-image-list='[
					<?php
					$i = 0;
					foreach ( $ciyashop_smart_product as $ciyashop_smart_product_id ) :
						$image     = wp_get_attachment_image_src( $ciyashop_smart_product_id, 'full' );
						$image_url = $image[0];
						if ( $i > 0 ) {
							echo ','; }
						?>
							"<?php echo esc_url( $image_url ); ?>"
							<?php
							$i++;
					endforeach;
					?>
				]'
				data-bottom-circle-offset="2"
				data-keys
				data-autoplay
				data-ratio="0.265">
				<button class="cloudimage-360-prev"><i class="fas fa-angle-left" aria-hidden="true"></i></button>
				<button class="cloudimage-360-next"><i class="fas fa-angle-right" aria-hidden="true"></i></button>
				<button title="<?php echo esc_attr__( 'Close', 'ciyashop' ); ?>" type="button" class="mfp-close"><i class="fas fa-times" aria-hidden="true"></i></button>
			</div>
		</div>
		<div class="ciyashop-product-gallery_button ciyashop-product-gallery_button-smart-product-image">
			<a href="#smart_product_popup" class="ciyashop-product-gallery_button-link-smart-product-image smart_product_open">
				<i class="fas fa-sync-alt"></i>
			</a>
		</div>
		<?php
	}
}
/**
 * Product 360 gallery output
 */
function ciyashop_product_360_gallery_output() {

	$product_360_data = ciyashop_get_product_360();

	if ( $product_360_data ) {
		?>
		<div class="ciyashop-smart-product-wrapper">
			<?php wc_get_template( 'custom/threesixtyslider.php' ); ?>
		</div>
		<?php
	}
}
/**
 * Get product 360
 */
function ciyashop_get_product_360() {
	global $post;

	if ( ! $post ) {
		return false;
	}

	if ( ! is_product() ) {
		return false;
	}

	global $smart_product;
	$smart_product = get_post_meta( $post->ID, 'smart_product_meta', true );

	if ( ! $smart_product ) {
		return;
	}

	$smart_product['fullscreen'] = true;
	$smart_product['show']       = false;
	$smart_product['360id']      = $post->ID . '_' . $smart_product['id'];

	if ( 'color-ciyashop' === (string) $smart_product['color'] ) {
		$smart_product['style'] = 'style-none';
	}

	$product_360_data = false;

	if ( isset( $smart_product['id'] ) && ! empty( $smart_product['id'] ) ) {

		if ( ! class_exists( 'ThreeSixtySlider' ) ) {
			return $product_360_data;
		}

		global $threesixtyslider;
		$threesixtyslider = new ThreeSixtySlider( $smart_product );

		if ( $threesixtyslider->imagesCount > 0 ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			ob_start();
			wc_get_template( 'custom/threesixtyslider.php' );
			$product_360_content = ob_get_clean();
			ob_start();
			$threesixtyslider->ID();

			$smart_product_id = ob_get_clean();

			$product_360_data = array(
				'content' => $product_360_content,
				'id'      => 'threesixty-anchor-' . $smart_product['360id'],
				'link'    => '#threesixty-slider-' . $smart_product['360id'],
			);
		}
	}

	return $product_360_data;
}
/**
 * Get product video
 */
function ciyashop_get_product_video() {
	global $post;

	if ( ! $post ) {
		return false;
	}

	if ( ! is_product() ) {
		return false;
	}

	$video_stat          = false;
	$video_link          = '';
	$video_popup_classes = array(
		'product-video-popup-link',
	);

	$video_source = get_post_meta( $post->ID, 'product_video_source', true );
	if ( $video_source && 'internal' === (string) $video_source ) {
		$video_internal = get_post_meta( $post->ID, 'product_video_internal', true );
		if ( $video_internal ) {
			$video_stat            = true;
			$video_link            = wp_get_attachment_url( $video_internal );
			$video_popup_classes[] = 'product-video-popup-link-html5';
		}
	} elseif ( 'external' === (string) $video_source && $video_source ) {
		$ext_video_html = get_post_meta( $post->ID, 'product_video_external', true );
		if ( $ext_video_html ) {
			$ext_video_url = get_post_meta( $post->ID, 'product_video_external', true );

			$ext_video_data = ciyashop_get_oembed_data( $ext_video_url );
			$video_stat     = true;

			// add extra params to iframe src.
			$params = array(
				'controls' => 0,
				'hd'       => 1,
				'autohide' => 1,
			);

			if ( is_object( $ext_video_data ) && ( 'YouTube' === (string) $ext_video_data->provider_name || 'Vimeo' === (string) $ext_video_data->provider_name ) ) {
				$video_link = $ext_video_url;
			} else {
				if ( 'Facebook' === (string) $ext_video_data->provider_name ) {
					$ext_video_src = 'https://www.facebook.com/plugins/video.php';
					$params        = array(
						'href'            => rawurlencode( $ext_video_data->url ),
						'autoplay'        => 1,
						'show_text'       => 0,
						'allowfullscreen' => 1,
					);
				} else {
					// use preg_match to find iframe src.
					preg_match( '/src="(.+?)"/', $ext_video_html, $ext_video_matches );
					$ext_video_src = $ext_video_matches[1];
				}
				if ( 'Dailymotion' === (string) $ext_video_data->provider_name ) {
					$params = array(
						'autoplay'         => 1,
						'endscreen-enable' => 0,
					);
				}

				$ext_video_src_new = add_query_arg( $params, $ext_video_src );
				$video_link        = $ext_video_src_new;
			}

			$video_popup_classes[] = 'product-video-popup-link-oembed';
			$video_popup_classes[] = 'product-video-popup-link-oembed-' . sanitize_title( $ext_video_data->provider_name );
		}
	}

	$video_popup_classes = ciyashop_class_builder( $video_popup_classes );

	if ( $video_stat ) {
		$video_data = array(
			'video_link'    => $video_link,
			'video_classes' => $video_popup_classes,
		);
	} else {
		$video_data = false;
	}

	/**
	 * Filters the product video data.
	 *
	 * @param array    $video_data      An array of product video data.
	 * @param WP_Post  $post            The Post object.
	 *
	 * @visible false
	 * @ignore
	 */
	$video_data = apply_filters( 'ciyashop_product_video', $video_data, $post );

	return $video_data;
}
/**
 * Ciyashop get oembed data
 *
 * @param string $url .
 */
function ciyashop_get_oembed_data( $url = '' ) {

	if ( empty( $url ) ) {
		return;
	}

	$oembed      = new WP_oEmbed();
	$oembed_data = $oembed->get_data( $url );

	return $oembed_data;
}

add_filter( 'smart_product_image_option_color', 'ciyashop_extend_smart_product_image_option_color', 10, 2 );
/**
 * Extend smart product image option color
 *
 * @param string $html .
 * @param string $active_color .
 */
function ciyashop_extend_smart_product_image_option_color( $html, $active_color ) {

	$colors = array(
		'color-ciyashop' => esc_html__( 'CiyaShop', 'ciyashop' ),
	);

	foreach ( $colors as $color_k => $color_v ) {
		$html .= '<option ' . selected( $color_k, esc_attr( $active_color ) ) . ' value="' . $color_k . '">' . $color_v . '</option>';
	}

	return $html;
}

add_filter( 'smart_product_image_option_color_notice', 'ciyashop_extend_smart_product_image_option_color_notice', 10, 2 );
/**
 * Extend smart product image option color notice
 *
 * @param string $html .
 * @param string $active_color .
 */
function ciyashop_extend_smart_product_image_option_color_notice( $html, $active_color ) {

	$html .= esc_html__( 'Note: If "Color" is set to "CiyaShop", "Style" will not be applicable.', 'ciyashop' );

	return $html;
}


/**
 * Add a custom product data tab
 *
 * @param array $tabs .
 */
add_filter( 'woocommerce_product_tabs', 'ciyashop_new_product_tab' );

if ( ! function_exists( 'ciyashop_new_product_tab' ) ) {
	/**
	 * New product tab
	 *
	 * @param string $tabs .
	 */
	function ciyashop_new_product_tab( $tabs ) {
		// Adds the new tab.

		$custom_tab_title   = get_post_meta( get_the_ID(), 'custom_tab_title', true );
		$custom_tab_content = get_post_meta( get_the_ID(), 'custom_tab_content', true );
		$custom_tab_title   = ( ! empty( $custom_tab_title ) ) ? $custom_tab_title : __( 'Custom Tab', 'ciyashop' );

		if ( ! empty( $custom_tab_content ) ) {
			$tabs['test_tab'] = array(
				'title'    => $custom_tab_title,
				'priority' => 50,
				'callback' => 'ciyashop_new_product_tab_content',
			);
		}

		return $tabs;
	}
}

if ( ! function_exists( 'ciyashop_new_product_tab_content' ) ) {
	/**
	 * New product tab content
	 */
	function ciyashop_new_product_tab_content() {
		$custom_tab_content = '';
		$custom_tab_content = get_post_meta( get_the_ID(), 'custom_tab_content', true );
		if ( $custom_tab_content ) {
			echo do_shortcode( get_post_meta( get_the_ID(), 'custom_tab_content', true ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}

// Add sticky add to cart on product single page.
if ( ! function_exists( 'ciyashop_sticky_single_add_to_cart' ) ) {
	/**
	 * Sticky single add to cart
	 */
	function ciyashop_sticky_single_add_to_cart() {
		global $product, $ciyashop_options;

		$hide_price_for_guest_user = isset( $ciyashop_options['hide_price_for_guest_user'] ) ? $ciyashop_options['hide_price_for_guest_user'] : '';
		$single_sticky_add_to_cart = isset( $ciyashop_options['single_sticky_add_to_cart'] ) ? $ciyashop_options['single_sticky_add_to_cart'] : '';

		if ( $hide_price_for_guest_user && ! is_user_logged_in() ) {
			return;
		}

		if ( ! is_product() || ! $single_sticky_add_to_cart || wp_is_mobile() ) {
			return;
		}
		?>
		<div class="ciyashop-sticky-btn">
			<div class="ciyashop-sticky-btn-container container">
				<div class="row align-items-center">
					<div class="col-lg-5">
						<div class="ciyashop-sticky-btn-content">
							<div class="ciyashop-sticky-btn-thumbnail">
								<?php echo woocommerce_get_product_thumbnail(); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>
							</div>
							<div class="ciyashop-sticky-btn-info">
								<h4 class="product-title"><?php the_title(); ?></h4>
								<?php
								if ( $ciyashop_options['product_rating_sticky_add_to_cart'] ) {
									echo wc_get_rating_html( $product->get_average_rating() ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
								}
								?>
							</div>
						</div>
					</div>
					<div class="col-lg-7">
						<?php
						if ( $ciyashop_options['sticky_add_to_cart_product_countdown'] ) {
							ciyashop_product_sale_countdown();
						}
						?>
						<div class="ciyashop-sticky-btn-cart">
							<div class="wishlist-compare-button">
								<?php
								/**
								 * Filters active plugins.
								 *
								 * @param array $active_plugins List of active plugins.
								 *
								 * @visible false
								 * @ignore
								 */

								if ( $ciyashop_options['wishlist_sticky_add_to_cart'] ) {
									ciyashop_product_actions_add_wishlist_link();
								}

								/**
								 * Filters active plugins.
								 *
								 * @param array $active_plugins List of active plugins.
								 *
								 * @visible false
								 * @ignore
								 */
								if ( $ciyashop_options['compare_sticky_add_to_cart'] ) {
									?>
									<div class="product-action product-action-compare" data-toggle='tooltip' data-original-title="<?php esc_attr_e( 'Compare', 'ciyashop' ); ?>" data-placement='top'>
										<?php
										if ( class_exists( 'YITH_Woocompare' ) ) {
											echo do_shortcode( '[yith_compare_button]' );
										} else {
											echo do_shortcode( '[ciyashop_add_to_compare]' );
										}
										?>
									</div>
									<?php
								}
								?>
							</div>
							<span class="price"><?php echo $product->get_price_html(); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></span>
							<?php if ( $product->is_type( 'simple' ) ) : ?>
								<?php woocommerce_simple_add_to_cart(); ?>
							<?php elseif ( $product->is_type( 'variable' ) ) : ?>
								<a href="#" class="ciyashop-sticky-add-to-cart vaiable_button button alt">
									<?php echo esc_html__( 'Select options', 'ciyashop' ); ?>
								</a>
							<?php elseif ( $product->is_type( 'grouped' ) ) : ?>
								<a href="#" class="ciyashop-sticky-add-to-cart grouped_product button alt">
									<?php echo esc_html__( 'Select options', 'ciyashop' ); ?>
								</a>
							<?php elseif ( $product->is_type( 'external' ) ) : ?>
								<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="ciyashop-sticky-add-to-cart button alt">
									<?php echo esc_html( $product->single_add_to_cart_text() ); ?>
								</a>
							<?php else : ?>
								<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="ciyashop-sticky-add-to-cart button alt">
									<?php echo esc_html( $product->single_add_to_cart_text() ); ?>
								</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	add_action( 'woocommerce_single_product_summary', 'ciyashop_sticky_single_add_to_cart', 999 );
}

/**
 * Track product views.
 * @link https://github.com/woocommerce/woocommerce/blob/5.0.0/includes/wc-product-functions.php#L531
 */
function ciyashop_extend_wc_track_product_view() {

	if ( is_singular( 'product' ) && is_active_widget( false, false, 'woocommerce_recently_viewed_products', true ) ) {
		return;
	}

	global $post;

	if ( $post ) {
		if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) ) { // @codingStandardsIgnoreLine.
			$viewed_products = array();
		} else {
			$viewed_products = wp_parse_id_list( (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) ); // @codingStandardsIgnoreLine.
		}

		// Unset if already in viewed products list.
		$keys = array_flip( $viewed_products );

		if ( isset( $keys[ $post->ID ] ) ) {
			unset( $viewed_products[ $keys[ $post->ID ] ] );
		}

		if ( 'product' === $post->post_type ) {
			$viewed_products[] = $post->ID;
		}

		if ( count( $viewed_products ) > 15 ) {
			array_shift( $viewed_products );
		}

		// Store for session only.
		wc_setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ) );
	}
}

add_action( 'template_redirect', 'ciyashop_extend_wc_track_product_view', 20 );
