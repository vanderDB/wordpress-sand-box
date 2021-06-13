<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Nav Menu Images Admin Functions
 *
 * @package Nav Menu Images
 * @subpackage Admin Functions
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Nav Menu Images admin functions.
 *
 * @since 1.0
 *
 * @uses Nav_Menu_Images
 */
class Nav_Menu_Images_Admin extends Nav_Menu_Images {

	/**
	 * Sets class properties.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @uses add_filter() To hook filters.
	 * @uses add_action() To hook functions.
	 */
	public function __construct() {

		// Register new AJAX thumbnail response.
		add_filter( 'admin_post_thumbnail_html', array( $this, '_wp_post_thumbnail_html' ), 10, 2 );

		// Register walker replacement.
		add_filter( 'wp_edit_nav_menu_walker', array( $this, 'filter_walker' ) );

		// Register enqueuing of scripts.
		add_action( 'admin_menu', array( $this, 'register_enqueuing' ) );

		// Save the menu fields data.
		add_action( 'wp_ajax_pgs_update_menu_item_data', array( $this, 'pgs_update_menu_item_data' ) );
		add_action( 'wp_ajax_nopriv_pgs_update_menu_item_data', array( $this, 'pgs_update_menu_item_data' ) );

		// Get the menu item fields.
		add_action( 'wp_ajax_pgs_get_item_field', array( $this, 'pgs_get_item_field' ) );
		add_action( 'wp_ajax_nopriv_pgs_get_item_field', array( $this, 'pgs_get_item_field' ) );
	}

	/**
	 * Register script enqueuing on nav menu page.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @uses add_action() To hook function.
	 */
	public function register_enqueuing() {
		add_action( 'admin_print_scripts-nav-menus.php', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Enqueue necessary scripts.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @global $wp_version.
	 * @uses Nav_Menu_Images::load_textdomain() To load translations.
	 * @uses wp_enqueue_script() To enqueue scripts.
	 * @uses plugins_url() To get URL of the file.
	 * @uses wp_localize_script() To add script's variables.
	 * @uses add_thickbox() To enqueue Thickbox style & script.
	 * @uses version_compare() To compare WordPress versions.
	 * @uses wp_enqueue_media() To load media view templates, scripts & styles.
	 * @uses do_action() Calls 'nmi_enqueue_scripts'.
	 */
	public function enqueue_scripts() {
		global $wp_version;

		// Load translations.
		$this->load_textdomain();

		// Enqueue old script.
		wp_enqueue_script( 'nmi-scripts', get_parent_theme_file_uri( 'includes/admin/nav-menu-images/inc/nmi.js' ), array( 'media-upload', 'thickbox' ), '1', true );
		wp_localize_script(
			'nmi-scripts',
			'nmi_vars',
			array(
				'alert' => __( 'You need to set an image as a featured image to be able to use it as an menu item image', 'ciyashop' ),
			)
		);
		add_thickbox();

		// For WP 3.5+, enqueue new script & dependicies.
		if ( version_compare( $wp_version, '3.5', '>=' ) ) {
			wp_enqueue_media();
			wp_enqueue_script( 'nmi-media-view', get_parent_theme_file_uri( 'includes/admin/nav-menu-images/inc/media-view.js' ), array( 'jquery', 'media-editor', 'media-views', 'post' ), '1.1', true );
		}

		do_action( 'nmi_enqueue_scripts' );
	}

	/**
	 * Use custom walker for nav menu edit.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @uses Nav_Menu_Images::load_textdomain() To load translations.
	 *
	 * @param string $walker Name of used walker class.
	 */
	public function filter_walker( $walker ) {
		// Load translations.
		$this->load_textdomain();

		require_once dirname( __FILE__ ) . '/walker.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		return 'NMI_Walker_Nav_Menu_Edit';
	}

	/**
	 * Output HTML for the post thumbnail meta-box.
	 *
	 * @since 2.0
	 * @access public
	 *
	 * @uses get_post() To get post's object.
	 * @uses Nav_Menu_Images::load_textdomain() To load translations.
	 * @uses admin_url() To get URL of uploader.
	 * @uses esc_url() To escape URL.
	 * @uses add_query_arg() To append variables to URL.
	 * @uses has_post_thumbnail() To check if item has thumb.
	 * @uses get_the_post_thumbnail() To get item's thumb.
	 * @uses wp_create_nonce() To create item's nonce.
	 * @uses esc_html__() To translate & escape string.
	 * @uses apply_filters() Calls 'nmi_admin_post_thumbnail_html' to
	 *                        overwrite returned output.
	 *
	 * @param string $content Original HTML output of the thubnail.
	 * @param int    $post_id The post ID associated with the thumbnail.
	 * @return string New HTML output.
	 */
	public function _wp_post_thumbnail_html( $content, $post_id ) {
		// Check if request from this plugin.
		if ( ! isset( $_REQUEST['nmi_request'] ) ) {
			return $content;
		}

		// Get post object.
		$post = get_post( $post_id );

		// Check if post exists and is nav menu item.
		if ( ! $post || 'nav_menu_item' !== (string) $post->post_type ) {
			return $content;
		}

		// Load translations.
		$this->load_textdomain();
		$item_id = $post->ID;

		// Form upload link.
		$upload_url = admin_url( 'media-upload.php' );
		$query_args = array(
			'post_id'   => $item_id,
			'tab'       => 'gallery',
			'TB_iframe' => '1',
			'width'     => '640',
			'height'    => '425',
		);

		$upload_url = esc_url( add_query_arg( $query_args, $upload_url ) );
		$content    = '<input type="hidden" name="nmi_item_id" id="nmi_item_id" value="' . $item_id . '"/>';

		// Item's featured image or plain link.
		if ( has_post_thumbnail( $item_id ) ) {
			$link_text = __( 'Change Menu Image', 'ciyashop' );
			$content  .= '<div class="nmi-current-image nmi-div"><a href="' . $upload_url . '" data-id="' . $item_id . '" class="add_media">' . get_the_post_thumbnail( $item_id, 'thumb' ) . '</a></div>';
		} else {
			$link_text = __( 'Upload Menu Image', 'ciyashop' );
			$content  .= '<div class="nmi-current-image nmi-div"></div>';
		}

		$content .= '<div class="nmi-upload-link nmi-div">';
		$content .= '<a href="' . $upload_url . '" data-id="' . $item_id . '" class="add_media">' . $link_text . '</a>';

		if ( has_post_thumbnail( $item_id ) ) {
			$ajax_nonce = wp_create_nonce( 'set_post_thumbnail-' . $item_id );

			$content .= '<a href="#" data-id="' . $post->ID . '" class="nmi_remove" onclick="NMIRemoveThumbnail(\'' . $ajax_nonce . '\',' . $post->ID . ');return false;">' . esc_html__( 'Remove Image', 'ciyashop' ) . '</a>';
		}

		$content .= '</div>';

		// Filter returned HTML output.
		return apply_filters( 'nmi_admin_post_thumbnail_html', $content, $post->ID );
	}

	/******************************************
		:: Update the menu item post meta
	 *******************************************/
	public function pgs_update_menu_item_data() {

		$cs_mega_menu_nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $cs_mega_menu_nonce, 'cs_menu_save_nonce' ) ) {
			return false;
		}

		$menu_item_id   = isset( $_POST['menu_item_id'] ) ? sanitize_text_field( wp_unslash( $_POST['menu_item_id'] ) ) : '';
		$menu_item_data = isset( $_POST['menu_item_data'] ) ? wp_unslash( $_POST['menu_item_data'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		if ( ! $menu_item_id ) {
			return false;
		}

		foreach ( $menu_item_data as $item_key => $item_value ) {
			update_post_meta( $menu_item_id, 'pgs_menu-item-' . $item_key, sanitize_post( $item_value ) );
		}

		echo wp_json_encode( $menu_item_data );
		wp_die();
	}

	/**
	 * Get Mega Menu Fields
	 */
	public function pgs_get_item_field() {
		global $wp_version;

		$cs_menu_get_item_nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $cs_menu_get_item_nonce, 'cs_menu_get_item_nonce' ) ) {
			return false;
		}

		$menu_item_id   = isset( $_POST['menu_item_id'] ) ? sanitize_text_field( wp_unslash( $_POST['menu_item_id'] ) ) : '';
		$menu_depth     = isset( $_POST['menu_depth'] ) ? sanitize_text_field( wp_unslash( $_POST['menu_depth'] ) ) : '';
		$parent_menu_id = isset( $_POST['parent_menu_id'] ) ? sanitize_text_field( wp_unslash( $_POST['parent_menu_id'] ) ) : '';

		$menu_item_label = '';

		if ( isset( $_POST['menu_item_label'] ) ) {
			$menu_item_label = ' ( ' . sanitize_text_field( wp_unslash( $_POST['menu_item_label'] ) ) . ' )';
		}

		if ( ! $menu_item_id ) {
			return false;
		}

		$output  = '<div class="pgs_menu-custom-fields" data-menu_item_id="' . esc_attr( $menu_item_id ) . '">';
		$output .= '<div class="pgs_menu_item-popup">';
		$output .= '<div class="pgs_menu_modal"><div class="pgs_menu_item-popup-close"><span class="dashicons dashicons-no"></span></div>';
		$output .= '<span class="pgs_menu-title">' . esc_html( 'Menu options' . $menu_item_label ) . '</span>';
		$output .= '</div><div class="pgs_menu_popup-content">';
		$output .= menu_render_fields( $menu_item_id, $menu_depth, $parent_menu_id );
		$output .= '</div><div class="pgs_menu_popup-footer">';
		$output .= '<div class="pgs_menu-edit-action-tabs"><a class="button button-large pgs_menu-save-element">' . esc_html__( 'Save', 'ciyashop' ) . '</a><a class="button button-secondary pgs_menu-close-element">' . esc_html__( 'Close', 'ciyashop' ) . '</a></div>';
		$output .= '</div><div class="pgs_menu-overlay"></div></div>';

		echo wp_json_encode( $output );
		wp_die();
	}
}
