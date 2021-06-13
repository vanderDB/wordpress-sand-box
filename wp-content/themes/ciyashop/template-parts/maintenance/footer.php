<?php
/**
 * The template for displaying the footer
 *
 * @package Ciyashop
 */

global $ciyashop_options;
?>
	</div><!-- #content .wrapper -->

	<footer id="colophon" class="mnt-footer" role="contentinfo">
		<?php get_template_part( 'template-parts/footer/copyright' ); ?>
	</footer><!-- #colophon -->

	<?php
	$back_to_top = isset( $ciyashop_options['back_to_top'] ) ? $ciyashop_options['back_to_top'] : true; // get the status of the side bar.
	if ( $back_to_top ) :
		?>
		<div id="back-to-top">
			<a class="top arrow" href="#top"><i class="fa fa-angle-up"></i></a>
		</div>
	<?php endif; ?>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
