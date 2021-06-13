<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.5
 */

defined( 'ABSPATH' ) || exit;

global $product, $ciyashop_options;

$attribute_keys  = array_keys( $attributes );
$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart"  action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>"
	method="post" enctype='multipart/form-data' 
	data-product_id="<?php echo absint( $product->get_id() ); ?>" 
	data-product_variations="<?php echo $variations_attr; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?>">

	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php
	if ( empty( $available_variations ) && false !== $available_variations ) {
		?>
		<p class="stock out-of-stock"><?php esc_html_e( 'This product is currently out of stock and unavailable.', 'ciyashop' ); ?></p>
		<?php
	} else {
		?>
		<table class="variations" cellspacing="0">
			<tbody>
			<?php

			// Get default attributes of the product.
			$default_attributes = $product->get_default_attributes();

			foreach ( $attributes as $attribute_name => $options ) {
				$swatches = ciyashop_get_swatches( $product->get_id(), $attribute_name, $options, $available_variations );

				// Gheck swatch option is not disabled for attribute.
				$enable_swatch = get_option( 'ciyashop_' . $attribute_name . '_swatch' );
				?>
				<tr>
					<td class="label">
						<label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"><?php echo ucwords( wc_attribute_label( $attribute_name ) ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE ?></label>
					</td>
					<td class="value<?php echo ! empty( $enable_swatch ) ? esc_attr( ' ciyashop-swatches' ) : ''; ?>">
						<?php
						if ( $enable_swatch ) {
							?>
							<div class="swatches-select" data-id="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>">
								<?php
								if ( is_array( $options ) ) {

									if ( isset( $_REQUEST[ 'attribute_' . $attribute_name ] ) ) {
										$selected_value = sanitize_text_field( wp_unslash( $_REQUEST[ 'attribute_' . $attribute_name ] ) );
									} else {
										$selected_value = '';
									}

									// Get terms if this is a taxonomy - ordered.
									if ( taxonomy_exists( $attribute_name ) ) {

										$terms          = wc_get_product_terms( $product->get_id(), $attribute_name, array( 'fields' => 'all' ) );
										$options_fliped = array_flip( $options );
										foreach ( $terms as $term ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride
											if ( ! in_array( $term->slug, $options, true ) ) {
												continue;
											}

											$key = $options_fliped[ $term->slug ];

											$style = '';
											$class = 'ciyashop-swatch ';

											if ( isset( $swatches[ $key ]['color'] ) && ! empty( $swatches[ $key ]['color'] ) && isset( $swatches[ $key ]['image'] ) && ! empty( $swatches[ $key ]['image'] ) ) {
												$class  .= 'swatch-image';
												$img_url = wp_get_attachment_image_src( $swatches[ $key ]['image'], 'ciyashop-brand-logo' );
												$style   = 'background-image: url(' . $img_url[0] . ')';
											} elseif ( isset( $swatches[ $key ]['color'] ) && ! empty( $swatches[ $key ]['color'] ) ) {
												$class .= 'swatch-colored';
												$style  = 'background-color:' . $swatches[ $key ]['color'];
											} elseif ( ! empty( $swatches[ $key ]['image'] ) ) {
												$class  .= 'swatch-image';
												$img_url = wp_get_attachment_image_src( $swatches[ $key ]['image'], 'ciyashop-brand-logo' );
												$style   = 'background-image: url(' . $img_url[0] . ')';
											} else {
												$class .= 'ciyashop-text-only';
											}

											// Check if default attribuet is set for current attribute and default selected attibute is current value.
											if ( isset( $default_attributes[ $attribute_name ] ) && (string) $term->slug === (string) $default_attributes[ $attribute_name ] ) {
												$class .= ' cs-attr-selected';
											}

											echo '<a class="' . esc_attr( $class ) . '" data-value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $selected_value ), sanitize_title( $term->slug ), false ) . ' style="' . esc_attr( $style ) . '" title="' . esc_attr( $term->name ) . '" data-toggle="tooltip" data-cs_parent="' . esc_attr( sanitize_title( $attribute_name ) ) . '">' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										}
									} else {
										foreach ( $options as $option ) {
											echo '<a data-value="' . esc_attr( sanitize_title( $option ) ) . '" ' . selected( sanitize_title( $selected_value ), sanitize_title( $option ), false ) . ' data-toggle="tooltip" data-cs_parent="' . esc_attr( sanitize_title( $attribute_name ) ) . '">' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</a>';
										}
									}
								}
								?>

							</div>
							<?php
						}

						wc_dropdown_variation_attribute_options(
							array(
								'options'   => $options,
								'attribute' => $attribute_name,
								'product'   => $product,
							)
						);
						echo end( $attribute_keys ) === $attribute_name ? wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'ciyashop' ) . '</a>' ) ) : '';
						?>
					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>

		<div class="single_variation_wrap">
			<?php
			/**
			 * Hook: woocommerce_before_single_variation.
			 */
			do_action( 'woocommerce_before_single_variation' );

			/**
			 * Hook: woocommerce_single_variation. Used to output the cart button and placeholder for variation data.
			 *
			 * @since 2.4.0
			 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
			 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
			 */
			do_action( 'woocommerce_single_variation' );

			/**
			 * Hook: woocommerce_after_single_variation.
			 */
			do_action( 'woocommerce_after_single_variation' );
			?>
		</div>
		<?php 
	}
	do_action( 'woocommerce_after_variations_form' );
	?>
</form>
<?php
do_action( 'woocommerce_after_add_to_cart_form' );
