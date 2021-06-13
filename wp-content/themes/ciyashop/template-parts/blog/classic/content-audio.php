<?php
/**
 * Template part for displaying blog content.
 *
 * @package CiyaShop
 */

global $ciyashop_options;

$ciyashop_blog_layout = ( isset( $ciyashop_options['blog_layout'] ) ) ? $ciyashop_options['blog_layout'] : 'classic';
$audio_file           = get_post_meta( get_the_ID(), 'audio_file', true );
$audio_file_data      = false;
$post_class[]         = 'post-no-image';

if ( $audio_file ) {
	$audio_file_data = ciyashop_acf_get_attachment( $audio_file );
}

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?>>    
	<?php
	if ( $audio_file_data ) {
		?>
		<div class="blog-entry-audio audio-video">
			<audio id="player2" src="<?php echo esc_url( $audio_file_data['url'] ); ?>" width="100%" controls="controls"></audio>
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

</article><!-- #post -->
