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
			current_idx: 0
		},
	
		// the constructor
		_create: function()
		{	
			var me = this;
			var sLabel = '';
			var sIcon = '';
			if (this.options.items.length > 0)
			{
				sIcon = this.options.items[this.options.current_idx].icon;
				sLabel = this.options.items[this.options.current_idx].label;
			}
			this.oImg = $('<img src="'+sIcon+'" style="vertical-align: middle;">');
			this.oLabel = $('<span>'+sLabel+'</span>');
			this.oButton = $('<button><div style="display: inline-block;vertical-align: middle;"><span class="ui-icon ui-icon-triangle-1-s"/></div></button>');
			this.oButton.prepend(this.oLabel).prepend(this.oImg);
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
			
			this._refresh();
		},
	
		// called when created, and later when changing options
		_refresh: function()
		{
			if (this.options.items.length > 0)
			{
				this.element.val(this.options.items[this.options.current_idx].value);
				this.oImg.attr('src', this.options.items[this.options.current_idx].icon);
				this.oLabel.text(this.options.items[this.options.current_idx].label);
			}
			this._create_menu();
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
	
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element.removeClass( "itop-icon-select" );
			this.oButton.destroy();
		},
		
		// _setOptions is called with a hash of all options that are changing
		// always refresh when changing options
		_setOptions: function()
		{
			// in 1.9 would use _superApply
			$.Widget.prototype._setOptions.apply( this, arguments );
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
			$.Widget.prototype._setOption.call( this, key, value );
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
		add_item: function(value, label, position)
		{
			if (position == 'bottom')
			{
				this.options.items.push({value: value, label: label });
			}
			else
			{
				// Assume 'top'
				this.options.items.unshift({value: value, label: label });				
			}
			this._refresh();
		}
	});
});
