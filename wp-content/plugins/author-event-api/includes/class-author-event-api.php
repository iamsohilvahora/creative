<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Author_Event_API {
    private $post_type = 'author_event';

    public function __construct() {
        // Register custom post type
        add_action('init', [$this, 'register_author_event_post_type']);

        // Register API routes
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    // Function for register custom post type
    public function register_author_event_post_type() {
        register_post_type($this->post_type, [
            'label' => 'Author Events',
            'public' => false, // Disallow on the frontend
            'show_ui' => true, // Allow access on admin side
            'supports' => ['title', 'editor'],
        ]);
    }

    // Function for register API routes
    public function register_api_routes() {
        register_rest_route('author-event/v1', '/create', [
            'methods' => 'POST',
            'callback' => [$this, 'create_author_event'],
            'permission_callback' => [$this, 'check_administrator_permission'],
        ]);

        register_rest_route('author-event/v1', '/update/(?P<id>\d+)', [
            'methods' => 'PUT',
            'callback' => [$this, 'update_author_event'],
            'permission_callback' => [$this, 'check_administrator_permission'],
        ]);

        register_rest_route('author-event/v1', '/delete/(?P<id>\d+)', [
            'methods' => 'DELETE',
            'callback' => [$this, 'delete_author_event'],
            'permission_callback' => [$this, 'check_administrator_permission'],
        ]);

        register_rest_route('author-event/v1', '/list', [
            'methods' => 'GET',
            'callback' => [$this, 'list_author_events'],
            'permission_callback' => [$this, 'check_administrator_permission'],
        ]);

        register_rest_route('author-event/v1', '/show/(?P<id>\d+)', [
            'methods' => 'GET',
            'callback' => [$this, 'show_author_event'],
            'permission_callback' => [$this, 'check_administrator_permission'],
        ]);
    }

    // Check if current user has permission to access the API
    public function check_administrator_permission() {
        return current_user_can('administrator');
    }

    // Create a new author event
    public function create_author_event($request) {
        return Author_Event_Handler::create_author_event($request->get_params(), $this->post_type);
    }

    // Update an existing author event
    public function update_author_event($request) {
        return Author_Event_Handler::update_author_event($request['id'], $request->get_params());
    }

    // Delete an existing author event
    public function delete_author_event($request) {
        return Author_Event_Handler::delete_author_event($request['id']);
    }

    // Get a list of author events
    public function list_author_events($request) {
        return Author_Event_Handler::list_author_events($request->get_param('date'), $this->post_type);
    }

    // Get a single author event by ID
    public function show_author_event($request) {
        return Author_Event_Handler::show_author_event($request['id']);
    }
}
