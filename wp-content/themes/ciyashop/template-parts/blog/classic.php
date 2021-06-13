<?php
/**
 * Template part for displaying blog content.
 *
 * @package CiyaShop
 */

global $ciyashop_blog_layout;

$layout_sr = 0;
// Start the loop.
while ( have_posts() ) {
	the_post();

	$layout_sr++;
	get_template_part( "template-parts/blog/$ciyashop_blog_layout/content", get_post_format() );
}
wp_reset_postdata();
// End the loop.
