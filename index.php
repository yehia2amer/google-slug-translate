<?php
/*
Plugin Name: Google Slug Translate
Plugin URI: http://donkey.name/wordpress-plugins/google-slug-translate/
Description: 使用Google翻译文章别名。需要您在固定链接结构中包含“%postname%”占位符。
Version: 1.0
Author: 骑驴觅驴
Author URI: http://donkey.name/
*/

// 此文件不允许直接访问
if (!defined('ABSPATH')) {
	header('HTTP/1.1 403 Forbidden');
	header('Content-Type: text/plain');
	exit;
}

/**
 * 定义 Google Slug Translate 插件版本
 * 
 * @var string
 */
define('DONKEY_GST_VERSION', '1.0');

/**
 * 定义 Google Slug Translate 插件路径
 *
 * @var string
 */
define('DONKEY_GST_PLUGIN_PATH', plugin_dir_path(__FILE__));

/**
 * 定义 Google Slug Translate 插件URL
 * 
 * @var string
 */
define('DONKEY_GST_PLUGIN_URL', plugin_dir_url(__FILE__));

// 永久链接结构
$donkey_permalink = get_option('permalink_structure');

// 仅当处于管理面板，并且永久链接结构中包含“%postname%”和
// “%pagename%”时才加载 Google Slug Translate 插件
if (defined('WP_ADMIN') && WP_ADMIN
		&& (false !== strpos($donkey_permalink, '%postname%') 
				|| false !== strpos($donkey_permalink, '%pagename%'))) {
	require_once(DONKEY_GST_PLUGIN_PATH . 'google-slug-translate.php');
}

// 取消变量
unset($donkey_permalink);