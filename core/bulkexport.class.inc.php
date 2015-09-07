<?php
// Copyright (C) 2015 Combodo SARL
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

define('EXPORTER_DEFAULT_CHUNK_SIZE', 1000);

class BulkExportException extends Exception
{
	protected $sLocalizedMessage;
	public function __construct($message, $sLocalizedMessage, $code = null, $previous = null)
	{
		parent::__construct($message, $code, $previous);
		$this->sLocalizedMessage = $sLocalizedMessage;
	}
	
	public function GetLocalizedMessage()
	{
		return $this->sLocalizedMessage;
	}
}
class BulkExportMissingParameterException extends BulkExportException
{
	public function __construct($sFieldCode)
	{
		parent::__construct('Missing parameter: '.$sFieldCode, Dict::Format('Core:BulkExport:MissingParameter_Param', $sFieldCode));
	}
		
}

/**
 * Class BulkExport
 *
 * @copyright   Copyright (C) 2015 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class BulkExportResult extends DBObject
{
	public static function Init()
	{
		$aParams = array
		(
			"category" => 'core/cmdb',
			"key_type" => 'autoincrement',
			"name_attcode" => array('created'),
			"state_attcode" => '',
			"reconc_keys" => array(),
			"db_table" => 'priv_bulk_export_result',
			"db_key_field" => 'id',
			"db_finalclass_field" => '',
			"display_template" => '',
		);
		MetaModel::Init_Params($aParams);

		MetaModel::Init_AddAttribute(new AttributeDateTime("created", array("allowed_values"=>null, "sql"=>"created", "default_value"=>"", "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("user_id", array("allowed_values"=>null, "sql"=>"user_id", "default_value"=>0, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeInteger("chunk_size", array("allowed_values"=>null, "sql"=>"chunk_size", "default_value"=>0, "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("format", array("allowed_values"=>null, "sql"=>"format", "default_value"=>'', "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeString("temp_file_path", array("allowed_values"=>null, "sql"=>"temp_file_path", "default_value"=>'', "is_null_allowed"=>true, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLongText("search", array("allowed_values"=>null, "sql"=>"search", "default_value"=>'', "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeLongText("status_info", array("allowed_values"=>null, "sql"=>"status_info", "default_value"=>'', "is_null_allowed"=>false, "depends_on"=>array())));
	}
	
	public function ComputeValues()
	{
		$this->Set('user_id', UserRights::GetUserId());
	}
}

/**
 * Garbage collector for cleaning "old" export results from the database and the disk.
 * This background process runs once per day and deletes the results of all exports which
 * are older than one day.
 */
class BulkExportResultGC implements iBackgroundProcess
{
	public function GetPeriodicity()
	{
		return 24*3600; // seconds
	}

	public function Process($iTimeLimit)
	{
		$sDateLimit = date('Y-m-d H:i:s', time() - 24*3600); // Every BulkExportResult older than one day will be deleted

		$sOQL = "SELECT BulkExportResult WHERE created < '$sDateLimit'";
		$iProcessed = 0;
		while (time() < $iTimeLimit)
		{
			// Next one ?
			$oSet = new CMDBObjectSet(DBObjectSearch::FromOQL($sOQL), array('created' => true) /* order by*/, array(), null, 1 /* limit count */);
			$oSet->OptimizeColumnLoad(array('temp_file_path'));
			$oResult = $oSet->Fetch();
			if (is_null($oResult))
			{
				// Nothing to be done
				break;
			}
			$iProcessed++;
			@unlink($oResult->Get('temp_file_path'));
			$oResult->DBDelete();
		}
		return "Cleaned $iProcessed old export results(s).";
	}
}

/**
 * Class BulkExport
 *
 * @copyright   Copyright (C) 2015 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

abstract class BulkExport
{
	protected $oSearch;
	protected $iChunkSize;
	protected $sFormatCode;
	protected $aStatusInfo;
	protected $oBulkExportResult;
	protected $sTmpFile;
	protected $bLocalizeOutput;
	
	public function __construct()
	{
		$this->oSearch = null;
		$this->iChunkSize = 0;
		$this->sFormatCode = null;
		$this->aStatusInfo = array();
		$this->oBulkExportResult = null;
		$this->sTmpFile = '';
		$this->bLocalizeOutput = false;
	}
		
	/**
	 * Find the first class capable of exporting the data in the given format
	 * @param string $sFormat The lowercase format (e.g. html, csv, spreadsheet, xlsx, xml, json, pdf...)
	 * @param DBSearch $oSearch The search/filter defining the set of objects to export or null when listing the supported formats
	 * @return iBulkExport|NULL
	 */
	static public function FindExporter($sFormatCode, $oSearch = null)
	{
		foreach(get_declared_classes() as $sPHPClass)
		{
			$oRefClass = new ReflectionClass($sPHPClass);
			if ($oRefClass->isSubclassOf('BulkExport') && !$oRefClass->isAbstract())
			{
				$oBulkExporter = new $sPHPClass();
				if ($oBulkExporter->IsFormatSupported($sFormatCode, $oSearch))
				{
					if ($oSearch)
					{
						$oBulkExporter->SetObjectList($oSearch);
					}
					return $oBulkExporter;
				}
			}
		}
		return null;
	}
	
	/**
	 * Find the exporter corresponding to the given persistent token
	 * @param int $iPersistentToken The identifier of the BulkExportResult object storing the information
	 * @return iBulkExport|NULL
	 */
	static public function FindExporterFromToken($iPersistentToken = null)
	{
		$oBulkExporter = null;
		$oInfo = MetaModel::GetObject('BulkExportResult', $iPersistentToken, false);
		if ($oInfo && ($oInfo->Get('user_id') == UserRights::GetUserId()))
		{
			$sFormatCode = $oInfo->Get('format');
			$oSearch = DBObjectSearch::unserialize($oInfo->Get('search'));
			
			$oBulkExporter = self::FindExporter($sFormatCode, $oSearch);
			if ($oBulkExporter)
			{
				$oBulkExporter->SetFormat($sFormatCode);
				$oBulkExporter->SetObjectList($oSearch);
				$oBulkExporter->SetChunkSize($oInfo->Get('chunk_size'));
				$oBulkExporter->SetStatusInfo(json_decode($oInfo->Get('status_info'), true));
				$oBulkExporter->sTmpFile = $oInfo->Get('temp_file_path');
				$oBulkExporter->oBulkExportResult = $oInfo;
			}
		}
		return $oBulkExporter;
	}
	
	public function AppendToTmpFile($data)
	{
		if ($this->sTmpFile == '')
		{
			$this->sTmpFile = $this->MakeTmpFile($this->GetFileExtension());
		}
		$hFile = fopen($this->sTmpFile, 'ab');
		if ($hFile !== false)
		{
			fwrite($hFile, $data);
			fclose($hFile);
		}
	}
	
	public function GetTmpFilePath()
	{
		return $this->sTmpFile;
	}
	
	/**
	 * Lists all possible export formats. The output is a hash array in the form: 'format_code' => 'localized format label'
	 * @return multitype:string
	 */
	static public function FindSupportedFormats()
	{
		$aSupportedFormats = array();
		foreach(get_declared_classes() as $sPHPClass)
		{
			$oRefClass = new ReflectionClass($sPHPClass);
			if ($oRefClass->isSubClassOf('BulkExport') && !$oRefClass->isAbstract())
			{
				$oBulkExporter = new $sPHPClass;
				$aFormats = $oBulkExporter->GetSupportedFormats();
				$aSupportedFormats = array_merge($aSupportedFormats, $aFormats);
			}
		}
		return $aSupportedFormats;
	}

	/**
	 * (non-PHPdoc)
	 * @see iBulkExport::SetChunkSize()
	 */
	public function SetChunkSize($iChunkSize)
	{
		$this->iChunkSize = $iChunkSize;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see iBulkExport::SetObjectList()
	 */
	public function SetObjectList(DBSearch $oSearch)
	{
		$this->oSearch = $oSearch;
	}
	
	public function SetFormat($sFormatCode)
	{
		$this->sFormatCode = $sFormatCode;	
	}
	
	/**
	 * (non-PHPdoc)
	 * @see iBulkExport::IsFormatSupported()
	 */
	public function IsFormatSupported($sFormatCode, $oSearch = null)
	{
		return array_key_exists($sFormatCode, $this->GetSupportedFormats());
	}

	/**
	 * (non-PHPdoc)
	 * @see iBulkExport::GetSupportedFormats()
	 */
	public function GetSupportedFormats()
	{
		return array(); // return array('csv' => Dict::S('UI:ExportFormatCSV'));
	}
	

	public function SetHttpHeaders(WebPage $oPage)
	{
	}

	public function GetHeader()
	{
	}
	abstract public function GetNextChunk(&$aStatus);
	public function GetFooter()
	{
		
	}
	
	public function SaveState()
	{
		if ($this->oBulkExportResult === null)
		{
			$this->oBulkExportResult = new BulkExportResult();
			$this->oBulkExportResult->Set('format', $this->sFormatCode);
			$this->oBulkExportResult->Set('search', $this->oSearch->serialize());
			$this->oBulkExportResult->Set('chunk_size', $this->iChunkSize);	
			$this->oBulkExportResult->Set('temp_file_path', $this->sTmpFile);	
		}
		$this->oBulkExportResult->Set('status_info', json_encode($this->GetStatusInfo()));
		return $this->oBulkExportResult->DBWrite();
	}
	
	public function Cleanup()
	{
		if (($this->oBulkExportResult &&  (!$this->oBulkExportResult->IsNew())))
		{
			$sFilename = $this->oBulkExportResult->Get('temp_file_path');
			if ($sFilename != '')
			{
				@unlink($sFilename);
			}
			$this->oBulkExportResult->DBDelete();
		}
	}
	
	public function EnumFormParts()
	{
		return array();
	}
	
	public function DisplayFormPart(WebPage $oP, $sPartId)
	{
	}
	
	public function DisplayUsage(Page $oP)
	{
		
	}
	public function ReadParameters()
	{
		$this->bLocalizeOutput = !((bool)utils::ReadParam('no_localize', 0, true, 'integer'));
	}
	
	public function GetResultAsHtml()
	{
		
	}
	public function GetRawResult()
	{
		
	}
	public function GetMimeType()
	{
		
	}
	public function GetFileExtension()
	{
		
	}
	public function GetCharacterSet()
	{
		return 'UTF-8';
	}
	
	public function GetStatistics()
	{
		
	}
	
	public function GetDownloadFileName()
	{
		return Dict::Format('Core:BulkExportOf_Class', MetaModel::GetName($this->oSearch->GetClass())).'.'.$this->GetFileExtension();
	}

	public function SetStatusInfo($aStatusInfo)
	{
		$this->aStatusInfo = $aStatusInfo;
	}
	
	public function GetStatusInfo()
	{
		return $this->aStatusInfo;
	}

	protected function MakeTmpFile($sExtension)
	{
		if(!is_dir(APPROOT."data/bulk_export"))
		{
			@mkdir(APPROOT."data/bulk_export", 0777, true /* recursive */);
			clearstatcache();
		}
		if (!is_writable(APPROOT."data/bulk_export"))
		{
			throw new Exception('Data directory "'.APPROOT.'data/bulk_export" could not be written.');
		}

		$iNum = rand();
		$sFileName = '';
		do
		{
			$iNum++;
			$sToken = sprintf("%08x", $iNum);
			$sFileName = APPROOT."data/bulk_export/$sToken.".$sExtension;
			$hFile = @fopen($sFileName, 'x');
		}
		while($hFile === false);
	
		fclose($hFile);
		return $sFileName;
	}
}

// The built-in exports
require_once(APPROOT.'core/tabularbulkexport.class.inc.php');
require_once(APPROOT.'core/htmlbulkexport.class.inc.php');
require_once(APPROOT.'core/pdfbulkexport.class.inc.php');
require_once(APPROOT.'core/csvbulkexport.class.inc.php');
require_once(APPROOT.'core/excelbulkexport.class.inc.php');
require_once(APPROOT.'core/spreadsheetbulkexport.class.inc.php');
require_once(APPROOT.'core/xmlbulkexport.class.inc.php');

