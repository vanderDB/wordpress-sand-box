<?php
/**
 * VC init admin
 *
 * @package CiyaShop
 */

if ( class_exists( 'WPBakeryVisualComposerAbstract' ) ) {
	include get_parent_theme_file_path( 'includes/vc/theme-vc-templates.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	include get_parent_theme_file_path( 'includes/vc/theme-vc-templates-panel-editor.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	add_action( 'admin_print_scripts-post.php', 'ciyashop_vc_templates_enqueue_scripts' );
	add_action( 'admin_print_scripts-post-new.php', 'ciyashop_vc_templates_enqueue_scripts' );
}
/**
 * Templates enqueue scripts
 */
function ciyashop_vc_templates_enqueue_scripts() {

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	$post_type = get_post_type();

	wp_register_script( 'jquery-lazy', get_parent_theme_file_uri( '/js/jquery.lazy/jquery.lazy.min.js' ), array(), '1.7.9', true );

	// VC Templates Backend.
	if ( ! vc_is_frontend_editor() && vc_check_post_type( $post_type ) ) {

		wp_register_script( 'ciyashop_vc_templates_js', get_parent_theme_file_uri( '/js/vc-templates' . $suffix . '.js' ), array( 'ciyashop_admin_js', 'jquery-lazy' ), WPB_VC_VERSION, true );
		wp_enqueue_script( 'ciyashop_vc_templates_js' );
	}

	// VC Templates Frontend.
	if ( vc_is_frontend_editor() ) {
		wp_register_script( 'ciyashop_vc_templates_js', get_parent_theme_file_uri( '/js/vc-templates' . $suffix . '.js' ), array( 'vc-frontend-editor-min-js', 'jquery-lazy' ), WPB_VC_VERSION, true );
		wp_enqueue_script( 'ciyashop_vc_templates_js' );
	}
}

add_action( 'init', 'ciyashop_extend_vc_shortcodes' );
/**
 * Extend vc shortcodes
 */
function ciyashop_extend_vc_shortcodes() {
	include get_parent_theme_file_path( 'includes/vc/shortcodes/vc_row.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	include get_parent_theme_file_path( 'includes/vc/shortcodes/vc_row_inner.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	include get_parent_theme_file_path( 'includes/vc/shortcodes/vc_column.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	include get_parent_theme_file_path( 'includes/vc/shortcodes/vc_column_inner.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	include get_parent_theme_file_path( 'includes/vc/shortcodes/shortcode-vc-icon.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	include get_parent_theme_file_path( 'includes/vc/shortcodes/shortcode-vc-message.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	include get_parent_theme_file_path( 'includes/vc/shortcodes/shortcode-vc-tta-accordion.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	include get_parent_theme_file_path( 'includes/vc/shortcodes/shortcode-vc-tta-section.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	include get_parent_theme_file_path( 'includes/vc/shortcodes/shortcode-vc-tta-tabs.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	include get_parent_theme_file_path( 'includes/vc/shortcodes/shortcode-vc-tta-tour.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	include get_parent_theme_file_path( 'includes/vc/shortcodes/vc_custom_heading.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
}

add_filter( 'vc_base_build_shortcodes_custom_css', 'ciyashop_parse_vc_shortcodes_custom_css', 12, 3 );
/**
 * Parse vc shortcodes custom css
 *
 * @param string $content .
 * @param string $post_id .
 * @param bool   $recur .
 */
function ciyashop_parse_vc_shortcodes_custom_css( $content, $post_id, $recur = false ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! $recur ) {
		$post = get_post( $post_id );
		if ( $post ) {
			$content = $post->post_content;
		}
	}

	$css = '';
	if ( ! preg_match( '/\s*(\.[^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', $content ) ) {
		return $css;
	}
	WPBMap::addAllMappedShortcodes();
	preg_match_all( '/' . get_shortcode_regex() . '/', $content, $shortcodes );

	foreach ( $shortcodes[2] as $index => $tag ) {

		$shortcode  = WPBMap::getShortCode( $tag );
		$attr_array = shortcode_parse_atts( trim( $shortcodes[3][ $index ] ) );

		if ( isset( $shortcode['params'] ) && ! empty( $shortcode['params'] ) ) {
			foreach ( $shortcode['params'] as $param ) {
				if ( isset( $param['type'] ) && 'css_editor' === $param['type'] && isset( $attr_array[ $param['param_name'] ] ) ) {
					if ( 'element_css_md' === (string) $param['param_name'] || 'element_css_sm' === (string) $param['param_name'] || 'element_css_xs' === (string) $param['param_name'] ) {
						continue;
					}
					$css .= $attr_array[ $param['param_name'] ];
				}
			}
		}
		if ( 'vc_row' === (string) $tag && ( isset( $attr_array['pgscore_enable_responsive_settings'] ) && 'true' === (string) $attr_array['pgscore_enable_responsive_settings'] ) ) {
			if ( isset( $attr_array['element_css_md'] ) && ! empty( $attr_array['element_css_md'] ) ) {
				$css .= '@media (max-width: 1200px) {' . $attr_array['element_css_md'] . '}';
			}
			if ( isset( $attr_array['element_css_sm'] ) && ! empty( $attr_array['element_css_sm'] ) ) {
				$css .= '@media (max-width: 992px) {' . $attr_array['element_css_sm'] . '}';
			}
			if ( isset( $attr_array['element_css_xs'] ) && ! empty( $attr_array['element_css_xs'] ) ) {
				$css .= '@media (max-width: 767px) {' . $attr_array['element_css_xs'] . '}';
			}
		}
		if ( 'vc_row_inner' === (string) $tag && ( isset( $attr_array['pgscore_enable_responsive_settings'] ) && 'true' === (string) $attr_array['pgscore_enable_responsive_settings'] ) ) {
			if ( isset( $attr_array['element_css_md'] ) && ! empty( $attr_array['element_css_md'] ) ) {
				$css .= '@media (max-width: 1200px) {' . $attr_array['element_css_md'] . '}';
			}
			if ( isset( $attr_array['element_css_sm'] ) && ! empty( $attr_array['element_css_sm'] ) ) {
				$css .= '@media (max-width: 992px) {' . $attr_array['element_css_sm'] . '}';
			}
			if ( isset( $attr_array['element_css_xs'] ) && ! empty( $attr_array['element_css_xs'] ) ) {
				$css .= '@media (max-width: 767px) {' . $attr_array['element_css_xs'] . '}';
			}
		}
		if ( 'vc_column' === (string) $tag && ( isset( $attr_array['pgscore_enable_responsive_settings'] ) && 'true' === (string) $attr_array['pgscore_enable_responsive_settings'] ) ) {
			if ( isset( $attr_array['element_css_md'] ) && ! empty( $attr_array['element_css_md'] ) ) {
				$css .= '@media (max-width: 1200px) {' . $attr_array['element_css_md'] . '}';
			}
			if ( isset( $attr_array['element_css_sm'] ) && ! empty( $attr_array['element_css_sm'] ) ) {
				$css .= '@media (max-width: 992px) {' . $attr_array['element_css_sm'] . '}';
			}
			if ( isset( $attr_array['element_css_xs'] ) && ! empty( $attr_array['element_css_xs'] ) ) {
				$css .= '@media (max-width: 767px) {' . $attr_array['element_css_xs'] . '}';
			}
		}
		if ( 'vc_column_inner' === (string) $tag && ( isset( $attr_array['pgscore_enable_responsive_settings'] ) && 'true' === (string) $attr_array['pgscore_enable_responsive_settings'] ) ) {
			if ( isset( $attr_array['element_css_md'] ) && ! empty( $attr_array['element_css_md'] ) ) {
				$css .= '@media (max-width: 1200px) {' . $attr_array['element_css_md'] . '}';
			}
			if ( isset( $attr_array['element_css_sm'] ) && ! empty( $attr_array['element_css_sm'] ) ) {
				$css .= '@media (max-width: 992px) {' . $attr_array['element_css_sm'] . '}';
			}
			if ( isset( $attr_array['element_css_xs'] ) && ! empty( $attr_array['element_css_xs'] ) ) {
				$css .= '@media (max-width: 767px) {' . $attr_array['element_css_xs'] . '}';
			}
		}
	}

	foreach ( $shortcodes[5] as $shortcode_content ) {
		$css .= ciyashop_parse_vc_shortcodes_custom_css( $shortcode_content, $post_id, $recur = true );
	}

	return $css;
}
