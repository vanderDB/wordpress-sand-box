<?php
/**
 * The file that defines the admin notices class.
 *
 * @link       http://www.potenzaglobalsolutions.com/
 * @since 3.5.0
 *
 * @package    CiyaShop
 */

/**
 * The CiyaShop Admin Notices class
 */
class CiyaShop_Admin_Notices {

	/**
	 * Stores notices.
	 *
	 * @var array
	 */
	private $notices = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 3.5.0
	 */
	public function __construct() {

		$this->notices = $this->get_admin_notices();

		add_action( 'admin_notices', array( $this, 'admin_notices' ), 99 );
		add_action( 'wp_ajax_ciyashop_dismiss_notice_handler', array( $this, 'dismiss_notice_handler' ) );
	}

	/**
	 * Admin notices.
	 *
	 * @since 3.5.0
	 */
	public function admin_notices() {
		if ( ! empty( $this->notices ) ) {
			foreach ( $this->notices as $notice ) {
				$this->render_notice( $notice );
			}
		}
	}

	/**
	 * Render admin notices.
	 *
	 * @since 3.5.0
	 * @param string $args The option that contains arguments for notices.
	 */
	public function render_notice( $args = array() ) {

		if ( empty( $args ) ) {
			return;
		}

		if ( ! is_array( $args ) ) {
			return;
		}

		$defaults = array(
			'message'        => '',
			'title'          => '',
			'type'           => 'info',
			'dismissible'    => false,
			'dismiss_option' => '',
			'classes'        => '',
		);

		$args = wp_parse_args( $args, $defaults );

		if ( empty( $args['message'] ) ) {
			return;
		}

		$notice_types = array(
			'error'   => 'notice-error',   // error message displayed with a red border.
			'warning' => 'notice-warning', // warning message displayed with a yellow border.
			'success' => 'notice-success', // success message displayed with a green border.
			'info'    => 'notice-info',    // info message displayed with a blue border.
		);

		$classes = array(
			'notice',
			'cs-admin-notice',
		);

		if ( array_key_exists( $args['type'], $notice_types ) ) {
			$classes[] = $notice_types[ $args['type'] ];
		} else {
			$classes[] = $notice_types['info'];
		}

		$dismiss_option = false;

		if ( $args['dismissible'] ) {
			$classes[] = 'is-dismissible';
			if ( isset( $args['dismiss_option'] ) && ! empty( $args['dismiss_option'] ) ) {
				$classes[]      = 'cs-dismiss-admin-notice';
				$dismiss_option = $args['dismiss_option'];
			}
		}

		$maybe_display_notice = 'display';

		if ( $dismiss_option ) {
			$maybe_display_notice = get_option( $dismiss_option, 'display' );
		}

		if ( 'display' !== $maybe_display_notice || ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( ! empty( $args['classes'] ) ) {
			$classes[] = ciyashop_class_builder( $args['classes'] );
		}

		$classes = ciyashop_class_builder( $classes );

		?>
		<div class="<?php echo esc_attr( $classes ); ?>" data-dismiss_option="<?php echo esc_attr( $dismiss_option ); ?>">
			<div class="notice-content">
				<?php
				if ( ! empty( $args['title'] ) ) {
					?>
					<h2><?php echo esc_html( $args['title'] ); ?></h2>
					<?php
				}
				echo wp_kses_post( wpautop( $args['message'] ) );
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Ajax dismiss notice handler.
	 *
	 * @since 3.5.0
	 */
	public function dismiss_notice_handler() {

		$ciyashop_nonce = isset( $_POST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) : '';

		if ( ! wp_verify_nonce( $ciyashop_nonce, 'ciyashop_admin_nonce' ) ) {
			return false;
			wp_die();
		}

		if ( isset( $_POST['dismiss_option'] ) && ! empty( $_POST['dismiss_option'] ) ) {
			$dismiss_option = sanitize_text_field( wp_unslash( $_POST['dismiss_option'] ) );
			update_option( $dismiss_option, 'dismissed' );
		}
		wp_die();
	}

	/**
	 * Get admin notices.
	 *
	 * @since 3.5.0
	 */
	public function get_admin_notices() {
		$admin_notices = '';
		if ( ciyashop_check_plugin_active( 'js_composer/js_composer.php' ) ) {
			if ( defined( 'WPB_VC_VERSION' ) ) {
				$wpb_version = str_replace( '.', '', WPB_VC_VERSION );
				if ( strlen( $wpb_version ) <= 2 ) {
					$wpb_version = $wpb_version . '0';
				}
				if ( 605 >= (int) $wpb_version ) {
					$admin_notices = array(
						array(
							'title'          => esc_html__( 'Update "WPBakery Page Builder"', 'ciyashop' ),
							'message'        => wp_kses_post(
								__( 'The current version of the theme contains a significant change in the <strong>Font Awesome</strong> fonts for compatibility with the latest version of the <strong>WPBakery Page Builder</strong>. The latest version of the WPBakery Page Builder plugin is bundled with the current release of the theme.<br><br>So, please update the <strong>PGS Core</strong>, and <strong>WPBakery Page Builder</strong> plugins and clear server-side cache.', 'ciyashop' )
							),
							'dismissible'    => true,
							'dismiss_option' => 'ciyashop_update_wpbakery_notice_v350',
							'type'           => 'warning',
						),
					);
				}
			}
		}
		$admin_notices = apply_filters( 'ciyashop_admin_notices', $admin_notices );
		return $admin_notices;

	}

}
new CiyaShop_Admin_Notices();
