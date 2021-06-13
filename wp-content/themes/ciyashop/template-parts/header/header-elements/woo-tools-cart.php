<?php
/**
 * Woo tools.
 *
 * @package CiyaShop
 */

if ( class_exists( 'WooCommerce' ) && ! wp_is_mobile() ) {

	global $ciyashop_options;

	if ( isset( $ciyashop_options['hide_price_for_guest_user'] ) && $ciyashop_options['hide_price_for_guest_user'] && ! is_user_logged_in() ) {
		return;
	}
	?>
	<li class="woo-tools-action woo-tools-cart">
		<a class="cart-link" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php /* translators: %1$s is replaced with "number" */ echo sprintf( esc_attr__( 'View Cart (%s)', 'ciyashop' ), esc_attr( WC()->cart->get_cart_contents_count() ) ); ?>"><span class="cart-icon"><?php ciyashop_cart_icon(); ?></span><?php ciyashop_header_cart_count(); ?></a>
		<div class="cart-contents"><?php the_widget( 'WC_Widget_Cart', 'title=' ); ?></div>
	</li>
	<?php

}
