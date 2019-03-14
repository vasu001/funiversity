<?php


    /**
     * Custom Styles:
     *      Font Awesome: 4.7.0
     *      Google Fonts: Roboto Condensed, Roboto
     *      Custom Styles: style.css, css/style.css
     *      Custom Scripts: js/scripts-bundled.js
     */
    function funiversity_styler() {
        wp_enqueue_style('google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
        wp_enqueue_style('fontawesome-4.7.0', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        wp_enqueue_style( 'custom_style', get_stylesheet_uri(), NULL, microtime(), false);        
        
        wp_deregister_script( 'jquery' );
        wp_enqueue_script('jquery', '//code.jquery.com/jquery-3.3.1.js', NULL, 1.0, true);
        wp_enqueue_script('custom-scripts', get_template_directory_uri(  ) . '/js/scripts.js', NULL, microtime(), true);
    }
    add_action( 'wp_enqueue_scripts', 'funiversity_styler' );

    /**
     * Header Functionalities for this theme
     * Menus
     */

    function funiversity_header() {
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_image_size( 'professorLandscape', 400, 260, true );
        add_image_size( 'professorPortrait', 480, 650, true );
        add_image_size( 'pageBanner', 1500, 350, true );
        register_nav_menus( [
            'header_one' => __('Header Menu','funiversity'),
            'footer_one' => __('Explore Menu', 'funiversity'),
            'footer_two' => __('Learn Menu', 'funiversity'),
            'social' => __('Social Links Menu', 'funiversity')
        ] );
    }
    add_action( 'after_setup_theme', 'funiversity_header' );

    remove_filter('the_excerpt','wpautop');
    remove_filter('the_content', 'wpautop');


    /**
     * Page Banner Function
     */

    function pageBanner($args = NULL) {
        if(!$args['title']) {
            $args['title'] = get_the_title();
        }

        if(!$args['subtitle']) {
            $args['subtitle'] = get_field('page_banner_subtitle');
        }

        if(!$args['photo']) {
            if(get_field('page_banner_image')) {
                $args['photo'] = get_field('page_banner_image')['sizes']['pageBanner'];
            } else {
                $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
            }
        }
        ?>
        <div class="page-banner">
            <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
            <div class="page-banner__content container container--narrow">
                <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
                <div class="page-banner__intro">
                    <p><?php echo $args['subtitle']; ?></p>
                </div>
            </div>
        </div>
<?php
    }

    function funiversity_default_query($query) {
        $today = date('Ymd');
        if(!is_admin() AND is_post_type_archive('event') AND $query->is_main_query(  )) {
            $query->set('meta_key', 'event_date');
            $query->set('orderby', 'meta_value_num');
            $query->set('order', 'ASC');
            $query->set('meta_query', [
                [
                    'key' => 'event_date',
                    'compare' => '>=',
                    'value' => $today,
                    'type' => 'numeric'
                ]
            ]);
        }
        
        if(!is_admin() AND is_post_type_archive( 'program' ) AND $query->is_main_query()) {
            $query->set('orderby', 'title');
            $query->set('order', 'ASC');
            $query->set('posts_per_page', -1);
        }
    }

    add_action('pre_get_posts', 'funiversity_default_query');


    function funiversity_location($api) {
        $api['key'] = '';
        return $api;
    }
    add_filter('acf/fields/google_maps/api', 'funiversity_location');