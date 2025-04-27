<?php
// Add Meta Box Custom Fields for testimonial post type
function testimonials_add_meta_boxes() {
    add_meta_box(
        'testimonials_fields',
        'Testimonial Details',
        'creative_testimonials_meta_box_callback',
        'testimonials',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'testimonials_add_meta_boxes');

// Meta Box Callback
function creative_testimonials_meta_box_callback($post) {
    // Retrieve existing values
    $position = get_post_meta($post->ID, 'position', true);
    $rating = get_post_meta($post->ID, 'rating', true);
    ?>
    <p>
        <label for="position">Position:</label><br>
        <input type="text" id="position" name="position" value="<?php echo esc_attr($position); ?>" size="50">
    </p>
    <p>
        <label for="rating">Rating (1-5):</label><br>
        <input type="number" id="rating" name="rating" value="<?php echo esc_attr($rating); ?>" min="1" max="5">
    </p>
    <?php
}

// Save Custom Fields
function creative_save_testimonials_meta($post_id) {
    // Verify nonce to secure the save operation
    if (!isset($_POST['post_type']) || $_POST['post_type'] !== 'testimonials') {
        return;
    }

    // Save fields
    if (isset($_POST['position'])) {
        update_post_meta($post_id, 'position', sanitize_text_field($_POST['position']));
    }
    if (isset($_POST['rating'])) {
        update_post_meta($post_id, 'rating', intval($_POST['rating']));
    }
}
add_action('save_post', 'creative_save_testimonials_meta');
