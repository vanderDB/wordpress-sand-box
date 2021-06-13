<?php
/**
 * Blog Style 2
 *
 * @package CiyaShop
 */

return array(
	'name'              => esc_html__( 'Blog Style 2', 'ciyashop' ),
	'template_category' => esc_html__( 'Blog', 'ciyashop' ),
	'disabled'          => true, // Disable it to not show in the default tab.
	'content'           => <<<CONTENT
[vc_row pgscore_enable_responsive_settings="true" el_id="1512650704699-bcb18cb4-e82c" css=".vc_custom_1524121806850{margin-top: 80px !important;margin-bottom: 80px !important;}" element_css_md=".vc_custom_1524121806857{margin-top: 60px !important;margin-bottom: 60px !important;}" element_css_sm=".vc_custom_1524121806860{margin-top: 50px !important;margin-bottom: 50px !important;}" element_css_xs=".vc_custom_1524121806864{margin-top: 40px !important;margin-bottom: 40px !important;}"][vc_column][vc_row_inner][vc_column_inner offset="vc_col-lg-offset-2 vc_col-lg-8 vc_col-md-offset-1 vc_col-md-10"][vc_custom_heading text="Our Latest Blog" font_container="tag:h3|text_align:center" use_theme_fonts="yes" font_weight="700" text_transform="uppercase" letter_spacing="normal" css=".vc_custom_1512653812580{margin-bottom: 20px !important;}"][vc_custom_heading text="Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book." font_container="tag:p|text_align:center" use_theme_fonts="yes" css=".vc_custom_1524121470207{margin-bottom: 25px !important;}"][/vc_column_inner][/vc_row_inner][pgscore_recent_posts style="style-2" listing_type="carousel" carousel_items_xl="3" posts_per_page="3"][/vc_column][/vc_row]
CONTENT
);
