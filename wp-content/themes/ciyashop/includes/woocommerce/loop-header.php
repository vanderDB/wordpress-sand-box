<?php
/**
 * Adds PGS Bestseller widget.
 *
 * @package CiyaShop
 * @version 1.0.0
 */

/**
 * **********************************************************************************
 * Loop Header
 * **********************************************************************************
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
add_action( 'woocommerce_archive_description', 'ciyashop_custom_loop_header', 20 );

add_action( 'ciyashop_loop_header', 'ciyashop_loop_active_filters', 10 );
add_action( 'ciyashop_loop_header', 'ciyashop_loop_filters', 20 );
add_action( 'ciyashop_loop_header', 'ciyashop_loop_tools', 30 );
add_action( 'ciyashop_before_loop_header', 'ciyashop_loop_tools_order', 10 );

/**
 * Ciyashop Loop tools
 */
function ciyashop_loop_tools_order() {
	global $ciyashop_options;
	if ( isset( $ciyashop_options['product_filter_type'] ) && 'default' === $ciyashop_options['product_filter_type'] ) {
		return;
	}

	remove_action( 'ciyashop_loop_header', 'ciyashop_loop_filters', 20 );
	remove_action( 'ciyashop_loop_header', 'ciyashop_loop_tools', 30 );
	add_action( 'ciyashop_loop_header', 'ciyashop_loop_filters', 30 );
	add_action( 'ciyashop_loop_header', 'ciyashop_loop_tools', 20 );
}

/**
 * Custom Loop header
 */
function ciyashop_custom_loop_header() {
	wc_get_template( 'loop/loop-header.php' );
}

/**
 * Ciyashop loop active
 */
function ciyashop_loop_active_filters() {
	wc_get_template( 'loop/loop-header-active-filters.php' );
}

add_action( 'ciyashop_loop_header', 'ciyashop_loop_filters', 20 );

/**
 * Loop Filters
 */
function ciyashop_loop_filters() {
	global $ciyashop_options;
	if ( isset( $ciyashop_options['show_product_filter'] ) && 'no' === $ciyashop_options['show_product_filter'] ) {
		return;
	}

	wc_get_template( 'loop/loop-header-filters.php' );
}

/**
 * Loop tools
 */
function ciyashop_loop_tools() {
	wc_get_template( 'loop/loop-header-tools.php' );
}


/**
 ***********************************************************************************
 * Active Filtes
 ***********************************************************************************
 */
add_action( 'ciyashop_loop_active_filters', 'ciyashop_loop_active_filters_content' );
/**
 * Ciyashop Loop avtive
 */
function ciyashop_loop_active_filters_content() {
	the_widget( 'PGS_Widget_Layered_Nav_Filters' );
}


/**
 ***********************************************************************************
 * Filtes
 ***********************************************************************************
 */
add_action( 'ciyashop_loop_filters', 'ciyashop_loop_filters_content' );
/**
 * Loop filters content
 */
function ciyashop_loop_filters_content() {
	global $ciyashop_options;

	$shop_filter_title = ( isset( $ciyashop_options['product_filter_title'] ) && ! empty( $ciyashop_options['product_filter_title'] ) ) ? $ciyashop_options['product_filter_title'] : '';

	if ( isset( $ciyashop_options['product_filter_type'] ) && 'widget' === $ciyashop_options['product_filter_type'] ) {
		?>
		<div class="pgs_widgets_shop-filters">
			<div class="row">
				<?php dynamic_sidebar( 'sidebar-product-shop-filters' ); ?>
			</div>
		</div>
		<?php
	} else {

		$shop_filters_widget_params = array(
			'title'             => $shop_filter_title,
			'show_in'           => 'shop_pg_content',
			'filter-attributes' => ( isset( $ciyashop_options['product_filters'] ) && ! empty( $ciyashop_options['product_filters'] ) ) ? $ciyashop_options['product_filters'] : array(),
		);

		the_widget( 'PGS_Shop_Filters_Widget', $shop_filters_widget_params );
	}
}

/**
 ***********************************************************************************
 *
 * Tools
 *
 ***********************************************************************************
 */
add_action( 'ciyashop_loop_tools', 'ciyashop_loop_tools_content', 5 );
add_action( 'ciyashop_loop_tools', 'woocommerce_show_filters', 15 );
add_action( 'ciyashop_loop_tools', 'ciyashop_show_shop_sidebar', 16 );
add_action( 'ciyashop_loop_tools', 'woocommerce_result_count', 20 );
add_action( 'ciyashop_loop_tools', 'ciyashop_products_tootls_start', 22 );
add_action( 'ciyashop_loop_tools', 'ciyashop_products_per_page_variations', 25 );
add_action( 'ciyashop_loop_tools', 'ciyashop_gridlist', 30 );
add_action( 'ciyashop_loop_tools', 'woocommerce_catalog_ordering', 40 );
add_action( 'ciyashop_loop_tools', 'ciyashop_products_tootls_end', 50 );

/**
 * Shop products tools start
 */
function ciyashop_products_tootls_start() {
	?>
	<div class="ciyashop-products-tools">
	<?php
}

/**
 * Shop products tools end
 */
function ciyashop_products_tootls_end() {
	?>
	</div>
	<?php
}

if ( ! function_exists( 'ciyashop_products_per_page_variations' ) ) {
	/**
	 * Shop page per page variations
	 */
	function ciyashop_products_per_page_variations() {
		global $ciyashop_options, $wp;

		$per_page_variations = isset( $ciyashop_options['products_per_page_variations'] ) ? $ciyashop_options['products_per_page_variations'] : '9,12,15';
		$per_page            = isset( $_GET['per_page'] ) ? (int) $_GET['per_page'] : '';
		if ( $per_page_variations ) {
			$per_page_variations_data = explode( ',', $per_page_variations );
			?>
			<div class="ciyashop-products-per-page">
				<span class="per-page-title"><?php esc_html_e( 'Show', 'ciyashop' ); ?></span>
					<?php
					$current_url = ( isset( $_SERVER['HTTPS'] ) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http' ) . '://' . sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) . esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated
					$pos         = strpos( $current_url, '/page' );

					if ( $pos ) {
						$page_removed = substr( $current_url, 0, $pos );
						$current_url  = str_replace( home_url( $wp->request ), $page_removed, $current_url );
					}

					foreach ( $per_page_variations_data as $per_page_variation ) {
						$variation_class = 'per-page-variation';
						if ( $per_page === (int) $per_page_variation ) {
							$variation_class .= ' active-variation';
						}
						?>
						<a rel="nofollow" href="<?php echo esc_url( add_query_arg( 'per_page', (int) $per_page_variation, $current_url ) ); ?>" class="<?php echo esc_attr( $variation_class ); ?>">
							<span><?php printf( '%s', -1 === (int) $per_page_variation ? esc_html__( 'All', 'ciyashop' ) : esc_html( (int) $per_page_variation ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</a>
						<span class="per-page-separator"></span>
						<?php
					}
					?>
			</div>
			<?php
		}
	}
}

/**
 * Show Shop Sidebar
 */
function ciyashop_show_shop_sidebar() {
	global $ciyashop_options;
	if ( function_exists( 'WC' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
		if ( ( ( isset( $ciyashop_options['off_canvas_shop_sidebar'] ) && $ciyashop_options['off_canvas_shop_sidebar'] ) || ( wp_is_mobile() && isset( $ciyashop_options['off_canvas_mobile_shop_sidebar'] ) && $ciyashop_options['off_canvas_mobile_shop_sidebar'] ) ) && ( isset( $ciyashop_options['shop_sidebar'] ) && 'no' !== $ciyashop_options['shop_sidebar'] ) ) {
			?>
			<div class="ciyashop-show-shop-sidebar">
				<a class="ciyashop-show-shop-btn"href="#"><i class="fas fa-bars" aria-hidden="true"></i>&nbsp<?php esc_html_e( 'Show sidebar', 'ciyashop' ); ?></a>
			</div>
			<?php
		}
	}
}

/**
 * Loop Tools content
 */
function ciyashop_loop_tools_content() {
	/**
	 * Hook: ciyashop_loop_tools_content.
	 *
	 * @visible true
	 */
	do_action( 'ciyashop_loop_tools_content' );
}

/**
 * Show filters
 */
function woocommerce_show_filters() {
	global $ciyashop_options;
	if ( isset( $ciyashop_options['show_product_filter'] ) && 'no' === $ciyashop_options['show_product_filter'] ) {
		return;
	}

	if ( ! isset( $ciyashop_options['show_product_filter_area_open'] ) || ( isset( $ciyashop_options['show_product_filter_area_open'] ) && 'yes' === $ciyashop_options['show_product_filter_area_open'] ) ) {
		return;
	}
	?>
	<div class="pgs-shop-filter-buttons">
		<label href="#" class="pgs-open-shop-filters"><?php esc_html_e( 'Filters :', 'ciyashop' ); ?></label>
		<label class="switch ">
			<input type="checkbox" class="default shop_filter_hide_show-btn" <?php echo ( isset( $_COOKIE['shop_filter_hide_show'] ) && 'shown' === $_COOKIE['shop_filter_hide_show'] ) ? 'checked' : ''; ?>>
			<span class="slider"></span>
		</label>
	</div>
	<?php
}

/**
 * Grid List
 */
function ciyashop_gridlist() {

	/**
	 * Hook: ciyashop_gridlist_content.
	 *
	 * @hooked ciyashop_gridlist_toggle_button - 10
	 * @hooked woocommerce_result_count        - 20
	 * @hooked woocommerce_catalog_ordering    - 30
	 * @hooked ciyashop_gridlist               - 40
	 * @hooked woocommerce_show_filters        - 50
	 *
	 * @visible true
	 */
	do_action( 'ciyashop_gridlist_content' );

}

add_action( 'ciyashop_gridlist_content', 'ciyashop_gridlist_toggle_button', 10 );
/**
 * Grid list toggle button
 */
function ciyashop_gridlist_toggle_button() {
	global $ciyashop_options;

	$pro_col_sel = ( isset( $ciyashop_options['pro-col-sel'] ) && ! empty( $ciyashop_options['pro-col-sel'] ) ) ? $ciyashop_options['pro-col-sel'] : '4';

	$grid_view = esc_html__( 'Grid view', 'ciyashop' );

	$list_view = esc_html__( 'List view', 'ciyashop' );

	if ( isset( $_COOKIE['gridlist_view'] ) ) {
		$gridlist_view = sanitize_text_field( wp_unslash( $_COOKIE['gridlist_view'] ) );
	} else {
		$gridlist_view = 'products-loop-column-' . $pro_col_sel;
	}

	ob_start();
	?>
	<div class="gridlist-toggle-wrap">
		<div class="gridlist-label-wrap">
			<span class="gridlist-toggle-label"><?php echo esc_html__( 'View :', 'ciyashop' ); ?></span>
		</div>
		<div class="gridlist-button-wrap">
			<div class="gridlist-toggle">
				<a href="#"  title="<?php echo esc_attr( $grid_view ); ?>" class="gridlist-button grid-2-column<?php echo esc_attr( ( 'products-loop-column-2' === $gridlist_view ) ? ' active' : '' ); ?>" data-grid='<?php echo esc_attr( wp_json_encode( array( 'column' => 2 ) ) ); ?>'>
					<svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19">
						<path id="Rectangle_1_copy_2" data-name="Rectangle 1 copy 2" class="cls-1" d="M0,0H8V8H0V0ZM11,0h8V8H11V0ZM0,11H8v8H0V11Zm11,0h8v8H11V11Z"/>
					</svg>
				</a>
				<a href="#"  title="<?php echo esc_attr( $grid_view ); ?>" class="gridlist-button grid-3-column<?php echo esc_attr( ( 'products-loop-column-3' === $gridlist_view ) ? ' active' : '' ); ?>" data-grid='<?php echo esc_attr( wp_json_encode( array( 'column' => 3 ) ) ); ?>'>
					<svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19">
						<path id="Rectangle_1_copy_4" data-name="Rectangle 1 copy 4" class="cls-1" d="M0,0H5V5H0V0ZM7,0h5V5H7V0Zm7,0h5V5H14V0ZM0,7H5v5H0V7ZM7,7h5v5H7V7Zm7,0h5v5H14V7ZM0,14H5v5H0V14Zm7,0h5v5H7V14Zm7,0h5v5H14V14Z"/>
					</svg>

				</a>
				<a href="#" title="<?php echo esc_attr( $grid_view ); ?>" class="gridlist-button grid-4-column<?php echo esc_attr( ( 'products-loop-column-4' === $gridlist_view ) ? ' active' : '' ); ?>" data-grid="<?php echo esc_attr( wp_json_encode( array( 'column' => 4 ) ) ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19">
						<path id="Rectangle_1_copy_7" data-name="Rectangle 1 copy 7" class="cls-1" d="M0,0H4V4H0V0ZM0,5H4V9H0V5ZM5,5H9V9H5V5Zm5,0h4V9H10V5Zm5,0h4V9H15V5Zm0-5h4V4H15V0ZM10,0h4V4H10V0ZM5,0H9V4H5V0ZM0,10H4v4H0V10Zm0,5H4v4H0V15Zm5-5H9v4H5V10Zm5,0h4v4H10V10Zm5,0h4v4H15V10ZM5,15H9v4H5V15Zm5,0h4v4H10V15Zm5,0h4v4H15V15Z"/>
					</svg>
				<?php
				if ( 5 === (int) $pro_col_sel ) {
					?>
					<a href="#" title="<?php echo esc_attr( $grid_view ); ?>" class="gridlist-button grid-5-column<?php echo esc_attr( ( 'products-loop-column-5' === $gridlist_view ) ? ' active' : '' ); ?>" data-grid="<?php echo esc_attr( wp_json_encode( array( 'column' => 5 ) ) ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19">
							<path id="Rectangle_1_copy_12" data-name="Rectangle 1 copy 12" class="cls-1" d="M0,0H3V3H0V0ZM0,4H3V7H0V4ZM0,8H3v3H0V8Zm0,4H3v3H0V12Zm0,4H3v3H0V16ZM4,0H7V3H4V0ZM4,4H7V7H4V4ZM4,8H7v3H4V8Zm0,4H7v3H4V12Zm0,4H7v3H4V16ZM8,0h3V3H8V0ZM8,4h3V7H8V4ZM8,8h3v3H8V8Zm0,4h3v3H8V12Zm0,4h3v3H8V16ZM12,0h3V3H12V0Zm0,4h3V7H12V4Zm0,4h3v3H12V8Zm0,4h3v3H12V12Zm0,4h3v3H12V16ZM16,0h3V3H16V0Zm0,4h3V7H16V4Zm0,4h3v3H16V8Zm0,4h3v3H16V12Zm0,4h3v3H16V16Z"/>
						</svg>

					</a>
					<?php
				}
				?>
				<a href="#" title="<?php echo esc_attr( $list_view ); ?>" class="gridlist-button gridlist-toggle-list<?php echo esc_attr( ( 'list' === $gridlist_view ) ? ' active' : '' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19">
						<path id="Rectangle_1_copy_2" data-name="Rectangle 1 copy 2" class="cls-1" d="M0,0H19V3H0V0ZM0,8H19v3H0V8Zm0,8H19v3H0V16Z"/>
					</svg>
				</a>
			</div>
		</div>
	</div>
	<?php
	$output                        = ob_get_clean();
	$allowd_html                   = ciyashop_allowed_html( array( 'div', 'span', 'a', 'i', 'em', 'svg' ) );
	$allowd_html['a']['data-grid'] = true;

	/**
	 * Filters output of Grid List toggle button.
	 *
	 * @param string    $button      Grid List toggle button output.
	 *
	 * @visible true
	 */
	$output = apply_filters( 'ciyashop_gridlist_toggle_button_output', $output, $grid_view, $list_view );
	echo wp_kses( $output, $allowd_html ); // variable/values escaped already.
}

add_action( 'init', 'ciyashop_product_search_redirection' );
/**
 * Product search redirection
 */
function ciyashop_product_search_redirection() {
	if ( isset( $_GET['filtering'] ) && 1 === (int) $_GET['filtering'] ) {

		$current_url = add_query_arg( array() );

		$current_url_prased = wp_parse_url( $current_url );
		$current_url_path   = ( isset( $current_url_prased['path'] ) ) ? $current_url_prased['path'] : '';
		$current_url_query  = ( isset( $current_url_prased['query'] ) ) ? $current_url_prased['query'] : '';

		$url_query_array_new = array();
		if ( ! empty( $current_url_query ) ) {
			$current_query_args = explode( '&', $current_url_query );
			foreach ( $current_query_args as $current_query_arg ) {
				if ( strstr( $current_query_arg, '=' ) !== false ) {
					$current_query_arg_parts                            = explode( '=', $current_query_arg );
					$url_query_array_new[ $current_query_arg_parts[0] ] = $current_query_arg_parts[1];
				}
			}
			$new_query_str = build_query( $url_query_array_new );
			unset( $url_query_array_new['filtering'] );
		}
		$new_url = add_query_arg( ( ( 'filtering=1' !== $new_query_str ) ? $url_query_array_new : array() ), $current_url_path );

		wp_safe_redirect( $new_url );
		exit;
	}
}
