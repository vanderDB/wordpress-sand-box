<?php
/**
 * Ciyashop Wishlist Classes
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ciyashop
 */

/**
 * Wishlist class.
 */
class Ciyashop_Wishlist {
	/**
	 * Init
	 *
	 * @return void
	 */
	public function init() {

		add_action( 'wp_ajax_add_ciyashop_wishlist', array( $this, 'add_ciyashop_wishlist' ) );
		add_action( 'wp_ajax_nopriv_add_ciyashop_wishlist', array( $this, 'add_ciyashop_wishlist' ) );

		add_action( 'wp_ajax_remove_ciyashop_wishlist', array( $this, 'remove_ciyashop_wishlist' ) );
		add_action( 'wp_ajax_nopriv_remove_ciyashop_wishlist', array( $this, 'remove_ciyashop_wishlist' ) );

		add_shortcode( 'ciyashop_wishlist', array( $this, 'cs_wishlist_html' ) ); // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.plugin_territory_add_shortcode
		add_shortcode( 'ciyashop_add_to_wishlist', array( $this, 'cs_add_to_wishlist' ) ); // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.plugin_territory_add_shortcode

		add_action( 'woocommerce_single_product_summary', array( $this, 'cs_wc_single_product_wishlist' ), 31 );
		add_action( 'wp_footer', array( $this, 'cs_wishlist_msg' ) );
	}

	/**
	 * Wishlist Shortcode
	 *
	 * @param array $atts shortcode attributes.
	 */
	public function cs_wishlist_html( $atts ) {

		global $ciyashop_options;

		if ( isset( $ciyashop_options['show_wishlist'] ) && ! $ciyashop_options['show_wishlist'] ) {
			return;
		}

		ob_start();

		$template = locate_template( array( 'woocommerce/cs-wishlist.php' ) );

		$template = apply_filters( 'ciyashop_get_cs_templates', $template );

		if ( $template ) {
			load_template( $template, false );
		}

		$output = ob_get_clean();
		return $output;
	}

	/**
	 * Wishlist Shortcode
	 *
	 * @param array $atts shortcode attributes.
	 */
	public function cs_add_to_wishlist( $atts ) {
		global $product, $ciyashop_options;

		$_wishlist = $this->get_wishlist();

		if ( isset( $ciyashop_options['show_wishlist'] ) && ! $ciyashop_options['show_wishlist'] ) {
			return;
		}

		$wishlist_url = ( isset( $ciyashop_options['cs_wishlist_page'] ) && ! empty( $ciyashop_options['cs_wishlist_page'] ) ) ? get_permalink( $ciyashop_options['cs_wishlist_page'] ) : '#';

		$product_id = $product->get_id();
		ob_start();

		$wishlist_class = 'add_to_wishlist single_add_to_wishlist';

		$wishlist_btn_text = isset( $ciyashop_options['add_to_wishlist_text'] ) ? $ciyashop_options['add_to_wishlist_text'] : esc_html__( 'Add to Wishlist', 'ciyashop' );

		if ( is_array( $_wishlist ) && $_wishlist && $product_id ) {
			if ( in_array( $product_id, $_wishlist ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
				$wishlist_class   .= ' added-wishlist';
				$wishlist_btn_text = isset( $ciyashop_options['browse_wishlist_text'] ) ? $ciyashop_options['browse_wishlist_text'] : esc_html__( 'Browse Wishlist', 'ciyashop' );
			}
		}
		?>
		<div class="cs-wcwl-add-button yith-wcwl-add-to-wishlist">
			<a href="<?php echo esc_url( $wishlist_url ); ?>" rel="nofollow" data-product-id="<?php echo esc_attr( $product_id ); ?>"  class="<?php echo esc_attr( $wishlist_class ); ?>" data-title="<?php echo esc_attr( $wishlist_btn_text ); ?>">
				<span><?php echo esc_html( $wishlist_btn_text ); ?></span>
			</a>
		</div>
		<?php
		$output = ob_get_clean();

		return $output;
	}

	/**
	 * Add ciyashop wishlist ajax call.
	 *
	 * @return void
	 */
	public function add_ciyashop_wishlist() {

		$data_response  = array();
		$ciyashop_nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $ciyashop_nonce, 'ciyashop_nonce' ) ) {
			wp_die();
		}

		$product_id = isset( $_POST['product_id'] ) ? sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : '';

		if ( $this->is_product_in_wishlist( $product_id ) ) {
			$count_products = $this->count_products();
			$data_response  = array(
				'added' => false,
				'count' => $count_products,
			);
		} else {
			$this->add_product_in_wishlist( $product_id );
			$count_products = $this->count_products();
			$data_response  = array(
				'added' => true,
				'count' => $count_products,
			);
		}

		echo wp_json_encode( $data_response );
		wp_die();

	}

	/**
	 * Remove ciyashop wishlist ajax call
	 *
	 * @return void
	 */
	public function remove_ciyashop_wishlist() {
		$product_id = isset( $_POST['product_id'] ) ? sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : '';

		$ciyashop_nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $ciyashop_nonce, 'ciyashop_nonce' ) ) {
			wp_die();
		}

		$this->remove_product_from_wishlist( $product_id );

		$count_products = $this->count_products();

		echo wp_json_encode( $count_products );
		wp_die();
	}
	
	/**
	 * Check if product in wishlist.
	 *
	 * @param int $product_id product id.
	 *
	 * @return bool
	 */
	public function is_product_in_wishlist( $product_id ) {
		$_wishlist = $this->get_wishlist();

		if ( in_array( $product_id, $_wishlist ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			return true;
		}

		return false;
	}

	/**
	 * Add product in wishlist.
	 *
	 * @param int $product_id product id.
	 *
	 * @return void
	 */
	public function add_product_in_wishlist( $product_id ) {
		$_wishlist = $this->get_wishlist();

		if ( ! in_array( $product_id, $_wishlist ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			$_wishlist[] = $product_id;
			$this->set_wishlist( $_wishlist );
		}
	}

	/**
	 * Remove product from wishlist.
	 *
	 * @param int $product_id product id.
	 *
	 * @return void
	 */
	public function remove_product_from_wishlist( $product_id ) {
		$_wishlist = $this->get_wishlist();

		if ( in_array( $product_id, $_wishlist ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			$key = array_search( $product_id, $_wishlist ); // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			if ( false !== $key ) {
				unset( $_wishlist[ $key ] );
			}

			$this->set_wishlist( $_wishlist );
		}
	}

	/**
	 * Get wishlist.
	 */
	public function get_wishlist() {
		
		$_wishlist      = array();
		$_wishlist_data = '';

		if ( is_user_logged_in() ) {

			$user_id   = get_current_user_id();
			$user_meta = get_user_option( 'cs_wishlist', $user_id );

			if ( is_serialized( $user_meta ) ) {
				$_wishlist = maybe_unserialize( $user_meta );
			}
		} else {
			
			if ( is_multisite() && isset( $_COOKIE[ 'cs_wishlist_' . get_current_blog_id() ] ) ) {
				$_wishlist_data = sanitize_text_field( wp_unslash( $_COOKIE[ 'cs_wishlist_' . get_current_blog_id() ] ) );
			} elseif ( isset( $_COOKIE['cs_wishlist'] ) ) {
				$_wishlist_data = sanitize_text_field( wp_unslash( $_COOKIE['cs_wishlist'] ) );
			}

			if ( $_wishlist_data && is_serialized( $_wishlist_data ) ) {
				$_wishlist = maybe_unserialize( $_wishlist_data );
			}
		}

		if ( class_exists( 'SitePress' ) && $_wishlist ) {
			$tlp_wishlist = array();
			foreach ( $_wishlist as $wishlist ) {
				$tlp_wishlist[] = apply_filters( 'wpml_object_id', $wishlist, 'product' );
			}
			$_wishlist = $tlp_wishlist;
		}

		if ( ! is_array( $_wishlist ) ) {
			$_wishlist = array();
		}

		return $_wishlist;
	}

	/**
	 * Set wishlist.
	 */
	public function set_wishlist( $_wishlist = array() ) {
	
		if ( ! is_serialized( $_wishlist ) ) {
			$_wishlist_data = maybe_serialize( $_wishlist );
		}

		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();
			update_user_option( $user_id, 'cs_wishlist', $_wishlist_data );
		} else {
			if ( is_multisite() ) {
				setcookie( 'cs_wishlist_' . get_current_blog_id(), $_wishlist_data, time() + 86400, '/' );
				$_COOKIE[ 'cs_wishlist_' . get_current_blog_id() ] = $_wishlist_data;
			} else {
				setcookie( 'cs_wishlist', $_wishlist_data, time() + 86400, '/' );
				$_COOKIE['cs_wishlist'] = $_wishlist_data;
			}
		}
	}

	/**
	 * Get products count
	 */
	public function count_products() {
		$_wishlist      = $this->get_wishlist();
		$count_products = count( $_wishlist );

		return $count_products;
	}

	/**
	 * Wishlist Massgages.
	 */
	public function cs_wishlist_msg() {
		global $ciyashop_options;

		if ( isset( $ciyashop_options['show_wishlist'] ) && ! $ciyashop_options['show_wishlist'] ) {
			return;
		}

		$product_added_text = isset( $ciyashop_options['product_added_text'] ) ? $ciyashop_options['product_added_text'] : esc_html__( 'Product added!', 'ciyashop' );
		$product_added_text = apply_filters( 'cs_product_added_text', $product_added_text );

		if ( $product_added_text ) {
			?>
			<div class="cs-wishlist-popup-message product-added">
				<div class="cs-wishlist-message">
					<?php echo wp_kses_post( $product_added_text ); ?>
				</div>
			</div>
			<?php
		}

		$already_in_wishlist_text = isset( $ciyashop_options['already_in_wishlist_text'] ) ? $ciyashop_options['already_in_wishlist_text'] : esc_html__( 'The product is already in the wishlist!', 'ciyashop' );
		$already_in_wishlist_text = apply_filters( 'cs_already_in_wishlist_text', $already_in_wishlist_text );

		if ( $already_in_wishlist_text ) {
			?>
			<div class="cs-wishlist-popup-message already-in-wishlist">
				<div class="cs-wishlist-message">
					<?php echo wp_kses_post( $already_in_wishlist_text ); ?>
				</div>
			</div>
			<?php
		}
	}


	/**
	 * Print "Add to Wishlist" shortcode
	 *
	 * @return void
	 */
	public function cs_wc_single_product_wishlist() {

		global $ciyashop_options;

		if ( isset( $ciyashop_options['show_wishlist'] ) && ! $ciyashop_options['show_wishlist'] ) {
			return;
		}

		echo do_shortcode( '[ciyashop_add_to_wishlist]' );

	}
}

if ( ! class_exists( 'YITH_WCWL' ) ) {
	$cs_wishlist = new Ciyashop_Wishlist();
	$cs_wishlist->init();
}
