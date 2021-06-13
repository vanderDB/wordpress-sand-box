<?php
/**
 * Wishlist pages template; load template parts basing on the url
 *
 * @package ciyashop
 */

global $ciyashop_options;

$cs_wishlist       = new Ciyashop_Wishlist();
$wishlist_products = $cs_wishlist->get_wishlist();

do_action( 'ciyashop_before_cs_wishlist_content' );
?>
<table class="shop_table cart wishlist_table wishlist_view traditional responsive">
	<thead>
		<tr>
			<th class="product-remove"></th>

			<th class="product-thumbnail"></th>

			<th class="product-name">
				<span class="nobr">
					<?php echo esc_html__( 'Product Name', 'ciyashop' ); ?>
				</span>
			</th>

			<th class="product-price">
				<span class="nobr">
					<?php echo esc_html__( 'Unit Price', 'ciyashop' ); ?>
				</span>
			</th>

			<th class="product-stock-status">
				<span class="nobr">
					<?php echo esc_html__( 'Stock Status', 'ciyashop' ); ?>
				</span>
			</th>

			<th class="product-add-to-cart"></th>
		</tr>

		<tbody class="wishlist-items-wrapper">
			<?php
			foreach ( $wishlist_products as $wishlist_product ) {
				$wcwl_product = wc_get_product( $wishlist_product );
				if ( $wcwl_product && $wcwl_product->exists() ) {
					$availability = $wcwl_product->get_availability();
					$stock_status = isset( $availability['class'] ) ? $availability['class'] : false;
					?>
					<tr id="cs-wcwl-row-<?php echo esc_attr( $wishlist_product ); ?>" class="cs-wcwl-row" data-row-id="<?php echo esc_attr( $wishlist_product ); ?>">
						<td class="product-remove">
							<div class="cs-wcwl-row-label">
								<?php echo esc_html__( 'Remove', 'ciyashop' ); ?>
							</div>
							<div>
								<a class="remove remove_from_wishlist cs-remove-wishlist" data-product-id="<?php echo esc_attr( $wishlist_product ); ?>" title="<?php echo esc_html( apply_filters( 'cs_wcwl_remove_product_wishlist_message_title', esc_html__( 'Remove this product', 'ciyashop' ) ) ); ?>"></a>
							</div>
						</td>

						<td class="product-thumbnail">
							<?php do_action( 'cs_wcwl_table_before_product_thumbnail', $wcwl_product, $cs_wishlist ); ?>
							<a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $wishlist_product ) ) ); ?>">
								<?php echo $wcwl_product->get_image(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>
							<?php do_action( 'cs_wcwl_table_after_product_thumbnail', $wcwl_product, $cs_wishlist ); ?>
						</td>

						<td class="product-name">
							<div class="cs-wcwl-row-label">
								<?php echo esc_html__( 'Product Name', 'ciyashop' ); ?>
							</div>
							<div>
								<?php do_action( 'cs_wcwl_table_before_product_name', $wcwl_product, $cs_wishlist ); ?>
								<a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $wishlist_product ) ) ); ?>"><?php echo esc_html( apply_filters( 'woocommerce_in_cartproduct_obj_title', $wcwl_product->get_title(), $wcwl_product ) ); ?></a>
								<?php do_action( 'cs_wcwl_table_after_product_name', $wcwl_product, $cs_wishlist ); ?>
							</div>
						</td>

						<td class="product-price">
							<div class="cs-wcwl-row-label">
								<?php echo esc_html__( 'Unit Price', 'ciyashop' ); ?>
							</div>
							<div>
								<?php
								do_action( 'cs_wcwl_table_before_product_price', $wcwl_product, $cs_wishlist );
								echo $wcwl_product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								do_action( 'cs_wcwl_table_after_product_price', $wcwl_product, $cs_wishlist );
								?>
							</div>
						</td>

						<td>
							<div class="cs-wcwl-row-label">
								<?php echo esc_html__( 'Stock Status', 'ciyashop' ); ?>
							</div>
							<div>
								<?php
								do_action( 'cs_wcwl_table_before_product_stock', $wcwl_product, $cs_wishlist );
								echo 'out-of-stock' === $stock_status ? '<span class="wishlist-out-of-stock">' . esc_html( apply_filters( 'cs_wcwl_out_of_stock_label', esc_html__( 'Out of stock', 'ciyashop' ) ) ) . '</span>' : '<span class="wishlist-in-stock">' . esc_html( apply_filters( 'cs_wcwl_in_stock_label', esc_html__( 'In Stock', 'ciyashop' ) ) ) . '</span>';
								do_action( 'cs_wcwl_table_after_product_stock', $wcwl_product, $cs_wishlist ); 
								?>
							</div>
						</td>

						<td class="product-add-to-cart">
							<?php do_action( 'cs_wcwl_table_before_add_to_cart', $wcwl_product, $cs_wishlist ); ?>
							<?php
							if ( 'simple' === $wcwl_product->get_type() ) {
								?>
								<a href="<?php echo esc_url( $wcwl_product->add_to_cart_url() ); ?>" value="<?php echo esc_attr( $wishlist_product ); ?>" class="ajax_add_to_cart add_to_cart_button button" data-product_id="<?php echo esc_attr( $wishlist_product ); ?>" > <?php echo esc_html__( 'Add to Cart', 'ciyashop' ); ?></a>
								<?php
							} else {
								?>
								<a href="<?php echo esc_url( $wcwl_product->add_to_cart_url() ); ?>" value="<?php echo esc_attr( $wishlist_product ); ?>" class="button add_to_cart_button add_to_cart alt" data-product_id="<?php echo esc_attr( $wishlist_product ); ?>" > <?php echo esc_html__( 'Select Options', 'ciyashop' ); ?></a>
								<?php
							}
							?>
							<?php woocommerce_template_loop_add_to_cart( array( 'quantity' => 1 ) ); ?>
							<?php do_action( 'cs_wcwl_table_before_add_to_cart', $wcwl_product, $cs_wishlist ); ?>
						</td>
					</tr>
					<?php
				}
			}
			?>
		</tbody>
	</thead>
</table>
<?php

$wishlist_empty_text = ( isset( $ciyashop_options['wishlist_empty_text'] ) && ! empty( $ciyashop_options['wishlist_empty_text'] ) ) ? $ciyashop_options['wishlist_empty_text'] : esc_html__( 'No products added to the wishlist', 'ciyashop' );

$wishlist_empty_text  = apply_filters( 'cs_wishlist_empty_text', $wishlist_empty_text );
$wishlist_empty_class = 'wishlist-empty';

if ( $wishlist_products ) {
	$wishlist_empty_class .= ' d-none';
}

if ( $wishlist_empty_text ) {
	?>
	<div class="<?php echo esc_attr( $wishlist_empty_class ); ?>">
		<?php echo wp_kses_post( $wishlist_empty_text ); ?>
	</div>
	<?php
}

do_action( 'ciyashop_after_cs_wishlist_content' );
