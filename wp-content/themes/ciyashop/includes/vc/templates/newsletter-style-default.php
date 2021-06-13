<?php
/**
 * Newsletter Style Default
 *
 * @package CiyaShop
 */

return array(
	'name'              => esc_html__( 'Newsletter Style Default', 'ciyashop' ),
	'template_category' => esc_html__( 'Newsletter', 'ciyashop' ),
	'disabled'          => true, // Disable it to not show in the default tab.
	'content'           => '<<<CONTENT
<p>[vc_row full_width="stretch_row" el_id="1533301058521-cebd6f76-e1ea" css=".vc_custom_1533355706742{padding-top: 10px !important;padding-bottom: 40px !important;background: #f7f4f1 url(http://ciyashop.potenzaglobalsolutions.com/leather/wp-content/uploads/sites/28/2018/08/bg-01.png?id=491) !important;}"][vc_column el_class="text-center" offset="vc_col-lg-offset-3 vc_col-lg-6 vc_col-md-offset-2 vc_col-md-8"][vc_custom_heading text="Sign up for our newsletter and get 10% off" font_container="tag:h4|text_align:center|color:%23966b3a" use_theme_fonts="yes" css=".vc_custom_1533355081465{margin-bottom: 20px !important;}"][pgscore_newsletter mailchimp_id="MailChimp List Id" mailchimp_api_key="MailChimp API Key"][/vc_column][/vc_row]</p>
CONTENT',
);
