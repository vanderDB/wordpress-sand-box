<?php
/**
 * Register.
 *
 * @package CiyaShop
 */

global $ciyashop_options;

if ( get_option( 'users_can_register' ) ) {
	$topbar_register_url = ( isset( $ciyashop_options['topbar_custom_register_url'] ) && ! empty( $ciyashop_options['topbar_custom_register_url'] ) ) ? $ciyashop_options['topbar_custom_register_url'] : wp_registration_url();
	?>
	<a class="register" href="<?php echo esc_url( $topbar_register_url ); ?>">
		<i class="fa fa-user text-blue"></i> <?php echo esc_html__( 'Register', 'ciyashop' ); ?>
	</a>
	<?php
} else {
	return;
}
