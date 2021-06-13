<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

/**
 * Add require files
 */
require_once get_parent_theme_file_path( '/includes/tgm-plugin-activation/core/class-tgm-plugin-activation.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

add_action( 'tgmpa_register', 'ciyashop_register_required_plugins' );

/**
 * Array of plugin arrays. Required keys are name and slug.
 * If the source is NOT from the .org repo, then source is also required.
 */
function ciyashop_tgmpa_plugin_list() {

	$plugin_list = array(
		array(
			'name'               => esc_html__( 'PGS Core', 'ciyashop' ),
			'slug'               => 'pgs-core',
			'source'             => ciyashop_tgmpa_plugin_path( 'pgs-core-5.0.3.zip' ),
			'required'           => true,
			'force_activation'   => false,
			'force_deactivation' => false,
			'version'            => '5.0.3',
			'details_url'        => '',
			'checked_in_wizard'  => true,
		),
		array(
			'name'               => esc_html__( 'PGS QRCode', 'ciyashop' ),
			'slug'               => 'pgs-qrcode',
			'source'             => ciyashop_tgmpa_plugin_path( 'pgs-qrcode-2.0.0.zip' ),
			'required'           => false,
			'force_activation'   => false,
			'force_deactivation' => false,
			'version'            => '2.0.0',
			'details_url'        => '',
			'checked_in_wizard'  => false,
		),
		array(
			'name'               => esc_html__( 'Slider Revolution', 'ciyashop' ),
			'slug'               => 'revslider',
			'source'             => ciyashop_tgmpa_plugin_path( 'revslider-6.4.8.zip' ),
			'required'           => true,
			'force_activation'   => false,
			'force_deactivation' => false,
			'version'            => '6.4.8',
			'details_url'        => 'https://codecanyon.net/item/slider-revolution-responsive-wordpress-plugin/2751380',
			'checked_in_wizard'  => true,
		),
		array(
			'name'              => esc_html__( 'Redux Framework', 'ciyashop' ),
			'slug'              => 'redux-framework',
			'required'          => true,
			'details_url'       => 'https://wordpress.org/plugins/redux-framework/',
			'checked_in_wizard' => true,
		),
		array(
			'name'              => esc_html__( 'Contact Form 7', 'ciyashop' ),
			'slug'              => 'contact-form-7',
			'required'          => true,
			'details_url'       => 'https://wordpress.org/plugins/contact-form-7/',
			'checked_in_wizard' => true,
		),
		array(
			'name'              => esc_html__( 'MailChimp for WordPress', 'ciyashop' ),
			'slug'              => 'mailchimp-for-wp',
			'required'          => false,
			'details_url'       => 'https://wordpress.org/plugins/mailchimp-for-wp/',
			'checked_in_wizard' => false,
		),
		array(
			'name'              => esc_html__( 'WooCommerce', 'ciyashop' ),
			'slug'              => 'woocommerce',
			'required'          => true,
			'details_url'       => 'https://wordpress.org/plugins/woocommerce/',
			'checked_in_wizard' => true,
		),
		array(
			'name'              => esc_html__( 'WooCommerce Currency Switcher', 'ciyashop' ),
			'slug'              => 'woocommerce-currency-switcher',
			'required'          => false,
			'details_url'       => 'https://wordpress.org/plugins/woocommerce-currency-switcher/',
			'checked_in_wizard' => false,
		),
		array(
			'name'              => esc_html__( 'YITH WooCommerce Brands Add-on', 'ciyashop' ),
			'slug'              => 'yith-woocommerce-brands-add-on',
			'required'          => false,
			'details_url'       => 'https://wordpress.org/plugins/yith-woocommerce-brands-add-on/',
			'checked_in_wizard' => false,
		),
		array(
			'name'              => esc_html__( 'Store Locator WordPress', 'ciyashop' ),
			'slug'              => 'agile-store-locator',
			'required'          => false,
			'details_url'       => 'https://wordpress.org/plugins/agile-store-locator/',
			'checked_in_wizard' => false,
		),
		array(
			'name'              => esc_html__( 'Envato Market', 'ciyashop' ),
			'slug'              => 'envato-market',
			'source'            => ciyashop_tgmpa_plugin_path( 'envato-market-2.0.6.zip' ),
			'required'          => false,
			'version'           => '2.0.6',
			'details_url'       => 'https://envato.com/market-plugin/',
			'checked_in_wizard' => false,
		),
	);

	$page_builders   = ciyashop_get_page_builders();
	$page_builder    = ciyashop_get_default_page_builder();
	$is_setup_wizard = ( isset( $_GET['page'] ) && 'ciyashop-setup' === $_GET['page'] );

	if ( ! $is_setup_wizard || ( $is_setup_wizard && array_key_exists( $page_builder, $page_builders ) && 'wpbakery' === $page_builder ) ) {
		$plugin_list[] = array(
			'name'               => esc_html__( 'WPBakery Page Builder', 'ciyashop' ),
			'slug'               => 'js_composer',
			'source'             => ciyashop_tgmpa_plugin_path( 'js_composer-6.6.0.zip' ),
			'required'           => ( 'wpbakery' === $page_builder ),
			'force_activation'   => false,
			'force_deactivation' => false,
			'version'            => '6.6.0',
			'details_url'        => 'https://codecanyon.net/item/visual-composer-page-builder-for-wordpress/242431',
			'checked_in_wizard'  => true,
		);
	}

	if ( ! $is_setup_wizard || ( $is_setup_wizard && array_key_exists( $page_builder, $page_builders ) && 'elementor' === $page_builder ) ) {
		$plugin_list[] = array(
			'name'              => esc_html__( 'Elementor Website Builder', 'ciyashop' ),
			'slug'              => 'elementor',
			'required'          => ( 'elementor' === $page_builder ),
			'optional'          => true,
			'details_url'       => 'https://wordpress.org/plugins/elementor/',
			'checked_in_wizard' => true,
		);
	}

	return apply_filters( 'tgmpa_plugin_list', $plugin_list );
}

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variables passed to the `tgmpa()` function should be:
 * - an array of plugin arrays;
 * - optionally a configuration array.
 * If you are not changing anything in the configuration array, you can remove the array and remove the
 * variable from the function call: `tgmpa( $plugins );`.
 * In that case, the TGMPA default settings will be used.
 *
 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
 */
function ciyashop_register_required_plugins() {
	if ( ! ciyashop_is_activated() ) {
		return;
	}
	$plugins = ciyashop_tgmpa_plugin_list();

	$tgmpa_id = 'ciyashop_recommended_plugins';

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => $tgmpa_id,           // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                  // Default absolute path to bundled plugins.
		'menu'         => 'theme-plugins',     // Menu slug.
		'parent_slug'  => 'themes.php',        // Parent menu slug.
		'capability'   => 'edit_theme_options', // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => false,                // Show admin notices or not.
		'dismissable'  => true,                // If false, a user cannot dismiss the nag message.
		'is_automatic' => false,               // Automatically activate plugins after installation or not.
	);

	tgmpa( $plugins, $config );
}

/**
 * Returns plugin activation status
 * ciyashop_tgmpa_stat()
 */
function ciyashop_tgmpa_setup_status() {

	$pluginy = ciyashop_tgmpa_plugins_data();

	$ciyashop_tgmpa_plugins_data_all = $pluginy['all'];
	foreach ( $ciyashop_tgmpa_plugins_data_all as $ciyashop_tgmpa_plugins_data_k => $ciyashop_tgmpa_plugins_data_v ) {
		if ( ! $ciyashop_tgmpa_plugins_data_v['required'] ) {
			unset( $ciyashop_tgmpa_plugins_data_all[ $ciyashop_tgmpa_plugins_data_k ] );
		}
	}

	if ( count( $ciyashop_tgmpa_plugins_data_all ) > 0 ) {
		return false;
	} else {
		return true;
	}
}

/**
 * Returns plugin activation list
 * ciyashop_tgmpa_plugins_data()
 */
function ciyashop_tgmpa_plugins_data() {
	$plugins = ciyashop_tgmpa_plugin_list();

	$pluginy = array(
		'all'      => array(), // Meaning: all plugins which still have open actions.
		'install'  => array(),
		'update'   => array(),
		'activate' => array(),
	);

	if ( isset( $GLOBALS['tgmpa'] ) ) {
		$tgmpax = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
		foreach ( $plugins as $plugin ) {
			call_user_func( array( $tgmpax, 'register' ), $plugin );
		}
		$pluginx = $tgmpax->plugins;

		foreach ( $tgmpax->plugins as $slug => $plugin ) {
			if ( $tgmpax->is_plugin_active( $slug ) && false === $tgmpax->does_plugin_have_update( $slug ) ) {
				// No need to display plugins if they are installed, up-to-date and active.
				continue;
			} else {
				$pluginy['all'][ $slug ] = $plugin;

				// Filter required and recommended plugins.
				$plugin_required = isset( $plugin['required'] ) && (bool) $plugin['required'] ? true : false;

				if ( $plugin_required ) {
					$pluginy['required'][ $slug ] = $plugin;
				} else {
					$pluginy['recommended'][ $slug ] = $plugin;
				}

				if ( ! $tgmpax->is_plugin_installed( $slug ) ) {
					$pluginy['install'][ $slug ] = $plugin;
				} else {
					if ( false !== $tgmpax->does_plugin_have_update( $slug ) ) {
						$pluginy['update'][ $slug ] = $plugin;
					}

					if ( $tgmpax->can_plugin_activate( $slug ) ) {
						$pluginy['activate'][ $slug ] = $plugin;
					}
				}
			}
		}
	}

	return $pluginy;
}
/**
 * Ciyashop tgmpa plugin path
 *
 * @param string $plugin_name .
 */
function ciyashop_tgmpa_plugin_path( $plugin_name = '' ) {
	return get_template_directory() . '/plugins/' . $plugin_name;

	$purchase_token = ciyashop_is_activated();

	// bail early if no plugin name provided.
	if ( empty( $plugin_name ) ) {
		return '';
	}

	return add_query_arg(
		array(
			'plugin_name' => $plugin_name,
			'token'       => $purchase_token,
			'site_url'    => get_site_url(),
			'product_key' => PGS_PRODUCT_KEY,
		),
		trailingslashit( PGS_ENVATO_API ) . 'install-plugin'
	);
}
