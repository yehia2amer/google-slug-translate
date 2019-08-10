<?php
/*
Plugin Name: Google Slug Translate
Plugin URI: http://donkey.name/wordpress-plugins/google-slug-translate/
Description: Use Google Translate article alias. You need to include the "%postname%" placeholder in your permalink structure.
Version: 1.0
Author: 骑驴觅驴
Author URI: http://donkey.name/
*/

// This file does not allow direct access
if (!defined('ABSPATH')) {
	header('HTTP/1.1 403 Forbidden');
	header('Content-Type: text/plain');
	exit;
}

/**
 * Define the Google Slug Translate plugin version * 
 * @var string
 */
define('DONKEY_GST_VERSION', '1.0');

/**
 * Define the Google Slug Translate plugin path *
 * @var string
 */
define('DONKEY_GST_PLUGIN_PATH', plugin_dir_path(__FILE__));

/**
 * Define the Google Slug Translate plugin URL
 * 
 * @var string
 */
define('DONKEY_GST_PLUGIN_URL', plugin_dir_url(__FILE__));

// Permalink structure
$donkey_permalink = get_option('permalink_structure');

// only when in the admin panel, and the permalink structure contains "%postname%" and
// The Google Slug Translate plugin is loaded when "%pagename%"
if (defined('WP_ADMIN') && WP_ADMIN
		&& (false !== strpos($donkey_permalink, '%postname%') 
				|| false !== strpos($donkey_permalink, '%pagename%'))) {
	require_once(DONKEY_GST_PLUGIN_PATH . 'google-slug-translate.php');
}

// cancel the variable
unset($donkey_permalink);
