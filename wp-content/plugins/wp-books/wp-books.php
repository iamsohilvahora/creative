<?php
/**
 * Plugin Name: WP Books
 * Description: A custom plugin to manage books with categories.
 * Version: 1.0
 * Author: Sohil Vahora
 * License: GPLv2 or later
 * Text Domain: wp-books
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit; 
}

// Define Constants.
define('WP_BOOK_PATH', plugin_dir_path(__FILE__));
define('WP_BOOK_URL', plugin_dir_url(__FILE__));

// Autoload Required Files.
require_once WP_BOOK_PATH . 'includes/class-wp-book.php';

// Initialize the Plugin.
function run_wp_book() {
    new WP_Book();
}
add_action('plugins_loaded', 'run_wp_book');

// Update Featured Image title to Book Cover Image
function wp_book_change_featured_image_meta_box_title() {
    remove_meta_box('postimagediv', 'book', 'side'); // Remove default featured image box
    add_meta_box('postimagediv', __('Book Cover Image', 'wp-book'), 'post_thumbnail_meta_box', 'book', 'side', 'low');
}
add_action('do_meta_boxes', 'wp_book_change_featured_image_meta_box_title');
