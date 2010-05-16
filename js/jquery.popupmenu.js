/*
 * Simple popup menu 1.0 (2010-05-15)
 *
 * Copyright (c) 2010 Combodo SARL (www.combodo.com)
 * Licenced under the GPL licence.
 *
 * http://www.combodo.com/
 *
 * Built upon jQuery jQuery 1.2.3a (http://jquery.com)
 * Requires the jQuery positionBy plugin by Jonathan Sharp (http://jdsharp.us)
 */

jQuery.fn.popupmenu = function ()
{
	var popupmenu = null;

	return this.each(function() 
	{
		$(this).bind('mouseenter.popup_menu click.popup_menu', function (evt)
		{
			var previous_popup = popupmenu;
			var bMenuClosed = false;
			popupmenu = $(this).find('ul');
			if ( previous_popup != null)
			{
				if ( ((evt.type == 'click') && ((previous_popup[0] == popupmenu[0])) || // Comparing the jQuery objects
					(evt.type == 'mouseenter') && (previous_popup[0] != popupmenu[0])) )
				// The user clicked again in the menu or moved over another menu let's close it
				previous_popup.css('display', 'none');
				bMenuClosed = true;
				
			}
			if ( (previous_popup == null) || (previous_popup[0] != popupmenu[0])) // Comparing the jQuery objects
			{
				// We really clicked in a different menu, let's open it
				popupmenu.bgiframe();
				popupmenu.positionBy({ target: $(this), 
										targetPos: 	2, 
										elementPos: 0,
										hideAfterPosition: true
										});
				popupmenu.css('display', 'block');
			}
			if (bMenuClosed)
			{
				popupmenu = null;
			}
			evt.stopPropagation();
		});

		$(document).bind('click.popup_menu', function(evt)
		{
			if (popupmenu)
			{
				// The user clicked in the document's body, let's close the menu
				popupmenu.css('display', 'none');
				popupmenu = null;
			}
		});
	});
};