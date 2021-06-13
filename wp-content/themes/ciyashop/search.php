<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package CiyaShop
 */

get_header(); ?>
<div class="row">       
	<div class="<?php ciyashop_classes_main_area(); ?>">
		<section id="primary" class="content-area">
			<main id="main" class="site-main">

			<?php
			if ( have_posts() ) :
				?>

				<header class="page-header">
					<h1 class="page-title">
						<?php
						/* translators: 1: title. */
						printf( esc_html__( 'Search Results for: %s', 'ciyashop' ), '<span>' . get_search_query() . '</span>' );
						?>
					</h1>
				</header><!-- .page-header -->

				<?php
				/* Start the Loop */
				while ( have_posts() ) :
					the_post();

					/**
					 * Run the loop for the search to output the results.
					 * If you want to overload this in a child theme then include a file
					 * called content-search.php and that will be used instead.
					 */
					get_template_part( 'template-parts/content', 'search' );

				endwhile;

				get_template_part( 'template-parts/posts-pagination' );

			else : // phpcs:ignore Generic.WhiteSpace.ScopeIndent.IncorrectExact

				get_template_part( 'template-parts/content', 'none' ); // phpcs:ignore Generic.WhiteSpace.ScopeIndent.Incorrect

			endif;
			// phpcs:ignore Generic.WhiteSpace.ScopeIndent.IncorrectExact ?>
			</main><!-- #main -->
		</section><!-- #primary -->
	</div>
	<?php get_sidebar(); ?> 
</div>
<?php get_footer(); ?>
