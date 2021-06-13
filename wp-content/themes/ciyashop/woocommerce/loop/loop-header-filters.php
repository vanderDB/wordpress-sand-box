<?php
/**
 * Loop header filter
 *
 * @package WooCommerce/Templates
 */

global $ciyashop_options;

$class[] = 'loop-header-filters';
if ( isset( $ciyashop_options['show_product_filter_area_open'] ) && 'no' === $ciyashop_options['show_product_filter_area_open'] ) {
	if ( ! isset( $_COOKIE['shop_filter_hide_show'] ) || ( isset( $_COOKIE['shop_filter_hide_show'] ) && 'shown' !== $_COOKIE['shop_filter_hide_show'] ) ) {
		$class[] = 'loop-header-filters-hide';
	}
}


$classes = implode( ' ', array_filter( array_unique( $class ) ) );
?>
<div class="<?php echo esc_attr( $classes ); ?>">

	<?php do_action( 'ciyashop_before_loop_filters_wrapper' ); ?>

	<div class="loop-header-filters-wrapper">

		<?php 
		if ( isset( $ciyashop_options['product_filter_type'] ) && 'widget' !== $ciyashop_options['product_filter_type'] ) {
			?>
			<form class="loop-header-filters-form" method="get">
			<?php
		}
		?>
		<div class="row">
			<div class="col">

				<?php do_action( 'ciyashop_before_loop_filters' ); ?>

				<?php
				/**
				 * Ciyashop_loop_filters hook.
				 *
				 * @hooked ciyashop_loop_filters_content - 10
				 */
				do_action( 'ciyashop_loop_filters' );
				?>

				<?php do_action( 'ciyashop_after_loop_filters' ); ?>

			</div>
		</div>

		<?php 
		if ( isset( $ciyashop_options['product_filter_type'] ) && 'widget' !== $ciyashop_options['product_filter_type'] ) {
			?>
			</form>
			<?php
		}
		?>
	</div>

	<?php do_action( 'ciyashop_after_loop_filters_wrapper' ); ?>

</div>
