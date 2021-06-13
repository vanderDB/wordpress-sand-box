<?php
/**
 * Newsletter With Custom Heading
 *
 * @package CiyaShop
 */

return array(
	'name'              => esc_html__( 'Newsletter With Custom Heading', 'ciyashop' ),
	'template_category' => esc_html__( 'Newsletter', 'ciyashop' ),
	'disabled'          => true, // Disable it to not show in the default tab.
	'content'           => '<<<CONTENT
<p>[vc_row pgscore_enable_responsive_settings="true" el_id="1533643581461-2b16ad1a-63c2" css=".vc_custom_1534759630934{margin-right: 0px !important;margin-left: 0px !important;padding-top: 40px !important;padding-bottom: 40px !important;background-image: url(http://ciyashop.potenzaglobalsolutions.com/fashion-vintage/wp-content/uploads/sites/29/2018/08/newsltter-bg.jpg?id=8711) !important;background-position: center !important;background-repeat: no-repeat !important;background-size: cover !important;}"][vc_column width="5/6" offset="vc_col-lg-offset-3 vc_col-lg-6 vc_col-md-offset-2 vc_col-md-8 vc_col-sm-offset-1" css=".vc_custom_1533643962595{padding-top: 0px !important;}" el_class="text-center"][vc_custom_heading text="Sign up for our newsletter and get 10% off" font_container="tag:h3|text_align:center" use_theme_fonts="yes" css=".vc_custom_1533644083379{margin-bottom: 20px !important;}"][pgscore_newsletter mailchimp_id="admin" mailchimp_api_key="admin" newsletter_design="design-6"][/vc_column][/vc_row]</p>
CONTENT',
);
