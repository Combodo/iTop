<?php
require_once('xlsxwriter.class.php');

class ExcelExporter
{
	protected $sToken;
	protected $aStatistics;
	protected $sState;
	protected $fStartTime;
	protected $oSearch;
	protected $aObjectsIDs;
	protected $aTableHeaders;
	protected $aAuthorizedClasses;
	protected $iChunkSize = 1000;
	protected $iPosition;
	protected $sOutputFilePath;
	protected $bAdvancedMode;
	
	public function __construct($sToken = null)
	{
		$this->aStatistics = array(
			'objects_count' => 0,
			'total_duration' => 0,
			'data_retrieval_duration' => 0,
			'excel_build_duration' => 0,
			'excel_write_duration' => 0,
			'peak_memory_usage' => 0,		
		);
		$this->fStartTime = microtime(true);
		$this->oSearch = null;
		
		$this->sState = 'new';
		$this->aObjectsIDs = array();
		$this->iPosition = 0;
		$this->aAuthorizedClasses = null;
		$this->aTableHeaders = null;
		$this->sOutputFilePath = null;
		$this->bAdvancedMode = false;
		$this->CheckDataDir();
		if ($sToken == null)
		{
			$this->sToken = $this->GetNewToken();
		}
		else
		{
			$this->sToken = $sToken;
			$this->ReloadState();
		}
	}
	
	public function __destruct()
	{
		if (($this->sState != 'done') && ($this->sState != 'error') && ($this->sToken != null))
		{
			// Operation in progress, save the state
			$this->SaveState();
		}
		else
		{
			// Operation completed, cleanup the temp files
			@unlink($this->GetStateFile());
			@unlink($this->GetDataFile());
		}
		self::CleanupOldFiles();	
	}
	
	public function SetChunkSize($iChunkSize)
	{
		$this->iChunkSize = $iChunkSize;	
	}
	
	public function SetOutputFilePath($sDestFilePath)
	{
		$this->sOutputFilePath = $sDestFilePath;
	}
	
	public function SetAdvancedMode($bAdvanced)
	{
		$this->bAdvancedMode = $bAdvanced;
	}
	
	public function SaveState()
	{
		$aState = array(
			'state' => $this->sState,
			'statistics' => $this->aStatistics,
			'filter' => $this->oSearch->serialize(),
			'position' => $this->iPosition,
			'chunk_size' => $this->iChunkSize,
			'object_ids' => $this->aObjectsIDs,
			'output_file_path' => $this->sOutputFilePath,
			'advanced_mode' => $this->bAdvancedMode,
		);
		
		file_put_contents($this->GetStateFile(), json_encode($aState));
		
		return $this->sToken;
	}
	
	public function ReloadState()
	{
		if ($this->sToken == null)
		{
			throw new Exception('ExcelExporter not initialized with a token, cannot reload state');
		}
		
		if (!file_exists($this->GetStateFile()))
		{
			throw new Exception("ExcelExporter: missing status file '".$this->GetStateFile()."', cannot reload state.");
		}
		$sJson = file_get_contents($this->GetStateFile());
		$aState = json_decode($sJson, true);
		if ($aState === null)
		{
			throw new Exception("ExcelExporter:corrupted status file '".$this->GetStateFile()."', not a JSON, cannot reload state.");
		}
		
		$this->sState = $aState['state'];
		$this->aStatistics = $aState['statistics'];
		$this->oSearch = DBObjectSearch::unserialize($aState['filter']);
		$this->iPosition = $aState['position'];
		$this->iChunkSize = $aState['chunk_size'];
		$this->aObjectsIDs = $aState['object_ids'];
		$this->sOutputFilePath  = $aState['output_file_path'];
		$this->bAdvancedMode = $aState['advanced_mode'];
	}
	
	public function SetObjectList($oSearch)
	{
		$this->oSearch = $oSearch;
	}
	
	public function Run()
	{
		$sCode = 'error';
		$iPercentage = 100;
		$sMessage = Dict::Format('ExcelExporter:ErrorUnexpected_State', $this->sState);
		$fTime = microtime(true);
		
		try
		{
			switch($this->sState)
			{
				case 'new':
				$oIDSet = new DBObjectSet($this->oSearch);
				$oIDSet->OptimizeColumnLoad(array('id'));
				$this->aObjectsIDs = array();
				while($oObj = $oIDSet->Fetch())
				{
					$this->aObjectsIDs[] = $oObj->GetKey();
				}		
				$sCode = 'retrieving-data';
				$iPercentage = 5;
				$sMessage = Dict::S('ExcelExporter:RetrievingData');
				$this->iPosition = 0;
				$this->aStatistics['objects_count'] = count($this->aObjectsIDs);
				$this->aStatistics['data_retrieval_duration'] += microtime(true) - $fTime;
				
				// The first line of the file is the "headers" specifying the label and the type of each column
				$this->GetFieldsList($oIDSet, $this->bAdvancedMode);
				$sRow = json_encode($this->aTableHeaders);
				$hFile = @fopen($this->GetDataFile(), 'ab');
				if ($hFile === false)
				{
					throw new Exception('ExcelExporter: Failed to open temporary data file: "'.$this->GetDataFile().'" for writing.');
				}
				fwrite($hFile, $sRow."\n");
				fclose($hFile);	
				
				// Next state
				$this->sState = 'retrieving-data';
				break;
				
				case 'retrieving-data':
				$oCurrentSearch = clone $this->oSearch;
				$aIDs = array_slice($this->aObjectsIDs, $this->iPosition, $this->iChunkSize);
				
				$oCurrentSearch->AddCondition('id', $aIDs, 'IN');
				$hFile = @fopen($this->GetDataFile(), 'ab');
				if ($hFile === false)
				{
					throw new Exception('ExcelExporter: Failed to open temporary data file: "'.$this->GetDataFile().'" for writing.');
				}
				$oSet = new DBObjectSet($oCurrentSearch);
				$this->GetFieldsList($oSet, $this->bAdvancedMode);
				while($aObjects = $oSet->FetchAssoc())
				{
					$aRow = array();
					foreach($this->aAuthorizedClasses as $sAlias => $sClassName)
					{
						$oObj = $aObjects[$sAlias];
						if ($this->bAdvancedMode)
						{
							$aRow[] = $oObj->GetKey();
						}
						foreach($this->aFieldsList[$sAlias] as $sAttCodeEx => $oAttDef)
						{
							$value = $oObj->Get($sAttCodeEx);
							if ($value instanceOf ormCaseLog)
							{
								// Extract the case log as text and remove the "===" which make Excel think that the cell contains a formula the next time you edit it!
								$sExcelVal = trim(preg_replace('/========== ([^=]+) ============/', '********** $1 ************', $value->GetText()));
							}
							else
							{
								$sExcelVal =  $oAttDef->GetEditValue($value, $oObj);					
							}
							$aRow[] = $sExcelVal;				
						}
					}
					$sRow = json_encode($aRow);
					fwrite($hFile, $sRow."\n");
				}
				fclose($hFile);
				
				if (($this->iPosition + $this->iChunkSize) > count($this->aObjectsIDs))
				{
					// Next state
					$this->sState = 'building-excel';
					$sCode = 'building-excel';
					$iPercentage = 80;
					$sMessage = Dict::S('ExcelExporter:BuildingExcelFile');
				}
				else
				{
					$sCode = 'retrieving-data';
					$this->iPosition += $this->iChunkSize;
					$iPercentage = 5 + round(75 * ($this->iPosition / count($this->aObjectsIDs)));
					$sMessage = Dict::S('ExcelExporter:RetrievingData');			
				}
				break;
				
				case 'building-excel':
				$hFile = @fopen($this->GetDataFile(), 'rb');
				if ($hFile === false)
				{
					throw new Exception('ExcelExporter: Failed to open temporary data file: "'.$this->GetDataFile().'" for reading.');
				}
				$sHeaders = fgets($hFile);
				$aHeaders = json_decode($sHeaders, true);
				
				$aData = array();
				while($sLine = fgets($hFile))
				{
					$aRow = json_decode($sLine);
					$aData[] = $aRow;
				}
				fclose($hFile);
				@unlink($this->GetDataFile());
					
				$fStartExcel = microtime(true);
				$writer = new XLSXWriter();
				$writer->setAuthor(UserRights::GetUserFriendlyName());
				$writer->writeSheet($aData,'Sheet1', $aHeaders);
				$fExcelTime = microtime(true) - $fStartExcel;
				$this->aStatistics['excel_build_duration'] = $fExcelTime;
				
				$fTime = microtime(true);
				$writer->writeToFile($this->GetExcelFilePath());
				$fExcelSaveTime = microtime(true) - $fTime;
				$this->aStatistics['excel_write_duration'] = $fExcelSaveTime;
				
				// Next state
				$this->sState = 'done';
				$sCode = 'done';
				$iPercentage = 100;
				$sMessage = Dict::S('ExcelExporter:Done');
				break;
				
				case 'done':
				$this->sState = 'done';
				$sCode = 'done';
				$iPercentage = 100;
				$sMessage = Dict::S('ExcelExporter:Done');
				break;
			}
		}
		catch(Exception $e)
		{
			$sCode = 'error';
			$sMessage = $e->getMessage();
		}
		
		$this->aStatistics['total_duration'] += microtime(true) - $fTime;
		$peak_memory = memory_get_peak_usage(true);
		if ($peak_memory > $this->aStatistics['peak_memory_usage'])
		{
			$this->aStatistics['peak_memory_usage'] = $peak_memory;
		}
		
		return array(
			'code' => $sCode,
			'message' => $sMessage,
			'percentage' => $iPercentage,
		);
	}
	
	public function GetExcelFilePath()
	{
		if ($this->sOutputFilePath == null)
		{
			return APPROOT.'data/bulk_export/'.$this->sToken.'.xlsx';
		}
		else
		{
			return $this->sOutputFilePath;
		}
	}
	
	public static function GetExcelFileFromToken($sToken)
	{
		return @file_get_contents(APPROOT.'data/bulk_export/'.$sToken.'.xlsx');
	}
	
	public static function CleanupFromToken($sToken)
	{
		@unlink(APPROOT.'data/bulk_export/'.$sToken.'.status');
		@unlink(APPROOT.'data/bulk_export/'.$sToken.'.data');
		@unlink(APPROOT.'data/bulk_export/'.$sToken.'.xlsx');
	}
	
	public function Cleanup()
	{
		self::CleanupFromToken($this->sToken);
	}
	
	/**
	 * Delete all files in the data/bulk_export directory which are older than 1 day
	 * unless a different delay is configured.
	 */
	public static function CleanupOldFiles()
	{
		$aFiles = glob(APPROOT.'data/bulk_export/*.*');
		$iDelay = MetaModel::GetConfig()->Get('xlsx_exporter_cleanup_old_files_delay');
		
		if($iDelay > 0)
		{
			foreach($aFiles as $sFile)
			{
				$iModificationTime = filemtime($sFile);
				
				if($iModificationTime < (time() - $iDelay))
				{
					// Temporary files older than one day are deleted
					//echo "Supposed to delete: '".$sFile." (Unix Modification Time: $iModificationTime)'\n";
					@unlink($sFile);
				}
			}
		}
	}
	
	public function DisplayStatistics(Page $oPage)
	{
		$aStats = array(
				'Number of objects exported' => $this->aStatistics['objects_count'],
				'Total export duration' => sprintf('%.3f s', $this->aStatistics['total_duration']),
				'Data retrieval duration' => sprintf('%.3f s', $this->aStatistics['data_retrieval_duration']),
				'Excel build duration' => sprintf('%.3f s', $this->aStatistics['excel_build_duration']),
				'Excel write duration' => sprintf('%.3f s', $this->aStatistics['excel_write_duration']),
				'Peak memory usage' => self::HumanDisplay($this->aStatistics['peak_memory_usage']),
		);
		
		if ($oPage instanceof CLIPage)
		{
			$oPage->add($this->GetStatistics('text'));
		}
		else
		{
			$oPage->add($this->GetStatistics('html'));
		}
	}
	
	public function GetStatistics($sFormat = 'html')
	{
		$sStats = '';
		$aStats = array(
				'Number of objects exported' => $this->aStatistics['objects_count'],
				'Total export duration' => sprintf('%.3f s', $this->aStatistics['total_duration']),
				'Data retrieval duration' => sprintf('%.3f s', $this->aStatistics['data_retrieval_duration']),
				'Excel build duration' => sprintf('%.3f s', $this->aStatistics['excel_build_duration']),
				'Excel write duration' => sprintf('%.3f s', $this->aStatistics['excel_write_duration']),
				'Peak memory usage' => self::HumanDisplay($this->aStatistics['peak_memory_usage']),
		);
		
		if ($sFormat == 'text')
		{
			foreach($aStats as $sLabel => $sValue)
			{
				$sStats .= "+------------------------------+----------+\n";
				$sStats .= sprintf("|%-30s|%10s|\n", $sLabel, $sValue);
			}
			$sStats .= "+------------------------------+----------+";
		}
		else
		{
			$sStats .= '<table><tbody>';
			foreach($aStats as $sLabel => $sValue)
			{
				$sStats .= "<tr><td>$sLabel</td><td>$sValue</td></tr>";
			}
			$sStats .= '</tbody></table>';
			
		}
		return $sStats;
	}
	
	public static function HumanDisplay($iSize)
	{
		$aUnits = array('B','KB','MB','GB','TB','PB');
		return @round($iSize/pow(1024,($i=floor(log($iSize,1024)))),2).' '.$aUnits[$i];
	}
	
	protected function CheckDataDir()
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
	}
	
	protected function GetStateFile($sToken = null)
	{
		if ($sToken == null)
		{
			$sToken = $this->sToken;
		}
		return APPROOT."data/bulk_export/$sToken.status";
	}
	
	protected function GetDataFile()
	{
		return APPROOT.'data/bulk_export/'.$this->sToken.'.data';
	}
	
	protected function GetNewToken()
	{
		$iNum = rand();
		do
		{
			$iNum++;
			$sToken = sprintf("%08x", $iNum);
			$sFileName = $this->GetStateFile($sToken);
			$hFile = @fopen($sFileName, 'x');
		}
		while($hFile === false);
		
		fclose($hFile);
		return $sToken;
	}
	
	protected function GetFieldsList($oSet, $bFieldsAdvanced = false, $bLocalize = true, $aFields = null)
	{
		$this->aFieldsList = array();
	
		$oAppContext = new ApplicationContext();
		$aClasses = $oSet->GetFilter()->GetSelectedClasses();
		$this->aAuthorizedClasses = array();
		foreach($aClasses as $sAlias => $sClassName)
		{
			if (UserRights::IsActionAllowed($sClassName, UR_ACTION_READ, $oSet) && (UR_ALLOWED_YES || UR_ALLOWED_DEPENDS))
			{
				$this->aAuthorizedClasses[$sAlias] = $sClassName;
			}
		}
		$aAttribs = array();
		$this->aTableHeaders = array();
		foreach($this->aAuthorizedClasses as $sAlias => $sClassName)
		{
			$aList[$sAlias] = array();
	
			foreach(MetaModel::ListAttributeDefs($sClassName) as $sAttCode => $oAttDef)
			{
				if (is_null($aFields) || (count($aFields) == 0))
				{
					// Standard list of attributes (no link sets)
					if ($oAttDef->IsScalar() && ($oAttDef->IsWritable() || $oAttDef->IsExternalField()))
					{
						$sAttCodeEx = $oAttDef->IsExternalField() ? $oAttDef->GetKeyAttCode().'->'.$oAttDef->GetExtAttCode() : $sAttCode;
						
						if ($oAttDef->IsExternalKey(EXTKEY_ABSOLUTE))
						{
							if ($bFieldsAdvanced)
							{
								$aList[$sAlias][$sAttCodeEx] = $oAttDef;
	
								if ($oAttDef->IsExternalKey(EXTKEY_RELATIVE))
								{
							  		$sRemoteClass = $oAttDef->GetTargetClass();
									foreach(MetaModel::GetReconcKeys($sRemoteClass) as $sRemoteAttCode)
								  	{
										$this->aFieldsList[$sAlias][$sAttCode.'->'.$sRemoteAttCode] = MetaModel::GetAttributeDef($sRemoteClass, $sRemoteAttCode);
								  	}
								}
							}
						}
						else
						{
							// Any other attribute
							$this->aFieldsList[$sAlias][$sAttCodeEx] = $oAttDef;
						}
					}
				}
				else
				{
					// User defined list of attributes
					if (in_array($sAttCode, $aFields) || in_array($sAlias.'.'.$sAttCode, $aFields))
					{
						$this->aFieldsList[$sAlias][$sAttCode] = $oAttDef;
					}
				}
			}
			if ($bFieldsAdvanced)
			{
				$this->aTableHeaders['id'] = '0';
			}
			foreach($this->aFieldsList[$sAlias] as $sAttCodeEx => $oAttDef)
			{
				$sLabel = $bLocalize ? MetaModel::GetLabel($sClassName, $sAttCodeEx, isset($aParams['showMandatoryFields'])) : $sAttCodeEx;
				if($oAttDef instanceof AttributeDateTime)
				{
					$this->aTableHeaders[$sLabel] = 'datetime';
				}
				else
				{
					$this->aTableHeaders[$sLabel] = 'string';
				}
			}
		}
	}
}

