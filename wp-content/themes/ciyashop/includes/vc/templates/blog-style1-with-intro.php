<?php
/**
 * Blog Style 1 With Intro
 *
 * @package CiyaShop
 */

return array(
	'name'              => esc_html__( 'Blog Style 1 With Intro', 'ciyashop' ),
	'template_category' => esc_html__( 'Blog', 'ciyashop' ),
	'disabled'          => true, // Disable it to not show in the default tab.
	'content'           => <<<CONTENT
[vc_row full_width="stretch_row_content" pgscore_enable_responsive_settings="true" el_id="1512650704699-bcb18cb4-e82c" css=".vc_custom_1545109363196{margin-top: 80px !important;margin-bottom: 80px !important;}" element_css_md=".vc_custom_1545109363197{margin-top: 60px !important;margin-bottom: 60px !important;}" element_css_sm=".vc_custom_1545109363198{margin-top: 50px !important;margin-bottom: 50px !important;}" element_css_xs=".vc_custom_1545109363199{margin-top: 40px !important;margin-bottom: 40px !important;}"][vc_column el_id="1545108879478-117a8011-47d8"][pgscore_recent_posts listing_type="carousel" intro_title_color="#ffffff" intro_description_color="#ffffff" intro_link_color="#ffffff" intro_link_position="with_controls" intro_content_alignment="left" intro_bg_type="color" intro_bg_color="#04d39f" carousel_items_xl="2" carousel_items_lg="2" posts_per_page="3" enable_intro="true" enable_intro_link="true" style="style-1" intro_title="Our Latest Post" intro_description="Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book." intro_link="url:%23|||"][/vc_column][/vc_row]
CONTENT
);
