<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit; 
}

class WP_Book {
    public function __construct() {
        // Include necessary files.
        require_once WP_BOOK_PATH . 'includes/class-cpt.php';
        require_once WP_BOOK_PATH . 'includes/class-meta-box.php';
        require_once WP_BOOK_PATH . 'includes/class-shortcode.php';

        // Initialize Components
        new WP_Book_CPT();
        new WP_Book_Meta_Box();
        new WP_Book_Shortcode();

        // Enqueue Styles
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
    }

    public function enqueue_styles() {
        wp_enqueue_style('wp-book-style', WP_BOOK_URL . 'assets/css/style.css');
    }
}
