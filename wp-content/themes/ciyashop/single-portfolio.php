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

			if ( isset( $ciyashop_options['display_portfolio_navigation'] ) && $ciyashop_options['display_portfolio_navigation'] ) {

				$next_post     = get_next_post();
				$previous_post = get_previous_post();
				?>
				<nav class="navigation portfolio-navigation" role="navigation">
					<div class="nav-links">
						<?php
						if ( ! empty( $previous_post ) ) {
							?>
							<div class="nav-previous">
								<a href="<?php echo esc_url( get_permalink( $previous_post->ID ) ); ?>" rel="prev">
									<div class="portfolio-image">
										<?php echo get_the_post_thumbnail( $previous_post->ID, 'thumbnail' ); ?>
									</div>
									<span class="portfolio-arrow"><i class="fa fa-long-arrow-left"></i></span>
									<span class="portfolio-title"><?php echo esc_html( get_the_title( $previous_post->ID ) ); ?></span>
									<span class="portfolio-next"><?php echo esc_html_e( 'Previous', 'ciyashop' ); ?></span>
								</a>
							</div>
							<?php
						}
						?>

						<div class="portfolio-back-to-archive">
							<a href="<?php echo esc_url( get_post_type_archive_link( 'portfolio' ) ); ?>">
								<span class="portfolio-back-to-list">
									<i class="fa fa-th-large" aria-hidden="true"></i>
								</span>
							</a>
						</div>	

						<?php
						if ( ! empty( $next_post ) ) {
							?>
							<div class="nav-next">
								<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" rel="next">
									<div class="portfolio-image">
										<?php echo get_the_post_thumbnail( $next_post->ID, 'thumbnail' ); ?>
									</div>
									<span class="portfolio-arrow"><i class="fa fa-long-arrow-right"></i></span>
									<span class="portfolio-title"><?php echo esc_html( get_the_title( $next_post->ID ) ); ?></span>
									<span class="portfolio-next"><?php echo esc_html_e( 'Next', 'ciyashop' ); ?></span>
								</a>
							</div>
							<?php
						}
						?>
					</div>
				</nav>
				<?php
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
