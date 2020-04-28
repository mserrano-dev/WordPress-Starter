<?php
$featured_image = meta_image(get_post_thumbnail_id());
$share_image = asset_img_url('screenshot.png');
if (empty($featured_image) === false) {
  $share_image = $featured_image;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <meta property="og:image" content="<?php echo $share_image; ?>">
  <?php wp_head(); ?>
</head>

<body <?php body_class($modifiers); ?>>
  <header class="header">
  </header>