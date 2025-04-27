<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handles frontend display.
 */
class WPC_Frontend {
   	public function __construct() {
	  	add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
	  	add_action('woocommerce_after_shop_loop_item', [$this, 'display_compare_button_shop'], 20);
	  	add_action('woocommerce_single_product_summary', [$this, 'display_compare_button_product'], 35);
	  	add_action('wp_footer', [$this, 'load_comparison_popup']);
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public function enqueue_scripts() {
	    wp_enqueue_style('wpc-style', WPC_PLUGIN_URL . 'assets/css/style.css');
	    wp_enqueue_script('wpc-script', WPC_PLUGIN_URL . 'assets/js/script.js', ['jquery'], false, true);

	    wp_localize_script('wpc-script', 'wpc_ajax', [
	        'ajax_url' => admin_url('admin-ajax.php')
	    ]);
	}

    /**
     * Display Compare Button on Shop Page
     */
    public function display_compare_button_shop() {
        if (get_option('wpc_enable_shop') !== 'yes') return;

        $button_text = get_option('wpc_button_text', 'Compare');
        echo '<button class="wpc-compare-btn" data-product-id="' . get_the_ID() . '">' . esc_html($button_text) . '</button>';
    }

    /**
     * Display Compare Button on Product Page Based on Position
     */
    public function display_compare_button_product() {
        if (get_option('wpc_enable_product') !== 'yes') return;

        $button_text = get_option('wpc_button_text', 'Compare');
        $button_html = '<button class="wpc-compare-btn" data-product-id="' . get_the_ID() . '">' . esc_html($button_text) . '</button>';
        echo $button_html;
    }

    /**
     * Load comparison popup template in footer.
     */
    public function load_comparison_popup() {
        include WPC_PLUGIN_PATH . 'templates/comparison-popup.php';
    }
}
new WPC_Frontend();
