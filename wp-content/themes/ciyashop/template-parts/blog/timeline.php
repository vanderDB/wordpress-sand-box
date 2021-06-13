<?php
/**
 * Template part for displaying blog content.
 *
 * @package CiyaShop
 */

global $wp_query, $ciyashop_blog_sidebar, $ciyashop_blog_layout, $ciyashop_timeline_type;

$layout_sr = 0;
?>
<ul class="timeline">
	<li class="entry-date"><span><?php echo esc_html( date_i18n( 'F Y', time() ) ); ?></span></li>
	<?php
	// Start the loop.
	while ( have_posts() ) {
		the_post();

		$layout_sr++;

		$timeline_class = '';
		if ( 'with_sidebar' === $ciyashop_timeline_type ) {
			$timeline_class .= ' timeline-inverted';
		} else {
			if ( 0 === $layout_sr % 2 ) {
				$timeline_class .= ' timeline-inverted';
			}
		}
		?>
		<li class="timeline-item<?php echo esc_attr( $timeline_class ); ?>">
			<div class="timeline-badge primary"><?php echo sprintf( '<a>%1$s<span>%2$s</span></a>', esc_attr( get_the_date( 'j' ) ), esc_attr( get_the_date( 'M' ) ) ); ?></div>
			<div class="timeline-panel">
				<?php
				$part_1 = "template-parts/blog/$ciyashop_blog_layout/content";
				$part_2 = get_post_format();
				if ( ( $part_2 && ! empty( locate_template( "$part_1-$part_2.php" ) ) ) || ( ! empty( locate_template( "$part_1.php" ) ) ) ) {
					get_template_part( $part_1, $part_2 );
				} else {
					get_template_part( 'template-parts/blog/classic/content', get_post_format() );
				}
				?>
			</div>
		</li>
		<?php
	}
	wp_reset_postdata();
	// End the loop.
	$max_pages    = $wp_query->max_num_pages;
	$current_page = ( get_query_var( 'paged' ) > 1 ) ? get_query_var( 'paged' ) : 1;
	$next_link    = next_posts( $max_pages, false );
	$next_page    = ! empty( $next_link ) ? $current_page + 1 : '';
	if ( ! empty( $next_page ) ) {
		?>
		<li class="entry-date-bottom"> <a href="#" data-max_pages="<?php echo esc_attr( $max_pages ); ?>" data-current_page="<?php echo esc_attr( $current_page ); ?>" data-next_page="<?php echo esc_attr( $next_page ); ?>" data-next_link="<?php echo esc_attr( $next_link ); ?>"><?php esc_html_e( 'Load more...', 'ciyashop' ); ?></a></li>
		<?php
	} else {
		?>
		<li class="entry-date-bottom"> <a href="#" class ="disabled" data-max_pages="<?php echo esc_attr( $max_pages ); ?>" data-current_page="<?php echo esc_attr( $current_page ); ?>" data-next_page="<?php echo esc_attr( $next_page ); ?>" data-next_link="<?php echo esc_attr( $next_link ); ?>"><?php esc_html_e( 'No more posts to load', 'ciyashop' ); ?></a></li>
		<?php
	}
	?>
	<li class="clearfix timeline-inverted timeline-inverted-end"></li>
</ul>
