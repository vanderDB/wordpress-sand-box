<?php
/**
 * WooCommerce Functions
 *
 * @package CiyaShop
 */

if ( function_exists( 'WC' ) ) {

	require_once get_parent_theme_file_path( '/includes/woocommerce/module/class-ciyashop-wishlist.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	require_once get_parent_theme_file_path( '/includes/woocommerce/module/class-ciyashop-compare.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

	if ( ! function_exists( 'ciyashop_get_available_attr_array' ) ) {
		/**
		 * Get all available product attributes for product filter options
		 *
		 * @param string $return .
		 */
		function ciyashop_get_available_attr_array( $return = 'all' ) {
			// Default Attributes.
			$default_filters = array(
				'search-box'   => esc_html__( 'Search Box', 'ciyashop' ),
				'categories'   => esc_html__( 'Categories', 'ciyashop' ),
				'ratings'      => esc_html__( 'Ratings', 'ciyashop' ),
				'price-slider' => esc_html__( 'Price Slider', 'ciyashop' ),
			);
			if ( 'default_filters' === $return ) {
				return $default_filters;
			}

			// Taxonomy Attributes.
			$attribute_taxonomies = wc_get_attribute_taxonomies();
			$attributes           = array();
			if ( ! empty( $attribute_taxonomies ) ) {
				foreach ( $attribute_taxonomies as $attr ) {
					$attributes[ $attr->attribute_name ] = $attr->attribute_label;
				}
			}
			if ( 'taxonomy_attributes' === $return ) {
				return $attributes;
			}
			return array_merge( $default_filters, $attributes );
		}
	}

	if ( ! function_exists( 'ciyashop_get_product_attr_array' ) ) {
		/**
		 * Get all the product attributes
		 */
		function ciyashop_get_product_attr_array() {
			$product_attributes = wc_get_attribute_taxonomies();
			$product_attribute  = array();

			foreach ( $product_attributes as $key => $value ) {
				$product_attribute[ 'pa_' . $value->attribute_name ] = wc_attribute_label( 'pa_' . $value->attribute_name );
			}

			return $product_attribute;
		}
	}

	if ( ! function_exists( 'ciyashop_get_product_compare_fields' ) ) {
		/**
		 * Get all the compare fields
		 */
		function ciyashop_get_product_compare_fields() {
			$product_fields = array(
				'image'        => esc_html__( 'Image', 'ciyashop' ),
				'title'        => esc_html__( 'Title', 'ciyashop' ),
				'price'        => esc_html__( 'Price', 'ciyashop' ),
				'add-to-cart'  => esc_html__( 'Add to cart', 'ciyashop' ),
				'description'  => esc_html__( 'Description', 'ciyashop' ),
				'sku'          => esc_html__( 'Sku', 'ciyashop' ),
				'availability' => esc_html__( 'Availability', 'ciyashop' ),
				'categories'   => esc_html__( 'Categories', 'ciyashop' ),
				'weight'       => esc_html__( 'Weight', 'ciyashop' ),
				'dimensions'   => esc_html__( 'Dimensions', 'ciyashop' ),
				'rating'       => esc_html__( 'Rating', 'ciyashop' ),
			);

			if ( function_exists( 'ciyashop_get_product_attr_array' ) ) {
				$product_attributes = ciyashop_get_product_attr_array();
			}

			$fields = array_merge( $product_fields, $product_attributes );
			$fields = apply_filters( 'ciyashop_get_product_compare_fields', $fields );

			return $fields;
		}
	}
}
