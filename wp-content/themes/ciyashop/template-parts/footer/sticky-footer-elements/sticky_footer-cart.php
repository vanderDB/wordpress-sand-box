<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Sticky Footer Cart file.
 *
 * @package CiyaShop
 */

if ( class_exists( 'WooCommerce' ) ) {

	if ( isset( $ciyashop_options['hide_price_for_guest_user'] ) && $ciyashop_options['hide_price_for_guest_user'] && ! is_user_logged_in() ) {
		return;
	}

	$woo_cart_classes[] = 'sticky-footer-mobile-woo-cart';
	$woo_cart_classes[] = 'woo-tools-cart';

	if ( is_cart() ) {
		$woo_cart_classes[] = 'sticky-footer-active';
	}

	$woo_cart_classes = implode( ' ', array_filter( array_unique( $woo_cart_classes ) ) );
	?>
	<div class="<?php echo esc_attr( $woo_cart_classes ); ?>">
		<?php /* translators: %s: string */ ?>
		<a class="cart-link" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php echo sprintf( esc_attr__( 'View Cart (%s)', 'ciyashop' ), esc_html( WC()->cart->get_cart_contents_count() ) ); ?>">
			<i class="vc_icon_element-icon glyph-icon pgsicon-ecommerce-commerce-3"></i>
			<?php ciyashop_header_cart_count(); ?>
		</a>
	</div>
	<?php
}
