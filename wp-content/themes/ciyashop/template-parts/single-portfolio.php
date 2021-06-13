<?php
/**
 * The template for displaying all single portfolio
 *
 * @package CiyaShop
 */

get_header();
?>
<div class="row">
	<div class="col-sm-12">
		<div id="primary" class="content-area">
			<main id="main" class="site-main">
			<?php
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/portfolio/single', 'content' );

			endwhile; // End of the loop.

			if ( isset( $ciyashop_options['display_portfolio_navigation'] ) && 1 === (int) $ciyashop_options['display_portfolio_navigation'] ) {
				$next_post     = get_next_post();
				$previous_post = get_previous_post();

				the_post_navigation(
					array(
						'next_text' => '<div class="portfolio-image">' . ( ! empty( $next_post ) ? get_the_post_thumbnail( $next_post->ID, 'thumbnail' ) : '' ) . '</div>
						<span class="portfolio-arrow"><i class="fa fa-long-arrow-right"></i></span>
						<span class="portfolio-title">' . get_the_title() . '</span>
						<span class="portfolio-next">' . __( 'Next', 'ciyashop' ) . '</span>',
						'prev_text' => '<div class="portfolio-image">' . ( ! empty( $previous_post ) ? get_the_post_thumbnail( $previous_post->ID, 'thumbnail' ) : '' ) . '</div>
						<span class="portfolio-arrow"><i class="fa fa-long-arrow-left"></i></span>
						<span class="portfolio-title">' . get_the_title() . '</span>
						<span class="portfolio-previous">' . __( 'Previous', 'ciyashop' ) . '</span>',
					)
				);
			}

			if ( function_exists( 'ciyashop_related_portfolio' ) ) {
				ciyashop_related_portfolio();
			}

			?>
			</main><!-- #main -->
		</div><!-- #primary -->
	</div>
</div>
<?php
get_footer();
