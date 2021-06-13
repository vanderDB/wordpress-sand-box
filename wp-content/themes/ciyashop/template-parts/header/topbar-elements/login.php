<?php
/**
 * Login.
 *
 * @package CiyaShop
 */

global $ciyashop_options;

$topbar_login_url = wp_login_url( add_query_arg( array(), remove_query_arg( array() ) ) );

if ( isset( $ciyashop_options['topbar_custom_login_url'] ) && ! empty( $ciyashop_options['topbar_custom_login_url'] ) ) {
	$topbar_login_url = $ciyashop_options['topbar_custom_login_url'];
}

if ( $topbar_login_url ) {
	?>
	<a class="login" href="<?php echo esc_url( $topbar_login_url ); ?>">
		<i class="fa fa-lock text-blue"></i> <?php echo esc_html__( 'Login', 'ciyashop' ); ?>
	</a>
	<?php
}
