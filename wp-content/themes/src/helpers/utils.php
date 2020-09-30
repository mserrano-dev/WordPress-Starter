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
  $url = wp_get_attachment_url($image_id);
  return $url ? $url : '';
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

function meta_complex($meta_key, $id = null)
{
  $list_item = meta_data($meta_key, $id);
  return recursive_complex($list_item);
}

function recursive_complex($list_item)
{
  // process each item in a CarbonField type 'complex' Field object
  $return = [];
  foreach ($list_item as $pbItem) {
    $new_item = array();
    if (is_array($pbItem) === true) {
      foreach ($pbItem as $key => $val) {
        if ($key === '_type') {
          // gridsome GraphQL ignores keys prefixed with underscore
          $new_item['type'] = toCamelCase($pbItem['_type']);
        } else if (is_array($val)) {
          // recurse down and continue to process entire tree
          $new_item[$key] = recursive_complex($val);
        } else {
          // this is a normal data, record it
          $new_item[$key] = $val;
        }
      }
      $return[] = $new_item;
    }
  }
  return $return;
}

function list_termBySlug($taxonomy, $post_id)
{
  // given a taxonomy and post ID, return a list of all terms for that post
  $return = [];
  $list_term = get_the_terms($post_id, $taxonomy);
  if (is_array($list_term) === true) {
    $return = wp_list_pluck($list_term, 'slug');
  }
  return $return;
}

function list_postBySlug($list_post)
{
  // given a list of posts produced by CarbonField association, return a list of slugs
  $return = [];
  if (is_array($list_post) === true) {
    foreach ($list_post as $post) {
      $return[] = get_post($post['id']); // get the trait
    }
    $return = wp_list_pluck($return, 'post_name');
  }
  return $return;
}

function get_cdn_url($media_url, $args = array())
{
  $url = $media_url;
  if (empty($media_url) === false) {
    $basename = explode('.', basename($media_url));
    $is_video = (in_array($basename[1], array('mp4')) === true);
    $structure = "https://res.cloudinary.com/<cloud_name>/<resource_type>/<type>/<transformations>/<public_id>.<format>";
    $settings = array(
      '<cloud_name>' => meta_data('cloudinary__cloud_name'),
      '<resource_type>/' => $is_video ? 'video/' : '', // image, raw, or video
      '<type>/' => $is_video ? 'upload/' : '', // https://cloudinary.com/documentation/image_transformations#delivery_types
      '<transformations>/' => ($args['transformations'] ? $args['transformations'] . ',f_auto/' : 'f_auto/'),
      '<public_id>' => $basename[0],
      '<format>' => $args['format'] ?? $basename[1],
    );
    $url = str_replace(array_keys($settings), array_values($settings), $structure);
  }
  return $url;
}

function list_cdn_url($list_url)
{
  $return = [];
  foreach ($list_url as $media_url) {
    $return[] = get_cdn_url($media_url);
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
  $return = [];
  $items = wp_get_nav_menu_items($menu_id);
  if (is_array($items) === true) {
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

function toCamelCase($input, $separator = '_')
{
  return lcfirst(str_replace($separator, '', ucwords($input, $separator)));
}
