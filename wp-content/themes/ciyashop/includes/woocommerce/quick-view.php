<?php
/**
 * Product Loop
 *
 * @package Ciyashop
 */

/**
 * Single Title
 */
function ciyashop_woocommerce_template_single_title() {
	the_title( sprintf( '<h2 class="product_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
}
