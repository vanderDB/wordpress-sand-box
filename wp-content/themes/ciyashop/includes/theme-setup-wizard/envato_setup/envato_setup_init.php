<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
// This is the setup wizard init file.
// This file changes for each one of dtbaker's themes
// This is where I extend the default 'Envato_Theme_Setup_Wizard' class and can do things like remove steps from the setup process.

// This particular init file has a custom "Update" step that is triggered on a theme update. If the setup wizard finds some old shortcodes after a theme update then it will go through the content and replace them. Probably remove this from your end product.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


add_filter( 'envato_setup_logo_image', 'dtbwp_envato_setup_logo_image' );
/**
 * Envato setup logo image
 *
 * @param string $old_image_url .
 */
function dtbwp_envato_setup_logo_image( $old_image_url ) {
	return get_parent_theme_file_uri( '/images/logo.png' );
}

if ( ! function_exists( 'envato_theme_setup_wizard' ) ) :
	/**
	 * Envato theme setup wizard
	 */
	function envato_theme_setup_wizard() {

		if ( isset( $_GET['page'] ) && 'ciyashop-setup' === $_GET['page'] ) {
			add_filter( 'woocommerce_enable_setup_wizard', '__return_false' );
		}

		if ( class_exists( 'Envato_Theme_Setup_Wizard' ) ) {
			/**
			 * Envato Theme Setup Wizard
			 */
			class dtbwp_Envato_Theme_Setup_Wizard extends Envato_Theme_Setup_Wizard {// phpcs:ignore PEAR.NamingConventions.ValidClassName.StartWithCapital

				/**
				 * Holds the current instance of the theme manager
				 *
				 * @since 1.1.3
				 * @var Envato_Theme_Setup_Wizard
				 */
				private static $instance = null;

				/**
				 * Get instance
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
				 * Init actions
				 */
				public function init_actions() {
					if ( apply_filters( $this->theme_name . '_enable_setup_wizard', true ) && current_user_can( 'manage_options' ) ) {
						add_filter(
							$this->theme_name . '_theme_setup_wizard_content',
							array(
								$this,
								'theme_setup_wizard_content',
							)
						);
						add_filter(
							$this->theme_name . '_theme_setup_wizard_steps',
							array(
								$this,
								'theme_setup_wizard_steps',
							)
						);
					}
					parent::init_actions();
				}
				/**
				 * Theme setup wizard steps
				 *
				 * @param string $steps .
				 */
				public function theme_setup_wizard_steps( $steps ) {
					return $steps;
				}
				/**
				 * Theme setup wizard content
				 *
				 * @param string $content .
				 */
				public function theme_setup_wizard_content( $content ) {
					if ( $this->is_possible_upgrade() ) {
						ciyashop_array_unshift_assoc(
							$content,
							'upgrade',
							array(
								'title'            => __( 'Upgrade', 'ciyashop' ),
								'description'      => __( 'Upgrade Content and Settings', 'ciyashop' ),
								'pending'          => __( 'Pending.', 'ciyashop' ),
								'installing'       => __( 'Installing Updates.', 'ciyashop' ),
								'success'          => __( 'Success.', 'ciyashop' ),
								'install_callback' => array( $this, '_content_install_updates' ),
								'checked'          => 1,
							)
						);
					}
					return $content;
				}
				/**
				 * Get default theme style
				 */
				public function get_default_theme_style() {
					return false;
				}
				/**
				 * Is possible upgrade
				 */
				public function is_possible_upgrade() {
					$widget = get_option( 'widget_text' );
					if ( is_array( $widget ) ) {
						foreach ( $widget as $item ) {
							if ( isset( $item['dtbwp_widget_bg'] ) ) {
								return true;
							}
						}
					}
					// check if shop page is already installed?
					$shoppage = get_page_by_title( 'Shop' );
					if ( $shoppage || get_option( 'page_on_front', false ) ) {
						return true;
					}

					return false;
				}
				/**
				 * Install updates
				 */
				public function _content_install_updates() {

					$widget = get_option( 'widget_text' );
					if ( is_array( $widget ) ) {
						foreach ( $widget as $key => $val ) {
							if ( ! empty( $val['text'] ) ) {
								$widget[ $key ]['text'] = str_replace( '[dtbaker_icon icon="truck"]', '<div class="dtbaker-icon-truck"></div>', $val['text'] );
							}
						}
						update_option( 'widget_text', $widget );
					}

					return true;

				}

			}

			dtbwp_Envato_Theme_Setup_Wizard::get_instance();
		}
	}
endif;
