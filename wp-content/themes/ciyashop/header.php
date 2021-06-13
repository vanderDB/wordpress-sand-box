<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package CiyaShop
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php echo esc_attr( get_bloginfo( 'charset' ) ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="format-detection" content="telephone=no" />
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url' ) ); ?>">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php

if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}

/**
 * Fires before page wrapper.
 *
 * @visible true
 */
do_action( 'ciyashop_before_page_wrapper' );
?>

<div id="page" class="<?php ciyashop_page_classes(); ?>">

	<?php
	/**
	 * Fires before header wrapper.
	 *
	 * @Functions hooked in to ciyashop_before_header_wrapper hook.
	 * @hooked ciyashop_preloader - 10
	 *
	 * @visible true
	 */
	do_action( 'ciyashop_before_header_wrapper' );
	?>

	<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) : ?>

		<!--header -->
		<header id="masthead" class="<?php ciyashop_header_classes(); ?>">
			<div id="masthead-inner">

				<?php
				/**
				 * Fires before header.
				 *
				 * @visible true
				 */
				do_action( 'ciyashop_before_header' );
				?>

				<?php 
				if ( 'static_block' !== get_post_type() ) {
					get_template_part( 'template-parts/header/header_type/' . ciyashop_header_type() );
				}
				?>

				<?php
				/**
				 * Fires after header.
				 *
				 * @visible true
				 */
				do_action( 'ciyashop_after_header' );
				?>

			</div><!-- #masthead-inner -->
		</header><!-- #masthead -->

	<?php endif ?>

	<?php
	/**
	 * Fires after header wrapper.
	 *
	 * @visible true
	 */
	do_action( 'ciyashop_after_header_wrapper' );
	?>

	<?php
	/**
	 * Fires before site content.
	 *
	 * @visible true
	 */
	do_action( 'ciyashop_before_content' );
	?>

	<div id="content" class="site-content" tabindex="-1">

		<?php
		/**
		 * Hook: ciyashop_content_top.
		 *
		 * @Functions hooked in to ciyashop_content_top hook.
		 * @hooked ciyashop_page_header - 20
		 * @hooked ciyashop_shop_category_carousel - 30
		 *
		 * @visible true
		 */
		do_action( 'ciyashop_content_top' );
		?>

		<div class="<?php ciyashop_content_wrapper_classes( 'content-wrapper' ); ?>"><!-- .content-wrapper -->
			<div class="<?php ciyashop_content_container_classes( 'container' ); ?>"><!-- .container -->
