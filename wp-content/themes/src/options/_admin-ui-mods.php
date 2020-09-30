<?php

// disable WP 5.0 block editor
// add_filter('use_block_editor_for_post', '__return_false', 10);

// allow all users to see the user-meta options
add_filter('carbon_fields_user_meta_container_admin_only_access', '__return_false');

// customize the admin login screen to link to our home page
function change_login_header_title()
{
    return get_bloginfo('name');
}
add_filter('login_headertitle', 'change_login_header_title');

function change_login_header_url()
{
    return home_url('/');
}
add_filter('login_headerurl', 'change_login_header_url');

// add a custom admin color scheme
function additional_admin_color_schemes()
{
    wp_admin_css_color(
        'scw',
        'California',
        ASSET_DIR . '_admin-styles.min.css',
        ['#ffc420', '#006bb6']
    );
}
add_action('admin_init', 'additional_admin_color_schemes');

// dont allow wordpress to scale images
add_filter('big_image_size_threshold', '__return_false');