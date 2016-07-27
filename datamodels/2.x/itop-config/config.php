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

require_once('../../approot.inc.php');
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
		eval('?'.'>'.trim($sContents));
		$sNoise = trim(ob_get_contents());
		ob_end_clean();
	}
	catch (Exception $e)
	{
		// well, never reach in case of parsing error :-(
		throw new Exception('Error in configuration: '.$e->getMessage());
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
			$oP->add_ready_script(
<<<EOF
setCursorPos($('#new_config')[0], $iStart, $iEnd);
$('#new_config')[0].focus();
var iScroll = Math.floor($('#new_config')[0].scrollHeight * ($iLine - 20) / $iTotalLines);
$('#new_config').scrollTop(iScroll);
EOF
			);

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
LoginWebPage::DoLogin(true); // Check user rights and prompt if needed (must be admin)

//$sOperation = utils::ReadParam('operation', 'menu');
//$oAppContext = new ApplicationContext();

$oP = new iTopWebPage(Dict::S('config-edit-title'));
$oP->set_base(utils::GetAbsoluteUrlAppRoot().'pages/');


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
		$oP->add_style(
<<<EOF
textarea {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;

	width: 100%;
	height: 550px;
}
.current_line {
	display: none;
	margin-left: 20px;
}
EOF
		);
	
		$sConfigFile = APPROOT.'conf/'.utils::GetCurrentEnvironment().'/config-itop.php';
	
		if ($sOperation == 'save')
		{
			$sConfig = utils::ReadParam('new_config', '', false, 'raw_data');
			$sTransactionId = utils::ReadParam('transaction_id', '');
			$sOrginalConfig = utils::ReadParam('prev_config', '', false, 'raw_data');
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
			
						$oP->p('<div id="save_result" class="header_message message_ok">'.Dict::S('Successfully recorded.').'</div>');
						$sOrginalConfig = str_replace("\r\n", "\n", file_get_contents($sConfigFile));
					}
					catch (Exception $e)
					{
						$oP->p('<div id="save_result" class="header_message message_error">'.$e->getMessage().'</div>');
					}
				}
			}
		}
		else
		{
			$sConfig = str_replace("\r\n", "\n", file_get_contents($sConfigFile));
			$sOrginalConfig = $sConfig;
		}
	
		$sConfigEscaped = htmlentities($sConfig, ENT_QUOTES, 'UTF-8');
		$sOriginalConfigEscaped = htmlentities($sOrginalConfig, ENT_QUOTES, 'UTF-8');
		$oP->p(Dict::S('config-edit-intro'));
		$oP->add("<form method=\"POST\">");
		$oP->add("<input type=\"hidden\" name=\"operation\" value=\"save\">");
		$oP->add("<input type=\"hidden\" name=\"transaction_id\" value=\"".utils::GetNewTransactionId()."\">");
		$oP->add("<input type=\"submit\" value=\"".Dict::S('config-apply')."\"><button onclick=\"ResetConfig(); return false;\">".Dict::S('config-cancel')."</button>");
		$oP->add("<span class=\"current_line\">".Dict::Format('config-current-line', "<span class=\"line_number\"></span>")."</span>");
		$oP->add("<input type=\"hidden\" id=\"prev_config\" name=\"prev_config\" value=\"$sOriginalConfigEscaped\">");
		$oP->add("<textarea id =\"new_config\" name=\"new_config\" onkeyup=\"UpdateLineNumber();\" onmouseup=\"UpdateLineNumber();\">$sConfigEscaped</textarea>");
		$oP->add("<input type=\"submit\" value=\"".Dict::S('config-apply')."\"><button onclick=\"ResetConfig(); return false;\">".Dict::S('config-cancel')."</button>");
		$oP->add("<span class=\"current_line\">".Dict::Format('config-current-line', "<span class=\"line_number\"></span>")."</span>");
		$oP->add("</form>");
	
		$sConfirmCancel = addslashes(Dict::S('config-confirm-cancel'));
		$oP->add_script(
<<<EOF
function UpdateLineNumber()
{
	var oTextArea = $('#new_config')[0];
	$('.line_number').html(oTextArea.value.substr(0, oTextArea.selectionStart).split("\\n").length);
	$('.current_line').show();
}
function ResetConfig()
{
	if ($('#new_config').val() != $('#prev_config').val())
	{
		if (confirm('$sConfirmCancel'))
		{
			$('#new_config').val($('#prev_config').val());
		}
	}
	$('.current_line').hide();
	$('#save_result').hide();
	return false;
}

function setCursorPos(input, start, end) {
    if (arguments.length < 3) end = start;
    if ("selectionStart" in input) {
        setTimeout(function() {
            input.selectionStart = start;
            input.selectionEnd = end;
        }, 1);
    }
    else if (input.createTextRange) {
        var rng = input.createTextRange();
        rng.moveStart("character", start);
        rng.collapse();
        rng.moveEnd("character", end - start);
        rng.select();
    }
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
