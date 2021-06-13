<?php
/**
 * The template for displaying the header
 *
 * @package Ciyashop
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php echo esc_attr( get_bloginfo( 'charset' ) ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="format-detection" content="telephone=no" />
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url' ) ); ?>">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
global $ciyashop_options;
$logo_style = '';
if ( isset( $ciyashop_options ) && ! empty( $ciyashop_options['site-logo-height'] ) ) {
	$logo_style = 'height:' . $ciyashop_options['site-logo-height']['height'];
}
?>

<!-- Main Body Wrapper Element -->
<div id="page" class="hfeed site page-wrapper">

	<header id="header" class="topbar-light">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
			<img class="logo-type-default" src="<?php echo esc_url( ciyashop_logo_url() ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" style="<?php echo esc_attr( $logo_style ); ?>"/>
		</a>
	</header>
	<div class="wrapper" id="main">
