<?php


    /**
     * Post Type: Events
     */

    function funiversity_event_post() {

        // Campus Post
        register_post_type('campus', [
            'labels' => [
                'name' => 'Campuses',
                'singular_name' => 'Campus',
                'add_new_item' => 'Add New Campus',
                'edit_item' => 'Edit Event',
                'all_items' => 'All Campuses'
            ],
            'supports' => [
                'title', 'excerpt', 'editor'
            ],
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-location-alt',
            'taxonomies' => ['category']
        ]);


        // Event Post
        register_post_type( 'event', [
            'labels' => [
                'name' => 'Events',
                'singular_name' => 'Event',
                'add_new_item' => 'Add New Event',
                'edit_item' => 'Edit Event',
                'all_items' => 'All Events'
            ],
            'supports' => [
                'title', 'editor', 'excerpt'
            ],
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-calendar',
            'taxonomies'  => array( 'category' )
        ] );

        // Program Post
        register_post_type( 'program', [
            'labels' => [
                'name' => 'Programs',
                'singular_name' => 'Program',
                'add_new_item' => 'Add New Program',
                'edit_item' => 'Edit Program',
                'all_items' => 'All Programs'
            ],
            'supports' => [
                'title', 'editor'
            ],
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-awards',
            'taxonomies'  => array( 'category' )
        ] );

        // Professor Post
        register_post_type( 'professor', [
            'labels' => [
                'name' => 'Professors',
                'singular_name' => 'Professor',
                'add_new_item' => 'Add New Professor',
                'edit_item' => 'Edit Professor',
                'all_items' => 'All Professors'
            ],
            'supports' => [
                'title', 'editor', 'thumbnail'
            ],
            'public' => true, 
            'menu_icon' => 'dashicons-welcome-learn-more',
            'taxonomies'  => array( 'category' )
        ] );
    }
    add_action( 'init', 'funiversity_event_post' );