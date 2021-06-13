<?php
/**
 * Quick view
 *
 * @package     WooCommerce/Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post, $woocommerce_loop, $ciyashop_options;

$product = wc_get_product( $post );

// Ensure visibility.
if ( ! $product || ! $product->is_visible() ) {
	return;
}

$classes   = array();
$classes[] = 'product-quick-view single-product-content woocommerce';
if ( 1 === (int) $ciyashop_options['quick_view_product_link'] ) {
	$woocommerce_loop['view'] = 'quick-view';
}

/**
 * Woocommerce_before_single_product hook
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	return;
}
?>
<div itemscope id="product-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>

	<div class="row product-image-summary">
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 product-images">
			<?php
			if ( has_post_thumbnail() ) {
				$props     = wc_get_product_attachment_props( get_post_thumbnail_id(), $post );
				$alt       = trim( wp_strip_all_tags( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ) );
				$image_alt = ( ! empty( $alt ) ) ? $alt : $post->post_name;
				if ( isset( $ciyashop_options['enable_lazyload'] ) && $ciyashop_options['enable_lazyload'] ) {
					?>
					<img id="product-zoom" class="img-fluid ciyashop-lazy-load" src="<?php echo esc_url( LOADER_IMAGE ); ?>" data-src="<?php echo esc_url( $props['url'] ); ?>" data-zoom-image="<?php echo esc_url( $props['url'] ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>"/>
					<?php
				} else {
					?>
					<img id="product-zoom" class="img-fluid" src="<?php echo esc_url( $props['url'] ); ?>" data-zoom-image="<?php echo esc_url( $props['url'] ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>"/>
					<?php
				}
			} else {
				if ( isset( $ciyashop_options['enable_lazyload'] ) && $ciyashop_options['enable_lazyload'] ) {
					$placeholder_img_src = sprintf(
						'<img src="%s" data-src="%s" alt="%s" class="ciyashop-lazy-load" />',
						LOADER_IMAGE,
						wc_placeholder_img_src(),
						$post->post_name
					);
				} else {
					$placeholder_img_src = sprintf(
						'<img src="%s" alt="%s" />',
						wc_placeholder_img_src(),
						$post->post_name
					);
				}
				$placeholder_img_src = apply_filters( 'woocommerce_single_product_image_html', $placeholder_img_src, $post->ID );
				echo wp_kses( $placeholder_img_src, ciyashop_allowed_html( array( 'div', 'a', 'img', 'span' ) ) );
			}
			?>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 summary entry-summary">
			<div class="summary-inner basel-scroll">
				<div class="basel-scroll-content">
					<?php
						/**
						 * Woocommerce_single_product_summary hook.
						 *
						 * @hooked woocommerce_template_single_title - 5
						 * @hooked woocommerce_template_single_rating - 10
						 * @hooked woocommerce_template_single_price - 10
						 * @hooked woocommerce_template_single_excerpt - 20
						 * @hooked woocommerce_template_single_add_to_cart - 30
						 * @hooked woocommerce_template_single_meta - 40
						 * @hooked woocommerce_template_single_sharing - 50
						 * @hooked WC_Structured_Data::generate_product_data() - 60
						 */
						do_action( 'woocommerce_single_product_summary' );
					?>
				</div>
			</div>
		</div><!-- .summary -->
	</div>


</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
