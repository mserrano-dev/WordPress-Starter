<?php
/*
  Template Name: Redirect
  */

$list_role = meta_data('acl');

$allow_access = false;
$allow_access = $allow_access || (current_user_can('administrator') && in_array('admin', $list_role));
$allow_access = $allow_access || (current_user_can('editor') && in_array('editor', $list_role));
$allow_access = $allow_access || (current_user_can('author') && in_array('author', $list_role));
$allow_access = $allow_access || (current_user_can('contributor') && in_array('contributor', $list_role));
$allow_access = $allow_access || (current_user_can('subscriber') && in_array('subscriber', $list_role));

if ($allow_access === false) {
  $redirect_url = meta_data('redirect_url');
  header("Location: $redirect_url");
}
