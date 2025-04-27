<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit; 
}

class WP_Book_CPT {
    public function __construct() {
        add_action('init', [$this, 'register_cpt']);
        add_action('init', [$this, 'register_taxonomy']);
    }

    public function register_cpt() {
        $args = [
            'labels'        => ['name' => 'Books', 'singular_name' => 'Book', 'add_new_item' => 'Add New Book', 'search_items' => 'Search Books'],
            'public'        => true,
            'has_archive'   => true,
            'menu_icon'     => 'dashicons-book',
            'supports'      => ['title', 'editor', 'thumbnail'],
            'taxonomies'    => ['book_category'],
        ];
        register_post_type('book', $args);
    }

    public function register_taxonomy() {
        $args = [
            'labels'        => ['name' => 'Book Categories', 'singular_name' => 'Book Category'],
            'public'        => true,
            'hierarchical'  => true,
        ];
        register_taxonomy('book_category', 'book', $args);
    }
}
