<?php
/**
 * Plugin Name: WooCommerce Product Comparison
 * Description: A WooCommerce plugin to compare products with a customizable popup.
 * Version:     1.0.0
 * Author:      Sohil Vahora
 * Text Domain: wc-product-comparison
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define Plugin Constants
define('WPC_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WPC_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include Core Classes
require_once WPC_PLUGIN_PATH . 'includes/class-wpc-init.php';

// Initialize Plugin
function wpc_initialize_plugin() {
    new WPC_Init();
}
add_action('plugins_loaded', 'wpc_initialize_plugin');
