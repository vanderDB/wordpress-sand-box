<?php
/**
 * Search file.
 *
 * @package CiyaShop
 */

/**
 * Fires before header search.
 *
 * @visible true
 */
do_action( 'ciyashop_before_header_search' );

/**
 * Hook: ciyashop_header_search
 *
 * @Functions hooked in to ciyashop_header_search action
 * @hooked ciyashop_header_search_content                - 10
 *
 * @visible true
 */
do_action( 'ciyashop_header_search' );

/**
 * Fires after header search.
 *
 * @visible true
 */
do_action( 'ciyashop_after_header_search' );
