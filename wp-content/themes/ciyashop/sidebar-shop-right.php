<?php
/**
 * The sidebar containing the shop widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package CiyaShop
 */

global $ciyashop_options;

if ( is_product() ) {
	$sidebar_layout = ciyashop_product_page_sidebar();
	$sidebar_class  = ciyashop_product_sidebar_class();
	$sidebar_id     = 'sidebar-product';

	if ( is_active_sidebar( $sidebar_id ) && 'no' !== $sidebar_layout ) {
		?>
		<aside id="<?php echo esc_attr( $sidebar_layout ); ?>" class="<?php echo esc_attr( $sidebar_class ); ?>">
			<?php dynamic_sidebar( $sidebar_id ); ?>
		</aside>
		<?php
	}
} else {
	global $ciyashop_options;

	$sidebar_layout = ciyashop_shop_page_sidebar();
	$sidebar_class  = ciyashop_shop_sidebar_class( 'sidebar' );
	$sidebar_id     = 'sidebar-shop';

	$show_sidebar_on_mobile  = ciyashop_show_sidebar_on_mobile();
	$mobile_sidebar_position = ciyashop_mobile_sidebar_position();

	if (
		( wp_is_mobile() && is_active_sidebar( $sidebar_id ) && 'no' !== $sidebar_layout && 'bottom' === $mobile_sidebar_position && $show_sidebar_on_mobile )
		||
		( ! wp_is_mobile() && is_active_sidebar( $sidebar_id ) && 'no' !== $sidebar_layout && 'bottom' === $mobile_sidebar_position )
	) {
		?>
		<div class="shop-sidebar-widgets-overlay"></div>
		<aside id="<?php echo esc_attr( $sidebar_layout ); ?>" class="<?php echo esc_attr( $sidebar_class ); ?>">
			<div class="shop-sidebar-widgets">
			<?php
			if ( ( is_shop() || is_product_category() || is_product_tag() ) && ( ( wp_is_mobile() && isset( $ciyashop_options['off_canvas_mobile_shop_sidebar'] ) && $ciyashop_options['off_canvas_mobile_shop_sidebar'] ) || isset( $ciyashop_options['off_canvas_shop_sidebar'] ) && $ciyashop_options['off_canvas_shop_sidebar'] ) ) {
				?>
				<div class="sidebar-widget-heading">
					<a href="#" class="close-sidebar-widget"><?php esc_html_e( 'Close', 'ciyashop' ); ?></a>
				</div>
				<?php
			}
			dynamic_sidebar( $sidebar_id );
			?>
			</div>
		</aside>
		<?php
	}
}
