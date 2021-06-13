<?php
/**
 * Init File
 *
 * @package Ciyashop
 */

/**
 * Include require files
 */
require_once get_parent_theme_file_path( '/includes/woocommerce/wc-functions.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
require_once get_parent_theme_file_path( '/includes/woocommerce/wc-functions-variations.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

add_filter( 'ciyashop_supported_shortcodes', 'ciyashop_add_woocommerce_supported_shortcodes' );

/**
 * Woocommerce supported shortcode.
 *
 * @param array $shortcodes shortcode name.
 * @return $shortcodes
 */
function ciyashop_add_woocommerce_supported_shortcodes( $shortcodes ) {

	if ( class_exists( 'WooCommerce' ) ) {
		$shortcodes = array_merge(
			$shortcodes,
			array(
				'pgscore_categorybox',
				'pgscore_single_product_slider',
				'pgscore_product_deal',
				'pgscore_product_deals',
				'pgscore_multi_tab_products_listing',
				'pgscore_products_listing',
				'pgscore_product_showcase',
			)
		);
	}

	return $shortcodes;
}

/**
 * Quick ajax call
 *
 * @return void
 */
function ciyashop_quick_view() {

	if ( isset( $_GET['id'] ) ) {
		$id = (int) $_GET['id'];
	}

	if ( ! $id ) {
		return;
	}

	$ciyashop_nonce = isset( $_GET['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['ajax_nonce'] ) ) : '';

	if ( ! wp_verify_nonce( $ciyashop_nonce, 'ciyashop_nonce' ) ) {
		return;
	}

	global $post, $ciyashop_options, $sitepress;

	$lang = isset( $_GET['lang'] ) ? sanitize_text_field( wp_unslash( $_GET['lang'] ) ) : '';

	if ( defined( 'ICL_LANGUAGE_CODE' ) && $lang && isset( $sitepress ) ) {
		$sitepress->switch_lang( $lang, true );
	}

	$args = array(
		'post__in'  => array( $id ),
		'post_type' => 'product',
	);

	$quick_posts = get_posts( $args );

	$quick_view_variable = $ciyashop_options['quick_view'];

	if ( ! empty( $quick_posts ) ) {
		foreach ( $quick_posts as $post ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

			setup_postdata( $post, $sitepress );
			remove_action( 'woocommerce_before_single_product', 'wc_print_notices', 10 );
			remove_action( 'woocommerce_single_product_summary', 'ciyashop_product_size_guide', 25 );

			if ( ! $ciyashop_options['quick_view_product_name'] ) {
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
			}
			if ( $ciyashop_options['quick_view_product_name'] && $ciyashop_options['quick_view_product_link'] ) {
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
				add_action( 'woocommerce_single_product_summary', 'ciyashop_woocommerce_template_single_title', 5 );
			}
			if ( ! $ciyashop_options['quick_view_product_categories'] ) {
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 29 );
			}
			if ( ! $ciyashop_options['quick_view_product_price'] ) {
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
			}
			if ( ! $ciyashop_options['quick_view_product_star_rating'] ) {
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
			}
			if ( ! $ciyashop_options['quick_view_product_short_description'] ) {
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
			}
			if ( ! $ciyashop_options['quick_view_product_add_to_cart'] ) {
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			}
			if ( ! $ciyashop_options['quick_view_product_share_icon'] ) {
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
			}
			// Disable add to cart button for catalog mode.
			if ( $ciyashop_options['woocommerce_catalog_mode'] ) {
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			} elseif ( ! $quick_view_variable ) {
				// If no needs to show variations.
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
				add_action( 'woocommerce_single_product_summary', 'woocommerce_template_loop_add_to_cart', 30 );
			}

			remove_action( 'woocommerce_single_product_summary', 'ciyashop_product_sale_countdown', 41 );
			remove_action( 'woocommerce_after_shop_loop_item', 'ciyashop_product_sale_countdown', 25 );

			get_template_part( 'woocommerce/content', 'quick-view' );
		endforeach;

		wp_reset_postdata();
	}

	wp_die();
}

add_action( 'wp_ajax_ciyashop_quick_view', 'ciyashop_quick_view' );
add_action( 'wp_ajax_nopriv_ciyashop_quick_view', 'ciyashop_quick_view' );

/**
 * Product Share
 *
 * @return void
 */
function ciyashop_woocommerce_share() {
	global $ciyashop_options;
	
	$product_share_buttons = isset( $ciyashop_options['product-share-buttons'] ) ? $ciyashop_options['product-share-buttons'] : '';

	if ( ! $product_share_buttons ) {
		return;
	}
	$social_icons = '';
	ob_start();
	ciyashop_social_share();
	$social_icons = ob_get_contents();
	ob_end_clean();
	if ( ! empty( $social_icons ) ) {
		?>
		<div class="pgs-social-share-wrapper share-wrapper social-profiles">
			<span class="share-label"><?php esc_html_e( 'Share :', 'ciyashop' ); ?></span>
			<?php
			ciyashop_social_share(
				array(
					'class' => 'share-links',
				)
			);
			?>
		</div>
		<?php
	}
}
add_action( 'woocommerce_share', 'ciyashop_woocommerce_share', 10, 0 );

// Registration Info.
add_action( 'woocommerce_register_form_start', 'ciyashop_register_form_start' );
/**
 * Set the registration info text on woocommerce register page
 *
 * @return void
 */
function ciyashop_register_form_start() {
	global $ciyashop_options;

	if ( $ciyashop_options['enable_registration_text'] && ! empty( $ciyashop_options['registration_text'] ) ) {
		?>
		<div class="woo-registration-info">
		<?php echo do_shortcode( $ciyashop_options['registration_text'] ); ?>
		</div>
		<?php
	}
}


add_filter( 'yith_wcwl_button_icon', 'ciyashopyith_wcwl_button_icon' );
add_filter( 'yith_wcwl_button_added_icon', 'ciyashopyith_wcwl_button_icon' );

/**
 * Remove wishlist icon from plugin
 *
 * @param string $icon_option icon option name.
 * @return $icon_option
 */
function ciyashopyith_wcwl_button_icon( $icon_option ) {
	$icon_option = false;

	return $icon_option;
}

