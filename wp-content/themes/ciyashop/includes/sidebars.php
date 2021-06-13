<?php
/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 * @package CiyaShop
 */

/**
 * Sidebars init
 */
function ciyashop_sidebars_init() {
	global $ciyashop_options;
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'ciyashop' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'ciyashop' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Shop/Product Listing Sidebar', 'ciyashop' ),
			'id'            => 'sidebar-shop',
			'description'   => esc_html__( 'Add widgets here to appear on product list page.', 'ciyashop' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Product Details Sidebar', 'ciyashop' ),
			'id'            => 'sidebar-product',
			'description'   => esc_html__( 'Add widgets here to appear on product details page.', 'ciyashop' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Product Shop Filters', 'ciyashop' ),
			'id'            => 'sidebar-product-shop-filters',
			'description'   => esc_html__( 'Add widgets here to appear on product shop filters. "Pgs Shop Filter" widget will not work here, if you want to use it simply just switch to Filter Type Default in ( Theme Options > Products Filters > Filter Type )', 'ciyashop' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s col-lg-3 col-md-6 col-sm-6">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);

	/* footer sidebar */
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 1', 'ciyashop' ),
			'id'            => 'sidebar-footer-1',
			'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'ciyashop' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title footer-title title">',
			'after_title'   => '</h4>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 2', 'ciyashop' ),
			'id'            => 'sidebar-footer-2',
			'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'ciyashop' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title footer-title title">',
			'after_title'   => '</h4>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 3', 'ciyashop' ),
			'id'            => 'sidebar-footer-3',
			'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'ciyashop' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title footer-title title">',
			'after_title'   => '</h4>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 4', 'ciyashop' ),
			'id'            => 'sidebar-footer-4',
			'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'ciyashop' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title footer-title title">',
			'after_title'   => '</h4>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 5', 'ciyashop' ),
			'id'            => 'sidebar-footer-5',
			'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'ciyashop' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title footer-title title">',
			'after_title'   => '</h4>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 6', 'ciyashop' ),
			'id'            => 'sidebar-footer-6',
			'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'ciyashop' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title footer-title title">',
			'after_title'   => '</h4>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 7', 'ciyashop' ),
			'id'            => 'sidebar-footer-7',
			'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'ciyashop' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title footer-title title">',
			'after_title'   => '</h4>',
		)
	);
}
