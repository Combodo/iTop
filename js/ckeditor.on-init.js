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
