<?php
/**
 * The template for displaying portfolio archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Ciyashop
 */

get_header();

global $ciyashop_options, $post, $wp_query;

$load_more_button_style = '';

$portfolio_style            = ( isset( $ciyashop_options['portfolio_style'] ) && ! empty( $ciyashop_options['portfolio_style'] ) ) ? $ciyashop_options['portfolio_style'] : 'style-1';
$portfolio_space            = ( isset( $ciyashop_options['portfolio_space'] ) ) ? $ciyashop_options['portfolio_space'] : '10';
$ciyashop_portfolio_sidebar = $ciyashop_options['portfolio_sidebar'];

$portfolio_classes[] = 'portfolio-' . $portfolio_style;
$portfolio_classes[] = 'portfolio-content-area';
$portfolio_classes[] = 'image_popup-gallery';
$portfolio_classes[] = 'portfolio-space-' . $portfolio_space;

if ( isset( $ciyashop_options['portfolio_column_size'] ) && ! empty( $ciyashop_options['portfolio_column_size'] ) ) {
	$portfolio_classes[] = 'column-' . $ciyashop_options['portfolio_column_size'];
} else {
	$portfolio_classes[] = 'column-3';
}

if ( isset( $ciyashop_options['portfolio_pagination'] ) && 'infinite_scroll' === $ciyashop_options['portfolio_pagination'] ) {
	$portfolio_classes[]    = 'portfolio-infinite_scroll';
	$load_more_button_style = 'pointer-events: none; cursor: default;';
}

$portfolio_section_classes[] = 'portfolio-section';
if ( isset( $ciyashop_options['display_portfolio_categories_filters'] ) && 1 === (int) $ciyashop_options['display_portfolio_categories_filters'] ) {
	$portfolio_classes[]         = 'isotope';
	$portfolio_section_classes[] = 'isotope-wrapper';
} else {
	$portfolio_classes[] = 'isotope-off';
}

$portfolio_section_classes = implode( ' ', array_filter( array_unique( $portfolio_section_classes ) ) );
$portfolio_classes         = implode( ' ', array_filter( array_unique( $portfolio_classes ) ) );

$max_pages    = $wp_query->max_num_pages;
$current_page = ( get_query_var( 'paged' ) > 1 ) ? get_query_var( 'paged' ) : 1;
$next_link    = next_posts( $max_pages, false );
$next_page    = ! empty( $next_link ) ? $current_page + 1 : '';

$filter_args = array(
	'taxonomy' => 'portfolio-category',
	'fields'   => 'id=>name',
);

$filter_terms = get_terms( $filter_args );
?>
<div id="primary" class="content-area portfolio-page">
	<div class="row">
		<div class="<?php ciyashop_classes_main_area(); ?>">
			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">
					<div class="<?php echo esc_attr( $portfolio_section_classes ); ?>">
						<?php
						if ( isset( $ciyashop_options['display_portfolio_categories_filters'] ) && 1 === (int) $ciyashop_options['display_portfolio_categories_filters'] && ! is_wp_error( $filter_terms ) && is_array( $filter_terms ) && ! empty( $filter_terms ) ) {
							?>
							<div class="row no-gutter">
								<div class="col-sm-12">
									<div class="isotope-filters">
										<button data-filter="" class="all active"><?php echo esc_html__( 'All', 'ciyashop' ); ?></button>
										<?php
										foreach ( $filter_terms as $filter_key => $filter_name ) {
											?>
											<button data-filter="<?php echo esc_attr( $filter_key ); ?>"><?php echo esc_html( $filter_name ); ?></button>
											<?php
										}
										?>
									</div>
								</div>
							</div>
							<?php
						}
						?>
						<div class="<?php echo esc_attr( $portfolio_classes ); ?>">
						<?php

							/* Start the Loop */
						if ( have_posts() ) :
							while ( have_posts() ) :
								the_post();

								$item_classes   = array();
								$item_groups    = array();
								$item_classes[] = 'portfolio-grid-item';

								if ( isset( $ciyashop_options['display_portfolio_categories_filters'] ) && 1 === (int) $ciyashop_options['display_portfolio_categories_filters'] ) {
									$item_classes[] = 'grid-item';
									$post_terms     = get_the_terms( $post->ID, 'portfolio-category' );
									if ( $post_terms && ! is_wp_error( $post_terms ) && ! empty( $post_terms ) ) {
										foreach ( $post_terms as $post_term ) {
											$item_groups[] = $post_term->term_id;
										}
									}
								}

								$item_classes = join( ' ', $item_classes );
								?>
								<div class="<?php echo esc_attr( $item_classes ); ?>" data-groups="<?php echo esc_attr( wp_json_encode( $item_groups ) ); ?>">
								<?php
								/*
								 * Include the Post-Format-specific template for the content.
								 * If you want to override this in a child theme, then include a file
								 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
								 */
								get_template_part( 'template-parts/content', 'portfolio' );
								?>
								</div>
								<?php
							endwhile;
							wp_reset_postdata();

						else : // phpcs:ignore Generic.WhiteSpace.ScopeIndent.IncorrectExact

								get_template_part( 'template-parts/content', 'none' );

						endif;
						// phpcs:ignore Generic.WhiteSpace.ScopeIndent.IncorrectExact ?>
						</div>
						<?php
						if ( isset( $ciyashop_options['portfolio_pagination'] ) && 'pagination' === $ciyashop_options['portfolio_pagination'] ) {
							?>
								<div class="row">
									<nav class="navigation pagination clearfix col-lg-12 col-md-12 text-center">
										<?php
										the_posts_pagination(
											array(
												'prev_text' => '<span class="icon-arrow-left">&laquo;</span> <span class="screen-reader-text">' . esc_html__( 'Previous page', 'ciyashop' ) . '</span>',
												'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next page', 'ciyashop' ) . '</span> <span class="icon-arrow-right">&raquo;</span>',
												'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'ciyashop' ) . ' </span>',
												'base'     => esc_url( str_replace( 999999999, '%#%', get_pagenum_link( 999999999 ) ) ),
												'format'   => '?paged=%#%',
												'current'  => max( 1, get_query_var( 'paged' ) ),
												'type'     => 'list',
												'end_size' => 3,
												'mid_size' => 3,
											)
										);
										?>
									</nav>
								</div>
							<?php
						}
						if ( isset( $ciyashop_options['portfolio_pagination'] ) && ( 'load_more' === $ciyashop_options['portfolio_pagination'] || 'infinite_scroll' === $ciyashop_options['portfolio_pagination'] ) ) {
							?>
							<div class="portfolio-more-button">
								<?php
								if ( ! empty( $next_page ) ) {
									?>
									<a href="#" style="<?php echo esc_attr( $load_more_button_style ); ?>" 
										data-max_pages="<?php echo esc_attr( $max_pages ); ?>" 
										data-current_page="<?php echo esc_attr( $current_page ); ?>" 
										data-next_page="<?php echo esc_attr( $next_page ); ?>" 
										data-next_link="<?php echo esc_attr( $next_link ); ?>">
									<?php esc_html_e( 'Load more...', 'ciyashop' ); ?></a>
									<?php
								}
								?>
							</div>
							<?php
						}
						?>
					</div>
				</main><!-- #main -->
			</div>
		</div><!-- #row -->
		<?php
		if ( ( 'left_sidebar' === $ciyashop_portfolio_sidebar || 'right_sidebar' === $ciyashop_portfolio_sidebar ) && is_active_sidebar( 'sidebar-1' ) ) {
			get_sidebar();
		}
		?>
	</div><!-- #row -->
</div><!-- #primary -->
<?php
get_footer();
