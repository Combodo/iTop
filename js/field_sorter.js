// jQuery UI style "widget" for selecting and sorting "fields"
$(function()
{
	// the widget definition, where "itop" is the namespace,
	// "fieldsorter" the widget name
	$.widget( "itop.fieldsorter",
	{
		// default options
		options:
		{
			fields: {},
			labels: { moveup: 'Move Up', movedown: 'Move Down' },
			onChange: null
		},
	
		// the constructor
		_create: function()
		{
			var me = this;

			this.element
				.addClass('itop-fieldsorter');

			var me = this;
			this._initFields();

			this.moveup_btn = $('<button type="button" disabled class="ibo-button ibo-is-regular ibo-is-neutral ibo-button--vertical-align">'+this.options.labels.moveup+'</button>');
			this.movedown_btn = $('<button type="button" disabled class="ibo-button ibo-is-regular ibo-is-neutral ibo-button--vertical-align">'+this.options.labels.movedown+'</button>');
			columnWithButtons = $('<div class="ibo-mini-column"></div>');
			this.element.parent().parent().append(columnWithButtons.append(this.moveup_btn).append(this.movedown_btn));
			this.moveup_btn.on('click', function () {
				me._moveUp();
			});
			this.movedown_btn.on('click', function () {
				me._moveDown();
			});
		},
	
		// called when created, and later when changing options
		_refresh: function()
		{
			this.element.find('li').remove();
			this._initFields();
		},
		_initFields: function()
		{			
			var me = this;
			for(alias in this.options.fields)
			{
				for(k in this.options.fields[alias])
				{
					var f = this.options.fields[alias][k];
					if (f.label != '')
					{
						var sChecked = '';
						if (f.checked) sChecked = ' checked';
						var sDisabled = '';
						if (f.disabled) sDisabled = ' disabled';
						var sSortOrder = '';
		
						if (f.sort)
						{
							var sHidden = ' sort_hidden';
							
							if (f.checked) sHidden = '';
							
							if (f.sort == 'none')
							{
								sSortOrder = '&nbsp;<span sort="none" class="sort_order sort_none ibo-sort-order ibo-is-none' + sHidden + '"/>&nbsp;</span>';
							}
							else if (f.sort == 'asc')
							{
								sSortOrder = '&nbsp;<span sort="none" class="sort_order sort_asc ibo-sort-order ibo-is-ascending' + sHidden + '"/>&nbsp;</span>';
							}
							else if (f.sort == 'desc')
							{
								sSortOrder = '&nbsp;<span sort="none" class="sort_order sort_desc ibo-sort-order ibo-is-descending' + sHidden + '">&nbsp;</span>';
							}
						}
						var field = $('<li name="' + k + '" alias="' + f.alias + '" code="' + f.code + '"><input type="checkbox"' + sChecked + sDisabled + '/>&nbsp;' + f.label + sSortOrder + '</li>');
						field.on('click', function() { me._selectItem(this); });
						field.find('input').on('click', function() { me._checkboxClicked(this); } );
						field.find('span').on('click', function() { me._sortOrderClicked(this); } );
						this.element.append(field);
					}
				}
			}			
			this.element.sortable({items: 'li:not(.ui-state-disabled)', start: function(event, ui) { me._selectItem(ui.item.get(0)); }, stop: function(event, ui) { me._onSortStop(event, ui); } });
		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element
			.removeClass('itop-fieldsorter');
			let oButtonsColumn = this.moveup_btn.parent('.ibo-mini-column');
			this.moveup_btn.remove();
			this.movedown_btn.remove();
			oButtonsColumn.remove();
			this.element.sortable('destroy').html('');		
		},
		// _setOptions is called with a hash of all options that are changing
		_setOptions: function()
		{
			// in 1.9 would use _superApply
			this._superApply(arguments);
		},
		// _setOption is called for each individual option that is changing
		_setOption: function( key, value )
		{
			// in 1.9 would use _super
			this._superApply(arguments);
			
			if (key == 'fields') this._refresh();
		},
		_selectItem: function(item)
		{
			this.element.find('li').each(function() {
				if (this == item)
				{
					$(this).addClass('selected');
				}
				else
				{
					$(this).removeClass('selected');
				}
			});
			this.moveup_btn.prop('disabled', false);
			this.movedown_btn.prop('disabled', false);
		},
		_moveUp: function()
		{
			var oSelected = this.element.find('li.selected');
			if (oSelected.length == 0) return;
			
			var oPrev = oSelected.prev();
			if (oPrev.length != 0)
			{
				if (!oPrev.hasClass('ui-state-disabled'))
				{
					// Not at the top, let's move up
					var oNew = oSelected.clone(true);
					oPrev.before(oNew);
					oSelected.remove();
					this._scrollIntoView(oNew);
					this._notifyChange();
				}
			}
			this._notifyChange();
		},
		_moveDown: function()
		{
			var oSelected = this.element.find('li.selected');
			if (oSelected.length == 0) return;
			if (oSelected.hasClass('ui-state-disabled')) return; // not moveable
			
			var oNext = oSelected.next();
			if (oNext.length != 0)
			{
				// Not at the top, let's move up
				var oNew = oSelected.clone(true);
				oNext.after(oNew);
				oSelected.remove();
				this._scrollIntoView(oNew);
			}
			this._notifyChange();
		},
		_scrollIntoView: function(item)
		{
			var containerTop = this.element.scrollTop(); 
			var containerHeight = this.element.height(); 
			var itemTop = item.position().top;
			var itemBottom = itemTop + item.height();			
			
			if (itemTop < 0)
			{
				this.element.scrollTop(containerTop + itemTop);
			}
			else if (itemBottom > containerHeight)
			{
				this.element.scrollTop(containerTop + itemBottom - this.element.height());
			}
		},
		_onSortStop: function(event, ui)
		{
			this._notifyChange();
		},
		_checkboxClicked: function(elt)
		{
			if (elt.checked)
			{
				$(elt).parent().find('span.sort_order').removeClass('sort_hidden');				
			}
			else
			{
				$(elt).parent().find('span.sort_order').addClass('sort_hidden');				
			}
			this._notifyChange();
		},
		_sortOrderClicked: function(elt)
		{
			// Reset all other sort orders
			var oElt = $(elt);
			this.element.find('span.sort_order').each(function(){
				if (this != elt)
				{
					$(this).attr('sort', 'none').removeClass('sort_asc').removeClass('sort_desc').addClass('sort_none');
					$(this).attr('sort', 'none').removeClass('ibo-is-ascending').removeClass('ibo-is-descending').addClass('ibo-is-none');
				}
			});
			var sSortOrder = oElt.attr('sort');
			if (sSortOrder == 'none')
			{
				oElt.attr('sort', 'asc').removeClass('sort_none').addClass('sort_asc');
				oElt.attr('sort', 'asc').removeClass('ibo-is-none').addClass('ibo-is-ascending');

			}
			else if (sSortOrder == 'asc')
			{
				oElt.attr('sort', 'desc').removeClass('sort_asc').addClass('sort_desc');
				oElt.attr('sort', 'desc').removeClass('ibo-is-ascending').addClass('ibo-is-descending');
			}
			else if (sSortOrder == 'desc')
			{
				oElt.attr('sort', 'none').removeClass('sort_desc').addClass('sort_none');
				oElt.attr('sort', 'none').removeClass('ibo-is-descending').addClass('ibo-is-none');
			}
			this._notifyChange();
		},
		_notifyChange: function()
		{
			if (this.options.onChange)
			{
				this.options.onChange();
			}
		},
		get_params: function()
		{
			var oParams = {};
			var me = this;
			this.element.find('li').each(function() {
				var oItem = $(this);
				var sName = oItem.attr('name');
				var sCode, sAlias;
				if (sName == undefined)
				{
					sName = '_key_'; // By convention the unnamed first column is the key
					sCode = 'id';
					sAlias = '';
					sLabel = '';
				}
				else
				{
					sCode = oItem.attr('code');
					sAlias = oItem.attr('alias');
					sLabel = me.options.fields[sAlias][sCode].label;
				}
				
				var oCheckbox = oItem.find('input[type=checkbox]');
				var bChecked = false;
				if (oCheckbox.prop('checked'))
				{
					bChecked = true;
				}
				var bDisabled = false;
				if (oCheckbox.prop('disabled'))
				{
					bDisabled = true;
				}
				var sSort = undefined;
				var oSort = oItem.find('span.sort_order');
				if (oSort.length > 0)
				{
					sSort = oSort.attr('sort');
				}
				var oData = { checked: bChecked, disabled: bDisabled, sort: sSort, code:sCode, alias: sAlias, label: sLabel };
				if (oParams[sAlias] == undefined) oParams[sAlias] = {};
				oParams[sAlias][sCode] = oData;
			});
			return oParams;
		}
	});	
});