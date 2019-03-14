<?php
  
  get_header();

  while(have_posts()) {
    the_post();
    pageBanner();
     ?>

    <div class="container container--narrow page-section">
          <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>"><i class="fa fa-home" aria-hidden="true"></i> <?php _e('Programs Home', 'funiversity'); ?></a> <span class="metabox__main"><?php the_title(); ?></span></p>
      </div>

      <div class="generic-content"><?php the_content(); ?></div>
      <?php
          $professorsData = new WP_Query([
            'post_type' => 'professor',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'meta_query' => [
              [
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"' . get_the_ID() . '"'
              ]
            ]
          ]);
          
          if($professorsData->have_posts()) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">' . get_the_title(). ' Professor(s)</h2>';
            echo '<ul class="professor-cards">';
            while($professorsData->have_posts(  )) {
              $professorsData->the_post(  ); ?>
              <li class='professor-card__list-item'>
                <a class="professor-card" href="<?php echo get_the_permalink(); ?>">
                  <img src="<?php the_post_thumbnail_url('professorLandscape');?>" alt="" class="professor-card__image">
                  <span class="professor-card__name"><?php the_title(); ?></span>
                </a>
              </li>
              <?php 
            }
            echo '</ul>';
          }

      wp_reset_postdata(  );

          $today = date('Ymd');
          $headerEvents = new WP_Query([
            'post_type' => 'event',
            'posts_per_page' => 2,
            'meta_key' => 'event_date',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
            'meta_query' => [
              [
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
              ],
              [
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"' . get_the_ID() . '"'
              ]
            ]
          ]);
          
          if($headerEvents->have_posts()) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title(). ' Events</h2>';
            while($headerEvents->have_posts(  )) {
              $headerEvents->the_post(  );
              get_template_part('template-parts/content', 'event');
            }
          }


          wp_reset_postdata();

          $relatedCampuses = get_field('related_campuses');

          if ($relatedCampuses) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">' . get_the_title() . ' is Available At These Campuses:</h2>';

            echo '<ul class="min-list link-list">';
            foreach($relatedCampuses as $campus) {
              ?> <li><a href="<?php echo get_the_permalink($campus); ?>"><?php echo get_the_title($campus) ?></a></li> <?php
            }
            echo '</ul>';

          }
        ?>
    </div>
    

    
  <?php }

  get_footer();

?>