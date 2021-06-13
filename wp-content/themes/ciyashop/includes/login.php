<?php
/**
 * Add script and style in login screen.
 *
 * @package CiyaShop
 */

add_action( 'login_enqueue_scripts', 'ciyashop_login_enqueue_scripts' );
/**
 * Ciyashop login enqueue scripts
 */
function ciyashop_login_enqueue_scripts() {
	global $ciyashop_options;

	$login_logo    = '';
	$primary_color = isset( $ciyashop_options['primary_color'] ) ? $ciyashop_options['primary_color'] : '#00de8c';

	wp_enqueue_style( 'ciyashop-login-css', get_template_directory_uri( '/css/login_style.css' ), array(), THEME_VERSION );
	if ( isset( $ciyashop_options['site-logo'] ) && ! empty( $ciyashop_options['site-logo'] ) && isset( $ciyashop_options['site-logo']['url'] ) && ! empty( $ciyashop_options['site-logo']['url'] ) ) {
		$login_logo_option = $ciyashop_options['site-logo'];
		if ( is_array( $login_logo_option ) && isset( $login_logo_option['url'] ) ) {
			$login_logo = $login_logo_option['url'];
		}
	}
	if ( empty( $login_logo ) ) {
		$login_logo = get_template_directory_uri() . '/images/logo.png';
	}
		$login_logo                = esc_url( $login_logo );
		$ciyashop_login_custom_css = "
			body.login{
				background-image:url('" . get_template_directory_uri() . ( '/images/login-body-bg.png' ) . "');
				display: flex;
			}
			#login{
				background-color:#ffffff;
				border: 0px solid #f9f9f9;
				-webkit-box-shadow: 0 3px 23px rgba(0,0,0,0.1); 
				-ms-box-shadow: 0 3px 23px rgba(0,0,0,0.1); 
				box-shadow: 0 3px 23px rgba(0,0,0,0.1);
				padding:0;
				width: 480px;
				position: relative;
				justify-content: center;
			}

			#login #login_error, 
			#login .message {
				padding: 10px; 
				background: $primary_color; 
				color: #ffffff; 
				border-left: 0px; 
				text-align: center;
				margin-bottom: 0;
			}

			#login #login_error{
				background: #dc3232;
			}

			#login #login_error a{
				color: #ffffff;
			}

			.login form{
				box-shadow:none;
			}

			.login label {
				color:#969696;
			}

			body.login form{
				margin-top: 0px;
			}

			.login h1{
				background-color:#f9f9f9;
				padding: 30px 0;
			}

			#login h1 a, .login h1 a {
				background-image: url({$login_logo}); 
				background-position: center center; 
				background-size: contain; 
				width: 200px; 
				height: 46px;
				margin-bottom:0;
			} 

			#loginform,
			#registerform{
				background-color: #ffffff;
				padding: 45px 40px 40px;
				border-bottom: 1px solid #f1f1f1;
			}

			#loginform label .input{
				font-size:18px; 
				padding:10px 15px; 
				margin-top:8px; 
				box-shadow: none; 
				border: 1px solid #e5e5e5; 
				background: #fff;
			}
			
			#loginform .forgetmenot{
			display: block; 
			float: right;
			margin: 14px 0px;
			}

			#login form p.submit{
				float: left;
				margin-top: 10px;
			}

			#login form p.submit #wp-submit{
				height: 40px; 
				line-height: 40px; 
				padding: 0 35px 10px; 
				text-transform: uppercase; 
				font-size: 14px;
				background: $primary_color;
			border-color: $primary_color;
			box-shadow: none; 
			text-shadow: none;
			transition: 0.3s;
			}

			#login form p.submit #wp-submit:hover {
				background: #323232; 
				border-color: #323232;
		}

		#login #nav{ 
			padding: 20px 0px 20px 40px; 
			margin-top: 0px; 
			display: inline-block; 
		}
		#login #backtoblog{
			float: right; 
			padding: 20px 40px 20px 0px; 
			margin: 0px; 
		}

			.login #backtoblog a, 
			.login #nav a { 
				color: #969696; 
				transition: 0.3s;
			}

			.login #backtoblog a:hover, 
			.login #nav a:hover, 
			.login h1 a:hover {
				color: $primary_color;
			}

			
			#login input:-webkit-autofill {
				-webkit-box-shadow: inset 0 0 0px 9999px white;
				-moz-box-shadow: inset 0 0 0px 9999px white;
				box-shadow: inset 0 0 0px 9999px white;
			}

			@media (max-width: 500px){
				#login{
					width: 320px;
				}
				#loginform{
					padding: 20px 20px 25px;
				}

			}

			@media only screen and (max-width: 479px) {
				body {
					padding: 0 15px;
				}
			
				.pgssl-login-inner {
					width: 100%;
				}
			
				#login #nav {
					padding: 20px 0px 5px 20px;
				}
			
				#login #backtoblog {
					float: none;
					padding: 5px 0px 20px 20px;
				}
			
				.login .privacy-policy-page-link {
					text-align: left;
					width: auto;
					margin: 0em 20px 20px;
				}
			
			}

			";
		wp_add_inline_style( 'ciyashop-login-css', $ciyashop_login_custom_css );
}

/**
 * Change logo link from wordpress.org to your site
 *
 * @since Car Dealer 1.0
 */
function ciyashop_login_url() {
	return esc_url( home_url( '/' ) );
}
add_filter( 'login_headerurl', 'ciyashop_login_url' );

/**
 * Change alt text on logo to show your site name
 *
 * @since Car Dealer 1.0
 */
function ciyashop_login_title() {
	return get_option( 'blogname' );
}
add_filter( 'login_headertext', 'ciyashop_login_title' );
