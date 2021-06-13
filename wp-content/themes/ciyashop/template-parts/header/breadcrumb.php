<?php
/**
 * Breadcrum file.
 *
 * @package CiyaShop
 */

global $ciyashop_options;
if ( function_exists( 'bcn_display_list' ) && 1 === (int) $ciyashop_options['display_breadcrumb'] && ! is_front_page() ) {
	?>
	<div class="breadcrumb-wrapper"><!-- .breadcrumb-wrapper -->

		<div class="container"><!-- .container -->
			<div class="row">
				<div class="col-lg-12">

					<?php
					/**
					 * Fires before breadcrumb.
					 *
					 * @visible true
					 */
					do_action( 'ciyashop_before_breadcrumb' );
					?>

					<ul class="page-breadcrumb">
						<?php bcn_display_list(); ?>
					</ul>

					<?php
					/**
					 * Fires after breadcrumb.
					 *
					 * @visible true
					 */
					do_action( 'ciyashop_after_breadcrumb' );
					?>

				</div>
			</div>
		</div><!-- .container -->

	</div><!-- .breadcrumb-wrapper -->
	<?php
}
