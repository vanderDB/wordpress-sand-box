<?php
/**
 * The template for displaying the newsletter
 *
 * @package Ciyashop
 */

global $ciyashop_options;

if ( ( isset( $ciyashop_options['comming_soon_newsletter'] ) && 1 === (int) $ciyashop_options['comming_soon_newsletter'] ) && ( ciyashop_check_plugin_active( 'mailchimp-for-wp/mailchimp-for-wp.php' ) ) && ( isset( $ciyashop_options['comming_page_newsletter_shortcode'] ) && ! empty( $ciyashop_options['comming_page_newsletter_shortcode'] ) ) ) {
	$mailchimp_id = $ciyashop_options['comming_page_newsletter_shortcode'];
	?>
	<div class="action-box maintenance-newsletter">
		<div class="container">
			<div class="row justify-content-center text-center">
				<div class="col-sm-8"> 
					<h5><?php esc_html_e( 'We will notify you when site is ready :', 'ciyashop' ); ?></h5>
					<h3><?php esc_html_e( 'Provide your email address!', 'ciyashop' ); ?></h3>
					<form id="mc4wp-form-1" class="notify-form mc4wp-form mc4wp-form-<?php echo esc_attr( $mailchimp_id ); ?>" method="post" data-id="<?php echo esc_attr( $mailchimp_id ); ?>" data-name="NewsLetter">
						<div class="mc4wp-form-fields">
							<input class="newsletter_email" name="EMAIL" placeholder="<?php esc_attr_e( 'Your email address', 'ciyashop' ); ?>" required="" type="email">
							<input class="newsletter_submit" value="<?php esc_attr_e( 'Notify Me', 'ciyashop' ); ?>" type="submit">
							<div class="ciyashop-hidden">
								<input name="_mc4wp_honeypot" value="" tabindex="-1" autocomplete="off" type="text">
							</div>
							<input name="_mc4wp_timestamp" value="<?php echo esc_attr( time() ); ?>" type="hidden">
							<input name="_mc4wp_form_id" value="<?php echo esc_attr( $mailchimp_id ); ?>" type="hidden">
							<input name="_mc4wp_form_element_id" value="mc4wp-form-1" type="hidden">
						</div>
						<div class="mc4wp-response"><?php echo mc4wp_form_get_response_html( $mailchimp_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					</form>
				</div>			
			</div>
		</div>
	</div>
	<?php
}
