<?php
/**
 * Customer Reviews
 *
 * @package CiyaShop
 */

return array(
	'name'              => esc_html__( 'Customer Reviews', 'ciyashop' ),
	'template_category' => esc_html__( 'Testimonial', 'ciyashop' ),
	'disabled'          => true, // Disable it to not show in the default tab.
	'content'           => '<<<CONTENT
[vc_row pgscore_enable_responsive_settings="true" el_id="1531384189414-4cc2d525-25dd" css=".vc_custom_1545452479332{padding-top: 80px !important;padding-bottom: 80px !important;}" element_css_md=".vc_custom_1545452479335{padding-top: 60px !important;padding-bottom: 60px !important;}" element_css_sm=".vc_custom_1545452479339{padding-top: 50px !important;padding-bottom: 50px !important;}" element_css_xs=".vc_custom_1545452479342{padding-top: 40px !important;padding-bottom: 40px !important;}"][vc_column offset="vc_col-lg-offset-1 vc_col-lg-10 vc_col-md-offset-1 vc_col-md-10"][vc_custom_heading text="Customer Reviews" font_container="tag:h2|text_align:center" use_theme_fonts="yes" css=".vc_custom_1531467769188{margin-bottom: 30px !important;padding-top: 0px !important;}"][pgscore_testimonials style="style-6" carousel_speed="5000" show_prev_next_buttons="true" enable_infinity_loop="true"][/vc_column][/vc_row]
CONTENT',
);
