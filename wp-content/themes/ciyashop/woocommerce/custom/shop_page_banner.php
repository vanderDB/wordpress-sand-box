<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Shop page banner
 *
 * @package WooCommerce/Templates
 */

global $ciyashop_options;

$image_url = ciyashop_shop_banner_img_src();

if ( ! $image_url ) {
	return;
}

?>
<div class="right-banner">
	<?php
	if ( isset( $ciyashop_options['enable_lazyload'] ) && $ciyashop_options['enable_lazyload'] ) {
		echo '<img alt="' . esc_attr__( 'Shop Banner', 'ciyashop' ) . '" src="' . esc_url( LOADER_IMAGE ) . '" data-src="' . esc_url( $image_url ) . '" class="img-fluid ciyashop-lazy-load">';
	} else {
		echo '<img alt="' . esc_attr__( 'Shop Banner', 'ciyashop' ) . '" src="' . esc_url( $image_url ) . '" class="img-fluid">';
	}
	?>
</div>
