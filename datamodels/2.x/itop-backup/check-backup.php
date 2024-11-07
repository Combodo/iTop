<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

// Purpose: check that the backup has been successfully executed
//          this script is aimed at being invoked in CLI mode only

// Developer's notes:
//   Duplicated code: sys_get_temp_dir, the computation of the target filename, etc.

// Recommended usage in cron
// /usr/bin/php -q /var/www/combodo/modules/itop-backup/check-backup.php --backup_file=/home/backups/combodo-crm-%Y-%m-%d
// Do not forget to set the 'itop_backup_incident' configuration file parameter !

use Combodo\iTop\Application\WebPage\CLIPage;

if (file_exists(__DIR__.'/../../approot.inc.php'))
{
	require_once __DIR__.'/../../approot.inc.php';   // When in env-xxxx folder
}
else
{
	require_once __DIR__.'/../../../approot.inc.php';   // When in datamodels/x.x folder
}
require_once(APPROOT.'application/utils.inc.php');
require_once(APPROOT.'application/startup.inc.php');


/**
 * Uses production env
 *
 * @return \Config
 */
function GetConfig()
{
	$oConfig = new Config(APPCONF.'production/config-itop.php');

	return $oConfig;
}


function ReadMandatoryParam($sParam)
{
	$value = utils::ReadParam($sParam, null, true /* Allow CLI */, 'raw_data');
	if (is_null($value))
	{
		throw new Exception("Missing argument '$sParam'");
	}
	return $value; 
}

if (!function_exists('sys_get_temp_dir'))
{
	// Based on http://www.phpit.net/
	// article/creating-zip-tar-archives-dynamically-php/2/
	function sys_get_temp_dir()
	{
		// Try to get from environment variable
		if (!empty($_ENV['TMP']))
		{
			return realpath($_ENV['TMP']);
		}
		else if (!empty($_ENV['TMPDIR']))
		{
			return realpath($_ENV['TMPDIR']);
		}
		else if (!empty($_ENV['TEMP']))
		{
			return realpath($_ENV['TEMP']);
		}
		// Detect by creating a temporary file
		else
		{
			// Try to use system's temporary directory
			// as random name shouldn't exist
			$temp_file = tempnam(md5(uniqid(rand(), TRUE)), '');
			if ($temp_file)
			{
				$temp_dir = realpath(dirname($temp_file));
				unlink($temp_file);
				return $temp_dir;
			}
			else
			{
				return FALSE;
			}
		}
	}
}


/**
 * @param int $iRefTime Reference date time as a unix timestamp
 *
 * @return string Absolute path to the backup file, WITHOUT the file extension (`.tar.gz`)
 * @throws \Exception
 */
function MakeArchiveFileName($iRefTime = null)
{
	$sDefaultBackupFileName = sys_get_temp_dir().'/'."__DB__-%Y-%m-%d";
	$sBackupFile =  utils::ReadParam('backup_file', $sDefaultBackupFileName, true, 'raw_data');

	$oBackup = new DBBackup();
	$oDateTime = $iRefTime !== null ? DateTime::createFromFormat('U', $iRefTime) : new DateTime();
	$sBackupFile = $oBackup->MakeName($sBackupFile, $oDateTime);

	return $sBackupFile;
}



function RaiseAlarm($sMessage)
{
	echo "$sMessage\n";

	try
	{
		$sTicketLogin = ReadMandatoryParam('check_ticket_login');
		$sTicketPwd = ReadMandatoryParam('check_ticket_pwd');
		$sTicketTitle = ReadMandatoryParam('check_ticket_title');
		$sTicketCustomer = ReadMandatoryParam('check_ticket_customer');
		$sTicketService = ReadMandatoryParam('check_ticket_service');
		$sTicketSubcategory = ReadMandatoryParam('check_ticket_service_subcategory');
		$sTicketWorkgroup = ReadMandatoryParam('check_ticket_workgroup');
		$sTicketImpactedServer = ReadMandatoryParam('check_ticket_impacted_server');
	}
	catch (Exception $e)
	{
		echo "The ticket could not be created: ".$e->GetMessage()."\n";
		return;
	}

	$sMessage = "Server: [[Server:".$sTicketImpactedServer."]]\n".$sMessage;

	require_once(APPROOT.'webservices/itopsoaptypes.class.inc.php');

	$oConfig = GetConfig();
	$sItopRootConfig = $oConfig->GetModuleSetting('itop-backup', 'itop_backup_incident');
	if (empty($sItopRootConfig))
	{
		// by default getting self !
		// we could have '' as config value...
		$sItopRootConfig = $oConfig->Get('app_root_url');
	}

	try
	{
		$sWsdlUri = $sItopRootConfig.'/webservices/itop.wsdl.php';
		$aSOAPMapping = SOAPMapping::GetMapping();
		ini_set("soap.wsdl_cache_enabled", "0");
		$oSoapClient = new SoapClient(
			$sWsdlUri,
			array(
				'trace' => 1,
				'classmap' => $aSOAPMapping, // defined in itopsoaptypes.class.inc.php
			)
		);
	}
	catch (Exception $e)
	{
		echo "ERROR: Failed to read WSDL of the target iTop ($sItopRootConfig)\n";

		return;
	}

	try
	{
		$oRes = $oSoapClient->CreateIncidentTicket
		(
			$sTicketLogin, /* login */
			$sTicketPwd, /* password */
			$sTicketTitle, /* title */
			$sMessage, /* description */
			null, /* caller */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', $sTicketCustomer))), /* customer */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', $sTicketService))), /* service */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', $sTicketSubcategory))), /* service subcategory */
			'', /* product */
			new SOAPExternalKeySearch(array(new SOAPSearchCondition('name', $sTicketWorkgroup))), /* workgroup */
			array(
				new SOAPLinkCreationSpec(
					'Server',
					array(new SOAPSearchCondition('name', $sTicketImpactedServer)),
					array()
				),
			), /* impacted cis */
			'1', /* impact */
			'1' /* urgency */
		);
	}
	catch(Exception $e)
	{
		echo "The ticket could not be created: SOAP Exception = '".$e->getMessage()."'\n";

		return;
	}

	//echo "<pre>\n";
	//print_r($oRes);
	//echo "</pre>\n";

	if ($oRes->status)
	{
		$sTicketName = $oRes->result[0]->values[1]->value;
		echo "Created ticket: $sTicketName\n";
	}
	else
	{
		echo "ERROR: Failed to create the ticket in target iTop ($sItopRootConfig)\n";
		foreach ($oRes->errors->messages as $oMessage)
		{
			echo $oMessage->text."\n";
		}
	}	
}


//////////
// Main

try
{
	utils::UseParamFile();
}
catch(Exception $e)
{
	echo "Error: ".$e->GetMessage()."\n";
	exit;
}


if (utils::IsModeCLI())
{
	SetupUtils::CheckPhpAndExtensionsForCli(new CLIPage('Check backup utility'));

	echo date('Y-m-d H:i:s')." - running check-backup utility\n";
	try
	{
		$sAuthUser = ReadMandatoryParam('auth_user');
		$sAuthPwd = ReadMandatoryParam('auth_pwd');
	}
	catch (Exception $e)
	{
		$sMessage = $e->getMessage();
		ToolsLog::Error($sMessage);
		echo $sMessage;
		exit;
	}
	$bDownloadBackup = false;
	if (UserRights::CheckCredentials($sAuthUser, $sAuthPwd))
	{
		UserRights::Login($sAuthUser); // Login & set the user's language
	}
	else
	{
		ExitError($oP, "Access restricted or wrong credentials ('$sAuthUser')");
	}
}
else
{
	require_once(APPROOT.'application/loginwebpage.class.inc.php');
	LoginWebPage::DoLogin(); // Check user rights and prompt if needed
	$bDownloadBackup = utils::ReadParam('download', false);
}

if (!UserRights::IsAdministrator())
{
	ExitError($oP, "Access restricted to administors");
}



// N°1802 : was moved from script param to config file (avoid direct call with untrusted param value)
$sItopRootParam = utils::ReadParam('check_ticket_itop', null, true, 'raw_data');
if (!empty($sItopRootParam))
{
	echo "ERROR: parameter 'check_ticket_itop' should now be specified in the config file 'itop_backup_incident' parameter\n";

	return;
}


$sZipArchiveFile = MakeArchiveFileName().'.tar.gz';
$sZipArchiveFileForDisplay = utils::HtmlEntities($sZipArchiveFile);
echo date('Y-m-d H:i:s')." - Checking file: $sZipArchiveFileForDisplay\n";


if (!file_exists($sZipArchiveFile))
{
	RaiseAlarm("Missing backup file '$sZipArchiveFileForDisplay'");

	return;
}

$aStat = stat($sZipArchiveFile);
if (!$aStat)
{
	RaiseAlarm("Failed to stat backup file '$sZipArchiveFileForDisplay'");

	return;
}

$iSize = (int)$aStat['size'];
$iMIN = utils::ReadParam('check_size_min', 0);
if ($iSize <= $iMIN)
{
	RaiseAlarm("Backup file '$sZipArchiveFileForDisplay' too small (Found: $iSize, while expecting $iMIN bytes)");

	return;
}


echo "Found the archive\n";
$sOldArchiveFile = MakeArchiveFileName(time() - 86400).'.tar.gz'; // yesterday's archive
$sOldArchiveFileForDisplay = utils::HtmlEntities($sOldArchiveFile);
if (file_exists($sOldArchiveFile))
{
	if ($aOldStat = stat($sOldArchiveFile))
	{
		echo "Comparing its size with older file: $sOldArchiveFileForDisplay\n";
		$iOldSize = (int)$aOldStat['size'];
		$fVariationPercent = 100 * ($iSize - $iOldSize) / $iOldSize;
		$sVariation = round($fVariationPercent, 2)." percent(s)";

		$iREDUCTIONMAX = utils::ReadParam('check_size_reduction_max');
		if ($fVariationPercent < -$iREDUCTIONMAX)
		{
			RaiseAlarm("Backup file '$sZipArchiveFileForDisplay' changed by $sVariation, expecting a reduction limited to $iREDUCTIONMAX percents of the original size");
		}
		elseif ($fVariationPercent < 0)
		{
			echo "Size variation: $sVariation (the maximum allowed reduction is $iREDUCTIONMAX) \n";
		}
		else
		{
			echo "The archive grew by: $sVariation\n";
		}
	}
}
