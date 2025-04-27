<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handles AJAX requests.
 */
class WPC_Ajax_Handler {
    public function __construct() {
        add_action('wp_ajax_wpc_add_to_compare', [$this, 'add_to_compare']);
        add_action('wp_ajax_nopriv_wpc_add_to_compare', [$this, 'add_to_compare']);
    }

    /**
     * Add product to comparison list.
     */
    public function add_to_compare() {
        if (!isset($_POST['product_id'])) {
            wp_send_json_error(['message' => __('Invalid product ID.', 'wpc')]);
        }

        $product_id = intval($_POST['product_id']);
        $product = wc_get_product($product_id);

        if (!$product) {
            wp_send_json_error(['message' => __('Product not found.', 'wpc')]);
        }

        // Get product attributes
        $attributes = [];
        if ($product->is_type('variable')) {
            // Get variable product attributes
            $available_variations = $product->get_available_variations();
            foreach ($available_variations as $variation) {
                foreach ($variation['attributes'] as $key => $value) {
                    $attr_name = wc_attribute_label(str_replace('attribute_', '', $key));
                    $attributes[$attr_name] = ucfirst($value);
                }
            }
        } else {
            // Get simple product attributes
            $product_attributes = $product->get_attributes();
            foreach ($product_attributes as $attribute_name => $attribute) {
                if ($attribute->is_taxonomy()) {
                    $terms = wp_get_post_terms($product_id, $attribute->get_name(), ['fields' => 'names']);
                    $attributes[wc_attribute_label($attribute_name)] = implode(', ', $terms);
                } else {
                    $attributes[wc_attribute_label($attribute_name)] = $attribute->get_options();
                }
            }
        }

        // Prepare response data
        $product_data = [
            'id'          => $product->get_id(),
            'title'       => $product->get_name(),
            'image'       => wp_get_attachment_image_url($product->get_image_id(), 'thumbnail'),
            'description' => wp_trim_words($product->get_short_description(), 15),
            'price'       => $product->get_price_html(),
            'attributes'  => $attributes,
        ];

        wp_send_json_success($product_data);
    }
}
new WPC_Ajax_Handler();
