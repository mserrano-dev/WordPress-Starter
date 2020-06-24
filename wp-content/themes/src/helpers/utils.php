<?php

function meta_data($meta_key, $id = null)
{
  $return = null;
  // try as term data first
  if (empty($return) === true) $return = carbon_get_term_meta($id, $meta_key);

  // then try as theme option
  if (empty($return) === true) $return = carbon_get_theme_option($meta_key);

  // then try as post data
  if (empty($id) === true) {
    $id = get_the_id();
  }
  if (empty($return) === true) $return = carbon_get_post_meta($id, $meta_key);

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

function meta_attachment($meta_key, $id = null)
{
  return meta_image($meta_key, $id);
}

function meta_mediaGallery($meta_key, $id = null)
{
  $media_gallery = meta_data($meta_key, $id);
  $return = [];
  if (is_array($media_gallery) === true) {
    foreach ($media_gallery as $media_id) {
      $return[] = meta_attachment($media_id);
    }
  }
  return $return;
}

function get_page_id()
{
  global $wp_query;
  return $wp_query->post->ID;
}

function get_nav($menu_id)
{
  $return = null;
  $items = wp_get_nav_menu_items($menu_id);
  if (isset($items) === true) {
    $return = extract_link_info($items);
  }
  return $return;
}

// --- helpers ---

function extract_link_info($list_link)
{
  $return = [];
  foreach ($list_link as $index => $obj) {
    $return[] = array(
      'id' => $obj->ID,
      'sort' => $index,
      'title' => $obj->post_title,
      'url' => $obj->url,
      'parent' => $obj->menu_item_parent,
    );
  }
  return $return;
}
