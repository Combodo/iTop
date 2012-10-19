// jQuery UI style "widget" for managing 1:n links "in-place"
$(function()
{
	// the widget definition, where "itop" is the namespace,
	// "directlinks" the widget name
	$.widget( "itop.directlinks",
	{
		// default options
		options:
		{
			input_name: '',
			class_name: '',
			att_code: '',
			submit_to: '../pages/ajax.render.php',
			submit_parameters: {},
			labels: { 'delete': 'Delete',
				  	  modify: 'Modify...' , 
				  	  creation_title: 'Creation of a new object...' , 
					  create: 'Create...'
					}
		},
	
		// the constructor
		_create: function()
		{
			var me = this;
			this.id = this.element.attr('id');

			this.element
			.addClass('itop-directlinks');
			
			this.datatable = this.element.find('table.listResults');
			
			this.deleteBtn = $('<button type="button">' + this.options.labels['delete'] + '</button>');
			this.modifyBtn = $('<button type="button">' + this.options.labels['modify'] + '</button>');
			this.createBtn = $('<button type="button">' + this.options.labels['create'] + '</button>');
			this.indicator = $('<span></span>');
			this.inputToBeCreated = $('<input type="hidden" name="'+this.options.input_name+'_tbc" value="{}">');
			this.toBeCreated = {};
			this.inputToBeDeleted = $('<input type="hidden" name="'+this.options.input_name+'_tbd" value="[]">');
			this.toBeDeleted = [];
			
			
			this.element
				.after(this.inputToBeCreated)
				.after(this.inputToBeDeleted)				
			 	.after('<span style="float:left">&nbsp;&nbsp;&nbsp;<img src="../images/tv-item-last.gif">&nbsp;&nbsp;&nbsp;')
			 	.after(this.indicator).after(this.createBtn).after('&nbsp;&nbsp;&nbsp')
			 	.after(this.modifyBtn).after('&nbsp;&nbsp;&nbsp')
			 	.after(this.deleteBtn);
			
			this.element.find('.selectList'+this.id).bind('change', function() { me._updateButtons(); });
			this.deleteBtn.click(function() {
				$('.selectList'+me.id+':checked', me.element).each( function() { me._deleteRow($(this)); });
			});
			this.createBtn.click(function() {
				me._createRow();
			});
			
			this.modifyBtn.hide(); //hidden for now since it's not yet implemented
			
			this._updateButtons();
		},
	
		// called when created, and later when changing options
		_refresh: function()
		{
			this._updateButtons();
		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		destroy: function()
		{
			this.element
			.removeClass('itop-directlinks');
			
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
			
			if (key == 'fields') this._refresh();
		},
		_updateButtons: function()
		{
			var oChecked = $('.selectList'+this.id+':checked', this.element);
			switch(oChecked.length)
			{
				case 0:
					this.deleteBtn.attr('disabled', 'disabled');
					this.modifyBtn.attr('disabled', 'disabled');
				break;
			
				case 1:
					this.deleteBtn.removeAttr('disabled');
					this.modifyBtn.removeAttr('disabled');
				break;
				
				default:
					this.deleteBtn.removeAttr('disabled');
					this.modifyBtn.attr('disabled', 'disabled');
				break;
			}
		},
		_updateTable: function()
		{
			var me = this;
			this.datatable.trigger("update").trigger("applyWidgets");
			this.datatable.tableHover();
			this.datatable.find('.selectList'+this.id).bind('change', function() { me._updateButtons(); });
		},
		_updateDlgSize: function()
		{
			this.oDlg.dialog('option', { position: { my: "center", at: "center", of: window }});
		},
		_createRow: function()
		{
			this.createBtn.attr('disabled', 'disabled');
			this.indicator.html('<img src="../images/indicator.gif">');
			oParams = this.options.submit_parameters;
			oParams.operation = 'createObject';
			oParams['class'] = this.options.class_name;
			oParams.real_class = '';
			oParams.att_code = this.options.att_code;
			oParams.iInputId = this.id;
			var me = this;
			$.post(this.options.submit_to, oParams, function(data){
				me.oDlg = $('<div></div>');
				$('body').append(me.oDlg);
				me.oDlg.html(data);
				me.oDlg.find('form').removeAttr('onsubmit').bind('submit', function() { me._onCreateRow(); return false; } );
				me.oDlg.find('button.cancel').unbind('click').click( function() { me.oDlg.dialog('close'); } );
				
				me.oDlg.dialog({
					title: me.options.labels['creation_title'],
					modal: true,
					width: 'auto',
					height: 'auto',
					position: { my: "center", at: "center", of: window },
					close: function() { me._onDlgClose(); }
				});
				me.indicator.html('');
				me.createBtn.removeAttr('disabled');
				me._updateDlgSize();
			});
		},
		subclassSelected: function()
		{
			var sRealClass = this.oDlg.find('select[name="class"]').val();
			oParams = this.options.submit_parameters;
			oParams.operation = 'createObject';
			oParams['class'] = this.options.class_name;
			oParams.real_class = sRealClass;
			oParams.att_code = this.options.att_code;
			oParams.iInputId = this.id;
			var me = this;
			me.oDlg.find('button').attr('disabled', 'disabled');
			me.oDlg.find('span.indicator').html('<img src="../images/indicator.gif">');
			$.post(this.options.submit_to, oParams, function(data){
				me.oDlg.html(data);
				me.oDlg.find('form').removeAttr('onsubmit').bind('submit', function() { me._onCreateRow(); return false; } );
				me.oDlg.find('button.cancel').unbind('click').click( function() { me.oDlg.dialog('close'); } );
				me._updateDlgSize();				
			});
		},
		_onCreateRow: function()
		{
			// Validate the form
			var sFormId = this.oDlg.find('form').attr('id');
			if (CheckFields(sFormId, true))
			{
				// Gather the values from the form
				oParams = this.options.submit_parameters;
				var oValues = {};
				this.oDlg.find(':input').each( function() {
					if (this.name != '')
					{
						oParams[this.name] = this.value;
						oValues[this.name] = this.value;
					}
				});
				var nextIdx = 0;
				for(k in this.toBeCreated)
				{
					nextIdx++;
				}
				nextIdx++;
				this.toBeCreated[nextIdx] = oValues;
				this.inputToBeCreated.val(JSON.stringify(this.toBeCreated));
				this.oDlg.dialog('close');
				
				oParams = this.options.submit_parameters;
				oParams.operation = 'getLinksetRow';
				oParams['class'] = this.options.class_name;
				oParams.att_code = this.options.att_code;
				oParams.iInputId = this.id;
				oParams.tempId = nextIdx;
				var me = this;

				this.createBtn.attr('disabled', 'disabled');
				this.indicator.html('<img src="../images/indicator.gif">');

				$.post(this.options.submit_to, oParams, function(data){
					me.datatable.find('tbody').append(data);
					me._updateTable();
					me.indicator.html('');
					me.createBtn.removeAttr('disabled');
				});
			}
		},
		_onDlgClose: function()
		{
			this.oDlg.remove();
			this.oDlg = null;
		},
		_deleteRow: function(oCheckbox)
		{
			var iObjKey = parseInt(oCheckbox.val(), 10); // Number in base 10
			
			if (iObjKey > 0)
			{
				// Existing objet: add it to the "to be deleted" list
				this.toBeDeleted.push(iObjKey);
				this.inputToBeDeleted.val(JSON.stringify(this.toBeDeleted));
			}
			else
			{
				// Object to be created, just remove it from the "to be created" list
				this.toBeCreated[-iObjKey] = undefined;
				this.inputToBeCreated.val(JSON.stringify(this.toBeCreated));
			}
			// Now remove the row from the table
			oRow = oCheckbox.closest('tr');
			oRow.remove();
			this._updateButtons();
			this._updateTable();
		}
	});	
});