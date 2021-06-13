<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Topbar Menu.
 *
 * @package CiyaShop
 */

if ( has_nav_menu( 'top_menu' ) ) {
	?>
	<div class="menu-top-menu-container">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'top_menu',
				'menu_class'     => 'top-menu list-inline',
				'menu_id'        => 'top-menu',
				'depth'          => 1,
			)
		);
		?>
	</div>
	<?php
}
