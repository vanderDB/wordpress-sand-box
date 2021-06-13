<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Sticky Footer Home file.
 *
 * @package CiyaShop
 */

$home_classes[] = 'sticky-footer-mobile-home';
if ( is_front_page() ) {
	$home_classes[] = 'sticky-footer-active';
}
$home_classes = implode( ' ', array_filter( array_unique( $home_classes ) ) );
?>
<div class="<?php echo esc_attr( $home_classes ); ?>">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><i class="vc_icon_element-icon glyph-icon pgsicon-ecommerce-house"></i></a>
</div>
