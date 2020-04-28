<?php

/**
 * Returns current year
 *
 * @uses [year]
 */
function shortcode_year()
{
  return date('Y');
}
add_shortcode('year', 'shortcode_year');
