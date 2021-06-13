<?php
/**
 * Login Form
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $woocommerce; ?>
<!-- Modal -->
<div class="modal fade" id="pgs_login_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel"><?php esc_html_e( 'Sign in Or Register', 'ciyashop' ); ?></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<div class="box-authentication">                    
					<div class="row">
						<div class="col-sm-6">
							<?php do_action( 'woocommerce_before_customer_login_form' ); ?>
							<form action="<?php echo esc_url( get_permalink( ( function_exists( 'wc_get_page_id' ) ) ? wc_get_page_id( 'myaccount' ) : '' ) ); ?>" method="post" class="login">
								<div class="form-group">
									<label for="username"><?php esc_html_e( 'Username', 'ciyashop' ); ?></label>
									<input type="text" class="form-control" name="username" id="username" placeholder="<?php esc_attr_e( 'Username', 'ciyashop' ); ?>" />                            
									<input name="form_key" type="hidden" value="lDLFLGU1hYlZ9gVL">
								</div>
								<div class="form-group">
									<label for="password"><?php esc_html_e( 'Password', 'ciyashop' ); ?></label>
									<input class="form-control" type="password" placeholder="<?php esc_attr_e( 'Password', 'ciyashop' ); ?>" name="password" id="password" />
								</div>
								<div class="form-group">
									<a href="<?php echo esc_url( ( function_exists( 'wc_lostpassword_url' ) ) ? wc_lostpassword_url() : '' ); ?>" title="<?php esc_attr_e( 'Forgot your password?', 'ciyashop' ); ?>"><?php esc_html_e( 'Forgot your password?', 'ciyashop' ); ?></a>
								</div>
								<div class="form-group">
									<?php wp_nonce_field( 'woocommerce-login' ); ?>
									<input type="submit" class="button btn-primary submit-login" name="login" value="<?php esc_attr_e( 'Login', 'ciyashop' ); ?>" />                            
								</div>
							</form>
						</div>                    
						<div class="col-sm-6">
							<h2><?php esc_html_e( 'NEW HERE?', 'ciyashop' ); ?></h2>
							<p class="note-reg"><?php esc_html_e( 'Registration is free and easy!', 'ciyashop' ); ?></p>
							<ul class="list-log">
								<li><?php esc_html_e( 'Faster checkout', 'ciyashop' ); ?></li>
								<li><?php esc_html_e( 'Save multiple shipping addresses', 'ciyashop' ); ?></li>
								<li><?php esc_html_e( 'View and track orders and more', 'ciyashop' ); ?></li>
							</ul>
							<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Create an account', 'ciyashop' ); ?></a>
						</div>
					</div>
					<div class="clearfix"></div>
					<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
					<?php do_action( 'pgs_social_login' ); ?>
				</div>
			</div>            
		</div>
	</div>
</div>
