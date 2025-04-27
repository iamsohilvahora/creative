<?php
/**
 * Plugin Name: Author Event API
 * Description: A simple CRUD API plugin for managing special author events.
 * Version: 1.0
 * Author: Sohil Vahora
 * License: GPLv2 or later
 * Text Domain: author-event-api
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Include necessary files
require_once plugin_dir_path(__FILE__) . 'includes/class-author-event-api.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-author-event-handler.php';

// Initialize the plugin
function author_event_api_init() {
    new Author_Event_API(); // Initialize the API 
}
add_action('plugins_loaded', 'author_event_api_init');
