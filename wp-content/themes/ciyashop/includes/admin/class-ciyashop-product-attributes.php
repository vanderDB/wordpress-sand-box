<?php
/**
 * Product attribute
 *
 * @package CiyaShop
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Ciyashop_Product_Attributes' ) ) {
	/**
	 * Product attributes
	 */
	class Ciyashop_Product_Attributes {
		/**
		 * Instance
		 *
		 * @var $instance
		 */
		public static $instance = null;

		/**
		 * Construct
		 */
		public function __construct() {
			do_action( 'ciyashop_theme_class_loaded' );
			$this->init();
		}

		/**
		 * Init
		 */
		public static function init() {
			add_action( 'init', array( __CLASS__, 'ciyashop_set_product_attributes' ) );

			/*
			----------- add attribute fields admin product attribute page --------------
			*/
			// add attribute page.
			add_action( 'woocommerce_after_add_attribute_fields', array( __CLASS__, 'ciyashop_add_custom_field_attributes' ) );
			// edit attribute page.
			add_action( 'woocommerce_after_edit_attribute_fields', array( __CLASS__, 'ciyashop_edit_custom_field_attributes' ) );

			/*
			----------- handle attribute post fields of product attribute page --------------
			*/
			// handle add attribute.
			add_action( 'woocommerce_attribute_added', array( __CLASS__, 'ciyashop_wc_attribute_added' ), 20, 2 );
			// handle edit attribute.
			add_action( 'woocommerce_attribute_updated', array( __CLASS__, 'ciyashop_wc_attribute_updated' ), 20, 3 );
		}

		/**
		 * Instance
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Add custom field attributes
		 */
		public static function ciyashop_add_custom_field_attributes() {
			// Display fields on add attr page.
			?>
			<div class="form-field">
				<label for="ciyashop_enable_swatch"><?php esc_html_e( 'Enable Swatch?', 'ciyashop' ); ?></label>
				<input name="ciyashop_enable_swatch" id="ciyashop_enable_swatch" type="checkbox" />
				<p class="description"><?php esc_html_e( 'Check this checkbox to enable attributes swatch.', 'ciyashop' ); ?></p>
			</div>
			<?php
		}

		/**
		 * Edit custom field attributes
		 */
		public static function ciyashop_edit_custom_field_attributes() {
			if ( ! isset( $_GET['edit'] ) ) {
				return;
			}
			$attribute_name = wc_attribute_taxonomy_name_by_id( (int) $_GET['edit'] );
			$swatch         = self::ciyashop_get_attribute_term( $attribute_name, 'swatch' );
			$checked        = ( 'on' === (string) $swatch ) ? 'checked' : '';
			?>
			<tr class="form-field form-required">
				<th scope="row" valign="top">
					<label for="ciyashop_enable_swatch"><?php esc_html_e( 'Enable Swatch?', 'ciyashop' ); ?></label>
				</th>
				<td>
					<input type="checkbox" name="ciyashop_enable_swatch" id="ciyashop_enable_swatch" <?php echo esc_attr( $checked ); ?>>
					<p class="description"><?php esc_html_e( 'Check this checkbox to enable attributes swatch.', 'ciyashop' ); ?></p>
				</td>
			</tr>
			<?php
		}

		/**
		 * Handle post fields of update attribute page.
		 *
		 * @param string $attribute_id .
		 * @param string $attribute .
		 * @param string $old_attribute_name .
		 */
		public static function ciyashop_wc_attribute_updated( $attribute_id, $attribute, $old_attribute_name ) {
			if ( ! isset( $_GET['edit'] ) || absint( $_GET['edit'] !== (string) $attribute_id ) ) {
				return;
			}

			$ciyashop_swatch_attr = isset( $_POST['ciyashop_enable_swatch'] ) ? sanitize_text_field( wp_unslash( $_POST['ciyashop_enable_swatch'] ) ) : '';
			if ( $ciyashop_swatch_attr ) {
				update_option( 'ciyashop_pa_' . $attribute['attribute_name'] . '_swatch', $ciyashop_swatch_attr );
			} else {
				delete_option( 'ciyashop_pa_' . $attribute['attribute_name'] . '_swatch', $ciyashop_swatch_attr );
			}
		}

		/**
		 * Handle post fields of add attribute page.
		 *
		 * @param string $attribute_id .
		 * @param string $attribute .
		 */
		public static function ciyashop_wc_attribute_added( $attribute_id, $attribute ) {
			if ( ! isset( $_POST['ciyashop_enable_swatch'] ) || empty( $_POST['ciyashop_enable_swatch'] ) ) {
				return;
			}

			$ciyashop_swatch_attr = sanitize_text_field( wp_unslash( $_POST['ciyashop_enable_swatch'] ) );
			add_option( 'ciyashop_pa_' . $attribute['attribute_name'] . '_swatch', $ciyashop_swatch_attr );
		}

		/**
		 * Get attribute option.
		 *
		 * @param string $attribute_name .
		 * @param string $term .
		 */
		public static function ciyashop_get_attribute_term( $attribute_name, $term ) {
			return get_option( 'ciyashop_' . $attribute_name . '_' . $term );
		}
		/**
		 * Set product attributes.
		 */
		public static function ciyashop_set_product_attributes() {

			if ( ! function_exists( 'wc_get_attribute_taxonomies' ) ) {
				return;
			}

			$attribute_taxonomies = wc_get_attribute_taxonomies();
			if ( function_exists( 'pgscf_add_field_group' ) ) {
				$fields = array(
					'id'     => 'pgs_attributes_options',
					'title'  => esc_html__( 'Attributes Options', 'ciyashop' ),
					'screen' => 'taxonomy',
					'fields' => array(
						array(
							'heading'  => esc_html__( 'Image preview', 'ciyashop' ),
							'field_id' => 'image_preview',
							'type'     => 'image',
						),
						array(
							'heading'  => esc_html__( 'Color Preview', 'ciyashop' ),
							'field_id' => 'color_preview',
							'type'     => 'color_picker',
						),
					),
				);

				foreach ( $attribute_taxonomies as $key => $value ) {
					$fields['taxonomy'][] = 'pa_' . $value->attribute_name;
				}
				pgscf_add_field_group( $fields );
			}
		}
	}
}
Ciyashop_Product_Attributes::instance();
