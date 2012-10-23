<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * Emailing: helper for the admins to troubleshoot email issues
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Wizard to configure and initialize the iTop application
 */
require_once('../approot.inc.php');
require_once(APPROOT.'/application/utils.inc.php');
require_once(APPROOT.'/core/email.class.inc.php');
require_once('./setuppage.class.inc.php');

$sOperation = Utils::ReadParam('operation', 'step1');
$oP = new SetupPage('iTop email test utility');


/**
 * Helper to check server setting required to send an email
 */  
function CheckEmailSetting($oP)
{
	$bRet = true;

	if (function_exists('php_ini_loaded_file')) // PHP >= 5.2.4
	{
		$sPhpIniFile = php_ini_loaded_file();
	}
	else
	{
		$sPhpIniFile = 'php.ini';
	}

	$bIsWindows = (array_key_exists('WINDIR', $_SERVER) || array_key_exists('windir', $_SERVER));
	if ($bIsWindows)
	{	
		$sSmtpServer = ini_get('SMTP');
		if (empty($sSmtpServer))
		{
			$oP->error("The SMTP server is not defined. Please add the 'SMTP' directive into $sPhpIniFile");
			$bRet = false;
		}
		else if (strcasecmp($sSmtpServer, 'localhost') == 0)
		{
			$oP->warning("Your SMTP server is configured to 'localhost'. You might want to set or change the 'SMTP' directive into $sPhpIniFile");
		}
		else
		{
			$oP->info("Your SMTP server: <strong>$sSmtpServer</strong>. To change this value, modify the 'SMTP' directive into $sPhpIniFile");
		}

		$iSmtpPort = (int) ini_get('smtp_port');
		if (empty($iSmtpPort))
		{
			$oP->info("The SMTP port is not defined. Please add the 'smtp_port' directive into $sPhpIniFile");
			$bRet = false;
		}
		else if ($iSmtpPort = 25)
		{
			$oP->info("Your SMTP port is configured to the default value: 25. You might want to set or change the 'smtp_port' directive into $sPhpIniFile");
		}
		else
		{
			$oP->info("Your SMTP port is configured to $iSmtpPort. You might want to set or change the 'smtp_port' directive into $sPhpIniFile");
		}
	}
	else
	{
		// Not a windows system
		$sSendMail = ini_get('sendmail_path');
		if (empty($sSendMail))
		{
			$oP->error("The command to send mail is not defined. Please add the 'sendmail_path' directive into $sPhpIniFile. A recommended setting is <em>sendmail_path=sendmail -t -i</em>");
			$bRet = false;
		}
		else
		{
			$oP->info("The command to send mail: <strong>$sSendMail</strong>. To change this value, modify the 'sendmail_path' directive into $sPhpIniFile");
		}
	}
	if ($bRet)
	{
		$oP->ok("PHP settings are ok to proceed with a test of the email");
	}
	return $bRet;
}


/**
 * Display the form for the first step of the test wizard
 * which consists in a basic check of the configuration and display of a form for testing
 */  
function DisplayStep1(SetupPage $oP)
{
	$sNextOperation = 'step2';
	$oP->add("<h1>iTop email test</h1>\n");
	$oP->add("<h2>Checking prerequisites</h2>\n");
	if (CheckEmailSetting($oP))
	{
		$sRedStar = '<span class="hilite">*</span>';
		$oP->add("<h2>Try to send an email</h2>\n");
		$oP->add("<form method=\"post\" onSubmit=\"return DoSubmit('Sending an email...', 10)\">\n");
		// Form goes here
		$oP->add("<fieldset><legend>Test configuration</legend>\n");
		$aForm = array();
		$aForm[] = array(
			'label' => "To$sRedStar:",
			'input' => "<input id=\"to\" type=\"text\" name=\"to\" value=\"\">",
			'help' => ' pure email address (john.foo@worldcompany.com)',
		);
		$aForm[] = array(
			'label' => "From:",
			'input' => "<input id=\"from\" type=\"text\" name=\"from\" value=\"\">",
			'help' => ' defaults to \'To\'',
		);
		$oP->form($aForm);
		$oP->add("</fieldset>\n");
		$oP->add("<input type=\"hidden\" name=\"operation\" value=\"$sNextOperation\">\n");
		$oP->add("<button type=\"submit\">Next >></button>\n");
		$oP->add("</form>\n");
	}
}

/**
 * Display the form for the second step of the configuration wizard
 * which consists in sending an email, which maybe a problem under Windows
 */  
function DisplayStep2(SetupPage $oP, $sFrom, $sTo)
{
	//$sNextOperation = 'step3';
	$oP->add("<h1>iTop configuration wizard</h1>\n");
	$oP->add("<h2>Step 2: send an email</h2>\n");
	$oP->add("<p>Sending an email to '$sTo'... (From: '$sFrom')</p>\n");
	$oP->add("<form method=\"post\">\n");

	$oEmail = new Email();
	$oEmail->SetRecipientTO($sTo);
	$oEmail->SetRecipientFrom($sFrom);
	$oEmail->SetSubject("Test iTop");
	$oEmail->SetBody("<p>Hello,</p><p>The email function is now working fine.</p><p>You may now be able to use the notification function.</p><p>iTop</p>");
	$iRes = $oEmail->Send($aIssues, true /* force synchronous exec */);
	switch ($iRes)
	{
		case EMAIL_SEND_OK:
			$oP->ok("The email has been sent, you may now check that the email will arrive...");
			break;

		case EMAIL_SEND_PENDING:
			$oP->ok("Email queued");
			$oP->add("<button onClick=\"window.history.back();\"><< Back</button>\n");
			break;

		case EMAIL_SEND_ERROR:
			foreach ($aIssues as $sError)
			{
				$oP->error($sError);
			}
			$oP->add("<button onClick=\"window.history.back();\"><< Back</button>\n");
			break;
	}
}

/**
 * Main program
 */

// #@# Init default timezone -> do not get a notice... to be improved !!!
// duplicated from 'attributedef.class.inc.php', needed here because mail() does
// generate a notice
date_default_timezone_set('Europe/Paris');


try
{
	switch($sOperation)
	{
		case 'step1':
		DisplayStep1($oP);
		break;
		
		case 'step2':
		$oP->no_cache();
		$sTo = Utils::ReadParam('to', '', false, 'raw_data');
		$sFrom = Utils::ReadParam('from', '', false, 'raw_data');
		if (strlen($sFrom) == 0)
		{
			$sFrom = $sTo;
		}
		DisplayStep2($oP, $sFrom, $sTo);
		break;

		default:
		$oP->error("Error: unsupported operation '$sOperation'");
		
	}
}
catch(Exception $e)
{
	$oP->error("Error: '".$e->getMessage()."'");	
}
catch(CoreException $e)
{
	$oP->error("Error: '".$e->getHtmlDesc()."'");	
}
$oP->output();
?>
