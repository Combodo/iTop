<?php
/*
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Form\Form;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Title\TitleUIBlockFactory;
use Combodo\iTop\Config\Validator\iTopConfigAstValidator;
use Combodo\iTop\Config\Validator\iTopConfigSyntaxValidator;

require_once(APPROOT.'application/application.inc.php');
require_once(APPROOT.'application/itopwebpage.class.inc.php');
require_once(APPROOT.'application/startup.inc.php');
require_once(APPROOT.'application/loginwebpage.class.inc.php');


/**
 * @param $sContents
 * @param $oP
 *
 * @throws \Exception
 */
function TestConfig($sContents, $oP)
{
	/// 1- first check if there is no malicious code
	$oiTopConfigValidator = new iTopConfigAstValidator();
	$oiTopConfigValidator->Validate($sContents);

	/// 2 - only after we are sure that there is no malicious cade, we can perform a syntax check!
	$oiTopConfigValidator = new iTopConfigSyntaxValidator();
	$oiTopConfigValidator->Validate($sContents);
}

/**
 * @param $sSafeContent
 *
 * @return bool
 */
function DBPasswordInNewConfigIsOk($sSafeContent)
{
	$bIsWindows = (array_key_exists('WINDIR', $_SERVER) || array_key_exists('windir', $_SERVER));

	if ($bIsWindows && (preg_match("@'db_pwd' => '[^%!\"]+',@U", $sSafeContent) === 0)) {
		return false;
	}
	return true;
}

/////////////////////////////////////////////////////////////////////
// Main program
//
LoginWebPage::DoLogin(); // Check user rights and prompt if needed
ApplicationMenu::CheckMenuIdEnabled('ConfigEditor');

//$sOperation = utils::ReadParam('operation', 'menu');
//$oAppContext = new ApplicationContext();

$oP = new iTopWebPage(Dict::S('config-edit-title'));
$oP->set_base(utils::GetAbsoluteUrlAppRoot().'pages/');
$oP->add_linked_script(utils::GetAbsoluteUrlAppRoot().'/js/ace/ace.js');
$oP->add_linked_script(utils::GetAbsoluteUrlAppRoot().'/js/ace/mode-php.js');
$oP->add_linked_script(utils::GetAbsoluteUrlAppRoot().'/js/ace/theme-eclipse.js');
$oP->add_linked_script(utils::GetAbsoluteUrlAppRoot().'/js/ace/ext-searchbox.js');

try {
	$sOperation = utils::ReadParam('operation', '');
	$iEditorTopMargin = 2;
	if (UserRights::IsAdministrator() && ExecutionKPI::IsEnabled()) {
		$iEditorTopMargin += 6;
	}
	$oP->AddUiBlock(TitleUIBlockFactory::MakeForPage(Dict::S('config-edit-title')));

	if (MetaModel::GetConfig()->Get('demo_mode')) {
		$oAlert = AlertUIBlockFactory::MakeForInformation('', "Sorry, iTop is in <b>demonstration mode</b>: the configuration file cannot be edited.");
		$oP->AddUiBlock($oAlert);
	} else {
		if (MetaModel::GetModuleSetting('itop-config', 'config_editor', '') == 'disabled') {
			$oAlert = AlertUIBlockFactory::MakeForWarning('', "iTop interactive edition of the configuration as been disabled. See <tt>'config_editor' => 'disabled'</tt> in the configuration file.");
			$oP->AddUiBlock($oAlert);
		} else {
			$sConfigFile = APPROOT.'conf/'.utils::GetCurrentEnvironment().'/config-itop.php';

			$iEditorTopMargin += 9;
			$sConfig = str_replace("\r\n", "\n", file_get_contents($sConfigFile));
			$sOriginalConfig = $sConfig;

			if (!empty($sOperation)) {
				$iEditorTopMargin += 5;
				$sConfig = utils::ReadParam('new_config', '', false, 'raw_data');
				$sOriginalConfig = utils::ReadParam('prev_config', '', false, 'raw_data');
			}

			if ($sOperation == 'revert') {
				$iEditorTopMargin += 5;
				$oAlert = AlertUIBlockFactory::MakeForWarning('', Dict::S('config-reverted'));
				$oP->AddUiBlock($oAlert);
			}
			if ($sOperation == 'save') {
				$sTransactionId = utils::ReadParam('transaction_id', '', false, 'transaction_id');
				if (!utils::IsTransactionValid($sTransactionId, true)) {
					$iEditorTopMargin += 5;
					$oAlert = AlertUIBlockFactory::MakeForFailure('', 'Error: invalid Transaction ID. The configuration was <b>NOT</b> modified.');
					$oP->AddUiBlock($oAlert);
				} else {
					if ($sConfig == $sOriginalConfig) {
						$iEditorTopMargin += 5;
						$oAlert = AlertUIBlockFactory::MakeForInformation('', Dict::S('config-no-change'));
						$oP->AddUiBlock($oAlert);
					} else {
						try {
							TestConfig($sConfig, $oP); // throws exceptions

							@chmod($sConfigFile, 0770); // Allow overwriting the file
							$sTmpFile = tempnam(SetupUtils::GetTmpDir(), 'itop-cfg-');
							// Don't write the file as-is since it would allow to inject any kind of PHP code.
							// Instead write the interpreted version of the file
							// Note:
							// The actual raw PHP code will anyhow be interpreted exactly twice: once in TestConfig() above
							// and a second time during the load of the Config object below.
							// If you are really concerned about an iTop administrator crafting some malicious
							// PHP code inside the config file, then turn off the interactive configuration
							// editor by adding the configuration parameter:
							// 'itop-config' => array(
							//     'config_editor' => 'disabled',
							// )
							file_put_contents($sTmpFile, $sConfig);
							$oTempConfig = new Config($sTmpFile, true);
							$oTempConfig->WriteToFile($sConfigFile);
							@unlink($sTmpFile);
							@chmod($sConfigFile, 0440); // Read-only

							if (DBPasswordInNewConfigIsOk($sConfig)) {
								$oAlert = AlertUIBlockFactory::MakeForSuccess('', Dict::S('config-saved'));
								$iEditorTopMargin += 5;
							} else {
								$oAlert = AlertUIBlockFactory::MakeForInformation('', Dict::S('config-saved-warning-db-password'));
								$iEditorTopMargin += 5;
							}
							$oP->AddUiBlock($oAlert);
							$sOriginalConfig = str_replace("\r\n", "\n", file_get_contents($sConfigFile));
						} catch (Exception $e) {
							$oAlert = AlertUIBlockFactory::MakeForDanger('', $e->getMessage());
							$iEditorTopMargin += 5;
							$oP->AddUiBlock($oAlert);
						}
					}
				}
			}


			$sConfigEscaped = htmlentities($sConfig, ENT_QUOTES, 'UTF-8');
			$sOriginalConfigEscaped = htmlentities($sOriginalConfig, ENT_QUOTES, 'UTF-8');
			$oP->AddUiBlock(new Html('<p>'.Dict::S('config-edit-intro').'</p>'));

			$oForm = new Form();
			$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('operation', 'save'));
			$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('transaction_id', utils::GetNewTransactionId()));

			// - Cancel button
			$oCancelButton = ButtonUIBlockFactory::MakeForCancel(Dict::S('config-cancel'), 'cancel_button', null, true, 'cancel_button');
			$oCancelButton->SetOnClickJsCode("return ResetConfig();");
			$oForm->AddSubBlock($oCancelButton);

			// - Submit button
			$oSubmitButton = ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('config-apply'), null, Dict::S('config-apply'), true, 'submit_button');
			$oForm->AddSubBlock($oSubmitButton);
			$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('prev_config', $sOriginalConfigEscaped, 'prev_config'));
			$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('new_config', $sConfigEscaped));
			$oForm->AddHtml("<div id =\"new_config\" style=\"position: absolute; top: ".$iEditorTopMargin."em; bottom: 0; left: 5px; right: 5px;\"></div>");
			$oP->AddUiBlock($oForm);

			$oP->add_script(
				<<<'JS'
var EditorUtils = (function() {
	var STORAGE_RANGE_KEY = 'cfgEditorRange';
	var STORAGE_LINE_KEY = 'cfgEditorFirstline';
	var _editorSavedRange = null;
	var _editorSavedFirstLine = null;
	
	var saveEditorDisplay = function(editor) {
		_initObjectValues(editor);
		_persistObjectValues();
	};
	
	var _initObjectValues = function(editor) {
		_editorSavedRange = editor.getSelectionRange();
		_editorSavedFirstLine = editor.renderer.getFirstVisibleRow();
	};
	
	var _persistObjectValues = function() {
		sessionStorage.setItem(EditorUtils.STORAGE_RANGE_KEY, JSON.stringify(_editorSavedRange));
		sessionStorage.setItem(EditorUtils.STORAGE_LINE_KEY, _editorSavedFirstLine);
	};
	
	var restoreEditorDisplay = function(editor) {
		_restoreObjectValues();
		_setEditorDisplay(editor);
	};
	
	var _restoreObjectValues = function() {
		if ((sessionStorage.getItem(STORAGE_RANGE_KEY) == null) 
			|| (sessionStorage.getItem(STORAGE_LINE_KEY) == null)) {
			return;
		}
		
		_editorSavedRange = JSON.parse(sessionStorage.getItem(EditorUtils.STORAGE_RANGE_KEY));
		_editorSavedFirstLine = sessionStorage.getItem(EditorUtils.STORAGE_LINE_KEY);
		sessionStorage.removeItem(STORAGE_RANGE_KEY);
		sessionStorage.removeItem(STORAGE_LINE_KEY);
	};
	
	var _setEditorDisplay = function(editor) {
		if ((_editorSavedRange == null) || (_editorSavedFirstLine == null)) {
			return;
		}

		editor.selection.setRange(_editorSavedRange);
		editor.renderer.scrollToRow(_editorSavedFirstLine);
	};
	
	var getEditorForm = function(editor) {
        var editorContainer = $(editor.container);
        return editorContainer.closest("form");
	};
	
	var updateConfigEditorButtonState = function(editor) {
	    var isSameContent = (editor.getValue() == $('#prev_config').val());
	    var hasNoError = $.isEmptyObject(editor.getSession().getAnnotations());
	    $('#cancel_button').prop('disabled', isSameContent);
	    $('#submit_button').prop('disabled', isSameContent || !hasNoError);
	};
	
	return {
		STORAGE_RANGE_KEY: STORAGE_RANGE_KEY,
		STORAGE_LINE_KEY : STORAGE_LINE_KEY,
		saveEditorDisplay : saveEditorDisplay,
		restoreEditorDisplay : restoreEditorDisplay,
		getEditorForm : getEditorForm,
		updateConfigEditorButtonState : updateConfigEditorButtonState
	};
})();
JS
		);
		$oP->add_ready_script(
			<<<'JS'
var editor = ace.edit("new_config");

var configurationSource = $('input[name="new_config"]');
editor.getSession().setValue(configurationSource.val());

editor.getSession().on('change', function()
{
  configurationSource.val(editor.getSession().getValue());
  EditorUtils.updateConfigEditorButtonState(editor);
});
editor.getSession().on("changeAnnotation", function()
{
  EditorUtils.updateConfigEditorButtonState(editor);
});

editor.setTheme("ace/theme/eclipse");
editor.getSession().setMode("ace/mode/php");
editor.commands.addCommand({
    name: 'save',
    bindKey: {win: "Ctrl-S", "mac": "Cmd-S"},
    exec: function(editor) {
        var editorForm = EditorUtils.getEditorForm(editor);
        var submitButton = $('#submit_button');
        
        if (submitButton.is(":enabled")) {
            editorForm.submit();
        }
    }
});


var editorForm = EditorUtils.getEditorForm(editor);
editorForm.on('submit', function() {
	EditorUtils.saveEditorDisplay(editor);
});


EditorUtils.restoreEditorDisplay(editor);
editor.focus();
JS
		);

			$sConfirmCancel = addslashes(Dict::S('config-confirm-cancel'));
			$oP->add_script(<<<JS
function ResetConfig()
{
    var editor = ace.edit("new_config");
	$("#operation").attr('value', 'revert');
	var prevConfig = $('#prev_config');
	if (editor.getValue() != prevConfig.val())
	{
		if (confirm('$sConfirmCancel'))
		{
			$('input[name="new_config"]').val(prevConfig.val());
			return true;
		}
	}
	return false;
}
JS
			);
		}
	}
} catch (Exception $e) {
	$oP->p('<b>'.$e->getMessage().'</b>');
}

$oP->output();
