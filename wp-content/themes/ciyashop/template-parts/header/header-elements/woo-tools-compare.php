<?php
/**
 * Woo tools Compare.
 *
 * @package CiyaShop
 */

global $yith_woocompare, $ciyashop_options;

if ( $yith_woocompare ) {
	?>
	<li class="woo-tools-action woo-tools-compare">
		<a href="<?php echo esc_url( add_query_arg( array( 'iframe' => 'true' ), $yith_woocompare->obj->view_table_url() ) ); ?>" class="compare" rel="nofollow">
			<?php ciyashop_compare_icon(); ?>
		</a>
	</li>
	<?php
} else {
	if ( isset( $ciyashop_options['show_compare'] ) && $ciyashop_options['show_compare'] ) {
		?>
		<li class="woo-tools-action woo-tools-compare ciyashop-tools-compare">
			<a href="#" class="compare ciyashop-compare" rel="nofollow">
				<?php ciyashop_compare_icon(); ?>
			</a>
		</li>
		<?php
	}
}
