<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * VC custom heading file
 *
 * @package CiyaShop
 */

if ( ! defined( 'ABSPATH' ) ) {
	wp_die( '-1' );
}

/**
 * Shortcode attributes
 *
 * @var $atts
 * @var $source
 * @var $text
 * @var $links
 * @var $google_fonts
 * @var $font_container
 * @var $el_class
 * @var $css
 * @var $css_animation
 * @var $font_container_data - returned from $this->getAttributes
 * @var $google_fonts_data - returned from $this->getAttributes
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Custom_heading
 */
$source                = '';
$text                  = '';
$links                 = '';
$google_fonts          = '';
$font_container        = '';
$el_class              = '';
$css                   = '';
$css_animation         = '';
$font_container_data   = '';
$google_fonts_data     = '';
$font_weight           = '';
$text_transform        = '';
$letter_spacing        = '';
$letter_spacing_custom = '';

// This is needed to extract $font_container_data and $google_fonts_data.
extract( $this->getAttributes( $atts ) ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

if ( empty( trim( $el_class ) ) ) {
	$el_class .= 'vc_custom_heading-text_align_' . $font_container_data['values']['text_align'];
} else {
	$el_class .= ' vc_custom_heading-text_align_' . $font_container_data['values']['text_align'];
}

/**
 * CSS class
 *
 * @var $css_class
 */
extract( $this->getStyles( $el_class . $this->getCSSAnimation( $css_animation ), $css, $google_fonts_data, $font_container_data, $atts ) ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$allowed_tags = wp_kses_allowed_html( 'post' );

$settings = get_option( 'wpb_js_google_fonts_subsets' );
if ( is_array( $settings ) && ! empty( $settings ) ) {
	$subsets = '&subset=' . implode( ',', $settings );
} else {
	$subsets = '';
}

if ( ( ! isset( $atts['use_theme_fonts'] ) || 'yes' !== $atts['use_theme_fonts'] ) && isset( $google_fonts_data['values']['font_family'] ) ) {
	wp_enqueue_style( 'vc_google_fonts_' . ciyashop_build_safe_css_class( $google_fonts_data['values']['font_family'] ), '//fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets, array(), THEME_VERSION );
}

if ( 'yes' === $use_theme_fonts && $use_theme_fonts ) {
	if ( $font_weight && ! empty( $font_weight ) ) {
		$styles[] = "font-weight: {$font_weight}";
	}
	if ( $text_transform && ! empty( $text_transform ) ) {
		$styles[] = "text-transform: {$text_transform}";
	}
	if ( $letter_spacing && ! empty( $letter_spacing ) ) {
		if ( 'custom' === $letter_spacing ) {
			if ( $letter_spacing_custom && ! empty( $letter_spacing_custom ) && ciyashop_validate_css_unit( $letter_spacing_custom, array( 'px', 'em', '%' ) ) ) {
				$styles[] = "letter-spacing: {$letter_spacing_custom}";
			}
		} else {
			$styles[] = "letter-spacing: {$letter_spacing}";
		}
	}
}

if ( ! empty( $styles ) ) {
	$style = implode( ';', $styles );
} else {
	$style = '';
}

if ( 'post_title' === $source ) {
	$text = get_the_title( get_the_ID() );
}

if ( ! empty( $links ) ) {
	$links = ( function_exists( 'vc_build_link' ) ) ? vc_build_link( $links ) : ciyashop_build_link( $links );
	$text  = '<a href="' . esc_url( $links['url'] ) . '"' . ( $links['target'] ? ' target="' . esc_attr( $links['target'] ) . '"' : '' ) . ( $links['rel'] ? ' rel="' . esc_attr( $links['rel'] ) . '"' : '' ) . ( $links['title'] ? ' title="' . esc_attr( $links['title'] ) . '"' : '' ) . '>' . $text . '</a>';
}

if ( apply_filters( 'vc_custom_heading_template_use_wrapper', false ) ) {
	$el_tag = $font_container_data['values']['tag'];
	?>
	<div class="section-title <?php echo esc_attr( $css_class ); ?>">
		<<?php echo esc_attr( $el_tag ); ?> style="<?php echo esc_attr( $style ); ?>" class="title">
			<?php echo wp_kses( $text, array( 'a' => $allowed_tags['a'] ) ); ?>
		</<?php echo esc_attr( $el_tag ); ?>>
	</div>
	<?php
} else {
	$el_tag = $font_container_data['values']['tag'];
	?>
	<<?php echo esc_attr( $el_tag ); ?> style="<?php echo esc_attr( $style ); ?>" class="title">
		<?php echo wp_kses( $text, array( 'a' => $allowed_tags['a'] ) ); ?>
	</<?php echo esc_attr( $el_tag ); ?>>
	<?php
}
