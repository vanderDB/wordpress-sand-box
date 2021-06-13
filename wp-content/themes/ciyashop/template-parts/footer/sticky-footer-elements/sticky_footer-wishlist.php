<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Sticky Footer Home file.
 *
 * @package CiyaShop
 */

// Return if compare plugin is not installed/activated ($yith_woocompare == null).

$woo_wishlist_classes = 'sticky-footer-mobile-woo-wishlist';
if ( class_exists( 'YITH_WCWL' ) ) {
	if ( function_exists( 'yith_wcwl_is_wishlist_page' ) && yith_wcwl_is_wishlist_page() ) {
		$woo_wishlist_classes .= ' sticky-footer-active';
	}

	$yith_wcwl    = YITH_WCWL();
	$wishlist_url = $yith_wcwl->get_wishlist_url();
	?>
	<div class="<?php echo esc_attr( $woo_wishlist_classes ); ?>">
		<a href="<?php echo esc_url( $wishlist_url ); ?>"><i class="vc_icon_element-icon glyph-icon pgsicon-ecommerce-heart"></i>
		<span class="wishlist ciyashop-wishlist-count">
			<?php echo esc_html( $yith_wcwl->count_products() ); ?>
		</span>
		</a>
	</div>
	<?php
} else {
	global $ciyashop_options;

	if ( isset( $ciyashop_options['show_wishlist'] ) && $ciyashop_options['show_wishlist'] ) {
		$cs_wishlist = new Ciyashop_Wishlist();
		$wishlist_id = ( isset( $ciyashop_options['cs_wishlist_page'] ) && ! empty( $ciyashop_options['cs_wishlist_page'] ) ) ? $ciyashop_options['cs_wishlist_page'] : '';
		if ( $wishlist_id ) {
			$wishlist_url = get_permalink( $ciyashop_options['cs_wishlist_page'] );
		} else {
			$wishlist_url = '#';
		}

		if ( $wishlist_id && is_page( $wishlist_id ) ) {
			$woo_wishlist_classes .= ' sticky-footer-active';
		}
		?>
		<div class="<?php echo esc_attr( $woo_wishlist_classes ); ?>">
			<a href="<?php echo esc_url( $wishlist_url ); ?>"><i class="vc_icon_element-icon glyph-icon pgsicon-ecommerce-heart"></i>
			<span class="wishlist ciyashop-wishlist-count">
				<?php echo esc_html( $cs_wishlist->count_products() ); ?>
			</span>
			</a>
		</div>
		<?php
	}
}
