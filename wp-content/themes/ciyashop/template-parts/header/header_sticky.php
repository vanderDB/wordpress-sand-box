<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Header Sticky file.
 *
 * @package CiyaShop
 */

/**
 * Fires before sticky header.
 *
 * @visible true
 */
do_action( 'ciyashop_before_header_sticky' );

$header_classes = ciyashop_header_sticky_classes();

?>

<div id="header-sticky" class="<?php echo esc_attr( $header_classes ); ?>">
	<div class="header-sticky-inner">

		<div class="container">
			<div class="row align-items-center">

				<div class="col-lg-3 col-md-3 col-sm-3">
					<?php get_template_part( 'template-parts/header/header-elements/sticky-site_title' ); ?>
				</div>

				<div class="col-lg-9 col-md-9 col-sm-9">
					<?php
					/**
					 * Fires before sticky navigation content.
					 *
					 * @visible true
					 */
					do_action( 'ciyashop_before_sticky_nav_content' );

					/**
					 * Hook: ciyashop_sticky_nav_content
					 *
					 * @Functions hooked in to ciyashop_sticky_nav_content action
					 * @hooked ciyashop_sticky_nav                  - 10
					 * @visible true
					 */
					do_action( 'ciyashop_sticky_nav_content' );

					/**
					 * Fires after sticky navigation content.
					 *
					 * @visible true
					 */
					do_action( 'ciyashop_after_sticky_nav_content' );
					?>
				</div>
			</div>
		</div>

	</div><!-- #header-sticky -->
</div>

<?php
/**
 * Fires after sticky header.
 *
 * @visible true
 */
do_action( 'ciyashop_after_header_sticky' );
