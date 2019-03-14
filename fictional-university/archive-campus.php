<?php

get_header();
pageBanner(array(
  'title' => 'Our Campuses',
  'subtitle' => 'List of our varied campuses'
));
 ?>

<div class="container container--narrow page-section">
    <div class="acf-map">
        <?php
                while(have_posts()) {
                    the_post();
                    $mapLocation = get_field('google_map');
                    ?>
                    <div class="marker" data-lat="<?php echo $mapLocation['lat']; ?>" data-lng="<?php echo $mapLocation['lng']; ?>"></div>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <?php echo $mapLocation['address']; ?>        
        <?php   }
        ?>
    </div>
</div>

<?php get_footer();

?>