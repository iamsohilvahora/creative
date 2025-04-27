<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit; 
}

class WP_Book_Meta_Box {
    public function __construct() {
        add_action('add_meta_boxes', [$this, 'add_meta_box']);
        add_action('save_post', [$this, 'save_meta']);
    }

    public function add_meta_box() {
        add_meta_box('wp_book_meta', 'Book Details', [$this, 'meta_box_callback'], 'book', 'normal', 'high');
    }

    public function meta_box_callback($post) {
        $subtitle     = get_post_meta($post->ID, 'wp_book_subtitle', true);
        $second_cover = get_post_meta($post->ID, 'wp_book_second_cover', true);
        $rating       = get_post_meta($post->ID, 'wp_book_rating', true);
        ?>
        <p><label>Book Sub Title:</label>
        <input type="text" name="wp_book_subtitle" value="<?php echo esc_attr($subtitle); ?>" style="width:100%;"></p>

        <p><label>Book Second Cover Image:</label>
        <input type="text" name="wp_book_second_cover" value="<?php echo esc_url($second_cover); ?>" style="width:80%;">
        <button type="button" class="upload-button">Upload</button></p>

        <p><label>Book Rating:</label>
        <select name="wp_book_rating">
            <?php for ($i = 0; $i <= 5; $i++) { ?>
                <option value="<?php echo $i; ?>" <?php selected($rating, $i); ?>><?php echo $i; ?></option>
            <?php } ?>
        </select></p>

        <script>
        jQuery(document).ready(function($) {
            $('.upload-button').click(function() {
                var send_attachment_bkp = wp.media.editor.send.attachment;
                var button = $(this);
                wp.media.editor.send.attachment = function(props, attachment) {
                    $(button).prev().val(attachment.url);
                    wp.media.editor.send.attachment = send_attachment_bkp;
                };
                wp.media.editor.open(button);
            });
        });
        </script>
        <?php
    }

    public function save_meta($post_id) {
        if (isset($_POST['wp_book_subtitle'])) {
            update_post_meta($post_id, 'wp_book_subtitle', sanitize_text_field($_POST['wp_book_subtitle']));
        }
        if (isset($_POST['wp_book_second_cover'])) {
            update_post_meta($post_id, 'wp_book_second_cover', esc_url($_POST['wp_book_second_cover']));
        }
        if (isset($_POST['wp_book_rating'])) {
            update_post_meta($post_id, 'wp_book_rating', intval($_POST['wp_book_rating']));
        }
    }
}
