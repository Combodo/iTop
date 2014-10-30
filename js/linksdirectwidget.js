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
					  create: 'Create...',
					  add: 'Add...',
					  remove: 'Remove',
					  selection_title: 'Objects selection'
					},
			buttons: ['create', 'delete'],
			oWizardHelper: null
		},
	
		// the constructor
		_create: function()
		{
			var me = this;
			this.id = this.element.attr('id');

			this.element
			.addClass('itop-directlinks');
			
			this.datatable = this.element.find('table.listResults');
			
			var aButtonsTypes = ['delete', 'remove', 'modify', 'add', 'create'];
			this.oButtons = {};
			for(k in aButtonsTypes)
			{
				this.oButtons[aButtonsTypes[k]] =  $('<button type="button">' + this.options.labels[aButtonsTypes[k]] + '</button>');
			}
			this.indicator = $('<span></span>');
			this.inputToBeCreated = $('<input type="hidden" name="'+this.options.input_name+'_tbc" value="{}">');
			this.toBeCreated = {};
			this.inputToBeDeleted = $('<input type="hidden" name="'+this.options.input_name+'_tbd" value="[]">');
			this.toBeDeleted = [];
			this.inputToBeAdded = $('<input type="hidden" name="'+this.options.input_name+'_tba" value="[]">');
			this.toBeAdded = [];
			this.inputToBeRemoved = $('<input type="hidden" name="'+this.options.input_name+'_tbr" value="[]">');
			this.toBeRemoved = [];
			
			
			this.element
				.after(this.inputToBeCreated)
				.after(this.inputToBeDeleted)				
				.after(this.inputToBeAdded)				
				.after(this.inputToBeRemoved)				
			 	.after('<span style="float:left">&nbsp;&nbsp;&nbsp;<img src="../images/tv-item-last.gif">&nbsp;&nbsp;&nbsp;')
			 	.after(this.indicator);
			for(k in this.options.buttons)
			{
				this.element.after(this.oButtons[this.options.buttons[k]]).after('&nbsp;&nbsp;&nbsp;');
			}
			
			this.element.find('.selectList'+this.id).bind('change', function() { me._updateButtons(); });
			this.oButtons['delete'].click(function() {
				$('.selectList'+me.id+':checked', me.element).each( function() { me._deleteRow($(this)); });
			});
			this.oButtons['create'].click(function() {
				me._createRow();
			});
			this.oButtons['remove'].click(function() {
				$('.selectList'+me.id+':checked', me.element).each( function() { me._removeRow($(this)); });
			});
			this.oButtons['add'].click(function() {
				me._selectToAdd();
			});
						
			this._updateButtons();
		},
	
		// called when created, and later when changing options
		_refresh: function()
		{
			this._updateButtons();
		},
		// events bound via _bind are removed automatically
		// revert other modifications here
		_destroy: function()
		{
			this.element
			.removeClass('itop-directlinks');			
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
		_updateButtons: function()
		{
			var oChecked = $('.selectList'+this.id+':checked', this.element);
			switch(oChecked.length)
			{
				case 0:
					this.oButtons['delete'].attr('disabled', 'disabled');
					this.oButtons['remove'].attr('disabled', 'disabled');
					this.oButtons['modify'].attr('disabled', 'disabled');
				break;
			
				case 1:
					this.oButtons['delete'].removeAttr('disabled');
					this.oButtons['remove'].removeAttr('disabled');
					this.oButtons['modify'].removeAttr('disabled');
				break;
				
				default:
					this.oButtons['delete'].removeAttr('disabled');
					this.oButtons['remove'].removeAttr('disabled');
					this.oButtons['modify'].attr('disabled', 'disabled');
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
		_updateDlgPosition: function()
		{
			this.oDlg.dialog('option', { position: { my: "center", at: "center", of: window }});
		},
		_createRow: function()
		{
			this.oButtons['create'].attr('disabled', 'disabled');
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
				me.oButtons['create'].removeAttr('disabled');
				me._updateDlgPosition();
			});
		},
		_selectToAdd: function()
		{
			this.oButtons['add'].attr('disabled', 'disabled');
			this.indicator.html('<img src="../images/indicator.gif">');
			oParams = this.options.submit_parameters;
			oParams.operation = 'selectObjectsToAdd';
			oParams['class'] = this.options.class_name;
			oParams.real_class = '';
			oParams.att_code = this.options.att_code;
			oParams.iInputId = this.id;
			if (this.options.oWizardHelper)
			{
				this.options.oWizardHelper.UpdateWizard();
				oParams.json = this.options.oWizardHelper.ToJSON();
			}
			var me = this;
			$.post(this.options.submit_to, oParams, function(data){
				me.oDlg = $('<div></div>');
				$('body').append(me.oDlg);
				me.oDlg.html(data);
				me.oDlg.find('form').removeAttr('onsubmit').bind('submit', function() { me._onSearchToAdd(); return false; } );
				me.oDlg.find('button.cancel').unbind('click').click( function() { me.oDlg.dialog('close'); } );
				me.oDlg.find('button.ok').unbind('click').click( function() { me._onDoAdd(); } );
				$('#SearchFormToAdd_'+me.id).resize(function() { me._onSearchDlgUpdateSize(); });
				
				me.oDlg.dialog({
					title: me.options.labels['selection_title'],
					modal: true,
					width: $(window).width()*0.8,
					height: $(window).height()*0.8,
					maxHeight: $(window).height() - 50,
					position: { my: "center", at: "center", of: window },
					close: function() { me._onDlgClose(); },
					resizeStop: function() { me._onSearchDlgUpdateSize(); }
				});
				me.indicator.html('');
				me.oButtons['add'].removeAttr('disabled');
				me._onSearchToAdd();
				me._updateDlgPosition();
				me._onSearchDlgUpdateSize();
			});
		},
		_onSearchToAdd: function()
		{
			var oParams = {};
			// Gather the parameters from the search form
			$('#SearchFormToAdd_'+this.id+' :input').each( function() {
					if (this.name != '')
					{
						var val = $(this).val(); // supports multiselect as well
						if (val !== null)
						{
							oParams[this.name] = val;					
						}
					}
			});
			// Gather the already linked target objects
			oParams.aAlreadyLinked = new Array();
			$('#'+this.id+' .listResults td input:checkbox').each(function(){
					iKey = parseInt(this.value, 10); // Numbers are in base 10
					oParams.aAlreadyLinked.push(iKey);
				}
			);
			oParams.operation = 'searchObjectsToAdd2';
			oParams['class'] = this.options.class_name;
			oParams.real_class = '';
			oParams.att_code = this.options.att_code;
			oParams.iInputId = this.id;
			if (this.options.oWizardHelper)
			{
				this.options.oWizardHelper.UpdateWizard();
				oParams.json = this.options.oWizardHelper.ToJSON();
			}
			var me = this;
			$('#SearchResultsToAdd_'+me.id).block();
			$.post(this.options.submit_to, oParams, function(data) {
				
				$('#SearchResultsToAdd_'+me.id).html(data);
				$('#SearchResultsToAdd_'+me.id+' .listResults').tableHover();
				$('#count_'+me.id).change(function() {
					var c = this.value;
					me._onUpdateDlgButtons(c);
				});
				$('#SearchResultsToAdd_'+me.id).unblock();
				me._onSearchDlgUpdateSize();
			});
			return false; // Stay on the page, no submit
		},
		_getSelection: function(sName)
		{
			// Gather the parameters from the search form
			var oMap = {};
			var oContext = $('#SearchResultsToAdd_'+this.id);
			var selectionMode = $(':input[name=selectionMode]', oContext);
			if (selectionMode.length > 0)
			{
				// Paginated table retrieve the mode and the exceptions
				var sMode = selectionMode.val();
				oMap['selectionMode'] = sMode;
				$('#fs_SearchFormToAdd_'+this.id+' :input').each(
						function(i)
						{
							oMap[this.name] = this.value;
						}
					);
				$(':input[name^=storedSelection]', oContext).each(function() {
					if (oMap[this.name] == undefined)
					{
						oMap[this.name] = new Array();
					}
					oMap[this.name].push(this.value);
				});
				// Retrieve the 'filter' definition
				var table = $('#ResultsToAdd_'+this.id).find('table.listResults')[0];
				oMap['filter'] = table.config.filter;
				oMap['extra_params'] = table.config.extra_params;
			}
			// Normal table, retrieve all the checked check-boxes
			$(':checked[name^=selectObject]', oContext).each(
				function(i)
				{
					if ( (this.name != '') && ((this.type != 'checkbox') || (this.checked)) ) 
					{
						arrayExpr = /\[\]$/;
						if (arrayExpr.test(this.name))
						{
							// Array
							if (oMap[this.name] == undefined)
							{
								oMap[this.name] = new Array();
							}
							oMap[this.name].push(this.value);
						}
						else
						{
							oMap[this.name] = this.value;
						}						
					}
				}
			);
			return oMap;
		},
		_onUpdateDlgButtons: function(iCount)
		{
			if (iCount > 0)
			{
				this.oDlg.find('button.ok').removeAttr('disabled');
			}
			else
			{
				this.oDlg.find('button.ok').attr('disabled', 'disabled');				
			}
		},
		_onDoAdd:function()
		{
			var oParams = this._getSelection('selectObject');
			oParams.operation = 'doAddObjects2';
			oParams['class'] = this.options.class_name;
			oParams.att_code = this.options.att_code;
			oParams.iInputId = this.id;
			
			// Retrieve the 'filter' definition, BEFORE closing the dialog and destroying its contents
			var table = $('#ResultsToAdd_'+this.id).find('table.listResults')[0];
			oParams.filter = table.config.filter;
			oParams.extra_params = table.config.extra_params;

			this.oDlg.dialog('close');
			
			var me = this;
			$.post(this.options.submit_to, oParams, function(data) {
				var oInserted = $(data);
				oInserted.find('input:checkbox').each(function() {
					var iKey = parseInt($(this).val(), 10); // Number in base 10
					me.toBeAdded.push(iKey);
					me.toBeRemoved = me._ArrayRemove(me.toBeRemoved, iKey);
					me.toBeDeleted = me._ArrayRemove(me.toBeDeleted, iKey);
				});
				me.inputToBeAdded.val(JSON.stringify(me.toBeAdded));
				me.inputToBeRemoved.val(JSON.stringify(me.toBeRemoved));
				me.inputToBeDeleted.val(JSON.stringify(me.toBeDeleted));
				me.datatable.find('tbody').append(data);
				me._updateTable();
				me.indicator.html('');
				me.oButtons['add'].removeAttr('disabled');
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
				me._updateDlgPosition();				
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

				this.oButtons['create'].attr('disabled', 'disabled');
				this.indicator.html('<img src="../images/indicator.gif">');

				$.post(this.options.submit_to, oParams, function(data){
					me.datatable.find('tbody').append(data);
					me._updateTable();
					me.indicator.html('');
					me.oButtons['create'].removeAttr('disabled');
				});
			}
		},
		_onDlgClose: function()
		{
			this.oDlg.remove();
			this.oDlg = null;
		},
		_onSearchDlgUpdateSize: function()
		{
			var searchHeight = $('#SearchFormToAdd_'+this.id).outerHeight();
			var dlgHeight = this.oDlg.height();
			$('.wizContainer', this.oDlg).height(dlgHeight - 20);
			$('#SearchResultsToAdd_'+this.id).height(dlgHeight - 50 - searchHeight);
		},
		_deleteRow: function(oCheckbox)
		{
			var iObjKey = parseInt(oCheckbox.val(), 10); // Number in base 10
			
			if (iObjKey > 0)
			{
				// Existing objet: add it to the "to be deleted" list
				// if it has not just been added now
				if (this._InArray(this.toBeAdded, iObjKey))
				{
					this.toBeAdded = this._ArrayRemove(this.toBeAdded, iObjKey);
					this.inputToBeAdded.val(JSON.stringify(this.toBeAdded));					
				}
				else
				{
					this.toBeDeleted.push(iObjKey);					
					this.inputToBeDeleted.val(JSON.stringify(this.toBeDeleted));
				}
			}
			else
			{
				// Object to be created, just remove it from the "to be created" list
				this.toBeCreated = this._ArrayRemove(this.toBeCreated, iObjKey);
				this.inputToBeCreated.val(JSON.stringify(this.toBeCreated));
			}
			// Now remove the row from the table
			oRow = oCheckbox.closest('tr');
			oRow.remove();
			this._updateButtons();
			this._updateTable();
		},
		_removeRow: function(oCheckbox)
		{
			var iObjKey = parseInt(oCheckbox.val(), 10); // Number in base 10
			
			if (iObjKey > 0)
			{
				// Existing objet: add it to the "to be removed" list
				// if it has not just been added now
				if (this._InArray(this.toBeAdded, iObjKey))
				{
					this.toBeAdded = this._ArrayRemove(this.toBeAdded, iObjKey);
					this.inputToBeAdded.val(JSON.stringify(this.toBeAdded));					
				}
				else
				{
					this.toBeRemoved.push(iObjKey);					
					this.inputToBeRemoved.val(JSON.stringify(this.toBeRemoved));
				}
			}
			else
			{
				// Object to be created, just remove it from the "to be created" list
				this.toBeCreated = this._ArrayRemove(this.toBeCreated, iObjKey);
				this.inputToBeCreated.val(JSON.stringify(this.toBeCreated));
			}
			// Now remove the row from the table
			oRow = oCheckbox.closest('tr');
			oRow.remove();
			this._updateButtons();
			this._updateTable();
		},
		_InArray: function(aArrayToSearch, needle)
		{
			aRes = [];
			for(k in aArrayToSearch)
			{
				if (aArrayToSearch[k] == needle)
				{
					return true;
				}
			}
			return false;
		},
		_ArrayRemove: function(aArrayToFilter, needle)
		{
			aRes = [];
			for(k in aArrayToFilter)
			{
				if (aArrayToFilter[k] != needle)
				{
					aRes.push(aArrayToFilter[k]);
				}
			}
			return aRes;
		}
	});	
});