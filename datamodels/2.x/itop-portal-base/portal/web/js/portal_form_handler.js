//iTop Portal Form handler
//This extends itop.form_handler
;
$(function()
{
	// the widget definition, where 'itop.portal' is the namespace,
	// 'form_handler' the widget name
	$.widget( 'itop.portal_form_handler', $.itop.form_handler,
	{
		options: {
			submit_url: null,
			cancel_url: null
		},
		
		// the constructor
		_create: function()
		{
			this.element
			.addClass('portal_form_handler');
	
			// Safe check for options
			if(this.options.submit_url === "")
				this.options.submit_url = null;
			if(this.options.cancel_url === "")
				this.options.cancel_url = null;
			
			this._super();
		},
   
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element
			.removeClass('portal_form_handler');
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
		// Overload from parent class
		_onSubmitClick: function(oEvent)
		{
			oEvent.preventDefault();
			var me = this;

			// EasterEgg : Vibrate on submit
			if(window.navigator.vibrate)
			{
				window.navigator.vibrate(200);
			}

			// Validating fields prior to post (Client side)
			var bIsValid = me.options.field_set.triggerHandler('validate');
			// Retrieving stimulus name
			var sStimulusCode = null;
			if($(oEvent.currentTarget).attr('name') === 'stimulus_code')
			{
				sStimulusCode = $(oEvent.currentTarget).val();
			}

			// Submit form
			if(bIsValid)
			{
				me._disableFormBeforeLoading();
				$.post(
					me.options.endpoint,
					{
						operation: 'submit',
						stimulus_code: sStimulusCode,
						transaction_id: me.options.formmanager_data.transaction_id,
						formmanager_class: me.options.formmanager_class,
						formmanager_data: JSON.stringify(me.options.formmanager_data),
						current_values: me.getCurrentValues(),
						attachment_ids: me.getAttachmentIds()
					},
					function(oData){
						if(oData.form.validation !== undefined)
						{   
							var oValidation = oData.form.validation;
							
							// First we build the form
							me.options.field_set.field_set('option', 'fields_list', oData.form.fieldset.fields_list);
							me.options.field_set.field_set('option', 'is_valid', oValidation.valid);
							me.options.field_set.field_set('buildForm');

							// Then only we display messages from the server, otherwise they will be cleared by the HTML print
							var oMessages = oValidation.messages;

							// Cleaning help blocks
							me.element.find('.form_alerts').removeClass('has-success has-warning has-error');
							me.element.find('.form_alerts .alert').html('').hide();
							me.element.find('.form_field').removeClass('has-success has-warning has-error');
							me.element.find('.form_field .help-block').html('');

							// For each type of messages (error, warning, success)...
							for(var sMessageType in oMessages)
							{
								var sMessageClass = 'has-' + sMessageType;  
								// ... for each concerned fields ...
								for(var sFieldId in oMessages[sMessageType])
								{
									var oField = me.options.field_set.field_set('getField', sFieldId);
									var oHelpBlock = null;

									// Checking if the messages are for a field or for the whole form
									if(oField.length === 1)
									{
										oField.addClass(sMessageClass);
										oHelpBlock = oField.find('.help-block');
									}
									else
									{
										oHelpBlock = me.element.find('.form_alerts .alert.alert-' + sMessageType);
										oHelpBlock.show();
									}
									// ... add the message to its help block
									for(var i in oMessages[sMessageType][sFieldId])
									{
										oHelpBlock.append($('<p>' + oMessages[sMessageType][sFieldId][i] + '</p>'));
									}
								}
							}

							// Scrolling to top so the user can see messages
							$('body').scrollTop(0);
						
							// If everything is okay, we close the form and reload it.
							if(oValidation.valid)
							{
								if(me.options.is_modal)
								{
									me.element.closest('.modal').modal('hide');
								}

								// Checking if we have to redirect to another page
								if(oValidation.redirection !== undefined)
								{
									var oRedirection = oValidation.redirection;
									var bRedirectionAjax = (oRedirection.ajax !== undefined) ? oRedirection.ajax : false;
									var sUrl = null;

									// URL priority order :
									// redirection.url > me.option.submit_url > redirection.alternative_url
									if(oRedirection.url !== undefined)
									{
										sUrl = oRedirection.url;
									}
									else if(me.options.submit_url !== null)
									{
										sUrl = me.options.submit_url;
									}
									else if(oRedirection.alternative_url !== undefined)
									{
										sUrl = oRedirection.alternative_url;
									}

									if(sUrl !== null)
									{
										if(bRedirectionAjax)
										{
											// Creating a new modal
											var oModalElem = $('#modal-for-all').clone();
											oModalElem.attr('id', '').appendTo('body');
											// Loading content
											oModalElem.find('.modal-content').html($('#page_overlay .overlay_content').html());
											oModalElem.find('.modal-content').load(sUrl, {
													// Passing formmanager data to the next page, just in case it needs it (eg. when applying stimulus)
													formmanager_class: me.options.formmanager_class,
													formmanager_data: JSON.stringify(me.options.formmanager_data)
												}
											);
											
											oModalElem.modal('show');
										}
										else
										{
											// Showing loader while redirecting, otherwise user tend to click somewhere in the page.
											// Note : We use a timeout because .always() is called right after here and will hide the loader
											setTimeout(function(){ me._disableFormBeforeLoading(); }, 50);
											// Redirecting after a few ms so the user can see what happend
											setTimeout(function() { location.href = sUrl; }, 400);
										}
									}
								}
								else if(me.options.submit_url !== null)
								{
									// Showing loader while redirecting, otherwise user tend to click somewhere in the page.
									// Note : We use a timeout because .always() is called right after here and will hide the loader
									setTimeout(function(){ me._disableFormBeforeLoading(); }, 50);
									// Redirecting after a few ms so the user can see what happend
									setTimeout(function() { location.href = me.options.submit_url; }, 400);
								}
							}
						}
					}
				)
				.fail(function(oData){
					me._onUpdateFailure(oData);
				})
				.always(function(){
					me._enableFormAfterLoading();
				});
			}
			// Else go to the first invalid field
			else
			{
				// EasterEgg : Vibrate on submit
				if(window.navigator.vibrate)
				{
					window.navigator.vibrate([200, 100, 200]);
				}
				this.element.find('.has-error')[0].scrollIntoView();
			}
		},
		// Overload from parent class
		_onCancelClick: function(oEvent)
		{
			oEvent.preventDefault();
			oEvent.stopPropagation();
			
			var me = this;

			// When fields have been modified, we have to ask them to cancel stuff if necessary
			if(me.options.field_set.field_set('option', 'touched_fields').length > 0)
			{
				me._disableFormBeforeLoading();
				$.post(
					me.options.endpoint,
					{
						operation: 'cancel',
						formmanager_class: me.options.formmanager_class,
						formmanager_data: JSON.stringify(me.options.formmanager_data),
						current_values: me.getCurrentValues()
					},
					function(oData)
					{
						if(me.options.cancel_url !== null)
						{
							location.href = me.options.cancel_url;
						}
					}
				)
				.always(function(){
					// Close the modal only if fields had to be cancelled
					if(me.options.is_modal)
					{
						me.element.closest('.modal').modal('hide');
					}
					me._enableFormAfterLoading();
				});
			}
			// Otherwise we can close the modal immediately
			else
			{
				if(me.options.cancel_url !== null)
				{
					location.href = me.options.cancel_url;
				}
				else
				{
					if(me.options.is_modal)
					{
						me.element.closest('.modal').modal('hide');
					}
					else
					{
						location.reload();
					}
				}
			}
		},
		// Overload from parent class
		_onUpdateFailure: function(oData)
		{
			if(oData.responseJSON !== undefined && oData.responseJSON !== null)
			{
				var oResponse = oData.responseJSON;
				// If we encounter an error
				if(oResponse.exception !== undefined)
				{
					// Note : This could be refactored for a global use
					var oModalElem = $('#modal-for-alert');
					oModalElem.find('.modal-title').html(oResponse.error_title);
					oModalElem.find('.modal-body .alert').html(oResponse.error_message)
							.removeClass('alert-success alert-info alert-warning alert-danger')
							.addClass('alert-danger');
					oModalElem.modal('show');
				}
			}
		},
		// Overload from parent class
		_onUpdateAlways: function(oData, sFormPath)
		{
			// Check all touched AFTER ajax is complete, otherwise the renderer will redraw the field in the mean time.
			this.element.find('.form_fields').trigger('validate', {touched_fields_only: true});
			this._enableFormAfterLoading();
		},
		_onFieldChange: function(oEvent, oData)
		{
			// Clear form help blocks
			this.element.find('.form_alerts').removeClass('has-success has-warning has-error');
			this.element.find('.form_alerts .alert').html('').hide();
			
			this._super(oEvent, oData);
		},
		// Place a field for which no container exists
		_addField: function(sFieldId)
		{
			$('<div ' + this.options.field_identifier_attr + '="'+sFieldId+'"></div>').appendTo(this.element.find('.form_fields'));
		},
		_disableFormBeforeLoading: function()
		{
			$('#page_overlay').fadeIn(200);
		},
		_enableFormAfterLoading: function()
		{
			$('#page_overlay').fadeOut(200);
		},
		submit: function(oEvent)
		{
			this._onSubmitClick(oEvent);
		},
		getOptions: function()
		{
			return this.options;
		},
		getAttachmentIds: function()
		{
			var me = this;
			var aResult = {actual_attachments_ids: [], removed_attachments_ids: []};
			
			// Actual attachments
			this.element.find('.attachments_container :input[name="attachments[]"]').each(function(iIndex, oElement){
				aResult.actual_attachments_ids.push($(oElement).val());
			});
			// Removed attachments
			this.element.find('.attachments_container :input[name="removed_attachments[]"]').each(function(iIndex, oElement){
				aResult.removed_attachments_ids.push($(oElement).val());
			});
			
			return aResult;
		}
	});
});
