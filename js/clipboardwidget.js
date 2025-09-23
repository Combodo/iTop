/*
 *
 *  * Copyright (C) 2013-2024 Combodo SAS
 *  *
 *  * This file is part of iTop.
 *  *
 *  * iTop is free software; you can redistribute it and/or modify
 *  * it under the terms of the GNU Affero General Public License as published by
 *  * the Free Software Foundation, either version 3 of the License, or
 *  * (at your option) any later version.
 *  *
 *  * iTop is distributed in the hope that it will be useful,
 *  * but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  * GNU Affero General Public License for more details.
 *  *
 *  * You should have received a copy of the GNU Affero General Public License
 *  
 */

$(function() {
	// the widget definition, where "itop" is the namespace,
	// "clipboard" the widget name
	$.widget("itop.clipboard",
		{
			// default options
			options: {
				standard_title: '',
				standard_icon: '',
				copied_title: '',
				copied_icon: '',
				container: '',
			},

			_create: function () {
				var me = this;
				var sTitle = this.element.attr('data-tooltip-content');
				var sDataTitleIcon = this.element.attr('data-title-icon');
				var sDataCopiedTitle = this.element.attr('data-copied-title');
				var sDataCopiedIcon = this.element.attr('data-copied-icon');
				
				this.options.standard_title = (typeof sTitle === 'undefined' ? this.options.standard_title : sTitle);
				this.options.standard_icon = (typeof sDataTitleIcon === 'undefined' ? this.options.standard_icon : sDataTitleIcon);
				this.options.copied_title = (typeof sDataCopiedTitle === 'undefined' ? this.options.copied_title : sDataCopiedTitle);
				this.options.copied_icon = (typeof sDataCopiedIcon === 'undefined' ? this.options.copied_icon : sDataCopiedIcon);
				
				this.element.addClass('url-to-clipboard');
				
				//initialize clipboard widget and set a container if provided (eg: bootstrap modal)
				var aInitParams = {};
				if (this.options.container !== '')
				{
					aInitParams['container'] = this.options.container;
				}
				new ClipboardJS(this.element[0], aInitParams);

				//initialize tooltip with mouse interaction
				this.element.on('click',function(){
					var sOriginalTitle = (me.options.copied_icon !== '' ? '<i class="'+me.options.copied_icon+' url-to-clipboard-tooltip-copied"></i>' : '') + me.options.copied_title;
					$(this).attr('data-tooltip-content', sOriginalTitle);
					this._tippy.setContent(sOriginalTitle);
					this._tippy.show();
					//CombodoTooltip.InitTooltipFromMarkup($(this), true);
				});
				this.element.on('mouseout',function(){
					var sOriginalTitle = (me.options.standard_icon !== '' ? '<i class="'+me.options.standard_icon+' url-to-clipboard-tooltip-copied"></i>' : '') + me.options.standard_title;
					$(this).attr('data-tooltip-content', sOriginalTitle);
					CombodoTooltip.InitTooltipFromMarkup(me.element, true);
					$(this).removeClass('url-to-clipboard-copied');
				});
			}
		}
	);
});

/*
 * Initialize every DOM objects on page ready with class url-to-clipboard with itop.clipboard widget
 */
$(document).ready(function()
{
	//Initialize every corresponding DOM element with clipboard.js widget
	$('.url-to-clipboard').clipboard();
});
