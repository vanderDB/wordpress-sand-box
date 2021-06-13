<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Envato Theme Setup Wizard Class
 *
 * Takes new users through some basic steps to setup their ThemeForest theme.
 *
 * @author      dtbaker
 * @author      vburlak
 * @package     envato_wizard
 * @version     1.2.4
 *
 *
 * 1.2.0 - added custom_logo
 * 1.2.1 - ignore post revisioins
 * 1.2.2 - elementor widget data replace on import
 * 1.2.3 - auto export of content.
 * 1.2.4 - fix category menu links
 *
 * Based off the WooThemes installer.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Envato_Theme_Setup_Wizard' ) ) {
	/**
	 * Envato_Theme_Setup_Wizard class
	 */
	class Envato_Theme_Setup_Wizard {

		/**
		 * The class version number.
		 *
		 * @since 1.1.1
		 * @access private
		 *
		 * @var string
		 */
		protected $version = '1.2.4';
		/**
		 * Variable
		 *
		 * @var $theme_data
		 */
		protected $theme_data = '';
		/**
		 * Current theme name, used as namespace in actions.
		 *
		 * @var $theme_name
		 */
		protected $theme_name = '';
		/**
		 * Current Step
		 *
		 * @var $step
		 */
		protected $step = '';
		/**
		 * Steps for the setup wizard
		 *
		 * @var array
		 */
		protected $steps = array();

		/**
		 * Relative plugin path
		 *
		 * @since 1.1.2
		 *
		 * @var string
		 */
		protected $plugin_path = '';

		/**
		 * Relative plugin url for this plugin folder, used when enquing scripts
		 *
		 * @since 1.1.2
		 *
		 * @var string
		 */
		protected $plugin_url = '';

		/**
		 * The slug name to refer to this menu
		 *
		 * @since 1.1.1
		 *
		 * @var string
		 */
		protected $page_slug;

		/**
		 * TGMPA instance storage
		 *
		 * @var object
		 */
		protected $tgmpa_instance;

		/**
		 * TGMPA Menu slug
		 *
		 * @var string
		 */
		protected $tgmpa_menu_slug = 'tgmpa-install-plugins';

		/**
		 * TGMPA Menu url
		 *
		 * @var string
		 */
		protected $tgmpa_url = 'themes.php?page=tgmpa-install-plugins';

		/**
		 * The slug name for the parent menu
		 *
		 * @since 1.1.2
		 *
		 * @var string
		 */
		protected $page_parent;

		/**
		 * Complete URL to Setup Wizard
		 *
		 * @since 1.1.2
		 *
		 * @var string
		 */
		public $page_url;
		/**
		 * Sample datas
		 *
		 * @var $sample_datas
		 */
		protected $sample_datas;
		/**
		 * Sample datas
		 *
		 * @var $themeforest_profile_url
		 */
		protected $themeforest_profile_url;


		/**
		 * Holds the current instance of the theme manager
		 *
		 * @since 1.1.3
		 * @var Envato_Theme_Setup_Wizard
		 */
		private static $instance = null;
		/**
		 * Vaiable
		 *
		 * @var $api_url
		 */
		public $api_url;

		/**
		 * Envato Theme Setup Wizard
		 *
		 * @since 1.1.3
		 *
		 * @return Envato_Theme_Setup_Wizard
		 */
		public static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * A dummy constructor to prevent this class from being loaded more than once.
		 *
		 * @see Envato_Theme_Setup_Wizard::instance()
		 *
		 * @since 1.1.1
		 * @access private
		 */
		public function __construct() {
			$this->init_globals();
			$this->init_actions();
		}

		/**
		 * Get the default style. Can be overriden by theme init scripts.
		 *
		 * @see Envato_Theme_Setup_Wizard::instance()
		 *
		 * @since 1.1.7
		 * @access public
		 */
		public function get_default_theme_style() {
			return 'pink';
		}

		/**
		 * Get the default style. Can be overriden by theme init scripts.
		 *
		 * @see Envato_Theme_Setup_Wizard::instance()
		 *
		 * @since 1.1.9
		 * @access public
		 */
		public function get_header_logo_width() {
			return apply_filters( 'envato_theme_setup_wizard_header_logo_width', '200px' );
		}


		/**
		 * Get the default style. Can be overriden by theme init scripts.
		 *
		 * @see Envato_Theme_Setup_Wizard::instance()
		 *
		 * @since 1.1.9
		 * @access public
		 */
		public function get_logo_image() {
			$image_url = trailingslashit( $this->plugin_url ) . 'images/logo.png';
			return apply_filters( 'envato_setup_logo_image', $image_url );
		}

		/**
		 * Setup the class globals.
		 *
		 * @since 1.1.1
		 * @access public
		 */
		public function init_globals() {
			$current_theme       = wp_get_theme();
			$current_child_theme = false;

			if ( is_child_theme() ) {
				$current_child_theme = $current_theme;
				$current_theme       = wp_get_theme( $current_theme->get( 'Template' ) );
			}

			$this->theme_data              = $current_theme;
			$this->theme_slug              = apply_filters( 'envato_theme_setup_wizard_theme_slug', sanitize_title( $current_theme->get( 'Name' ) ) );
			$this->theme_name              = apply_filters( 'envato_theme_setup_wizard_theme_name', str_replace( '-', '_', $this->theme_slug ) );
			$this->page_slug               = apply_filters( 'envato_theme_setup_wizard_page_slug', $this->theme_name . '-setup' );
			$this->parent_slug             = apply_filters( 'envato_theme_setup_wizard_parent_slug', '' );
			$this->page_url                = apply_filters( 'envato_theme_setup_wizard_page_url', ( '' !== $this->parent_slug ) ? 'admin.php?page=' . $this->page_slug : 'themes.php?page=' . $this->page_slug );
			$this->api_url                 = apply_filters( 'envato_theme_setup_wizard_api_url', '' );
			$this->sample_datas            = apply_filters( 'envato_theme_setup_wizard_styles', array() );
			$this->themeforest_profile_url = apply_filters( 'envato_theme_setup_wizard_themeforest_profile_url', array() );

			// set relative plugin path url.
			$this->plugin_path = trailingslashit( $this->cleanFilePath( dirname( __FILE__ ) ) );
			$relative_url      = str_replace( $this->cleanFilePath( get_parent_theme_file_path() ), '', $this->plugin_path );
			$this->plugin_url  = get_parent_theme_file_uri( $relative_url );
		}

		/**
		 * Setup the hooks, actions and filters.
		 *
		 * @uses add_action() To add actions.
		 * @uses add_filter() To add filters.
		 *
		 * @since 1.1.1
		 * @access public
		 */
		public function init_actions() {
			if ( apply_filters( $this->theme_name . '_enable_setup_wizard', true ) && current_user_can( 'manage_options' ) ) {

				if ( ! is_child_theme() ) {
					add_action( 'after_switch_theme', array( $this, 'switch_theme' ) );
				}

				if ( class_exists( 'TGM_Plugin_Activation' ) && isset( $GLOBALS['tgmpa'] ) ) {
					add_action( 'init', array( $this, 'get_tgmpa_instanse' ), 30 );
					add_action( 'init', array( $this, 'set_tgmpa_url' ), 40 );
				}

				add_action( 'admin_menu', array( $this, 'admin_menus' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
				add_action( 'admin_init', array( $this, 'admin_redirects' ), 30 );
				add_action( 'admin_init', array( $this, 'init_wizard_steps' ), 30 );
				add_action( 'admin_init', array( $this, 'setup_wizard' ), 30 );
				add_filter( 'tgmpa_load', array( $this, 'tgmpa_load' ), 10, 1 );
				add_action( 'wp_ajax_envato_setup_plugins', array( $this, 'ajax_plugins' ) );
			}
		}

		/**
		 * After a theme update we clear the setup_complete option. This prompts the user to visit the update page again.
		 *
		 * @since 1.1.8
		 * @access public
		 * @param bool   $return .
		 * @param string $theme .
		 */
		public function upgrader_post_install( $return, $theme ) {
			if ( is_wp_error( $return ) ) {
				return $return;
			}
			if ( get_stylesheet() !== $theme ) {
				return $return;
			}
			update_option( 'envato_setup_complete', false );

			return $return;
		}

		/**
		 * Enqueue scripts
		 */
		public function enqueue_scripts() {
		}
		/**
		 * Tgmpa load
		 *
		 * @param string $status .
		 */
		public function tgmpa_load( $status ) {
			return is_admin() || current_user_can( 'install_themes' );
		}
		/**
		 * Switch theme
		 */
		public function switch_theme() {
			set_transient( '_' . $this->theme_name . '_activation_redirect', 1 );
		}
		/**
		 * Admin redirects
		 */
		public function admin_redirects() {
			$after_theme_switch = $this->after_theme_switch();
			if ( isset( $after_theme_switch ) && 'wizard' === (string) $after_theme_switch ) {
				ob_start();
				if ( ! get_transient( '_' . $this->theme_name . '_activation_redirect' ) || get_option( 'envato_setup_complete', false ) ) {
					return;
				}
				delete_transient( '_' . $this->theme_name . '_activation_redirect' );
				wp_safe_redirect( admin_url( $this->page_url ) );
				exit;
			}
		}

		/**
		 * Get configured TGMPA instance
		 *
		 * @access public
		 * @since 1.1.2
		 */
		public function get_tgmpa_instanse() {
			$this->tgmpa_instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
		}

		/**
		 * Update $tgmpa_menu_slug and $tgmpa_parent_slug from TGMPA instance
		 *
		 * @access public
		 * @since 1.1.2
		 */
		public function set_tgmpa_url() {

			$this->tgmpa_menu_slug = ( property_exists( $this->tgmpa_instance, 'menu' ) ) ? $this->tgmpa_instance->menu : $this->tgmpa_menu_slug;
			$this->tgmpa_menu_slug = apply_filters( $this->theme_name . '_theme_setup_wizard_tgmpa_menu_slug', $this->tgmpa_menu_slug );

			$tgmpa_parent_slug = ( property_exists( $this->tgmpa_instance, 'parent_slug' ) && 'themes.php' !== $this->tgmpa_instance->parent_slug ) ? 'admin.php' : 'themes.php';

			$this->tgmpa_url = apply_filters( $this->theme_name . '_theme_setup_wizard_tgmpa_url', $tgmpa_parent_slug . '?page=' . $this->tgmpa_menu_slug );

		}

		/**
		 * Add admin menus/screens.
		 */
		public function admin_menus() {

			if ( $this->is_submenu_page() ) {
				// prevent Theme Check warning about "themes should use add_theme_page for adding admin pages".
				$add_subpage_function = 'add_submenu_page';
				$add_subpage_function(
					$this->parent_slug,
					esc_html__( 'Setup Wizard', 'ciyashop' ),
					esc_html__( 'Setup Wizard', 'ciyashop' ),
					'manage_options',
					$this->page_slug,
					array(
						$this,
						'setup_wizard',
					)
				);
			} else {
				add_theme_page(
					esc_html__( 'Setup Wizard', 'ciyashop' ),
					esc_html__( 'Setup Wizard', 'ciyashop' ),
					'manage_options',
					$this->page_slug,
					array(
						$this,
						'setup_wizard',
					)
				);
			}

		}


		/**
		 * Setup steps.
		 *
		 * @since 1.1.1
		 * @access public
		 */
		public function init_wizard_steps() {

			$purchase_token = ciyashop_is_activated();

			$this->steps = array(
				'introduction' => array(
					'name'    => esc_html__( 'Introduction', 'ciyashop' ),
					'view'    => array( $this, 'envato_setup_introduction' ),
					'handler' => array( $this, 'envato_setup_introduction_save' ),
				),
			);

			$this->steps['activate'] = array(
				'name'    => esc_html__( 'Activate', 'ciyashop' ),
				'view'    => array( $this, 'envato_setup_activate' ),
				'handler' => array( $this, 'envato_setup_activate_save' ),
			);

			$this->steps['customize'] = array(
				'name'    => esc_html__( 'Child Theme', 'ciyashop' ),
				'view'    => array( $this, 'envato_setup_customize' ),
				'handler' => '',
			);

			if ( class_exists( 'TGM_Plugin_Activation' ) && isset( $GLOBALS['tgmpa'] ) ) {
				$this->steps['default_plugins'] = array(
					'name'    => esc_html__( 'Plugins', 'ciyashop' ),
					'view'    => array( $this, 'envato_setup_default_plugins' ),
					'handler' => '',
				);
			}
			$this->steps['default_content'] = array(
				'name'    => esc_html__( 'Content', 'ciyashop' ),
				'view'    => array( $this, 'envato_setup_default_content' ),
				'handler' => '',
			);
			$this->steps['help_support']    = array(
				'name'    => esc_html__( 'Support', 'ciyashop' ),
				'view'    => array( $this, 'envato_setup_help_support' ),
				'handler' => '',
			);
			$this->steps['final']           = array(
				'name'    => esc_html__( 'Ready!', 'ciyashop' ),
				'view'    => array( $this, 'envato_setup_ready' ),
				'handler' => '',
			);

			$this->steps = apply_filters( $this->theme_name . '_theme_setup_wizard_steps', $this->steps );

		}

		/**
		 * Show the setup wizard
		 */
		public function setup_wizard() {
			if ( empty( $_GET['page'] ) || $this->page_slug !== $_GET['page'] ) {
				return;
			}
			ob_end_clean();

			$this->step = isset( $_GET['step'] ) ? sanitize_key( $_GET['step'] ) : current( array_keys( $this->steps ) );

			wp_register_script( 'jquery-blockui', $this->plugin_url . 'js/jquery.blockUI.js', array( 'jquery' ), '2.70', true );
			wp_register_script(
				'envato-setup',
				$this->plugin_url . 'js/envato-setup.js',
				array(
					'jquery',
					'jquery-blockui',
				),
				$this->version
			);
			wp_localize_script(
				'envato-setup',
				'envato_setup_params',
				array(
					'tgm_plugin_nonce' => array(
						'update'  => wp_create_nonce( 'tgmpa-update' ),
						'install' => wp_create_nonce( 'tgmpa-install' ),
					),
					'tgm_bulk_url'     => admin_url( $this->tgmpa_url ),
					'ajaxurl'          => admin_url( 'admin-ajax.php' ),
					'wpnonce'          => wp_create_nonce( 'envato_setup_nonce' ),
					'verify_text'      => esc_html__( '...verifying', 'ciyashop' ),
				)
			);

			wp_enqueue_style(
				'envato-setup',
				$this->plugin_url . 'css/envato-setup.css',
				array(
					'wp-admin',
					'dashicons',
					'install',
				),
				$this->version
			);

			// enqueue style for admin notices.
			wp_enqueue_style( 'wp-admin' );

			wp_enqueue_media();
			wp_enqueue_script( 'media' );

			ob_start();
			$this->setup_wizard_header();
			$this->setup_wizard_steps();
			$show_content = true;
			?>
			<div class="envato-setup-content envato-setup-content-step-<?php echo esc_attr( $this->step ); ?>">
				<?php
				if ( ! empty( $_REQUEST['save_step'] ) && isset( $this->steps[ $this->step ]['handler'] ) ) {
					$show_content = call_user_func( $this->steps[ $this->step ]['handler'], $this );
				}
				if ( $show_content ) {
					$this->setup_wizard_content();
				}
				?>
			</div>
			<?php
			$this->setup_wizard_footer();
			exit;
		}
		/**
		 * Get step link
		 *
		 * @param string $step .
		 */
		public function get_step_link( $step ) {
			return add_query_arg( 'step', $step, admin_url( 'admin.php?page=' . $this->page_slug ) );
		}
		/**
		 * Get next step link
		 */
		public function get_next_step_link() {
			$keys = array_keys( $this->steps );

			return add_query_arg( 'step', $keys[ array_search( $this->step, array_keys( $this->steps ), true ) + 1 ], remove_query_arg( 'translation_updated' ) );
		}

		/**
		 * Setup Wizard Header
		 */
		public function setup_wizard_header() {
			?>
			<!DOCTYPE html>
			<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
			<head>
				<meta name="viewport" content="width=device-width"/>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
				<title><?php wp_title(); // phpcs:ignore WPThemeReview.CoreFunctionality.NoTitleTag.TagFound ?></title>
				<?php
				wp_print_scripts( 'envato-setup' );
				do_action( 'admin_print_styles' );
				do_action( 'admin_print_scripts' );
				?>
			</head>
			<body class="envato-setup wp-core-ui envato-setup-step-<?php echo esc_attr( $this->step ); ?>">
				<h1 id="wc-logo">
					<?php
					$header_logo = sprintf(
						'<img class="site-logo" src="%s" alt="%s" style="width:%s; height:auto" />',
						( $this->get_logo_image() ) ? $this->get_logo_image() : trailingslashit( $this->plugin_url ) . 'images/logo.png',
						$this->theme_data->get( 'Name' ),
						$this->get_header_logo_width()
					);
					echo wp_kses( $header_logo, ciyashop_allowed_html( array( 'img' ) ) );
					?>
				</h1>
			<?php
		}

		/**
		 * Setup Wizard Footer
		 */
		public function setup_wizard_footer() {
			if ( 'final' === $this->step ) {
				?>
						<a class="wc-return-to-dashboard" href="<?php echo esc_url( admin_url() ); ?>">
					<?php esc_html_e( 'Return to the WordPress Dashboard', 'ciyashop' ); ?>
						</a>
						<?php
			}
			?>
					<p class="copyrights">
						<?php
						$copyright = '';
						if ( $this->theme_data->get( 'Author' ) ) {
							$copyright = sprintf(
								// Translators: %s is the theme author link.
								esc_html__( '&copy; Created by %s', 'ciyashop' ),
								( $this->theme_data->get( 'AuthorURI' ) ) ? sprintf( '<a href="%s" target="_blank" rel="noopener">%s</a>', $this->theme_data->get( 'AuthorURI' ), $this->theme_data->get( 'Author' ) ) : $this->theme_data->get( 'Author' )
							);
						}
						$copyright = apply_filters( 'envato_setup_wizard_footer_copyright', $copyright, $this->theme_data );
						if ( $copyright ) {
							echo wp_kses( $copyright, ciyashop_allowed_html( 'a', 'span', 'i' ) );
						}
						?>
					</p>
				</body>
				<?php
				do_action( 'admin_footer' );
				do_action( 'admin_print_footer_scripts' );
				?>
			</html>
			<?php
		}

		/**
		 * Output the steps
		 */
		public function setup_wizard_steps() {
			$ouput_steps = $this->steps;
			array_shift( $ouput_steps );
			?>
			<ol class="envato-setup-steps">
				<?php
				foreach ( $ouput_steps as $step_key => $step ) :
					$class  = 'envato-setup-step';
					$class .= ' envato-setup-step-' . $step_key;

					$show_link = false;
					if ( $step_key === $this->step ) {
						$class .= ' active';
					} elseif ( array_search( $this->step, array_keys( $this->steps ), true ) > array_search( $step_key, array_keys( $this->steps ), true ) ) {
						$class    .= ' done';
						$show_link = true;
					}
					?>
					<li class="<?php echo esc_attr( $class ); ?>">
						<?php
						if ( $show_link ) {
							?>
							<a href="<?php echo esc_url( $this->get_step_link( $step_key ) ); ?>"><?php echo esc_html( $step['name'] ); ?></a>
							<?php
						} else {
							echo esc_html( $step['name'] );
						}
						?>
					</li>
				<?php endforeach; ?>
			</ol>
			<?php
		}

		/**
		 * Output the content for the current step
		 */
		public function setup_wizard_content() {
			isset( $this->steps[ $this->step ] ) ? call_user_func( $this->steps[ $this->step ]['view'], $this ) : false;
		}

		/**
		 * Introduction step
		 */
		public function envato_setup_introduction() {

			if ( false && isset( $_REQUEST['debug'] ) ) {
				?>
				<pre>
					<?php
					// debug inserting a particular post so we can see what's going on.
					$post_type = 'post';
					$post_id   = 1; // debug this particular import post id.
					$all_data  = $this->_get_json( 'default.json' );
					if ( ! $post_type || ! isset( $all_data[ $post_type ] ) ) {
						echo sprintf(
							/* translators: $s: not found */
							esc_html__( 'Post type %s not found.', 'ciyashop' ),
							$post_type
						); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
					} else {
						echo sprintf(
							/* translators: $s: Looking for post */
							esc_html__( 'Looking for post id %s', 'ciyashop' ),
							$post_id
						) . "\n"; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
						foreach ( $all_data[ $post_type ] as $post_data ) {

							if ( (string) $post_data['post_id'] === (string) $post_id ) {
								print_r( $post_data );
							}
						}
					}
					print_r( $this->logs );
					?>
				</pre>
				<?php
			} elseif ( get_option( 'envato_setup_complete', false ) ) {
				?>
				<h1>
				<?php
				printf(
					/* translators: $s: Link to Home */
					esc_html__( 'Welcome to the steps for setting the %s theme.', 'ciyashop' ),
					$this->theme_data->get( 'Name' )
				); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
				?>
					</h1>
				<p><?php esc_html_e( 'It seems that you have already been through the setup medium. Below are some choices:', 'ciyashop' ); ?></p>
				<ul>
					<li>
						<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-primary button-next button-large">
							<?php esc_html_e( 'Run Setup Wizard Again', 'ciyashop' ); ?>
						</a>
					</li>
				</ul>
				<p class="envato-setup-actions step">
					<a href="<?php echo esc_url( wp_get_referer() && ! strpos( wp_get_referer(), 'update.php' ) ? wp_get_referer() : admin_url( '' ) ); ?>"
					class="button button-large"><?php esc_html_e( 'Cancel', 'ciyashop' ); ?>
					</a>
				</p>
				<?php
			} else {
				?>
				<h1>
				<?php
				printf(
					/* translators: $s: Link to Home */
					esc_html__( 'Welcome to %s Setup Wizard', 'ciyashop' ),
					$this->theme_data->get( 'Name' )
				); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
				?>
					</h1>
				<p>
				<?php
				printf(
					/* translators: $s: Link to Home */
					esc_html__( 'Thank you for choosing %s theme.', 'ciyashop' ),
					$this->theme_data->get( 'Name' )
				); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
				?>
					</p>
				<p>
				<?php
				printf(
					/* translators: $s: Link to Home */
					esc_html__( 'This setup wizard will help you to refresh and configure your website with a new layout. You will have Child Theme, Content, and Plugins installed in 5-10 minutes (depending on your server configuration). ', 'ciyashop' ),
					$this->theme_data->get( 'Name' )
				);// phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
				?>
					</p>
				<p><?php esc_html_e( 'No time right now? If you do not want to go through the wizard, you can skip, and get back to WordPress dashboard. Come back any time to continue!', 'ciyashop' ); ?></p>
				<p class="envato-setup-actions step">
					<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>"
					class="button button-large button-primary button-next button-active"><?php esc_html_e( 'Let\'s Go!', 'ciyashop' ); ?></a>
					<a href="<?php echo esc_url( wp_get_referer() && ! strpos( wp_get_referer(), 'update.php' ) ? wp_get_referer() : admin_url( '' ) ); ?>"
					class="button button-large"><?php esc_html_e( 'Not right now', 'ciyashop' ); ?></a>
				</p>
				<?php
			}
		}
		/**
		 * Filter options
		 *
		 * @param string $options .
		 */
		public function filter_options( $options ) {
			return $options;
		}

		/**
		 *
		 * Handles save button from welcome page. This is to perform tasks when the setup wizard has already been run. E.g. reset defaults
		 *
		 * @since 1.2.5
		 */
		public function envato_setup_introduction_save() {

			check_admin_referer( 'envato-setup' );

			if ( ! empty( $_POST['reset-font-defaults'] ) && 'yes' === (string) sanitize_text_field( wp_unslash( $_POST['reset-font-defaults'] ) ) ) {

				// clear font options.
				update_option( 'tt_font_theme_options', array() );

				// reset site color.
				remove_theme_mod( 'dtbwp_site_color' );

				if ( class_exists( 'dtbwp_customize_save_hook' ) ) {
					$site_color_defaults = new dtbwp_customize_save_hook();
					$site_color_defaults->save_color_options();
				}

				$file_name = get_parent_theme_file_path( '/style.custom.css' );
				if ( file_exists( $file_name ) ) {
					require_once ABSPATH . 'wp-admin/includes/file.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
					WP_Filesystem();
					global $wp_filesystem;
					$wp_filesystem->put_contents( $file_name, '' );
				}
				?>
				<p>
					<strong><?php esc_html_e( 'Options have been reset. Please go to Appearance > Customize in the WordPress backend.', 'ciyashop' ); ?></strong>
				</p>
				<?php
				return true;
			}

			return false;
		}

		/**
		 * Payments Step
		 */
		public function envato_setup_activate() {
			?>
			<h1><?php esc_html_e( 'Activate Theme', 'ciyashop' ); ?></h1>
			<p class="lead"><?php esc_html_e( 'Enter purchase code to activate your theme.', 'ciyashop' ); ?></p>
			<?php
			$slug = basename( get_template_directory() );

			$output = '';

			// get notice.
			$notices = get_option( 'ciyashop_purchase_code_notice', array() );
			delete_option( 'ciyashop_purchase_code_notice' );

			// get purchase code and purchase token.
			$purchase_code = sanitize_text_field( get_option( 'ciyashop_theme_purchase_key', '' ) );
			$notices       = get_option( 'ciyashop_purchase_code_notices' );
			delete_option( 'ciyashop_purchase_code_notices' );

			if ( ! empty( $purchase_code ) && empty( $notices ) ) {
				$notices = array(
					'notice_type' => 'success',
					'notice'      => esc_html__( 'Purchase code successfully verified.', 'ciyashop' ),
				);
			}
			?>
			<form class="ciyashop_activate_theme" method="post" action="">
			<?php
			// display notices.
			if ( ! empty( $notices ) ) {
				echo '<div class="notice-' . $notices['notice_type'] . ' notice-alt notice-large"><p>' . $notices['notice'] . '</p></div>';// phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			}
			$purchase_disabled = ! empty( $purchase_code ) ? 'disabled' : '';
			?>
				<?php wp_nonce_field( 'purchase_code_activation', 'purchase_code_nonce' ); ?>
				<input type="text" id="ciyashop_purchase_code" name="ciyashop_purchase_code" value="<?php echo esc_attr( $purchase_code ); ?>" placeholder="<?php esc_attr_e( 'Purchase code ( e.g. 9g2b13fa-10aa-2267-883a-9201a94cf9b5 )', 'ciyashop' ); ?>" <?php echo esc_attr( $purchase_disabled ); ?>/>
				<div class="activation-instructions">
					<h3><?php esc_html_e( 'Instructions to find the Purchase Code', 'ciyashop' ); ?></h3>
					<ol>
						<li><?php esc_html_e( 'Log into your Envato Market account.', 'ciyashop' ); ?></li>
						<li><?php esc_html_e( 'Hover the mouse over your username at the top of the screen.', 'ciyashop' ); ?></li>
						<li><?php esc_html_e( 'Click \'Downloads\' from the drop-down menu.', 'ciyashop' ); ?></li>
						<li>
						<?php
						printf(
							// Translators: %s is the ThemeForest Item Support Policy link.
							wp_kses( /* translators: $s: Link to Home */
								__( 'Click \'License certificate & purchase code\' (available as PDF or text file). Click <a href="%s" target="_blank">here</a> for more information.', 'ciyashop' ),
								ciyashop_allowed_html( array( 'a' ) )
							),
							'https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code'
						);
						?>
						</li>
					</ol>
				</div>
				<div class="license-notice">
					<?php echo '<b>' . esc_html__( 'Note:', 'ciyashop' ) . '</b> ' . esc_html__( 'you are allowed to use our theme only on one domain if you have purchased a regular license. ', 'ciyashop' ) . '<br/>' . esc_html__( 'But we give you an ability to activate our theme to turn on auto updates on two domains: for the development website and for your production (live) website.', 'ciyashop' ) . ''; ?>
				</div>

				<p class="envato-setup-actions step">
					<?php
					if ( empty( $purchase_code ) ) {
						?>
						<input type="submit" class="button button-large button-next button-primary" value="<?php esc_attr_e( 'Activate', 'ciyashop' ); ?>"/>
						<?php
					} else {
						?>
						<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-primary button-large button-next">
							<?php esc_html_e( 'Continue', 'ciyashop' ); ?>
						</a>
						<?php
					}
					?>
				</p>
			</form>
			<?php
			wp_nonce_field( 'envato-setup' );
		}

		/**
		 * Payments Step save
		 */
		public function envato_setup_activate_save() {
			check_admin_referer( 'envato-setup' );

			// redirect to our custom login URL to get a copy of this token.
			$url = $this->get_oauth_login_url( $this->get_step_link( 'updates' ) );

			wp_safe_redirect( esc_url_raw( $url ) );
			exit;
		}
		/**
		 * Get plugins
		 *
		 * @param bool $version .
		 */
		private function _get_plugins( $version = false ) {
			$plugins = array(
				'all'         => array(), // Meaning: all plugins which still have open actions.
				'required'    => array(), // Meaning: all plugins which still have open actions.
				'recommended' => array(), // Meaning: all plugins which still have open actions.
				'install'     => array(),
				'update'      => array(),
				'activate'    => array(),
			);

			if ( isset( $GLOBALS['tgmpa'] ) ) {
				$instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) ); // phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.Found

				foreach ( $instance->plugins as $slug => $plugin ) {
					if ( $this->is_plugin_check_active( $slug ) && false === $instance->does_plugin_have_update( $slug ) ) {
						// No need to display plugins if they are installed, up-to-date and active.
						continue;
					} else {
						$plugins['all'][ $slug ] = $plugin;

						// Filter required and recommended plugins.
						$plugin_required = isset( $plugin['required'] ) && (bool) $plugin['required'] ? true : false;

						if ( $plugin_required ) {
							$plugins['required'][ $slug ] = $plugin;
						} else {
							$plugins['recommended'][ $slug ] = $plugin;
						}

						if ( ! $instance->is_plugin_installed( $slug ) ) {
							$plugins['install'][ $slug ] = $plugin;
						} else {
							if ( false !== $instance->does_plugin_have_update( $slug ) ) {
								$plugins['update'][ $slug ] = $plugin;
							}

							if ( $instance->can_plugin_activate( $slug ) ) {
								$plugins['activate'][ $slug ] = $plugin;
							}
						}
					}
				}
			}

			return $plugins;
		}

		/**
		 * Plugin check active
		 *
		 * @param string $slug .
		 */
		public function is_plugin_check_active( $slug ) {
			$instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );

			return ( ( isset( $instance->plugins[ $slug ] ) && ! empty( $instance->plugins[ $slug ]['is_callable'] ) && is_callable( $instance->plugins[ $slug ]['is_callable'] ) ) || ( isset( $instance->plugins[ $slug ] ) && ciyashop_check_plugin_active( $instance->plugins[ $slug ]['file_path'] ) ) );
		}


		/**
		 * Page setup
		 */
		public function envato_setup_default_plugins() {

			tgmpa_load_bulk_installer();

			// Add option to bypass templates loading by Slider Revolution.
			add_option( 'rs-templates', array(), '', false );
			add_option( 'rs-templates-new', false, '', false );
			update_option( 'revslider-templates-check', time() );

			// install plugins with TGM.
			if ( ! class_exists( 'TGM_Plugin_Activation' ) || ! isset( $GLOBALS['tgmpa'] ) ) {
				wp_die( 'Failed to find TGM' );
			}
			$url     = wp_nonce_url( add_query_arg( array( 'plugins' => 'go' ) ), 'envato-setup' );
			$plugins = $this->_get_plugins();

			// copied from TGM.
			$method = ''; // Leave blank so WP_Filesystem can populate it as necessary.
			$fields = array_keys( $_POST ); // Extra fields to pass to WP_Filesystem.
			$creds  = request_filesystem_credentials( esc_url_raw( $url ), $method, false, false, $fields );

			if ( false === $creds ) { // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found
				return true; // Stop the normal page form from displaying, credential request form will be shown.
			}

			// Now we have some credentials, setup WP_Filesystem.
			if ( ! WP_Filesystem( $creds ) ) {
				// Our credentials were no good, ask the user for them again.
				request_filesystem_credentials( esc_url_raw( $url ), $method, true, false, $fields );

				return true;
			}

			$version_import = false;

			if ( isset( $_GET['version'] ) && isset( $this->sample_datas[ $_GET['version'] ] ) ) {
				$version_import = sanitize_text_field( wp_unslash( $_GET['version'] ) );
			}

			/* If we arrive here, we have the filesystem */
			?>
			<h1><?php esc_html_e( 'Default Plugins', 'ciyashop' ); ?></h1>
			<form method="post" class="plugins-form" data-version="<?php echo esc_attr( $version_import ); ?>">

				<?php
				$plugins = $this->_get_plugins( $version_import );

				$required = array_filter(
					$plugins['all'],
					function( $el ) {
						return $el['required'];
					}
				);

				$version_plugins = ( ! empty( $this->sample_datas[ $version_import ]['plugins'] ) ) ? $this->sample_datas[ $version_import ]['plugins'] : array();

				$for_version = array_filter(
					$plugins['all'],
					function( $el ) use ( $version_plugins ) {
						return in_array( $el['slug'], array_merge( $version_plugins ), true );
					}
				);

				if ( count( $plugins['all'] ) && count( $plugins['required'] ) > 0 ) {
					?>
					<p><?php esc_html_e( 'The following plugins can be installed for some supplemented features to your website:', 'ciyashop' ); ?></p>
					<ul class="envato-wizard-plugins">
						<?php
						$required_core = array();
						if ( ! empty( $required ) ) {
							if ( isset( $required['pgs-core'] ) ) {
								$required_core['pgs-core'] = $required['pgs-core'];
								unset( $required['pgs-core'] );
							}
							?>
							<li class="plugins-title"><?php esc_html_e( 'Required', 'ciyashop' ); ?></li>
							<?php
							if ( ! empty( $required_core ) ) {
								$this->_list_plugins( $required_core, $plugins, false, 'required' );
							}
							$this->_list_plugins( $required, $plugins, false, 'required' );
						}
						if ( ! empty( $for_version ) ) {
							?>
							<li class="plugins-title"><?php esc_html_e( 'Needed for this version', 'ciyashop' ); ?></li>
							<?php
							$this->_list_plugins( $for_version, $plugins, true, 'for_version' );
						}
						?>
					</ul>
					<?php
				} else {
					if ( 0 === (int) count( $plugins['all'] ) && count( ciyashop_tgmpa_plugin_list() ) > 0 && ! ciyashop_is_activated() ) {
						?>
						<div class="plugin-activation-notice">
							<p><strong><?php esc_html_e( 'Please acivate theme using Purchase Code to display plugins list.', 'ciyashop' ); ?></strong></p>
						</div>
						<?php
					} else {
						?>
						<p><strong><?php esc_html_e( 'Good news! All plugins are already installed and up to date. Please continue.', 'ciyashop' ); ?></strong></p>
						<?php
					}
				}
				?>

				<p><?php esc_html_e( 'Please, note that every external plugin can affect your website loading speed. You can add and remove plugins later on from within WordPress.', 'ciyashop' ); ?></p>

				<p class="envato-setup-actions step">
					<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-primary button-next button-active" data-callback="install_plugins"><?php esc_html_e( 'Continue', 'ciyashop' ); ?></a>
					<?php wp_nonce_field( 'envato-setup' ); ?>
				</p>
			</form>
			<?php
		}
		/**
		 * List plugins
		 *
		 * @param string $plugins .
		 * @param string $all .
		 * @param string $checked .
		 * @param string $plugin_type .
		 */
		private function _list_plugins( $plugins, $all, $checked = false, $plugin_type = 'recommended' ) {
			foreach ( $plugins as $slug => $plugin ) {
				$this->_plugin_list_item( $slug, $plugin, $all, $plugin_type, $checked );
			}
		}
		/**
		 * Plugin list item
		 *
		 * @param string $slug .
		 * @param string $plugin .
		 * @param string $plugins .
		 * @param string $checked .
		 * @param string $plugin_type .
		 */
		private function _plugin_list_item( $slug, $plugin, $plugins, $plugin_type, $checked = false ) {
			$message_strings = array(
				'Activate'                       => esc_html__( 'Activate', 'ciyashop' ),
				'Activation required'            => esc_html__( 'Activation required', 'ciyashop' ),
				'Install'                        => esc_html__( 'Install', 'ciyashop' ),
				'Installation required'          => esc_html__( 'Installation required', 'ciyashop' ),
				'Update and Activate'            => esc_html__( 'Update and Activate', 'ciyashop' ),
				'Update and Activation required' => esc_html__( 'Update and Activation required', 'ciyashop' ),
				'Update required'                => esc_html__( 'Update required', 'ciyashop' ),
				'Update'                         => esc_html__( 'Update', 'ciyashop' ),
			);
			?>
			<li data-slug="<?php echo esc_attr( $slug ); ?>" class="plugin-to-install">
				<label for="plugin-import[<?php echo esc_attr( $slug ); ?>]">
					<?php
					$plugin_check_class = 'pgs-' . $slug;
					echo sprintf(
						'<input type="checkbox" class="%s" name="%s" id="%s"%s>',
						"$plugin_check_class",
						"plugin-import[$slug]",
						"plugin-import[$slug]",
						checked( ( ( isset( $plugin['checked_in_wizard'] ) && ! empty( $plugin['checked_in_wizard'] ) ) || $checked ), true, false )
					);// phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE

					$plugin_details = ( ( isset( $plugin['details_url'] ) && ! empty( $plugin['details_url'] ) ) ? sprintf( ' (<a href="%s" target="_blank" rel="noopener">%s</a>)', esc_url( $plugin['details_url'] ), esc_html__( 'View details', 'ciyashop' ) ) : '' ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
					echo sprintf(
						'<span class="plugin-title">%s</span>%s',
						esc_html( trim( $plugin['name'] ) ),
						$plugin_details
					); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
					?>
					<span class="status"></span>
					<span class="plugin-action">
						<?php
						$keys = array();
						if ( 'required' === (string) $plugin_type ) {
							if ( isset( $plugins['install'][ $slug ] ) ) {
								$keys[] = 'Installation';
							}
							if ( isset( $plugins['update'][ $slug ] ) ) {
								$keys[] = 'Update';
							}
							if ( isset( $plugins['activate'][ $slug ] ) ) {
								$keys[] = 'Activation';
							}
							echo esc_html( $message_strings[ trim( implode( ' and ', $keys ) . ' required' ) ] );
						} else {
							if ( isset( $plugins['install'][ $slug ] ) ) {
								$keys[] = 'Install';
							}
							if ( isset( $plugins['update'][ $slug ] ) ) {
								$keys[] = 'Update';
							}
							if ( isset( $plugins['activate'][ $slug ] ) ) {
								$keys[] = 'Activate';
							}
							echo esc_html( $message_strings[ trim( implode( ' and ', $keys ) ) ] );
						}
						?>
					</span>
					<div class="spinner"></div>
				</label>
			</li>
			<?php
		}
		/**
		 * Ajax plugins
		 */
		public function ajax_plugins() {
			if ( ! check_ajax_referer( 'envato_setup_nonce', 'wpnonce' ) || empty( $_POST['slug'] ) ) {
				wp_send_json_error(
					array(
						'error'   => 1,
						'message' => esc_html__(
							'No Slug Found',
							'ciyashop'
						),
					)
				);
			}
			$json = array();

			// send back some json we use to hit up TGM.
			$plugins = $this->_get_plugins();

			// what are we doing with this plugin?.
			foreach ( $plugins['activate'] as $slug => $plugin ) {
				if ( (string) $_POST['slug'] === (string) $slug ) {
					$json = array(
						'url'           => admin_url( $this->tgmpa_url ),
						'plugin'        => array( $slug ),
						'tgmpa-page'    => $this->tgmpa_menu_slug,
						'plugin_status' => 'all',
						'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
						'action'        => 'tgmpa-bulk-activate',
						'action2'       => - 1,
						'message'       => esc_html__( 'Activating Plugin', 'ciyashop' ),
					);
					break;
				}
			}
			foreach ( $plugins['update'] as $slug => $plugin ) {
				if ( (string) $_POST['slug'] === (string) $slug ) {
					$json = array(
						'url'           => admin_url( $this->tgmpa_url ),
						'plugin'        => array( $slug ),
						'tgmpa-page'    => $this->tgmpa_menu_slug,
						'plugin_status' => 'all',
						'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
						'action'        => 'tgmpa-bulk-update',
						'action2'       => - 1,
						'message'       => esc_html__( 'Updating Plugin', 'ciyashop' ),
					);
					break;
				}
			}
			foreach ( $plugins['install'] as $slug => $plugin ) {
				if ( (string) $_POST['slug'] === (string) $slug ) {
					$json = array(
						'url'           => admin_url( $this->tgmpa_url ),
						'plugin'        => array( $slug ),
						'tgmpa-page'    => $this->tgmpa_menu_slug,
						'plugin_status' => 'all',
						'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
						'action'        => 'tgmpa-bulk-install',
						'action2'       => - 1,
						'message'       => esc_html__( 'Installing Plugin', 'ciyashop' ),
					);
					break;
				}
			}

			if ( $json ) {
				$json['hash'] = md5( serialize( $json ) ); // used for checking if duplicates happen, move to next plugin.
				wp_send_json( $json );
			} else {
				wp_send_json(
					array(
						'done'    => 1,
						'message' => esc_html__(
							'Success',
							'ciyashop'
						),
					)
				);
			}
			exit;

		}


		/**
		 * Page setup
		 */
		public function envato_setup_default_content() {

			$plugins = $this->_get_plugins();

			$required = array_filter(
				$plugins['all'],
				function( $el ) {
					return $el['required'];
				}
			);

			$required_names = array();
			foreach ( $required as $require ) {
				if ( isset( $require['name'] ) ) {
					$required_names[] = $require['name'];
				}
			}

			$sample_datas_imported = array();

			$sample_datas = $this->sample_datas;
			?>
			<h1><?php esc_html_e( 'Default Content', 'ciyashop' ); ?></h1>
			<form method="post">
				<p><?php esc_html_e( 'Select the most suitable option from the below mentioned list of variants to complete the default content for your website. Also, choose the appropriate pages to be imported from the list. The WordPress dashboard handles this content once you import them.', 'ciyashop' ); ?></p>
				<?php
				if ( ! empty( $required_names ) ) {
					?>
					<div class="content-missing-plugin-notice-wrapper">
						<div class="content-missing-plugin-notice"><?php esc_html_e( 'One, or more, required plugins (listed below) not installed or activated. Some features may not work correctly, i.e., Sample Data import.', 'ciyashop' ); ?></div>
						<ul class="content-missing-plugins">
							<?php
							array_walk(
								$required_names,
								function( $item, $key ) {
									echo "<li class='content-missing-plugin'>$item</li>"; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
								}
							);
							?>
						</ul>
					</div>
					<?php
				}

				if ( 0 === (string) count( $plugins['all'] ) && count( ciyashop_tgmpa_plugin_list() ) > 0 && ! ciyashop_is_activated() ) {
					?>
					<div class="plugin-activation-notice">
						<strong><?php esc_html_e( 'Please acivate theme using Purchase Code to display sample data list.', 'ciyashop' ); ?></strong>
					</div>
					<?php
				}
				if ( ! ciyashop_is_activated() ) {
					$sample_datas = array();
				}

				$sample_data_first = 'default';
				if ( $sample_datas ) {
					$sample_data_first = $this->cs_array_key_first( $sample_datas );
				}
				?>
				<div class="sample-contents-wrapper clearfix">
					<div class="sample-contents">
						<?php
						$i                      = 0;
						$sample_data_image_path = get_parent_theme_file_path( 'images/sample_data' );
						$sample_data_image_url  = get_parent_theme_file_uri( 'images/sample_data' );

						foreach ( $sample_datas as $sample_data_k => $sample_data ) {
							$i++;
							$sample_data_classes   = array( 'sample-content' );
							$sample_data_classes[] = 'sample-content-' . $sample_data_k;

							if ( 1 === (int) $i ) {
								$sample_data_classes[] = 'sample-content-active';
							}

							$preview_img_path = trailingslashit( $sample_data_image_path ) . str_replace( '-elementor', '', $sample_data['id'] ) . '.jpg';
							$preview_img_url  = trailingslashit( $sample_data_image_url ) . str_replace( '-elementor', '', $sample_data['id'] ) . '.jpg';

							$sample_data_classes = implode( ' ', array_filter( array_unique( $sample_data_classes ) ) );
							?>
							<div class="<?php echo esc_attr( $sample_data_classes ); ?>" data-version="<?php echo esc_attr( $sample_data_k ); ?>">
								<div class="sample-content-view">
									<?php
									if ( file_exists( $preview_img_path ) ) {
										?>
										<div class="sample-content-thumb">
											<img src="<?php echo esc_url( $preview_img_url ); ?>" alt="<?php echo esc_attr( $sample_data['name'] ); ?>"/>
										</div>
										<?php
									} else {
										?>
										<div class="sample-content-thumb sample-content-thumb-blank">
											<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAyAAAAJYCAQAAAAwf0r7AAAGrUlEQVR42u3VIQEAAAzDsM+/6dPh4URCSXMAMIgEABgIAAYCgIEAYCAAYCAAGAgABgKAgQBgIABgIAAYCAAGAoCBAGAgAGAgABgIAAYCgIEAYCAAYCAAGAgABgKAgQBgIABgIAAYCAAGAoCBAGAgAGAgABgIAAYCgIEAYCAAYCAAGAgABgKAgQCAgQBgIAAYCAAGAoCBAICBAGAgABgIAAYCgIEAgIEAYCAAGAgABgKAgQCAgQBgIAAYCAAGAoCBAICBAGAgABgIAAYCgIEAgIEAYCAAGAgABgKAgQCAgQBgIAAYCAAGAoCBAICBAGAgABgIAAYCAAYCgIEAYCAAGAgABgIABgKAgQBgIAAYCAAGAgAGAoCBAGAgABgIAAYCAAYCgIEAYCAAGAgABgIABgKAgQBgIAAYCAAGAgAGAoCBAGAgABgIAAYCAAYCgIEAYCAAGAgAGAgABgKAgQBgIAAYCAAYCAAGAoCBAGAgABgIABgIAAYCgIEAYCAAGAgAGAgABgKAgQBgIAAYCAAYCAAGAoCBAGAgABgIABgIAAYCgIEAYCAAGAgAGAgABgKAgQBgIAAYCAAYCAAGAoCBAGAgAGAgABgIAAYCgIEAYCAAYCAAGAgABgKAgQBgIABgIAAYCAAGAoCBAGAgAGAgABgIAAYCgIEAYCAAYCAAGAgABgKAgQBgIABgIAAYCAAGAoCBAGAgAGAgABgIAAYCgIEAgIEAYCAAGAgABgKAgQCAgQBgIAAYCAAGAoCBAICBAGAgABgIAAYCgIEAgIEAYCAAGAgABgKAgQCAgQBgIAAYCAAGAoCBAICBAGAgABgIAAYCgIEAgIEAYCAAGAgABgKAgQCAgQBgIAAYCAAGAgAGAoCBAGAgABgIAAYCAAYCgIEAYCAAGAgABgIABgKAgQBgIAAYCAAGAgAGAoCBAGAgABgIAAYCAAYCgIEAYCAAGAgABgIABgKAgQBgIAAYCAAGAgAGAoCBAGAgABgIAAYiAQAGAoCBAGAgABgIABgIAAYCgIEAYCAAGAgAGAgABgKAgQBgIAAYCAAYCAAGAoCBAGAgABgIABgIAAYCgIEAYCAAGAgAGAgABgKAgQBgIAAYCAAYCAAGAoCBAGAgABgIABgIAAYCgIEAYCAAYCAAGAgABgKAgQBgIABgIAAYCAAGAoCBAGAgAGAgABgIAAYCgIEAYCAAYCAAGAgABgKAgQBgIABgIAAYCAAGAoCBAGAgAGAgABgIAAYCgIEAYCAAYCAAGAgABgKAgQBgIABgIAAYCAAGAoCBAICBAGAgABgIAAYCgIEAgIEAYCAAGAgABgKAgQCAgQBgIAAYCAAGAoCBAICBAGAgABgIAAYCgIEAgIEAYCAAGAgABgKAgQCAgQBgIAAYCAAGAoCBAICBAGAgABgIAAYCAAYCgIEAYCAAGAgABgIABgKAgQBgIAAYCAAGAgAGAoCBAGAgABgIAAYCAAYCgIEAYCAAGAgABgIABgKAgQBgIAAYCAAGAgAGAoCBAGAgABgIAAYCAAYCgIEAYCAAGAgABgIABgKAgQBgIAAYCAAYCAAGAoCBAGAgABgIABgIAAYCgIEAYCAAGAgAGAgABgKAgQBgIAAYCAAYCAAGAoCBAGAgABgIABgIAAYCgIEAYCAAGAgAGAgABgKAgQBgIAAYCAAYCAAGAoCBAGAgAGAgABgIAAYCgIEAYCAAYCAAGAgABgKAgQBgIABgIAAYCAAGAoCBAGAgAGAgABgIAAYCgIEAYCAAYCAAGAgABgKAgQBgIABgIAAYCAAGAoCBAGAgAGAgABgIAAYCgIEAYCAAYCAAGAgABgKAgQCAgQBgIAAYCAAGAoCBAICBAGAgABgIAAYCgIEAgIEAYCAAGAgABgKAgQCAgQBgIAAYCAAGAoCBAICBAGAgABgIAAYCgIEAgIEAYCAAGAgABgKAgQCAgQBgIAAYCAAGAoCBSACAgQBgIAAYCAAGAgAGAoCBAGAgABgIAAYCAAYCgIEAYCAAGAgABgIABgKAgQBgIAAYCAAGAgAGAoCBAGAgABgIAAYCAAYCgIEAYCAAGAgABgIABgKAgQBgIAAYCAAGAgAGAoCBAGAgABgIABgIAAYCgIEAYCAAGAgAGAgABgKAgQBgIAAYCAAYCAAGAoCBAGAgABgIABgIAAYCgIEAYCAAGAgAGAgABgKAgQBgIAAYCAAYCAAGAoCBAGAgABgIABgIAAYCgIEAYCAAGAgAGAgABgKAgQBgIABgIAAYCAAGAoCBAGAgANAeRqACWRdZgjsAAAAASUVORK5CYII=" alt="<?php echo esc_attr( $sample_data['name'] ); ?>"/>
										</div>
										<?php
									}
									?>
									<span class="sample-content-thumb-title"><?php echo esc_html( $sample_data['name'] ); ?></span>
								</div>
								<div class="sample-content-title-wrap">
									<h2 class="sample-content-title"><?php echo esc_html( $sample_data['name'] ); ?></h2>
									<?php
									if ( isset( $sample_data['preview_url'] ) && ! empty( $sample_data['preview_url'] ) ) {
										?>
										<a href="<?php echo esc_url( $sample_data['preview_url'] ); ?>" target="_blank" rel="noopener" class="live-preview-button button button-primary button-small">
											<?php esc_html_e( 'Live Preview', 'ciyashop' ); ?>
										</a>
										<?php
									}
									?>
								</div>
							</div>
							<?php
						}
						?>
					</div>
				</div>

				<input type="hidden" name="import-id" id="import-id" value="<?php echo esc_attr( $sample_data_first ); ?>">
				<?php wp_nonce_field( 'sample_import_security_check', 'sample_import_nonce' ); ?>

				<p class="envato-setup-actions step">
					<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-primary button-next button-active" data-callback="install_content"><?php esc_html_e( 'Continue', 'ciyashop' ); ?></a>
					<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large"><?php esc_html_e( 'Skip this step', 'ciyashop' ); ?></a>
					<?php wp_nonce_field( 'envato-setup' ); ?>
				</p>
			</form>
			<?php
		}
		/**
		 * Imported term id
		 *
		 * @param string $original_term_id .
		 * @param string $new_term_id .
		 */
		private function _imported_term_id( $original_term_id, $new_term_id = false ) {
			$terms = get_transient( 'importtermids' );
			if ( ! is_array( $terms ) ) {
				$terms = array();
			}
			if ( $new_term_id ) {
				if ( ! isset( $terms[ $original_term_id ] ) ) {
					$this->log( 'Insert old TERM ID ' . $original_term_id . ' as new TERM ID: ' . $new_term_id );
				} elseif ( (string) $terms[ $original_term_id ] !== (string) $new_term_id ) {
					$this->error( 'Replacement OLD TERM ID ' . $original_term_id . ' overwritten by new TERM ID: ' . $new_term_id );
				}
				$terms[ $original_term_id ] = $new_term_id;
				set_transient( 'importtermids', $terms, 60 * 60 * 24 );
			} elseif ( $original_term_id && isset( $terms[ $original_term_id ] ) ) {
				return $terms[ $original_term_id ];
			}

			return false;
		}

		/**
		 * VC post
		 *
		 * @param string $post_id .
		 */
		public function vc_post( $post_id = false ) {

			$vc_post_ids = get_transient( 'import_vc_posts' );
			if ( ! is_array( $vc_post_ids ) ) {
				$vc_post_ids = array();
			}
			if ( $post_id ) {
				$vc_post_ids[ $post_id ] = $post_id;
				set_transient( 'import_vc_posts', $vc_post_ids, 60 * 60 * 24 );
			} else {

				$this->log( 'Processing vc pages 2: ' );

				return;
				if ( class_exists( 'Vc_Manager' ) && class_exists( 'Vc_Post_Admin' ) ) {
					$this->log( $vc_post_ids );
					$vc_manager = Vc_Manager::getInstance();
					$vc_base    = $vc_manager->vc();
					$post_admin = new Vc_Post_Admin();
					foreach ( $vc_post_ids as $vc_post_id ) {
						$this->log( 'Save ' . $vc_post_id );
						$vc_base->buildShortcodesCustomCss( $vc_post_id );
						$post_admin->save( $vc_post_id );
						$post_admin->setSettings( $vc_post_id );
						// twice? bug?.
						$vc_base->buildShortcodesCustomCss( $vc_post_id );
						$post_admin->save( $vc_post_id );
						$post_admin->setSettings( $vc_post_id );
					}
				}
			}

		}
		/**
		 * Imported post id
		 *
		 * @param bool $original_id .
		 * @param bool $new_id .
		 */
		private function _imported_post_id( $original_id = false, $new_id = false ) {
			if ( is_array( $original_id ) || is_object( $original_id ) ) {
				return false;
			}
			$post_ids = get_transient( 'importpostids' );
			if ( ! is_array( $post_ids ) ) {
				$post_ids = array();
			}
			if ( $new_id ) {
				if ( ! isset( $post_ids[ $original_id ] ) ) {
					$this->log( 'Insert old ID ' . $original_id . ' as new ID: ' . $new_id );
				} elseif ( (string) $post_ids[ $original_id ] !== (string) $new_id ) {
					$this->error( 'Replacement OLD ID ' . $original_id . ' overwritten by new ID: ' . $new_id );
				}
				$post_ids[ $original_id ] = $new_id;
				set_transient( 'importpostids', $post_ids, 60 * 60 * 24 );
			} elseif ( $original_id && isset( $post_ids[ $original_id ] ) ) {
				return $post_ids[ $original_id ];
			} elseif ( false === $original_id ) {
				return $post_ids;
			}

			return false;
		}
		/**
		 * Post orphans
		 *
		 * @param bool $original_id .
		 * @param bool $missing_parent_id .
		 */
		private function _post_orphans( $original_id = false, $missing_parent_id = false ) {
			$post_ids = get_transient( 'postorphans' );
			if ( ! is_array( $post_ids ) ) {
				$post_ids = array();
			}
			if ( $missing_parent_id ) {
				$post_ids[ $original_id ] = $missing_parent_id;
				set_transient( 'postorphans', $post_ids, 60 * 60 * 24 );
			} elseif ( $original_id && isset( $post_ids[ $original_id ] ) ) {
				return $post_ids[ $original_id ];
			} elseif ( false === $original_id ) {
				return $post_ids;
			}

			return false;
		}
		/**
		 * Cleanup imported ids
		 */
		private function _cleanup_imported_ids() {
			// Loop over all attachments and assign the correct post ids to those attachments.
		}
		/**
		 * Variable
		 *
		 * @var $delay_posts
		 */
		private $delay_posts = array();
		/**
		 * Post process
		 *
		 * @param string $post_type .
		 * @param string $post_data .
		 */
		private function _delay_post_process( $post_type, $post_data ) {
			if ( ! isset( $this->delay_posts[ $post_type ] ) ) {
				$this->delay_posts[ $post_type ] = array();
			}
			$this->delay_posts[ $post_type ][ $post_data['post_id'] ] = $post_data;

		}


		/**
		 * Return the difference in length between two strings
		 *
		 * @param string $a .
		 * @param string $b .
		 */
		public function cmpr_strlen( $a, $b ) {
			return strlen( $b ) - strlen( $a );
		}
		/**
		 * Gallery shortcode content
		 *
		 * @param string $content .
		 */
		private function _parse_gallery_shortcode_content( $content ) {
			// we have to format the post content. rewriting images and gallery stuff.
			$replace      = $this->_imported_post_id();
			$urls_replace = array();
			foreach ( $replace as $key => $val ) {
				if ( $key && $val && ! is_numeric( $key ) && ! is_numeric( $val ) ) {
					$urls_replace[ $key ] = $val;
				}
			}
			if ( $urls_replace ) {
				uksort( $urls_replace, array( &$this, 'cmpr_strlen' ) );
				foreach ( $urls_replace as $from_url => $to_url ) {
					$content = str_replace( $from_url, $to_url, $content );
				}
			}
			if ( preg_match_all( '#\[gallery[^\]]*\]#', $content, $matches ) ) {
				foreach ( $matches[0] as $match_id => $string ) {
					if ( preg_match( '#ids="([^"]+)"#', $string, $ids_matches ) ) {
						$ids = explode( ',', $ids_matches[1] );
						foreach ( $ids as $key => $val ) {
							$new_id = $val ? $this->_imported_post_id( $val ) : false;
							if ( ! $new_id ) {
								unset( $ids[ $key ] );
							} else {
								$ids[ $key ] = $new_id;
							}
						}
						$new_ids = implode( ',', $ids );
						$content = str_replace( $ids_matches[0], 'ids="' . $new_ids . '"', $content );
					}
				}
			}
			// contact form 7 id fixes.
			if ( preg_match_all( '#\[contact-form-7[^\]]*\]#', $content, $matches ) ) {
				foreach ( $matches[0] as $match_id => $string ) {
					if ( preg_match( '#id="(\d+)"#', $string, $id_match ) ) {
						$new_id = $this->_imported_post_id( $id_match[1] );
						if ( $new_id ) {
							$content = str_replace( $id_match[0], 'id="' . $new_id . '"', $content );
						} else {
							// no imported ID found. remove this entry.
							$content = str_replace( $matches[0], '(insert contact form here)', $content );
						}
					}
				}
			}
			return $content;
		}
		/**
		 * Elementor id import
		 *
		 * @param string $item .
		 * @param string $key .
		 */
		private function _elementor_id_import( &$item, $key ) {
			if ( 'id' === (string) $key && ! empty( $item ) && is_numeric( $item ) ) {
				// check if this has been imported before.
				$new_meta_val = $this->_imported_post_id( $item );
				if ( $new_meta_val ) {
					$item = $new_meta_val;
				}
			}
			if ( 'page' === (string) $key && ! empty( $item ) ) {

				if ( false !== strpos( $item, 'p.' ) ) {
					$new_id = str_replace( 'p.', '', $item );
					// check if this has been imported before.
					$new_meta_val = $this->_imported_post_id( $new_id );
					if ( $new_meta_val ) {
						$item = 'p.' . $new_meta_val;
					}
				} elseif ( is_numeric( $item ) ) {
					// check if this has been imported before.
					$new_meta_val = $this->_imported_post_id( $item );
					if ( $new_meta_val ) {
						$item = $new_meta_val;
					}
				}
			}
			if ( 'post_id' === (string) $key && ! empty( $item ) && is_numeric( $item ) ) {
				// check if this has been imported before.
				$new_meta_val = $this->_imported_post_id( $item );
				if ( $new_meta_val ) {
					$item = $new_meta_val;
				}
			}
			if ( 'url' === (string) $key && ! empty( $item ) && strstr( $item, 'ocalhost' ) ) {
				// check if this has been imported before.
				$new_meta_val = $this->_imported_post_id( $item );
				if ( $new_meta_val ) {
					$item = $new_meta_val;
				}
			}
			if ( ( 'shortcode' === (string) $key || 'editor' === (string) $key ) && ! empty( $item ) ) {
				// we have to fix the [contact-form-7 id=133] shortcode issue.
				$item = $this->_parse_gallery_shortcode_content( $item );

			}
		}
		/**
		 * Get json
		 *
		 * @param string $file .
		 */
		private function _get_json( $file ) {
			if ( is_file( __DIR__ . '/content/' . basename( $file ) ) ) {
				WP_Filesystem();
				global $wp_filesystem;
				$file_name = __DIR__ . '/content/' . basename( $file );
				if ( file_exists( $file_name ) ) {
					return json_decode( $wp_filesystem->get_contents( $file_name ), true );
				}
			}

			return array();
		}
		/**
		 * Get sql
		 *
		 * @param string $file .
		 */
		private function _get_sql( $file ) {
			if ( is_file( __DIR__ . '/content/' . basename( $file ) ) ) {
				WP_Filesystem();
				global $wp_filesystem;
				$file_name = __DIR__ . '/content/' . basename( $file );
				if ( file_exists( $file_name ) ) {
					return $wp_filesystem->get_contents( $file_name );
				}
			}

			return false;
		}
		/**
		 * Variable
		 *
		 * @var $logs .
		 */
		public $logs = array();
		/**
		 * Log
		 *
		 * @param string $message .
		 */
		public function log( $message ) {
			$this->logs[] = $message;
		}
		/**
		 * Variable
		 *
		 * @var $errors
		 */
		public $errors = array();
		/**
		 * Variable
		 *
		 * @param string $message .
		 */
		public function error( $message ) {
			$this->logs[] = 'ERROR!!!! ' . $message;
		}
		/**
		 * Setup customize
		 */
		public function envato_setup_customize() {
			?>
			<h1>
			<?php
			printf(
				/* translators: $s: Link to Home */
				esc_html__( 'Setup %s Child Theme (Optional)', 'ciyashop' ),
				$this->theme_data->get( 'Name' )
			); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			?>
				</h1>

			<p><?php esc_html_e( 'If you want any alterations or changes done on the source code, please use a child theme. Your source code will remain unaffected and meanwhile, your parent theme will get all the updates. The changes in the HTML/CSS/PHP code must be refrained. Use the form below to create and activate the Child Theme.', 'ciyashop' ); ?></p>
			<?php
			if ( ! isset( $_REQUEST['theme_name'] ) ) {
				?>
				<p class="lead"><?php esc_html_e( "If you don't want to use the child theme, please click on 'Skip this step'", 'ciyashop' ); ?></p>
				<?php
			}

			// Create Child Theme.
			if ( isset( $_REQUEST['theme_name'] ) && current_user_can( 'manage_options' ) ) {
				$this->_make_child_theme( esc_html( sanitize_text_field( wp_unslash( $_REQUEST['theme_name'] ) ) ) );
			}

			$theme = $this->theme_data->get( 'Name' ) . ' Child';
			if ( ! isset( $_REQUEST['theme_name'] ) ) {
				?>
				<form action="<?php $_PHP_SELF; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase ?>" method="POST">
					<div class="child-theme-input">
					<label for="theme_name"><?php esc_html_e( 'Child Theme Title', 'ciyashop' ); ?></label>
					<input id="theme_name" type="text" name="theme_name" value="<?php echo esc_attr( $theme ); ?>" />
					</div>
					<p class="envato-setup-actions step">
						<button type="submit" type="submit" class="button button-large button-primary button-active">
							<?php esc_html_e( 'Create and Use Child Theme', 'ciyashop' ); ?>
						</button>
						<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large">
							<?php esc_html_e( 'Skip this step', 'ciyashop' ); ?>
						</a>
					</p>
				</form>
				<?php
			} else {
				?>
				<p class="envato-setup-actions step">
					<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-primary button-active"><?php esc_html_e( 'Continue', 'ciyashop' ); ?></a>
				</p>
				<?php
			}
		}
		/**
		 * Child theme
		 *
		 * @param string $child_theme_title .
		 */
		private function _make_child_theme( $child_theme_title ) {
			global $wp_filesystem;

			if ( ! $wp_filesystem ) {
				require_once ABSPATH . 'wp-admin/includes/file.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				WP_Filesystem();
			}

			$themes_dir            = get_theme_root();
			$parent_theme_template = 'ciyashop';
			$child_theme_slug      = sanitize_title( $child_theme_title ); // Sanitize Child Theme Title.
			$child_theme_name      = str_replace( '-', '_', $child_theme_slug );
			$child_theme_path      = $themes_dir . '/' . $child_theme_slug;
			$child_template_path   = get_parent_theme_file_path( 'includes/theme-setup-wizard/child-theme' );

			$child_theme_data = array(
				'title' => $child_theme_title,
				'name'  => $child_theme_name,
				'slug'  => $child_theme_slug,
			);

			$force_child_creation = true;

			$child_theme_template_files = array(
				'style.css'      => 'template',
				'functions.php'  => 'template',
				'screenshot.png' => 'copy_only',
			);

			// Validate theme name.
			if ( ! file_exists( $child_theme_path ) || ( file_exists( $child_theme_path ) && $force_child_creation ) ) {

				// Rename folder if child-folder exists.
				if ( file_exists( $child_theme_path ) && $force_child_creation ) {
					$child_theme_bak_path = $child_theme_path . '_' . gmdate( 'Y-m-d_H-i-s' );
					rename( $child_theme_path, $child_theme_bak_path );
				}

				// Create child theme directory.
				if ( ! is_dir( $child_theme_path ) ) {
					wp_mkdir_p( $child_theme_path );
				}

				// Process Child Theme template files.
				foreach ( $child_theme_template_files as $child_theme_template_file => $child_theme_template_file_type ) {

					$child_theme_template_file_source = trailingslashit( $child_template_path ) . $child_theme_template_file;
					$child_theme_template_file_target = trailingslashit( $child_theme_path ) . $child_theme_template_file;

					if ( 'template' === (string) $child_theme_template_file_type ) {
						$template_content = $this->template_content( $child_theme_template_file_source, $child_theme_data );
						$wp_filesystem->put_contents(
							$child_theme_template_file_target,
							$template_content,
							FS_CHMOD_FILE
						);
					} else {
						copy( $child_theme_template_file_source, $child_theme_template_file_target );
					}
				}

				// Make child theme an allowed theme (network enable theme).
				$allowed_themes                      = get_site_option( 'allowedthemes' );
				$allowed_themes[ $child_theme_slug ] = true;
				update_site_option( 'allowedthemes', $allowed_themes );
			}

			// Switch to theme.
			if ( $parent_theme_template !== $child_theme_slug ) {
				?>
				<p class="lead success">
				<?php
				printf(
					/* translators: $s: Link to Home */
					esc_html__( 'Child Theme %1$s created and activated! Folder is located in wp-content/themes/%2$s', 'ciyashop' ),
					'<strong>' . $child_theme_title . '</strong>',
					'<strong>' . $child_theme_slug . '</strong>'
				); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
				?>
					</h1>
				</p>
				<?php
				update_option( $this->theme_name . '_has_child', $child_theme_slug );
				switch_theme( $child_theme_slug, $child_theme_slug );
			}
		}
		/**
		 * Template content
		 *
		 * @param string $filename .
		 * @param string $data .
		 */
		private function template_content( $filename, $data ) {
			global $wp_filesystem;

			if ( ! $wp_filesystem ) {
				require_once ABSPATH . 'wp-admin/includes/file.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
				WP_Filesystem();
			}

			$content = $wp_filesystem->get_contents( $filename );

			foreach ( $data as $key => $value ) {
				$content = str_replace( '{' . $key . '}', $value, $content );
			}

			return $content;
		}
		/**
		 * Envato setup help support
		 */
		public function envato_setup_help_support() {
			?>
			<h1><?php esc_html_e( 'Help and Support', 'ciyashop' ); ?></h1>
			<p><?php esc_html_e( 'This theme is entitled to assist for 6 months item support from the date of purchase (you can definitely lengthen the time). With respect to this license, the theme can be used on one website. To use on another site, please buy an additional license.', 'ciyashop' ); ?></p>
			<p>
			<?php
			echo sprintf(
				// Translators: %s is the theme support link.
				wp_kses( __( 'You can get the item support from <a href="%s" target="_blank" rel="noopener">Potenza Support Center</a> and that is comprised of:', 'ciyashop' ), ciyashop_allowed_html( array( 'a' ) ) ),
				'https://potezasupport.ticksy.com/'
			);
			?>
			</p>

			<div class="help-support-wrapper">
				<div class="help-support-bullets clearfix">
					<div class="includes">
						<h3><?php esc_html_e( 'Item Support comprises of:', 'ciyashop' ); ?></h3>
						<ul>
							<li><?php esc_html_e( 'In detail explanation of the technical elements of the product', 'ciyashop' ); ?></li>
							<li><?php esc_html_e( 'Help if there is any error or concern.', 'ciyashop' ); ?></li>
							<li><?php esc_html_e( 'Extensive support for 3rd party plugins (bundled).', 'ciyashop' ); ?></li>
						</ul>
					</div>
					<div class="excludes">
						<h3><?php echo wp_kses( __( 'Item Support <strong>DOES NOT</strong> comprise of:', 'ciyashop' ), array( 'strong' => array() ) ); ?></h3>
						<ul>
							<li><?php esc_html_e( 'Customization services', 'ciyashop' ); ?></li>
							<li><?php esc_html_e( 'Installation services', 'ciyashop' ); ?></li>
							<li><?php esc_html_e( 'Assistance for non-bundled 3rd party plugins.', 'ciyashop' ); ?></li>
						</ul>
					</div>
				</div>
			</div>

			<p>
			<?php
			echo sprintf(
				// Translators: %s is the ThemeForest Item Support Policy link.
				wp_kses( __( 'ThemeForest <a href="%s" target="_blank" rel="noopener">Item Support Policy</a> can be used to gather extra details about the item support.', 'ciyashop' ), ciyashop_allowed_html( array( 'a' ) ) ),
				'http://themeforest.net/page/item_support_policy'
			);
			?>
			</p>
			<p class="envato-setup-actions step">
				<a href="<?php echo esc_url( $this->get_next_step_link() ); ?>" class="button button-large button-primary button-active"><?php esc_html_e( 'Continue', 'ciyashop' ); ?></a>
				<?php wp_nonce_field( 'envato-setup' ); ?>
			</p>
			<?php
		}

		/**
		 * Final step
		 */
		public function envato_setup_ready() {

			update_option( 'envato_setup_complete', time() );
			update_option( $this->theme_name . '_setup_wizard_displayed', true );
			?>
			<div class="envato-setup-done dashicons dashicons-admin-site"><div class="envato-setup-done-check dashicons dashicons-yes">&nbsp;</div></div>

			<h1><?php esc_html_e( 'Your Website is Ready!', 'ciyashop' ); ?></h1>

			<p><?php esc_html_e( 'Praises to you, your website is ready now. The required themes have been turned on to enhance the functionality of your site. You can modify the content and make required changes, if important by logging into the WordPress Dashboard.', 'ciyashop' ); ?></p>
			<p>
			<?php
			echo sprintf(
				// Translators: %s is the Themeforest downloads link.
				wp_kses( __( 'Please encourage us by <a href="%s" target="_blank" rel="noopener">dropping 5-star</a>.', 'ciyashop' ), ciyashop_allowed_html( array( 'a' ) ) ),
				'https://themeforest.net/downloads'
			);
			?>
			</p>
			<br>
			<br>
			<div class="envato-setup-final-contents">
				<div class="envato-setup-final-content envato-setup-final-content-first">
					<div class="envato-setup-final-content-inner">
						<div class="envato-setup-final-header"><h2><?php esc_html_e( 'Next Steps', 'ciyashop' ); ?></h2></div>
						<ul>
							<li class="envato-setup-final-step-button"><a class="button button-primary button-large" href="http://themeforest.net/user/potenzaglobalsolutions/follow" target="_blank" rel="noopener"><?php esc_html_e( 'Follow PotenzaGlobalSolutions on ThemeForest', 'ciyashop' ); ?></a></li>
							<li class="envato-setup-final-step-button"><a class="button button-large" href="<?php echo esc_url( home_url() ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'View your new website!', 'ciyashop' ); ?></a></li>
						</ul>
					</div>
				</div>
				<div class="envato-setup-final-content envato-setup-final-content-last">
					<div class="envato-setup-final-content-inner">
						<div class="envato-setup-final-header"><h2><?php esc_html_e( 'More Resources', 'ciyashop' ); ?></h2></div>
						<div class="more-resources">
							<div class="more-resource documentation"><a href="<?php echo esc_url( 'http://docs.potenzaglobalsolutions.com/docs/ciyashop-wp/' ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Read the Theme Documentation', 'ciyashop' ); ?></a></div>
							<div class="more-resource howto"><a href="<?php echo esc_url( 'https://wordpress.org/support/' ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Learn how to use WordPress', 'ciyashop' ); ?></a></div>
							<div class="more-resource rating"><a href="<?php echo esc_url( 'https://themeforest.net/downloads' ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Leave an Item Rating', 'ciyashop' ); ?></a></div>
							<div class="more-resource support"><a href="<?php echo esc_url( 'https://potezasupport.ticksy.com/ ' ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Get Help and Support', 'ciyashop' ); ?></a></div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}



		/**
		 * Array merge recursive distinct
		 *
		 * @param array $array1 .
		 * @param array $array2 .
		 *
		 * @return mixed
		 * @since    1.1.4
		 */
		private function _array_merge_recursive_distinct( $array1, $array2 ) {
			$merged = $array1;
			foreach ( $array2 as $key => &$value ) {
				if ( is_array( $value ) && isset( $merged [ $key ] ) && is_array( $merged [ $key ] ) ) {
					$merged [ $key ] = $this->_array_merge_recursive_distinct( $merged [ $key ], $value );
				} else {
					$merged [ $key ] = $value;
				}
			}

			return $merged;
		}

		/**
		 * Helper function
		 * Take a path and return it clean
		 *
		 * @param string $path .
		 *
		 * @since    1.1.2
		 */
		public static function cleanFilePath( $path ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid
			$path = str_replace( '', '', str_replace( array( '\\', '\\\\', '//' ), '/', $path ) );
			if ( '/' === $path[ strlen( $path ) - 1 ] ) {
				$path = rtrim( $path, '/' );
			}

			return $path;
		}
		/**
		 * Submenu page
		 */
		public function is_submenu_page() {
			return ( empty( $this->parent_slug ) ) ? false : true;
		}

		/**
		 * Returns the first key of array if the array is not empty; null otherwise.
		 */
		public function cs_array_key_first( $arr ) {
			foreach ( $arr as $key => $unused ) {
				return $key;
			}
			return null;
		}

		/**
		 * Stored code
		 */
		public function get_stored_code() {
			$code = false;

			$stored = get_option( 'theme_purchase_code', false );

			if ( $stored ) {
				$code = $stored;
			}

			return $code;
		}
		/**
		 * Domain
		 */
		public function domain() {
			$domain = get_option( 'siteurl' ); // or home.
			$domain = str_replace( 'http://', '', $domain );
			$domain = str_replace( 'https://', '', $domain );
			$domain = str_replace( 'www', '', $domain ); // add the . after the www if you don't want it.
			return rawurlencode( $domain );
		}
		/**
		 * After theme switch
		 */
		public static function after_theme_switch() {
			return ''; // wizard, other.
		}

	}

}// if !class_exists.

add_action( 'after_setup_theme', 'envato_theme_setup_wizard', 10 );
if ( ! function_exists( 'envato_theme_setup_wizard' ) ) :
	/**
	 * Loads the main instance of Envato_Theme_Setup_Wizard to have
	 * ability extend class functionality
	 *
	 * @since 1.1.1
	 */
	function envato_theme_setup_wizard() {
		Envato_Theme_Setup_Wizard::get_instance();
	}
endif;

/**
 * Display admin notice if required plugins are not active.
 */
function ciyashop_theme_setup_wizard_notice() {

	$current_theme = wp_get_theme();
	if ( is_child_theme() ) {
		$current_theme = wp_get_theme( $current_theme->get( 'Template' ) );
	}

	$theme_slug = str_replace( '-', '_', sanitize_title( $current_theme->get( 'Name' ) ) );

	if ( get_option( $theme_slug . '_setup_wizard_displayed', false ) ) {
		return;
	}
	?>
	<div class="notice notice-error">
		<p><strong>
		<?php
		echo sprintf(
			// Translators: %s is the theme name.
			esc_html__( 'Welcome to %s', 'ciyashop' ),
			$current_theme->get( 'Name' )
		);// phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		?>
		</strong></p>
		<p>
		<?php
		echo sprintf(
			// Translators: %s is the theme name.
			esc_html__( 'You\'re almost there. %1$s contains many useful features and functionalities. For this, some settings are to be done, to enable all features and functionalities. And, %2$s Setup Wizard might be of a great help to you for same.', 'ciyashop' ),
			$current_theme->get( 'Name' ),
			$current_theme->get( 'Name' )
		);// phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		?>
		</p>
		<p><a href="<?php echo esc_url( admin_url( 'themes.php?page=' . $theme_slug . '-setup' ) ); ?>" class="button-primary"><?php esc_html_e( 'Run the Setup Wizard', 'ciyashop' ); ?></a> <a class="button-secondary skip" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'ciyashop-setup-hide-notice', '1' ), 'ciyashop_setup_hide_notice_nonce', '_cssetup_notice_nonce' ) ); ?>"><?php esc_html_e( 'Skip setup', 'ciyashop' ); ?></a></p>
	</div>
	<?php
}
add_action( 'admin_notices', 'ciyashop_theme_setup_wizard_notice' );
/**
 * Hide setup wizard notice
 */
function ciyashop_hide_setup_wizard_notice() {

	$current_theme = wp_get_theme();
	if ( is_child_theme() ) {
		$current_theme = wp_get_theme( $current_theme->get( 'Template' ) );
	}

	$theme_slug = str_replace( '-', '_', sanitize_title( $current_theme->get( 'Name' ) ) );

	if ( isset( $_GET['ciyashop-setup-hide-notice'] ) && isset( $_GET['_cssetup_notice_nonce'] ) ) { // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		if ( ! wp_verify_nonce( sanitize_key( $_GET['_cssetup_notice_nonce'] ), 'ciyashop_setup_hide_notice_nonce' ) ) { // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
			wp_die( esc_html__( 'Action failed. Please refresh the page and retry.', 'ciyashop' ) );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You don&#8217;t have permission to do this.', 'ciyashop' ) );
		}

		$hide_notice = sanitize_text_field( wp_unslash( $_GET['ciyashop-setup-hide-notice'] ) );
		if ( ! empty( $hide_notice ) && 1 === (int) $hide_notice ) {
			update_option( $theme_slug . '_setup_wizard_displayed', true );
		}
		$url = remove_query_arg( array( 'ciyashop-setup-hide-notice', '_cssetup_notice_nonce' ) );
		wp_safe_redirect( $url );
		exit;
	}
}
add_action( 'admin_init', 'ciyashop_hide_setup_wizard_notice' );

/**
 * Setup Wizard wp title
 *
 * @param string $title title.
 * @param string $sep seprator.
 * @param string $seplocation spelocation.
 */
function ciyashop_envato_setup_wp_title( $title, $sep, $seplocation ) {
	$title = esc_html__( 'Theme &rsaquo; Setup Wizard', 'ciyashop' );
	return $title;
};

add_filter( 'wp_title', 'ciyashop_envato_setup_wp_title', 10, 3 );
