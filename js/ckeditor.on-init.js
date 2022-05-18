/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

// WARNING: This code cannot be placed directly within the page as CKEditor could not be loaded yet.
// As it can be loaded from an XHR call (several times), we need to ensure it will be called when necessary (see PHP WebResourcesHelper)

// For disabling the CKEditor at init time when the corresponding textarea is disabled !
if ((CKEDITOR !== undefined) && (CKEDITOR.plugins.registered['disabler'] === undefined)) {
	CKEDITOR.plugins.add( 'disabler',
		{
			init : function( editor )
			{
				editor.on( 'instanceReady', function(e)
				{
					e.removeListener();
					$('#'+ editor.name).trigger('update');
				});
			}

		});
}

// Rewrite the CKEditor Mentions plugin regexp to make it suitable for all Unicode alphabets.
if (CKEDITOR !== undefined && CKEDITOR.plugins.registered['mentions']) {
	// from https://github.com/ckeditor/ckeditor4/blob/a3786007fb979d7d7bff3d10c34a2d422935baed/plugins/mentions/plugin.js#L147
	function createPattern(marker, minChars) {
		let pattern = marker + '[\\p{L}\\p{N}_-]';
		if ( minChars ) {
			pattern += '{' + minChars + ',}';
		} else {
			pattern += '*';
		}
		pattern += '$';
		return new RegExp(pattern, 'u');
	}

	CKEDITOR.on('instanceLoaded', event => {
		event.editor.config.mentions.forEach(config => {
			config.pattern = createPattern(config.marker, config.minChars);
		});
	});
}
