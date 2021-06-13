<?php
/**
 * Template part for displaying post pagination.
 *
 * @package CiyaShop
 */

global $wp_query;

if ( $wp_query->max_num_pages <= 1 ) {
	return;
}
?>
<div class="row">
	<nav class="navigation pagination clearfix col-lg-12 col-md-12 text-center">
		<?php
		the_posts_pagination(
			array(
				'prev_text'          => '<span class="icon-arrow-left">&laquo;</span><span class="screen-reader-text">' . esc_html__( 'Previous page', 'ciyashop' ) . '</span>',
				'next_text'          => '<span class="screen-reader-text">' . esc_html__( 'Next page', 'ciyashop' ) . '</span><span class="icon-arrow-right">&raquo;</span>',
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'ciyashop' ) . ' </span>',
				'base'               => esc_url( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', wp_specialchars_decode( get_pagenum_link( 999999999 ) ) ) ) ),
				'format'             => '',
				'current'            => max( 1, get_query_var( 'paged' ) ),
				'total'              => $wp_query->max_num_pages,
				'type'               => 'list',
				'end_size'           => 3,
				'mid_size'           => 3,
			)
		);
		?>
	</nav>
</div>
