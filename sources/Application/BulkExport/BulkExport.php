<?php

/**
 * Class BulkExport
 *
 * @copyright   Copyright (C) 2024 Combodo SAS
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
		$this->aStatusInfo = [
			'show_obsolete_data' => utils::ShowObsoleteData(),
		];
		$this->oBulkExportResult = null;
		$this->sTmpFile = '';
		$this->bLocalizeOutput = false;
	}

	/**
	 * Find the first class capable of exporting the data in the given format
	 *
	 * @param string $sFormatCode The lowercase format (e.g. html, csv, spreadsheet, xlsx, xml, json, pdf...)
	 * @param DBSearch $oSearch The search/filter defining the set of objects to export or null when listing the supported formats
	 *
	 * @return BulkExport|null
	 * @throws ReflectionException
	 */
	static public function FindExporter($sFormatCode, $oSearch = null)
	{
		foreach (get_declared_classes() as $sPHPClass) {
			$oRefClass = new ReflectionClass($sPHPClass);
			if ($oRefClass->isSubclassOf('BulkExport') && !$oRefClass->isAbstract()) {
				/** @var BulkExport $oBulkExporter */
				$oBulkExporter = new $sPHPClass();
				if ($oBulkExporter->IsFormatSupported($sFormatCode, $oSearch)) {
					if ($oSearch) {
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
	 *
	 * @param int $iPersistentToken The identifier of the BulkExportResult object storing the information
	 *
	 * @return BulkExport|null
	 * @throws ArchivedObjectException
	 * @throws CoreException
	 * @throws ReflectionException
	 */
	static public function FindExporterFromToken($iPersistentToken = null)
	{
		$oBulkExporter = null;
		$oInfo = MetaModel::GetObject('BulkExportResult', $iPersistentToken, false);
		if ($oInfo && ($oInfo->Get('user_id') == UserRights::GetUserId())) {
			$sFormatCode = $oInfo->Get('format');
			$aStatusInfo = json_decode($oInfo->Get('status_info'), true);

			$oSearch = DBObjectSearch::unserialize($oInfo->Get('search'));
			$oSearch->SetShowObsoleteData($aStatusInfo['show_obsolete_data']);
			$oBulkExporter = self::FindExporter($sFormatCode, $oSearch);
			if ($oBulkExporter) {
				$oBulkExporter->SetFormat($sFormatCode);
				$oBulkExporter->SetObjectList($oSearch);
				$oBulkExporter->SetChunkSize($oInfo->Get('chunk_size'));
				$oBulkExporter->SetStatusInfo($aStatusInfo);

				$oBulkExporter->SetLocalizeOutput($oInfo->Get('localize_output'));


				$oBulkExporter->sTmpFile = $oInfo->Get('temp_file_path');
				$oBulkExporter->oBulkExportResult = $oInfo;
			}
		}

		return $oBulkExporter;
	}

	/**
	 * @param $data
	 *
	 * @throws Exception
	 */
	public function AppendToTmpFile($data)
	{
		if ($this->sTmpFile == '') {
			$this->sTmpFile = $this->MakeTmpFile($this->GetFileExtension());
		}
		$hFile = fopen($this->sTmpFile, 'ab');
		if ($hFile !== false) {
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
	 *
	 * @return array :string
	 */
	static public function FindSupportedFormats()
	{
		$aSupportedFormats = array();
		foreach (get_declared_classes() as $sPHPClass) {
			$oRefClass = new ReflectionClass($sPHPClass);
			if ($oRefClass->isSubClassOf('BulkExport') && !$oRefClass->isAbstract()) {
				$oBulkExporter = new $sPHPClass;
				$aFormats = $oBulkExporter->GetSupportedFormats();
				$aSupportedFormats = array_merge($aSupportedFormats, $aFormats);
			}
		}

		return $aSupportedFormats;
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see iBulkExport::SetChunkSize()
	 */
	public function SetChunkSize($iChunkSize)
	{
		$this->iChunkSize = $iChunkSize;
	}

	/**
	 * @param $bLocalizeOutput
	 */
	public function SetLocalizeOutput($bLocalizeOutput)
	{
		$this->bLocalizeOutput = $bLocalizeOutput;
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see iBulkExport::SetObjectList()
	 */
	public function SetObjectList(DBSearch $oSearch)
	{
		$oSearch->SetShowObsoleteData($this->aStatusInfo['show_obsolete_data']);
		$this->oSearch = $oSearch;
	}

	public function SetFormat($sFormatCode)
	{
		$this->sFormatCode = $sFormatCode;
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see iBulkExport::IsFormatSupported()
	 */
	public function IsFormatSupported($sFormatCode, $oSearch = null)
	{
		return array_key_exists($sFormatCode, $this->GetSupportedFormats());
	}

	/**
	 * (non-PHPdoc)
	 *
	 * @see iBulkExport::GetSupportedFormats()
	 */
	public function GetSupportedFormats()
	{
		return array(); // return array('csv' => Dict::S('UI:ExportFormatCSV'));
	}


	public function SetHttpHeaders(\Combodo\iTop\Application\WebPage\WebPage $oPage)
	{
	}

	/**
	 * @return string
	 */
	public function GetHeader()
	{
		return '';
	}

	abstract public function GetNextChunk(&$aStatus);

	/**
	 * @return string
	 */
	public function GetFooter()
	{
		return '';
	}

	public function SaveState()
	{
		if ($this->oBulkExportResult === null) {
			$this->oBulkExportResult = new BulkExportResult();
			$this->oBulkExportResult->Set('format', $this->sFormatCode);
			$this->oBulkExportResult->Set('search', $this->oSearch->serialize());
			$this->oBulkExportResult->Set('chunk_size', $this->iChunkSize);
			$this->oBulkExportResult->Set('localize_output', $this->bLocalizeOutput);
		}
		$this->oBulkExportResult->Set('status_info', json_encode($this->GetStatusInfo()));
		$this->oBulkExportResult->Set('temp_file_path', $this->sTmpFile);
		utils::PushArchiveMode(false);
		$ret = $this->oBulkExportResult->DBWrite();
		utils::PopArchiveMode();

		return $ret;
	}

	public function Cleanup()
	{
		if (($this->oBulkExportResult && (!$this->oBulkExportResult->IsNew()))) {
			$sFilename = $this->oBulkExportResult->Get('temp_file_path');
			if ($sFilename != '') {
				@unlink($sFilename);
			}
			utils::PushArchiveMode(false);
			$this->oBulkExportResult->DBDelete();
			utils::PopArchiveMode();
		}
	}

	public function EnumFormParts()
	{
		return array();
	}

	/**
	 * @deprecated 3.0.0 use GetFormPart instead
	 */
	public function DisplayFormPart(\Combodo\iTop\Application\WebPage\WebPage $oP, $sPartId)
	{
		DeprecatedCallsLog::NotifyDeprecatedPhpMethod('use GetFormPart instead');
		$oP->AddSubBlock($this->GetFormPart($oP, $sPartId));
	}


	/**
	 * @param \Combodo\iTop\Application\WebPage\WebPage $oP
	 * @param $sPartId
	 *
	 * @return UIContentBlock
	 */
	public function GetFormPart(\Combodo\iTop\Application\WebPage\WebPage $oP, $sPartId)
	{
	}

	public function DisplayUsage(\Combodo\iTop\Application\WebPage\Page $oP)
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

	/**
	 * @return string
	 */
	public function GetMimeType()
	{
		return '';
	}

	/**
	 * @return string
	 */
	public function GetFileExtension()
	{
		return '';
	}

	public function GetCharacterSet()
	{
		return 'UTF-8';
	}

	public function GetStatistics()
	{

	}

	public function SetFields($sFields)
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

	/**
	 * @param $sExtension
	 *
	 * @return string
	 * @throws Exception
	 */
	protected function MakeTmpFile($sExtension)
	{
		if (!is_dir(utils::GetDataPath()."bulk_export")) {
			@mkdir(utils::GetDataPath()."bulk_export", 0777, true /* recursive */);
			clearstatcache();
		}
		if (!is_writable(utils::GetDataPath()."bulk_export")) {
			throw new Exception('Data directory "'.utils::GetDataPath().'bulk_export" could not be written.');
		}

		$iNum = rand();
		do {
			$iNum++;
			$sToken = sprintf("%08x", $iNum);
			$sFileName = utils::GetDataPath()."bulk_export/$sToken.".$sExtension;
			$hFile = @fopen($sFileName, 'x');
		} while ($hFile === false);

		fclose($hFile);

		return $sFileName;
	}
}