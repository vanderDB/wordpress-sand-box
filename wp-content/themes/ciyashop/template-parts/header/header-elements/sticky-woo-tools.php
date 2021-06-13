<?php
/**
 * Sticky Woo tools.
 *
 * @package CiyaShop
 */

?>
<div class="woo-tools">
	<div class="woo-tools-wrapper">
		<ul class="woo-tools-actions">
			<?php
			/**
			 * Fires before sticky header WooTools.
			 *
			 * @visible true
			 */
			do_action( 'ciyashop_before_sticky_header_wootools' );

			/**
			 * Hook: ciyashop_sticky_header_wootools
			 *
			 * @Functions hooked in to ciyashop_sticky_header_wootools action
			 * @hooked ciyashop_sticky_header_wootools_cart                     - 10
			 * @hooked ciyashop_sticky_header_wootools_compare                  - 20
			 * @hooked ciyashop_sticky_header_wootools_wishlist                 - 30
			 *
			 * @visible true
			 */
			do_action( 'ciyashop_sticky_header_wootools' );

			/**
			 * Fires after sticky header WooTools.
			 *
			 * @visible true
			 */
			do_action( 'ciyashop_after_sticky_header_wootools' );
			?>
		</ul>
	</div>
</div>
