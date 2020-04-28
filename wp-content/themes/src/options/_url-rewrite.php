<?php

/**
 * Modify the custom post type/taxonomy permalink
 * 
 */
function rewrite_rules($url, $post)
{
  // accepts post_type or taxonomny
  $type = $post->post_type;
  if (empty($type) === true) {
    $type = $post->taxonomy;
  }

  switch ($type) {
    // case 'job':
    //   // this would combine all job postings to one aggregate page /jobs
    //   $return = str_replace('/job/', '/jobs?title=', $url);
    //   $return = rtrim($return, "/");
    //   break;
    default:
      $return = $url;
      break;
  }
  return $return;
}
add_filter('term_link', 'rewrite_rules', 10, 2);
add_filter('post_type_link', 'rewrite_rules', 10, 2);
