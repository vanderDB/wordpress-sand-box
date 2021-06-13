<?php
/**
 * Email.
 *
 * @package CiyaShop
 */

global $ciyashop_options;

$site_email = ( isset( $ciyashop_options['site_email'] ) && ! empty( $ciyashop_options['site_email'] ) ) ? sanitize_email( $ciyashop_options['site_email'] ) : false;

if ( $site_email ) {
	?>
	<a href="<?php echo esc_url( 'mailto:' . sanitize_email( $site_email ) ); ?>"><i class="fa fa-envelope-o">&nbsp;</i><?php echo esc_html( sanitize_email( $site_email ) ); ?></a>
	<?php
}
