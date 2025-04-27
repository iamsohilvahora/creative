<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handles plugin initialization.
 */
class WPC_Init {
    public function __construct() {
        // Check if WooCommerce is installed
        add_action('admin_init', [$this, 'check_woocommerce']);

        // Load required files
        $this->includes();
    }

    /**
     * Check if WooCommerce is installed and active.
     */
    public function check_woocommerce() {
        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', [$this, 'woocommerce_missing_notice']);
            deactivate_plugins(plugin_basename(WPC_PLUGIN_PATH . 'wc-product-comparison.php'));
        }
    }

    /**
     * Display admin notice if WooCommerce is missing.
     */
    public function woocommerce_missing_notice() {
        echo '<div class="error"><p><strong>WooCommerce Product Comparison</strong> requires <strong>WooCommerce</strong> plugin to be installed and activated.</p></div>';
        return;
    }

    /**
     * Include required files for the plugin.
     */
    private function includes() {
        require_once WPC_PLUGIN_PATH . 'includes/class-wpc-settings.php';
        require_once WPC_PLUGIN_PATH . 'includes/class-wpc-frontend.php';
        require_once WPC_PLUGIN_PATH . 'includes/class-wpc-ajax-handler.php';
    }
}
