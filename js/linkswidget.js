// JavaScript Document
function LinksWidget(id, sClass, sAttCode, iInputId, sSuffix)
{
	this.id = id;
	this.iInputId = iInputId;
	this.sClass = sClass;
	this.sAttCode = sAttCode;
	this.sSuffix = sSuffix;
	var me = this;
	this.Init = function()
	{
		// make sure that the form is clean
		$('#linkedset_'+this.id+' .selection').each( function() { this.checked = false; });
		$('#'+this.id+'_btnRemove').attr('disabled','disabled');
		$('#'+this.id+'_linksToRemove').val('');
	}
	
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
		// Re-run the zebra plugin to properly highlight the remaining lines
		$('#linkset_'+this.id+' .listResults').trigger('update');
		
	}

	this.OnSelectChange = function()
	{
		var nbChecked = $('#linkedset_'+me.id+' .selection:checked').length;
		if (nbChecked > 0)
		{
			$('#'+me.id+'_btnRemove').attr('disabled','');
		}
		else
		{
			$('#'+me.id+'_btnRemove').attr('disabled','disabled');
		}
	}
	
	this.AddObjects  = function()
	{
		//$('#dlg_'+this.id).hide();
		$('#dlg_'+me.id).dialog('open');
		//alert('Not Yet Implemented !');
	}
	
	this.SearchObjectsToAdd = function()
	{
		var theMap = { sAttCode: me.sAttCode,
					   iInputId: me.iInputId,
					   sSuffix: me.sSuffix
					 }
		
		// Gather the parameters from the search form
		$('#SearchFormToAdd_'+me.id+' :input').each(
			function(i)
			{
				if (this.name != '')
				{
					theMap[this.name] = this.value;
				}
			}
		);
		
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
		
		// Run the query and display the results
		$.post( 'ajax.render.php', theMap, 
			function(data)
			{
				$(sSearchAreaId).html(data);
				$(sSearchAreaId+' .listResults').tablesorter( { headers: { 0:{sorter: false }}, widgets: ['zebra']} ); // sortable and zebra tables
				
			},
			'html'
		);

		return false; // Don't submit the form, stay in the current page !
	}

	this.DoAddObjects = function()
	{
		var theMap = { sAttCode: me.sAttCode,
				   	   iInputId: me.iInputId,
				   	   sSuffix: me.sSuffix,
				   	   'class': me.sClass
				 	 }
		
		// Gather the parameters from the search form
		$('#SearchResultsToAdd_'+me.id+' :checked').each(
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
					$(this).parents('tr:first').remove(); // Remove the whole line, so that, next time the dialog gets displayed it's no longer there
				}
			}
		);
		theMap['operation'] = 'doAddObjects';
		
		// Run the query and display the results
		$.post( 'ajax.render.php', theMap, 
			function(data)
			{
				//console.log('Data: ' + data);
				if (data != '')
				{
					$('#'+me.id+'_empty_row').remove();
					$('#linkedset_'+me.id+' .listResults tbody').append(data);
					$('#linkedset_'+me.id+' .listResults').trigger('update');
					$('#linkedset_'+me.id+' .listResults').tablesorter( { headers: { 0:{sorter: false }}, widgets: ['zebra']} ); // sortable and zebra tables
				}
			},
			'html'
		);
		$('#dlg_'+me.id).dialog('close');
		return false;
	}
}
