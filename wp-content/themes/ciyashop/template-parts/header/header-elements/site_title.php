<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Site Title.
 *
 * @package CiyaShop
 */

global $ciyashop_options, $ciyashop_site_title_el;

$ciyashop_site_title_el = is_front_page() && ! isset( $ciyashop_site_title_el ) ? 'h1' : 'div';
$site_title_class       = is_front_page() ? 'site-title' : 'site-title';

/**
 * Filters site title class.
 *
 * @param string    $site_title_class      Class of the site title. Default site-title.
 *
 * @visible true
 */
$site_title_class = apply_filters( 'ciyashop_site_title_class', $site_title_class );
?>

<?php
/**
 * Fires before site title wrapper starts.
 *
 * @visible true
 */
do_action( 'ciyashop_before_site_title_wrapper_start' );
?>

<div class="site-title-wrapper">

	<?php
	/**
	 * Fires after site title wrapper starts.
	 *
	 * @visible true
	 */
	do_action( 'ciyashop_after_site_title_wrapper_start' );
	?>

	<<?php echo esc_attr( $ciyashop_site_title_el ); ?> class="<?php echo esc_attr( $site_title_class ); ?>">

		<?php
		/**
		 * Fires before site title link.
		 *
		 * @visible true
		 */
		do_action( 'ciyashop_before_site_title_link' );
		?>

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">

			<?php
			/**
			 * Fires before site title.
			 *
			 * @visible true
			 */
			do_action( 'ciyashop_before_site_title' );
			?>

			<?php
			/**
			 * Hook: ciyashop_site_title.
			 *
			 * @Functions hooked in to ciyashop_site_title action
			 * @hooked ciyashop_logo - 10
			 *
			 * @visible true
			 */
			do_action( 'ciyashop_site_title' );
			?>

			<?php
			/**
			 * Fires after site title.
			 *
			 * @visible true
			 */
			do_action( 'ciyashop_after_site_title' );
			?>

		</a>

		<?php
		/**
		 * Fires after site title link.
		 *
		 * @visible true
		 */
		do_action( 'ciyashop_after_site_title_link' );
		?>

	</<?php echo esc_attr( $ciyashop_site_title_el ); ?>>

	<?php
	/**
	 * Fires before site title wrapper end.
	 *
	 * @Functions hooked in to ciyashop_before_site_title_wrapper_end action
	 * @hooked ciyashop_site_description - 10
	 *
	 * @visible true
	 */
	do_action( 'ciyashop_before_site_title_wrapper_end' );
	?>

</div>

<?php
/**
 * Fires after site title wrapper end.
 *
 * @visible true
 */
do_action( 'ciyashop_after_site_title_wrapper_end' );
