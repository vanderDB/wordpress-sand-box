<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Sticky Footer account.
 *
 * @package CiyaShop
 */

if ( class_exists( 'WooCommerce' ) ) {

	$woo_account_classes[] = 'sticky-footer-mobile-woo-account';
	if ( is_account_page() ) {
		$woo_account_classes[] = 'sticky-footer-active';
	}
	$woo_account_classes = implode( ' ', array_filter( array_unique( $woo_account_classes ) ) );
	?>
	<div class="<?php echo esc_attr( $woo_account_classes ); ?>">
		<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" title="<?php esc_attr_e( 'My Account', 'ciyashop' ); ?>"><i class="vc_icon_element-icon glyph-icon pgsicon-ecommerce-avatar"></i></a>
	</div>
	<?php
}
