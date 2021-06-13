<?php
/**
 * Search Form.
 *
 * @package CiyaShop
 */

global $ciyashop_options, $header_el_search_form_index;

if ( empty( $header_el_search_form_index ) ) {
	$header_el_search_form_index = 0;
}
$header_el_search_form_index++;
$search_form_id = 'header-el-search-' . $header_el_search_form_index;

$search_placeholder_text = ( isset( $ciyashop_options['search_placeholder_text'] ) && ! empty( $ciyashop_options['search_placeholder_text'] ) ) ? $ciyashop_options['search_placeholder_text'] : esc_html__( 'Enter Search Keyword...', 'ciyashop' );
$search_form_classes     = array();

$search_form_classes[] = 'search_form-inner';

if ( isset( $ciyashop_options['search_background_type'] ) && $ciyashop_options['search_background_type'] ) {
	$search_form_classes[] = $ciyashop_options['search_background_type'];
}

$search_form_classes = implode( ' ', $search_form_classes );
$search_content_type = ( isset( $ciyashop_options['search_content_type'] ) ) ? $ciyashop_options['search_content_type'] : '';
?>
<div class="search_form-wrap">
	<div class="<?php echo esc_attr( $search_form_classes ); ?>">
		<form class="search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php
			if ( ( 'post' === $search_content_type || 'product' === $search_content_type ) && ( isset( $ciyashop_options['show_categories'] ) && $ciyashop_options['show_categories'] )
			) {
				?>
				<div class="search_form-category-wrap">
					<?php
					$selected_cat = '';
					if ( 'product' === $search_content_type && isset( $_GET['product_cat'] ) && ! empty( $_GET['product_cat'] ) ) {
						$selected_cat = sanitize_text_field( wp_unslash( $_GET['product_cat'] ) );
					} elseif ( isset( $_GET['search_category'] ) && ! empty( $_GET['search_category'] ) ) {
						$selected_cat = sanitize_text_field( wp_unslash( $_GET['search_category'] ) );
					}

					// Prepare category dropdown ID.
					global $search_category_sr;
					if ( ! isset( $search_category_sr ) ) {
						$search_category_sr = 1;
					} else {
						$search_category_sr++;
					}

					// Hide "Uncategorized" category from categories dropdown.
					$exclude = array();

					// get default category for posts.
					$default_cat = get_term_by( 'name', 'Uncategorized', 'category' );

					// get default category for products.
					if ( 'product' === $search_content_type ) {
						$default_cat = get_term_by( 'name', 'Uncategorized', 'product_cat' );
					}

					if ( $default_cat && ! is_wp_error( $default_cat ) ) {
						$exclude[] = $default_cat->term_id;
					}

					$exclude = apply_filters( 'ciyashop_search_exclude_term', $exclude );

					// Category Dropdown Arguments.
					$args = array(
						'hide_empty'       => false,
						'hierarchical'     => 1,
						'taxonomy'         => ( 'product' === $search_content_type ) ? 'product_cat' : 'category',
						'pad_counts'       => false,
						'name'             => ( 'product' === $search_content_type ) ? 'product_cat' : 'search_category',
						'class'            => 'search_form-category',
						'id'               => 'search_category_' . $search_category_sr,
						'show_option_all'  => esc_html__( 'All Categories', 'ciyashop' ),
						'selected'         => $selected_cat,
						'value_field'      => ( 'product' === $search_content_type ) ? 'slug' : 'term_id',
						'show_option_none' => false,
						'hide_if_empty'    => false,
						'exclude'          => $exclude,
					);

					if ( 'product' === $search_content_type ) {
						$args['orderby']  = 'meta_value_num';
						$args['meta_key'] = 'order';
					}

					// Stuck with this until a fix for https://core.trac.wordpress.org/ticket/13258.
					/**
					 * Filters search form categories dropdown args.
					 *
					 * @visible false
					 * @ignore
					 */
					$args = apply_filters( 'ciyashop_search_categories_args', $args );
					if ( ( 'product' === $search_content_type && function_exists( 'WC' ) ) || ( 'post' === $search_content_type ) ) {
						wp_dropdown_categories( apply_filters( 'ciyashop_search_form_categories_dropdown_args', $args ) );
					}
					?>
				</div>
				<?php
			}
			?>
			<div class="search_form-input-wrap">
				<?php
				if ( 'all' !== $search_content_type ) {
					?>
					<input type="hidden" name="post_type" value="<?php echo esc_attr( $search_content_type ); ?>"/>
					<?php
				}
				?>
				<label class="screen-reader-text" for="<?php echo esc_attr( $search_form_id ); ?>"><?php esc_html_e( 'Search for:', 'ciyashop' ); ?></label>
				<div class="search_form-search-field">
					<input type="text" id="<?php echo esc_attr( $search_form_id ); ?>" class="form-control search-form" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" placeholder="<?php echo esc_attr( $search_placeholder_text ); ?>" />
				</div>
				<div class="search_form-search-button">
					<input value="" type="submit">
				</div>				
			</div>			
			<div class="ciyashop-auto-compalte-default ciyashop-empty"><ul class="ui-front ui-menu ui-widget ui-widget-content search_form-autocomplete"></ul></div>
		</form>		
	</div>
	<?php
	if ( ( isset( $ciyashop_options['show_search_keywords'] ) && $ciyashop_options['show_search_keywords'] ) && 'default' !== $ciyashop_options['header_type'] && 'product' === $search_content_type ) {
		if ( ! empty( $ciyashop_options['search_keywords_title'] ) && ! empty( $ciyashop_options['search_keywords'] ) ) {

			$category_ids       = $ciyashop_options['search_keywords'];
			$terms              = get_terms(
				'product_cat',
				array(
					'include' => $category_ids,
					'orderby' => 'include',
				)
			);
			$product_categories = $terms;
			if ( ! is_wp_error( $product_categories ) ) {
				?>
				<div class="search_form-keywords-wrap">
					<div class="search_form-keywords-title">
						<?php echo esc_html( $ciyashop_options['search_keywords_title'] ); ?>
					</div>
					<div class="search_form-keywords">
						<ul class="search_form-keywords-list">
						<?php
						foreach ( $product_categories as $product_category ) {
							?>
							<li class="search_form-keyword-single">
								<a href="<?php echo esc_url( get_term_link( $product_category->term_id ) ); ?>" class="search-keyword" ><?php echo esc_html( $product_category->name ); ?></a>
							</li>
							<?php
						}
						?>
						</ul>
					</div>
				</div>
				<?php
			}
		}
	}
	?>
</div>
