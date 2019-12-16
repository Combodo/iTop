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