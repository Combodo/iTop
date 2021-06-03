/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'object_details' the widget name
	$.widget( 'itop.object_details', $.itop.panel,
	{
		// default options
		options:
		{
		},
		css_classes:
		{
		},
		js_selectors:
		{
		},
   
		// the constructor
		_create: function()
		{
			this._super();
		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this._super();
		},

		_bindEvents: function ()
		{
			this._super();

			// Keep URL's hash parameters when clicking on a link of the header
			this.element.on('click', '[data-role="ibo-panel--header-right"] a', function() {
				aMatches = /#(.*)$/.exec(window.location.href);
				if (aMatches != null) {
					currentHash = aMatches[1];
					if (/#(.*)$/.test(this.href)) {
						this.href = this.href.replace(/#(.*)$/, '#'+currentHash);
					}
				}
			});
		},
	});
});
