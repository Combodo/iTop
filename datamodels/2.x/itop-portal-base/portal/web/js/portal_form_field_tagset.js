//iTop Portal Form field TagSet
//Used for field containing tagset ...
;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'portal_form_field' the widget name
	$.widget( 'itop.portal_form_field_tagset', $.itop.portal_form_field,
	{
		// the constructor
		_create: function()
		{
			this.element
			.addClass('portal_form_field_tagset');

            this.element.find('input[type="hidden"]').tagset_widget();
	
			this._super();
		},  
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element
			.removeClass('portal_form_field_tagset');
	
			this._super();
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
		validate: function(oEvent, oData)
		{
			var oResult = { is_valid: true, error_messages: [] };
			
			// Doing data validation
			if(this.options.validators !== null)
			{
				// TODO
			}
			
			this.options.on_validation_callback(this, oResult);
			
			return oResult;
		}
	});
});
light