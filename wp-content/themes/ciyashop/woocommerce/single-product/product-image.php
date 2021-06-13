<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.5.1
 */

defined( 'ABSPATH' ) || exit;

global $post, $product, $ciyashop_options;

$product_gallery_image_ids = array();
$product                   = wc_get_product( $post );
$thumbnail_size            = apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' );
if ( $product->get_image_id() ) {
	$product_gallery_image_ids[] = get_post_thumbnail_id( $post->ID );
}

$attachment_ids = $product->get_gallery_image_ids();
if ( $attachment_ids ) {
	$product_gallery_image_ids = array_merge( $product_gallery_image_ids, $attachment_ids );
}

if ( $product->is_type( 'variable' ) ) {
	$variations       = $product->get_available_variations();
	$variation_images = array();
	foreach ( $variations as $variation ) {
		if ( isset( $variation['image_id'] ) && ! empty( $variation['image_id'] ) ) {
			$variation_images[] = $variation['image_id'];
		}
	}
	if ( ! empty( $variation_images ) ) {
		$product_gallery_image_ids = array_merge( $product_gallery_image_ids, $variation_images );
	}
}

$product_gallery_image_ids = array_unique( $product_gallery_image_ids );

$ciyashop_product_images_classes = array( 'ciyashop-product-images' );
if ( count( $product_gallery_image_ids ) <= 1 ) {
	array_push( $ciyashop_product_images_classes, 'product-without-gallery-image' );
}

$product_images_classes = apply_filters( 'ciyashop_product_images_classes', $ciyashop_product_images_classes );

$product_images_wrapper_classes = apply_filters(
	'ciyashop_product_images_wrapper_classes',
	array(
		'ciyashop-product-images-wrapper',
	)
);

$placeholder     = $product->get_image_id() ? 'with-images' : 'without-images';
$image_classes   = ( $product->get_image_id() || ! empty( $product->get_gallery_image_ids() ) ) ? 'with-images' : 'without-images';
$wrapper_classes = apply_filters(
	'woocommerce_single_product_image_gallery_classes',
	array(
		'ciyashop-product-gallery',
		'ciyashop-product-gallery--' . $image_classes,
	)
);

$gallery_wrapper_classes = apply_filters(
	'ciyashop_product_image_gallery_wrapper_classes',
	array(
		'ciyashop-product-gallery__wrapper',
	)
);
?>

<?php do_action( 'ciyashop_before_product_images' ); ?>

<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $product_images_classes ) ) ); ?>">

	<?php do_action( 'ciyashop_before_product_images_wrapper' ); ?>

	<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $product_images_wrapper_classes ) ) ); ?>">

		<?php do_action( 'ciyashop_before_product_gallery' ); ?>

		<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>">

			<?php do_action( 'ciyashop_before_product_gallery_wrapper' ); ?>

			<?php
			$default_image_props = array();
			if ( ! empty( $product_gallery_image_ids ) ) {
				$default_img_id      = $product_gallery_image_ids[0];
				$default_image_props = wc_get_product_attachment_props( $default_img_id );
			}
			?>

			<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $gallery_wrapper_classes ) ) ); ?>" data-default_image_props="<?php echo htmlspecialchars( wp_json_encode( $default_image_props ) ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>">
			<?php
			if ( ! empty( $product_gallery_image_ids ) ) {

				$gallery_images_sr = 0;
				foreach ( $product_gallery_image_ids as $attachment_id ) {
					$gallery_images_sr++;
						$full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
						$thumbnail       = wp_get_attachment_image_src( $attachment_id, 'woocommerce_gallery_thumbnail' );

						$image_size = 'woocommerce_single';

						$size_class = $image_size;
					if ( is_array( $size_class ) ) {
						$size_class = join( 'x', $size_class );
					}

						$attributes  = array(
							'title'                   => get_post_field( 'post_title', $attachment_id ),
							'data-caption'            => get_post_field( 'post_excerpt', $attachment_id ),
							'data-src'                => $full_size_image[0],
							'data-image_id'           => $attachment_id,
							'data-large_image'        => $full_size_image[0],
							'data-large_image_width'  => $full_size_image[1],
							'data-large_image_height' => $full_size_image[2],
							'class'                   => "attachment-$size_class size-$size_class" . ( ( 1 === (int) $gallery_images_sr ) ? 'wp-post-image' : '' ),
						);
						$image_props = wc_get_product_attachment_props( $attachment_id );

						$html  = '<div class="ciyashop-product-gallery__image" data-image_props="' . htmlspecialchars( wp_json_encode( $image_props ) ) . '">';
						$html .= '<a href="' . esc_url( $full_size_image[0] ) . '" data-elementor-open-lightbox="no" >';
						$html .= wp_get_attachment_image( $attachment_id, $image_size, false, $attributes );
						$html .= '</a>';
						$html .= '</div>';

						echo apply_filters( 'ciyashop_single_product_image_html', $html, $attachment_id ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
				}
			} else {
				$html = '<div class="ciyashop-product-gallery__image--placeholder">';

				$wc_placeholder_img_src = wc_placeholder_img_src( 'woocommerce_single' );
				if ( isset( $ciyashop_options['enable_lazyload'] ) && $ciyashop_options['enable_lazyload'] ) {
					$html .= sprintf(
						'<img src="%s" data-src="%s" alt="%s" class="wp-post-image ciyashop-lazy-load" />',
						esc_url( LOADER_IMAGE ),
						esc_url( $wc_placeholder_img_src ),
						esc_attr__( 'Awaiting product image', 'ciyashop' )
					);
				} else {
					$html .= sprintf(
						'<img src="%s" alt="%s" class="wp-post-image" />',
						esc_url( $wc_placeholder_img_src ),
						esc_attr__( 'Awaiting product image', 'ciyashop' )
					);
				}

				$html .= '</div>';
				echo apply_filters( 'ciyashop_single_product_placeholder_image_html', $html, $product ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			}
			?>
			</div>

			<?php do_action( 'ciyashop_after_product_gallery_wrapper' ); ?>

			<div class="ciyashop-product-gallery_buttons_wrapper">
				<?php do_action( 'ciyashop_product_gallery_buttons' ); ?>
			</div>

		</div><!-- .ciyashop-product-gallery -->

		<?php do_action( 'ciyashop_after_product_gallery' ); ?>

		<?php
		$style = ciyashop_single_product_style();

		if ( 'wide_gallery' !== $style && $product_gallery_image_ids && count( $product_gallery_image_ids ) > 1 ) {
			?>
			<div class="ciyashop-product-thumbnails">
				<div class="ciyashop-product-thumbnails__wrapper">
					<?php
					foreach ( $product_gallery_image_ids as $attachment_id ) {
						$thumbnail  = wp_get_attachment_image_src( $attachment_id, 'woocommerce_thumbnail' );
						$attributes = array(
							'title'         => get_post_field( 'post_title', $attachment_id ),
							'data-src'      => $thumbnail[0],
							'data-image_id' => $attachment_id,
						);

						$html  = '<div class="ciyashop-product-thumbnail__image" data-image_id="' . esc_attr( $attachment_id ) . '">';
						$html .= wp_get_attachment_image( $attachment_id, 'woocommerce_thumbnail', false, $attributes );
						$html .= '</div>';

						echo apply_filters( 'ciyashop_single_product_image_thumbnail_html', $html, $attachment_id );// phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
					}
					?>
				</div>
			</div>
			<div class="clearfix"></div>
			<?php
		}
		?>

		<?php do_action( 'ciyashop_after_product_thumbnails' ); ?>

	</div><!-- .ciyashop-product-images-wrapper -->

		<?php do_action( 'ciyashop_after_product_images_wrapper' ); ?>
</div><!-- .ciyashop-product-images -->
<div class="images"></div>

<?php do_action( 'ciyashop_after_product_images' ); ?>
