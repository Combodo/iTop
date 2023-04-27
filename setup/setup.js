function WizardAsyncAction(sActionCode, oParams, OnErrorFunction)
{
	var sStepClass = $('#_class').val();
	var sStepState = $('#_state').val();
	var sAuthent = $('#authent_token').val();
	
	var oMap = { operation: 'async_action', step_class: sStepClass, step_state: sStepState, code: sActionCode, authent : sAuthent, params: oParams };
	
	var ErrorFn = OnErrorFunction;
	$(document).ajaxError(function(event, request, settings) {
		// update progressbar
		// not calling a dedicated plugin method as it is overdated and will be replaced soon
		$("#progress .progress").addClass('progress-error');
		$('#async_action').html('<pre>'+request.responseText+'</pre>').show();
		if (ErrorFn)
		{
			ErrorFn();
		}
	});
	
	$.post(GetAbsoluteUrlAppRoot()+'setup/ajax.dataloader.php', oMap, function(data) {
		$('#async_action').html(data);
	});
}

function WizardUpdateButtons()
{
	if (CanMoveForward())
	{
		$("#btn_next").prop('disabled', false);
	}
	else
	{
		$("#btn_next").prop('disabled', true);
	}

	if (CanMoveBackward())
	{
		$("#btn_back").prop('disabled', false);
	}
	else
	{
		$("#btn_back").prop('disabled', true);
	}
}

function ExecuteStep(sStep)
{
	var oParams = { installer_step: sStep, installer_config: $('#installer_parameters').val() };
	WizardAsyncAction('execute_step', oParams, function() {
		$('#wiz_form').data('installation_status', 'error');
		WizardUpdateButtons();
	} );
}

function CheckDirectoryConfFilesPermissions(sWikiVersion){
	$.ajax('permissions-test-folder/permissions-test-subfolder/permissions-test-file', {
		cache: false,
		statusCode: {
			200: function() {
				$('#details').prepend('<div class="message message-warning"><span class="message-title">Security issue:</span> iTop is bundled with directory-level configuration files. You must check that those files will be read by your web server (eg. ' +
					'AllowOverride directive should be set to <code>All</code> for Apache HTTP Server) <a href="https://www.itophub.io/wiki/page?id='+sWikiVersion+'%3Ainstall%3Asecurity#secure_critical_directories_access" target="_blank">see documentation</a>.</div>');
				$('<span class="text-warning"> and 1 Security issue</span>').insertBefore('h2.message button:first');
			}
		}
	});
}

CombodoTooltip.InitAllNonInstantiatedTooltips();