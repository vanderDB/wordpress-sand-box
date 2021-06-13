<?php
/**
 * Copyright file.
 *
 * @package CiyaShop
 */

global $ciyashop_options;

$footer_builder_elements = ciyashop_get_custom_footer_data();

if ( $footer_builder_elements && isset( $footer_builder_elements['common_settings']['enable_copyright_footer'] ) && 'no' === $footer_builder_elements['common_settings']['enable_copyright_footer'] ) {
	return;
}

if ( isset( $ciyashop_options['enable_copyright_footer'] ) && 'no' === $ciyashop_options['enable_copyright_footer'] ) {
	return;
}
?>
<div class="site-info">
	<div class="footer-widget"><!-- .footer-widget -->
		<div class="container"><!-- .container -->
			<div class="row align-items-center">
				<div class="col-lg-6 col-md-6 pull-left">
					<?php
					if ( $footer_builder_elements && isset( $footer_builder_elements['common_settings']['footer_text_left'] ) ) {
						echo do_shortcode( $footer_builder_elements['common_settings']['footer_text_left'] );
					} else {
						if ( isset( $ciyashop_options['footer_text_left'] ) && $ciyashop_options['footer_text_left'] ) {
							echo do_shortcode( $ciyashop_options['footer_text_left'] );
						} else {
							if ( ! isset( $ciyashop_options['footer_text_left'] ) ) {
								ciyashop_footer_copyright();
							}
						}
					}
					?>
				</div>
				<div class="col-lg-6 col-md-6 pull-right">
					<div class="text-right">
						<?php
						if ( $footer_builder_elements && isset( $footer_builder_elements['common_settings']['footer_text_right'] ) ) {
							echo do_shortcode( $footer_builder_elements['common_settings']['footer_text_right'] );
						} else {
							if ( isset( $ciyashop_options['footer_text_right'] ) && ! empty( $ciyashop_options['footer_text_right'] ) ) {
								echo do_shortcode( $ciyashop_options['footer_text_right'] );
							} else {
								if ( ! isset( $ciyashop_options['footer_text_right'] ) ) {
									ciyashop_footer_credits();
								}
							}
						}
						?>
					</div>
				</div>
			</div>
		</div><!-- .container -->

	</div><!-- .footer-widget -->
</div><!-- .site-info -->
