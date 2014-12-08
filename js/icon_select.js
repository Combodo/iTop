//iTop Designer combo box for icons
$(function()
{
	// the widget definition, where "itop" is the namespace,
	// "icon_select" the widget name
	$.widget( "itop.icon_select",
	{
		// default options
		options:
		{
			items: [],
			current_idx: 0,
			labels: {cancel: 'Cancel', pick_icon_file: 'Select an icon file to upload:', upload_dlg_title: 'Icon Upload...', upload: 'Upload...'},
			post_upload_to: null
		},
	
		// the constructor
		_create: function()
		{	
			var me = this;
			var sLabel = '';
			var sIcon = '';
			for(var i in this.options.items)
			{
				if(this.options.items[i].icon == '')
				{
					this.options.items[i].icon = GetAbsoluteUrlAppRoot()+'images/transparent_32_32.png';		
				}
			}
			if (this.options.items.length > 0)
			{
				sIcon = this.options.items[this.options.current_idx].icon;
				sLabel = this.options.items[this.options.current_idx].label;
			}
			this.oImg = $('<img src="'+sIcon+'" style="vertical-align: middle;" foo="bar">');								
			this.oLabel = $('<span>'+sLabel+'</span>');
			this.oButton = $('<button type="button" class="icon-select"><div style="display: inline-block;vertical-align: middle;"><span class="ui-icon ui-icon-triangle-1-s"/></div></button>');
			this.oButton.prepend(this.oLabel).prepend(this.oImg);
			this.oButton.click(function(event, ui) { me._on_button_clicked(event, ui); });
			this.element.after(this.oButton);
			this.element.addClass( "itop-icon-select" ).button();
			this.element.bind( "reverted.itop-icon-select", function(ev, data) {
				var idx = me._find_item(data.previous_value);
				if (idx != null)
				{
					me.oImg.attr('src', me.options.items[idx].icon);
					me.oLabel.text(me.options.items[idx].label);
				}
			});
			if (this.options.post_upload_to != null)
			{
				this.oUploadBtn = $('<button class="icon-select" type="button" title="'+this.options.labels['upload']+'"><div style="display: inline-block;position: relative;vertical-align:middle;height:48px; line-height:48px; width:16px"><span style="height:16px;display:block;position:absolute;top:50%;margin-top:-8px" class="ui-icon ui-icon-circle-plus"/></div></button>');
				this.oUploadBtn.click( function() { me._upload_dlg(); } );
				this.oButton.after(this.oUploadBtn);
			}
			var id = this.element.attr('id');
			$('#event_bus').bind('tabshow.itop-icon-select'+id, function(event) {
				// Compute the offsetX the first time the 'element' becomes visible...
				var bVisible = me.element.parent().is(':visible');
				if ((me.options.offsetX == null) && (bVisible))
				{
					me._refresh();
				}
			});
			this.oUploadDlg = null;
			this._refresh();
		},
	
		// called when created, and later when changing options
		_refresh: function()
		{
			if (!this.element.parent().is(':visible'))
			{
				this.options.offsetX = null; // Menu needs to be reconstructed when the button becomes visible
			}
			else
			{
				if (this.options.items.length > 0)
				{
					this.element.val(this.options.items[this.options.current_idx].value);
					this.oImg.attr('src', this.options.items[this.options.current_idx].icon);
					this.oLabel.text(this.options.items[this.options.current_idx].label);
				}
				this._create_menu();
			}
		},
		_create_menu: function()
		{
			var me = this;
			var sMenu = '<ul>';
			for(var i in this.options.items)
			{
				sMenu = sMenu + '<li><a href="#" value="'+i+'"><img src="'+this.options.items[i].icon+'" style="vertical-align: middle;">'+this.options.items[i].label+'</a></li>';					
			}
			sMenu = sMenu + '</ul>';
			var iWidth = Math.max(250, this.oButton.width());
			this.oMenu = this.oButton.menu({ content: sMenu, callback: function(data) {me._on_icon_selection(data);}, showSpeed: 0, maxHeight: 300, flyOut: true, width: iWidth, positionOpts: {posX: 'left', posY: 'top', offsetX: 0, offsetY: 0} });
		},
		_on_button_clicked: function(event, ui)
		{
			// Adjust the position of the menu, in case the button was moved...
			// The simpler is to kill and rebuild the menu !!!
			KillAllMenus();
			this._create_menu();
		},
	
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element.removeClass( "itop-icon-select" );
			this.oButton.button( "destroy" );
		},
		
		// _setOptions is called with a hash of all options that are changing
		// always refresh when changing options
		_setOptions: function()
		{
			// in 1.9 would use _superApply
			this._superApply(arguments);
			this._refresh();
		},
	
		// _setOption is called for each individual option that is changing
		_setOption: function( key, value )
		{
			if (key == 'current_idx')
			{
				this.element.val(this.options.items[value].value).trigger('change');
			}

			// in 1.9 would use _super
			this._superApply(arguments);
		},
		_on_icon_selection: function(data)
		{
			this._setOptions({current_idx: data.item.attr('value')});
		},
		_find_item: function(value)
		{
			var res = null;
			for(var idx in this.options.items)
			{
				if (value == this.options.items[idx].value)
				{
					res = idx;
					break;
				}
			}
			return res;
		},
		add_item: function(value, label, icon, position)
		{
			if (position == 'bottom')
			{
				this.options.items.push({value: value, label: label, icon: icon });
			}
			else
			{
				// Assume 'top'
				this.options.items.unshift({value: value, label: label, icon: icon });				
			}
			this._refresh();
		},
		get_post_upload_to: function()
		{
			return this.options.post_upload_to;
		},
		_upload_dlg: function()
		{
			var me = this;
			this.oUploadDlg = $('<div><p>'+this.options.labels['pick_icon_file']+'</p><p><input type="file" name="file" id="file"/></p></div>');
			this.element.after(this.oUploadDlg);
			$('input[type=file]').bind('change', function() { me._do_upload(); });
			this.oUploadDlg.dialog({
				width: 400,
				modal: true,
				title: this.options.labels['upload_dlg_title'],
				buttons: [
				{ text: this.options.labels['cancel'], click: function() {
					me.oUploadDlg.dialog( "close" );
					}
				}
				],
				close: function() { me._on_upload_close(); }
			});
		},
		_on_upload_close: function()
		{
			this.oUploadDlg.remove();
			this.oUploadDlg = null;
		},
		_do_upload: function()
		{
			var me = this;
			var $element = this.oUploadDlg.find('#file');
			this.oUploadDlg.closest('.ui-dialog').find('.ui-button').button('disable');
			if (ReplaceWithAnimation)
			{
				ReplaceWithAnimation($element);				
			}
			$.ajaxFileUpload
			(
				{
					url: this.options.post_upload_to, 
					secureuri:false,
					fileElementId:'file',
					dataType: 'json',
					success: function (data, status)
					{
						me._on_upload_complete(data);
					},
					error: function (data, status, e)
					{
						me._on_upload_error(data, status, e);
					}
				}
			);			
		},
		_on_upload_complete: function(data)
		{
			//console.log(data);
			//console.log(data.icon);
			var sIcon = data.icon.replace(/&amp;/g, "&");
			//console.log(sIcon);
			this.add_item(data.id, data.msg, sIcon, 'top');
			this.element.val(data.id);
			var idx = this._find_item(data.id);
			if (idx != null)
			{
				this.oImg.attr('src', this.options.items[idx].icon);
				this.oLabel.text(this.options.items[idx].label);
			}
			this.element.trigger('change');
			this.oUploadDlg.dialog('close');
		},
		_on_upload_error: function(data, status, e)
		{
			alert(e);
			this.oUploadDlg.closest('.ui-dialog').find('.ui-button').button('enable');
		}
	});
});
