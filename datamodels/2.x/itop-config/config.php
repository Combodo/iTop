<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\UI\Base\Component\Alert\Alert;
use Combodo\iTop\Application\UI\Base\Component\Alert\AlertUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Form\Form;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Title\TitleUIBlockFactory;
use Combodo\iTop\Application\WebPage\iTopWebPage;
use Combodo\iTop\Config\Validator\iTopConfigAstValidator;
use Combodo\iTop\Config\Validator\iTopConfigSyntaxValidator;

require_once(APPROOT.'application/startup.inc.php');

const CONFIG_ERROR = 0;
const CONFIG_WARNING = 1;
const CONFIG_INFO = 2;


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

function CheckAsyncTasksRetryConfig(Config $oTempConfig, iTopWebPage $oP)
{
	$iWarnings = 0;
	foreach (get_declared_classes() as $sPHPClass) {
		$oRefClass = new ReflectionClass($sPHPClass);
		if ($oRefClass->isSubclassOf('AsyncTask') && !$oRefClass->isAbstract()) {
			$aMessages = AsyncTask::CheckRetryConfig($oTempConfig, $oRefClass->getName());

			if (count($aMessages) !== 0) {
				foreach ($aMessages as $sMessage) {
					$oAlert = AlertUIBlockFactory::MakeForWarning('', $sMessage);
					$oP->AddUiBlock($oAlert);
					$iWarnings++;
				}
			}
		}
	}

	return $iWarnings;
}

/**
 * @param \Exception $e
 *
 * @return \Combodo\iTop\Application\UI\Base\Component\Alert\Alert
 */
function GetAlertFromException(Exception $e): Alert
{
	switch ($e->getCode()) {
		case CONFIG_WARNING:
			$oAlert = AlertUIBlockFactory::MakeForWarning('', $e->getMessage());
			break;
		case CONFIG_INFO:
			$oAlert = AlertUIBlockFactory::MakeForInformation('', $e->getMessage());
			break;
		case CONFIG_ERROR:
		default:
			$oAlert = AlertUIBlockFactory::MakeForDanger('', $e->getMessage());
	}

	return $oAlert;
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
$sAceDir = 'node_modules/ace-builds/src-min/';
$oP->LinkScriptFromAppRoot($sAceDir.'ace.js');
$oP->LinkScriptFromAppRoot($sAceDir.'mode-php.js');
$oP->LinkScriptFromAppRoot($sAceDir.'theme-eclipse.js');
$oP->LinkScriptFromAppRoot($sAceDir.'ext-searchbox.js');

try {
	$sOperation = utils::ReadParam('operation', '');
	$iEditorTopMargin = 2;
	if (UserRights::IsAdministrator() && ExecutionKPI::IsEnabled()) {
		$iEditorTopMargin += 6;
	}
	$oP->AddUiBlock(TitleUIBlockFactory::MakeForPage(Dict::S('config-edit-title')));

	if (MetaModel::GetConfig()->Get('demo_mode')) {
		throw new Exception(Dict::S('config-not-allowed-in-demo'), CONFIG_INFO);
	}

	if (MetaModel::GetModuleSetting('itop-config', 'config_editor', '') == 'disabled') {
		throw new Exception(Dict::S('config-interactive-not-allowed'), CONFIG_WARNING);
	}

	$sConfigFile = APPROOT.'conf/'.utils::GetCurrentEnvironment().'/config-itop.php';

	$iEditorTopMargin += 9;
	$sConfigContent = file_get_contents($sConfigFile);
	$sConfigChecksum = md5($sConfigContent);
	$sConfig = str_replace("\r\n", "\n", $sConfigContent);
	$sOriginalConfig = $sConfig;

	if (!empty($sOperation)) {
		$iEditorTopMargin += 5;
		$sConfig = utils::ReadParam('new_config', '', false, 'raw_data');
	}

	try {
		if ($sOperation == 'revert') {
			throw new Exception(Dict::S('config-reverted'), CONFIG_WARNING);
		}

		if ($sOperation == 'save') {
			$sTransactionId = utils::ReadParam('transaction_id', '', false, 'transaction_id');
			if (!utils::IsTransactionValid($sTransactionId, true)) {
				throw new Exception(Dict::S('config-error-transaction'), CONFIG_ERROR);
			}

			$sChecksum = utils::ReadParam('checksum');
			if ($sChecksum !== $sConfigChecksum) {
				throw new Exception(Dict::S('config-error-file-changed'), CONFIG_ERROR);
			}

			if ($sConfig === $sOriginalConfig) {
				throw new Exception(Dict::S('config-no-change'), CONFIG_INFO);
			}
			TestConfig($sConfig, $oP); // throws exceptions

			@chmod($sConfigFile, 0770); // Allow overwriting the file
			$sTmpFile = tempnam(SetupUtils::GetTmpDir(), 'itop-cfg-');
			// Don't write the file as-is since it would allow to inject any kind of PHP code.
			// Instead, write the interpreted version of the file
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
			} else {
				$oAlert = AlertUIBlockFactory::MakeForInformation('', Dict::S('config-saved-warning-db-password'));
			}
			$oP->AddUiBlock($oAlert);

			$iWarnings = CheckAsyncTasksRetryConfig($oTempConfig, $oP);

			// Read the config from disk after save
			$sConfigContent = file_get_contents($sConfigFile);
			$sConfigChecksum = md5($sConfigContent);
			$sConfig = str_replace("\r\n", "\n", $sConfigContent);
			$sOriginalConfig = $sConfig;
		}
	}
	catch (Exception $e) {
		$oAlert = GetAlertFromException($e);
		$oP->AddUiBlock($oAlert);
	}

	// (remove EscapeHtml)  N°5914 - Wrong encoding in modules configuration editor
	$oP->AddUiBlock(new Html('<p>'.Dict::S('config-edit-intro').'</p>'));

	$oForm = new Form();
	$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('operation', 'save', 'operation'));
	$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('transaction_id', utils::GetNewTransactionId()));
	$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('checksum', $sConfigChecksum));

	//--- Cancel button
	$oCancelButton = ButtonUIBlockFactory::MakeForCancel(Dict::S('config-cancel'), 'cancel_button', null, true, 'cancel_button');
	$oCancelButton->SetOnClickJsCode("return ResetConfig();");
	$oForm->AddSubBlock($oCancelButton);

	//--- Submit button
	$oSubmitButton = ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('config-apply'), null, Dict::S('config-apply'), true, 'submit_button');
	$oForm->AddSubBlock($oSubmitButton);

	//--- Config editor
	$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('prev_config', $sOriginalConfig, 'prev_config'));
	$oForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('new_config', $sOriginalConfig));
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
	$oP->add_ready_script(<<<'JS'
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
	$("#operation").attr('value', 'revert');
	if (confirm('$sConfirmCancel'))
	{
		$('input[name="new_config"]').val(prevConfig.val());
		return true;
	}
	return false;
}
JS
	);
} catch (Exception $e) {
	$oAlert = GetAlertFromException($e);
	$oP->AddUiBlock($oAlert);
}

$oP->output();
