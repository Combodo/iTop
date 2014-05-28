/*
 * Simple popup menu 1.0 (2010-05-15)
 *
 * Copyright (c) 2010 Combodo SARL (www.combodo.com)
 * Licenced under the GPL licence.
 *
 * http://www.combodo.com/
 *
 * Built upon jQuery jQuery 1.2.3a (http://jquery.com)
 * Requires the (modified) jQuery positionBy plugin by Jonathan Sharp (http://jdsharp.us)
 */
jQuery.fn.popupmenu = function ()
{
	var popupmenu = null;
	return this.each(function() 
	{
		$(this).bind('click.popup_menu', function (evt)
		{
			var previous_popup = popupmenu;
			var bMenuClosed = false;
			popupmenu = $(this).find('ul');
			if ( previous_popup != null)
			{
				// The user clicked while a menu is open, close the currently opened menu
				previous_popup.css('display', 'none');
				
			}
			if ( (previous_popup == null) || (previous_popup.get(0) != popupmenu.get(0))) // Comparing the DOM objects
			{
				// The user clicked in a different menu, let's open it
				popupmenu.positionBy({ target: $(this), 
										targetPos: 	4, 
										elementPos: 0,
										hideAfterPosition: true
										});
				// In links containing a hash, replace what's after the hash by the current hash
				// In order to navigate to the same tab as the current one when editing an object
				currentHash = '';
				aMatches = /#(.*)$/.exec(window.location.href);
				if (aMatches != null)
				{
					currentHash = aMatches[1];
					popupmenu.find('a').each( function() {
						if ( /#(.*)$/.test(this.href))
						{
							this.href = this.href.replace(/#(.*)$/, '#'+currentHash);
						}
					});
				}
				popupmenu.css('top', ''); // let the "position: absolute;" do its job, for better support of scrolling...
				popupmenu.css('display', 'block');
			}
			else
			{
				// The user clicked in the opened menu, it is closed now
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