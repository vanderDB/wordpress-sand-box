<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Social Profile.
 *
 * @package CiyaShop
 */

$ciyashop_social_profiles = ciyashop_social_profiles();

if ( ! empty( $ciyashop_social_profiles ) && is_array( $ciyashop_social_profiles ) ) {
	$social_content  = '<div class="topbar-social_profiles-wrapper">';
	$social_content .= '<ul class="topbar-social_profiles">';
	foreach ( $ciyashop_social_profiles as $ciyashop_social_profile ) {
		$social_content .= '<li class="topbar-social_profile">';
		$social_content .= '<a href="' . esc_url( $ciyashop_social_profile['link'] ) . '" target="_blank">' . $ciyashop_social_profile['icon'] . '</a>';
		$social_content .= '</li>';
	}
	$social_content .= '</ul>';
	$social_content .= '</div>';

	echo wp_kses_post( $social_content );
}
