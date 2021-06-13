<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Opening Hours.
 *
 * @package CiyaShop
 */

global $ciyashop_options;

$opening_hours = ( isset( $ciyashop_options['opening_hours'] ) && $ciyashop_options['opening_hours'] ) ? $ciyashop_options['opening_hours'] : '';
if ( $opening_hours ) {
	?>
	<i class="fa fa-clock-o"></i> <?php echo esc_html( $opening_hours ); ?>;
	<?php
}
