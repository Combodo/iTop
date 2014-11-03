<?php
// Copyright (C) 2014 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

// Purpose: check that the backup has been successfully executed
//          this script is aimed at being invoked in CLI mode only

// Developer's notes:
//   Duplicated code: sys_get_temp_dir, the computation of the target filename, etc.

// Recommended usage in CRON
// /usr/bin/php -q /var/www/combodo/modules/itop-backup/backup.php --backup_file=/home/backups/combodo-crm-%Y-%m-%d

if (!defined('__DIR__')) define('__DIR__', dirname(__FILE__));
require_once(__DIR__.'/../../approot.inc.php');
require_once(APPROOT.'/application/utils.inc.php');
require_once(APPROOT.'/core/config.class.inc.php');


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



function MakeArchiveFileName($iRefTime = null)
{
	$sDefaultBackupFileName = sys_get_temp_dir().'/'."__DB__-%Y-%m-%d";
	$sBackupFile =  utils::ReadParam('backup_file', $sDefaultBackupFileName, true, 'raw_data');
	
	$oConfig = new Config(APPCONF.'production/config-itop.php');
	
	$sBackupFile = str_replace('__HOST__', $oConfig->GetDBHost(), $sBackupFile);
	$sBackupFile = str_replace('__DB__', $oConfig->GetDBName(), $sBackupFile);
	$sBackupFile = str_replace('__SUBNAME__', $oConfig->GetDBSubName(), $sBackupFile);
	
	if (is_null($iRefTime))
	{
		$sBackupFile = strftime($sBackupFile);
	}
	else
	{
		$sBackupFile = strftime($sBackupFile, $iRefTime);
	}

	return $sBackupFile.'.zip';
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
	
	//$sItopRootDefault = 'http'.((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']!='off')) ? 's' : '').'://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].dirname($_SERVER['SCRIPT_NAME']).'/../..';
	//$sItopRoot = utils::ReadParam('check_ticket_itop', $sItopRootDefault);
	$sItopRoot = ReadMandatoryParam('check_ticket_itop');

	$sWsdlUri = $sItopRoot.'/webservices/itop.wsdl.php';
	//$sWsdlUri .= '?service_category=';
	
	$aSOAPMapping = SOAPMapping::GetMapping();
	
	ini_set("soap.wsdl_cache_enabled","0");
	$oSoapClient = new SoapClient(
		$sWsdlUri,
		array(
			'trace' => 1,
			'classmap' => $aSOAPMapping, // defined in itopsoaptypes.class.inc.php
		)
	);
	
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
		echo "ERROR: Failed to create the ticket in target iTop ($sItopRoot)\n";
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

$sZipArchiveFile = MakeArchiveFileName();
echo date('Y-m-d H:i:s')." - Checking file: $sZipArchiveFile\n";

if (file_exists($sZipArchiveFile))
{
	if ($aStat = stat($sZipArchiveFile))
	{
		$iSize = (int) $aStat['size'];
		$iMIN = utils::ReadParam('check_size_min', 0);
		if ($iSize > $iMIN)
		{
			echo "Found the archive\n";
			$sOldArchiveFile = MakeArchiveFileName(time() - 86400); // yesterday's archive
			if (file_exists($sOldArchiveFile))
			{
				if ($aOldStat = stat($sOldArchiveFile))
				{
					echo "Comparing its size with older file: $sOldArchiveFile\n";
					$iOldSize = (int) $aOldStat['size'];
					$fVariationPercent = 100 * ($iSize - $iOldSize) / $iOldSize;
					$sVariation = round($fVariationPercent, 2)." percent(s)";

					$iREDUCTIONMAX = utils::ReadParam('check_size_reduction_max');
					if ($fVariationPercent < -$iREDUCTIONMAX)
					{
						RaiseAlarm("Backup file '$sZipArchiveFile' changed by $sVariation, expecting a reduction limited to $iREDUCTIONMAX percents of the original size");
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
		}
		else
		{
			RaiseAlarm("Backup file '$sZipArchiveFile' too small (Found: $iSize, while expecting $iMIN bytes)");
		}
	}
	else
	{
		RaiseAlarm("Failed to stat backup file '$sZipArchiveFile'");
	}
}
else
{
	RaiseAlarm("Missing backup file '$sZipArchiveFile'");
}

?>
