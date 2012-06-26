<?php
/**
 * 此文件实现了将文章别名翻译为英文。
 *
 * @author 骑驴觅驴 <mail@donkey.name>
 * @link http://donkey.name/
 * @copyright Copyright &copy; 2012 骑驴觅驴
 * @license http://www.gnu.org/licenses/gpl-2.0.html
 */

// 此文件不允许直接访问
if (!defined('ABSPATH')) {
	header('HTTP/1.1 403 Forbidden');
	header('Content-Type: text/plain');
	exit;
}

// 当后台初始化时加载 Google Slug Translate 插件的 js 文件
add_action('admin_init', 'donkey_google_slug_translate_load_js');
/**
 * 钩子 {@link admin_init} 的回调函数，用来加载插件的js文件
 * 注意，此插件的 js 依赖 jquery.js
 * 
 * @return void
 */
function donkey_google_slug_translate_load_js()
{
	wp_register_script('google-slug-translate.js',
			DONKEY_GST_PLUGIN_URL . 'google-slug-translate.js',
			array('jquery'),
			DONKEY_GST_VERSION);

	wp_enqueue_script('google-slug-translate.js');
}

// 在获取永久链接时，自动翻译文章标题作为别名
add_action('wp_ajax_sample-permalink', 'donkey_slug_translate', 0);
/**
 * 钩子 {@link wp_ajax_sample} 的回调函数，自动翻译文章标题作为别名。
 * 
 * @return void
 */
function donkey_slug_translate()
{
	$title = isset($_POST['new_title'])? $_POST['new_title'] : '';
	$slug = isset($_POST['new_slug'])? $_POST['new_slug'] : null;
	if ($title && null === $slug) {
		$_POST['new_slug'] = donkey_google_translate($title);
	}
}

// 获取永久链接编辑器的HTML时，加入 “Google翻译” 按钮
add_filter('get_sample_permalink_html', 'donkey_get_sample_permalink_html', 0, 2);
/**
 * 钩子 {@link get_sample_permalink_html} 的回调函数，
 * 在永久链接编辑器后加入 “Google翻译” 按钮
 * 
 * @param string $return 永久链接编辑器的 HTML
 * @param string $id 文章ID
 * 
 * @return string 加入 “Google翻译” 按钮的 HTML
 */
function donkey_get_sample_permalink_html($return, $id)
{
	$html = '<span id="translate">';
	$html .= '<a href="#translate" class="button"';
	$html .= ' onclick="donkey_autosave_update_slug(' . $id . '); return false;">';
	$html .=  __('Google翻译') . "</a></span>\n";
	$html .= '<script type="text/javascript">donkey_update_post_name();</script>' . "\n";
	
	return $return . $html;
}

/**
 * 使用 Google 翻译中文
 * 如果翻译失败，则返回原文
 * 
 * @param string $text 需要翻译的中文
 * 
 * @return string 翻译后的英文。如果翻译失败，则返回原文
 */
function donkey_google_translate($text)
{
	$retval = $text;
	$url = 'http://translate.google.cn/translate_a/t?client=t&text='
		. urlencode($text)
		. '&hl=zh-CN&sl=zh-CN&tl=en&ie=UTF-8&oe=UTF-8&multires=1&oc=6&prev=btn&ssel=0&tsel=0&sc=1';
	$args = array(
			'User-Agent' => 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.56 Safari/536.5',
			'Referer' => 'http://translate.google.cn/?hl=zh-CN'
	);
	$http = new WP_Http();
	$result = $http->get($url, $args);
	if (isset($result['response']['code']) && $result['response']['code'] == 200
			&& isset($result['body']) && !empty($result['body'])) {
		$body = $result['body'];
		$body = str_replace('\\"', '', $body);
		$body = explode('"', $body, 3);
		$title = sanitize_user($body[1], true);
		$title = str_replace(' ', '-', strtolower($title));
		$retval = $title;
	}
	
	return $retval;
}