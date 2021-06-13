<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Third Party Mega Menu Supports.
 *
 * @package Ciyashop
 */

// Max Mega Menu Support.
require_once get_parent_theme_file_path( '/includes/menus/maxmegamenu.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
/**
 * Ciyashop add burger to wp menu
 *
 * @param string $items .
 * @param string $args .
 */
function ciyashop_add_burger_to_wp_menu( $items, $args ) {

	if ( 'primary' !== $args->theme_location ) {
		return $items;
	}

	// Add "Burger" to nav menus.
	if ( 'burger-menu' === (string) ciyashop_header_type() ) {
		ob_start();
		?>
		<li class="menu-item menu-item-burger-toggle">
			<div id="menu-toggle">
				<div id="menu-icon">
					<span></span>
					<span></span>
					<span></span>
					<span></span>
					<span></span>
					<span></span>
				</div>
			</div>
		</li>
		<?php
		$burger_item = ob_get_clean();
		$items       = $items . $burger_item;
	}

	// Add "Cart" to nav menus.
	if ( 'burger-menu' === (string) ciyashop_header_type() ) {
		$search_items = '';
		ob_start();
		?>
		<li class="menu-item menu-item-cart">
			<?php get_template_part( 'woocommerce/custom/minicart-ajax' ); ?>
		</li>
		<?php
		$search_item = ob_get_clean();

		$items = $items . $search_item;
	}
	return $items;
}
add_filter( 'wp_nav_menu_items', 'ciyashop_add_burger_to_wp_menu', 100, 2 );

/**
 * CiyaShop page nav walker for set the default meny without any broken
 */
class CiyaShop_Page_Nav_Walker extends Walker_Page {
	/**
	 * Outputs the beginning of the current element in the tree.
	 *
	 * @see Walker::start_el()
	 * @since 2.1.0
	 * @access public
	 *
	 * @param string  $output       Used to append additional content. Passed by reference.
	 * @param WP_Post $page         Page data object.
	 * @param int     $depth        Optional. Depth of page. Used for padding. Default 0.
	 * @param array   $args         Optional. Array of arguments. Default empty array.
	 * @param int     $current_page Optional. Page ID. Default 0.
	 */
	public function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {
		if ( isset( $args['item_spacing'] ) && 'preserve' === $args['item_spacing'] ) {
			$t = "\t";
			$n = "\n";
		} else {
			$t = '';
			$n = '';
		}
		if ( $depth ) {
			$indent = str_repeat( $t, $depth );
		} else {
			$indent = '';
		}

		$css_class = array( 'menu-item', 'page_item', 'page-item-' . $page->ID );

		if ( isset( $args['pages_with_children'][ $page->ID ] ) ) {
			$css_class[] = 'menu-item-has-children page_item_has_children';
		}
		
		if ( ! empty( $current_page ) ) {
			$_current_page = get_post( $current_page );
			
			if ( $_current_page && in_array( $page->ID, $_current_page->ancestors, true ) ) {
				$css_class[] = 'current-menu-ancestor current_page_ancestor';
			}
			if ( (string) $page->ID === (string) $current_page ) {
				$css_class[] = 'current_page_item';
			} elseif ( $page->ID === (string) $_current_page->post_parent ) {
				$css_class[] = 'current-menu-parent current_page_parent';
			}
		} elseif ( (string) get_option( 'page_for_posts' ) === (string) $page->ID ) {
			$css_class[] = 'current-menu-parent current_page_parent';
		}

		/**
		 * Filters the list of CSS classes to include with each page item in the list.
		 *
		 * @since 2.8.0
		 *
		 * @see wp_list_pages()
		 *
		 * @param array   $css_class    An array of CSS classes to be applied
		 *                              to each list item.
		 * @param WP_Post $page         Page data object.
		 * @param int     $depth        Depth of page, used for padding.
		 * @param array   $args         An array of arguments.
		 * @param int     $current_page ID of the current page.
		 *
		 * @visible false
		 * @ignore
		 */
		$css_classes = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );

		if ( '' === $page->post_title ) {
			/* translators: %d: ID of a post */
			$page->post_title = sprintf( esc_html__( '#%d (no title)', 'ciyashop' ), $page->ID );
		}

		$args['link_before'] = empty( $args['link_before'] ) ? '' : $args['link_before'];
		$args['link_after']  = empty( $args['link_after'] ) ? '' : $args['link_after'];

		$output .= $indent . sprintf(
			'<li class="%s"><a href="%s">%s%s%s</a>',
			$css_classes,
			get_permalink( $page->ID ),
			$args['link_before'],
			/**
			 * Filters the post title.
			 *
			 * This filter is documented in wp-includes/post-template.php.
			 *
			 * @since 0.71
			 *
			 * @param string $title The post title.
			 * @param int    $id    The post ID.
			 *
			 * @visible false
			 * @ignore
			 */
			apply_filters( 'the_title', $page->post_title, $page->ID ),
			$args['link_after']
		);

		if ( ! empty( $args['show_date'] ) ) {
			if ( 'modified' === (string) $args['show_date'] ) {
				$time = $page->post_modified;
			} else {
				$time = $page->post_date;
			}

			$date_format = empty( $args['date_format'] ) ? '' : $args['date_format'];
			$output     .= ' ' . mysql2date( $date_format, $time );
		}
	}
	/**
	 * Outputs the beginning of the current level in the tree before elements are output.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @see Walker::start_lvl()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Optional. Depth of page. Used for padding. Default 0.
	 * @param array  $args   Optional. Arguments for outputting the next level.
	 *                       Default empty array.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args['item_spacing'] ) && 'preserve' === $args['item_spacing'] ) {
			$t = "\t";
			$n = "\n";
		} else {
			$t = '';
			$n = '';
		}
		$indent  = str_repeat( $t, $depth );
		$output .= "{$n}{$indent}<ul class='sub-menu'>{$n}";
	}
}

/***********************************
	:: Mega Menu Custom navigation
*********************************** */

if ( ! class_exists( 'CiyaShop_Walker_Nav_Menu' ) ) {
	/**
	 * Walker Nav Menu
	 */
	class CiyaShop_Walker_Nav_Menu extends Walker_Nav_Menu {
		/**
		 * Start lvl
		 *
		 * @param int   $output .
		 * @param int   $depth .
		 * @param array $args .
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {

			if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}
			$indent = str_repeat( $t, $depth );

			$classes = array( 'pgs-mega-sub-menu', 'sub-menu' );

			$class_names = join( ' ', apply_filters( 'ciyashop_nav_menu_submenu_css_class', $classes, $args, $depth ) );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			if ( $depth > 0 ) {
				$output .= "{$n}{$indent}<div class=\"pgs_menu-sublist\">{$n}";
			}
			$output .= "{$n}{$indent}<ul$class_names>{$n}";

		}
		/**
		 * End lvl
		 *
		 * @param int   $output .
		 * @param int   $depth .
		 * @param array $args .
		 */
		public function end_lvl( &$output, $depth = 0, $args = array() ) {

			if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}
			$indent = str_repeat( $t, $depth );

			$output .= "{$indent}</ul>{$n}";
			if ( $depth > 0 ) {
				$output .= "{$n}{$indent}</div>{$n}";
			}
		}
		/**
		 * Start el
		 *
		 * @param int   $output .
		 * @param int   $item .
		 * @param int   $depth .
		 * @param array $args .
		 * @param int   $id .
		 */
		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

			$label_text          = '';
			$background_position = '';
			$background_repeat   = '';
			$background_size     = '';

			if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}

			$indent  = ( $depth ) ? str_repeat( $t, $depth ) : '';
			$item_id = $item->ID;

			$menu_anchor              = '';
			$menu_design              = '';
			$menu_columns             = '';
			$html_block               = '';
			$menu_widget_area         = '';
			$menu_open_on_click       = '';
			$menu_icon                = '';
			$menu_label               = '';
			$menu_background_repeat   = '';
			$menu_background_position = '';
			$styles                   = '';
			$widgets_content          = '';
			$html_block               = '';
			$menu_icon_html           = '';

			$menu_anchor              = get_post_meta( $item_id, 'pgs_menu-item-menu_anchor', true );
			$menu_design              = get_post_meta( $item_id, 'pgs_menu-item-menu_design', true );
			$menu_color_scheme        = get_post_meta( $item_id, 'pgs_menu-item-menu_color_scheme', true );
			$mega_menu_design         = get_post_meta( $item_id, 'pgs_menu-item-mega_menu_design', true );
			$mega_menu_width          = get_post_meta( $item_id, 'pgs_menu-item-mega_menu_width', true );
			$mega_menu_height         = get_post_meta( $item_id, 'pgs_menu-item-mega_menu_height', true );
			$menu_columns             = get_post_meta( $item_id, 'pgs_menu-item-menu_columns', true );
			$html_block_id            = get_post_meta( $item_id, 'pgs_menu-item-html_block', true );
			$menu_widget_area         = get_post_meta( $item_id, 'pgs_menu-item-menu_widget_area', true );
			$menu_open_on_click       = get_post_meta( $item_id, 'pgs_menu-item-menu_open_on_click', true );
			$enable_menu_link         = get_post_meta( $item_id, 'pgs_menu-item-enable_menu_link', true );
			$menu_icon                = get_post_meta( $item_id, 'pgs_menu-item-menu_icon', true );
			$menu_label               = get_post_meta( $item_id, 'pgs_menu-item-menu_label', true );
			$menu_label_color         = get_post_meta( $item_id, 'pgs_menu-item-menu_label_color', true );
			$menu_background_repeat   = get_post_meta( $item_id, 'pgs_menu-item-menu_background_repeat', true );
			$menu_background_size     = get_post_meta( $item_id, 'pgs_menu-item-menu_background_size', true );
			$menu_background_position = get_post_meta( $item_id, 'pgs_menu-item-menu_background_position', true );

			if ( ! empty( $menu_anchor ) ) {
				$item->url = $item->url . '#' . $menu_anchor;
			}

			$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;
			$classes[] = 'pgs-menu-item-depth-' . $depth;

			if ( 0 === (int) $depth ) {

				if ( ! empty( $menu_design ) ) {
					$classes[] = 'pgs-menu-item-' . $menu_design;
				} else {
					$classes[] = 'pgs-menu-item-dropdown';
				}

				if ( 'mega-menu' === (string) $menu_design ) {

					if ( ! empty( $menu_columns ) && empty( $html_block_id ) ) {
						$classes[] = 'columns-' . $menu_columns;
					}

					if ( ! empty( $mega_menu_design ) ) {
						$classes[] = 'pgs-mega-menu-' . $mega_menu_design;
					}

					if ( 'custom-size' === (string) $mega_menu_design ) {
						$styles .= '.menu-item-' . $item->ID . '.pgs-mega-menu-custom-size > .pgs_menu_nav-sublist-dropdown {';
						$styles .= 'min-height: ' . $mega_menu_height . 'px; ';
						$styles .= 'width: ' . $mega_menu_width . 'px; ';
						$styles .= '}';

						$styles .= '.menu-item-' . $item->ID . '.pgs-mega-menu-custom-size > .pgs-menu-html-block {';
						$styles .= 'min-height: ' . $mega_menu_height . 'px; ';
						$styles .= 'width: ' . $mega_menu_width . 'px; ';
						$styles .= '}';
					}

					if ( ! empty( $html_block_id && function_exists( 'pgs_get_html_block' ) ) ) {
						$classes[]  = 'menu-item-with-block';
						$classes[]  = 'menu-item-has-children menu-parent-item';
						$html_block = '<div class="pgs-menu-html-block sub-menu"><div class="html-block-container container"><div class="html-block-inner">' . pgs_get_html_block( $html_block_id ) . '</div></div></div>';
					}
				}

				if ( 1 === (int) $menu_open_on_click ) {
					$classes[] = 'pgs-menu-item-open-on-click';
					if ( 1 === (int) $enable_menu_link ) {
						$classes[] = 'pgs-menu-item-link-enable';
					}
				}

				if ( ! empty( $menu_color_scheme ) ) {
					$classes[] = 'pgs-menu-item-color-scheme-' . $menu_color_scheme;
				}
			} else {
				if ( ! empty( $menu_widget_area ) && 1 === (int) $depth ) {
					$classes[]               = 'pgs-menu-item-item-with-widgets';
					$parent_mega_menu_design = get_post_meta( $item->menu_item_parent, 'pgs_menu-item-menu_design', true );

					if ( 'mega-menu' === (string) $parent_mega_menu_design ) {

						ob_start();
						dynamic_sidebar( $menu_widget_area );
						$sidebar_content = ob_get_clean();

						$widgets_content = '<div class="pgs_menu-widgets">' . $sidebar_content . '</div>';
					}
				}
			}

			if ( ! empty( $menu_icon ) && 'fip-icon-block' !== (string) $menu_icon ) {
				$menu_icon_html = '<i class="' . $menu_icon . '"></i>';
			}

			if ( ! empty( $menu_label ) ) {
				$classes[]   = 'pgs_menu-label-' . $menu_label_color;
				$label_text .= '<span class="text-label">';
				$label_text .= $menu_label;
				$label_text .= '</span>';
			}

			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			$output .= $indent . '<li' . $id . $class_names . '>';

			$atts           = array();
			$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
			$atts['target'] = ! empty( $item->target ) ? $item->target : '';
			$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
			$atts['href']   = ! empty( $item->url ) ? $item->url : '';

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			$item_output = $args->before;

			if ( $depth > 0 ) {
				$item_output .= $menu_icon_html;
			}
			$item_output .= '<a' . $attributes . '>';
			if ( 0 === (int) $depth ) {
				$item_output .= $menu_icon_html;
			}
			$item_output .= $args->link_before;
			$item_output .= apply_filters( 'the_title', $item->title, $item->ID );
			$item_output .= $args->link_after;
			$item_output .= $args->after;
			$item_output .= $label_text;

			if ( has_post_thumbnail( $item->ID ) ) {

				$post_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $item->ID ), 'full' );

				if ( ! empty( $menu_background_position ) ) {
					$background_position = 'background-position: ' . $menu_background_position . ';';
				}

				if ( ! empty( $menu_background_repeat ) ) {
					$background_repeat = 'background-repeat: ' . $menu_background_repeat . ';';
				}

				if ( ! empty( $menu_background_size ) ) {
					$background_size = 'background-size: ' . $menu_background_size . ';';
				}

				if ( $depth > 0 && $depth < 2 ) {

					$styles .= 'body .site-header .pgs_megamenu-enable .menu-item-' . $item->ID . ' > .pgs_menu-sublist > .pgs-mega-sub-menu {';
					$styles .= 'background-image: url(' . $post_thumbnail[0] . '); ' . $background_position . $background_repeat . $background_size;
					$styles .= '}';

				} else {

					$styles .= 'body .site-header .pgs_megamenu-enable .menu-item-' . $item->ID . ' > .pgs_menu_nav-sublist-dropdown {';
					$styles .= 'background-image: url(' . $post_thumbnail[0] . '); ' . $background_position . $background_repeat . $background_size;
					$styles .= '}';

				}
			}

			$item_output .= '</a>';
			$item_output .= $widgets_content;
			$item_output .= $html_block;
			$item_output .= $args->after;

			if ( ! empty( $styles ) && 'site-mobile-menu' !== (string) $args->menu_class ) {
				$item_output .= '<style type="text/css">';
				$item_output .= $styles;
				$item_output .= '</style>';
			}

			if ( 0 === $depth && in_array( 'menu-item-has-children', $item->classes, true ) ) {
				$item_output .= "{$n}<div class=\"pgs_menu_nav-sublist-dropdown sub-menu\"><div class=\"container\">{$n}";
			}

			$output .= apply_filters( 'ciyashpp_walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
		/**
		 * End el
		 *
		 * @param int   $output .
		 * @param int   $item .
		 * @param int   $depth .
		 * @param array $args .
		 */
		public function end_el( &$output, $item, $depth = 0, $args = array() ) {

			if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}

			$item_id = $item->ID;

			if ( 0 === $depth && in_array( 'menu-item-has-children', $item->classes, true ) ) {
				$output .= "{$n}</div></div>{$n}";
			}

			$output .= "</li>{$n}";
		}
	}
}
