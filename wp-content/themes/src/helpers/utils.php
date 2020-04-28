<?php

function meta_data($meta_key, $id = null)
{
  $return = null;

  if (empty($id) === true) {
    $id = get_the_id();
  }
  // first try getting the custom field value
  if (empty($return) === true) $return = carbon_get_post_meta($id, $meta_key);
  // then try getting the theme option value
  if (empty($return) === true) $return = carbon_get_theme_option($meta_key);

  return $return;
}

function meta_image($meta_key, $id = null)
{
  $image_id = meta_data($meta_key, $id);
  if ($image_id === null) {
    $image_id = $meta_key;
  }
  return wp_get_attachment_url($image_id);
}

function get_page_id()
{
  global $wp_query;
  return $wp_query->post->ID;
}
