<?php
/**
 * Template tag
 *
 * @package CiyaShop
 */

/**
 * Product hover style
 */
function ciyashop_product_hover_style() {
	global $ciyashop_options;

	if ( isset( $ciyashop_options['product_hover_style'] ) && ! empty( $ciyashop_options['product_hover_style'] ) ) {
		$hover_style = $ciyashop_options['product_hover_style'];
	} else {
		$hover_style = 'image-center';
	}

	/**
	 * Filters the hover style of the product.
	 *
	 * @param string    $hover_style          Product hover style.
	 * @param array     $ciyashop_options     An array of theme options.
	 *
	 * @visible false
	 * @ignore
	 */
	$hover_style = apply_filters( 'ciyashop_product_hover_style', $hover_style, $ciyashop_options );

	return $hover_style;
}
/**
 * Product hover button shape
 */
function ciyashop_product_hover_button_shape() {
	global $ciyashop_options;

	if ( isset( $ciyashop_options['product_hover_button_shape'] ) && ! empty( $ciyashop_options['product_hover_button_shape'] ) ) {
		$hover_button_shape = $ciyashop_options['product_hover_button_shape'];
	} else {
		$hover_button_shape = 'square';
	}

	/**
	 * Filters the hover button shape of the product.
	 *
	 * @param string    $hover_button_shape   Product hover button shape.
	 * @param array     $ciyashop_options     An array of theme options.
	 *
	 * @visible false
	 * @ignore
	 */
	$hover_button_shape = apply_filters( 'ciyashop_product_hover_button_shape', $hover_button_shape, $ciyashop_options );

	return $hover_button_shape;
}
/**
 * Product hover button style
 */
function ciyashop_product_hover_button_style() {
	global $ciyashop_options;

	if ( isset( $ciyashop_options['product_hover_button_style'] ) && ! empty( $ciyashop_options['product_hover_button_style'] ) ) {
		$hover_button_style = $ciyashop_options['product_hover_button_style'];
	} else {
		$hover_button_style = 'flat';
	}

	/**
	 * Filters the hover button style of the product.
	 *
	 * @param string    $hover_button_style   Product hover button style.
	 * @param array     $ciyashop_options     An array of theme options.
	 *
	 * @visible false
	 * @ignore
	 */
	$hover_button_style = apply_filters( 'ciyashop_product_hover_button_style', $hover_button_style, $ciyashop_options );

	return $hover_button_style;
}
/**
 * Product hover default button style
 */
function ciyashop_product_hover_default_button_style() {
	global $ciyashop_options;

	if ( isset( $ciyashop_options['product_hover_default_button_style'] ) && ! empty( $ciyashop_options['product_hover_default_button_style'] ) ) {
		$hover_button_style = $ciyashop_options['product_hover_default_button_style'];
	} else {
		$hover_button_style = 'dark';
	}

	/**
	 * Filters the hover button style of the product.
	 *
	 * @param string    $hover_button_style   Product hover button style.
	 * @param array     $ciyashop_options     An array of theme options.
	 *
	 * @visible false
	 * @ignore
	 */
	$hover_button_style = apply_filters( 'ciyashop_product_hover_default_button_style', $hover_button_style, $ciyashop_options );

	return $hover_button_style;

}
/**
 * Product hover bar style
 */
function ciyashop_product_hover_bar_style() {
	global $ciyashop_options;

	if ( isset( $ciyashop_options['product_hover_bar_style'] ) && ! empty( $ciyashop_options['product_hover_bar_style'] ) ) {
		$hover_bar_style = $ciyashop_options['product_hover_bar_style'];
	} else {
		$hover_bar_style = 'flat';
	}

	/**
	 * Filters the hover bar style of the product.
	 *
	 * @param string    $hover_bar_style      Product hover bar style.
	 * @param array     $ciyashop_options     An array of theme options.
	 *
	 * @visible false
	 * @ignore
	 */
	$hover_bar_style = apply_filters( 'ciyashop_product_hover_bar_style', $hover_bar_style, $ciyashop_options );

	return $hover_bar_style;
}
/**
 * Product hover add to cart position
 */
function ciyashop_product_hover_add_to_cart_position() {
	global $ciyashop_options;

	if ( isset( $ciyashop_options['product_hover_add_to_cart_position'] ) && ! empty( $ciyashop_options['product_hover_add_to_cart_position'] ) ) {
		$position = $ciyashop_options['product_hover_add_to_cart_position'];
	} else {
		$position = 'center';
	}

	/**
	 * Filters the position of Add to Cart button in product hover.
	 *
	 * @param string    $position             Add to Cart button position.
	 * @param array     $ciyashop_options     An array of theme options.
	 *
	 * @visible false
	 * @ignore
	 */
	$position = apply_filters( 'ciyashop_product_hover_add_to_cart_position', $position, $ciyashop_options );

	return $position;
}
