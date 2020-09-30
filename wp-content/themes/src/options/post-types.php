<?php

register_post_type('job', array(
  'labels' => array(
    'name'               => 'Jobs',
    'singular_name'      => 'Job',
    'add_new'            => 'Add New',
    'add_new_item'       => 'Add new Job',
    'view_item'          => 'View Job',
    'edit_item'          => 'Edit Job',
    'new_item'           => 'New Job',
    'view_item'          => 'View Job',
    'search_items'       => 'Search Jobs',
    'not_found'          => 'No Jobs found',
    'not_found_in_trash' => 'No Jobs found in trash',
  ),
  'public' => true,
  'exclude_from_search' => false,
  'show_ui' => true,
  'capability_type' => 'post',
  'hierarchical' => false,
  '_edit_link' => 'post.php?post=%d',
  'rewrite' => array(
    'slug' => 'job',
    'with_front' => false,
  ),
  'query_var' => true,
  'menu_icon' => 'dashicons-businessman',
  'supports' => array('title', 'editor', 'page-attributes', 'thumbnail', 'excerpt'),
));
