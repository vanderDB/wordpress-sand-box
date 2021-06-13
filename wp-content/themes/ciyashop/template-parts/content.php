<?php
/**
 * Template part for displaying posts.
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
		<?php
		get_template_part( 'template-parts/entry_meta-date' );
		if ( is_single() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			get_template_part( 'template-parts/entry_meta' );
		endif;
		?>
		</div>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
		if ( is_single() ) {
			the_content(
				sprintf(
					/* translators: %s: Name of current post. */
					wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'ciyashop' ), array( 'span' => array( 'class' => array() ) ) ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				)
			);
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
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php
		if ( is_single() ) {
			ciyashop_entry_footer();
		} else {
			?>
			<a href="<?php echo esc_url( get_the_permalink() ); ?>" class="readmore"><?php esc_html_e( 'Read More', 'ciyashop' ); ?></a>
			<?php
			$social_icons = '';
			ob_start();
			ciyashop_social_share();
			$social_icons = ob_get_contents();
			ob_end_clean();
			if ( ! empty( $social_icons ) ) {
				?>
				<div class="entry-social share pull-right">
					<a href="javascript:void(0)" class="share-button" data-title="<?php esc_attr_e( 'Share it on', 'ciyashop' ); ?>">
						<i class="fa fa-share-alt"></i>
					</a>
					<?php
					ciyashop_social_share(
						array(
							'class' => 'share-links',
						)
					);
					?>
				</div>
				<?php
			}
		}
		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
