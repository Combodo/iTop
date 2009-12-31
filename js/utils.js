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
