<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */

$tabs = apply_filters( 'woocommerce_product_tabs', array() ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride

if ( ! empty( $tabs ) ) : ?>

	<div class="woocommerce-tabs wc-tabs-wrapper <?php echo esc_attr( "woocommerce-tabs-layout-$tab_layout" ); ?>">
		<div id="accordion" role="tablist" aria-multiselectable="true">
			<?php
			$wc_tabs_sr = 1;
			foreach ( $tabs as $key => $tab ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride
				?>
				<div class="card">
					<div class="card-header" role="tab" id="headingOne">
						<h5 class="mb-0">
							<a class="<?php echo esc_attr( 1 !== (int) $wc_tabs_sr ? 'collapsed' : '' ); ?>" data-toggle="collapse" data-parent="#accordion" href="#tab-<?php echo esc_attr( $key ); ?>" aria-expanded="true" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
								<?php echo esc_html( apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ) ); ?>
							</a>
						</h5>
					</div>
					<div id="tab-<?php echo esc_attr( $key ); ?>" class="collapse<?php echo esc_attr( 1 === (int) $wc_tabs_sr ? ' show' : '' ); ?>" role="tabpanel" aria-labelledby="headingOne">
						<div class="card-block">
							<?php call_user_func( $tab['callback'], $key, $tab ); ?>
						</div>
					</div>
				</div>
				<?php
				$wc_tabs_sr++;
			}
			?>
		</div>
	</div>

<?php endif; ?>
