<?php
// Copyright (C) 2014-2016 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>


/**
 * Monitor the backup
 *
 * @copyright   Copyright (C) 2013 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

require_once(APPROOT.'application/application.inc.php');
require_once(APPROOT.'application/itopwebpage.class.inc.php');
require_once(APPROOT.'application/startup.inc.php');
require_once(APPROOT.'application/loginwebpage.class.inc.php');


function TestConfig($sContents, $oP)
{
	try
	{
		ini_set('display_errors', 1);
		ob_start();
        // in PHP < 7.0.0 syntax errors are in output
        // in PHP >= 7.0.0 syntax errors are thrown as Error
        $sSafeContent = preg_replace(array('#^\s*<\?php#', '#\?>\s*$#'), '', $sContents);
        eval('if(0){'.trim($sSafeContent).'}');
        $sNoise = trim(ob_get_contents());
		ob_end_clean();
    }
    catch (Error $e)
    {
        // ParseError only thrown in PHP7
		throw new Exception('Error in configuration: '.$e->getMessage().' at line '.$e->getLine());
	}
	if (strlen($sNoise) > 0)
	{
		if (preg_match("/(Error|Parse error|Notice|Warning): (.+) in \S+ : eval\(\)'d code on line (\d+)/i", strip_tags($sNoise), $aMatches))
		{
			$sMessage = $aMatches[2];
			$sLine = $aMatches[3];
			$iLine = (int) $sLine;

			// Highlight the line
			$aLines = explode("\n", $sContents);
			$iStart = 0;
			for ($i = 0 ; $i < $iLine - 1; $i++) $iStart += strlen($aLines[$i]);
			$iEnd = $iStart + strlen($aLines[$iLine - 1]);
			$iTotalLines = count($aLines);

			$sMessage = Dict::Format('config-parse-error', $sMessage, $sLine);
			throw new Exception($sMessage);
		}
		else
		{
			// Note: sNoise is an html output, but so far it was ok for me (e.g. showing the entire call stack) 
			throw new Exception('Syntax error in configuration file: <tt>'.$sNoise.'</tt>');
		}
	}
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
$oP->add_linked_script(utils::GetCurrentModuleUrl().'/js/ace.js');
$oP->add_linked_script(utils::GetCurrentModuleUrl().'/js/mode-php.js');
$oP->add_linked_script(utils::GetCurrentModuleUrl().'/js/theme-eclipse.js');
$oP->add_linked_script(utils::GetCurrentModuleUrl().'/js/ext-searchbox.js');

try
{
	$sOperation = utils::ReadParam('operation', '');

	$oP->add("<h1>".Dict::S('config-edit-title')."</h1>");

	if (MetaModel::GetConfig()->Get('demo_mode'))
	{
		$oP->add("<div class=\"header_message message_info\">Sorry, iTop is in <b>demonstration mode</b>: the configuration file cannot be edited.</div>");
	}
	else if (MetaModel::GetModuleSetting('itop-config', 'config_editor', '') == 'disabled')
	{
		$oP->add("<div class=\"header_message message_info\">iTop interactive edition of the configuration as been disabled. See <tt>'config_editor' => 'disabled'</tt> in the configuration file.</div>");
	}
	else
	{
		$sConfigFile = APPROOT.'conf/'.utils::GetCurrentEnvironment().'/config-itop.php';

        $iEditorTopMargin = 9;
        $sConfig = str_replace("\r\n", "\n", file_get_contents($sConfigFile));
        $sOrginalConfig = $sConfig;

        if (!empty($sOperation))
        {
            $iEditorTopMargin = 14;
            $sConfig = utils::ReadParam('new_config', '', false, 'raw_data');
            $sOrginalConfig = utils::ReadParam('prev_config', '', false, 'raw_data');
        }

        if ($sOperation == 'revert')
        {
            $oP->add('<div id="save_result" class="header_message message_info">'.Dict::S('config-reverted').'</div>');
        }
        if ($sOperation == 'save')
        {
            $sTransactionId = utils::ReadParam('transaction_id', '');
            if (!utils::IsTransactionValid($sTransactionId, true))
            {
                $oP->add("<div class=\"header_message message_info\">Error: invalid Transaction ID. The configuration was <b>NOT</b> modified.</div>");
            }
            else
            {
                if ($sConfig == $sOrginalConfig)
                {
                    $oP->add('<div id="save_result" class="header_message">'.Dict::S('config-no-change').'</div>');
                }
                else
                {
                    try
                    {
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
                        @chmod($sConfigFile, 0444); // Read-only

                        $oP->p('<div id="save_result" class="header_message message_ok">'.Dict::S('config-saved').'</div>');
                        $sOrginalConfig = str_replace("\r\n", "\n", file_get_contents($sConfigFile));
                    }
                    catch (Exception $e)
                    {
                        $oP->p('<div id="save_result" class="header_message message_error">'.$e->getMessage().'</div>');
                    }
                }
            }
        }


		$sConfigEscaped = htmlentities($sConfig, ENT_QUOTES, 'UTF-8');
		$sOriginalConfigEscaped = htmlentities($sOrginalConfig, ENT_QUOTES, 'UTF-8');
		$oP->p(Dict::S('config-edit-intro'));
		$oP->add("<form method=\"POST\">");
        $oP->add("<input id=\"operation\" type=\"hidden\" name=\"operation\" value=\"save\">");
		$oP->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".utils::GetNewTransactionId()."\">");
		$oP->add("<input id=\"submit_button\" type=\"submit\" value=\"".Dict::S('config-apply')."\" title=\"".Dict::S('config-apply-title')."\"><button id=\"cancel_button\" disabled=\"disabled\" onclick=\"return ResetConfig();\">".Dict::S('config-cancel')."</button>");
		$oP->add("<input type=\"hidden\" id=\"prev_config\" name=\"prev_config\" value=\"$sOriginalConfigEscaped\">");
        $oP->add("<input type=\"hidden\"  name=\"new_config\" value=\"$sConfigEscaped\">");
        $oP->add("<div id =\"new_config\" style=\"position: absolute; top: ".$iEditorTopMargin."em; bottom: 0; left: 5px; right: 5px;\"></div>");
		$oP->add("</form>");
	
		$sConfirmCancel = addslashes(Dict::S('config-confirm-cancel'));
		$oP->add_script(
			<<<'EOF'
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
        var $editorContainer = $(editor.container);
        var $editorForm = $editorContainer.closest("form");
        return $editorForm;
	};
	
	var updateConfigEditorButtonState = function(editor) {
	    var isSameContent = (editor.getValue() == $('#prev_config').val());
	    var hasNoError = $.isEmptyObject(editor.getSession().getAnnotations());
	    $('#cancel_button').attr('disabled', isSameContent);
	    $('#submit_button').attr('disabled', isSameContent || !hasNoError);
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
EOF
		);
		$oP->add_ready_script(
	        <<<'EOF'
var editor = ace.edit("new_config");

var $configurationSource = $('input[name="new_config"]');
editor.getSession().setValue($configurationSource.val());

editor.getSession().on('change', function()
{
  $configurationSource.val(editor.getSession().getValue());
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
        $editorForm = EditorUtils.getEditorForm(editor);
        $submitButton = $('#submit_button');
        
        if ($submitButton.is(":enabled")) {
            $editorForm.submit();
        }
    }
});


var $editorForm = EditorUtils.getEditorForm(editor);
$editorForm.submit(function() {
	EditorUtils.saveEditorDisplay(editor);
});


EditorUtils.restoreEditorDisplay(editor);
editor.focus();
EOF
        );

		$oP->add_script(
<<<EOF
function ResetConfig()
{
    var editor = ace.edit("new_config");
	$("#operation").attr('value', 'revert');
	if (editor.getValue() != $('#prev_config').val())
	{
		if (confirm('$sConfirmCancel'))
		{
			$('input[name="new_config"]').val($('#prev_config').val());
			return true;
		}
	}
	return false;
}
EOF
		);
	}
}
catch(Exception $e)
{
	$oP->p('<b>'.$e->getMessage().'</b>');
}

$oP->output();
