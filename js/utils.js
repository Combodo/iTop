// Some general purpose JS functions for the iTop application
/**
 * Reload a truncated list
 */ 
function ReloadTruncatedList(divId, sSerializedFilter, sExtraParams)
{
	$('#'+divId).addClass('loading');
	//$('#'+divId).blockUI();
	$.post('ajax.render.php?style=list',
	   { operation: 'ajax', filter: sSerializedFilter, extra_params: sExtraParams },
	   function(data){
		 $('#'+divId).empty();
		 $('#'+divId).append(data);
		 $('#'+divId).removeClass('loading');
		 $('#'+divId+' .listResults').tableHover(); // hover tables
		 $('#'+divId+' .listResults').each( function()
				{
					var table = $(this);
					var id = $(this).parent();
					var checkbox = (table.find('th:first :checkbox').length > 0);
					if (checkbox)
					{
						// There is a checkbox in the first column, don't make it sortable
						table.tablesorter( { headers: { 0: {sorter: false}}, widgets: ['myZebra', 'truncatedList']} ); // sortable and zebra tables
					}
					else
					{
						// There is NO checkbox in the first column, all columns are considered sortable
						table.tablesorter( { widgets: ['myZebra', 'truncatedList']} ); // sortable and zebra tables
					}
				});
		 //$('#'+divId).unblockUI();
		}
	 );
}
/**
 * Truncate a previously expanded list !
 */
function TruncateList(divId, iLimit, sNewLabel, sLinkLabel)
{
	var iCount = 0;
	$('#'+divId+' table.listResults tr:gt('+iLimit+')').each( function(){
			$(this).remove();
	});
	$('#lbl_'+divId).html(sNewLabel);
	$('#'+divId+' table.listResults tr:last td').addClass('truncated');
	$('#'+divId+' table.listResults').addClass('truncated');
	$('#trc_'+divId).html(sLinkLabel);
	$('#'+divId+' .listResults').trigger("update"); //  Reset the cache
}
/**
 * Reload any block -- used for periodic auto-reload
 */ 
function ReloadBlock(divId, sStyle, sSerializedFilter, sExtraParams)
{
	$('#'+divId).addClass('loading');
	//$('#'+divId).blockUI();
	$.post('ajax.render.php?style='+sStyle,
	   { operation: 'ajax', filter: sSerializedFilter, extra_params: sExtraParams },
	   function(data){
		 $('#'+divId).empty();
		 $('#'+divId).append(data);
		 $('#'+divId).removeClass('loading');
		 $('#'+divId+' .listResults').tableHover(); // hover tables
		 $('#'+divId+' .listResults').each( function()
				{
					var table = $(this);
					var id = $(this).parent();
					var checkbox = (table.find('th:first :checkbox').length > 0);
					if (checkbox)
					{
						// There is a checkbox in the first column, don't make it sortable
						table.tablesorter( { headers: { 0: {sorter: false}}, widgets: ['myZebra', 'truncatedList']} ); // sortable and zebra tables
					}
					else
					{
						// There is NO checkbox in the first column, all columns are considered sortable
						table.tablesorter( { widgets: ['myZebra', 'truncatedList']} ); // sortable and zebra tables
					}
				});
		 //$('#'+divId).unblockUI();
		}
	 );
}

/**
 * Update the display and value of a file input widget when the user picks a new file
 */ 
function UpdateFileName(id, sNewFileName)
{
	var aPath = sNewFileName.split('\\');
	var sNewFileName = aPath[aPath.length-1];

	$('#'+id).val(sNewFileName);
	$('#'+id).trigger('validate');
	$('#name_'+id).text(sNewFileName);
	return true;
}
/**
 * Reload a search form for the specified class
 */
function ReloadSearchForm(divId, sClassName, sBaseClass)
{
    var oDiv = $('#'+divId);
	oDiv.block();
	var oFormEvents = $('#'+divId+' form').data('events');
	
	// Save the submit handlers
	aSubmit = new Array();
	if ( (oFormEvents != null) && (oFormEvents.submit != undefined))
	{
		aSubmit = oFormEvents.submit;
	}

	$.post('ajax.render.php',
	   { operation: 'search_form', className: sClassName, baseClass: sBaseClass, currentId: divId },
	   function(data) {
		   oDiv.empty();
		   oDiv.append(data);
		   if (aSubmit.length > 0)
		   {
			    var oForm = $('#'+divId+' form'); // Form was reloaded, recompute it
				for(index = 0; index < aSubmit.length; index++)
				{
					// Restore the previously bound submit handlers
					if (aSubmit[index].data != undefined)
					{
						oForm.bind('submit.'+aSubmit[index].namespace, aSubmit[index].data, aSubmit[index].handler)
					}
					else
					{
						oForm.bind('submit.'+aSubmit[index].namespace, aSubmit[index].handler)
					}
				}
		   }
		   oDiv.unblock();
		   oDiv.parent().resize(); // Inform the parent that the form has just been (potentially) resized
	   }
	 );
}

/**
 * Stores - in a persistent way - user specific preferences
 * depends on a global variable oUserPreferences created/filled by the iTopWebPage
 * that acts as a local -write through- cache
 */
function SetUserPreference(sPreferenceCode, sPrefValue, bPersistent)
{
	sPreviousValue = undefined;
	try
	{
		sPreviousValue = oUserPreferences[sPreferenceCode];
	}
	catch(err)
	{
		sPreviousValue = undefined;
	}
    oUserPreferences[sPreferenceCode] = sPrefValue;
    if (bPersistent && (sPrefValue != sPreviousValue))
    {
    	ajax_request = $.post('ajax.render.php',
    						  { operation: 'set_pref', code: sPreferenceCode, value: sPrefValue} ); // Make it persistent
    }
}

/**
 * Get user specific preferences
 * depends on a global variable oUserPreferences created/filled by the iTopWebPage
 * that acts as a local -write through- cache
 */
function GetUserPreference(sPreferenceCode, sDefaultValue)
{
	var value = sDefaultValue;
	if ( oUserPreferences[sPreferenceCode] != undefined)
	{
		value = oUserPreferences[sPreferenceCode];
	}
	return value;
}
