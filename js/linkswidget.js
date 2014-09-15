// JavaScript Document
function LinksWidget(id, sClass, sAttCode, iInputId, sSuffix, bDuplicates, oWizHelper, sExtKeyToRemote)
{
	this.id = id;
	this.iInputId = iInputId;
	this.sClass = sClass;
	this.sAttCode = sAttCode;
	this.sSuffix = sSuffix;
	this.bDuplicates = bDuplicates;
	this.oWizardHelper = oWizHelper;
	this.sExtKeyToRemote = sExtKeyToRemote;
	var me = this;
	this.Init = function()
	{
		// make sure that the form is clean
		$('#linkedset_'+this.id+' .selection').each( function() { this.checked = false; });
		$('#'+this.id+'_btnRemove').attr('disabled','disabled');
		$('#'+this.id+'_linksToRemove').val('');
	};
	
	this.RemoveSelected = function()
	{
		var my_id = '#'+me.id;
		$('#linkedset_'+me.id+' .selection:checked').each(
			function()
			{
				$linksToRemove = $(my_id+'_linksToRemove');
				prevValue = $linksToRemove.val();
				if (prevValue != '')
				{
					$linksToRemove.val(prevValue + ',' + this.value);
				}
				else
				{
					$linksToRemove.val(this.value);
				}
				$(my_id+'_row_'+this.value).remove();
			}
		);
		// Disable the button since all the selected items have been removed
		$(my_id+'_btnRemove').attr('disabled','disabled');
		// Re-run the zebra plugin to properly highlight the remaining lines & and take into account the removed ones
		$('#linkedset_'+this.id+' .listResults').trigger('update').trigger("applyWidgets");
		
		if ($('#linkedset_'+this.id+' .selection').length == 0)
		{
			// All items were removed: add a dummy hidden input to make sure that the linkset will be updated (emptied) when posted
			$('#'+me.id+'_empty_row').show();
		}
	};

	this.OnSelectChange = function()
	{
		var nbChecked = $('#linkedset_'+me.id+' .selection:checked').length;
		if (nbChecked > 0)
		{
			$('#'+me.id+'_btnRemove').removeAttr('disabled');
		}
		else
		{
			$('#'+me.id+'_btnRemove').attr('disabled','disabled');
		}
	};
	
	this.AddObjects  = function()
	{
		var me = this;
		$('#'+me.id+'_indicatorAdd').html('&nbsp;<img src="../images/indicator.gif"/>');
		me.oWizardHelper.UpdateWizard();
		var theMap = { sAttCode: me.sAttCode,
				   iInputId: me.iInputId,
				   sSuffix: me.sSuffix,
				   bDuplicates: me.bDuplicates,
				   'class' : me.sClass,
				   operation: 'addObjects',
				   json: me.oWizardHelper.ToJSON()
				 };
		$.post( GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', theMap, 
				function(data)
				{
					$('#dlg_'+me.id).html(data);
					$('#dlg_'+me.id).dialog('open');
					me.UpdateSizes(null, null);
					me.SearchObjectsToAdd();
					$('#'+me.id+'_indicatorAdd').html('');
				},
				'html'
			);
	};
	
	this.SearchObjectsToAdd = function()
	{
		var theMap = { sAttCode: me.sAttCode,
					   iInputId: me.iInputId,
					   sSuffix: me.sSuffix,
					   bDuplicates: me.bDuplicates
					 };
		
		me.UpdateButtons(0);
		// Gather the parameters from the search form
		$('#SearchFormToAdd_'+me.id+' :input').each( function() {
				if (this.name != '')
				{
					var val = $(this).val(); // supports multiselect as well
					if (val !== null)
					{
						theMap[this.name] = val;					
					}
				}
		});
		
		// Gather the already linked target objects
		theMap.aAlreadyLinked = new Array();
		$('#linkedset_'+me.id+' .selection:input').each(
			function(i)
			{
				theMap.aAlreadyLinked.push(this.value);
			}
		);
		theMap['sRemoteClass'] = theMap['class'];  // swap 'class' (defined in the form) and 'remoteClass'
		theMap['class'] = me.sClass;
		theMap.operation = 'searchObjectsToAdd'; // Override what is defined in the form itself
		
		sSearchAreaId = '#SearchResultsToAdd_'+me.id;
		$(sSearchAreaId).block();
		
		// Run the query and display the results
		$.post( GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', theMap, 
			function(data)
			{
				$(sSearchAreaId).html(data);
				$(sSearchAreaId+' .listResults').tableHover();
				$('#count_'+me.id).change(function(){
					var c = this.value;
					me.UpdateButtons(c);
				});
				$(sSearchAreaId).unblock();		
			},
			'html'
		);

		return false; // Don't submit the form, stay in the current page !
	};

	this.UpdateButtons = function(iCount)
	{
		var okBtn = $('#btn_ok_'+me.id);
		if (iCount > 0)
		{
			okBtn.removeAttr('disabled');
		}
		else
		{
			okBtn.attr('disabled', 'disabled');
		}
	};
	
	this.DoAddObjects = function()
	{
		var theMap = { sAttCode: me.sAttCode,
				   	   iInputId: me.iInputId,
				   	   sSuffix: me.sSuffix,
				   	   bDuplicates: me.bDuplicates,
				   	   'class': me.sClass
				 	 };
		
		// Gather the parameters from the search form
		var context = $('#SearchResultsToAdd_'+me.id);
		var selectionMode = $(':input[name=selectionMode]', context);
		if (selectionMode.length > 0)
		{
			// Paginated table retrieve the mode and the exceptions
			var sMode = selectionMode.val();
			theMap['selectionMode'] = sMode;
			$('#fs_SearchFormToAdd_'+me.id+' :input').each(
					function(i)
					{
						theMap[this.name] = this.value;
					}
				);
			theMap['sRemoteClass'] = theMap['class'];  // swap 'class' (defined in the form) and 'remoteClass'
			theMap['class'] = me.sClass;
			$(' :input[name^=storedSelection]', context).each(function() {
				if (theMap[this.name] == undefined)
				{
					theMap[this.name] = new Array();
				}
				theMap[this.name].push(this.value);
				$(this).remove(); // Remove the selection for the next time the dialog re-opens
			});
			// Retrieve the 'filter' definition
			var table = $('#ResultsToAdd_'+me.id).find('table.listResults')[0];
			theMap['filter'] = table.config.filter;
			theMap['extra_params'] = table.config.extra_params;
		}
//		else
//		{
			// Normal table, retrieve all the checked check-boxes
			$(':checked[name^=selectObject]', context).each(
				function(i)
				{
					if ( (this.name != '') && ((this.type != 'checkbox') || (this.checked)) ) 
					{
						//console.log(this.type);
						arrayExpr = /\[\]$/;
						if (arrayExpr.test(this.name))
						{
							// Array
							if (theMap[this.name] == undefined)
							{
								theMap[this.name] = new Array();
							}
							theMap[this.name].push(this.value);
						}
						else
						{
							theMap[this.name] = this.value;
						}						
					}
					$(this).parents('tr:first').remove(); // Remove the whole line, so that, next time the dialog gets displayed it's no longer there
				}
			);
//		}
		
		theMap['operation'] = 'doAddObjects';
		if (me.oWizardHelper == null)
		{
			theMap['json'] = '';
		}
		else
		{
			// Not inside a "search form", updating a real object
			me.oWizardHelper.UpdateWizard();
			theMap['json'] = me.oWizardHelper.ToJSON();
		}
		$('#busy_'+me.iInputId).html('&nbsp;<img src="../images/indicator.gif"/>');
		// Run the query and display the results
		$.post( GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', theMap, 
			function(data)
			{
				//console.log('Data: ' + data);
				if (data != '')
				{
					$('#'+me.id+'_empty_row').hide();
					$('#linkedset_'+me.id+' .listResults tbody').prepend(data);
					$('#linkedset_'+me.id+' .listResults').trigger('update');
					$('#linkedset_'+me.id+' .listResults').tableHover();
					$('#linkedset_'+me.id+' .listResults').trigger('update').trigger("applyWidgets"); // table is already sortable, just refresh it
					$('#linkedset_'+me.id+' :input').each( function() { $(this).trigger('validate', ''); }); // Validate newly added form fields...
					$('#busy_'+me.iInputId).html('');
				}
			},
			'html'
		);
		$('#dlg_'+me.id).dialog('close');
		return false;
	};
	
	this.UpdateSizes = function(event, ui)
	{
		var dlg = $('#dlg_'+me.id);
		var searchForm = $('#SearchFormToAdd_'+me.id);
		var results = $('#SearchResultsToAdd_'+me.id);
		var padding_right = 0;
		if (dlg.css('padding-right'))
		{
			padding_right = parseInt(dlg.css('padding-right').replace('px', ''));			
		}
		var padding_left = 0;
		if (dlg.css('padding-left'))
		{
			padding_left = parseInt(dlg.css('padding-left').replace('px', ''));			
		}
		var padding_top = 0;
		if (dlg.css('padding-top'))
		{
			padding_top = parseInt(dlg.css('padding-top').replace('px', ''));			
		}
		var padding_bottom = 0;
		if (dlg.css('padding-bottom'))
		{
			padding_bottom = parseInt(dlg.css('padding-bottom').replace('px', ''));			
		}
		width = dlg.innerWidth() - padding_right - padding_left - 22; // 5 (margin-left) + 5 (padding-left) + 5 (padding-right) + 5 (margin-right) + 2 for rounding !
		height = dlg.innerHeight() - padding_top - padding_bottom -22;
		wizard = dlg.find('.wizContainer:first');
		wizard.width(width);
		wizard.height(height);
		form_height = searchForm.outerHeight();
		results.height(height - form_height - 40); // Leave some space for the buttons
	};
	
	this.GetUpdatedValue = function()
	{
		var sSelector = '#linkedset_'+me.id+' input[name^=attr_'+me.id+']';
		var aIndexes = [];
		var aValues = [];
		$(sSelector).each(function() {
			var re = /\[([^\[]+)\]\[(.+)\]/;
			var aMatches = [];
			if (aMatches = this.name.match(re))
			{
				var idx = aMatches[1];
				var index = jQuery.inArray(idx, aIndexes);
				if (index == -1)
				{
					aIndexes.push(idx);
					index = jQuery.inArray(idx, aIndexes);
					aValues[index] = {};
				}
				var value = $(this).val();
				if (aMatches[2] == "id")
				{
					var iId = parseInt(aMatches[1], 10);
					if (iId < 0)
					{
						aValues[index][me.sExtKeyToRemote] = -iId;
					}
					else
					{
						aValues[index]['id'] = value;						
					}
				}
				else
				{
					aValues[index][aMatches[2]] = value;					
				}
			}
		});
		return JSON.stringify(aValues);
	};
}
