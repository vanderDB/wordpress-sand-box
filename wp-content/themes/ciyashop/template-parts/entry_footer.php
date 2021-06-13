<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Template part for displaying page content in footer.
 *
 * @package CiyaShop
 */

?>
<footer class="entry-footer clearfix">
	<?php
	global $ciyashop_options;

	if ( ! is_single() ) {
		?>
		<a href="<?php echo esc_url( get_the_permalink() ); ?>" class="readmore"><?php esc_html_e( 'Read More', 'ciyashop' ); ?></a>
		<?php
	}

	$posttype = get_post_type( $post );
	if ( ( 'post' === $posttype ) && ( is_home() || is_archive() || is_single() ) ) {
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
						'class' => 'single-share-box mk-box-to-trigger',
					)
				);
				?>
			</div>
			<?php
		}
	}
	?>
</footer>
