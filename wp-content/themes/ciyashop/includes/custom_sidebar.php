<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/** * Custom Sidebar * Class for adding custom widget area and choose them on single pages/posts */
if ( ! class_exists( 'CiyaShopCustomSidebar' ) ) {
	/**
	 * CiyaShopCustomSidebar
	 */
	class CiyaShopCustomSidebar {
		/**
		 * Variable
		 *
		 * @var $sidebars .
		 */
		var $sidebars = array();
		/**
		 * Variable
		 *
		 * @var $stored .
		 */
		var $stored = '';
		/**
		 * Construct
		 */
		public function __construct() {
			$this->stored = 'ciyashop_sidebars';
			$this->title  = __( 'Custom Widget Area', 'ciyashop' );
			add_action( 'load-widgets.php', array( &$this, 'ciyashop_load_files' ), 5 );
			add_action( 'widgets_admin_page', array( &$this, 'ciyashop_widgets_sidebar_form' ), 5 );
			add_action( 'widgets_init', array( $this, 'ciyashop_register_custom_sidebars' ), 9999 );
			add_action( 'wp_ajax_ciyashop_delete_sidebar_area', array( $this, 'ciyashop_delete_sidebar_area' ), 1000 );
			add_action( 'wp_ajax_nopriv_ciyashop_delete_sidebar_area', array( $this, 'ciyashop_delete_sidebar_area' ), 1000 );
		}
		/**
		 * Ciyashop load files
		 */
		public function ciyashop_load_files() {
			add_action( 'load-widgets.php', array( $this, 'ciyashop_add_sidebar_area' ), 100 );
			$current_screen = get_current_screen();
			if ( is_object( $current_screen ) && 'widgets' === $current_screen->id ) {
				wp_enqueue_script( 'ciyashop_sidebar', get_template_directory_uri() . '/js/custom_sidebar.min.js' );
				wp_localize_script(
					'ciyashop_sidebar',
					'ciyashop_sidebar_strings',
					array(
						'delete_sidebar_msg'     => __( 'You cannot delete this sidebar. This sidebar is already in use.', 'ciyashop' ),
						'delete_sidebar_confirm' => __( 'Are you sure you want to delete sidebar?', 'ciyashop' ),
						'sidebar_nonce'          => wp_create_nonce( 'ciyashop_sidebar_nonce' ),
					)
				);
			}
		}
		/**
		 * Ciyashop widgets sidebar form
		 */
		public function ciyashop_widgets_sidebar_form() { ?>
			<div id="ciyashop-widgets-form-cover">
				<h2><?php echo esc_html__( 'Custom Sidebar Widget', 'ciyashop' ); ?></h2>
				<form method="post" action="widgets.php">
					<input type="text" name="ciyashop_sidebar_name" placeholder="<?php echo esc_attr__( 'Enter Sidebar Name', 'ciyashop' ); ?>" />
					<input type="submit" class="button button-primary" name="create_ciyashop_sidebar" value="<?php echo esc_attr__( 'Create', 'ciyashop' ); ?>" />
				</form>
			</div>
			<?php
		}
		/**
		 * Ciyashop add sidebar area
		 */
		public function ciyashop_add_sidebar_area() {
			if ( isset( $_POST['create_ciyashop_sidebar'] ) && isset( $_POST['ciyashop_sidebar_name'] ) && ! empty( $_POST['ciyashop_sidebar_name'] ) ) {
				$this->sidebars = get_option( $this->stored );
				$count          = 0;

				if ( is_array( $this->sidebars ) ) {
					$count = count( $this->sidebars );
				}

				if ( $count > 0 ) {
					$id = $this->get_next_id( $count, $this->sidebars );
				} else {
					$id = 'pgs-cs-1';
				}

				$name = $this->ciyashop_get_sidebar_name( sanitize_text_field( wp_unslash( $_POST['ciyashop_sidebar_name'] ) ) );
				if ( empty( $this->sidebars['name'] ) ) {
					$this->sidebars[] = array(
						'name' => $name,
						'id'   => $id,
					);
				} else {
					$this->sidebars[] = array_merge(
						$this->sidebars,
						array(
							'name' => $name,
							'id'   => $id,
						)
					);
				}
				update_option( $this->stored, $this->sidebars );
				unset( $_POST['create_ciyashop_sidebar'] );
				unset( $_POST['ciyashop_sidebar_name'] );
				wp_redirect( get_admin_url() . 'widgets.php' );
				return false;
				wp_die();
			}
		}
		
		/**
		 * New sidebar id
		 */
		public function get_next_id( $count, $sidebars ) {
			$new_id = ++$count;
			$key    = array_search( 'pgs-cs-' . $new_id, array_column( $sidebars, 'id' ), true );
			if ( $key ) {
				return $this->get_next_id( $count, $sidebars );
			} else {
				return 'pgs-cs-' . $new_id;
			}
		}

		/**
		 * Delete sidebar area from the db
		 */
		public function ciyashop_delete_sidebar_area() {
			/**
			 * Search value
			 *
			 * @param string $id .
			 * @param array  $array .
			 * @param array  $field .
			 */
			if ( ! empty( $_POST['widget_id'] ) ) {

				$ciyashop_nonce = isset( $_POST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) : '';

				if ( ! wp_verify_nonce( $ciyashop_nonce, 'ciyashop_sidebar_nonce' ) ) {
					return false;
					wp_die();
				}

				$result         = false;
				$widget_id      = sanitize_text_field( wp_unslash( $_POST['widget_id'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				$this->sidebars = get_option( $this->stored );
				$key            = $this->search_for_id( $widget_id, $this->sidebars );

				if ( false !== $key ) {
					unset( $this->sidebars[ $key ] );
					update_option( $this->stored, $this->sidebars );
					$result = true;
				}

				echo wp_json_encode( array( 'result' => $result ) );
			}
			wp_die();
		}

		/**
		 * Find the key of the value
		 *
		 * @param string $name .
		 */
		function search_for_id( $id, $sidebars ) {
			foreach ( $sidebars as $key => $val ) {
				if ( $val['id'] === $id ) {
					return $key;
				}
			}
			return null;
		}

		/**
		 * Checks the user submitted name and makes sure that there are no colitions
		 *
		 * @param string $name .
		 */
		function ciyashop_get_sidebar_name( $name ) {
			if ( empty( $GLOBALS['wp_registered_sidebars'] ) ) {
				return $name;
			}
			$taken = array();
			foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) {
				$taken[] = $sidebar['name'];
			}
			if ( empty( $this->sidebars ) ) {
				$this->sidebars = array();
			}
			$taken = array_merge( $taken, $this->sidebars );
			if ( in_array( $name, $taken, true ) ) {
				$counter  = substr( $name, -1 );
				$new_name = '';
				if ( ! is_numeric( $counter ) ) {
					$new_name = $name . ' 1';
				} else {
					$new_name = substr( $name, 0, -1 ) . ( (int) $counter + 1 );
				}
				$name = $this->ciyashop_get_sidebar_name( $new_name );
			}
			return $name;
		}

		/**
		 * Register custom sidebar areas
		 */
		function ciyashop_register_custom_sidebars() {
			if ( empty( $this->sidebars ) ) {
				$this->sidebars = get_option( $this->stored );
			}

			$args = array(
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			);
			/**
			 * Filters arguments for custom widgets.
			 *
			 * @param array    $args      Array of widget parameters.
			 *
			 * @visible true
			 */
			$args = apply_filters( 'ciyashop_custom_widget_args', $args );
			if ( is_array( $this->sidebars ) ) {
				$sidebar_details = array();
				foreach ( $this->sidebars as $key => $sidebar ) {
					if ( isset( $sidebar['name'] ) && isset( $sidebar['id'] ) ) {
						$args['name']      = $sidebar['name'];
						$args['id']        = $sidebar['id'];
						$args['class']     = 'ciyashop-custom';
						$sidebar_details[] = array(
							'id'   => $args['id'],
							'name' => $args['name'],
						);
						register_sidebar( $args );
					} else {
						unset( $this->sidebars[ $key ] );
					}
				}
				update_option( $this->stored, $this->sidebars );
			}
		}
	}
}

new CiyaShopCustomSidebar();
