<?php
/**
 * Template functions
 *
 * @package CiyaShop
 */

/**
 * Display preloader
 *
 * @return void
 */
function ciyashop_preloader() {

	/**
	 * Fires before preloader.
	 *
	 * @visible true
	 */
	do_action( 'ciyashop_before_preloader' );

	get_template_part( 'template-parts/preloader/default' );

	/**
	 * Fires after preloader.
	 *
	 * @visible true
	 */
	do_action( 'ciyashop_after_preloader' );
}

/**
 * Header style settings
 *
 * @return void
 */
function ciyashop_header_style_settings() {
	global $ciyashop_options;

	$ciyashop_header_type = ciyashop_header_type();

	if ( 'topbar-with-main-header' === $ciyashop_header_type ) {
		add_action( 'ciyashop_header_nav_content', 'ciyashop_header_nav_right_wrapper_start', 30 );
		add_action( 'ciyashop_header_nav_content', 'ciyashop_wootools', 31 );

		$show_search = ciyashop_show_search();
		if ( $show_search ) {
			add_action( 'ciyashop_header_nav_content', 'ciyashop_header_search_content', 32 );
		}
		add_action( 'ciyashop_header_nav_content', 'ciyashop_header_nav_right_wrapper_end', 33 );
		add_filter( 'ciyashop_site_title_class', 'ciyashop_topbar_with_main_header_title_class' );

	} elseif ( 'right-topbar-main' === $ciyashop_header_type ) {
		remove_action( 'ciyashop_before_header_nav_content', 'ciyashop_before_header_nav_content_wrapper_start', 10 );
		remove_action( 'ciyashop_after_header_nav_content', 'ciyashop_after_header_nav_content_wrapper_end', 10 );
	}
}

/**
 * Header nav wrapper
 *
 * @return void
 */
function ciyashop_header_nav_right_wrapper_start() {
	?>
	<div class="header-nav-right">
	<?php
}

/**
 * Header nav wrapper
 *
 * @return void
 */
function ciyashop_header_nav_right_wrapper_end() {
	?>
	</div>
	<?php
}

/**
 * Get theme logo
 *
 * @return void
 */
function ciyashop_logo() {
	global $ciyashop_options;

	$site_logo = ciyashop_logo_url();
	$logo_type = ciyashop_logo_type();
	$logo_text = ( isset( $ciyashop_options['logo_text'] ) && ! empty( $ciyashop_options['logo_text'] ) ) ? $ciyashop_options['logo_text'] : false;

	if ( 'image' === $logo_type && ! empty( $site_logo ) ) {
		?>
		<img class="img-fluid" src="<?php echo esc_url( $site_logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"/>
		<?php
	} elseif ( 'text' === $logo_type && $logo_text ) {
		?>
		<span class="logo-text"><?php echo esc_html( $logo_text ); ?></span>
		<?php
	} else {
		?>
		<span class="logo-text"><?php bloginfo( 'name' ); ?></span>
		<?php
	}
}

/**
 * Get logo for sticky
 *
 * @return void
 */
function ciyashop_sticky_logo() {
	global $ciyashop_options;

	$site_logo = ciyashop_sticky_logo_url();
	$logo_type = ciyashop_logo_type();
	$logo_text = ( isset( $ciyashop_options['logo_text'] ) && ! empty( $ciyashop_options['logo_text'] ) ) ? $ciyashop_options['logo_text'] : false;

	if ( 'image' === $logo_type && ! empty( $site_logo ) ) {
		?>
		<img class="img-fluid" src="<?php echo esc_url( $site_logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"/>
		<?php
	} elseif ( 'text' === $logo_type && $logo_text ) {
		?>
		<span class="logo-text"><?php echo esc_html( $logo_text ); ?></span>
		<?php
	} else {
		?>
		<span class="logo-text"><?php bloginfo( 'name' ); ?></span>
		<?php
	}
}

/**
 * Sticky nav template part
 *
 * @return void
 */
function ciyashop_sticky_nav() {
	get_template_part( 'template-parts/header/header-elements/sticky-nav' );
}

/**
 * Get site description
 *
 * @return void
 */
function ciyashop_site_description() {
	global $ciyashop_options;

	$description      = get_bloginfo( 'description', 'display' );
	$site_description = isset( $ciyashop_options['site_description'] ) ? $ciyashop_options['site_description'] : false;

	if ( ( $site_description && $description ) || is_customize_preview() ) {
		?>
		<p class="site-description"><?php echo esc_html( $description ); ?></p>
		<?php
	}
}

/**
 * Header nav wrapper
 *
 * @return void
 */
function ciyashop_before_header_nav_content_wrapper_start() {
	?>
	<div class="container">
		<div class="row">
			<?php
			if ( has_nav_menu( 'categories_menu' ) && ciyashop_categories_menu_status() === 'enable' ) {
				?>
				<div class="col-lg-3 col-md-3 col-sm-3 category-col">
				<?php
			} else {
				?>
				<div class="col-lg-12 col-md-12 col-sm-12 navigation-col">
				<?php
			}
}

/**
 * Get category menu
 *
 * @return void
 */
function ciyashop_category_menu() {
	if ( has_nav_menu( 'categories_menu' ) && 'enable' === (string) ciyashop_categories_menu_status() ) {
		?>
		<div class="category-nav">

			<?php
			/**
			 * Fires before category menu wrapper.
			 *
			 * @visible true
			 */
			do_action( 'ciyashop_before_category_menu_wrapper' );
			?>

			<div class="category-nav-wrapper">
				<div class="category-nav-title">
					<i class="fa fa-bars"></i>
					<?php
					/**
					 * Filters category menu title.
					 *
					 * @param string    $title      Title of category menu.
					 *
					 * @visible true
					 */
					$category_menu_title = apply_filters( 'ciyashop_category_menu_title', esc_html__( 'Categories', 'ciyashop' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo esc_html( $category_menu_title );
					?>
					<span class="arrow"><i class="fa fa-angle-down fa-indicator"></i></span>
				</div>
				<div class="category-nav-content">
					<?php
					/**
					 * Fires before category menu.
					 *
					 * @visible true
					 */
					do_action( 'ciyashop_before_category_menu' );

					get_template_part( 'template-parts/header/header-elements/categories-menu' );

					/**
					 * Fires after category menu.
					 *
					 * @visible true
					 */
					do_action( 'ciyashop_after_category_menu' );
					?>
				</div>
			</div>

			<?php
			/**
			 * Fires after category menu wrapper.
			 *
			 * @visible true
			 */
			do_action( 'ciyashop_after_category_menu_wrapper' );
			?>

		</div>
		<?php
	}
}

/**
 * Get menu separator
 *
 * @return void
 */
function ciyashop_catmenu_primenu_separator() {
	if ( has_nav_menu( 'categories_menu' ) && ciyashop_categories_menu_status() === 'enable' ) {
		?>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9 navigation-col">
		<?php
	}
}

/**
 * Get primary menu.
 *
 * @return void
 */
function ciyashop_primary_menu() {
	?>
	<div class="primary-nav">

		<?php
		/**
		 * Fires before primary menu wrapper.
		 *
		 * @visible true
		 */
		do_action( 'ciyashop_before_primary_menu_wrapper' );
		?>

		<div class="primary-nav-wrapper">
			<?php
			/**
			 * Fires before primary menu.
			 *
			 * @visible true
			 */
			do_action( 'ciyashop_before_primary_menu' );

			get_template_part( 'template-parts/header/header-elements/primary-menu' );

			/**
			 * Fires after primary menu.
			 *
			 * @visible true
			 */
			do_action( 'ciyashop_before_primary_menu' );
			?>
		</div>

		<?php
		/**
		 * Fires after primary menu wrapper.
		 *
		 * @visible true
		 */
		do_action( 'ciyashop_after_primary_menu_wrapper' );
		?>

	</div>
	<?php
}

/**
 * Header nav wrapper
 *
 * @return void
 */
function ciyashop_after_header_nav_content_wrapper_end() {
	?>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Site header title class
 *
 * @param string $site_title_class site title calss.
 */
function ciyashop_topbar_with_main_header_title_class( $site_title_class ) {
	return $site_title_class . ' text-center';
}

/**
 * Template for header elements
 *
 * @return void
 */
function ciyashop_wootools() {
	get_template_part( 'template-parts/header/header-elements/woo-tools' );
}

/**
 * Cart template for header
 *
 * @return void
 */
function ciyashop_header_wootools_cart() {
	global $ciyashop_options;

	if ( isset( $ciyashop_options['show_header_cart'] ) && $ciyashop_options['show_header_cart'] ) {
		get_template_part( 'template-parts/header/header-elements/woo-tools-cart' );
	}
}

/**
 * Compare template for header
 *
 * @return void
 */
function ciyashop_header_wootools_compare() {
	global $ciyashop_options;

	if ( isset( $ciyashop_options['show_header_compare'] ) && $ciyashop_options['show_header_compare'] ) {
		get_template_part( 'template-parts/header/header-elements/woo-tools-compare' );
	}
}

/**
 * Wishlist template for header
 *
 * @return void
 */
function ciyashop_header_wootools_wishlist() {
	global $ciyashop_options;

	if ( isset( $ciyashop_options['show_header_wishlist'] ) && $ciyashop_options['show_header_wishlist'] ) {
		get_template_part( 'template-parts/header/header-elements/woo-tools-wishlist' );
	}
}

/**
 * Template for sticky header elements
 *
 * @return void
 */
function ciyashop_sticky_wootools() {
	get_template_part( 'template-parts/header/header-elements/sticky-woo-tools' );
}

/**
 * Cart template for sticky header
 *
 * @return void
 */
function ciyashop_sticky_header_wootools_cart() {
	global $ciyashop_options;

	if ( isset( $ciyashop_options['show_sticky_header_cart'] ) && $ciyashop_options['show_sticky_header_cart'] ) {
		get_template_part( 'template-parts/header/header-elements/woo-tools-cart' );
	}
}

/**
 * Compare template for sticky header
 *
 * @return void
 */
function ciyashop_sticky_header_wootools_compare() {
	global $ciyashop_options;

	if ( isset( $ciyashop_options['show_sticky_header_compare'] ) && $ciyashop_options['show_sticky_header_compare'] ) {
		get_template_part( 'template-parts/header/header-elements/woo-tools-compare' );
	}
}

/**
 * Wishlist template for sticky header
 *
 * @return void
 */
function ciyashop_sticky_header_wootools_wishlist() {
	global $ciyashop_options;

	if ( isset( $ciyashop_options['show_sticky_header_wishlist'] ) && $ciyashop_options['show_sticky_header_wishlist'] ) {
		get_template_part( 'template-parts/header/header-elements/woo-tools-wishlist' );
	}
}

/**
 * Home button template for mobile sticky
 *
 * @return void
 */
function ciyashop_sticky_footer_mobile_home() {
	get_template_part( 'template-parts/footer/sticky-footer-elements/sticky_footer-home' );
}

/**
 * Wishlist template for moble sticky
 *
 * @return void
 */
function ciyashop_sticky_footer_mobile_wishlist() {
	get_template_part( 'template-parts/footer/sticky-footer-elements/sticky_footer-wishlist' );
}

/**
 * Account template for mobile sticky
 *
 * @return void
 */
function ciyashop_sticky_footer_mobile_account() {
	get_template_part( 'template-parts/footer/sticky-footer-elements/sticky_footer-account' );
}

/**
 * Cart template for mobile sticky
 *
 * @return void
 */
function ciyashop_sticky_footer_mobile_cart() {
	global $ciyashop_options;

	if ( isset( $ciyashop_options['hide_price_for_guest_user'] ) && $ciyashop_options['hide_price_for_guest_user'] && ! is_user_logged_in() ) {
		return;
	}

	get_template_part( 'template-parts/footer/sticky-footer-elements/sticky_footer-cart' );
}

/**
 * Get search form
 *
 * @return void
 */
function ciyashop_search_form() {
	get_template_part( 'template-parts/header/header-elements/search-form' );
}

/**
 * Header search content template call
 *
 * @return void
 */
function ciyashop_header_search_content() {
	$ciyashop_header_type = ciyashop_header_type();

	if ( 'default' === $ciyashop_header_type ) {
		get_template_part( 'template-parts/header/header-elements/search-form' );
	} else {
		get_template_part( 'template-parts/header/header-elements/search-button' );
		add_action( 'wp_footer', 'ciyashop_search_popup' );
	}
}

/**
 * Search popup
 *
 * @return void
 */
function ciyashop_search_popup() {
	?>
	<div id="search_popup" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-content-inner">

					<?php
					/**
					 * Fires before search popup content.
					 *
					 * @visible true
					 */
					do_action( 'ciyashop_before_search_popup_content' );
					?>

					<?php
					/**
					 * Hook: ciyashop_search_popup_content.
					 *
					 * @hooked ciyashop_search_form - 10
					 *
					 * @visible true
					 */
					do_action( 'ciyashop_search_popup_content' );
					?>

					<?php
					/**
					 * Fires after search popup content.
					 *
					 * @visible true
					 */
					do_action( 'ciyashop_after_search_popup_content' );
					?>

				</div>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Get page header template
 *
 * @return void
 */
function ciyashop_page_header() {
	if ( ! is_front_page() && 'static_block' !== get_post_type() ) {
		get_template_part( 'template-parts/page-header' );
	}
}

/**
 * Get footer template
 *
 * @return void
 */
function ciyashop_footer_main() {
	get_template_part( 'template-parts/footer/footer' );
}

/**
 * Get back to top
 *
 * @return void
 */
function ciyashop_bak_to_top() {
	global $ciyashop_options;

	$back_to_top        = isset( $ciyashop_options['back_to_top'] ) ? $ciyashop_options['back_to_top'] : true;
	$back_to_top_mobile = isset( $ciyashop_options['back_to_top_mobile'] ) ? $ciyashop_options['back_to_top_mobile'] : true;

	if ( function_exists( 'wp_is_mobile' ) && wp_is_mobile() && $back_to_top_mobile ) {
		?>
		<div id="back-to-top">
			<a class="top arrow" href="#top"><i class="fa fa-angle-up"></i></a>
		</div>
		<?php
	} elseif ( function_exists( 'wp_is_mobile' ) && ! wp_is_mobile() && $back_to_top ) {
		?>
		<div id="back-to-top">
			<a class="top arrow" href="#top"><i class="fa fa-angle-up"></i></a>
		</div>
		<?php
	}
}

/**
 * Template for cookie notice
 *
 * @return void
 */
function ciyashop_cookie_notice() {
	get_template_part( 'template-parts/footer/cookies_info' );
}

if ( ! function_exists( 'ciyashop_display_comments' ) ) {
	/**
	 * CiyaShop display comments
	 *
	 * @since  1.0.0
	 */
	function ciyashop_display_comments() {
		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || '0' !== (string) get_comments_number() ) :
			comments_template();
		endif;
	}
}

/**
 * Get login form
 *
 * @return void
 */
function ciyashop_login_form() {
	get_template_part( 'woocommerce/custom/login-form' ); // popup login form.
}

/**
 * Get header
 */
function ciyashop_show_header() {
	$page_id = get_the_ID();

	if ( function_exists( 'is_shop' ) && is_shop() ) {
		$page_id = get_option( 'woocommerce_shop_page_id' );
	} elseif ( is_home() && get_option( 'page_for_posts' ) ) {
		$page_id = get_option( 'page_for_posts' );
	} elseif ( is_search() ) {
		$page_id = 0;
	}

	$show_header = get_post_meta( $page_id, 'show_header', true );

	/**
	 * Filters whether to display header.
	 *
	 * @param boolean    $show_header      Whether to display the header.
	 *
	 * @visible true
	 */
	$show_header = apply_filters( 'ciyashop_show_header', $show_header, $page_id );

	if ( '' === $show_header ) {
		$show_header = 1;
	}

	return $show_header;
}

/**
 * Get primary nav menu
 *
 * @return void
 */
function ciyashop_primary_nav_menu() {
	$menu_obj            = '';
	$cs_mega_menu_enable = '';
	$theme_locations     = get_nav_menu_locations();

	if ( isset( $theme_locations['primary'] ) ) {

		$menu_obj = get_term( $theme_locations['primary'], 'nav_menu' );

		if ( isset( $menu_obj->term_id ) && $menu_obj->term_id ) {
			$menu_id             = $menu_obj->term_id;
			$cs_mega_menu_enable = get_post_meta( $menu_id, 'cs_megamenu_enable', true );
		}
	}

	$primary_args = array(
		'theme_location'  => 'primary',
		'menu_class'      => 'menu primary-menu',
		'menu_id'         => 'primary-menu',
		'container'       => false,
		'container_id'    => 'menu-wrap-primary',
		'container_class' => 'menu-wrap',
	);

	if ( 'true' === (string) $cs_mega_menu_enable ) {
		$primary_args['menu_class'] = $primary_args['menu_class'] . ' pgs_megamenu-enable';
		$primary_args['walker']     = new CiyaShop_Walker_Nav_Menu();
	}

	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu( $primary_args );
	} else {
		wp_page_menu(
			array(
				'theme_location' => 'primary',
				'menu_id'        => false,
				'menu_class'     => 'menu primary-menu',
				'container'      => 'div',
				'before'         => '<ul id="primary-menu" class="menu primary-menu nav-menu">',
				'after'          => '</ul>',
				'walker'         => new CiyaShop_Page_Nav_Walker(), // use Walker here.
			)
		);
	}
}

/**
 * Instagram scrape function
 * Based on https://gist.github.com/cosmocatalano/4544576.
 *
 * @param string  $username instagram username.
 * @param integer $count    image count.
 */
function ciyashop_scrape_instagram( $username = '', $count = 9 ) {

	// bail early if no username provided.
	if ( empty( $username ) ) {
		return false;
	}

	$username = trim( strtolower( $username ) );

	switch ( substr( $username, 0, 1 ) ) {
		case '#':
			$url              = 'https://instagram.com/explore/tags/' . str_replace( '#', '', $username );
			$transient_prefix = 'h';
			break;

		default:
			$url              = 'https://instagram.com/' . str_replace( '@', '', $username );
			$transient_prefix = 'u';
			break;
	}

	$instagram_transient_key = 'instagram-032k18-' . $transient_prefix . '-' . sanitize_title_with_dashes( $username );

	$instagram = get_transient( $instagram_transient_key );
	if ( WP_DEBUG || false === $instagram ) {

		$remote = wp_remote_get( $url );

		if ( is_wp_error( $remote ) ) {
			return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'ciyashop' ) );
		}

		if ( 200 !== wp_remote_retrieve_response_code( $remote ) ) {
			return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'ciyashop' ) );
		}

		$shards      = explode( 'window._sharedData = ', $remote['body'] );
		$insta_json  = explode( ';</script>', $shards[1] );
		$insta_array = json_decode( $insta_json[0], true );

		if ( ! $insta_array ) {
			return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'ciyashop' ) );
		}

		if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
			$images = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
		} elseif ( isset( $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ) {
			$images = $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
		} else {
			return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'ciyashop' ) );
		}

		if ( ! is_array( $images ) ) {
			return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'ciyashop' ) );
		}

		$instagram = array();

		foreach ( $images as $image ) {
			if ( true === $image['node']['is_video'] ) {
				$type = 'video';
			} else {
				$type = 'image';
			}

			$caption = esc_html__( 'Instagram Image', 'ciyashop' );
			if ( ! empty( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
				$caption = wp_kses( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'], array() );
			}

			$instagram[] = array(
				'description' => $caption,
				'link'        => trailingslashit( '//instagram.com/p/' . $image['node']['shortcode'] ),
				'time'        => $image['node']['taken_at_timestamp'],
				'comments'    => $image['node']['edge_media_to_comment']['count'],
				'likes'       => $image['node']['edge_liked_by']['count'],
				'thumbnail'   => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][0]['src'] ),
				'thumbnail_w' => $image['node']['thumbnail_resources'][0]['config_width'],
				'thumbnail_h' => $image['node']['thumbnail_resources'][0]['config_height'],

				'small'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][2]['src'] ),
				'small_w'     => $image['node']['thumbnail_resources'][2]['config_width'],
				'small_h'     => $image['node']['thumbnail_resources'][2]['config_height'],

				'large'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][4]['src'] ),
				'large_w'     => $image['node']['thumbnail_resources'][4]['config_width'],
				'large_h'     => $image['node']['thumbnail_resources'][4]['config_height'],

				'original'    => preg_replace( '/^https?\:/i', '', $image['node']['display_url'] ),
				'original_w'  => $image['node']['dimensions']['width'],
				'original_h'  => $image['node']['dimensions']['height'],
				'type'        => $type,
			);
		}

		// do not set an empty transient - should help catch private or empty accounts.
		if ( ! empty( $instagram ) ) {
			/**
			 * Filters arguments for custom widgets.
			 *
			 * @param int    $cache_time      Cache time in seconds.
			 *
			 * @visible true
			 */
			set_transient( $instagram_transient_key, $instagram, apply_filters( 'ciyashop_instagram_cache_time', HOUR_IN_SECONDS * 2 ) );
		}
	}

	if ( ! empty( $instagram ) ) {

		return array_slice( $instagram, 0, $count );

	} else {

		return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'ciyashop' ) );

	}
}

/**
 * Layout content templete call
 *
 * @param string $field   fields.
 * @param string $context cobtext.
 */
function ciyashop_layout_content( $field = '', $context = '' ) {
	if ( empty( $field ) ) {
		return;
	}
	ob_start();
	get_template_part( 'template-parts/header/topbar-elements/' . $field, $context );
	return ob_get_clean();
}

/**
 * Show serach filters.
 */
function ciyashop_show_search() {

	global $ciyashop_options;

	$show_search = isset( $ciyashop_options['show_search'] ) ? $ciyashop_options['show_search'] : true;

	/**
	 * Filters whether to display search.
	 *
	 * @param boolean    $show_search           Whether to display search.
	 * @param array      $ciyashop_options      Array of theme options.
	 *
	 * @visible true
	 */
	$show_search = apply_filters( 'ciyashop_show_search', $show_search, $ciyashop_options );

	return $show_search;

}

if ( ! function_exists( 'ciyashop_social_share' ) ) {
	/**
	 * Social share.
	 *
	 * @param array $args arguments array.
	 */
	function ciyashop_social_share( $args = array() ) {
		global $post, $ciyashop_options;

		$post_type = get_post_type( $post );

		if ( ! class_exists( 'Redux' ) ) {
			return;
		}

		$social_share_links = array();

		$social_share_profiles = array(
			'facebook'        => array(
				'class'      => 'facebook',
				'icon_class' => 'fa fa-facebook',
				'link_base'  => 'https://www.facebook.com/sharer/sharer.php?u=%%url%%',
			),
			'twitter'         => array(
				'class'      => 'twitter',
				'icon_class' => 'fa fa-twitter',
				'link_base'  => 'http://twitter.com/intent/tweet?text=%%title%%&%%url%%',
			),
			'linkedin'        => array(
				'class'      => 'linkedin',
				'icon_class' => 'fa fa-linkedin',
				'link_base'  => 'https://www.linkedin.com/shareArticle?mini=true&url=%%url%%&title=%%title%%&summary=%%text%%',
			),
			'pinterest'       => array(
				'class'      => 'pinterest',
				'icon_class' => 'fa fa-pinterest',
				'link_base'  => 'http://pinterest.com/pin/create/button/?url=%%url%%',
			),
			'googlebookmarks' => array(
				'class'      => 'googlebookmarks',
				'icon_class' => 'fa fa-bookmark-o',
				'link_base'  => 'https://www.google.com/bookmarks/mark?op=edit&bkmk=%%url%%&title=%%title%%&annotation=%%text%%',
			),
			'reddit'          => array(
				'class'      => 'reddit',
				'icon_class' => 'fa fa-reddit',
				'link_base'  => 'https://reddit.com/submit?url=%%url%%&title=%%title%%',
			),
			'tumblr'          => array(
				'class'      => 'tumblr',
				'icon_class' => 'fa fa-tumblr-square',
				'link_base'  => 'https://www.tumblr.com/widgets/share/tool?canonicalUrl=%%url%%&title=%%title%%&caption=%%text%%',
			),
			'stumbleupon'     => array(
				'class'      => 'stumbleupon-circle',
				'icon_class' => 'fa fa-stumbleupon-circle',
				'link_base'  => 'http://www.stumbleupon.com/submit?url=%%url%%',
			),
			'digg'            => array(
				'class'      => 'digg',
				'icon_class' => 'fa fa-digg',
				'link_base'  => 'http://digg.com/submit?url=%%url%%',
			),
			'vk'              => array(
				'class'      => 'vk',
				'icon_class' => 'fa fa-vk',
				'link_base'  => 'http://vk.com/share.php?url=%%url%%&title=%%title%%&comment=%%text%%',
			),
			'xing'            => array(
				'class'      => 'xing',
				'icon_class' => 'fa fa-xing',
				'link_base'  => 'https://www.xing.com/app/user?op=share&url=%%url%%',
			),
			'skype'           => array(
				'class'      => 'skype',
				'icon_class' => 'fa fa-skype',
				'link_base'  => 'https://web.skype.com/share?url=%%url%%&text=%%text%%',
			),
		);

		/**
		 * Filters list of social shares parameters.
		 *
		 * @param array    $social_share_profiles  Array of social share profiles.
		 * @param WP_Post  $post                   The Post object.
		 *
		 * @visible true
		 */
		$social_share_profiles = apply_filters( 'ciyashop_social_share', $social_share_profiles, $post );
		$social_share_profiles = apply_filters( 'ciyashop_social_share_profiles', $social_share_profiles, $post );

		foreach ( $social_share_profiles as $social_share_k => $social_share_d ) {
			$social_share_stat = isset( $ciyashop_options[ $social_share_k . '_share' ] ) ? $ciyashop_options[ $social_share_k . '_share' ] : 1;

			$link = $social_share_profiles[ $social_share_k ]['link_base'];
			$link = str_replace( '%%url%%', get_permalink(), $link );
			$link = str_replace( '%%title%%', get_the_title(), $link );
			$link = str_replace( '%%text%%', get_the_excerpt(), $link );

			if ( $social_share_stat ) {
				$social_share_links[ $social_share_k ] = array(
					'class'      => $social_share_profiles[ $social_share_k ]['class'],
					'icon_class' => $social_share_profiles[ $social_share_k ]['icon_class'],
					'link'       => $link,
					'link_base'  => $social_share_profiles[ $social_share_k ]['link_base'],
				);
			}
		}

		/**
		 * Filters list of social shares links.
		 *
		 * @param array    $social_share_links   Array of social share links.
		 * @param WP_Post  $post                 The Post object.
		 *
		 * @visible true
		 */
		$social_share_links = apply_filters( 'ciyashop_social_share_links', $social_share_links, $post );

		$share_links_class[] = 'pgs-social-share-items';

		if ( isset( $args['class'] ) ) {
			if ( is_string( $args['class'] ) ) {
				$args['class'] = explode( ' ', $args['class'] );
			}

			$share_links_class = array_merge( $share_links_class, $args['class'] );
		}

		$share_links_class = ciyashop_class_builder( $share_links_class );

		if ( ! empty( $social_share_links ) ) {
			?>
			<ul class="<?php echo esc_attr( $share_links_class ); ?>">
				<?php
				foreach ( $social_share_links as $social_share_link_k => $social_share_link ) {
					?>
					<li class="pgs-social-share-item pgs-social-share-item-<?php echo esc_attr( $social_share_link_k ); ?>">
						<a href="<?php echo esc_url( $social_share_link['link'] ); ?>" class="share-link <?php echo esc_attr( $social_share_link['class'] ); ?>-share" target="popup" onclick="window.open('<?php echo esc_url( $social_share_link['link'] ); ?>','popup','width=600,height=600'); return false;">
							<i class="<?php echo esc_attr( $social_share_link['icon_class'] ); ?>"></i>
						</a>
					</li>
					<?php
				}
				?>
			</ul>
			<?php
		}
	}
}

function ciyashop_get_page_builders() {
	$page_builders = array(
		'wpbakery'  => array(
			'name'  => 'wpbakery',
			'label' => esc_html__( 'WPBakery Page Builder', 'ciyashop' ),
		),
		'elementor' => array(
			'name'  => 'elementor',
			'label' => esc_html__( 'Elementor', 'ciyashop' ),
		),
	);

	$page_builders = apply_filters( 'ciyashop_page_builders', $page_builders );

	return $page_builders;
}
function ciyashop_get_default_page_builder() {
	$page_builder_option = '';
	$page_builders       = ciyashop_get_page_builders();

	if ( class_exists( 'WPBakeryVisualComposerAbstract' ) && did_action( 'elementor/loaded' ) ) {
		$page_builder_option = 'wpbakery';
	} elseif ( class_exists( 'WPBakeryVisualComposerAbstract' ) && ! did_action( 'elementor/loaded' ) ) {
		$page_builder_option = 'wpbakery';
	} elseif ( ! class_exists( 'WPBakeryVisualComposerAbstract' ) && did_action( 'elementor/loaded' ) ) {
		$page_builder_option = 'elementor';
	}

	if ( ! $page_builder_option ) {
		$page_builder_option = get_option( 'ciyashop_default_page_builder' );
	}

	$default_page_builder = ( $page_builder_option && array_key_exists( $page_builder_option, $page_builders ) ) ? $page_builder_option : 'wpbakery';
	$default_page_builder = apply_filters( 'ciyashop_get_default_page_builder', $default_page_builder );

	return $default_page_builder;
}
