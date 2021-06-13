<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Cookie info file.
 *
 * @package CiyaShop
 */

global $ciyashop_options;
if ( isset( $ciyashop_options['cookies_info'] ) && $ciyashop_options['cookies_info'] ) {
	if ( isset( $_COOKIE['ciyashop_cookies'] ) && 'accepted' === $_COOKIE['ciyashop_cookies'] ) {
		return;
	}

	$page_id = false;
	if ( isset( $ciyashop_options['cookies_policy_page'] ) ) {
		$page_id = $ciyashop_options['cookies_policy_page'];
	}
	?>
	<div class="ciyashop-cookies-info">
		<div class="ciyashop-cookies-inner">
			<div class="cookies-info-text">
				<?php echo do_shortcode( $ciyashop_options['cookies_text'] ); ?>
			</div>
			<div class="cookies-buttons">
				<a href="#" class="cookies-info-accept-btn"><?php esc_html_e( 'Accept', 'ciyashop' ); ?></a>
				<?php if ( $page_id ) : ?>
					<a href="<?php echo esc_url( get_permalink( $page_id ) ); ?>" class="cookies-more-btn"><?php esc_html_e( 'More info', 'ciyashop' ); ?></a>
				<?php endif ?>
			</div>
		</div>
	</div>
	<?php
}
