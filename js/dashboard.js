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
			submit_parameters: {operation: 'async_action'}
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
		},
		save: function()
		{
			var oParams = this.options.submit_parameters;
			oParams.dashlets = [];
			this.element.find(':itop-dashlet').each(function() {
				var oDashlet = $(this).data('dashlet');
				if(oDashlet)
				{
					var sId = $(this).attr('id');
					var oDashletParams = oDashlet.get_params();
					oParams['dashlet_'+sId] = oDashletParams;				
					oParams.dashlets.push({dashlet_id: sId, dashlet_class: oDashletParams['dashlet_class']} );
				}
			});
			oParams.dashboard_id = this.options.dashboard_id;
			oParams.layout_class = this.options.layout_class;
			oParams.title = this.options.title;
			var me = this;
			$.post(this.options.submit_to, oParams, function(data){
				me.ajax_div.html(data);
			});
		}
	});	
});