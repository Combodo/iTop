//iTop Subform field
;
$(function()
{
	// the widget definition, where 'itop' is the namespace,
	// 'subform_field' the widget name
	$.widget( 'itop.subform_field', $.itop.form_field,
	{
		// default options
		options:
		{
			field_set: null
		},
   
		// the constructor
		_create: function()
		{
			var me = this;
			
			this.element
			.addClass('subform_field');

			this._super();
		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element
			.removeClass('subform_field');

			this._super();
		},
		getCurrentValue: function()
		{
			return this.options.field_set.triggerHandler('get_current_values');
		},
		validate: function(oEvent, oData)
		{
			return {
				is_valid: this.options.field_set.triggerHandler('validate', oData),
				error_messages: []
			}
		},
	});
});
