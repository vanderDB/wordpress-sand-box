<?php
/**
 * Product Deal Left Intro
 *
 * @package CiyaShop
 */

return array(
	'name'              => esc_html__( 'Product Deal Left Intro', 'ciyashop' ),
	'template_category' => esc_html__( 'Product', 'ciyashop' ),
	'disabled'          => true, // Disable it to not show in the default tab.
	'content'           => <<<CONTENT
[vc_row full_width="stretch_row" pgscore_background_position="right-center" pgscore_enable_responsive_settings="true" el_id="1513340535080-82f9d407-15c6" css=".vc_custom_1513833895219{padding-top: 80px !important;padding-bottom: 80px !important;background-position: 0 0 !important;background-repeat: no-repeat !important;}" element_css_md=".vc_custom_1513833895223{padding-top: 60px !important;padding-bottom: 60px !important;}" element_css_sm=".vc_custom_1513833895225{padding-top: 50px !important;padding-bottom: 50px !important;}" element_css_xs=".vc_custom_1513833895227{padding-top: 40px !important;padding-bottom: 40px !important;}"][vc_column css=".vc_custom_1505473144504{padding-top: 0px !important;}"][pgscore_product_deals intro_title_color="#ffffff" intro_description_color="#ffffff" product_per_page="10" intro_bg_color="#04d39f" intro_link_color="#ffffff" enable_intro_content="true" enable_intro_link="true" intro_title="Deal of the day" intro_description="Avail daily discounted offers, hot deals, promo codes & coupon's with CiyaShop's deal of the day" intro_link="url:%23|||"][/vc_column][/vc_row]
CONTENT
);
