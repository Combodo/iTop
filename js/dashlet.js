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
		},
	
		// called when created, and later when changing options
		_refresh: function()
		{
		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		destroy: function()
		{
			this.element
			.removeClass('itop-dashlet')
			.unbind('click.itop-dashlet');

			// call the original destroy method since we overwrote it
			$.Widget.prototype.destroy.call( this );			
		},
		// _setOptions is called with a hash of all options that are changing
		_setOptions: function()
		{
			// in 1.9 would use _superApply
			$.Widget.prototype._setOptions.apply( this, arguments );
		},
		// _setOption is called for each individual option that is changing
		_setOption: function( key, value )
		{
			// in 1.9 would use _super
			$.Widget.prototype._setOption.call( this, key, value );
		},
		_select: function()
		{
			this.element.addClass('dashlet-selected');
			$('#event_bus').trigger('dashlet-selected', {'dashlet_id': this.options.dashlet_id, 'dashlet_class': this.options.dashlet_class})
		},
		_deselect: function()
		{
			this.element.removeClass('dashlet-selected');			
		},
		_on_click: function(event)
		{
			var sCurrentId = this.element.attr('id');
			
			$(':itop-dashlet').each(function(){
				var sId = $(this).attr('id');
				var oWidget = $(this).data('dashlet');
				if (oWidget)
				{
					if (sCurrentId != sId)
					{
						oWidget._deselect();
					}
				}
			});
			this._select();
		},
		get_params: function()
		{
			var oParams = {};
			var oProperties = $('#dashlet_properties_'+this.options.dashlet_id);
			oProperties.find(':itop-property_field').each(function(){
				var oWidget = $(this).data('property_field');
				if (oWidget)
				{
					var oVal = oWidget._get_committed_value();
					oParams[oVal.name] = oVal.value;
				}
			});
			oParams.dashlet_id = this.options.dashlet_id;
			oParams.dashlet_class = this.options.dashlet_class;
			return oParams;
		}
	});	
});