<?php


    function funiversity_searchRoute() {
        register_rest_route( 'university/v1', 'search', [
            'methods' => WP_REST_SERVER::READABLE,
            'callback' => 'getResultsForSearch'
        ] );
    }
    add_action('rest_api_init', 'funiversity_searchRoute');

    function getResultsForSearch($data) {
        $mainQuery = new WP_QUERY([
            'post_type' => ['post', 'page', 'campus', 'event', 'program', 'professor'],
            's' => sanitize_text_field($data['term']) 
        ]);

        $results = [
            'generalInfo' => [],
            'professors' => [],
            'programs' => [],
            'events' => [],
            'campuses' => []
        ];

        while($mainQuery->have_posts()) {
            $mainQuery->the_post();

            if(get_post_type() == 'post' or get_post_type() == 'page') {
                array_push($results['generalInfo'], [
                        'title' => get_the_title(),
                        'permalink' => get_permalink(),
                        'authorName' => get_the_author(),
                        'postType' => get_post_type()
                    ]
                );
            }

            if(get_post_type() == 'campus') {
                array_push($results['campuses'], [
                        'title' => get_the_title(),
                        'permalink' => get_permalink()
                    ]
                );
            }

            if(get_post_type() == 'program') {
                $relatedCampuses = get_field('related_campuses');

                if($relatedCampuses) {
                    foreach($relatedCampuses as $campus) {
                        array_push($results['campuses'], [
                            'title' => get_the_title($campus),
                            'permalink' => get_the_permalink($campus)
                        ]);
                    }
                }

                array_push($results['programs'], [
                        'title' => get_the_title(),
                        'permalink' => get_permalink(),
                        'id' => get_the_ID()
                    ]
                );
            }

            if(get_post_type() == 'event') {
                $eventDate = new DateTime(get_field('event_date'));
                $description = null;

                if(has_excerpt()) {
                    $description = get_the_excerpt();
                } else {
                    $description = wp_trim_words(get_the_content(), 18);
                }

                array_push($results['events'], [
                        'title' => get_the_title(),
                        'permalink' => get_permalink(),
                        'month' => $eventDate->format('M'),
                        'day' => $eventDate->format('d'),
                        'description' => $description
                        
                    ]
                );
            }

            if(get_post_type() == 'professor') {
                
                array_push($results['professors'], [
                        'title' => get_the_title(),
                        'permalink' => get_permalink(),
                        'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
                    ]
                );
            }
        }


        if($results['programs']) {
            $programsMetaQuery = ['relation' => 'OR'];

            foreach($results['programs'] as $item) {
                array_push($programsMetaQuery, [
                    'key' => 'related_programs',
                    'compare' => 'LIKE',
                    'value' => '"' . $item['id'] . '"'
                ]);
            }

            $programRelationshipQuery = new WP_QUERY([
                'post_type' => ['professor', 'event'],
                'meta_query' => $programsMetaQuery
            ]);
    
            while($programRelationshipQuery->have_posts()) {
                $programRelationshipQuery->the_post();

                if (get_post_type() == 'event') {
                    $eventDate = new DateTime(get_field('event_date'));
                    $description = null;
                    if (has_excerpt()) {
                      $description = get_the_excerpt();
                    } else {
                      $description = wp_trim_words(get_the_content(), 18);
                    }
            
                    array_push($results['events'], array(
                      'title' => get_the_title(),
                      'permalink' => get_the_permalink(),
                      'month' => $eventDate->format('M'),
                      'day' => $eventDate->format('d'),
                      'description' => $description
                    ));
                  }

                if(get_post_type() == 'professor') {
                    array_push($results['professors'], [
                        'title' => get_the_title(),
                        'permalink' => get_permalink(),
                        'image' => get_the_post_thumbnail_url( 0, 'professorLandscape' )
                    ]);
                }
            }
        }

        $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));
        $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));

        return $results;
    }