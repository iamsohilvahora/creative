<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit; 
}

class WP_Book_Shortcode {
    public function __construct() {
        add_shortcode('wp_books', [$this, 'display_books']);
        add_action('wp_ajax_load_books', [$this, 'load_books']);
        add_action('wp_ajax_nopriv_load_books', [$this, 'load_books']);
        add_action('wp_ajax_load_book_details', [$this, 'load_book_details']);
        add_action('wp_ajax_nopriv_load_book_details', [$this, 'load_book_details']);
    }

    public function display_books($atts) {
        // Extract Shortcode Attributes
        $atts = shortcode_atts([
            'limit' => 6,       // Default to 6 books
            'subtitle' => 'true' // Default to showing subtitle
        ], $atts);

        // Enqueue Scripts
        wp_enqueue_script('wp-book-script', WP_BOOK_URL . 'assets/js/script.js', ['jquery'], null, true);
        
        // Pass Shortcode Attributes to JavaScript
        wp_localize_script('wp-book-script', 'wpBookAjax', [
            'ajaxurl'   => admin_url('admin-ajax.php'),
            'limit'     => intval($atts['limit']),
            'subtitle'  => $atts['subtitle'],
        ]);

        ob_start();
        ?>
        <div id="wp-book-container">
            <div id="wp-book-list"></div>
            <div id="wp-book-pagination"></div>
        </div>
        <div id="wp-book-popup" class="wp-book-popup">
            <div class="wp-book-popup-content">
                <span class="wp-book-close">&times;</span>
                <div id="wp-book-popup-body"></div>
            </div>
        </div>
        <script>
            jQuery(document).ready(function($) {
                loadBooks(1, wpBookAjax.limit, wpBookAjax.subtitle);
                function loadBooks(page, limit, subtitle) {
                    $.ajax({
                        url: wpBookAjax.ajaxurl,
                        type: 'POST',
                        data: { action: 'load_books', page: page, limit: limit, showsubtitle: subtitle },
                        success: function(response) {
                            $('#wp-book-list').html(response.books);
                            $('#wp-book-pagination').html(response.pagination);
                        }
                    });
                }
            });
        </script>
        <?php
        return ob_get_clean();
    }

    public function load_books() {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 6;
        $showsubtitle = isset($_POST['showsubtitle']) ? $_POST['showsubtitle'] : 0;
        $offset = ($page - 1) * $limit;
        $query = new WP_Query([
            'post_type'      => 'book',
            'posts_per_page' => $limit,
            'offset'         => $offset,
        ]);
        $output = '';
        while ($query->have_posts()) : $query->the_post();
            if($showsubtitle === "true") {
                $subtitle = get_post_meta(get_the_ID(), 'wp_book_subtitle', true);
            }
            $rating = get_post_meta(get_the_ID(), 'wp_book_rating', true);
            $output .= '<div class="wp-book-item">';
            $output .= '<a href="#" data-id="' . get_the_ID() . '">';
            if (has_post_thumbnail()) {
                $output .= '<div class="wp-book-cover">' . get_the_post_thumbnail(get_the_ID(), 'medium') . '</div>';
            }
            $output .= '<h3>' . get_the_title() . '</h3>';
            if($showsubtitle === "true") {
                $output .= '<p class="wp-book-subtitle">' . esc_html($subtitle) . '</p>';
            }
            $output .= '<p class="wp-book-rating">Rating: ' . esc_html($rating) . '/5</p>';
            $output .= '</a></div>';
        endwhile;
        wp_reset_postdata();

        $total_books = wp_count_posts('book')->publish;
        $total_pages = ceil($total_books / $limit);
        $pagination = '';
        for ($i = 1; $i <= $total_pages; $i++) {
            $class = ($i == $page) ? 'active' : "";
            $pagination .= '<a href="#" class="wp-book-page '. $class .'" data-page="' . $i . '">' . $i . '</a> ';
        }
        wp_send_json(['books' => $output, 'pagination' => $pagination]);
    }

    public function load_book_details() {
        $book_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $showsubtitle = isset($_POST['showsubtitle']) ? $_POST['showsubtitle'] : 0;
        if (!$book_id) {
            wp_send_json_error('Invalid book ID');
        }
        $post = get_post($book_id);
        $second_cover = get_post_meta($book_id, 'wp_book_second_cover', true);
        if($showsubtitle === "true") {
            $subtitle = get_post_meta($book_id, 'wp_book_subtitle', true);
        }
        $rating = get_post_meta($book_id, 'wp_book_rating', true);
        $content = apply_filters('the_content', $post->post_content);
        $content .= wp_trim_words( $content, 50, '...' );
        $output = '<div class="wp-book-popup-inner">';
        $output .= '<img src="' . esc_url($second_cover) . '" class="wp-book-second-cover">';
        $output .= '<h2>' . esc_html($post->post_title) . '</h2>';
        if($showsubtitle === "true") {
            $output .= '<h3>' . esc_html($subtitle) . '</h3>';
        }
        $output .= '<p>Rating: ' . esc_html($rating) . '/5</p>';
        $output .= '<div class="wp-book-content">' . $content . '</div>';
        $output .= '</div>';
        wp_send_json_success($output);
    }
}
