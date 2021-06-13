<?php
/**
 * Before loop header
 *
 * @package WooCommerce/Templates
 */

do_action( 'ciyashop_before_loop_header' ); ?>

<div class="loop-header">
	<div class="loop-header-wrapper">

		<?php
		/**
		 * Ciyashop_loop_header hook.
		 *
		 * @hooked ciyashop_loop_active_filters - 10
		 * @hooked ciyashop_loop_filters        - 20
		 * @hooked ciyashop_loop_tools          - 30
		 */
		do_action( 'ciyashop_loop_header' );
		?>

	</div>
</div>

<?php do_action( 'ciyashop_after_loop_header' ); ?>
