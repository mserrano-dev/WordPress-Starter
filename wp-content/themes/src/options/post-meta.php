<?php

use Carbon_Fields\Container\Container;
use Carbon_Fields\Field\Field;
use Carbon_Fields\Block;

$blank4default = 'leave blank to use default value';
$blank4hidden  = 'leave blank to hide';

function standard_title($var_name)
{
  $replacements =  array(
    '%VAR_NAME%' => $var_name,
  );
  return str_replace(
    array_keys($replacements),
    array_values($replacements),
    '
    <% if (%VAR_NAME%) { %>
      <%- %VAR_NAME% %>
    <% } else { %>
      ---
    <% } %>
  '
  );
}

function generate_shortcode_listing(string ...$list_shortcode)
{
  $return = array();
  foreach ($list_shortcode as $idx => $shortcode) {
    $return[] = Field::make('html', sprintf('style-guide__shortcode-%s-input', $shortcode))
      ->set_html(sprintf('<div>[%s]</div>', $shortcode))
      ->set_classes('centered')
      ->set_width(33);
    $return[] = Field::make('html', sprintf('style-guide__shortcode-%s-output', $shortcode))
      ->set_html(do_shortcode(sprintf('[%s]', $shortcode)))
      ->set_width(66);
  }
  return $return;
}

/*
 * Reference Guide
 */
Container::make('post_meta', 'Style Guide')
  // ->where('current_user_role', 'IN', array('administrator', 'editor')) // TODO: remove this
  ->or_where('post_id', '=', '1234-this-is-just-an-example-of-conditional-display') // TODO: remove this
  ->or_where('post_template', '!=', 'templates/omit-all-conditions-to-apply-to-all.php') // TODO: remove this
  ->add_tab('Shortcodes', generate_shortcode_listing(
    'year'
  ));
