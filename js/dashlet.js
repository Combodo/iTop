// jQuery UI style "widget" for editing an iTop "dashlet"
$(function()
{
	// the widget definition, where "itop" is the namespace,
	// "dashlet" the widget name
	$.widget( "itop.dashlet",
	{
		// default options
		options:
		{
			dashlet_id: '',
			dashlet_class: ''
		},
	
		// the constructor
		_create: function()
		{
			var me = this; 

			this.element
			.addClass('itop-dashlet')
			.bind('click.itop-dashlet', function(event) { me._on_click(event); } );

			this._update();
		},
	
		// to call when the contents are changed
		_update: function()
		{
			var me = this; 

			this.closeBox = $('<div class="close-box"/>');
			this.closeBox
				.click(function() { me._remove_dashlet(); })
				.prependTo(this.element);
			if (this.element.hasClass('dashlet-selected'))
			{
				this.closeBox.show();
			}
			else
			{
				this.closeBox.hide();
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
			.removeClass('itop-dashlet')
			.unbind('click.itop-dashlet');
			
			this.closeBox.remove();			
		},
		// _setOptions is called with a hash of all options that are changing
		_setOptions: function()
		{
			// in 1.9 would use _superApply
			this._superApply(arguments);
			this._update();
		},
		// _setOption is called for each individual option that is changing
		_setOption: function( key, value )
		{
			// in 1.9 would use _super
			this._superApply(arguments);
		},
		select: function()
		{
			this.element.addClass('dashlet-selected');
			this.closeBox.fadeIn(500);
			$('#event_bus').trigger('dashlet-selected', {'dashlet_id': this.options.dashlet_id, 'dashlet_class': this.options.dashlet_class});
		},
		deselect: function()
		{
			this.element.removeClass('dashlet-selected');
			this.closeBox.hide();
		},
		deselect_all: function()
		{
			$(':itop-dashlet').each(function(){
				var sId = $(this).attr('id');
				var oWidget = $(this).data('itopDashlet');
				if (oWidget)
				{
					oWidget.deselect();
				}
			});
		},
		_on_click: function(event)
		{
			this.deselect_all();
			this.select();
		},
		get_params: function()
		{
			var oParams = {};
			var oProperties = $('#dashlet_properties_'+this.options.dashlet_id);
			oProperties.find(':itop-property_field').each(function(){
				var oWidget = $(this).data('itopProperty_field');
				if (oWidget)
				{
					var oVal = oWidget._get_committed_value();
					oParams[oVal.name] = oVal.value;
				}
			});
			oParams.dashlet_id = this.options.dashlet_id;
			oParams.dashlet_class = this.options.dashlet_class;
			return oParams;
		},
		get_drag_icon: function()
		{
			var oDragItem = $('#dashlet_'+this.options.dashlet_class).clone();
			oDragItem.css({zIndex: 999});
			oDragItem.appendTo('body');
			return oDragItem;
		},
		_remove_dashlet: function()
		{
			var iDashletId = this.options.dashlet_id;
			var sDashletClass = this.options.dashlet_class;
			var oContainer = this.element.parent();

			$('#dashlet_properties_'+iDashletId).remove();
			this.element.remove();
			$('#event_bus').trigger('dashlet-removed', {'dashlet_id': iDashletId, 'dashlet_class': sDashletClass, 'container': oContainer});
		}
	});	
});