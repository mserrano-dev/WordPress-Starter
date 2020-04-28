<?php

if (is_single()) {
  get_template_part('templates/single', get_post_type());
} else if (!is_front_page() && is_home()) {
  get_template_part('templates/blog');
} else {
  get_template_part('templates/404');
}