<?php
/**
 * Theme VC templates
 *
 * @package CiyaShop
 */

/**
 * Get vc templates
 */
function ciyashop_get_vc_templates() {

	$vc_templates_uri = get_parent_theme_file_uri( '/includes/vc/templates/' );
	$vc_templates_dir = get_parent_theme_file_path( '/includes/vc/templates/' );

	$image_fallback = untrailingslashit( $vc_templates_uri ) . '/images/default.jpg';

	$templates      = array();
	$templates_list = array();

	$templates_list_path = get_parent_theme_file_path( 'includes/vc/templates-list.php' );
	if ( file_exists( $templates_list_path ) ) {
		$templates_list_raw = include $templates_list_path; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		if ( $templates_list_raw && is_array( $templates_list_raw ) ) {
			$templates_list = $templates_list_raw;
		}
	}

	foreach ( $templates_list as $template_name ) {
		$template_file = untrailingslashit( $vc_templates_dir ) . "/$template_name.php";

		if ( file_exists( $template_file ) ) {

			$template_data = include $template_file; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			if ( $template_data && is_array( $template_data ) && ( isset( $template_data['name'] ) && ! empty( $template_data['name'] ) ) && ( isset( $template_data['content'] ) && ! empty( $template_data['content'] ) ) ) {

				if ( ! isset( $template_data['new'] ) ) {
					$template_data['new'] = false;
				}

				if ( ! isset( $template_data['disabled'] ) ) {
					$template_data['disabled'] = true;
				}

				if ( ! isset( $template_data['template_category'] ) || empty( $template_data['template_category'] ) ) {
					$template_data['template_category'] = esc_html__( 'Misc', 'ciyashop' );
				}

				$template_data['template_category_slug'] = sanitize_title( $template_data['template_category'] );

				$template_category_slug = sanitize_title( $template_data['template_category'] );

				$image_path = untrailingslashit( $vc_templates_dir ) . "/images/$template_category_slug/$template_name.jpg";
				$image_uri  = untrailingslashit( $vc_templates_uri ) . "/images/$template_category_slug/$template_name.jpg";

				if ( ! file_exists( $image_path ) ) {
					$template_data['image_path_original'] = preg_replace( '/\s/', '%20', $image_uri );
					$image_uri                            = $image_fallback;
				}

				$template_data['image_path'] = preg_replace( '/\s/', '%20', $image_uri );
				$templates[]                 = $template_data;
			}
		}
	}

	return $templates;
}
