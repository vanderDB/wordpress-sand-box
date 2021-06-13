<?php
/**
 * Shop category carousel
 *
 * @package WooCommerce/Templates
 */

global $ciyashop_options;

// Categories slider for shop page.
if ( isset( $ciyashop_options['show_category_on_shop_header'] ) && 1 === (int) $ciyashop_options['show_category_on_shop_header'] && ( is_shop() || is_product_category() || is_product_tag() ) ) {

	$banner_image_bg_url = '';
	$product_include     = 'all';

	if ( isset( $ciyashop_options['shop_product_categories'] ) && ! empty( $ciyashop_options['shop_product_categories'] ) ) {
		$product_include = $ciyashop_options['shop_product_categories'];
	}

	$number = isset( $ciyashop_options['shop_categories_per_page'] ) ? $ciyashop_options['shop_categories_per_page'] : 5;
	$args   = array(
		'taxonomy' => 'product_cat',
		'include'  => $product_include,
		'orderby'  => 'include',
	);

	$product_categories = get_terms( $args );

	$owl_options_args = array(
		'items'              => $number,
		'responsive'         => array(
			0    => array(
				'items' => 1,
			),
			480  => array(
				'items' => 2,
			),
			768  => array(
				'items' => 3,
			),
			992  => array(
				'items' => 4,
			),
			1200 => array(
				'items' => $number,
			),
		),
		'margin'             => 15,
		'dots'               => false,
		'nav'                => false,
		'loop'               => true,
		'autoplay'           => true,
		'autoplayHoverPause' => true,
		'autoplayTimeout'    => 3100,
		'smartSpeed'         => 1000,
	);

	$owl_options = wp_json_encode( $owl_options_args );

	$current_cat                = '';
	$categories_classes         = array();
	$style                      = isset( $ciyashop_options['shop_categories_style'] ) ? $ciyashop_options['shop_categories_style'] : 'style-1';
	$shop_categories_text_color = isset( $ciyashop_options['shop_categories_text_color'] ) ? $ciyashop_options['shop_categories_text_color'] : 'light';

	$container = isset( $ciyashop_options['shop-categories-width'] ) ? $ciyashop_options['shop-categories-width'] : 'fixed';

	if ( ! empty( $number ) ) {
		$categories_classes[] = 'woocommerce-categories-count-' . $number;
	}

	$categories_classes[] = 'carousel-wrapper';
	$categories_classes[] = 'woocommerce-categories-slider';
	$categories_classes[] = 'woocommerce-categories-slider-' . $style;
	
	if ( 'style-1' !== $style ) {
		$categories_classes[] = 'woocommerce-categories-slider-' . $shop_categories_text_color;
	}

	if ( 'fixed' === $container ) {
		$container_class = 'container';
	} elseif ( 'wide' === $container ) {
		$container_class = 'container-fluid';
	}

	if ( is_product_category() && isset( get_queried_object()->term_id ) ) {
		$current_cat = get_queried_object()->term_id;
		$term_bg_id  = get_term_meta( $current_cat, 'shop_categories_background', true );
		if ( $term_bg_id ) {
			$image_data          = wp_get_attachment_image_src( $term_bg_id, 'full', false );
			$banner_image_bg_url = $image_data[0];
		}
	}

	if ( isset( $ciyashop_options['shop_categories_background']['background-image'] ) && $ciyashop_options['shop_categories_background']['background-image'] && ! $banner_image_bg_url ) {
		$banner_image_bg_url = $ciyashop_options['shop_categories_background']['background-image'];
	}

	$categories_classes = implode( ' ', $categories_classes );
	?>
	<div class="woocommerce-categories-wrapper">
		<div class="<?php echo esc_attr( $container_class ); ?>">
			<div class="row">
				<div class="col-md-12">
					<div class="<?php echo esc_attr( $categories_classes ); ?>" <?php echo ( $banner_image_bg_url ) ? 'style="background-image:url(' . esc_attr( $banner_image_bg_url ) . ' )"' : ''; ?>>
						<div class="col-md-12">
							<div class="row">
								<div class="container">
									<div class="owl-carousel owl-theme owl-carousel-options" data-owl_options="<?php echo esc_attr( $owl_options ); ?>">
									<?php
									foreach ( $product_categories as $product_categorie ) {
										$image      = 0;
										$image_src  = '';
										$item_class = 'item';
										$image      = get_term_meta( $product_categorie->term_id, 'product_category_icon', true );

										if ( ! empty( $image ) ) {
											$image_data = wp_get_attachment_image_src( $image, 'thumbnail', false );
											$image_src  = $image_data[0];
										} else {
											$thumbnail_id = get_term_meta( $product_categorie->term_id, 'thumbnail_id', true );
											if ( $thumbnail_id ) {
												$image_src = wp_get_attachment_url( $thumbnail_id );
											}
										}
										
										if ( $current_cat === $product_categorie->term_id ) {
											$item_class .= ' active-category';
										}
										?>
										<div class="<?php echo esc_attr( $item_class ); ?>">
											<a href="<?php echo esc_url( get_category_link( $product_categorie->term_id ) ); ?>">
												<?php
												if ( $image_src ) {
													?>
													<div class="woo-category-image">
														<img src="<?php echo esc_url( $image_src ); ?>" alt="<?php echo esc_attr( $product_categorie->slug ); ?>" class="category-icon">
													</div>
													<?php
												}
												?>
												<div class="woo-category-summary">
													<div class="woo-category-name">
													<?php echo esc_html( $product_categorie->name ); ?>
													</div>
													<div class="woo-category-products-count">
														<span class="woo-cat-count"><?php echo esc_html( $product_categorie->count ); ?></span> 
														<span class="woo-cat-label"><?php esc_html_e( 'product', 'ciyashop' ); ?></span>
													</div>
												</div>
											</a>
										</div>
										<?php
									}
									?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
