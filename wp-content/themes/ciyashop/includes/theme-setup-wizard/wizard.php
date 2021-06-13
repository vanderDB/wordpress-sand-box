<?php
/**
 * Theme setup wizard
 *
 * @package CiyaShop
 */

global $ciyashop_globals;

require_once get_parent_theme_file_path( '/includes/theme-setup-wizard/envato_setup/envato_setup_init.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
require_once get_parent_theme_file_path( '/includes/theme-setup-wizard/envato_setup/envato_setup.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

add_filter( 'envato_setup_logo_image', 'ciyashop_set_envato_setup_logo_image' );
/**
 * Set envato setup logo image
 *
 * @param string $image_url .
 */
function ciyashop_set_envato_setup_logo_image( $image_url ) {

	$logo_path = get_parent_theme_file_path( 'images/logo.png' );
	$logo_url  = get_parent_theme_file_uri( 'images/logo.png' );

	if ( file_exists( $logo_path ) ) {
		$image_url = $logo_url;
	}

	return $image_url;
}

add_filter( 'ciyashop_theme_setup_wizard_steps', 'ciyashop_theme_setup_wizard_steps_extend' );
/**
 * Theme setup wizard steps extend
 *
 * @param string $steps .
 */
function ciyashop_theme_setup_wizard_steps_extend( $steps ) {

	if ( isset( $steps['design'] ) ) {
		unset( $steps['design'] );
	}

	$new_steps = array();

	foreach ( $steps as $step_key => $step_data ) {
		$new_steps[ $step_key ] = $step_data;

		if ( 'customize' === $step_key ) {
			$new_steps['default_builder'] = array(
				'name'    => esc_html__( 'Page Builder', 'ciyashop' ),
				'view'    => 'envato_setup_step_default_builder_view',
				'handler' => 'envato_setup_step_default_builder_save',
			);
		}
	}

	return $new_steps;
}

/**
 * Envato setup wizard default builder step view.
 *
 * @param array $wizard  List of steps.
 * @return mixed
 */
function envato_setup_step_default_builder_view( $wizard ) {
	?>
	<h1><?php esc_html_e( 'Default Page Builder', 'ciyashop' ); ?></h1>
	<form method="post">
		<p><?php esc_html_e( 'Please choose a default page builder from the list below.', 'ciyashop' ); ?></p>

		<div class="page-builders-wrapper">
			<ul class="page-builders">
				<?php
				$page_builders = ciyashop_get_page_builders();
				$page_builder  = ciyashop_get_default_page_builder();

				foreach ( $page_builders as $builder_name => $builder_data ) {
					?>
					<li class="page-builder">
						<label for="<?php echo esc_attr( $builder_name ); ?>">
							<input type="radio" id="<?php echo esc_attr( $builder_name ); ?>" name="page_builder" value="<?php echo esc_attr( $builder_name ); ?>" <?php checked( $page_builder, $builder_name ); ?>> <?php echo esc_html( $builder_data['label'] ); ?>
						</label>
					</li>
					<?php
				}
				?>
			</ul>
		</div>

		<div class="license-notice">
			<h3><?php esc_html_e( 'Important note:', 'ciyashop' ); ?></h3>
			<p><?php esc_html_e( 'Please note that the sample data import process, on the Theme Options\'s Sample Data page or the Theme Setup Wizard, will import sample-data only for the page-builder plugin selected above.', 'ciyashop' ); ?></p>
			<p><?php esc_html_e( 'Also, the above two, or other page-builder plugins, work differently to store data/content. And, the imported sample-data for one page-builder plugin will not work for another.', 'ciyashop' ); ?></p>
		</div>

		<p class="envato-setup-actions step">
			<input type="submit" class="button-primary button button-large button-next" value="<?php esc_attr_e( 'Continue', 'ciyashop' ); ?>" name="save_step" />
			<a href="<?php echo esc_url( $wizard->get_next_step_link() ); ?>" class="button button-large button-next"><?php esc_html_e( 'Skip this step', 'ciyashop' ); ?></a>
			<?php wp_nonce_field( 'envato-setup' ); ?>
		</p>
	</form>
	<?php
}

/**
 * Envato setup wizard default builder step handler.
 */
function envato_setup_step_default_builder_save( $wizard ) {
	check_admin_referer( 'envato-setup' );

	$page_builders = ciyashop_get_page_builders();
	$page_builder  = ( isset( $_POST['page_builder'] ) && array_key_exists( sanitize_text_field( wp_unslash( $_POST['page_builder'] ) ), $page_builders ) ) ? sanitize_text_field( wp_unslash( $_POST['page_builder'] ) ) : 'wpbakery';

	if ( $page_builder ) {
		update_option( 'ciyashop_default_page_builder', $page_builder );
	}

	wp_redirect( esc_url_raw( $wizard->get_next_step_link() ) );
	exit;
}

// Please don't forgot to change filters tag.
add_filter( $ciyashop_globals['theme_name'] . '_theme_setup_wizard_username', 'ciyashop_set_theme_setup_wizard_username', 10 );
if ( ! function_exists( 'ciyashop_set_theme_setup_wizard_username' ) ) {
	/**
	 * It must start from your theme's name.
	 *
	 * @param string $username .
	 */
	function ciyashop_set_theme_setup_wizard_username( $username ) {
		return 'potenzaglobalsolutions';
	}
}

add_filter( $ciyashop_globals['theme_name'] . '_theme_setup_wizard_oauth_script', 'ciyashop_set_theme_setup_wizard_oauth_script', 10 );
if ( ! function_exists( 'ciyashop_set_theme_setup_wizard_oauth_script' ) ) {
	/**
	 * Set theme setup wizard oauth script
	 *
	 * @param string $oauth_url .
	 */
	function ciyashop_set_theme_setup_wizard_oauth_script( $oauth_url ) {
		return 'http://themes.potenzaglobalsolutions.com/api/envato/auth.php';
	}
}

add_filter( 'envato_theme_setup_wizard_styles', 'ciyashop_set_theme_setup_wizard_site_styles', 10 );
if ( ! function_exists( 'ciyashop_set_theme_setup_wizard_site_styles' ) ) {
	/**
	 * Set theme setup wizard site styles
	 *
	 * @param string $styles .
	 */
	function ciyashop_set_theme_setup_wizard_site_styles( $styles ) {

		$styles = ciyashop_sample_data_items();

		return $styles;
	}
}

add_filter( $ciyashop_globals['theme_name'] . '_theme_setup_wizard_default_theme_style', 'ciyashop_set_envato_setup_default_theme_style' );
/**
 * Set envato setup default theme style
 *
 * @param string $style .
 */
function ciyashop_set_envato_setup_default_theme_style( $style ) {

	$style = 'default';

	return $style;
}

add_action( 'admin_head', 'ciyashop_theme_setup_wizard_set_assets', 0 );
/**
 * Theme setup wizard set assets
 */
function ciyashop_theme_setup_wizard_set_assets() {
	wp_print_scripts( 'ciyashop-theme-setup' );
}

add_filter( 'envato_setup_wizard_footer_copyright', 'ciyashop_envato_setup_wizard_footer_copyright', 10, 2 );
/**
 * Envato setup wizard footer copyright
 *
 * @param string $copyright .
 * @param string $theme_data .
 */
function ciyashop_envato_setup_wizard_footer_copyright( $copyright, $theme_data ) {

	/* translators: %s: Postenza Global Solutions (Name of Theme Developer) */
	$copyright = sprintf(
		/* translators: $s: Company Name */
		esc_html__( '&copy; Created by %s', 'ciyashop' ),
		sprintf(
			'<a href="%s" target="_blank">%s</a>',
			'http://www.potenzaglobalsolutions.com/',
			esc_html__( 'Potenza Global Solutions', 'ciyashop' )
		)
	);

	return $copyright;
}

add_filter( 'envato_theme_setup_wizard_themeforest_profile_url', 'ciyashop_envato_theme_setup_wizard_themeforest_profile_url' );
/**
 * Envato theme setup wizard themeforest profile url
 *
 * @param string $url .
 */
function ciyashop_envato_theme_setup_wizard_themeforest_profile_url( $url ) {

	$url = '';

	return $url;
}

/**
 * Envato theme setup wizard prevernt WooCommere to redirect after activation
 *
 * @param bool $false .
 */
function ciyashop_prevent_wc_automatic_wizard_redirect( $false ) {
	$current_page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : false; // phpcs:ignore WordPress.Security.NonceVerification
	if ( 'ciyashop-setup' === $current_page ) {
		$false = true;
	}
	return $false;
};
add_filter( 'woocommerce_prevent_automatic_wizard_redirect', 'ciyashop_prevent_wc_automatic_wizard_redirect', 10, 1 );

/**
 * Stop Redirect after elementor active
 */
function ciyashop_elementor_activation_redirect() {
	if ( did_action( 'elementor/loaded' ) && get_option( 'ciyashop_default_page_builder' ) ) {
		delete_transient( 'elementor_activation_redirect' );
	}
}
add_action( 'admin_init', 'ciyashop_elementor_activation_redirect', 1 );
