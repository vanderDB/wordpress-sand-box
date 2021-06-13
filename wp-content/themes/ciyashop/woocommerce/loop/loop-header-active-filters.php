<div class="loop-header-active-filters">

	<?php
	/**
	 * Ciyashop before loop active filters wrapper
	 *
	 * @package WooCommerce/Templates
	 */

	do_action( 'ciyashop_before_loop_active_filters_wrapper' ); ?>

	<div class="loop-header-active-filters-wrapper">
		<div class="row">
			<div class="col">
				<?php do_action( 'ciyashop_before_loop_active_filters' ); ?>

				<?php
				/**
				 * Ciyashop_loop_active_filters hook.
				 *
				 * @hooked ciyashop_loop_active_filters_content - 10
				 */
				do_action( 'ciyashop_loop_active_filters' );
				?>

				<?php do_action( 'ciyashop_after_loop_active_filters' ); ?>

				<?php do_action( 'ciyashop_before_active_filters_widgets' ); ?>
			</div>
		</div>
	</div>

	<?php do_action( 'ciyashop_after_loop_active_filters_wrapper' ); ?>

</div>
