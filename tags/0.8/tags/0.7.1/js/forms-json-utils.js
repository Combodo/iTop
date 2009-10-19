// ID of the (hidden) form field used to store the JSON representation of the
// object being edited in this page
var sJsonFieldId = 'json_object';

// The memory representation of the object
var oObj = {};

// Mapping between the fields of the form and the attribute of the current object
// If aFieldsMap[2] contains 'foo' it means that oObj.foo corresponds to the field
// of Id 'att_2' in the form 
var aFieldsMap = new Array;

// Update the whole object from the form and also update its
// JSON (serialized) representation in the (hidden) field
function UpdateObjectFromForm(aFieldsMap, oObj)
{
	for(i=0; i<aFieldsMap.length; i++)
	{
		var oElement = document.getElementById('att_'+i);
		var sFieldName = aFieldsMap[i];
		oObj['m_aCurrValues'][sFieldName] = oElement.value;
		sJSON = JSON.stringify(oObj);
		var oJSON = document.getElementById(sJsonFieldId);
		oJSON.value = sJSON;
	}
	return oObj;
}

// Update the specified field from the current object
function UpdateFieldFromObject(idField, aFieldsMap, oObj)
{
	var oElement = document.getElementById('att_'+idField);
	oElement.value = oObj['m_aCurrValues'][aFieldsMap[idField]];
}
// Update all the fields of the Form from the current object
function UpdateFormFromObject(aFieldsMap, oObj)
{
	for(i=0; i<aFieldsMap.length; i++)
	{
		UpdateFieldFromForm(i, aFieldsMap, oObj);
	}
}

// This function is meant to be called from the AJAX page
// It reloads the object (oObj) from the JSON representation
// and also updates the form field that contains the JSON
// representation of the object
function ReloadObjectFromServer(sJSON)
{
	//console.log('JSON value:', sJSON);
	var oJSON = document.getElementById(sJsonFieldId);
	oJSON.value = sJSON;
	oObj = JSON.parse( '(' + sJSON + ')' );
	return oObj;	
}

function GoToStep(iCurrentStep, iNextStep)
{
	var oCurrentStep = document.getElementById('wizStep'+iCurrentStep);
	oCurrentStep.style.display = 'none';
	ActivateStep(iNextStep);
}

function ActivateStep(iTargetStep)
{
	UpdateObjectFromForm(aFieldsMap, oObj);
	var oNextStep = document.getElementById('wizStep'+(iTargetStep));
	window.location.href='#step'+iTargetStep;
	// If a handler for entering this step exists, call it
	if (typeof(this['OnEnterStep'+iTargetStep]) == 'function')
	{
		eval( 'OnEnterStep'+iTargetStep+'();');
	}
	oNextStep.style.display = '';
	G_iCurrentStep = iTargetStep;
	$('#wizStep'+(iTargetStep)).block({ message: null });
}


function AjaxGetValuesDef(oObj, sClass, sAttCode, iFieldId)
{
	var oJSON = document.getElementById(sJsonFieldId);
	$.get('ajax.render.php?class=' + sClass + '&json_obj=' + oJSON.value + '&att_code=' + sAttCode,
	   { operation: "allowed_values" },
	   function(data){
		 //$('#field_'+iFieldId).html(data);
		}
	);
}

function AjaxGetDefaultValue(oObj, sClass, sAttCode, iFieldId)
{
	// Asynchronously call the server to provide a default value if the field is
	// empty
	if (oObj['m_aCurrValues'][sAttCode] == '')
	{
		var oJSON = document.getElementById(sJsonFieldId);
		$.get('ajax.render.php?class=' + sClass + '&json_obj=' + oJSON.value + '&att_code=' + sAttCode,
		   { operation: "default_value" },
		   function(json_data){
			 var oObj = ReloadObjectFromServer(json_data);
			 UpdateFieldFromObject(iFieldId, aFieldsMap, oObj)
			}
		);
	}
}
