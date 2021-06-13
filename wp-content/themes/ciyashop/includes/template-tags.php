<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package CiyaShop
 */

if ( ! function_exists( 'ciyashop_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function ciyashop_posted_on() {
		$year  = gmdate( 'Y', strtotime( get_the_date() ) );
		$month = gmdate( 'm', strtotime( get_the_date() ) );
		$day   = gmdate( 'd', strtotime( get_the_date() ) );
		?>
	<ul>
		<li><a href="<?php echo esc_url( get_day_link( $year, $month, $day ) ); ?>"><i class="fa fa-calendar"></i> <?php echo esc_html( gmdate( 'd M, Y', strtotime( get_the_date() ) ) ); ?></a></li>
		<li><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ) ); ?>"><i class="fa fa fa-user"></i> <?php echo esc_html( get_the_author() ); ?></a></li>
		<li>
			<?php comments_popup_link( '<i class="fa fa-comments-o"></i> 0', '<i class="fa fa-comments-o"></i> 1', '<i class="fa fa-comments-o"></i>' . wp_count_comments( get_the_ID() )->total_comments, '' ); ?>                
		<li>
		<?php
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( ', ' );
			if ( $categories_list && ciyashop_categorized_blog() ) {
				printf( '<i class="fa fa-folder-open"></i> %1$s', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
		?>

		</li>
		<li>
			<?php
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', ', ' );
			if ( $tags_list ) {
				printf( '<i class="fa fa-tags"></i> %1$s', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
		</li>
	</ul> 
		<?php
	}
endif;

if ( ! function_exists( 'ciyashop_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function ciyashop_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( ', ' );
			if ( $categories_list && ciyashop_categorized_blog() ) {
				/* translators: %1$s: Category List */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'ciyashop' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', ', ' );
			if ( $tags_list ) {
				/* translators: %1$s: Tag List */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'ciyashop' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			?>
		<span class="comments-link">
			<?php
			/* translators: %s: post title */
			comments_popup_link( sprintf( wp_kses( __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'ciyashop' ), array( 'span' => array( 'class' => array() ) ) ), get_the_title() ) );
			?>
		</span>
			<?php
		}

		edit_post_link(
			sprintf(
				/* translators: %s: Name of current post */
				esc_html__( 'Edit %s', 'ciyashop' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			),
			'<span class="edit-link">',
			'</span>'
		);
		$social_icons = '';
		ob_start();
		ciyashop_social_share();
		$social_icons = ob_get_contents();
		ob_end_clean();
		if ( ! empty( $social_icons ) ) {
			?>
			<div class="entry-social share pull-right">
				<a href="javascript:void(0)" class="share-button" data-title="<?php esc_attr_e( 'Share it on', 'ciyashop' ); ?>">
					<i class="fa fa-share-alt"></i>asd
				</a>
				<?php
				ciyashop_social_share(
					array(
						'class' => 'single-share-box mk-box-to-trigger',
					)
				);
				?>
			</div>
			<?php
		}
	}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function ciyashop_categorized_blog() {
	$category_count = get_transient( 'ciyashop_categories' );

	if ( false === $category_count ) {
		// Create an array of all the categories that are attached to posts.
		$categories = get_categories(
			array(
				'fields'     => 'ids',
				'hide_empty' => 1,
				// We only need to know if there is more than one category.
				'number'     => 2,
			)
		);

		// Count the number of categories that are attached to the posts.
		$category_count = count( $categories );

		set_transient( 'ciyashop_categories', $category_count );
	}

	// Allow viewing case of 0 or 1 categories in post preview.
	if ( is_preview() ) {
		return true;
	}

	return $category_count > 1;
}

/**
 * Flush out the transients used in ciyashop_categorized_blog.
 */
function ciyashop_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'ciyashop_categories' );
}
add_action( 'edit_category', 'ciyashop_category_transient_flusher' );
add_action( 'save_post', 'ciyashop_category_transient_flusher' );


if ( ! function_exists( 'ciyashop_comment_nav' ) ) :
	/**
	 * Display navigation to next/previous comments when applicable.
	 *
	 * @since CiyaShop 1.0
	 */
	function ciyashop_comment_nav() {
		// Are there comments to navigate through?
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
			?>
	<nav class="navigation comment-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'ciyashop' ); ?></h2>
		<div class="nav-links">
			<?php
			$prev_link = get_previous_comments_link( esc_html__( 'Older Comments', 'ciyashop' ) );
			if ( $prev_link ) :

				/* translators: %s: Previous Link */
				printf(
					'<div class="nav-previous">%s</div>',
					wp_kses( $prev_link, ciyashop_allowed_html( array( 'a' ) ) )
				);
				endif;

			$next_link = get_next_comments_link( esc_html__( 'Newer Comments', 'ciyashop' ) );
			if ( $next_link ) :
				/* translators: %s: Next Link */
				printf(
					'<div class="nav-next">%s</div>',
					wp_kses( $next_link, ciyashop_allowed_html( array( 'a' ) ) )
				);
			endif;
			?>
		</div><!-- .nav-links -->
	</nav><!-- .comment-navigation -->
			<?php
		endif;
	}
endif;

/**
 * Get topbar
 *
 * @param string $position topbar position.
 */
function ciyashop_get_topbar( $position = '' ) {

	// Check topbar position.
	if ( empty( $position ) ) {
		return;
	}

	if ( 'left' !== $position && 'right' !== $position ) {
		return;
	}

	if ( 'left' === $position ) {
		$position_name = 'Left';
	} elseif ( 'right' === $position ) {
		$position_name = 'Right';
	}

	global $ciyashop_globals, $ciyashop_options;

	if ( ! isset( $ciyashop_options['topbar_layout'] ) && empty( $ciyashop_options['topbar_layout'] ) ) {
		return;
	}

	$topbar_layout          = $ciyashop_options['topbar_layout'];
	$topbar_layout_position = false;

	if ( isset( $topbar_layout[ $position_name ] ) && ! empty( $topbar_layout[ $position_name ] ) && is_array( $topbar_layout[ $position_name ] ) ) {
		$topbar_position_sr = 0;
		foreach ( $topbar_layout[ $position_name ] as $topbar_layout_k => $topbar_layout_v ) {
			$topbar_layout_content = ciyashop_layout_content( $topbar_layout_k, 'topbar' );

			if ( ! empty( $topbar_layout_content ) ) {

				$topbar_item_classes   = array();
				$topbar_item_classes[] = 'topbar_item';
				if ( 'topbar_widget_1' === $topbar_layout_k || 'topbar_widget_2' === $topbar_layout_k ) {
					$topbar_item_classes[] = 'topbar_item_type-widget';
					$topbar_item_classes[] = 'topbar_widget-' . $topbar_layout_k;
				} else {
					$topbar_item_classes[] = 'topbar_item_type-' . $topbar_layout_k;
				}

				$topbar_item_classes = implode( ' ', $topbar_item_classes );

				$topbar_position_sr++;
				$topbar_layout_position .= "<!-- topbar_item_{$position} $topbar_position_sr START --> \n";
				$topbar_layout_position .= '<li class="' . esc_attr( $topbar_item_classes ) . '">' . "\n";
				$topbar_layout_position .= $topbar_layout_content;
				$topbar_layout_position .= "</li><!-- topbar_item_{$position} $topbar_position_sr END --> \n";
			}
		}
	}
	return $topbar_layout_position;
}

/**
 * Single prodcut navigation
 */
function ciyashop_single_product_nav() {
	global $ciyashop_options;

	$product_navigation = isset( $ciyashop_options['product-navigation'] ) && ! empty( $ciyashop_options['product-navigation'] ) ? $ciyashop_options['product-navigation'] : false;

	// Bail if navigation is disabled.
	if ( ! $product_navigation ) {
		return false;
	}

	if ( is_singular( 'product' ) ) {
		wc_get_template( 'single-product/product-navigation.php' );
	}
}

/**
 * Cart icon
 *
 * @param boolean $echo print or not.
 * @param boolean $html icon content.
 */
function ciyashop_cart_icon( $echo = true, $html = true ) {
	global $ciyashop_options;

	$icon_class = isset( $ciyashop_options['shopping-cart-icon'] ) && ! empty( $ciyashop_options['shopping-cart-icon'] ) ? $ciyashop_options['shopping-cart-icon'] : 'fa fa-shopping-cart';

	/**
	 * Filters cart icon class.
	 *
	 * @param string    $icon_class            Class of cart icon.
	 * @param array     $ciyashop_options      Array of theme options.
	 *
	 * @visible true
	 */
	$icon_class = apply_filters( 'ciyashop_cart_icon_class', $icon_class, $ciyashop_options );

	/**
	 * Filters cart icon.
	 *
	 * @param string    $icon                  Cart icon.
	 * @param string    $icon_class            Class of cart icon.
	 * @param array     $ciyashop_options      Array of theme options.
	 *
	 * @visible true
	 */
	$icon_html = apply_filters( 'ciyashop_cart_icon_html', '<i class="' . esc_attr( $icon_class ) . '"></i>', $icon_class, $ciyashop_options );

	$icon_content = ( $html ) ? $icon_html : $icon_class;

	if ( $echo ) {
		echo wp_kses( $icon_content, ciyashop_allowed_html( array( 'i' ) ) );
	} else {
		return wp_kses( $icon_content, ciyashop_allowed_html( array( 'i' ) ) );
	}
}

/**
 * Compare icon.
 *
 * @param boolean $echo print or not.
 * @param boolean $html icon content.
 */
function ciyashop_compare_icon( $echo = true, $html = true ) {
	global $ciyashop_options;

	$icon_class = isset( $ciyashop_options['woocommerce_compare_icon'] ) && ! empty( $ciyashop_options['woocommerce_compare_icon'] ) ? $ciyashop_options['woocommerce_compare_icon'] : 'fa fa-compress';

	/**
	 * Filters compare icon class.
	 *
	 * @param string    $icon_class            Class of compare icon.
	 * @param array     $ciyashop_options      Array of theme options.
	 *
	 * @visible true
	 */
	$icon_class = apply_filters( 'ciyashop_compare_icon_class', $icon_class, $ciyashop_options );

	/**
	 * Filters compare icon.
	 *
	 * @param string    $icon                  Compare icon.
	 * @param string    $icon_class            Class of compare icon.
	 * @param array     $ciyashop_options      Array of theme options.
	 *
	 * @visible true
	 */
	$icon_html = apply_filters( 'ciyashop_compare_icon_html', '<i class="' . esc_attr( $icon_class ) . '"></i>', $icon_class, $ciyashop_options );

	$icon_content = ( $html ) ? $icon_html : $icon_class;

	if ( $echo ) {
		echo wp_kses( $icon_content, ciyashop_allowed_html( array( 'i' ) ) );
	} else {
		return wp_kses( $icon_content, ciyashop_allowed_html( array( 'i' ) ) );
	}
}

/**
 * Wishlist icons
 *
 * @param boolean $echo print or not.
 * @param boolean $html icon content.
 */
function ciyashop_wishlist_icon( $echo = true, $html = true ) {
	global $ciyashop_options;

	$icon_class = isset( $ciyashop_options['woocommerce_wishlist_icon'] ) && ! empty( $ciyashop_options['woocommerce_wishlist_icon'] ) ? $ciyashop_options['woocommerce_wishlist_icon'] : 'fa fa-heart';
	/**
	 * Filters wishlist icon class.
	 *
	 * @param string    $icon_class            Class of wishlist icon.
	 * @param array     $ciyashop_options      Array of theme options.
	 *
	 * @visible true
	 */
	$icon_class = apply_filters( 'ciyashop_wishlist_icon_class', $icon_class, $ciyashop_options );

	/**
	 * Filters wishlist icon.
	 *
	 * @param string    $icon                  Wishlist icon.
	 * @param string    $icon_class            Class of wishlist icon.
	 * @param array     $ciyashop_options      Array of theme options.
	 *
	 * @visible true
	 */
	$icon_html = apply_filters( 'ciyashop_wishlist_icon_html', '<i class="' . esc_attr( $icon_class ) . '"></i>', $icon_class, $ciyashop_options );

	$icon_content = ( $html ) ? $icon_html : $icon_class;

	if ( $echo ) {
		echo wp_kses( $icon_content, ciyashop_allowed_html( array( 'i' ) ) );
	} else {
		return wp_kses( $icon_content, ciyashop_allowed_html( array( 'i' ) ) );
	}
}

/**
 * Search icon
 *
 * @param boolean $echo print or not.
 * @param boolean $html display icon content or not.
 */
function ciyashop_search_icon( $echo = true, $html = true ) {
	global $ciyashop_options;

	$icon_class = isset( $ciyashop_options['site_search_icon'] ) && ! empty( $ciyashop_options['site_search_icon'] ) ? $ciyashop_options['site_search_icon'] : 'fa fa-search';

	/**
	 * Filters search icon class.
	 *
	 * @param string    $icon_class            Class of search icon.
	 * @param array     $ciyashop_options      Array of theme options.
	 *
	 * @visible true
	 */
	$icon_class = apply_filters( 'ciyashop_search_icon_class', $icon_class, $ciyashop_options );

	/**
	 * Filters search icon.
	 *
	 * @param string    $icon                  Search icon.
	 * @param string    $icon_class            Class of search icon.
	 * @param array     $ciyashop_options      Array of theme options.
	 *
	 * @visible true
	 */
	$icon_html = apply_filters( 'ciyashop_search_icon_html', '<i class="' . esc_attr( $icon_class ) . '"></i>', $icon_class, $ciyashop_options );

	$icon_content = ( $html ) ? $icon_html : $icon_class;

	if ( $echo ) {
		echo wp_kses( $icon_content, ciyashop_allowed_html( array( 'i' ) ) );
	} else {
		return wp_kses( $icon_content, ciyashop_allowed_html( array( 'i' ) ) );
	}
}
