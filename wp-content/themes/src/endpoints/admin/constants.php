<?php
/*
 * /wp-json/admin/constants
 */

add_action('rest_api_init', function () {
  register_rest_route('admin', 'constants', array(
    'methods'  => 'GET',
    'permission_callback' => function () {
      return current_user_can('administrator'); // requires header X-WP-Nonce
    },
    'callback' => 'get_adminConstants'
  ));
});

function get_adminConstants()
{
  $return = array();

  $list_taxonomy = array();
  foreach (get_taxonomies(array(), 'objects') as $taxonomy) {
    $list_taxonomy[] = $taxonomy->name;
  }

  $list_taxonomyTerm = get_terms([
    'taxonomy' => $list_taxonomy,
    'hide_empty' => false,
  ]);

  foreach ($list_taxonomyTerm as $term) {
    $key = sprintf('term:%s:%s', $term->taxonomy, $term->term_id);
    $return[$key] = $term->name;
  }

  $response = new WP_REST_Response($return);
  $response->set_status(200);

  return $response;
}
