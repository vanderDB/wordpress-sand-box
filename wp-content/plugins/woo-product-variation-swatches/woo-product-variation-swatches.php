<?php
/**
 * Plugin Name:             Variation Swatches for WooCommerce
 * Plugin URI:              https://radiustheme.com
 * Description:             Beautiful Colors, Images and Buttons Variation Swatches For WooCommerce Product Attributes
 * Version:                 1.1.55.2
 * Author:                  RadiusTheme
 * Author URI:              https://radiustheme.com
 * Requires at least:       4.8
 * Tested up to:            5.7
 * WC requires at least:    3.2
 * WC tested up to:         5.1
 * Domain Path:             /languages
 * Text Domain:             woo-product-variation-swatches
 */

// Define RTWPVS_PLUGIN_FILE.
if (!defined('RTWPVS_PLUGIN_FILE')) {
    define('RTWPVS_PLUGIN_FILE', __FILE__);
}

// Define RTCL_PLUGIN_FILE.
if (!defined('RTWPVS_VERSION')) {
    $plugin_data = get_file_data(__FILE__, array('version' => 'Version'), false);
    define('RTWPVS_VERSION', $plugin_data['version']);
}

if (!class_exists('WooProductVariationSwatches')) {
    require_once("app/WooProductVariationSwatches.php");
}