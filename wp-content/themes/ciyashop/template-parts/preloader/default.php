<?php
/**
 * The template for displaying the preloader
 *
 * @package Ciyashop
 */

global $ciyashop_options;

$preloader              = ( isset( $ciyashop_options['preloader'] ) ) ? $ciyashop_options['preloader'] : 0;
$preloader_source       = ( isset( $ciyashop_options['preloader_source'] ) ) ? $ciyashop_options['preloader_source'] : 'default';
$preloader_image        = ( isset( $ciyashop_options['preloader_image'] ) ) ? $ciyashop_options['preloader_image'] : '';
$predefine_loader_image = ( isset( $ciyashop_options['predefine_loader_image'] ) ) ? $ciyashop_options['predefine_loader_image'] : '';

if ( $preloader && defined( 'PGSCORE_URL' ) ) {
	if ( 'custom' === $preloader_source && ! empty( $preloader_image['url'] ) ) {
		?>
		<div id="preloader" class="preloader-type-custom">
			<div id="loading-center">
				<img src="<?php echo esc_url( $preloader_image['url'] ); ?>" alt="<?php esc_attr_e( 'Loading...', 'ciyashop' ); ?>">
			</div>
		</div>
		<?php
	} elseif ( 'predefine_loader' === $preloader_source && ! empty( $predefine_loader_image ) ) {

		$preloader_image = PGSCORE_PATH . 'images/options/loader/' . $predefine_loader_image . '.gif';
		if ( file_exists( $preloader_image ) ) {
			$preloader_image = PGSCORE_URL . 'images/options/loader/' . $predefine_loader_image . '.gif';
		} else {
			$preloader_image = PGSCORE_URL . 'images/options/loader/' . $predefine_loader_image . '.svg';
		}
		?>
		<div id="preloader" class="preloader-type-custom">
			<div id="loading-center">
				<img src="<?php echo esc_url( $preloader_image ); ?>" alt="<?php esc_attr_e( 'Loading...', 'ciyashop' ); ?>">
			</div>
		</div>
		<?php
	} else {
		$preloader_image = PGSCORE_URL . 'images/options/loader/loader19.svg';
		?>
		<div id="preloader" class="preloader-type-default">
			<div class="clear-loading loading-effect">
				<img src="<?php echo esc_url( $preloader_image ); ?>" alt="<?php esc_attr_e( 'Loading...', 'ciyashop' ); ?>">
			</div>
		</div>
		<?php
	}
}
