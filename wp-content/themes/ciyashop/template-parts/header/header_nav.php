<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Header Nav file.
 *
 * @package CiyaShop
 */

/**
 * Fires before header navigation.
 *
 * @visible true
 */
do_action( 'ciyashop_before_header_nav' );?>

<div class="<?php ciyashop_header_nav_classes( 'header-nav' ); ?>">
	<div class="header-nav-wrapper">

		<?php
		/**
		 * Fires before header navigation content.
		 *
		 * @Functions hooked in to ciyashop_before_header_nav_content action
		 * @hooked ciyashop_before_header_nav_content_wrapper_start  - 10
		 *
		 * @visible true
		 */
		do_action( 'ciyashop_before_header_nav_content' );

		/**
		 * Hook: ciyashop_header_nav_content
		 *
		 * @Functions hooked in to ciyashop_header_nav_content action
		 * @hooked ciyashop_category_menu                 - 10
		 * @hooked ciyashop_catmenu_primenu_separator     - 15
		 * @hooked ciyashop_primary_menu                  - 20
		 *
		 * @visible true
		 */
		do_action( 'ciyashop_header_nav_content' );

		/**
		 * Fires after header navigation content.
		 *
		 * @Functions hooked in to ciyashop_after_header_nav_content action
		 * @hooked ciyashop_after_header_nav_content_wrapper_end  - 10
		 *
		 * @visible true
		 */
		do_action( 'ciyashop_after_header_nav_content' );
		?>

	</div>
</div>

<?php
/**
 * Fires after header navigation.
 *
 * @visible true
 */
do_action( 'ciyashop_after_header_nav' );
