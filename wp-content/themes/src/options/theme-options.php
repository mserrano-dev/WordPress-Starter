<?php

use Carbon_Fields\Container\Container;
use Carbon_Fields\Field\Field;

$theme_options = Container::make('theme_options', 'Theme Options')
  ->add_tab('Footer', array(
    Field::make('text', 'copyright_message', 'Copyright Message')
      ->help_text('use © and shortcode [year]'),
  ));

// Container::make('theme_options', 'Example 2nd Page')
//   ->set_page_parent($theme_options)
//   ->add_fields(array(
//     Field::make('text', 'facebook_link'),
//     Field::make('text', 'twitter_link'),
//   ));
