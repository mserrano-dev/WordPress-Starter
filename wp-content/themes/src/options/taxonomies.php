<?php

register_taxonomy(
  'location', # Taxonomy name
  array('job'), # Post Types
  array( # Arguments
    'labels'            => array(
      'name'              => 'Office Locations',
      'singular_name'     => 'Office Location',
      'search_items'      => 'Search Office Locations',
      'all_items'         => 'All Office Locations',
      'parent_item'       => 'Parent Office Location',
      'parent_item_colon' => 'Parent Office Location:',
      'view_item'         => 'View Office Location',
      'edit_item'         => 'Edit Office Location',
      'update_item'       => 'Update Office Location',
      'add_new_item'      => 'Add New Office Location',
      'new_item_name'     => 'New Office Location Name',
      'menu_name'         => 'Office Locations',
    ),
    'hierarchical'      => true,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array('slug' => 'location'),
  )
);
