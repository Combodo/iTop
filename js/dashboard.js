// jQuery UI style "widget" for editing an iTop "dashboard"
$(function()
{
	// the widget definition, where "itop" is the namespace,
	// "dashboard" the widget name
	$.widget( "itop.dashboard",
	{
		// default options
		options:
		{
			dashboard_id: '',
			layout_class: '',
			title: '',
			submit_to: 'index.php',
			submit_parameters: {},
			render_to: 'index.php',
			render_parameters: {}
		},
	
		// the constructor
		_create: function()
		{
			var me = this; 

			this.element
			.addClass('itop-dashboard');

			this.ajax_div = $('<div></div>').appendTo(this.element);
		},
	
		// called when created, and later when changing options
		_refresh: function()
		{
			var oParams = this._get_state(this.options.render_parameters);
			var me = this;
			$.post(this.options.render_to, oParams, function(data){
				me.element.html(data);
			});
		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		destroy: function()
		{
			this.element
			.removeClass('itop-dashboard');

			this.ajax_div.remove();
			
			// call the original destroy method since we overwrote it
			$.Widget.prototype.destroy.call( this );			
		},
		// _setOptions is called with a hash of all options that are changing
		_setOptions: function()
		{
			// in 1.9 would use _superApply
			$.Widget.prototype._setOptions.apply( this, arguments );
			this._refresh();
		},
		// _setOption is called for each individual option that is changing
		_setOption: function( key, value )
		{
			// in 1.9 would use _super
			$.Widget.prototype._setOption.call( this, key, value );
			if (key == 'layout')
			{
				_refresh();
			}
		},
		_get_state: function(oMergeInto)
		{
			var oState = oMergeInto;
			oState.dashlets = [];
			this.element.find(':itop-dashlet').each(function() {
				var oDashlet = $(this).data('dashlet');
				if(oDashlet)
				{
					var oDashletParams = oDashlet.get_params();
					var sId = oDashletParams.dashlet_id;
					oState[sId] = oDashletParams;				
					oState.dashlets.push({dashlet_id: sId, dashlet_class: oDashletParams.dashlet_class} );
				}
			});
			oState.dashboard_id = this.options.dashboard_id;
			oState.layout_class = this.options.layout_class;
			oState.title = this.options.title;
			
			return oState;
		},
		save: function()
		{
			var oParams = this._get_state(this.options.submit_parameters);
			var me = this;
			$.post(this.options.submit_to, oParams, function(data){
				me.ajax_div.html(data);
			});
		}
	});	
});