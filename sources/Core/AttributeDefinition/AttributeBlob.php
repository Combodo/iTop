<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use CMDBChangeOp;
use CMDBChangeOpSetAttributeBlob;
use CMDBSource;
use DBObject;
use Exception;
use IssueLog;
use ormDocument;
use utils;

/**
 * A blob is an ormDocument, it is stored as several columns in the database
 *
 * @package     iTopORM
 */
class AttributeBlob extends AttributeDefinition
{
	const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_RAW;

	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("depends_on"));
	}

	public function GetEditClass()
	{
		return "Document";
	}

	public static function IsBasedOnDBColumns()
	{
		return true;
	}

	public static function IsScalar()
	{
		return true;
	}

	public function IsWritable()
	{
		return true;
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		return new ormDocument('', '', '');
	}

	public function IsNullAllowed(DBObject $oHostObject = null)
	{
		return $this->GetOptional("is_null_allowed", false);
	}

	public function GetEditValue($sValue, $oHostObj = null)
	{
		return '';
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see AttributeDefinition::MakeRealValue()
	 *
	 * @param string $proposedValue Can be an URL (including an URL to iTop itself), or a local path (CSV import)
	 *
	 */
	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if ($proposedValue === null) {
			return null;
		}

		if (is_object($proposedValue)) {
			$proposedValue = clone $proposedValue;
		} else {
			try {
				// Read the file from iTop, an URL (or the local file system - for admins only)
				$proposedValue = utils::FileGetContentsAndMIMEType($proposedValue);
			}
			catch (Exception $e) {
				IssueLog::Warning(get_class($this)."::MakeRealValue - ".$e->getMessage());
				// Not a real document !! store is as text !!! (This was the default behavior before)
				$proposedValue = new ormDocument($e->getMessage()." \n".$proposedValue, 'text/plain');
			}
		}

		return $proposedValue;
	}

	public function GetSQLExpressions($sPrefix = '')
	{
		if ($sPrefix == '') {
			$sPrefix = $this->GetCode();
		}
		$aColumns = array();
		// Note: to optimize things, the existence of the attribute is determined by the existence of one column with an empty suffix
		$aColumns[''] = $sPrefix.'_mimetype';
		$aColumns['_data'] = $sPrefix.'_data';
		$aColumns['_filename'] = $sPrefix.'_filename';
		$aColumns['_downloads_count'] = $sPrefix.'_downloads_count';

		return $aColumns;
	}

	public function FromSQLToValue($aCols, $sPrefix = '')
	{
		if (!array_key_exists($sPrefix, $aCols)) {
			$sAvailable = implode(', ', array_keys($aCols));
			throw new MissingColumnException("Missing column '$sPrefix' from {$sAvailable}");
		}
		$sMimeType = isset($aCols[$sPrefix]) ? $aCols[$sPrefix] : '';

		if (!array_key_exists($sPrefix.'_data', $aCols)) {
			$sAvailable = implode(', ', array_keys($aCols));
			throw new MissingColumnException("Missing column '".$sPrefix."_data' from {$sAvailable}");
		}
		$data = isset($aCols[$sPrefix.'_data']) ? $aCols[$sPrefix.'_data'] : null;

		if (!array_key_exists($sPrefix.'_filename', $aCols)) {
			$sAvailable = implode(', ', array_keys($aCols));
			throw new MissingColumnException("Missing column '".$sPrefix."_filename' from {$sAvailable}");
		}
		$sFileName = isset($aCols[$sPrefix.'_filename']) ? $aCols[$sPrefix.'_filename'] : '';

		if (!array_key_exists($sPrefix.'_downloads_count', $aCols)) {
			$sAvailable = implode(', ', array_keys($aCols));
			throw new MissingColumnException("Missing column '".$sPrefix."_downloads_count' from {$sAvailable}");
		}
		$iDownloadsCount = isset($aCols[$sPrefix.'_downloads_count']) ? $aCols[$sPrefix.'_downloads_count'] : ormDocument::DEFAULT_DOWNLOADS_COUNT;

		$value = new ormDocument($data, $sMimeType, $sFileName, $iDownloadsCount);

		return $value;
	}

	public function GetSQLValues($value)
	{
		// #@# Optimization: do not load blobs anytime
		//	 As per mySQL doc, selecting blob columns will prevent mySQL from
		//	 using memory in case a temporary table has to be created
		//	 (temporary tables created on disk)
		//	 We will have to remove the blobs from the list of attributes when doing the select
		//	 then the use of Get() should finalize the load
		if ($value instanceof ormDocument) {
			$aValues = array();
			if (!$value->IsEmpty()) {
				$aValues[$this->GetCode().'_data'] = $value->GetData();
			} else {
				$aValues[$this->GetCode().'_data'] = '';
			}
			$aValues[$this->GetCode().'_mimetype'] = $value->GetMimeType();
			$aValues[$this->GetCode().'_filename'] = $value->GetFileName();
			$aValues[$this->GetCode().'_downloads_count'] = $value->GetDownloadsCount();
		} else {
			$aValues = array();
			$aValues[$this->GetCode().'_data'] = '';
			$aValues[$this->GetCode().'_mimetype'] = '';
			$aValues[$this->GetCode().'_filename'] = '';
			$aValues[$this->GetCode().'_downloads_count'] = ormDocument::DEFAULT_DOWNLOADS_COUNT;
		}

		return $aValues;
	}

	public function GetSQLColumns($bFullSpec = false)
	{
		$aColumns = array();
		$aColumns[$this->GetCode().'_data'] = 'LONGBLOB'; // 2^32 (4 Gb)
		$aColumns[$this->GetCode().'_mimetype'] = 'VARCHAR(255)'.CMDBSource::GetSqlStringColumnDefinition();
		$aColumns[$this->GetCode().'_filename'] = 'VARCHAR(255)'.CMDBSource::GetSqlStringColumnDefinition();
		$aColumns[$this->GetCode().'_downloads_count'] = 'INT(11) UNSIGNED';

		return $aColumns;
	}

	public function GetBasicFilterOperators()
	{
		return array();
	}

	public function GetBasicFilterLooseOperator()
	{
		return '=';
	}

	public function GetBasicFilterSQLExpr($sOpCode, $value)
	{
		return 'true';
	}

	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		if (is_object($value)) {
			return $value->GetAsHTML();
		}

		return '';
	}

	/**
	 * @param string $sValue
	 * @param string $sSeparator
	 * @param string $sTextQualifier
	 * @param DBObject $oHostObject
	 * @param bool $bLocalize
	 * @param bool $bConvertToPlainText
	 *
	 * @return string
	 */
	public function GetAsCSV(
		$sValue, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
		$bConvertToPlainText = false
	)
	{
		$sAttCode = $this->GetCode();
		if ($sValue instanceof ormDocument && !$sValue->IsEmpty()) {
			return $sValue->GetDownloadURL(get_class($oHostObject), $oHostObject->GetKey(), $sAttCode);
		}

		return ''; // Not exportable in CSV !
	}

	/**
	 * @param $value
	 * @param DBObject $oHostObject
	 * @param bool $bLocalize
	 *
	 * @return mixed|string
	 */
	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		$sRet = '';
		if (is_object($value)) {
			/** @var ormDocument $value */
			if (!$value->IsEmpty()) {
				$sRet = '<mimetype>'.$value->GetMimeType().'</mimetype>';
				$sRet .= '<filename>'.$value->GetFileName().'</filename>';
				$sRet .= '<data>'.base64_encode($value->GetData()).'</data>';
				$sRet .= '<downloads_count>'.$value->GetDownloadsCount().'</downloads_count>';
			}
		}

		return $sRet;
	}

	public function GetForJSON($value)
	{
		if ($value instanceof ormDocument) {
			$aValues = array();
			$aValues['data'] = base64_encode($value->GetData());
			$aValues['mimetype'] = $value->GetMimeType();
			$aValues['filename'] = $value->GetFileName();
			$aValues['downloads_count'] = $value->GetDownloadsCount();
		} else {
			$aValues = null;
		}

		return $aValues;
	}

	public function FromJSONToValue($json)
	{
		if (isset($json->data)) {
			$data = base64_decode($json->data);
			$value = new ormDocument($data, $json->mimetype, $json->filename, $json->downloads_count);
		} else {
			$value = null;
		}

		return $value;
	}

	public function Fingerprint($value)
	{
		$sFingerprint = '';
		if ($value instanceof ormDocument) {
			$sFingerprint = $value->GetSignature();
		}

		return $sFingerprint;
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\BlobField';
	}

	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		/** @var $oFormField BlobField */
		if ($oFormField === null) {
			$sFormFieldClass = static::GetFormFieldClass();
			$oFormField = new $sFormFieldClass($this->GetCode());
		}

		// Note: As of today we want this field to always be read-only
		$oFormField->SetReadOnly(true);

		// Calling parent before so current value is set, then proceed
		parent::MakeFormField($oObject, $oFormField);

		// Setting current value correctly as the default method returns an empty string when there is no file yet.
		/** @var ormDocument $value */
		$value = $oObject->Get($this->GetCode());
		if (!is_object($value)) {
			$oFormField->SetCurrentValue(new ormDocument());
		}

		// Generating urls
		if (is_object($value) && !$value->IsEmpty()) {
			$oFormField->SetDownloadUrl($value->GetDownloadURL(get_class($oObject), $oObject->GetKey(), $this->GetCode()));
			$oFormField->SetDisplayUrl($value->GetDisplayURL(get_class($oObject), $oObject->GetKey(), $this->GetCode()));
		}

		return $oFormField;
	}

	/**
	 * @inheritDoc
	 */
	public function HasAValue($proposedValue): bool
	{
		if (false === ($proposedValue instanceof ormDocument)) {
			return parent::HasAValue($proposedValue);
		}

		// Empty file (no content, just a filename) are supported since PR {@link https://github.com/Combodo/combodo-email-synchro/pull/17}, so we check for both empty content and empty filename to determine that a document has no value
		return utils::IsNotNullOrEmptyString($proposedValue->GetData()) && utils::IsNotNullOrEmptyString($proposedValue->GetFileName());
	}

	/**
	 * @inheritDoc
	 *
	 * @param ormDocument $original
	 * @param ormDocument $value
	 *
	 * @since N°6502
	 */
	public function RecordAttChange(DBObject $oObject, $original, $value): void
	{
		// N°6502 Don't record history if only the download count has changed
		if ((null !== $original) && (null !== $value) && $original->EqualsExceptDownloadsCount($value)) {
			return;
		}

		parent::RecordAttChange($oObject, $original, $value);
	}

	protected function GetChangeRecordAdditionalData(CMDBChangeOp $oMyChangeOp, DBObject $oObject, $original, $value): void
	{
		if (is_null($original)) {
			$original = new ormDocument();
		}
		$oMyChangeOp->Set("prevdata", $original);
	}

	protected function GetChangeRecordClassName(): string
	{
		return CMDBChangeOpSetAttributeBlob::class;
	}
}