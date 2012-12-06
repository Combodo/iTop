function WizardAsyncAction(sActionCode, oParams, OnErrorFunction)
{
	var sStepClass = $('#_class').val();
	var sStepState = $('#_state').val();
	
	var oMap = { operation: 'async_action', step_class: sStepClass, step_state: sStepState, code: sActionCode, params: oParams };
	
	var ErrorFn = OnErrorFunction;
	$(document).ajaxError(function(event, request, settings) {
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
		$("#btn_next").removeAttr("disabled");
	}
	else
	{
		$("#btn_next").attr("disabled", "disabled");		
	}

	if (CanMoveBackward())
	{
		$("#btn_back").removeAttr("disabled");
	}
	else
	{
		$("#btn_back").attr("disabled", "disabled");		
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