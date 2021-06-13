<p><?php
/**
 * Admin support
 *
 * @package CiyaShop
 */

printf(
	/* translators: $s: Theme Name */
	esc_html__( '%1$s comes with six months of free support for every license you purchase. You can extend the support through subscriptions via ThemeForest.', 'ciyashop' ),
	'<strong>' . $this->theme_data['Name'] . '</strong>'
); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
?></p>
<div class="support-columns mb-4">
	<div class="row">
		<div class="col-sm support-column support-column-ticket">
			<div class="support-column-inner">
				<img src="<?php echo esc_url( get_parent_theme_file_uri( '/images/admin/cs-admin/ticket.png' ) ); ?>" />
				<h2><?php esc_html_e( 'Ticket System', 'ciyashop' ); ?></h2>
				<p><?php esc_html_e( 'We offer excellent support through our support system. Make sure to register your purchase first to access our support services and other resources.', 'ciyashop' ); ?></p>
				<a class="cs-btn" href="https://potezasupport.ticksy.com" rel="noopener" target="_blank">
					<?php esc_html_e( 'Submit a ticket', 'ciyashop' ); ?>
				</a>
			</div>
		</div>

		<div class="col-sm support-column support-column-documentation">
			<div class="support-column-inner">
				<img src="<?php echo esc_url( get_parent_theme_file_uri( '/images/admin/cs-admin/documentation.png' ) ); ?>" />
				<h2><?php esc_html_e( 'Documentation', 'ciyashop' ); ?></h2>
				<p>
					<?php
					printf(
						/* Translators: %1$s: Theme Name. */
						esc_html__( 'Our online documentation is a useful resource for learning every aspect and features of %1$s.', 'ciyashop' ),
						$this->theme_data['Name']
					); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
					?>
				</p>
				<a class="cs-btn" href="http://docs.potenzaglobalsolutions.com/docs/ciyashop-wp/" rel="noopener" target="_blank">
					<?php esc_html_e( 'Learn more', 'ciyashop' ); ?>
				</a>
			</div>
		</div>
		<div class="col-sm support-column support-column-video">
			<div class="support-column-inner">
				<img src="<?php echo esc_url( get_parent_theme_file_uri( '/images/admin/cs-admin/video.png' ) ); ?>" />
				<h2><?php esc_html_e( 'Video Tutorials', 'ciyashop' ); ?></h2>
				<p>
				<?php
					printf(
						/* Translators: %1$s: Theme Name. */
						esc_html__( 'We recommend you to watch video tutorials before you start the theme customization. Our video tutorials can teach you the different aspects of using %s.', 'ciyashop' ),
						$this->theme_data['Name']
					); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
					?>
				</p>
				<a class="cs-btn" href="http://docs.potenzaglobalsolutions.com/docs/ciyashop-wp/#videos" rel="noopener" target="_blank">
					<?php esc_html_e( 'Watch Videos', 'ciyashop' ); ?>
				</a>
			</div>
		</div>
	</div>
</div>
