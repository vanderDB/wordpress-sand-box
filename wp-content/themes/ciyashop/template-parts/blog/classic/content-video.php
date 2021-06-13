<?php
/**
 * Template part for displaying blog content.
 *
 * @package CiyaShop
 */

global $ciyashop_options;

$video_type                = get_post_meta( get_the_ID(), 'video_type', true );
$post_format_video_youtube = get_post_meta( get_the_ID(), 'post_format_video_youtube', true );
$post_format_video_youtube = wp_oembed_get( $post_format_video_youtube );
$post_format_video_vimeo   = get_post_meta( get_the_ID(), 'post_format_video_vimeo', true );
$post_format_video_vimeo   = wp_oembed_get( $post_format_video_vimeo );
$mp4                       = get_post_meta( get_the_ID(), 'post_format_video_html5_0_mp4', true );
$webm                      = get_post_meta( get_the_ID(), 'post_format_video_html5_0_webm', true );
$ogv                       = get_post_meta( get_the_ID(), 'post_format_video_html5_0_ogv', true );
$cover                     = get_post_meta( get_the_ID(), 'post_format_video_html5_0_cover', true );
$ciyashop_blog_layout      = ( isset( $ciyashop_options['blog_layout'] ) ) ? $ciyashop_options['blog_layout'] : 'classic';
$post_class                = '';

if ( 'youtube' === $video_type && ! $post_format_video_youtube ) {
	$post_class = 'post-no-image';
} elseif ( 'vimeo' === $video_type && ! $post_format_video_vimeo ) {
	$post_class = 'post-no-image';
}

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?>>
	<div class="blog-entry post-1 clearfix">
		<?php
		// Check if video type is html5 and have rows.
		if ( 'html5' === $video_type ) {
			?>
			<div class="blog-entry-html-video audio-video">
				<?php
				if ( $cover ) {
					$cover_img = wp_get_attachment_url( $cover );
				} else {
					$cover_img = '';
				}
				?>
				<video poster="<?php echo esc_url( $cover_img ); ?>" controls="controls" preload="none">
					<?php
					if ( $mp4 ) {
						?>
						<!-- MP4 for Safari, IE9, iPhone, iPad, Android, and Windows Phone 7 -->
						<source type="video/mp4" src="<?php echo esc_url( wp_get_attachment_url( $mp4 ) ); ?>" />
						<?php
					}

					if ( $webm ) {
						$webm_data = wp_get_attachment_metadata( $webm );
						if ( 'video/webm' === $webm_data['mime_type'] ) {
							?>
							<!-- WebM/VP8 for Firefox4, Opera, and Chrome -->
							<source type="video/webm" src="<?php echo esc_url( wp_get_attachment_url( $webm ) ); ?>" />
							<?php
						}
					}

					if ( $ogv ) {
						$ogv_data = wp_get_attachment_metadata( $ogv );
						if ( 'video/ogg' === $ogv_data['mime_type'] ) {
							?>
							<!-- Ogg/Vorbis for older Firefox and Opera versions -->
							<source type="video/ogg" src="<?php echo esc_url( wp_get_attachment_url( $ogv ) ); ?>" />
							<?php
						}
					}
					?>
				</video>
			</div>
			<?php
		} elseif ( 'youtube' === $video_type && $post_format_video_youtube ) {
			// use preg_match to find iframe src.
			preg_match( '/src="(.+?)"/', $post_format_video_youtube, $matches );
			$src = isset( $matches[1] ) ? $matches[1] : '';

			// Remove existing params.
			$src = remove_query_arg( array( 'feature' ), $src );

			// add extra params to iframe src.
			$params  = array(
				'rel' => 0,
			);
			$new_src = add_query_arg( $params, $src );
			?>
			<div class="blog-entry-you-tube">
				<div class="js-video [youtube, widescreen]">
					<?php
					if ( isset( $ciyashop_options['enable_lazyload'] ) && $ciyashop_options['enable_lazyload'] ) {
						echo '<iframe class="ciyashop-lazy-load" src="' . esc_url( LOADER_IMAGE ) . '" data-src="' . esc_url( $new_src ) . '" frameborder="0" allowfullscreen></iframe>';
					} else {
						echo '<iframe src="' . esc_url( $new_src ) . '" frameborder="0" allowfullscreen></iframe>';
					}
					?>
				</div>
			</div>
			<?php
		} elseif ( 'vimeo' === $video_type && $post_format_video_vimeo ) {
			// use preg_match to find iframe src.
			preg_match( '/src="(.+?)"/', $post_format_video_vimeo, $matches );
			$src = isset( $matches[1] ) ? $matches[1] : '';
			?>
			<div class="blog-entry-vimeo">
				<div class="js-video [vimeo, widescreen]">
					<?php
					if ( isset( $ciyashop_options['enable_lazyload'] ) && $ciyashop_options['enable_lazyload'] ) {
						echo '<iframe class="ciyashop-lazy-load" src="' . esc_url( LOADER_IMAGE ) . '" data-src="' . esc_url( $src ) . '" frameborder="0" allowfullscreen="" mozallowfullscreen="" webkitallowfullscreen=""></iframe>';
					} else {
						echo '<iframe src="' . esc_url( $src ) . '" frameborder="0" allowfullscreen="" mozallowfullscreen="" webkitallowfullscreen=""></iframe>';
					}
					?>

				</div>
			</div>
			<?php
		}
		?>

		<?php if ( 'classic' === $ciyashop_blog_layout || is_single() || is_archive() ) : ?>
			<div class="entry-header-section">
		<?php endif; ?>

		<?php get_template_part( 'template-parts/entry_meta-date' ); ?>

		<?php
		if ( ! is_single() ) :
			?>
			<div class="entry-title">
				<?php the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
			</div>
			<?php
		endif;

		get_template_part( 'template-parts/entry_meta' );

		if ( 'classic' === $ciyashop_blog_layout || is_single() || is_archive() ) :
			?>
			</div>
			<?php
		endif;
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

	</div>

</article><!-- #post -->
