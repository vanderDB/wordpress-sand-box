<?php
/**
 * Cross-sells
 *
 * This template can be overridden by copying it to ciyashop/woocommerce/cart/cross-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $cross_sells ) : ?>

	<div class="cross-sells">

		<?php
		$heading = apply_filters( 'woocommerce_product_cross_sells_products_heading', __( 'You may be interested in&hellip;', 'ciyashop' ) );

		if ( $heading ) :
			?>
			<h2><?php esc_html__( 'You may be interested in&hellip;', 'ciyashop' ); ?></h2>
			<?php
		endif;

		/**
		 * Hook: woocommerce_before_shop_loop.
		 *
		 * @hooked wc_print_notices - 10
		 * @hooked woocommerce_result_count - 20
		 * @hooked woocommerce_catalog_ordering - 30
		 */
		do_action( 'woocommerce_before_shop_loop' );

		woocommerce_product_loop_start();
		?>

			<?php foreach ( $cross_sells as $cross_sell ) : ?>

				<?php
				// @codingStandardsIgnoreStart

				$post_object = get_post( $cross_sell->get_id() );

				setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited, Squiz.PHP.DisallowMultipleAssignments.Found

				wc_get_template_part( 'content', 'product' );
				
				// @codingStandardsIgnoreEnd
				?>

			<?php endforeach; ?>

		<?php
		woocommerce_product_loop_end();

		/**
		* Hook: woocommerce_after_shop_loop.
		*
		* @hooked woocommerce_pagination - 10
		*/
		do_action( 'woocommerce_after_shop_loop' );
		?>

	</div>

	<?php
endif;

wp_reset_postdata();
