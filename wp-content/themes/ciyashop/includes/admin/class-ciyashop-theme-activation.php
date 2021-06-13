<?php
/**
 * Theme activation
 *
 * @package CiyaShop
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'ciyashop_activate_theme' );
/**
 * Theme activation function
 *
 * @return void
 */
function ciyashop_activate_theme() {

	if ( isset( $_POST['ciyashop_purchase_code'] ) && isset( $_POST['purchase_code_nonce'] ) ) {
		$notices = array();

		// verify nonce.
		if ( wp_verify_nonce( wp_unslash( $_POST['purchase_code_nonce'] ), 'purchase_code_activation' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$ciyashop_purchase_code = sanitize_text_field( wp_unslash( $_POST['ciyashop_purchase_code'] ) ); // Sanitize purchase code.

			// Compare old stored purchase code with newly submitted.
			$ciyashop_purchase_code_old     = get_option( 'ciyashop_theme_purchase_key' );
			$ciyashop_site_url_key_old      = get_option( 'ciyashop_site_url_key' );
			$ciyashop_purchase_token_old    = get_option( 'ciyashop_pgs_token' );
			$ciyashop_purchase_code_notices = get_option( 'ciyashop_purchase_code_notices' );

			delete_option( 'ciyashop_purchase_code_notices' );

			$ciyashop_site_url     = get_site_url();
			$ciyashop_site_url_key = md5( $ciyashop_site_url );

			// If empty purchase code provided.
			if ( empty( $ciyashop_purchase_code ) ) {
				delete_option( 'ciyashop_theme_purchase_key' );
				delete_option( 'ciyashop_pgs_token' );
				if ( $ciyashop_purchase_code_old && ! empty( $ciyashop_purchase_code_old ) ) {
					$notices = array(
						'notice_type' => 'warning',
						'notice'      => esc_html__( 'Purchase code removed.', 'ciyashop' ),
					);
				} else {
					$notices = array(
						'notice_type' => 'warning',
						'notice'      => esc_html__( 'Please enter purchase code.', 'ciyashop' ),
					);
				}
			} else {

				// Prevalidate purchase code structure.
				if ( true !== ciyashop_prevalidate_purchase_code_cppc( $ciyashop_purchase_code ) ) {
					delete_option( 'ciyashop_theme_purchase_key' );
					delete_option( 'ciyashop_pgs_token' );
					$notices = array(
						'notice_type' => 'warning',
						'notice'      => esc_html__( 'Please enter a valid purchase code.', 'ciyashop' ),
					);
				} else {
					// Check if same key is submitted from same site, and purchase token is already set.
					if ( ( $ciyashop_purchase_code_old === (string) $ciyashop_purchase_code ) // check old code and new code are same.
						&& ( $ciyashop_site_url_key_old === (string) $ciyashop_site_url_key ) // check if old url is same as new one.
						&& false !== $ciyashop_purchase_token_old
					) {
						$notices = array(
							'notice_type' => 'warning',
							'notice'      => esc_html__( 'Purchase code already activated on this website.', 'ciyashop' ),
						);
					} else {
						delete_option( 'ciyashop_theme_purchase_key' );
						delete_option( 'ciyashop_pgs_token' );

						$template   = get_template();
						$theme_info = wp_get_theme( $template );
						$theme_name = $theme_info['Name'];

						$args = array(
							'product_key'  => PGS_PRODUCT_KEY,
							'purchase_key' => $ciyashop_purchase_code,
							'site_url'     => get_site_url(),
							'action'       => 'register',
						);

						$url      = add_query_arg( $args, trailingslashit( PGS_ENVATO_API ) . 'verifyproduct' );
						$response = wp_remote_get( $url, array( 'timeout' => 2000 ) );

						if ( is_wp_error( $response ) ) {
							$error_string = $response->get_error_message();
							$notices      = array(
								'notice_type' => 'error',
								'notice'      => sprintf(
									/* translators: %s: parameter */
									esc_html__( 'There was an error processing your request; please try again later. Error: %s', 'ciyashop' ),
									esc_html( $error_string )
								),
							);
						} else {
							$response_code = wp_remote_retrieve_response_code( $response );
							$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

							if ( 200 === (int) $response_code ) {
								if ( 1 === (int) $response_body['status'] ) {
									update_option( 'ciyashop_theme_purchase_key', $ciyashop_purchase_code );
									update_option( 'ciyashop_pgs_token', $response_body['pgs_token'] );
									update_option( 'ciyashop_site_url_key', $ciyashop_site_url_key );
									$notices = array(
										'notice_type' => 'success',
										'notice'      => $response_body['message'],
									);
								} else {
									$notices = array(
										'notice_type' => 'error',
										'notice'      => $response_body['message'],
									);
								}
							} else {
								delete_option( 'ciyashop_pgs_token' ); // update pgs_token.
								$notices = array(
									'notice_type' => 'warning',
									'notice'      => sprintf(
										/* translators: %d: The HTTP response code returned. */
										esc_html__( 'There was an error processing your request with http status code %d; please try again later.', 'ciyashop' ),
										$response_code
									),
								);
							}
						}
					}
				}
			}
		} else {
			$notices = array(
				'notice_type' => 'error',
				'notice'      => esc_html__( 'Unable to verify security check. Please try to reload the page.', 'ciyashop' ),
			);
		}

		update_option( 'ciyashop_purchase_code_notices', $notices );
	}
}

/**
 * Purchase code validation
 *
 * @param string $purchase_code purchase code.
 */
function ciyashop_prevalidate_purchase_code_cppc( $purchase_code ) {
	$purchase_code = preg_replace( '#([a-z0-9]{8})-?([a-z0-9]{4})-?([a-z0-9]{4})-?([a-z0-9]{4})-?([a-z0-9]{12})#', '$1-$2-$3-$4-$5', strtolower( $purchase_code ) );
	if ( 36 === (int) strlen( $purchase_code ) ) {
		return true;
	}
	return false;
}
