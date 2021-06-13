<div class="loop-header-tools">

	<?php
	/**
	 * Before loop tools wrapper
	 *
	 * @package WooCommerce/Templates
	 */

	do_action( 'ciyashop_before_loop_tools_wrapper' ); ?>

	<div class="loop-header-tools-wrapper">
		<div class="row">
			<div class="col">

				<?php do_action( 'ciyashop_before_loop_tools' ); ?>

				<?php
				/**
				 * Ciyashop_loop_tools hook.
				 *
				 * @hooked ciyashop_loop_tools_content - 10
				 * @hooked woocommerce_result_count      - 20
				 * @hooked ciyashop_gridlist           - 30
				 * @hooked woocommerce_catalog_ordering  - 40
				 */
				do_action( 'ciyashop_loop_tools' );
				?>

				<?php do_action( 'ciyashop_after_loop_tools' ); ?>

			</div>
		</div>
	</div>

	<?php do_action( 'ciyashop_after_loop_tools_wrapper' ); ?>

</div>
