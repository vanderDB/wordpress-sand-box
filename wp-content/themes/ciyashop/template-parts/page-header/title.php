<?php
/**
 * The template for displaying the Title
 *
 * @package Ciyashop
 */

global $ciyashop_options, $ciyashop_title;

$page_id = get_the_ID();

if ( function_exists( 'is_shop' ) && is_shop() ) {
	$page_id = get_option( 'woocommerce_shop_page_id' );
}
$page_title = get_the_title();
$posttype   = get_post_type();

$show_on_front     = get_option( 'show_on_front' );
$page_on_front     = get_option( 'page_on_front' );
$page_for_posts_id = get_option( 'page_for_posts' );

if ( is_home() ) {
	if ( 'page' === $show_on_front ) {
		$page_for_posts_data = get_post( $page_for_posts_id );
		if ( isset( $page_for_posts_data->post_title ) ) {
			$page_title = $page_for_posts_data->post_title;
		}
	}
} elseif ( is_archive() ) {
	if ( function_exists( 'is_shop' ) && is_shop() && ! empty( $page_id ) ) {
		$page_title = get_the_title( $page_id );
	} elseif ( is_day() ) {
		/* translators: $s: date */
		$page_title = sprintf( esc_html__( 'Daily Archives: %s', 'ciyashop' ), '<span>' . get_the_date() . '</span>' );
	} elseif ( is_month() ) {
		/* translators: $s: monthly archives date format */
		$page_title = sprintf( esc_html__( 'Monthly Archives: %s', 'ciyashop' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'ciyashop' ) ) . '</span>' );
	} elseif ( is_year() ) {
		/* translators: $s: yearly archives date format */
		$page_title = sprintf( esc_html__( 'Yearly Archives: %s', 'ciyashop' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'ciyashop' ) ) . '</span>' );
	} elseif ( is_category() ) {
		/* translators: $s: category title */
		$page_title = sprintf( esc_html__( 'Category Archives: %s', 'ciyashop' ), '<span>' . single_cat_title( '', false ) . '</span>' );
	} elseif ( is_tax() ) {
		$page_title = single_cat_title( '', false );
	} elseif ( is_tag() ) {
		/* translators: $s: tag title */
		$page_title = sprintf( esc_html__( 'Tag Archives: %s', 'ciyashop' ), '<span>' . single_tag_title( '', false ) . '</span>' );
	} elseif ( is_author() ) {
		/* translators: $s: author name */
		$page_title = sprintf( esc_html__( 'Author Archives: %s', 'ciyashop' ), '<span>' . get_the_author() . '</span>' );
	} elseif ( is_archive() && 'post' === get_post_type() ) {
		$page_title = esc_html__( 'Archives', 'ciyashop' );
	} else {
		$page_title = post_type_archive_title( '', false );
	}
} elseif ( is_search() ) {
	$page_title = esc_html__( 'Search', 'ciyashop' );
} elseif ( is_404() ) {

	if ( isset( $ciyashop_options['fourofour_page_title_source'] ) && 'custom' === $ciyashop_options['fourofour_page_title_source'] && isset( $ciyashop_options['fourofour_page_title'] ) && ! empty( $ciyashop_options['fourofour_page_title'] ) ) {
		$page_title = $ciyashop_options['fourofour_page_title'];
	} else {
		$page_title = esc_html__( '404 error', 'ciyashop' );
	}
} elseif ( function_exists( 'dokan_is_store_page' ) && dokan_is_store_page() ) {
	$store_user = dokan()->vendor->get( get_query_var( 'author' ) );
	$page_title = $store_user->get_shop_name();
} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
	$page_title = get_the_title( $page_id );
}

/**
 * Filters the header title for the current post or page.
 *
 * With the help of this filter can change the title of any post, page or any other archive page.
 *
 * @param string $page_title Header title.
 *
 * @visible true
 */
$ciyashop_title = apply_filters( 'ciyashop_title', $page_title );
?>

<div class="intro-title-inner">
	<h1><?php echo html_entity_decode( esc_html( $ciyashop_title ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h1>
</div>
