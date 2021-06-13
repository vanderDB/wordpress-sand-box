<?php
/**
 * Ciyashop Compare Classes
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ciyashop
 */

/**
 * Compare class.
 */
class Ciyashop_Compare {
	/**
	 * Init
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'wp_ajax_ciyashop_get_compare', array( $this, 'ciyashop_get_compare' ) );
		add_action( 'wp_ajax_nopriv_ciyashop_get_compare', array( $this, 'ciyashop_get_compare' ) );

		add_action( 'wp_ajax_ciyashop_remove_compare_product', array( $this, 'ciyashop_remove_compare_product' ) );
		add_action( 'wp_ajax_nopriv_ciyashop_remove_compare_product', array( $this, 'ciyashop_remove_compare_product' ) );

		add_shortcode( 'ciyashop_add_to_compare', array( $this, 'cs_add_to_compare' ) ); // phpcs:ignore WPThemeReview.PluginTerritory.ForbiddenFunctions.plugin_territory_add_shortcode
		add_action( 'woocommerce_single_product_summary', array( $this, 'cs_wc_single_product_compare' ), 35 );
		add_action( 'wp_footer', array( $this, 'ciyashop_compare_model' ) );
	}

	/**
	 * Add ciyashop compare ajax call.
	 *
	 * @return void
	 */
	public function ciyashop_compare_model() {
		global $ciyashop_options;

		if ( isset( $ciyashop_options['show_compare'] ) && $ciyashop_options['show_compare'] ) {
			?>
			<div id="cs-comparelist" class="cs-comparelist"></div>
			<?php
		}
	}

	/**
	 * Add ciyashop compare ajax call.
	 *
	 * @return void
	 */
	public function ciyashop_remove_compare_product() {
		$data_response  = array();
		$ciyashop_nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $ciyashop_nonce, 'ciyashop_nonce' ) ) {
			wp_die();
		}
		
		$product_id = isset( $_POST['product_id'] ) ? sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : '';
		if ( $product_id ) {
			$this->remove_product_from_compare( $product_id );
			$data_response = array(
				'removed'    => true,
				'product_id' => $product_id,
			);
		}

		echo wp_json_encode( $data_response );
		wp_die();
	}
	
	/**
	 * Add ciyashop compare ajax call.
	 *
	 * @return void
	 */
	public function ciyashop_get_compare() {
		global $product, $ciyashop_options;

		$ciyashop_nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $ciyashop_nonce, 'ciyashop_nonce' ) ) {
			wp_die();
		}

		$product_id     = isset( $_POST['product_id'] ) ? sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : '';
		$product_action = isset( $_POST['product_action'] ) ? sanitize_text_field( wp_unslash( $_POST['product_action'] ) ) : '';

		if ( 'product_add' === $product_action && ! $this->is_product_in_compare( $product_id ) ) {
			$this->add_product_in_compare( $product_id );
		}

		$products_data          = array();
		$products               = $this->get_compare();
		$product_count          = count( $products );
		$all_fields             = ciyashop_get_product_compare_fields();
		$compare_title          = isset( $ciyashop_options['compare_title'] ) ? $ciyashop_options['compare_title'] : esc_html__( 'Compare', 'ciyashop' );
		$compare_empty_text     = isset( $ciyashop_options['compare_empty_text'] ) ? $ciyashop_options['compare_empty_text'] : esc_html__( 'No products added for the compare', 'ciyashop' );
		$compare_fields_to_show = isset( $ciyashop_options['compare_fields_to_show'] ) ? $ciyashop_options['compare_fields_to_show'] : array_keys( $all_fields );

		do_action( 'ciyashop_before_compare_popup', $products );
		?>
		<div class="cs-woocompare-popup-overlay"></div>
		<div class="cs-woocompare-popup">
			<div class="cs-woocompare-popup-header">
				<h1 class="cs-compare-title"><?php echo esc_html( $compare_title ); ?></h1>
				<button type="button" id="cs-compare-popup-close" class="close-model">×</button>
			</div>
			<div class="cs-woocompare-popup-content">
				<div class="compare-empty-text">
					<?php echo wp_kses_post( $compare_empty_text ); ?>
				</div>
				<?php
				if ( $product_count > 0 ) {
					?>
					<div class="cs-compare-list-wrapper">
						<div class="cs-compare-list-header">
							<div class="cs-compare-list-title"></div>
							<?php
							foreach ( $compare_fields_to_show as $field_value ) {
								?>
								<div class="cs-compare-list-title"><?php echo esc_html( $all_fields[ $field_value ] ); ?></div>
								<?php
							}
							?>
						</div>
						<?php do_action( 'ciyashop_before_compare_product_list', $products ); ?>
						<div class="cs-compare-list-content">
							<?php
							do_action( 'ciyashop_before_compare_loop', $products );

							foreach ( $products as $product_id ) {
								$product = wc_get_product( $product_id );
								if ( $product ) {
									?>
									<div class="cs-product-list-column">
										<div class="cs-product-list-row">
											<div class="remove">
												<a class="cs-product-compare-remove" href="#" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>">
													<span class="ciyashop-compare-remove">x</span>
												</a>
											</div>
										</div>
										<?php
										foreach ( $compare_fields_to_show as $field_key ) {
											?>
											<div class="cs-product-list-row">
											<?php
											switch ( $field_key ) {
												case 'image':
													?>
													<div class="<?php echo esc_attr( $field_key ); ?>">
														<?php echo '<div class="image-wrap">' . $product->get_image( 'thumbnail' ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
													</div>
													<?php
													break;
												case 'title':
													?>
													<div class="<?php echo esc_attr( $field_key ); ?>">
														<?php echo $product->get_name(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
													</div>													
													<?php
													break;
												case 'price':
													?>
													<div class="<?php echo esc_attr( $field_key ); ?>">
														<?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
													</div>													
													<?php
													break;
												case 'description':
													?>
													<div class="<?php echo esc_attr( $field_key ); ?>">
														<?php echo ( $product->get_short_description() ? $product->get_short_description() : 'N/A' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
													</div>													
													<?php
													break;
												case 'add-to-cart':
													?>
													<div class="<?php echo esc_attr( $field_key ); ?>">
														<?php woocommerce_template_loop_add_to_cart(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
													</div>													
													<?php
													break;
												case 'sku':
													?>
													<div class="<?php echo esc_attr( $field_key ); ?>">
														<?php echo ( $product->get_sku() ? $product->get_sku() : 'N/A' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
													</div>													
													<?php
													break;
												case 'availability':
													?>
													<div class="<?php echo esc_attr( $field_key ); ?>">
														<?php 
														$availability = $product->get_availability();
														if ( empty( $availability['availability'] ) ) {
															$availability['availability'] = esc_html__( 'In stock', 'ciyashop' );
														}
														echo sprintf( '<span>%s</span>', esc_html( $availability['availability'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
														?>
													</div>													
													<?php
													break;
												case 'categories':
													?>
													<div class="<?php echo esc_attr( $field_key ); ?>">
														<?php echo ( wc_get_product_category_list( $product->get_id() ) ? wc_get_product_category_list( $product->get_id() ) : 'N/A' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
													</div>													
													<?php
													break;
												case 'weight':
													?>
													<div class="<?php echo esc_attr( $field_key ); ?>">
														<?php echo ( $product->get_weight() ? $product->get_weight() : 'N/A' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
													</div>													
													<?php
													break;
												case 'dimensions':
													?>
													<div class="<?php echo esc_attr( $field_key ); ?>">
														<?php echo ( wc_format_dimensions( $product->get_dimensions( false ) ) ) ? wc_format_dimensions( $product->get_dimensions( false ) ) : 'N/A'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
													</div>													
													<?php
													break;
												case 'rating':
													?>
													<div class="<?php echo esc_attr( $field_key ); ?>">
														<?php
														if ( $product->get_average_rating() ) {
															$rating = $product->get_average_rating();
															$count  = $product->get_rating_count();
															echo wc_get_rating_html( $rating, $count ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
														} else {
															echo 'N/A';
														}
														?>
													</div>													
													<?php
													break;
												default:
													?>
													<div class="<?php echo esc_attr( $field_key ); ?>">
														<?php
														$p_attributes = $product->get_attributes();
														if ( isset( $p_attributes[ $field_key ] ) && $p_attributes[ $field_key ] ) {
															$product_termas = array();
															$terms          = get_the_terms( $product->get_id(), $field_key );

															if ( ! empty( $terms ) ) {
																foreach ( $terms as $term ) {
																	$term             = sanitize_term( $term, $field_key );
																	$product_termas[] = $term->name;
																}
															}
															echo implode( ', ', $product_termas ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
														} else {
															echo 'N/A';
														}
														?>
													</div>
													<?php
											}
											?>
											</div>
											<?php
										}
										?>
									</div>
									<?php
								}
							}
							do_action( 'ciyashop_after_compare_loop', $products );
							?>
						</div>
						<?php do_action( 'ciyashop_after_compare_product_list', $products ); ?>
					</div>
					<?php
				}
				?>
			</div>
		</div>
		<?php
		do_action( 'ciyashop_afer_compare_popup', $products );
		wp_die();
	}

	/**
	 * Add product in compare.
	 *
	 * @param int $product_id product id.
	 *
	 * @return void
	 */
	public function add_product_in_compare( $product_id ) {
		$_compare = $this->get_compare();

		if ( ! in_array( $product_id, $_compare ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			$_compare[] = $product_id;
			$this->set_compare( $_compare );
		}
	}

	/**
	 * Remove product from compare.
	 *
	 * @param int $product_id product id.
	 *
	 * @return void
	 */
	public function remove_product_from_compare( $product_id ) {
		$_compare = $this->get_compare();

		if ( in_array( $product_id, $_compare ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			$key = array_search( $product_id, $_compare ); // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			if ( false !== $key ) {
				unset( $_compare[ $key ] );
			}
			$this->set_compare( $_compare );
		}
	}

	/**
	 * Set compare.
	 */
	public function set_compare( $_compare = array() ) {

		if ( ! is_serialized( $_compare ) ) {
			$_compare_data = maybe_serialize( $_compare );
		}

		if ( is_multisite() ) {
			setcookie( 'cs_compare_' . get_current_blog_id(), $_compare_data, time() + 86400, '/' );
			$_COOKIE[ 'cs_compare_' . get_current_blog_id() ] = $_compare_data;
		} else {
			setcookie( 'cs_compare', $_compare_data, time() + 86400, '/' );
			$_COOKIE['cs_compare'] = $_compare_data;
		}
	}
	
	/**
	 * Check if product in compare.
	 *
	 * @param int $product_id product id.
	 *
	 * @return bool
	 */
	public function is_product_in_compare( $product_id ) {
		$_compare = $this->get_compare();

		if ( in_array( $product_id, $_compare ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
			return true;
		}

		return false;
	}
	
	/**
	 * Get compare.
	 */
	public function get_compare() {

		$_compare      = array();
		$_compare_data = '';

		if ( is_multisite() && isset( $_COOKIE[ 'cs_compare_' . get_current_blog_id() ] ) ) {
			$_compare_data = sanitize_text_field( wp_unslash( $_COOKIE[ 'cs_compare_' . get_current_blog_id() ] ) );
		} elseif ( isset( $_COOKIE['cs_compare'] ) ) {
			$_compare_data = sanitize_text_field( wp_unslash( $_COOKIE['cs_compare'] ) );
		}

		if ( $_compare_data && is_serialized( $_compare_data ) ) {
			$_compare = maybe_unserialize( $_compare_data );
		}

		if ( class_exists( 'SitePress' ) && $_compare ) {
			$tlp_compare = array();
			foreach ( $_compare as $compare ) {
				$tlp_compare[] = apply_filters( 'wpml_object_id', $compare, 'product' );
			}
			$_compare = $tlp_compare;
		}

		if ( ! is_array( $_compare ) ) {
			$_compare = array();
		}

		return $_compare;
	}
	
	/**
	 * Compare Shortcode
	 *
	 * @param array $atts shortcode attributes.
	 */
	public function cs_add_to_compare( $atts ) {
		global $product;

		ob_start();
		if ( $product ) {
			$product_id = $product->get_id();
			?>
			<a href="#" class="compare button ciyashop-compare" data-product-action="product_add" data-product-id="<?php echo esc_attr( $product_id ); ?>" rel="nofollow"><?php esc_html_e( 'Compare', 'ciyashop' ); ?></a>			
			<?php
		}
		$output = ob_get_clean();

		return $output;
	}

	/**
	 * Print "Add to Compare" shortcode
	 *
	 * @return void
	 */
	public function cs_wc_single_product_compare() {
		global $ciyashop_options;

		if ( ! wp_doing_ajax() ) {
			if ( isset( $ciyashop_options['show_compare'] ) && $ciyashop_options['show_compare'] ) {
				echo do_shortcode( '[ciyashop_add_to_compare]' );
			}
		}
	}
}

if ( ! class_exists( 'YITH_WOOCOMPARE' ) ) {
	$cs_compare = new Ciyashop_Compare();
	$cs_compare->init();
}
