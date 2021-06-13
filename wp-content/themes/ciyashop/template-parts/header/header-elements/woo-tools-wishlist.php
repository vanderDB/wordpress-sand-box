<?php
/**
 * Woo tools Wishlist.
 *
 * @package CiyaShop
 */

// Return if compare plugin is not installed/activated ($yith_woocompare == null).
if ( wp_is_mobile() ) {
	return;
}

if ( class_exists( 'YITH_WCWL' ) ) {
	$yith_wcwl    = YITH_WCWL();
	$wishlist_url = $yith_wcwl->get_wishlist_url();
	?>
	<li class="woo-tools-action woo-tools-wishlist">
		<a href="<?php echo esc_url( $wishlist_url ); ?>"><?php ciyashop_wishlist_icon(); ?>
		<span class="wishlist ciyashop-wishlist-count">
			<?php echo esc_html( YITH_WCWL()->count_products() ); ?>
		</span>
		</a>
	</li>

	<?php
} else {
	global $ciyashop_options;

	if ( ( isset( $ciyashop_options['show_wishlist'] ) && $ciyashop_options['show_wishlist'] ) ) {
		$cs_wishlist  = new Ciyashop_Wishlist();
		$wishlist_url = ( isset( $ciyashop_options['cs_wishlist_page'] ) && ! empty( $ciyashop_options['cs_wishlist_page'] ) ) ? get_permalink( $ciyashop_options['cs_wishlist_page'] ) : '#';
		?>
		<li class="woo-tools-action woo-tools-wishlist">
			<a href="<?php echo esc_url( $wishlist_url ); ?>"><?php ciyashop_wishlist_icon(); ?>
			<span class="wishlist ciyashop-wishlist-count">
				<?php echo esc_html( $cs_wishlist->count_products() ); ?>
			</span>
			</a>
		</li>
		<?php
	}
}
