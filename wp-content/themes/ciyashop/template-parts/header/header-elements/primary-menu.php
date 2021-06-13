<?php
/**
 * Primary Mennu.
 *
 * @package CiyaShop
 */

?>
<nav id="site-navigation" class="main-navigation">
	<?php ciyashop_primary_nav_menu(); ?>
</nav>

<nav id="site-navigation-mobile">
	<?php
	if ( has_nav_menu( 'mobile_menu' ) ) {
		wp_nav_menu(
			array(
				'theme_location'  => 'mobile_menu',
				'menu_class'      => 'menu primary-menu-mobile',
				'menu_id'         => 'primary-menu-mobile',
				'container'       => false,
				'container_id'    => 'menu-wrap-primary-mobile',
				'container_class' => 'mobile-menu-wrap',
			)
		);
	} else {
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu(
				array(
					'theme_location'  => 'primary',
					'menu_class'      => 'menu primary-menu',
					'menu_id'         => 'primary-menu',
					'container'       => false,
					'container_id'    => 'menu-wrap-primary',
					'container_class' => 'menu-wrap',
				)
			);
		} else {
			wp_page_menu(
				array(
					'theme_location' => 'primary',
					'menu_id'        => false,
					'menu_class'     => 'menu primary-menu',
					'container'      => 'div',
					'before'         => '<ul id="primary-menu" class="menu primary-menu nav-menu">',
					'after'          => '</ul>',
					'walker'         => new CiyaShop_Page_Nav_Walker(),
				)
			);
		}
	}
	?>
</nav>
