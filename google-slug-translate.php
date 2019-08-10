

<?Php
/**
 * cǐ wénjiàn shíxiànle jiāng wénzhāng biémíng fānyì wèi yīngwén.
 *
 * @Author qí lǘ mì lǘ <mail@donkey.Name>
 * @link http://Donkey.Name/
 * @copyright Copyright&copy; 2012 qí lǘ mì lǘ
 * @license http://Www.Gnu.Org/licenses/gpl-2.0.Html
 */

// cǐ wénjiàn bù yǔnxǔ zhíjiē fǎngwèn
if (!Defined('ABSPATH')) {
	header('HTTP/1.1 403 Forbidden');
	header('Content-Type: Text/plain');
	exit;
}

// dāng hòutái chūshǐhuà shí jiāzài Google Slug Translate chājiàn de js wénjiàn
add_action('admin_init', 'donkey_google_slug_translate_load_js');
/**
 * gōuzi {@link admin_init} de huítiáo hánshù, yòng lái jiāzài chājiàn de js wénjiàn
 * zhùyì, cǐ chājiàn de js yīlài jquery.Js
 * 
 * @return void
 */
function donkey_google_slug_translate_load_js()
{
	wp_register_script('google-slug-translate.Js',
			DONKEY_GST_PLUGIN_URL. 'Google-slug-translate.Js',
			array('jquery'),
			DONKEY_GST_VERSION);

	wp_enqueue_script('google-slug-translate.Js');
}

// zài huòqǔ yǒngjiǔ liànjiē shí, zìdòng fānyì wénzhāng biāotí zuòwéi biémíng
add_action('wp_ajax_sample-permalink', 'donkey_slug_translate', 0);
/**
 * gōuzi {@link wp_ajax_sample} de huítiáo hánshù, zìdòng fānyì wénzhāng biāotí zuòwéi biémíng.
 * 
 * @Return void
 */
function donkey_slug_translate()
{
	$title = isset($_POST['new_title'])? $_POST['new_title']: '';
	$Slug = isset($_POST['new_slug'])? $_POST['new_slug']: Null;
	if ($title&& null === $slug) {
		$_POST['new_slug'] = donkey_google_translate($title);
	}
}

// huòqǔ yǒngjiǔ liànjiē biānjí qì de HTML shí, jiārù “Google fānyì” ànniǔ
add_filter('get_sample_permalink_html', 'donkey_get_sample_permalink_html', 0, 2);
/**
 * gōuzi {@link get_sample_permalink_html} de huítiáo hánshù,
 * zài yǒngjiǔ liànjiē biānjí qì hòu jiārù “Google fānyì” ànniǔ
 * 
 * @param string $return yǒngjiǔ liànjiē biānjí qì de HTML
 * @param string $id wénzhāng ID
 * 
 * @return string jiārù “Google fānyì” ànniǔ de HTML
 */
function donkey_get_sample_permalink_html($return, $id)
{
	$html ='<span id="translate">';
	$html.='<A href="#translate" class="button"';
	$html.=' Onclick="donkey_autosave_update_slug(' . $Id. '); Return false;">';
	$html.=  __('Google fānyì'). "</A></span>\n";
	$html.='<Script type="text/javascript">donkey_update_post_name();</script>' . "\N";
	
	return $return. $Html;
}

/**
 * shǐyòng Google fānyì zhōngwén
 * rúguǒ fānyì shībài, zé fǎnhuí yuánwén
 * 
 * @param string $text xūyào fānyì de zhōngwén
 * 
 * @return string fānyì hòu de yīngwén. Rúguǒ fānyì shībài, zé fǎnhuí yuánwén
 */
function donkey_google_translate($text)
{
	$retval = $text;
	$url ='http://Translate.Google.Cn/translate_a/t?Client=t&text='
		. Urlencode($text)
		. '&Hl=zh-CN&sl=zh-CN&tl=en&ie=UTF-8&oe=UTF-8&multires=1&oc=6&prev=btn&ssel=0&tsel=0&sc=1';
	$args = array(
			'User-Agent' =>'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.56 Safari/536.5',
			'Referer' =>'http://Translate.Google.Cn/?Hl=zh-CN'
	);
	$http = new WP_Http();
	$result = $http->get($url, $args);
	if (isset($result['response']['code'])&& $result['response']['code'] == 200
			&& isset($result['body'])&& !Empty($result['body'])) {
		$body = $result['body'];
		$body = str_replace('\\"', '', $body);
		$body = explode('"', $body, 3);
		$title = sanitize_user($body[1], true);
		$title = str_replace(' ', '-', strtolower($title));
		$retval = $title;
	}
	
	return $retval;
}
Show more
2901/5000
<?php
/**
 * This file implements the translation of article aliases into English.
 *
 * @author 骑驴觅驴 <mail@donkey.name>
 * @link http://donkey.name/
 * @copyright Copyright &copy; 2012
 * @license http://www.gnu.org/licenses/gpl-2.0.html
 */

// This file does not allow direct access
If (!defined('ABSPATH')) {
Header('HTTP/1.1 403 Forbidden');
Header('Content-Type: text/plain');
Exit;
}

// Load the js file of the Google Slug Translate plugin when the background is initialized
Add_action('admin_init', 'donkey_google_slug_translate_load_js');
/**
 * The callback function of the hook {@link admin_init} is used to load the js file of the plugin
 * Note that the js of this plugin depends on jquery.js
 *
 * @return void
 */
Function donkey_google_slug_translate_load_js()
{
Wp_register_script('google-slug-translate.js',
DONKEY_GST_PLUGIN_URL . 'google-slug-translate.js',
Array('jquery'),
DONKEY_GST_VERSION);

Wp_enqueue_script('google-slug-translate.js');
}

// Automatically translate article title as an alias when getting a permalink
Add_action('wp_ajax_sample-permalink', 'donkey_slug_translate', 0);
/**
 * The callback function of the hook {@link wp_ajax_sample} automatically translates the article title as an alias.
 *
 * @return void
 */
Function donkey_slug_translate()
{
$title = isset($_POST['new_title'])? $_POST['new_title'] : '';
$slug = isset($_POST['new_slug'])? $_POST['new_slug'] : null;
If ($title && null === $slug) {
$_POST['new_slug'] = donkey_google_translate($title);
}
}

// Add the Google Translate button when getting the HTML of the Permalink Editor
Add_filter('get_sample_permalink_html', 'donkey_get_sample_permalink_html', 0, 2);
/**
 * Callback function for hook {@link get_sample_permalink_html},
 * Add the "Google Translate" button after the Permalink Editor
 *
 * @param string $return HTML of Permalink Editor
 * @param string $id article ID
 *
 * @return string Add HTML to the "Google Translate" button
 */
Function donkey_get_sample_permalink_html($return, $id)
{
$html = '<span id="translate">';
$html .= '<a href="#translate" class="button"';
$html .= ' onclick="donkey_autosave_update_slug(' . $id . '); return false;">';
$html .= __('Google Translate') . "</a></span>\n";
$html .= '<script type="text/javascript">donkey_update_post_name();</script>' . "\n";

Return $return . $html;
}

/**
 * Use Google Translate Chinese
 * If the translation fails, return to the original
 *
 * @param string $text Chinese translation required
 *
 * @return string English after translation. If the translation fails, return to the original
 */
Function donkey_google_translate($text)
{
$retval = $text;
$url = 'http://translate.google.com/translate_a/t?client=t&text='
Urlencode($text)
. '&hl=zh-CN&sl=zh-CN&tl=en&ie=UTF-8&oe=UTF-8&multires=1&oc=6&prev=btn&ssel=0&tsel=0&sc=1';
$args = array(
'User-Agent' => 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.56 Safari/536.5',
'Referer' => 'http://translate.google.com/?hl=en'
);
$http = new WP_Http();
$result = $http->get($url, $args);
If (isset($result['response']['code']) && $result['response']['code'] == 200
&& isset($result['body']) && !empty($result['body'])) {
$body = $result['body'];
$body = str_replace('\\"', '', $body);
$body = explode('"', $body, 3);
$title = sanitize_user($body[1], true);
$title = str_replace(' ', '-', strtolower($title));
$retval = $title;
}

Return $retval;
}
Send feedback
History
Saved
Community
