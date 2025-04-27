<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Author_Event_Handler {
    public static function validate_author_event_data($data) {
        $errors = [];

        if (empty($data['title'])) {
            $errors[] = 'Title is required.';
        }
        if (empty($data['start_date_time']) || empty($data['end_date_time'])) {
            $errors[] = 'Start and End date are required.';
        } elseif (strtotime($data['start_date_time']) >= strtotime($data['end_date_time'])) {
            $errors[] = 'Start date must be before End date.';
        }

        return $errors;
    }

    public static function create_author_event($data, $post_type) {
        $errors = self::validate_author_event_data($data);
        
        if (!empty($errors)) {
            return ['status' => false, 'error' => $errors];
        }

        // Check for duplicate title
        $existing_event = get_page_by_title(sanitize_text_field($data['title']), OBJECT, 'author_event');
        if ($existing_event) {
            return ['status' => false, 'error' => 'An author event with this title already exists.'];
        }

        $post_id = wp_insert_post([
            'post_type' => $post_type,
            'post_title' => sanitize_text_field($data['title']),
            'post_content' => sanitize_textarea_field($data['description']),
            'post_status' => 'publish',
            'meta_input' => [
                'start_date_time' => sanitize_text_field($data['start_date_time']),
                'end_date_time' => sanitize_text_field($data['end_date_time']),
                'category' => sanitize_text_field($data['category']),
            ],
        ]);

        if (is_wp_error($post_id)) {
            return $post_id;
        }

        return rest_ensure_response(['status' => true, 'id' => $post_id, 'message' => 'Author event created successfully.']);
    }

    public static function update_author_event($id, $data) {
        $post = get_post($id);
        if (!$post || get_post_type($post) !== 'author_event') {
            return ['status' =>false, 'error' => 'Author event not found.'];
        }

        $errors = self::validate_author_event_data($data);
        if (!empty($errors)) {
            return ['status' => false, 'error' => $errors];
        }

        wp_update_post([
            'ID' => $id,
            'post_title' => sanitize_text_field($data['title']),
            'post_content' => sanitize_textarea_field($data['description']),
        ]);

        update_post_meta($id, 'start_date_time', sanitize_text_field($data['start_date_time']));
        update_post_meta($id, 'end_date_time', sanitize_text_field($data['end_date_time']));
        update_post_meta($id, 'category', sanitize_text_field($data['category']));

        return rest_ensure_response(['status' => true, 'id' => $id, 'message' => 'Author event updated successfully.']);
    }

    public static function delete_author_event($id) {
        $post = get_post($id);
        if (!$post || get_post_type($post) !== 'author_event') {
            return ['status' =>false, 'error' => 'Author event not found.'];
        }

        wp_delete_post($id);

        return rest_ensure_response(['status' => true, 'id' => $id, 'message' => 'Author event deleted successfully.']);
    }

    public static function list_author_events($date = null, $post_type) {
        $args = [
            'post_type' => $post_type,
            'post_status' => 'publish',
            'meta_query' => [],
        ];

        if ($date) {
            $args['meta_query'][] = [
                'key' => 'start_date_time',
                'value' => $date,
                'compare' => '>=',
                'type' => 'DATE',
            ];
        }

        $query = new WP_Query($args);
        $events = [];

        while ($query->have_posts()) {
            $query->the_post();
            $events[] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'description' => get_the_content(),
                'start_date_time' => get_post_meta(get_the_ID(), 'start_date_time', true),
                'end_date_time' => get_post_meta(get_the_ID(), 'end_date_time', true),
                'category' => get_post_meta(get_the_ID(), 'category', true),
            ];
        }

        wp_reset_postdata();
        return $events;
    }

    public static function show_author_event($id) {
        $post = get_post($id);
        if (!$post || get_post_type($post) !== 'author_event') {
            return ['status' =>false, 'error' => 'Author event not found.'];
        }

        return [
            'id' => $post->ID,
            'title' => $post->post_title,
            'description' => $post->post_content,
            'start_date_time' => get_post_meta($post->ID, 'start_date_time', true),
            'end_date_time' => get_post_meta($post->ID, 'end_date_time', true),
            'category' => get_post_meta($post->ID, 'category', true),
        ];
    }
}
