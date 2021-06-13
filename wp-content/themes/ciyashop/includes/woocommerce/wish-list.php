<?php
/**
 * Wish List
 *
 * @package CiyaShop
 */

add_action( 'ciyashop_before_page_wrapper', 'ciyashop_wc_wishlist', 10 );
/**
 * WC wishlist
 */
function ciyashop_wc_wishlist() {
	/**
	 * Filters active plugins.
	 *
	 * @param array $active_plugins List of active plugins.
	 *
	 * @visible false
	 * @ignore
	 */
	global $ciyashop_options,$cs_product_list_styles;
	
	$product_hover_style = isset( $ciyashop_options['product_hover_style'] ) ? $ciyashop_options['product_hover_style'] : '';

	switch ( $product_hover_style ) {
		case $cs_product_list_styles['hover-summary']:
			add_action( 'woocommerce_before_shop_loop_item_title', 'ciyashop_product_actions_add_wishlist_link', 20 );
			break;
		case $cs_product_list_styles['icons-top-left']:
		case $cs_product_list_styles['icons-top-right']:
			add_action( 'woocommerce_shop_loop_item_title', 'ciyashop_product_actions_add_wishlist_link', 35 );
			break;
		default:
			add_action( 'ciyashop_product_actions', 'ciyashop_product_actions_add_wishlist_link', 8 );
	}
}
// Product Loop.
/**
 * Add wishlist icon
 */
function ciyashop_product_actions_add_wishlist_link() {
	
	global $product, $ciyashop_options;

	$position               = ciyashop_get_tooltip_position();
	$wishlist_icon_position = isset( $position['wishlist_icon'] ) ? $position['wishlist_icon'] : '';
	$show_wishlist          = ( isset( $ciyashop_options['show_wishlist'] ) ) ? $ciyashop_options['show_wishlist'] : true;

	if ( $show_wishlist ) {

		$position               = ciyashop_get_tooltip_position();
		$wishlist_icon_position = isset( $position['wishlist_icon'] ) ? $position['wishlist_icon'] : '';

		if ( class_exists( 'YITH_WCWL' ) ) {
			$label_option = get_option( 'yith_wcwl_add_to_wishlist_text' );
		} else {
			$label_option = isset( $ciyashop_options['add_to_wishlist_text'] ) ? $ciyashop_options['add_to_wishlist_text'] : esc_html__( 'Add to Wishlist', 'ciyashop' );
			$cs_wishlist  = new Ciyashop_Wishlist();
			$_wishlist    = $cs_wishlist->get_wishlist();
			$product_id   = $product->get_id();

			if ( is_array( $_wishlist ) && $_wishlist && $product_id ) {
				if ( in_array( $product_id, $_wishlist ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
					$label_option = isset( $ciyashop_options['browse_wishlist_text'] ) ? $ciyashop_options['browse_wishlist_text'] : esc_html__( 'Browse Wishlist', 'ciyashop' );
				}
			}
		}
		$label_option = apply_filters( 'ciyashop_wcwl_add_to_wishlist_text', $label_option );

		if ( $wishlist_icon_position ) {
			?>
			<div class="product-action product-action-wishlist" data-toggle="tooltip" data-original-title="<?php echo esc_attr( $label_option ); ?>" data-placement="<?php echo esc_attr( $wishlist_icon_position ); ?>">
				<?php
				if ( class_exists( 'YITH_WCWL' ) ) {
					echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
				} else {
					echo do_shortcode( '[ciyashop_add_to_wishlist]' );
				}
				?>
			</div>
			<?php
		} else {
			?>
			<div class="product-action product-action-wishlist" data-toggle="tooltip" data-original-title="<?php echo esc_attr( $label_option ); ?>">
				<?php
				if ( class_exists( 'YITH_WCWL' ) ) {
					echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
				} else {
					echo do_shortcode( '[ciyashop_add_to_wishlist]' );
				}
				?>
			</div>
			<?php
		}
	}
}
