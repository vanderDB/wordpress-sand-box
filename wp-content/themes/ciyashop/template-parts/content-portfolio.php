<?php
/**
 * Template part for displaying page content in portfolio.
 *
 * @package CiyaShop
 */

?>
<div class="project-item">
	<div class="project-info">
		<div class="project-image">
			<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'full' );
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
