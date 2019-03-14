<?php

get_header();
pageBanner(array(
  'title' => 'Past Events',
  'subtitle' => 'See what happened in our world.'
));
 ?>

<div class="container container--narrow page-section">
<?php
  $today = date('Ymd');
  $pastEvents = new WP_Query([
    'paged' => get_query_var( 'paged', 1 ),
    'post_type' => 'event',
    'meta_key' => 'event_date',
    'orderby' => 'meta_value_num',
    'order' => 'ASC',
    'meta_query' => [
      [
        'key' => 'event_date',
        'compare' => '<',
        'value' => $today,
        'type' => 'numeric'
      ]
    ]
  ]);

  while($pastEvents->have_posts(  )) {
    $pastEvents->the_post(  );
    get_template_part('template-parts/content-event');
   }
  echo paginate_links([
      'total' => $pastEvents->max_num_pages
  ]);
?>

<hr class="section-break">

<p>Want to know what's happening? <a href="<?php echo site_url('/event') ?>">Check out our latest events</a>.</p>

</div>

<?php get_footer();

?>