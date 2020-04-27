<?php
/*
 * /wp-json/midtier/example?tag=&page=1
 */

add_action('rest_api_init', function() {
  register_rest_route( 'midtier', 'example', array(
    'methods'  => 'GET',
    'callback' => 'collect_example_posts'
  ));
});

function collect_example_posts() {
  
  $args = array(
    'paged' => $_GET['page'] ?? 1,
    'test' => 5,
  );

  $response = new WP_REST_Response(array(
    
  ));
  $response->set_status(200);

  return $response;
}
