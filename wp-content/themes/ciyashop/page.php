<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package CiyaShop
 */

get_header();
?>
<div class="row">
	<div class="<?php ciyashop_classes_main_area(); ?>">
		<div id="primary" class="content-area">
			<main id="main" class="site-main">

				<?php
				while ( have_posts() ) :
					the_post();

					/**
					 * Fires before page.
					 *
					 * @visible true
					 */
					do_action( 'ciyashop_page_before' );

					get_template_part( 'template-parts/content', 'page' );

					/**
					 * Hook: ciyashop_page_after
					 *
					 * @Functions hooked in to ciyashop_page_after action
					 * @hooked ciyashop_display_comments - 10
					 *
					 * @visible true
					 */
					do_action( 'ciyashop_page_after' );

				endwhile; // End of the loop.
				?>
			</main><!-- #main -->
		</div><!-- #primary -->
	</div>
	<?php get_sidebar(); ?>
</div>
<?php
get_footer();
