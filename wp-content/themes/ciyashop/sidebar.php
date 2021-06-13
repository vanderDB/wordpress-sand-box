<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package CiyaShop
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}

if ( ! is_home() && ( is_front_page() && ciyashop_is_vc_enabled() ) ) {
	return;
}

if ( 'static_block' === get_post_type() ) {
	return;
}

/**
 * Filters active plugins.
 *
 * @param array $active_plugins List of active plugins.
 *
 * @visible false
 * @ignore
 */
if ( function_exists( 'WC' ) && ( is_checkout() || is_cart() || is_account_page() ) ) {
	return;
}

global $ciyashop_options, $post;

$custom_sidebar      = '';
$blog_sidebar        = ( isset( $ciyashop_options['blog_sidebar'] ) && $ciyashop_options['blog_sidebar'] ) ? $ciyashop_options['blog_sidebar'] : 'right_sidebar';
$portfolio_sidebar   = ( isset( $ciyashop_options['portfolio_sidebar'] ) && $ciyashop_options['portfolio_sidebar'] ) ? $ciyashop_options['portfolio_sidebar'] : 'full_width';
$search_page_sidebar = ( isset( $ciyashop_options['search_page_sidebar'] ) && $ciyashop_options['search_page_sidebar'] ) ? $ciyashop_options['search_page_sidebar'] : 'right_sidebar';
$page_sidebar        = ( isset( $ciyashop_options['page_sidebar'] ) && $ciyashop_options['page_sidebar'] ) ? $ciyashop_options['page_sidebar'] : 'right_sidebar';
$cs_wishlist_page    = ( isset( $ciyashop_options['cs_wishlist_page'] ) ) ? $ciyashop_options['cs_wishlist_page'] : '';

if ( get_the_ID() === (int) $cs_wishlist_page ) {
	$page_sidebar = 'full_width';
}

// Check if page sidebar settings for perticular page.
if ( get_post_meta( get_the_ID(), 'page_sidebar', true ) !== 'default' && get_post_meta( get_the_ID(), 'page_sidebar', true ) ) {
	$page_sidebar = get_post_meta( get_the_ID(), 'page_sidebar', true );
}

if ( ( is_page() && 'full_width' === $page_sidebar ) || ( is_search() && 'full_width' === $search_page_sidebar ) ) {
	return;
}

if (
	( ( is_home() || is_archive() || is_single() ) && 'left_sidebar' === $blog_sidebar )
	|| ( is_post_type_archive( 'portfolio' ) && 'left_sidebar' === $portfolio_sidebar )
	|| ( is_page() && 'left_sidebar' === $page_sidebar )
	|| ( is_search() && 'left_sidebar' === $search_page_sidebar )
) {
	$sidebar_classes = 'sidebar col-sm-12 col-md-12 col-lg-4 col-xl-3 order-xl-1 order-lg-1 column';

} else {
	$sidebar_classes = 'sidebar col-sm-12 col-md-12 col-lg-4 col-xl-3 column';
}

if ( isset( $post->ID ) && metadata_exists( 'post', $post->ID, 'pgs_custom_sidebar' ) ) {
	$custom_sidebar = get_post_meta( $post->ID, 'pgs_custom_sidebar', true );
}
?>
<div class="<?php echo esc_attr( $sidebar_classes ); ?>">
	<aside id="secondary" class="widget-area" role="complementary">
		<?php
		if ( ! empty( $custom_sidebar ) ) {
			dynamic_sidebar( $custom_sidebar );
		} else {
			dynamic_sidebar( 'sidebar-1' );
		}
		?>
	</aside><!-- #secondary -->
</div>
