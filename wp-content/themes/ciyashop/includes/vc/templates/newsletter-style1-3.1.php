<?php
/**
 * Newsletter With Background Color
 *
 * @package CiyaShop
 */

return array(
	'name'              => esc_html__( 'Newsletter With Background Color', 'ciyashop' ),
	'template_category' => esc_html__( 'Newsletter', 'ciyashop' ),
	'disabled'          => true, // Disable it to not show in the default tab.
	'content'           => '<<<CONTENT
<p>[vc_row full_width="stretch_row" pgscore_bg_type="row-background-light" el_id="1533129653281-0a4363ab-cc98" css=".vc_custom_1534506361971{padding-top: 50px !important;padding-bottom: 50px !important;background-color: #5c8dff !important;}"][vc_column alignment="center" offset="vc_col-lg-offset-3 vc_col-lg-6" css=".vc_custom_1533728224198{padding-top: 0px !important;}"][pgscore_newsletter title="Sign up for our newsletter and get 10% off" mailchimp_id="MailChimp List Id" mailchimp_api_key="MailChimp API Key" element_css=".vc_custom_1534506246115{padding-top: 0px !important;}" newsletter_design="design-3"][/vc_column][/vc_row]</p>
CONTENT',
);
