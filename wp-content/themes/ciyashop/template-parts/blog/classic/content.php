<?php
/**
 * Template part for displaying blog content.
 *
 * @package CiyaShop
 */

global $ciyashop_options;

$post_class = '';
if ( ! has_post_thumbnail() ) {
	$post_class = 'post-no-image';
}

$blog_layout = 'classic';
if ( isset( $ciyashop_options['blog_layout'] ) && ! empty( $ciyashop_options['blog_layout'] ) ) {
	$blog_layout = $ciyashop_options['blog_layout'];
}
?>


<article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?>>
	<?php
	if ( has_post_thumbnail() ) {
		?>
		<div class="post-entry-image clearfix">
			<?php
			echo ciyashop_lazyload_thumbnail( get_the_ID(), 'ciyashop-blog-thumb', array( 'img-fluid' ) ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
			?>
		</div>
		<?php
	}
	
	if ( 'static_block' !== get_post_type() ) {
		if ( 'classic' === $blog_layout || is_archive() ) {
			?>
			<div class="entry-header-section">
			<?php
		}

		get_template_part( 'template-parts/entry_meta-date' );

		if ( ! is_single() ) :
			?>
			<div class="entry-title">
				<?php the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
			</div>
			<?php
		endif;

		get_template_part( 'template-parts/entry_meta' );

		if ( 'classic' === $blog_layout || is_archive() ) {
			?>
			</div>
			<?php
		}
	}
	?>

	<div class="entry-content">
		<?php
		if ( is_single() ) {
			the_content();
		} else {
			the_excerpt();
		}

		wp_link_pages(
			array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages', 'ciyashop' ) . ':</span>',
				'after'       => '</div>',
				'link_before' => '<span class="page-number">',
				'link_after'  => '</span>',
			)
		);
		?>
	</div>

	<?php get_template_part( 'template-parts/entry_footer' ); ?>

</article><!-- #post-## -->
