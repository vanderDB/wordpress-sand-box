<?php
/**
 * Categories Menu.
 *
 * @package CiyaShop
 */

if ( has_nav_menu( 'categories_menu' ) ) {
	wp_nav_menu(
		array(
			'theme_location' => 'categories_menu',
			'menu_class'     => 'categories-menu',
			'menu_id'        => 'vertical-menu',
			'container'      => 'div',
		)
	);
}
