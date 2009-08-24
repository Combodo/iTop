function NameIsValid(name)
{
	sName = new String(name);
	if (sName.match(/^[A-Za-z][A-Za-z0-9_]*$/))	return true;
	return false;
}
function DoSubmit(sMsg, iStep)
{
	var bResult = true;
	switch(iStep)
	{
		case 1:
		if ($('#db_server').val() == '')
		{
			alert('Please specify a database server. Use "localhost" for a local DB server.');
			bResult = false;
		}
		else if ($('#db_user').val() == '')
		{
			alert('Please specify a user name to connect to the database.');
			bResult = false;
		}
		break;
		
		case 2:
		if ($("input[@type=radio]:checked").length < 1)
		{
			alert('Please specify a database name');
			bResult = false;
		}
		else if( ($("#new_db:checked").length == 1))
		{
			if ($('#new_db_name').val() == '')
			{
				alert('Please specify the name of the database to create');
				bResult = false;
			}
			else if (!NameIsValid($('#new_db_name').val()))
			{
				alert($('#new_db_name').val()+' is not a valid database name. Please limit yourself to letters, numbers and the underscore character.');
				bResult = false;
			}
		}
		else if ($("#current_db:checked").length == 1)
		{
			// Special case (DB enumeration failed, user must enter DB name)
			if ($("#current_db_name").val() == '')
			{
				alert('Please specify the name of the database.');
				bResult = false;
			}
			else
			{
				// Copy the typed value as the value of the radio
				$("#current_db").val($("#current_db_name").val());
			}
		}
		if( ($('#db_prefix').val() != '') && (!NameIsValid($('#db_prefix').val())) )
		{
				alert($('#db_prefix').val()+' is not a valid table name. Please limit yourself to letters, numbers and the underscore character.');
				bResult = false;			
		}
		break;
		
		case 3:
		if ($('#auth_user').val() == '')
		{
			alert('Please specify a login name for the administrator account');
			bResult = false;
		}
		else if ($('#auth_pwd').val() != $('#auth_pwd2').val())
		{
			alert('Retyped password does not match! Please verify the password.');
			bResult = false;
		}
		break;
		
		case 4:
		bResult = DoLoadDataAsynchronous();
	}
	if (bResult)
	{
		$('#setup').block({message: '<img src="../images/indicator.gif">&nbsp;'+sMsg});
	}
	return bResult;
}

var aFilesToLoad = new Array();

function DoLoadDataAsynchronous()
{
	// The array aFilesToLoad is populated by this function dynamically written on the server
	PopulateDataFilesList();

	$('#setup').block({message: '<p>Loading data...<br/><div id=\"progress\">0%</div></p>'});
	$('#progress').progression( {Current:0, Maximum: 100, aBackgroundImg: 'orange-progress.gif', aTextColor: '#000000'} );
	LoadNextDataFile('', '');
	return false; // Stop here for now
}

var iCounter = 0;

function LoadNextDataFile(sData, sTextStatus)
{
	//$("#progress").html(sData);
	if (iCounter < aFilesToLoad.length)
	{
		if (iCounter == (aFilesToLoad.length - 1))
		{
			// Last file in the list (or only 1 file), this completes the session
			sSessionStatus = 'end';
		}
		else if (iCounter == 0)
		{
			// First file in the list, start the session
			sSessionStatus = 'start';
		}
		else
		{
			sSessionStatus = 'continue';
		}
		iPercent = Math.round((100.0 * (1+iCounter)) / aFilesToLoad.length);
		sFileName = aFilesToLoad[iCounter];
		//alert('Loading file '+sFileName+' ('+iPercent+' %) - '+sSessionStatus);
		$("#progress").progression({ Current: iPercent });
		iCounter++;
		$.get( 'ajax.dataloader.php', { 'file': sFileName, 'percent': iPercent, 'session_status': sSessionStatus }, LoadNextDataFile, 'html');
	}
	else
	{
		// We're done
		$('#setup').unblock();
		$('#GoToNextStep').submit(); // Use the hidden form to navigate to the next step
	}
}
