//
// Set of functions to manage the controls in a wizard
//
function UpdateObjectList(sClass, sId, sExtKeyToRemote)
{
	aRelatedObjectIds = GetObjectIds(sId, sExtKeyToRemote);
	if (aRelatedObjectIds.length == 0)
	{
		aRelatedObjectIds[0] = 0;
	}
	var oql = "SELECT "+sClass+" AS c WHERE c.id IN (" + aRelatedObjectIds.join(", ") + ")";
	$.post(GetAbsoluteUrlAppRoot()+"ajax.render.php?style=list&encoding=oql",
	   { operation: "ajax", filter: oql },
	   function(data){
		 $("#related_objects_"+sId).empty();
		 $("#related_objects_"+sId).append(data);
		 $("#related_objects_"+sId).removeClass("loading");
		});
}

function AddObject(sClass, sId, sExtKeyToRemote)
{
	var sCurrentObjectId = new String($('#ac_current_object_id_'+sId).val());
	// Display the additional dialog
	$('#LinkDlg_'+sId).dialog('open');
	return;
}

function ManageObjects(sTitle, sClass, sId, sExtKeyToRemote)
{
	aObjList = GetObjectIds(sId, sExtKeyToRemote);
	if (aObjList.length == 0)
	{
		aObjList[0] = 0;
	}
	Manage_LoadSelect('selected_objects_'+sId, 'SELECT '+sClass+' WHERE id IN (' + aObjList.join(', ') + ')'); // id is a reserved keyword always representing the primary key
	Manage_LoadSelect('available_objects_'+sId, 'SELECT '+sClass+' WHERE id NOT IN (' + aObjList.join(', ') + ')');
	$('#ManageObjectsDlg_'+sId).dialog('option', {title: sTitle});
	$('#ManageObjectsDlg_'+sId).dialog('open');
}

function Manage_LoadSelect(sSelectedId, sFilter)
{
 	$('#'+sSelectedId).addClass('loading');
	$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php',
	   { operation: 'combo_options', filter: sFilter },
	   function(data){
		 $('#'+sSelectedId).empty();
		 $('#'+sSelectedId).append(data);
		 $('#'+sSelectedId).removeClass('loading');
		}
	 );
}

function Manage_SwapSelectedObjects(oSourceSelect, oDestinationSelect, sId)
{
	j = oDestinationSelect.options.length;
	for (i=oSourceSelect.length-1;i>=0;i--) // Count down because we are removing the indexes from the combo
	{
		if (oSourceSelect.options[i].selected)
		{
			var newOption = document.createElement('option');
			newOption.text = oSourceSelect.options[i].text;
			newOption.value = oSourceSelect.options[i].value;
			oDestinationSelect.options[j++] = newOption;
			oSourceSelect.remove(i);
		}
	}
	Manage_UpdateButtons(sId);
}

function Manage_UpdateButtons(sId)
{
	var oSrc = document.getElementById('available_objects_'+sId);
	var oAddBtn = document.getElementById('btn_add_objects_'+sId)
	var oDst = document.getElementById('selected_objects_'+sId);
	var oRemoveBtn = document.getElementById('btn_remove_objects_'+sId)
	if (oSrc.selectedIndex == -1)
	{
		oAddBtn.disabled = true;
	}
	else
	{
		oAddBtn.disabled = false;
	}
	if (oDst.selectedIndex == -1)
	{
		oRemoveBtn.disabled = true;
	}
	else
	{
		oRemoveBtn.disabled = false;
	}
}

function Manage_AddObjects(sId)
{
	var oSrc = document.getElementById('available_objects_'+sId);
	var oDst = document.getElementById('selected_objects_'+sId);
	Manage_SwapSelectedObjects(oSrc, oDst, sId);
}

function Manage_RemoveObjects(sId)
{
	var oSrc = document.getElementById('selected_objects_'+sId);
	var oDst = document.getElementById('available_objects_'+sId);
	Manage_SwapSelectedObjects(oSrc, oDst, sId);
}

//function Manage_Ok(sClass, sExtKeyToRemote)
//{
//	var objectsToAdd = document.getElementById('selected_objects');
//	var aSelectedObjects = new Array();
//	for (i=0; i<objectsToAdd.length;i++)
//	{
//		aSelectedObjects[aSelectedObjects.length] = objectsToAdd.options[i].value;
//	}
//	$('#related_object_ids').val(aSelectedObjects.join(' '));
//	UpdateObjectList(sClass, sExtKeyToRemote);
//}

function FilterLeft(sClass)
{
	alert('Not Yet Implemented');
}

function FilterRight(sClass)
{
	alert('Not Yet Implemented');
}

function GetObjectIds(sInputId, sExtKeyToRemote)
{
	aLinkedIds = new Array;
	sLinkedSet = $('#'+sInputId).val();
	//console.log('(sInputId: '+sInputId+') => sLinkedSet: '+sLinkedSet);
	if (sLinkedSet != '')
	{
		aLinkedSet = JSON.parse(sLinkedSet);
		for(i=0; i<aLinkedSet.length; i++)
		{
			aLinkedIds[aLinkedIds.length] = aLinkedSet[i][sExtKeyToRemote];
		}
	}
	return aLinkedIds;
}

