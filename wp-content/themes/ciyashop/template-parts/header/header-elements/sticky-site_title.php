<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Sticky Site Title.
 *
 * @package CiyaShop
 */

/**
 * Fires before sticky site title wrapper start.
 *
 * @visible true
 */
do_action( 'ciyashop_before_sticky_site_title_wrapper_start' ); ?>

<div class="sticky-site-title-wrapper">

	<?php
	/**
	 * Fires after sticky site title wrapper start.
	 *
	 * @visible true
	 */
	do_action( 'ciyashop_after_sticky_site_title_wrapper_start' );
	?>

	<div class="sticky-site-title h1">

		<?php
		/**
		 * Fires before sticky site title link.
		 *
		 * @visible true
		 */
		do_action( 'ciyashop_before_sticky_site_title_link' );
		?>

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">

			<?php
			/**
			 * Fires before sticky site title.
			 *
			 * @visible true
			 */
			do_action( 'ciyashop_before_sticky_site_title' );
			?>

			<?php
			/**
			 * Hook: ciyashop_sticky_site_title
			 *
			 * @Functions hooked in to ciyashop_page_after action
			 * @hooked ciyashop_sticky_logo - 10
			 *
			 * @visible true
			 */
			do_action( 'ciyashop_sticky_site_title' );
			?>

			<?php
			/**
			 * Fires after sticky site title.
			 *
			 * @visible true
			 */
			do_action( 'ciyashop_after_sticky_site_title' );
			?>

		</a>

		<?php
		/**
		 * Fires after sticky site title link.
		 *
		 * @visible true
		 */
		do_action( 'ciyashop_after_sticky_site_title_link' );
		?>

	</div>

	<?php
	/**
	 * Fires before sticky site title wrapper end.
	 *
	 * @Functions hooked in to ciyashop_before_sticky_site_title_wrapper_end action
	 * @hooked ciyashop_site_description - 10
	 *
	 * @visible true
	 */
	do_action( 'ciyashop_before_sticky_site_title_wrapper_end' );
	?>

</div>

<?php
/**
 * Fires after sticky site title wrapper start.
 *
 * @visible true
 */
do_action( 'ciyashop_after_sticky_site_title_wrapper_end' );
