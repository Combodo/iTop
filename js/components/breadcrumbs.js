/*
 * Copyright (C) 2013-2021 Combodo SARL
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'breadcrumbs' the widget name
	$.widget( 'itop.breadcrumbs',
	{
		// default options
		options:
		{
			itop_instance_id: '',
			new_entry: null,
			max_count: 8
		},
   
		// the constructor
		_create: function()
		{
			var me = this;
			
			this.element.addClass('ibo-breadcrumbs');

			// Check that storage API is available
			if(typeof(Storage) !== 'undefined')
			{
				$(window).bind('hashchange', function(e)
				{
					me.RefreshLatestEntry();
				});

				aBreadCrumb = this._readDataFromStorage();

                if (this.options.new_entry !== null) {
                    var sUrl = this.options.new_entry.url;
                    if (sUrl.length == 0) {
                        sUrl = window.location.href;
                    }
                    // Eliminate items having the same id, before appending the new item
                    var aBreadCrumb = $.grep(aBreadCrumb, function(item, ipos){
                        if (item.id == me.options.new_entry.id) return false;
                        else return true;
                    });
                    aBreadCrumb.push({
                        id: this.options.new_entry.id,
                        label: this.options.new_entry.label,
						description: this.options.new_entry.description,
                        icon: this.options.new_entry.icon,
	                    icon_type: this.options.new_entry.icon_type,
                        url: sUrl
                    });
                    // Keep only the last <max_count> items
                    aBreadCrumb = aBreadCrumb.slice(-(this.options.max_count));
                }
				this._writeDataToStorage(aBreadCrumb);

				for (iEntry in aBreadCrumb)
				{
					var sBreadcrumbsItemHtml = '';
					var oEntry = aBreadCrumb[iEntry];
					if (oEntry['label'].length > 0)
					{
                        var sIconSpec = '';
                        if (oEntry['icon_type'] === 'css_classes')
                        {
	                        sIconSpec = '<span class="ibo-breadcrumbs--item-icon"><span class="'+oEntry['icon']+'"/></span></span>';
                        }
                        else if (oEntry['icon'].length > 0)
                        {
                            sIconSpec = '<span class="ibo-breadcrumbs--item-icon"><img src="'+oEntry['icon']+'"/></span>';
                        }

						var sTitle = oEntry['description'],
							sLabel = oEntry['label'];
						if (sTitle.length == 0) {
							sTitle = sLabel;
						}
						sTitle = EncodeHtml(sTitle, false);
						sLabel = EncodeHtml(sLabel, false);

						if ((this.options.new_entry !== null) && (iEntry == aBreadCrumb.length - 1)) {
							// Last entry is the current page
							sBreadcrumbsItemHtml += '<span class="ibo-breadcrumbs--item--is-current" data-breadcrumb-entry-number="'+iEntry+'" title="'+sTitle+'">'+sIconSpec+'<span class="ibo-breadcrumbs--item-label">'+sLabel+'</span></span>';
						} else {
							var sSanitizedUrl = StripArchiveArgument(oEntry['url']);
							sBreadcrumbsItemHtml += '<a class="ibo-breadcrumbs--item" data-breadcrumb-entry-number="'+iEntry+'" href="'+sSanitizedUrl+'" title="'+sTitle+'">'+sIconSpec+'<span class="ibo-breadcrumbs--item-label">'+sLabel+'</span></a>';
						}
					}
					this.element.append(sBreadcrumbsItemHtml);
				}
			}
		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element.removeClass('ibo-breadcrumbs');
		},
		_readDataFromStorage: function()
		{
			var sBreadCrumbStorageKey = this.options.itop_instance_id + 'breadcrumb-v1';
			var aBreadCrumb = [];
			var sBreadCrumbData = sessionStorage.getItem(sBreadCrumbStorageKey);
			if (sBreadCrumbData !== null)
			{
				aBreadCrumb = JSON.parse(sBreadCrumbData);
			}
			return aBreadCrumb;
		},
		_writeDataToStorage: function(aBreadCrumb)
		{
			var sBreadCrumbStorageKey = this.options.itop_instance_id + 'breadcrumb-v1';
			sBreadCrumbData = JSON.stringify(aBreadCrumb);
			sessionStorage.setItem(sBreadCrumbStorageKey, sBreadCrumbData);
		},
		// Refresh the latest entry (navigating to a tab)
		RefreshLatestEntry: function(sRefreshHrefTo)
		{
			var aBreadCrumb = this._readDataFromStorage();
			var iDisplayableItems = aBreadCrumb.length;

			if (this.options.new_entry !== null) {
				if (sRefreshHrefTo === undefined)
				{
					sRefreshHrefTo = window.location.href;
				}

				// The current page is the last entry in the breadcrumb, let's refresh it
				aBreadCrumb[aBreadCrumb.length - 1].url = sRefreshHrefTo;
				$('#itop-breadcrumb .breadcrumb-current:last-of-type a').attr('href', sRefreshHrefTo);
			}
			this._writeDataToStorage(aBreadCrumb);
		},
	});
});
