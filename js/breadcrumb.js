//iTop Form field
;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'breadcrumb' the widget name
	$.widget( 'itop.breadcrumb',
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
			
			this.element
			.addClass('breadcrumb');

			if(typeof(Storage) !== "undefined")
			{
				$(window).bind( 'hashchange', function(e)
				{
					me.RefreshLatestEntry();
				});

				aBreadCrumb = this._Read();

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
                        url: sUrl
                    });
                    // Keep only the last <max_count> items
                    aBreadCrumb = aBreadCrumb.slice(-(this.options.max_count));
                }
				this._Write(aBreadCrumb);
				var sBreadCrumbHtml = '';
				for (iEntry in aBreadCrumb)
				{
                    //if (iEntry >= iDisplayableItems) break; // skip the current page
					var oEntry = aBreadCrumb[iEntry];
					if (oEntry['label'].length > 0)
					{
						var sIconSpec = '';
						if (oEntry['icon'].length > 0) {
							sIconSpec = '<span class="icon"><img src="'+oEntry['icon']+'"/></span>';
						}

						var sTitle = oEntry['description'],
							sLabel = oEntry['label'];
						if (sTitle.length == 0) {
							sTitle = oEntry['label'];
						}
						sTitle = EncodeHtml(sTitle, false);
						sLabel = EncodeHtml(sLabel, false);

						if ((this.options.new_entry !== null) && (iEntry == aBreadCrumb.length-1)) {
							// Last entry is the current page
							sBreadCrumbHtml += '<div class="breadcrumb-item breadcrumb-current" breadcrumb-entry="'+iEntry+'" title="'+sTitle+'">'+sIconSpec+'<span class="truncate">'+sLabel+'</span></div>';
						} else {
							var sSanitizedUrl = StripArchiveArgument(oEntry['url']);
							sBreadCrumbHtml += '<div class="breadcrumb-item"><a class="breadcrumb-link" breadcrumb-entry="'+iEntry+'" href="'+sSanitizedUrl+'" title="'+sTitle+'">'+sIconSpec+'<span class="truncate">'+sLabel+'</span></a></div>';
						}
					}
				}
				$('#itop-breadcrumb').html(sBreadCrumbHtml);
			}
			else
			{
				// Sorry! No Web Storage support..
				//$('#itop-breadcrumb').html('<span style="display:none;">Session storage not available for the current browser</span>');
			}
		},
		// called when created, and later when changing options
		_refresh: function()
		{

		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element
			.removeClass('breadcrumb');
		},
		// _setOptions is called with a hash of all options that are changing
		// always refresh when changing options
		_setOptions: function()
		{
			this._superApply(arguments);
		},
		// _setOption is called for each individual option that is changing
		_setOption: function( key, value )
		{
			this._super( key, value );
		},
		_Read: function()
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
		_Write: function(aBreadCrumb)
		{
			var sBreadCrumbStorageKey = this.options.itop_instance_id + 'breadcrumb-v1';
			sBreadCrumbData = JSON.stringify(aBreadCrumb);
			sessionStorage.setItem(sBreadCrumbStorageKey, sBreadCrumbData);
		},
		// Refresh the latest entry (navigating to a tab)
		RefreshLatestEntry: function(sRefreshHrefTo)
		{
			aBreadCrumb = this._Read();
			var iDisplayableItems = aBreadCrumb.length;

			if (this.options.new_entry !== null) {
				if (sRefreshHrefTo == undefined)
				{
					sRefreshHrefTo = window.location.href;
				}

				// The current page is the last entry in the breadcrumb, let's refresh it
				aBreadCrumb[aBreadCrumb.length - 1].url = sRefreshHrefTo;
				$('#itop-breadcrumb .breadcrumb-current:last-of-type a').attr('href', sRefreshHrefTo);
			}
			this._Write(aBreadCrumb);
		},

	});
});
