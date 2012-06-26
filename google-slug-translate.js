function donkey_autosave_update_slug(post_id) {
	jQuery('#editable-post-name').html('翻译中 ...');
	jQuery.post(ajaxurl, {
			action: 'sample-permalink',
			post_id: post_id,
			new_title: fullscreen && fullscreen.settings.visible ? jQuery('#wp-fullscreen-title').val() : jQuery('#title').val(),
			samplepermalinknonce: jQuery('#samplepermalinknonce').val()
		},
		function(data) {
			if (data !== '-1') {
				jQuery('#edit-slug-box').html(data);
//				jQuery('#post_name').val(jQuery('#editable-post-name-full').text());
				donkey_update_post_name();
				makeSlugeditClickable();
			} else {
				jQuery('#editable-post-name').html('翻译失败，请手动填写。');
			}
		}
	);
}

function donkey_update_post_name()
{
	jQuery('#post_name').val(jQuery('#editable-post-name-full').text());
}