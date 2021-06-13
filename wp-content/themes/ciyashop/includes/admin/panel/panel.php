<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * CiyaShop Welcome Panel Class
 *
 * Displays panel with various details.
 *
 * @author      dinesh
 * @package     ciyashop
 * @version     2.0.0
 */

if ( ! class_exists( 'Ciyashop_Panel' ) ) {
	/**
	 * Ciyashop Panel
	 */
	class Ciyashop_Panel {
		/**
		 * Variable
		 *
		 * @var $args
		 */
		private $args = array();
		/**
		 * Variable
		 *
		 * @var $sections
		 */
		private $sections = array();
		/**
		 * Variable
		 *
		 * @var $new_sections
		 */
		private $new_sections = array();
		/**
		 * Variable
		 *
		 * @var $theme_data
		 */
		private $theme_data = array();
		/**
		 * Variable
		 *
		 * @var $theme_slug
		 */
		private $theme_slug = '';
		/**
		 * Variable
		 *
		 * @var $theme_id
		 */
		private $theme_id = '';

		/**
		 * Variable
		 *
		 * @var $slu
		 */
		private $slug = '';
		/**
		 * Variable
		 *
		 * @var $menu_title
		 */
		private $menu_title = 'CiyaShop';
		/**
		 * Variable
		 *
		 * @var $theme_name
		 */
		private $theme_name = 'CiyaShop';
		/**
		 * Variable
		 *
		 * @var $class_prefix
		 */
		private $class_prefix = 'ciyashop';
		/**
		 * Variable
		 *
		 * @var $current_section
		 */
		private $current_section = '';
		/**
		 * Variable
		 *
		 * @var $ciyashop_panel_page
		 */
		private $ciyashop_panel_page;


		/**
		 * A dummy constructor to prevent this class from being loaded more than once.
		 *
		 * @since 2.0.0
		 */
		public function __construct() {

			$current_template = get_template();
			$this->theme_data = wp_get_theme( $current_template );
			$this->theme_slug = sanitize_title( $current_template );
			$this->theme_id   = str_replace( '-', '_', $current_template );

			$this->set_default_args();
			$this->set_default_sections();

			/**
			 * Filter 'ciyashop-panel-sections'
			 *
			 * @param  array $sections list of sections
			 */
			$this->new_sections = (array) apply_filters( 'ciyashop-panel-sections', array() ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
			$this->sections     = array_merge( $this->sections, $this->new_sections );

			add_action( 'admin_menu', array( $this, 'register_menu' ) );

		}
		/**
		 * Default arguments
		 */
		private function set_default_args() {
			$this->args = array(
				'slug'               => 'ciyashop-panel',
				'menu_title'         => $this->menu_title, // menu title.
				'page_title'         => $this->theme_name, // page title.
				/* translators: $s: Theme Name */
				'screen_title'       => sprintf( esc_html__( 'Welcome to %s', 'ciyashop' ), $this->theme_name ), // page title.
				'menu_icon'          => get_parent_theme_file_uri() . '/images/admin/admin-icon.png', // menu icon.
				'class_prefix'       => $this->class_prefix,
				'templates_path'     => 'includes/admin/panel/templates', // Path to the templates file for sections.
				'templates_uri'      => get_parent_theme_file_uri() . '/includes/admin/panel/templates', // URL to the templates file for sections.
				'page_permissions'   => 'manage_options',
				'page_priority'      => 2,

				'admin_bar'          => true,
				'admin_bar_priority' => 999,                                                                          // Show the panel pages on the admin bar.
				'admin_bar_icon'     => '',                                                                           // admin bar icon.
				'help_tabs'          => array(),
				'help_sidebar'       => '',
			);
		}
		/**
		 * Default sections
		 */
		private function set_default_sections() {
			$this->sections = array(
				'license' => array(
					'slug'  => 'license',
					'title' => esc_html__( 'License', 'ciyashop' ),
				),
			);
		}

		/**
		 * Creates the dashboard page
		 *
		 * @see  add_theme_page()
		 * @since 1.0.0
		 */
		public function register_menu() {
			$this->ciyashop_panel_page = add_menu_page( // phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_menu_page
				$this->args['page_title'],       // Page Title.
				$this->args['menu_title'],       // Menu Title.
				$this->args['page_permissions'], // Capability.
				$this->args['slug'],             // Slug.
				array( $this, 'settings_content' ), // Callback.
				$this->args['menu_icon'],        // Icon.
				$this->args['page_priority']     // Position.
			);

			$tabs_sr = 0;
			global $submenu;

			foreach ( $this->sections as $tab_k => $tab ) {

				if ( 0 === (int) $tabs_sr ) {
					$url = $this->args['slug'];
				} else {
					$url = $this->args['slug'] . '-' . sanitize_text_field( $tab_k );
				}

				add_submenu_page( // phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_submenu_page
					$this->args['slug'],
					$tab['title'],
					$tab['title'],
					$this->args['page_permissions'],
					$url,
					array( $this, 'settings_content' )
				);

				if ( isset( $tab['link_type'] ) && 'custom' === $tab['link_type'] ) {

					$submenu[ $this->args['slug'] ][ $tabs_sr ][2] = $tab['link']; // phpcs:ignore WordPress.WP.GlobalVariablesOverride
				}

				$tabs_sr++;
			}
		}
		/**
		 * Current tab
		 */
		public function get_current_tab() {

			$screen = get_current_screen();

			$current_tab = 'license';

			if ( (string) $screen->id !== (string) $this->ciyashop_panel_page ) {
				$current_tab = ( isset( $_GET['page'] ) && ! empty( $_GET['page'] ) ) ? str_replace( $this->args['slug'] . '-', '', sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) : '';
			}

			return $current_tab;
		}

		/**
		 * Display a custom menu page
		 */
		public function settings_content() {

			$screen      = get_current_screen();
			$current_tab = $this->get_current_tab();

			$this->get_template( 'content.php' );
		}
		/**
		 * Welcom logo
		 */
		public function welcome_logo() {
			$welcome_logo      = get_parent_theme_file_uri() . '/images/admin/welcome-logo.png';
			$welcome_logo_path = get_parent_theme_file_path() . '/images/admin/welcome-logo.png';
			if ( file_exists( $welcome_logo_path ) && getimagesize( $welcome_logo_path ) !== false ) {
				return $welcome_logo;
			} else {
				return false;
			}
		}

		/**
		 * Get other templates (e.g. product attributes) passing attributes and including the file.
		 *
		 * @access public
		 * @param string $template_name Template name.
		 * @param array  $args          Arguments. (default: array).
		 * @param string $template_path Template path. (default: '').
		 * @param string $default_path  Default path. (default: '').
		 */
		public function get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {

			$templates_path = $this->args['templates_path'];

			if ( ! empty( $args ) && is_array( $args ) ) {
				extract( $args ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
			}

			$located = $this->locate_template( $template_name, $templates_path );

			// Allow 3rd party plugin filter template file from their plugin.
			$located = apply_filters( 'ciyashop_panel_get_template', $located, $template_name, $args, $templates_path );

			do_action( 'ciyashop_panel_before_template_part', $template_name, $templates_path, $located, $args );

			if ( file_exists( $located ) ) {
				include $located; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			}

			do_action( 'cs_admin_after_template_part', $template_name, $templates_path, $located, $args );
		}
		/**
		 * Locate template
		 *
		 * @param string $template_name .
		 * @param string $templates_path .
		 */
		public function locate_template( $template_name, $templates_path = '' ) {

			// Look within passed path within the theme - this is priority.
			$template = locate_template(
				array(
					trailingslashit( $templates_path ) . $template_name,
				)
			);

			$sections    = $this->sections;
			$current_tab = $this->get_current_tab();

			// Get template from plugin, if provided.
			if ( ! $template && ( isset( $sections[ $current_tab ]['template'] ) && ! empty( $sections[ $current_tab ]['template'] ) ) ) {
				$template = $sections[ $current_tab ]['template'];
			}

			// Return what we found.
			return apply_filters( 'ciyashop_panel_locate_template', $template, $template_name, $templates_path );
		}
	}
}

add_action( 'init', 'ciyashop_panel_init' );
/**
 * Panel init
 */
function ciyashop_panel_init() {
	$GLOBALS['Ciyashop_Panel'] = new Ciyashop_Panel();
}
