<?php
/**
 * Template part for displaying results in search pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package CiyaShop
 */

$post_class = '';
if ( ! has_post_thumbnail() ) {
	$post_class = 'post-no-image';
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?>>

	<div class="blog-image">
		<?php
		if ( has_post_thumbnail() ) {
			echo ciyashop_lazyload_thumbnail( get_the_ID(), 'full', array( 'img-fluid' ) ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
		}
		?>
	</div>
	<header class="entry-header">

		<div class="entry-header-section">
		<?php get_template_part( 'template-parts/entry_meta-date' ); ?>

		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php get_template_part( 'template-parts/entry_meta' ); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
		</div>

	</header><!-- .entry-header -->

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

</article><!-- #post-## -->
