<?php
/**
 * The template for displaying the breadcrumb
 *
 * @package Ciyashop
 */

global $ciyashop_options;

$page_id = get_the_ID();
if ( function_exists( 'is_shop' ) && is_shop() ) {
	$page_id = get_option( 'woocommerce_shop_page_id' );
} elseif ( is_home() && get_option( 'page_for_posts' ) ) {
	$page_id = get_option( 'page_for_posts' );
}

$breadcrumbs            = '';
$display_breadcrumb     = ( isset( $ciyashop_options['display_breadcrumb'] ) ) ? $ciyashop_options['display_breadcrumb'] : 0;
$hide_breadcrumb_mobile = ( isset( $ciyashop_options['hide_breadcrumb_mobile'] ) ) ? $ciyashop_options['hide_breadcrumb_mobile'] : 0;

/* Start particular page breadcrumb setting*/
$header_settings_source = get_post_meta( $page_id, 'header_settings_source', true );
if ( 'custom' === $header_settings_source ) {
	$display_breadcrumb     = get_post_meta( $page_id, 'display_breadcrumb', true );
	$hide_breadcrumb_mobile = get_post_meta( $page_id, 'display_breadcrumb_on_mobile', true );
}

/* End particular page breadcrumb setting*/
$breadcrumbs_class = '';
if ( 0 === (int) $hide_breadcrumb_mobile && ! empty( $hide_breadcrumb_mobile ) ) {
	$breadcrumbs_class = 'breadcrumbs-hide-mobile';
}

$show_on_front = get_option( 'show_on_front' );

if ( ( ! is_home() || ( is_home() && 'page' === $show_on_front ) ) && 1 === (int) $display_breadcrumb && ! is_404() ) {
	if ( function_exists( 'bcn_display_list' ) ) {
		?>
		<ul class="page-breadcrumb breadcrumbs <?php echo esc_attr( $breadcrumbs_class ); ?>" typeof="BreadcrumbList" vocab="http://schema.org/">
			<?php bcn_display_list(); ?>
		</ul>
		<?php
	} else {
		if ( function_exists( 'yoast_breadcrumb' ) && isset( $ciyashop_options['yoast_breadcrumb'] ) && $ciyashop_options['yoast_breadcrumb'] ) {
			yoast_breadcrumb( '<div class="yoast-breadcrumb">', '</div>' );
		} else {
			$breadcrumbs = ciyashop_breadcrumbs( $breadcrumbs_class );
			echo $breadcrumbs; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		}
	}
}
