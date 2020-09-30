<?php
define('ROOT_DIR', ABSPATH);
define('THEME_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('ASSET_DIR', get_template_directory_uri() . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR);
define('PROJECT', json_decode(file_get_contents(ROOT_DIR . 'project-settings.json'), true));

# Enqueue (frontend) JS and CSS assets
function load_frontend_assets()
{
	wp_enqueue_script('app-scripts', ASSET_DIR . PROJECT['entry']['main'] . '.min.js');
	wp_enqueue_script('app-scripts-override', ASSET_DIR . '_app-override-scripts.js');
	wp_enqueue_style('app-styles', ASSET_DIR . PROJECT['entry']['main'] . '.min.css');
	wp_enqueue_style('app-styles-override', ASSET_DIR . '_app-override-styles.css');
}
add_action('wp_enqueue_scripts', 'load_frontend_assets');

# Enqueue (admin dashboard) JS and CSS assets
function load_admin_assets()
{
	wp_enqueue_script('app-scripts', ASSET_DIR . PROJECT['entry']['admin'] . '.min.js');
	wp_enqueue_style('app-styles', ASSET_DIR . PROJECT['entry']['admin'] . '.min.css');

	// for REST API permission_callback
	$wp_nonce = wp_create_nonce('wp_rest');
	echo "<input id='rest_auth_nonce' type='hidden' name='rest_auth_nonce' value='$wp_nonce' />";
}
add_action('admin_enqueue_scripts', 'load_admin_assets');
add_action('login_enqueue_scripts', 'load_admin_assets');

# Add Dashicons
function load_dashicons()
{
	wp_enqueue_style('dashicons');
}
add_action('wp_enqueue_scripts', 'load_dashicons');

# Attach custom Post Types/Taxonomies
function attach_post_types_and_taxonomies()
{
	include(THEME_DIR . 'options/post-types.php');
	include(THEME_DIR . 'options/taxonomies.php');
}
add_action('init', 'attach_post_types_and_taxonomies', 0);

# Load theme php files and init vendor code
function setup_custom_theme()
{
	# Autoload composer dependencies
	$composer_deps = ROOT_DIR . 'vendor/autoload.php';
	if (is_readable($composer_deps) === false) {
		wp_die('Please, run <code>composer install</code> to download and install the theme dependencies.');
	}
	include($composer_deps);
	\Carbon_Fields\Carbon_Fields::boot();

	# Register Theme Menu Locations
	register_nav_menus(PROJECT['wordpress']['menu-display-location']);

	# Load up our helper files
	load_php_recursive(THEME_DIR . 'helpers');

	# Load any REST endpoints
	load_php_recursive(THEME_DIR . 'endpoints');

	# Attach other custom Theme Options
	add_action('carbon_fields_register_fields', 'attach_theme_options');
}

function attach_theme_options()
{
	load_php_recursive(THEME_DIR . 'options');
}

function load_php_recursive($dir)
{
	// We support loading subdirectories
	$globbed = array(
		'files'    => array_filter(glob($dir . '/*.php'), 'is_file'),
		'subdirs'  => glob($dir . '/*', GLOB_ONLYDIR),
	);
	foreach ($globbed['files'] as $file) include_once($file);
	foreach ($globbed['subdirs'] as $subdir) load_php_recursive($subdir);
}

add_action('after_setup_theme', 'setup_custom_theme');
