<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName

update_option( 'ciyashop_pgs_token', 'activated');
update_option( 'ciyashop_theme_purchase_key', 'activated' );
update_option( 'ciyashop_purchase_code_notices', false );

/**
 * CiyaShop functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package CiyaShop
 */

ciyashop_globals_n_constants();
/**
 * Ciyashop globals and constants
 */
function ciyashop_globals_n_constants() {

	// Globals.
	global $ciyashop_globals, $ciyashop_theme_data, $cs_product_list_styles;

	$ciyashop_globals['current_theme'] = get_template();
	$ciyashop_globals['theme_data']    = wp_get_theme( get_template() );
	$ciyashop_globals['theme_title']   = esc_html__( 'CiyaShop', 'ciyashop' );
	$ciyashop_globals['theme_slug']    = 'ciyashop';
	$ciyashop_globals['theme_name']    = 'ciyashop';
	$ciyashop_globals['theme_option']  = 'ciyashop_options';

	$ciyashop_globals['options_title'] = esc_html__( 'CiyaShop Theme Options', 'ciyashop' );
	$ciyashop_globals['options_slug']  = 'ciyashop-options';
	$ciyashop_globals['options_name']  = 'ciyashop_options';

	/**
	 *
	 * Define Product List Styles
	 *
	 * WooCommerce Product List styles mapped with theme options slug
	 */

	$cs_product_list_styles = array(
		'default'                   => 'default',
		'icons-top-left'            => 'icon-top-left',
		'icons-top-right'           => 'icons-top-right',
		'image-icon-left'           => 'image-icon-left',
		'image-icon-bottom'         => 'image-icon-bottom',
		'icons-center'              => 'image-center',
		'standard-icons-left'       => 'image-left',
		'icons-bottom-right'        => 'icons-bottom-right',
		'icons-separate'            => 'image-bottom',
		'icons-bottom'              => 'image-bottom-2',
		'icons-bottom-bar'          => 'image-bottom-bar',
		'info-bottom'               => 'info-bottom',
		'info-bottom-bar'           => 'info-bottom-bar',
		'hover-summary'             => 'hover-summary',
		'button-standard'           => 'button-standard',
		'icons-left'                => 'icons-left',
		'icons-rounded'             => 'icons-rounded',
		'minimal-hover-cart'        => 'minimal-hover-cart',
		'minimal'                   => 'minimal',
		'info-transparent-center'   => 'info-transparent-center',
		'icons-transparent-center'  => 'icons-transparent-center',
		'standard-info-transparent' => 'standard-info-transparent',
		'standard-quick-shop'       => 'standard-quick-shop',
	);

	// Backwards compatibility for __DIR__.
	if ( ! defined( '__DIR__' ) ) { // phpcs:ignore PHPCompatibility.Keywords.ForbiddenNames.__dir__Found
		define( '__DIR__', dirname( __FILE__ ) ); // phpcs:ignore PHPCompatibility.Keywords.ForbiddenNames.__dir__Found
	}

	define( 'PGS_PRODUCT_KEY', 'c097cb30fd31dc506445323601d9c14c' );
	if ( ! defined( 'PGS_ENVATO_API' ) ) {
		define( 'PGS_ENVATO_API', 'https://envatoapi.potenzaglobalsolutions.com/' );
	}

	/**
	 * Filters lazy loading's loader image url.
	 *
	 * @param string $loader_image Link of lazy loading loader image.
	 *
	 * @visible true
	 */
	$loader_image = apply_filters( 'ciyashop_loader_image', get_template_directory_uri() . '/images/loader.gif' );
	if ( ! defined( 'LOADER_IMAGE' ) ) {
		define( 'LOADER_IMAGE', $loader_image );
	}

	if ( ! defined( 'THEME_VERSION' ) ) {
		$my_theme = wp_get_theme();
		define( 'THEME_VERSION', $my_theme->get( 'Version' ) );
	}
}

if ( ! function_exists( 'ciyashop_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function ciyashop_setup() {

		/**
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on CiyaShop, use a find and replace
		 * to change 'ciyashop' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'ciyashop', get_parent_theme_file_path( '/languages' ) );

		// Add PGS Core support.
		add_theme_support( 'pgs-core' );

		add_theme_support( 'pgs-welcome' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// Custom Thumbnail Sizes.
		/**
		 * Filters theme's predefined custom thumbnail sizes.
		 *
		 * @param array $image_sizes Array of custom image sizes.
		 *
		 * @visible true
		 */
		$image_sizes = apply_filters(
			'ciyashop_image_sizes',
			array(
				'ciyashop-thumbnail-small'         => array(
					'name'   => 'ciyashop-thumbnail-small',
					'width'  => 80,
					'height' => 80,
					'crop'   => true,
				),
				'ciyashop-logo-carousel'           => array(
					'name'   => 'ciyashop-logo-carousel',
					'width'  => 200,
					'height' => 90,
					'crop'   => true,
				),
				'ciyashop-latest-post-thumbnail'   => array(
					'name'   => 'ciyashop-latest-post-thumbnail',
					'width'  => 500,
					'height' => 375,
					'crop'   => true,
				),
				'ciyashop-team-member-thumbnail-v' => array(
					'name'   => 'ciyashop-team-member-thumbnail-v',
					'width'  => 450,
					'height' => 700,
					'crop'   => true,
				),
				'ciyashop-blog-thumb'              => array(
					'name'   => 'ciyashop-blog-thumb',
					'width'  => 1400,
					'height' => 600,
					'crop'   => true,
				),
				'ciyashop-product-180x230'         => array(
					'name'   => 'ciyashop-product-180x230',
					'width'  => 180,
					'height' => 230,
					'crop'   => true,
				),
			)
		);

		foreach ( $image_sizes as $image_size ) {
			if (
				( isset( $image_size['name'] ) && ! empty( $image_size['name'] ) )
				&& ( isset( $image_size['width'] ) && ! empty( $image_size['width'] ) && is_numeric( $image_size['width'] ) )
				&& ( isset( $image_size['height'] ) && ! empty( $image_size['height'] ) && is_numeric( $image_size['height'] ) )
			) {
				$image_size_crop = false;
				if ( isset( $image_size['crop'] ) ) {
						$image_size_crop = $image_size['crop'];
				}
				add_image_size(
					$image_size['name'],
					$image_size['width'],
					$image_size['height'],
					$image_size_crop
				);
			}
		}

		// Support for post formats.
		add_theme_support(
			'post-formats',
			array(
				'aside',  // title less blurb.
				'gallery', // gallery of images.
				'link',   // quick link to other site.
				'image',  // an image.
				'quote',  // a quick quote.
				'status', // a Facebook like status update.
				'video',  // video.
				'audio',  // audio.
				'chat',   // chat transcript.
			)
		);

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary'          => esc_html__( 'Primary Menu', 'ciyashop' ),
				'mobile_menu'      => esc_html__( 'Mobile Menu', 'ciyashop' ),
				'categories_menu'  => esc_html__( 'Categories Menu', 'ciyashop' ),
				'top_menu'         => esc_html__( 'Topbar Menu', 'ciyashop' ),
				'footer_menu'      => esc_html__( 'Footer Menu', 'ciyashop' ),
				'shortcode_v_menu' => esc_html__( 'Shortcode - Vertical Menu', 'ciyashop' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		// Declare WooCommerce support.
		/**
		 * Filters parameters of adding theme support for WooCommerce in the theme.
		 *
		 * @param array $woocommerce_args Array of parameters for adding theme support for WooCommerce.
		 *
		 * @visible true
		 */
		add_theme_support(
			'woocommerce',
			apply_filters(
				'ciyashop_woocommerce_args',
				array(
					'single_image_width'    => 600,
					'thumbnail_image_width' => 510,
					'product_grid'          => array(
						'default_columns' => 3,
						'default_rows'    => 4,
						'min_columns'     => 1,
						'max_columns'     => 6,
						'min_rows'        => 1,
					),
				)
			)
		);

		// Add Woocommerce theme support.
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-lightbox' ); // Enable Popup.

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		add_action( 'widgets_init', 'ciyashop_sidebars_init' );

		/* Remove WooCommerce OutPut Content Wrapper*/
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

		// adding the search form.
		add_filter( 'get_search_form', 'ciyashop_wpsearch' );

		// Custom Post Supports.
		add_theme_support( 'pgs_faqs' );
		add_theme_support( 'pgs_testimonials' );
		add_theme_support( 'pgs_teams' );
		add_theme_support( 'pgs_portfolio' );

		// Add theme support for shortcodes.
		add_theme_support( 'pgscore_shortcodes' );

		// Add theme support for Massive Addons.
		add_theme_support( 'massive-addons' );
	}
endif;
add_action( 'after_setup_theme', 'ciyashop_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function ciyashop_content_width() {
	/**
	 * Filters the maximum allowed width for any content in the theme, like oEmbeds and images added to posts.
	 *
	 * @param int $content_width Content width. Default 640.
	 *
	 * @visible true
	 */
	$GLOBALS['content_width'] = apply_filters( 'ciyashop_content_width', 640 );
}
add_action( 'after_setup_theme', 'ciyashop_content_width', 0 );
if ( ! function_exists( 'ciyashop_wpsearch' ) ) {
	/**
	 * Search Form
	 * Call using get_search_form().
	 *
	 * @param string $form .
	 * @package CiyaShop
	 */
	function ciyashop_wpsearch( $form ) {
		ob_start();
		get_template_part( 'template-parts/search-form' );
		$form = ob_get_clean();
		return $form;
	}
}

require_once get_parent_theme_file_path( '/includes/base_functions.php' );                // Load Base functions file.

// Includes for Admin & Front.
require_once get_parent_theme_file_path( '/includes/sidebars.php' );
require_once get_parent_theme_file_path( '/includes/menus.php' );
require_once get_parent_theme_file_path( '/includes/icons/icons.php' );
require_once get_parent_theme_file_path( '/includes/external-lib-fix.php' );
require_once get_parent_theme_file_path( '/includes/redux/redux-init.php' );
require_once get_parent_theme_file_path( '/includes/scripts_and_styles.php' );
require_once get_parent_theme_file_path( '/includes/jetpack.php' );                      // Load Jetpack compatibility file.
require_once get_parent_theme_file_path( '/includes/customizer.php' );                   // Customizer additions.
require_once get_parent_theme_file_path( '/includes/extras.php' );                       // Custom functions that act independently of the theme templates.
require_once get_parent_theme_file_path( '/includes/vc/vc-fallback-functions.php' );     // Fallback for vc based functions.

// Extend VC.
global $vc_manager;
if ( $vc_manager ) {
	require_once get_parent_theme_file_path( '/includes/vc/vc-init-admin.php' );
	require_once get_parent_theme_file_path( '/includes/vc/vc-init.php' );
}

// Includes for Admin.
if ( is_admin() ) {
	require_once get_parent_theme_file_path( '/includes/tgm-plugin-activation/tgm-init.php' ); // Load TGM Plugin compatibility file.
	require_once get_parent_theme_file_path( '/includes/admin/admin-init.php' );
	require_once get_parent_theme_file_path( '/includes/sample-data.php' );
	require_once get_parent_theme_file_path( '/includes/theme-setup-wizard/wizard.php' );
	require_once get_parent_theme_file_path( '/includes/promo_notice.php' );
	require_once get_parent_theme_file_path( '/includes/class-ciyashop-admin-notices.php' );
}

// Includes for Front.
require_once get_parent_theme_file_path( '/includes/custom_sidebar.php' );
require_once get_parent_theme_file_path( '/includes/template-functions.php' );
require_once get_parent_theme_file_path( '/includes/template-hooks.php' );
require_once get_parent_theme_file_path( '/includes/template-tags.php' );         // Custom template tags for this theme.
require_once get_parent_theme_file_path( '/includes/template-classes.php' );
require_once get_parent_theme_file_path( '/includes/comments.php' );
require_once get_parent_theme_file_path( '/includes/maintenance.php' );
require_once get_parent_theme_file_path( '/includes/acf_ported_functions.php' );
require_once get_parent_theme_file_path( '/includes/dynamic_css.php' );           // Dynamic CSS.
require_once get_parent_theme_file_path( '/includes/login.php' );                 // Login Page Settings.

if ( ! function_exists( 'ciyashop_elementor_register_location' ) ) {
	/**
	 * Register Elementor Locations.
	 */
	function ciyashop_elementor_register_location( $elementor_theme_manager ) {
		$elementor_theme_manager->register_location(
			'header',
			[
				'public'          => false,
				'label'           => esc_html__( 'Header', 'ciyashop' ),
				'edit_in_content' => false,
			]
		);

		$elementor_theme_manager->register_location(
			'footer',
			[
				'public'          => false,
				'label'           => esc_html__( 'Footer', 'ciyashop' ),
				'edit_in_content' => false,
			]
		);
	}

	add_action( 'elementor/theme/register_locations', 'ciyashop_elementor_register_location' );
}

// Includes for WooCommerce.
if ( class_exists( 'WooCommerce' ) ) {
	require_once get_parent_theme_file_path( '/includes/woocommerce/init.php' );
	require_once get_parent_theme_file_path( '/includes/woocommerce/init-front.php' );
}

if ( ! class_exists( 'Ciyashop_Theme_Activation' ) ) {
	/**
	 * Ciyashop Theme Activation
	 */
	class Ciyashop_Theme_Activation {
		/**
		 * Ciyashop verify theme
		 */
		public static function ciyashop_verify_theme() {
			return ciyashop_is_activated();
		}
	}
}


if ( ! function_exists( 'ciyashop_welcome_fallback_menu_page' ) ) {
	/**
	 * Fallback for old welcome page
	 */
	function ciyashop_welcome_fallback_menu_page() {
		add_menu_page( // phpcs:ignore WPThemeReview.PluginTerritory.NoAddAdminPages.add_menu_pages_add_menu_page
			esc_html__( 'CiyaShop Welcome', 'ciyashop' ),
			esc_html__( 'CiyaShop', 'ciyashop' ),
			'manage_options',
			'cs-welcome',
			'ciyashop_welcome_fallback_menu_content'
		);

		remove_menu_page( 'cs-welcome' );
	}
}
add_action( 'admin_menu', 'ciyashop_welcome_fallback_menu_page' );

if ( ! function_exists( 'ciyashop_welcome_fallback_menu_content' ) ) {
	/**
	 * Welcome fallback menu content
	 */
	function ciyashop_welcome_fallback_menu_content() {
		$url = add_query_arg(
			array(
				'page' => 'ciyashop-panel',
			),
			admin_url( 'admin.php' )
		);

		// redirect to new page.
		wp_safe_redirect( $url );
	}
}

if ( ! function_exists( 'ciyashop_lazyload_thumbnail' ) ) {
	/**
	 * Lazyload thumbnail
	 *
	 * @param string $post_id .
	 * @param string $size .
	 * @param array  $attr .
	 */
	function ciyashop_lazyload_thumbnail( $post_id, $size = 'full', $attr = array() ) {

		global $ciyashop_options;

		if ( empty( $post_id ) ) {
			return;
		}

		if ( isset( $ciyashop_options['enable_lazyload'] ) && $ciyashop_options['enable_lazyload'] ) {
			$image_src      = get_the_post_thumbnail_url( $post_id, $size );
			$attr[]         = 'ciyashop-lazy-load';
			$class          = implode( ' ', $attr );
			$thumbnail_html = '<img class="' . $class . '" src="' . esc_url( LOADER_IMAGE ) . '" data-src="' . esc_url( $image_src ) . '" alt="' . esc_attr( get_the_title( $post_id ) ) . '"/>';
		} else {
			$thumbnail_html = get_the_post_thumbnail( $post_id, $size, $attr );
		}

		return $thumbnail_html;
	}
}
