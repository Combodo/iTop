function NameIsValid(name)
{
	sName = new String(name);
	if (sName.match(/^[A-Za-z][A-Za-z0-9_]*$/))	return true;
	return false;
}

function DoGoBack(iStep)
{
	$('input[name=operation]').val('step'+iStep);
	$(':button').attr('disabled', 'disabled');
	$('#theForm').submit(); // Submit the form
	return true; 
}

function DoSubmit(sMsg, iStep)
{
	var bResult = true;
	switch(iStep)
	{
		case 0: // Select either install or upgrade or nothing to select...
		if ( ($("input:radio").length > 0) && ($("input:radio:checked").length < 1))
		{
			alert('Please select either install or upgrade');
			bResult = false;
		}
		break;

		case 1: // Licence agreement
		if ($('#licence_ok:checked').length < 1)
		{
			alert('Please accept the licence agreement before continuing.');
			bResult = false;
		}
		break;
		
		case 2: // Database server selection
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
		
		case 3: // Database instance selection
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
		
		case 4: // Choice of iTop modules
		break;
		
		case 5: // Administrator account
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
		
		case 6: // application path
		var appPath = new String($('#application_path').val());
		if (appPath == '')
		{
			alert('Please specify the URL to the application');
			bResult = false;
		}
		else
		{
			var bMatch = appPath.match(/^http(?:s)?\:\/\//);
			if (!bMatch)
			{
				alert('"'+appPath+'" does not look like a valid URL for the application...\nPlease check your input.');
				bResult = false;
			}
			else
			{
				// Make sure that the root URL ends with a slash
				var bMatch = appPath.match(/\/$/);
				if (!bMatch)
				{
					// If not, add a slash at the end
					appPath += '/';
					$('#application_path').val(appPath);
				}
			}
		}

		break;
			
		case 7: // Sample data selection
			break;
			
		case 8: // Display Summary: launch DoUpdateDBSchema to start the asynchronous update
		bResult = DoUpdateDBSchema();
		break;

		// Email test page
		case 10:
		if ($('#to').val() == '')
		{
			alert('Please specify a destination address');
			bResult = false;
		}
	}
	if (bResult)
	{
		$(':button').attr('disabled', 'disabled');
		if ((sMsg != ''))
		{
			$('#setup').block({message: '<img src="../images/indicator.gif">&nbsp;'+sMsg});
		}
	}
	return bResult;
}

function DoUpdateDBSchema()
{
	try
	{
		// Call the asynchronous page that performs the creation/update of the DB Schema
		$('#log').html('');
		$('#setup').block({message: '<p><span id="setup_msg">Updating DB schema...</span><br/><div id=\"progress\">0%</div></p>'});
		$('#progress').progression( {Current:5, Maximum: 100, aBackgroundImg: 'orange-progress.gif', aTextColor: '#000000'} );
		$('#log').load( 'ajax.dataloader.php',
						{ 
							'operation': 'update_db_schema',
							'selected_modules': GetSelectedModules(),
							'mode': $(':input[name=mode]').val()
						},
						DoUpdateProfiles, 'html');
	}
	catch(err)
	{
		alert('An exception occured: '+err);
	}
	return false; // Do NOT submit the form yet
}

function DoUpdateProfiles(response, status, xhr)
{
	if (status == 'error')
	{
		$('#setup').unblock();
		return; // An error occurred !
	}
	try
	{
		// Call the asynchronous page that performs the creation/update of the DB Schema
		$('#log').html('');
		$('#setup_msg').text('Updating Profiles...');
		$('#progress').progression( {Current:40,  Maximum: 100, aBackgroundImg: 'orange-progress.gif', aTextColor: '#000000'} );
		$('#log').load( 'ajax.dataloader.php',
				{ 
					'operation': 'after_db_create',
					'selected_modules': GetSelectedModules(),
					'mode': $(':input[name=mode]').val(),
					'auth_user': $(':input[name=auth_user]').val(),
					'auth_pwd': $(':input[name=auth_pwd]').val(),
					'language': $(':input[name=language]').val()
				},
				DoLoadDataAsynchronous, 'html');
//		$('#log').ajaxError(
//				function(e, xhr, settings, exception)
//				{
//					bStopAysncProcess = true;
//					alert('Fatal error detected: '+ xhr.responseText);
//					$('#log').append(xhr.responseText);
//					$('#setup').unblock();
//				} );
	}
	catch(err)
	{
		alert('An exception occured: '+err);
	}
	return true; // Continue loading the data
}

var aFilesToLoad = new Array();
var iCounter = 0;

function DoLoadDataAsynchronous(response, status, xhr)
{
	if (status == 'error')
	{
		$('#setup').unblock();
		return; // An error occurred !
	}
	try
	{
		// The array aFilesToLoad is populated by this function dynamically written on the server
		PopulateDataFilesList();
		iCurrent = 60;
		if (aFilesToLoad.length == 0)
		{
			$('#progress').progression( {Current: 100} );
		}
		else
		{
			$('#log').html('');
			$('#setup_msg').text('Loading data...');
			$('#progress').progression( {Current: 60, Maximum: 100, aBackgroundImg: 'orange-progress.gif', aTextColor: '#000000'} );
//			$('#log').ajaxError(
//					function(e, xhr, settings, exception)
//					{
//						bStopAysncProcess = true;
//						alert('Fatal error detected: '+ xhr.responseText);
//						$('#log').append(xhr.responseText);
//						$('#setup').unblock();
//					} );
		}
		LoadNextDataFile('', '', '');
	}
	catch(err)
	{
		alert('An exception occured: '+err);
	}
	return true; // Continue
}

function LoadNextDataFile(response, status, xhr)
{
	if (status == 'error')
	{
		$('#setup').unblock();
		return; // Stop here
	}
	
	try
	{
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
			iPercent = 60+Math.round((40.0 * (1+iCounter)) / aFilesToLoad.length);
			sFileName = aFilesToLoad[iCounter];
			//alert('Loading file '+sFileName+' ('+iPercent+' %) - '+sSessionStatus);
			$("#progress").progression({ Current: iPercent, Maximum: 100, aBackgroundImg: 'orange-progress.gif', aTextColor: '#000000' });
			iCounter++;
			$('#log').load( 'ajax.dataloader.php', { 'operation': 'load_data', 'file': sFileName, 'percent': iPercent, 'session_status': sSessionStatus }, LoadNextDataFile, 'html');
		}
		else
		{
			// We're done
			$("#progress").progression({ Current: 100, Maximum: 100, aBackgroundImg: 'orange-progress.gif', aTextColor: '#000000' });
			//$('#setup').unblock();
			$('#GoToNextStep').submit(); // Use the hidden form to navigate to the next step
		}
	}
	catch(err)
	{
		alert('An exception occurred: '+err);
	}
}

function GetSelectedModules()
{
	var aModules = new Array();
	$(':input[name^=module]').each(function() { aModules.push($(this).val()); } );
	return aModules.join(',');
}