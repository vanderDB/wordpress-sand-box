<?php
/**
 * Admin license
 *
 * @package CiyaShop
 */

$purchase_token = ciyashop_is_activated();
$purchase_code  = get_option( 'ciyashop_theme_purchase_key' );
$notices        = get_option( 'ciyashop_purchase_code_notices' );
delete_option( 'ciyashop_purchase_code_notices' );

$icon               = 'dashicons dashicons-admin-network';
$token_status_class = 'btn btn-secondary';

if ( ! empty( $purchase_code ) ) {
	$token_status_class = 'btn btn-success';
	$icon               = 'dashicons dashicons-yes';
}

if ( ! empty( $notices ) ) {

	$notice      = $notices['notice'];
	$notice_type = $notices['notice_type'];

	$alert_class = 'p-3 mb-2 bg-light text-dark';

	if ( 'warning' === (string) $notice_type ) {
		$alert_class = 'p-3 mb-2 bg-warning text-dark';
	} elseif ( 'error' === (string) $notice_type ) {
		$alert_class = 'p-3 mb-2 bg-danger text-white';
	} elseif ( 'success' === (string) $notice_type ) {
		$alert_class = 'p-3 mb-2 bg-success text-white';
	}
	?>
	<div class="<?php echo esc_attr( $alert_class ); ?>">
		<h6 class="mb-0"><?php echo esc_html( $notice ); ?></h6>
	</div>
	<?php
}
?>
<div class="ciyashop-theme-activation-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm">
				<div class="ciyashop-theme-activation-main">
					<?php
					if ( ! empty( $themes ) && ! empty( $auth_token ) ) {
						?>
						<div class="card mb-4 shadow-sm">
							<?php envato_market_themes_column( 'active' ); ?>
						</div>
						<?php
					}
					?>
					<form id="ciyashop_activate_theme" method="post" action="">
						<!-- validate_purchase_code -->
						<?php settings_fields( 'ciyashop_validate_purchase_code' ); ?>

						<!-- purchase_code_nonce -->
						<?php wp_nonce_field( 'purchase_code_activation', 'purchase_code_nonce' ); ?>
						<div class="mb-3">
							<h6><?php esc_html_e( 'Purchase Code', 'ciyashop' ); ?></h6>
							<div class="input-group mb-2 mr-sm-2 is-invalid">
								<?php
								$purchase_code_input_val  = ( ! empty( $purchase_code ) ) ? $purchase_code : '';
								$purchase_code_input_type = ( ! empty( $purchase_code ) ) ? 'password' : 'text';
								?>
								<input class="form-control" id="ciyashop_purchase_code" type="<?php echo esc_attr( $purchase_code_input_type ); ?>" name="ciyashop_purchase_code" value="<?php echo esc_attr( $purchase_code_input_val ); ?>" placeholder="<?php esc_attr_e( 'Purchase code ( e.g. 9g2b13fa-10aa-2267-883a-9201a94cf9b5 )', 'ciyashop' ); ?>">
								<div class="input-group-prepend">
									<div class="<?php echo esc_attr( $token_status_class ); ?>"><span class="<?php echo esc_attr( $icon ); ?>"></span></div>
								</div>
							</div>
						</div>
						<?php submit_button( esc_attr__( 'Check', 'ciyashop' ), array( 'primary', 'large' ) ); ?>
					</form>
				</div>
			</div>
			<div class="col-sm">
				<div class="ciyashop-theme-activation-info">
					<h6><?php esc_html_e( 'Instructions to find the Purchase Code', 'ciyashop' ); ?></h6>
					<ol>
						<li><?php esc_html_e( 'Log into your Envato Market account.', 'ciyashop' ); ?></li>
						<li><?php esc_html_e( 'Hover the mouse over your username at the top of the screen.', 'ciyashop' ); ?></li>
						<li><?php esc_html_e( 'Click \'Downloads\' from the drop-down menu.', 'ciyashop' ); ?></li>
						<li>
						<?php
						printf(
							wp_kses(
								/* translators: $s: artical link */
								__( 'Click \'License certificate & purchase code\' (available as PDF or text file). For more information <a href="%1$s" target="_blank">click here</a>.', 'ciyashop' ),
								array(
									'br'     => array(),
									'strong' => array(),
									'a'      => array(
										'href'   => array(),
										'target' => array(),
									),
								)
							),
							'https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code',
							esc_html__( 'CiyaShop', 'ciyashop' )
						);
						?>
						</li>
					</ol>	
				</div>
			</div>

			<div class="col-sm-12 license-notice">
				<?php echo '<b>' . esc_html__( 'Note:', 'ciyashop' ) . '</b> ' . esc_html__( 'you are allowed to use our theme only on one domain if you have purchased a regular license. ', 'ciyashop' ) . '<br/>' . esc_html__( 'But we give you an ability to activate our theme to turn on auto updates on two domains: for the development website and for your production (live) website.', 'ciyashop' ) . ''; ?>
			</div>
		</div>
	</div>
</div>
