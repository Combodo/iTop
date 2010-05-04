// Some general purpose JS functions for the iTop application
/**
 * Reload a truncated list
 */ 
function ReloadTruncatedList(divId, sSerializedFilter, sExtraParams)
{
	$('#'+divId).addClass('loading');
	//$('#'+divId).blockUI();
	$.get('ajax.render.php?filter='+sSerializedFilter+'&style=list',
	   { operation: 'ajax', extra_params: sExtraParams },
	   function(data){
		 $('#'+divId).empty();
		 $('#'+divId).append(data);
		 $('#'+divId).removeClass('loading');
		 $('#'+divId+' .listResults').tableHover(); // hover tables
		 $('#'+divId+' .listResults').tablesorter( { headers: { 0:{sorter: false }}, widgets: ['zebra']} ); // sortable and zebra tables
		 //$('#'+divId).unblockUI();
		}
	 );
}
/**
 * Reload any block -- used for periodic auto-reload
 */ 
function ReloadBlock(divId, sStyle, sSerializedFilter, sExtraParams)
{
	$('#'+divId).addClass('loading');
	//$('#'+divId).blockUI();
	$.get('ajax.render.php?filter='+sSerializedFilter+'&style='+sStyle,
	   { operation: 'ajax', extra_params: sExtraParams },
	   function(data){
		 $('#'+divId).empty();
		 $('#'+divId).append(data);
		 $('#'+divId).removeClass('loading');
		 $('#'+divId+' .listResults').tableHover(); // hover tables
		 $('#'+divId+' .listResults').tablesorter( { headers: { 0:{sorter: false }}, widgets: ['zebra']} ); // sortable and zebra tables
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
	$('#name_'+id).text(sNewFileName);
}
/**
 * Reload a search form for the specified class
 */
function ReloadSearchForm(divId, sClassName, sBaseClass)
{
	$('#'+divId).block();
	var formEvents = $('#'+divId+' form').data('events');
	var bSubmitHookIsUsed = false;
	if ( (formEvents != undefined) && (SubmitHook != undefined))
	{
		// Assume that we're using the function submit hook...
		bSubmitHookIsUsed = true;
	}
	$('#'+divId+' form').unbind('submit');
	$.get('ajax.render.php',
	   { operation: 'search_form', className: sClassName, baseClass: sBaseClass, currentId: divId },
	   function(data){
		   $('#'+divId).empty();
		   $('#'+divId).append(data);
		   if (bSubmitHookIsUsed)
		   {
			   $('#'+divId+' form').bind('submit', SubmitHook);
		   }
		   $('#'+divId).unblock();
	   }
	 );

}
