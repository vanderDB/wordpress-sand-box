<?php
/**
 * Template part for displaying post pagination.
 *
 * @package CiyaShop
 */

?>
<form role="search" method="get" id="searchform" class="clearfix" action="<?php echo esc_url( home_url( '/' ) ); ?>" >
	<label class="screen-reader-text" for="s"><?php echo esc_html__( 'Search for:', 'ciyashop' ); ?></label>
	<input type="text" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" id="s" placeholder="<?php echo esc_attr__( 'Search the Site&hellip;', 'ciyashop' ); ?>" />
	<button class="search-button" value="<?php echo esc_attr_x( 'Search', 'submit button', 'ciyashop' ); ?>" type="submit"> <i class="fa fa-search"></i> </button>
</form>
