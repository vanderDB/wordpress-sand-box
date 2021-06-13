<?php
/**
 * Template part for displaying blog content.
 *
 * @package CiyaShop
 */

global $ciyashop_options;

$ciyashop_blog_layout = ( isset( $ciyashop_options['blog_layout'] ) ) ? $ciyashop_options['blog_layout'] : 'classic';
$quote                = get_post_meta( get_the_ID(), 'quote', true );
$quote_author         = get_post_meta( get_the_ID(), 'quote_author', true );
$author_link          = get_post_meta( get_the_ID(), 'author_link', true );
$post_class           = 'post-no-image';

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?>>
	<?php
	if ( $quote ) {
		?>
		<div class="blog-entry-quote">
			<blockquote class="entry-quote">
				<i class="fa fa-quote-left"></i> 
				<p><?php echo wp_kses_post( $quote ); ?></p>
				<?php
				if ( $quote_author ) {
					?>
					<div class="quote-author text-right">
						<?php
						if ( $author_link ) {
							?>
							<a href="<?php echo esc_url( $author_link ); ?>">
							<?php
						}
						echo esc_html( "- $quote_author" );
						if ( $author_link ) {
							?>
							</a>
							<?php
						}
						?>
					</div>
					<?php
				}
				?>
			</blockquote>
		</div>
		<?php
	}
	?>
	<?php if ( 'classic' === $ciyashop_blog_layout || is_single() || is_archive() ) : ?>
		<div class="entry-header-section">
	<?php endif; ?>

	<?php
	get_template_part( 'template-parts/entry_meta-date' );

	if ( ! is_single() ) {
		?>
		<div class="entry-title">
			<?php the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
		</div>
		<?php
	}

	get_template_part( 'template-parts/entry_meta' );
	?>

	<?php if ( 'classic' === $ciyashop_blog_layout || is_single() || is_archive() ) : ?>
		</div>
	<?php endif; ?>

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
