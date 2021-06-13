<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Nav Menu.
 *
 * @package CiyaShop
 */

/**
 * The Nav Menu Images Plugin
 *
 * Display image as a menu item content.
 *
 * @package Nav Menu Images
 * @subpackage Main
 */

/**
 * Plugin Name: Nav Menu Images
 * Plugin URI:  http://blog.milandinic.com/wordpress/plugins/nav-menu-images/
 * Description: Display image as a menu content.
 * Author:      Milan Dinić
 * Author URI:  http://blog.milandinic.com/
 * Version:     3.4
 * Text Domain: nav-menu-images
 * Domain Path: /languages/
 * License:     GPL
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Display image as a menu content.
 *
 * @since 1.0
 */
class Nav_Menu_Images {
	/**
	 * Name of a plugin's file.
	 *
	 * @var $plugin_basename
	 * @since 1.0
	 * @access protected
	 */
	protected $plugin_basename;

	/**
	 * Is last menu item of current page.
	 *
	 * @var $is_current_item
	 * @since 3.0
	 * @access public
	 */
	public $is_current_item;

	/**
	 * Sets class properties.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @uses add_action() To hook function.
	 * @uses plugin_basename() To get plugin's file name.
	 */
	public function __construct() {
		// Register init.
		add_action( 'init', array( $this, 'init' ) );

		// Get a basename.
		$this->plugin_basename = plugin_basename( __FILE__ );
	}

	/**
	 * Register actions & filters on init.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @uses add_post_type_support() To enable thumbs for nav menu.
	 * @uses is_admin() To see if it's admin area.
	 * @uses Nav_Menu_Images_Admin() To call admin functions.
	 * @uses add_action() To hook function.
	 * @uses apply_filters() Calls 'nmi_filter_menu_item_content' to
	 *                        overwrite menu item filter.
	 * @uses add_filter() To hook filters.
	 * @uses do_action() Calls 'nmi_init'.
	 */
	public function init() {
		// Add thumbnail support to menus.
		add_post_type_support( 'nav_menu_item', 'thumbnail' );

		// Include the required menu fields files.
		require_once dirname( __FILE__ ) . '/inc/fields-helper.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once dirname( __FILE__ ) . '/inc/menu-fields.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

		// Load admin file.
		if ( is_admin() ) {
			require_once dirname( __FILE__ ) . '/inc/admin.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			new Nav_Menu_Images_Admin();
		}

		// Register AJAX handler.
		add_action( 'wp_ajax_nmi_added_thumbnail', array( $this, 'ajax_added_thumbnail' ) );

		// Register plugins action links filter.
		add_filter( 'plugin_action_links', array( $this, 'action_links' ), 10, 2 );
		add_filter( 'network_admin_plugin_action_links', array( $this, 'action_links' ), 10, 2 );

		do_action( 'nmi_init' );
	}

	/**
	 * Load textdomain for internationalization.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @uses is_textdomain_loaded() To check if translation is loaded.
	 * @uses load_plugin_textdomain() To load translation file.
	 */
	public function load_textdomain() {
		/* If translation isn't loaded, load it */
	}

	/**
	 * Return thumbnail's HTML after addition.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @uses absint() To get positive integer.
	 * @uses has_post_thumbnail() To check if item has thumb.
	 * @uses admin_url() To get URL of uploader.
	 * @uses esc_url() To escape URL.
	 * @uses add_query_arg() To append variables to URL.
	 * @uses get_the_post_thumbnail() To get item's thumb.
	 */
	public function ajax_added_thumbnail() {
		// Get submitted values.
		$post_id      = isset( $_POST['post_id'] ) ? absint( sanitize_text_field( wp_unslash( $_POST['post_id'] ) ) ) : 0;
		$thumbnail_id = isset( $_POST['thumbnail_id'] ) ? absint( sanitize_text_field( wp_unslash( $_POST['thumbnail_id'] ) ) ) : 0;

		// If there aren't values, exit.
		if ( 0 === (int) $post_id || 0 === (int) $thumbnail_id ) {
			wp_die( '0' );
		}

		// If there isn't featured image, exit.
		if ( ! has_post_thumbnail( $post_id ) ) {
			wp_die( '1' );
		}

		// Form upload link.
		$upload_url = admin_url( 'media-upload.php' );
		$query_args = array(
			'post_id'   => $post_id,
			'tab'       => 'gallery',
			'TB_iframe' => '1',
			'width'     => '640',
			'height'    => '425',
		);
		$upload_url = esc_url( add_query_arg( $query_args, $upload_url ) );

		// Item's featured image.
		$post_thumbnail = get_the_post_thumbnail( $post_id, 'thumb' );

		// Full HTML.
		$return_html = '<a href="' . $upload_url . '" data-id="' . $post_id . '" class="thickbox add_media">' . $post_thumbnail . '</a>';

		wp_die( $return_html ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}

	/**
	 * Display an image as menu item content.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @uses has_post_thumbnail() To check if item has thumb.
	 * @uses apply_filters() Calls 'nmi_menu_item_content' to
	 *                        filter outputted content.
	 * @uses get_the_post_thumbnail() To get item's thumb.
	 *
	 * @param string $content Item's content.
	 * @param int    $item_id Item's ID.
	 * @return string $content Item's content.
	 */
	public function menu_item_content( $content, $item_id ) {
		if ( has_post_thumbnail( $item_id ) ) {
			$content = apply_filters(
				'nmi_menu_item_content',
				get_the_post_thumbnail(
					$item_id,
					'full',
					array(
						'alt'   => $content,
						'title' => $content,
					)
				),
				$item_id,
				$content
			);
		}

		return $content;
	}

	/**
	 * Add action links to plugins page.
	 *
	 * @since 1.0
	 * @since 3.3 Added second parameter.
	 * @access public
	 *
	 * @uses Nav_Menu_Images::load_textdomain() To load translations.
	 *
	 * @param array  $links       Existing plugin's action links.
	 * @param string $plugin_file Path to the plugin file.
	 * @return array $links New plugin's action links.
	 */
	public function action_links( $links, $plugin_file ) {
		// Check if it is for this plugin.
		if ( $this->plugin_basename !== (string) $plugin_file ) {
			return $links;
		}

		// Load translations.
		$this->load_textdomain();

		$links['donate'] = '';
		return $links;
	}
}

/**
 * Initialize a plugin.
 *
 * Load class when all plugins are loaded
 * so that other plugins can overwrite it.
 *
 * @since 1.0
 *
 * @uses Nav_Menu_Images To initialize plugin.
 */
function nmi_instantiate() {

	$menu_id             = ciyashop_get_selected_menu_id();
	$cs_mega_menu_enable = get_post_meta( $menu_id, 'cs_megamenu_enable', true );

	if ( 'true' === (string) $cs_mega_menu_enable ) {
		new Nav_Menu_Images();
	}
}
add_action( 'after_setup_theme', 'nmi_instantiate', 15 );


