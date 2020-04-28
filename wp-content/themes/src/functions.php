<?php
define('THEME_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('ASSET_DIR', get_template_directory_uri() . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR);
define('PROJECT', json_decode(file_get_contents(THEME_DIR . 'project-settings.json'), true));

# Enqueue (frontend) JS and CSS assets
function load_frontend_assets()
{
	wp_enqueue_script('app-scripts', ASSET_DIR . PROJECT['scripts']['filename']);
	wp_enqueue_script('app-scripts-override', ASSET_DIR . '_app-override-scripts.js');
	wp_enqueue_style('app-styles', ASSET_DIR . PROJECT['styles']['filename']);
	wp_enqueue_style('app-styles-override', ASSET_DIR . '_app-override-styles.css');
}
add_action('wp_enqueue_scripts', 'load_frontend_assets');

# Enqueue (admin dashboard) JS and CSS assets
function load_admin_assets()
{
	wp_enqueue_script('admin-scripts', ASSET_DIR . PROJECT['admin-scripts']['filename']);
	wp_enqueue_style('admin-styles', ASSET_DIR . PROJECT['admin-styles']['filename']);
}
add_action('admin_enqueue_scripts', 'load_admin_assets');
add_action('login_enqueue_scripts', 'load_admin_assets');
// -- also load frontend assets so we can have a live preview in the Style Guide
add_action('admin_enqueue_scripts', 'load_frontend_assets');
add_action('login_enqueue_scripts', 'load_frontend_assets');

# Add Dashicons
function load_dashicons()
{
	wp_enqueue_style('dashicons');
}
add_action('wp_enqueue_scripts', 'load_dashicons');

# Attach custom Post Types/Taxonomies
function attach_post_types_and_taxonomies()
{
	include(THEME_DIR . 'options/_post-types.php');
	include(THEME_DIR . 'options/_taxonomies.php');
}
add_action('init', 'attach_post_types_and_taxonomies', 0);

# Load theme php files and init vendor code
function setup_custom_theme()
{
	# Autoload composer dependencies
	$composer_deps = THEME_DIR . 'vendor/autoload.php';
	if (is_readable($composer_deps) === false) {
		wp_die('Please, run <code>composer install</code> to download and install the theme dependencies.');
	}
	include($composer_deps);
	\Carbon_Fields\Carbon_Fields::boot();

	# Register Theme Menu Locations
	register_nav_menus(PROJECT['wordpress']['menu-display-location']);

	# Load up our helper files
	$path = THEME_DIR . 'helpers/*.php';
	foreach (glob($path) as $filename) include $filename;

	# Load any REST endpoints
	$path = THEME_DIR . 'endpoints/*.php';
	foreach (glob($path) as $filename) include $filename;

	# Attach other custom Theme Options
	add_action('carbon_fields_register_fields', 'attach_theme_options');
}

function attach_theme_options()
{
	$path = THEME_DIR . 'options/*.php';
	foreach (glob($path) as $filename) include_once $filename;
}

add_action('after_setup_theme', 'setup_custom_theme');
