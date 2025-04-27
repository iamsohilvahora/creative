<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handles plugin settings in WooCommerce.
 */
class WPC_Settings {
    public function __construct() {
        add_filter('woocommerce_settings_tabs_array', [$this, 'add_settings_tab'], 50);
        add_action('woocommerce_settings_tabs_wpc_settings', [$this, 'settings_page']);
        add_action('woocommerce_update_options_wpc_settings', [$this, 'update_settings']);
    }

    /**
     * Add a new settings tab in WooCommerce > Settings.
     */
    public function add_settings_tab($tabs) {
        $tabs['wpc_settings'] = __('Product Comparison', 'wpc');
        return $tabs;
    }

    /**
     * Display settings fields.
     */
    public function settings_page() {
        woocommerce_admin_fields($this->get_settings());
    }

    /**
     * Save settings fields.
     */
    public function update_settings() {
        woocommerce_update_options($this->get_settings());
    }

    /**
     * Define plugin settings.
     */
    public function get_settings() {
        return [
            // Section Title
            'section_title' => [
                'name' => __('Product Comparison Settings', 'wpc'),
                'type' => 'title',
                'id'   => 'wpc_settings_title'
            ],

            // Compare Button Text
            'wpc_button_text' => [
                'name'    => __('Compare Button Text', 'wpc'),
                'type'    => 'text',
                'desc'    => __('Change the text of the "Compare" button.', 'wpc'),
                'id'      => 'wpc_button_text',
                'default' => __('Compare', 'wpc'),
            ],

            // Show/Hide on Shop Page
            'wpc_enable_shop' => [
                'name'    => __('Enable on Shop Page', 'wpc'),
                'type'    => 'checkbox',
                'desc'    => __('Show "Compare" button on the Shop page.', 'wpc'),
                'id'      => 'wpc_enable_shop',
                'default' => 'yes',
            ],

            // Show/Hide on Product Page
            'wpc_enable_product' => [
                'name'    => __('Enable on Product Page', 'wpc'),
                'type'    => 'checkbox',
                'desc'    => __('Show "Compare" button on the Product details page.', 'wpc'),
                'id'      => 'wpc_enable_product',
                'default' => 'yes',
            ],

            // Section End
            'section_end' => [
                'type' => 'sectionend',
                'id'   => 'wpc_settings_end'
            ]
        ];
    }
}
new WPC_Settings();
