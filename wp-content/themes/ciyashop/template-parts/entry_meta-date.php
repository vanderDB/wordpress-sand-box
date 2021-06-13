<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Template part for displaying page content meta data.
 *
 * @package CiyaShop
 */

global $ciyashop_options;

$ciyashop_blog_layout = ( isset( $ciyashop_options['blog_layout'] ) ) ? $ciyashop_options['blog_layout'] : 'classic';

$byear = get_the_time( 'Y' );
$month = get_the_time( 'm' );
$day   = get_the_time( 'd' );

if ( 'timeline' === $ciyashop_blog_layout && ! is_single() && ! is_search() && ! is_archive() ) {
	return;
}

echo sprintf(
	'<div class="entry-meta-date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></div>',
	esc_url( get_day_link( $byear, $month, $day ) ),
	esc_attr( get_the_time() ),
	esc_attr( get_the_date( 'c' ) ),
	esc_attr( get_the_date( get_option( 'date_format' ) ) )
);

