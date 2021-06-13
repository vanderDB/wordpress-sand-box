<?php
/**
 * The template for displaying the portfolio related post
 *
 * @package Ciyashop
 */

global $ciyashop_options;

$args            = array(
	'post_type'      => 'portfolio',
	'posts_per_page' => ( isset( $ciyashop_options['no_of_related_portfolio'] ) ) ? $ciyashop_options['no_of_related_portfolio'] : 5,
	'post__not_in'   => array( get_the_ID() ),
);
$portfolio_query = new WP_Query( $args );
if ( ! $portfolio_query->have_posts() ) {
	return;
}

$owl_options_args = array(
	'items'              => 4,
	'loop'               => true,
	'dots'               => true,
	'nav'                => true,
	'margin'             => 30,
	'autoplay'           => false,
	'autoplayHoverPause' => true,
	'smartSpeed'         => 1000,
	'responsive'         => array(
		0    => array(
			'items' => 1,
		),
		576  => array(
			'items' => 1,
		),
		768  => array(
			'items' => 2,
		),
		992  => array(
			'items' => 3,
		),
		1200 => array(
			'items' => 4,
		),
	),
	'navText'            => array(
		"<i class='fas fa-angle-left fa-2x'></i>",
		"<i class='fas fa-angle-right fa-2x'></i>",
	),
	'lazyLoad'           => ( isset( $ciyashop_options['enable_lazyload'] ) && $ciyashop_options['enable_lazyload'] ) ? true : false,
);

$owl_options             = wp_json_encode( $owl_options_args );
$portfolio_style         = ( isset( $ciyashop_options['portfolio_style'] ) && $ciyashop_options['portfolio_style'] ) ? $ciyashop_options['portfolio_style'] : 'style-1';
$portfolio_classes       = 'portfolio-content-area image_popup-gallery item portfolio-' . $portfolio_style;
$related_portfolio_title = ( isset( $ciyashop_options['related_portfolio_title'] ) && $ciyashop_options['related_portfolio_title'] ) ? $ciyashop_options['related_portfolio_title'] : '';
?>
<section class="related-portfolio">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="section-title"><h2 class="title"><?php echo esc_html( $related_portfolio_title ); ?></h2></div>
				<div class="related-portfolio-section">
					<div class="owl-carousel owl-theme owl-carousel-options" data-owl_options="<?php echo esc_attr( $owl_options ); ?>">
						<?php
						while ( $portfolio_query->have_posts() ) {
							$portfolio_query->the_post();
							?>
							<div class="<?php echo esc_attr( $portfolio_classes ); ?>">
								<div class="project-item">
									<div class="project-info">
										<div class="project-image">
											<?php
											if ( has_post_thumbnail() ) {
												if ( isset( $ciyashop_options['enable_lazyload'] ) && $ciyashop_options['enable_lazyload'] ) {
													echo '<img class="owl-lazy" src="' . esc_url( LOADER_IMAGE ) . '" data-src="' . esc_url( get_the_post_thumbnail_url( '', 'ciyashop-latest-post-thumbnail' ) ) . '" alt="' . esc_attr( get_the_title() ) . '" />';
												} else {
													echo '<img src="' . esc_url( get_the_post_thumbnail_url( '', 'ciyashop-latest-post-thumbnail' ) ) . '" alt="' . esc_attr( get_the_title() ) . '" />';
												}
											}
											?>
											<div class="portfolio-control">
												<a href="<?php echo esc_url( get_permalink() ); ?>" class="portfolio-link"><i class="fa fa-link"></i></span></a>
												<a href="<?php the_post_thumbnail_url( 'full' ); ?>" class="button popup-link image_popup-img"><i class="fa fa-arrows-alt"></i></a>
											</div>
										</div>
										<div class="overlay">
											<div class="overlay-content">
												<a class="category-link" href="<?php echo esc_url( get_permalink() ); ?>"><?php esc_html_e( 'View Portfolio', 'ciyashop' ); ?></a>
												<?php the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php
						}
						wp_reset_postdata();
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
