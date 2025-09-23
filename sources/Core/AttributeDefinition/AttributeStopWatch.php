<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use CMDBChangeOpSetAttributeScalar;
use CoreException;
use DateTime;
use DBObject;
use Dict;
use Exception;
use MetaModel;
use ormStopWatch;
use Str;

/**
 * A stop watch is an ormStopWatch object, it is stored as several columns in the database
 *
 * @package     iTopORM
 */
class AttributeStopWatch extends AttributeDefinition
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
		// The list of thresholds must be an array of iPercent => array of 'option' => value
		return array_merge(parent::ListExpectedParams(),
			array("states", "goal_computing", "working_time_computing", "thresholds"));
	}

	public function GetEditClass()
	{
		return "StopWatch";
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
		return $this->NewStopWatch();
	}

	/**
	 * @param ormStopWatch $value
	 * @param DBObject $oHostObj
	 *
	 * @return string
	 */
	public function GetEditValue($value, $oHostObj = null)
	{
		return $value->GetTimeSpent();
	}

	public function GetStates()
	{
		return $this->Get('states');
	}

	public function AlwaysLoadInTables()
	{
		// Each and every stop watch is accessed for computing the highlight code (DBObject::GetHighlightCode())
		return true;
	}

	/**
	 * Construct a brand new (but configured) stop watch
	 */
	public function NewStopWatch()
	{
		$oSW = new ormStopWatch();
		foreach ($this->ListThresholds() as $iThreshold => $aFoo) {
			$oSW->DefineThreshold($iThreshold);
		}

		return $oSW;
	}

	// Facilitate things: allow the user to Set the value from a string
	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if (!$proposedValue instanceof ormStopWatch) {
			return $this->NewStopWatch();
		}

		return $proposedValue;
	}

	public function GetSQLExpressions($sPrefix = '')
	{
		if ($sPrefix == '') {
			$sPrefix = $this->GetCode(); // Warning: a stopwatch does not have any 'sql' property, so its SQL column is equal to its attribute code !!
		}
		$aColumns = array();
		// Note: to optimize things, the existence of the attribute is determined by the existence of one column with an empty suffix
		$aColumns[''] = $sPrefix.'_timespent';
		$aColumns['_started'] = $sPrefix.'_started';
		$aColumns['_laststart'] = $sPrefix.'_laststart';
		$aColumns['_stopped'] = $sPrefix.'_stopped';
		foreach ($this->ListThresholds() as $iThreshold => $aFoo) {
			$sThPrefix = '_'.$iThreshold;
			$aColumns[$sThPrefix.'_deadline'] = $sPrefix.$sThPrefix.'_deadline';
			$aColumns[$sThPrefix.'_passed'] = $sPrefix.$sThPrefix.'_passed';
			$aColumns[$sThPrefix.'_triggered'] = $sPrefix.$sThPrefix.'_triggered';
			$aColumns[$sThPrefix.'_overrun'] = $sPrefix.$sThPrefix.'_overrun';
		}

		return $aColumns;
	}

	public static function DateToSeconds($sDate)
	{
		if (is_null($sDate)) {
			return null;
		}
		$oDateTime = new DateTime($sDate);
		$iSeconds = $oDateTime->format('U');

		return $iSeconds;
	}

	public static function SecondsToDate($iSeconds)
	{
		if (is_null($iSeconds)) {
			return null;
		}

		return date("Y-m-d H:i:s", $iSeconds);
	}

	public function FromSQLToValue($aCols, $sPrefix = '')
	{
		$aExpectedCols = array($sPrefix, $sPrefix.'_started', $sPrefix.'_laststart', $sPrefix.'_stopped');
		foreach ($this->ListThresholds() as $iThreshold => $aFoo) {
			$sThPrefix = '_'.$iThreshold;
			$aExpectedCols[] = $sPrefix.$sThPrefix.'_deadline';
			$aExpectedCols[] = $sPrefix.$sThPrefix.'_passed';
			$aExpectedCols[] = $sPrefix.$sThPrefix.'_triggered';
			$aExpectedCols[] = $sPrefix.$sThPrefix.'_overrun';
		}
		foreach ($aExpectedCols as $sExpectedCol) {
			if (!array_key_exists($sExpectedCol, $aCols)) {
				$sAvailable = implode(', ', array_keys($aCols));
				throw new MissingColumnException("Missing column '$sExpectedCol' from {$sAvailable}");
			}
		}

		$value = new ormStopWatch(
			$aCols[$sPrefix],
			self::DateToSeconds($aCols[$sPrefix.'_started']),
			self::DateToSeconds($aCols[$sPrefix.'_laststart']),
			self::DateToSeconds($aCols[$sPrefix.'_stopped'])
		);

		foreach ($this->ListThresholds() as $iThreshold => $aDefinition) {
			$sThPrefix = '_'.$iThreshold;
			$value->DefineThreshold(
				$iThreshold,
				self::DateToSeconds($aCols[$sPrefix.$sThPrefix.'_deadline']),
				(bool)($aCols[$sPrefix.$sThPrefix.'_passed'] == 1),
				(bool)($aCols[$sPrefix.$sThPrefix.'_triggered'] == 1),
				$aCols[$sPrefix.$sThPrefix.'_overrun'],
				array_key_exists('highlight', $aDefinition) ? $aDefinition['highlight'] : null
			);
		}

		return $value;
	}

	public function GetSQLValues($value)
	{
		if ($value instanceof ormStopWatch) {
			$aValues = array();
			$aValues[$this->GetCode().'_timespent'] = $value->GetTimeSpent();
			$aValues[$this->GetCode().'_started'] = self::SecondsToDate($value->GetStartDate());
			$aValues[$this->GetCode().'_laststart'] = self::SecondsToDate($value->GetLastStartDate());
			$aValues[$this->GetCode().'_stopped'] = self::SecondsToDate($value->GetStopDate());

			foreach ($this->ListThresholds() as $iThreshold => $aFoo) {
				$sPrefix = $this->GetCode().'_'.$iThreshold;
				$aValues[$sPrefix.'_deadline'] = self::SecondsToDate($value->GetThresholdDate($iThreshold));
				$aValues[$sPrefix.'_passed'] = $value->IsThresholdPassed($iThreshold) ? '1' : '0';
				$aValues[$sPrefix.'_triggered'] = $value->IsThresholdTriggered($iThreshold) ? '1' : '0';
				$aValues[$sPrefix.'_overrun'] = $value->GetOverrun($iThreshold);
			}
		} else {
			$aValues = array();
			$aValues[$this->GetCode().'_timespent'] = '';
			$aValues[$this->GetCode().'_started'] = '';
			$aValues[$this->GetCode().'_laststart'] = '';
			$aValues[$this->GetCode().'_stopped'] = '';
		}

		return $aValues;
	}

	public function GetSQLColumns($bFullSpec = false)
	{
		$aColumns = array();
		$aColumns[$this->GetCode().'_timespent'] = 'INT(11) UNSIGNED';
		$aColumns[$this->GetCode().'_started'] = 'DATETIME';
		$aColumns[$this->GetCode().'_laststart'] = 'DATETIME';
		$aColumns[$this->GetCode().'_stopped'] = 'DATETIME';
		foreach ($this->ListThresholds() as $iThreshold => $aFoo) {
			$sPrefix = $this->GetCode().'_'.$iThreshold;
			$aColumns[$sPrefix.'_deadline'] = 'DATETIME';
			$aColumns[$sPrefix.'_passed'] = 'TINYINT(1) UNSIGNED';
			$aColumns[$sPrefix.'_triggered'] = 'TINYINT(1)';
			$aColumns[$sPrefix.'_overrun'] = 'INT(11) UNSIGNED';
		}

		return $aColumns;
	}

	public function GetMagicFields()
	{
		$aRes = [
			$this->GetCode().'_started',
			$this->GetCode().'_laststart',
			$this->GetCode().'_stopped',
		];
		foreach ($this->ListThresholds() as $iThreshold => $aFoo) {
			$sPrefix = $this->GetCode().'_'.$iThreshold;
			$aRes[] = $sPrefix.'_deadline';
			$aRes[] = $sPrefix.'_passed';
			$aRes[] = $sPrefix.'_triggered';
			$aRes[] = $sPrefix.'_overrun';
		}

		return $aRes;
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

	/**
	 * @param ormStopWatch $value
	 * @param DBObject $oHostObject
	 * @param bool $bLocalize
	 *
	 * @return string
	 */
	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		if (is_object($value)) {
			return $value->GetAsHTML($this, $oHostObject);
		}

		return '';
	}

	/**
	 * @param ormStopWatch $value
	 * @param string $sSeparator
	 * @param string $sTextQualifier
	 * @param null $oHostObject
	 * @param bool $bLocalize
	 * @param bool $bConvertToPlainText
	 *
	 * @return string
	 */
	public function GetAsCSV(
		$value, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
		$bConvertToPlainText = false
	)
	{
		return $value->GetTimeSpent();
	}

	/**
	 * @param ormStopWatch $value
	 * @param DBObject $oHostObject
	 * @param bool $bLocalize
	 *
	 * @return mixed
	 */
	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		return $value->GetTimeSpent();
	}

	public function ListThresholds()
	{
		return $this->Get('thresholds');
	}

	public function Fingerprint($value)
	{
		$sFingerprint = '';
		if (is_object($value)) {
			$sFingerprint = $value->GetAsHTML($this);
		}

		return $sFingerprint;
	}

	/**
	 * To expose internal values: Declare an attribute AttributeSubItem
	 * and implement the GetSubItemXXXX verbs
	 *
	 * @param string $sItemCode
	 *
	 * @return array
	 * @throws CoreException
	 */
	public function GetSubItemSQLExpression($sItemCode)
	{
		$sPrefix = $this->GetCode();
		switch ($sItemCode) {
			case 'timespent':
				return array('' => $sPrefix.'_timespent');
			case 'started':
				return array('' => $sPrefix.'_started');
			case 'laststart':
				return array('' => $sPrefix.'_laststart');
			case 'stopped':
				return array('' => $sPrefix.'_stopped');
		}

		foreach ($this->ListThresholds() as $iThreshold => $aFoo) {
			$sThPrefix = $iThreshold.'_';
			if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix) {
				// The current threshold is concerned
				$sThresholdCode = substr($sItemCode, strlen($sThPrefix));
				switch ($sThresholdCode) {
					case 'deadline':
						return array('' => $sPrefix.'_'.$iThreshold.'_deadline');
					case 'passed':
						return array('' => $sPrefix.'_'.$iThreshold.'_passed');
					case 'triggered':
						return array('' => $sPrefix.'_'.$iThreshold.'_triggered');
					case 'overrun':
						return array('' => $sPrefix.'_'.$iThreshold.'_overrun');
				}
			}
		}
		throw new CoreException("Unknown item code '$sItemCode' for attribute ".$this->GetHostClass().'::'.$this->GetCode());
	}

	/**
	 * @param string $sItemCode
	 * @param ormStopWatch $value
	 * @param DBObject $oHostObject
	 *
	 * @return mixed
	 * @throws CoreException
	 */
	public function GetSubItemValue($sItemCode, $value, $oHostObject = null)
	{
		$oStopWatch = $value;
		switch ($sItemCode) {
			case 'timespent':
				return $oStopWatch->GetTimeSpent();
			case 'started':
				return $oStopWatch->GetStartDate();
			case 'laststart':
				return $oStopWatch->GetLastStartDate();
			case 'stopped':
				return $oStopWatch->GetStopDate();
		}

		foreach ($this->ListThresholds() as $iThreshold => $aFoo) {
			$sThPrefix = $iThreshold.'_';
			if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix) {
				// The current threshold is concerned
				$sThresholdCode = substr($sItemCode, strlen($sThPrefix));
				switch ($sThresholdCode) {
					case 'deadline':
						return $oStopWatch->GetThresholdDate($iThreshold);
					case 'passed':
						return $oStopWatch->IsThresholdPassed($iThreshold);
					case 'triggered':
						return $oStopWatch->IsThresholdTriggered($iThreshold);
					case 'overrun':
						return $oStopWatch->GetOverrun($iThreshold);
				}
			}
		}

		throw new CoreException("Unknown item code '$sItemCode' for attribute ".$this->GetHostClass().'::'.$this->GetCode());
	}


	public function GetSubItemSearchType($sItemCode)
	{
		switch ($sItemCode) {
			case 'timespent':
				return static::SEARCH_WIDGET_TYPE_NUMERIC;  //seconds
			case 'started':
			case 'laststart':
			case 'stopped':
				return static::SEARCH_WIDGET_TYPE_DATE_TIME; //timestamp
		}

		foreach ($this->ListThresholds() as $iThreshold => $aFoo) {
			$sThPrefix = $iThreshold.'_';
			if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix) {
				// The current threshold is concerned
				$sThresholdCode = substr($sItemCode, strlen($sThPrefix));
				switch ($sThresholdCode) {
					case 'deadline':
						return static::SEARCH_WIDGET_TYPE_DATE_TIME; //timestamp
					case 'passed':
					case 'triggered':
						return static::SEARCH_WIDGET_TYPE_ENUM; //booleans, used in conjuction with GetSubItemAllowedValues and IsSubItemNullAllowed
					case 'overrun':
						return static::SEARCH_WIDGET_TYPE_NUMERIC; //seconds
				}
			}
		}

		return static::SEARCH_WIDGET_TYPE_RAW;
	}

	public function GetSubItemAllowedValues($sItemCode, $aArgs = array(), $sContains = '')
	{
		foreach ($this->ListThresholds() as $iThreshold => $aFoo) {
			$sThPrefix = $iThreshold.'_';
			if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix) {
				// The current threshold is concerned
				$sThresholdCode = substr($sItemCode, strlen($sThPrefix));
				switch ($sThresholdCode) {
					case 'passed':
					case 'triggered':
						return array(
							0 => $this->GetBooleanLabel(0),
							1 => $this->GetBooleanLabel(1),
						);
				}
			}
		}

		return null;
	}

	public function IsSubItemNullAllowed($sItemCode, $bDefaultValue)
	{
		foreach ($this->ListThresholds() as $iThreshold => $aFoo) {
			$sThPrefix = $iThreshold.'_';
			if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix) {
				// The current threshold is concerned
				$sThresholdCode = substr($sItemCode, strlen($sThPrefix));
				switch ($sThresholdCode) {
					case 'passed':
					case 'triggered':
						return false;
				}
			}
		}

		return $bDefaultValue;
	}

	protected function GetBooleanLabel($bValue)
	{
		$sDictKey = $bValue ? 'yes' : 'no';

		return Dict::S('BooleanLabel:'.$sDictKey, 'def:'.$sDictKey);
	}

	public function GetSubItemAsHTMLForHistory($sItemCode, $sValue)
	{
		$sHtml = null;
		switch ($sItemCode) {
			case 'timespent':
				$sHtml = (int)$sValue ? Str::pure2html(AttributeDuration::FormatDuration($sValue)) : null;
				break;
			case 'started':
			case 'laststart':
			case 'stopped':
				$sHtml = (int)$sValue ? date((string)AttributeDateTime::GetFormat(), (int)$sValue) : null;
				break;

			default:
				foreach ($this->ListThresholds() as $iThreshold => $aFoo) {
					$sThPrefix = $iThreshold.'_';
					if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix) {
						// The current threshold is concerned
						$sThresholdCode = substr($sItemCode, strlen($sThPrefix));
						switch ($sThresholdCode) {
							case 'deadline':
								$sHtml = (int)$sValue ? date((string)AttributeDateTime::GetFormat(),
									(int)$sValue) : null;
								break;
							case 'passed':
							case 'triggered':
								$sHtml = $this->GetBooleanLabel((int)$sValue);
								break;
							case 'overrun':
								$sHtml = (int)$sValue > 0 ? Str::pure2html(AttributeDuration::FormatDuration((int)$sValue)) : '';
						}
					}
				}
		}

		return $sHtml;
	}

	public function GetSubItemAsPlainText($sItemCode, $value)
	{
		$sRet = $value;

		switch ($sItemCode) {
			case 'timespent':
				$sRet = AttributeDuration::FormatDuration($value);
				break;
			case 'started':
			case 'laststart':
			case 'stopped':
				if (is_null($value)) {
					$sRet = ''; // Undefined
				} else {
					$oDateTime = new DateTime();
					$oDateTime->setTimestamp($value);
					$oDateTimeFormat = AttributeDateTime::GetFormat();
					$sRet = $oDateTimeFormat->Format($oDateTime);
				}
				break;

			default:
				foreach ($this->ListThresholds() as $iThreshold => $aFoo) {
					$sThPrefix = $iThreshold.'_';
					if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix) {
						// The current threshold is concerned
						$sThresholdCode = substr($sItemCode, strlen($sThPrefix));
						switch ($sThresholdCode) {
							case 'deadline':
								if ($value) {
									if (is_int($value)) {
										$sDate = date(AttributeDateTime::GetInternalFormat(), $value);
										$sRet = AttributeDeadline::FormatDeadline($sDate);
									} else {
										$sRet = $value;
									}
								} else {
									$sRet = '';
								}
								break;
							case 'passed':
							case 'triggered':
								$sRet = $this->GetBooleanLabel($value);
								break;
							case 'overrun':
								$sRet = AttributeDuration::FormatDuration($value);
								break;
						}
					}
				}
		}

		return $sRet;
	}

	public function GetSubItemAsHTML($sItemCode, $value)
	{
		$sHtml = $value;

		switch ($sItemCode) {
			case 'timespent':
				$sHtml = Str::pure2html(AttributeDuration::FormatDuration($value));
				break;
			case 'started':
			case 'laststart':
			case 'stopped':
				if (is_null($value)) {
					$sHtml = ''; // Undefined
				} else {
					$oDateTime = new DateTime();
					$oDateTime->setTimestamp($value);
					$oDateTimeFormat = AttributeDateTime::GetFormat();
					$sHtml = Str::pure2html($oDateTimeFormat->Format($oDateTime));
				}
				break;

			default:
				foreach ($this->ListThresholds() as $iThreshold => $aFoo) {
					$sThPrefix = $iThreshold.'_';
					if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix) {
						// The current threshold is concerned
						$sThresholdCode = substr($sItemCode, strlen($sThPrefix));
						switch ($sThresholdCode) {
							case 'deadline':
								if ($value) {
									$sDate = date(AttributeDateTime::GetInternalFormat(), $value);
									$sHtml = Str::pure2html(AttributeDeadline::FormatDeadline($sDate));
								} else {
									$sHtml = '';
								}
								break;
							case 'passed':
							case 'triggered':
								$sHtml = $this->GetBooleanLabel($value);
								break;
							case 'overrun':
								$sHtml = Str::pure2html(AttributeDuration::FormatDuration($value));
								break;
						}
					}
				}
		}

		return $sHtml;
	}

	public function GetSubItemAsCSV(
		$sItemCode, $value, $sSeparator = ',', $sTextQualifier = '"', $bConvertToPlainText = false
	)
	{
		$sFrom = array("\r\n", $sTextQualifier);
		$sTo = array("\n", $sTextQualifier.$sTextQualifier);
		$sEscaped = str_replace($sFrom, $sTo, (string)$value);
		$sRet = $sTextQualifier.$sEscaped.$sTextQualifier;

		switch ($sItemCode) {
			case 'timespent':
				$sRet = $sTextQualifier.AttributeDuration::FormatDuration($value).$sTextQualifier;
				break;
			case 'started':
			case 'laststart':
			case 'stopped':
				if ($value !== null) {
					$oDateTime = new DateTime();
					$oDateTime->setTimestamp($value);
					$oDateTimeFormat = AttributeDateTime::GetFormat();
					$sRet = $sTextQualifier.$oDateTimeFormat->Format($oDateTime).$sTextQualifier;
				}
				break;

			default:
				foreach ($this->ListThresholds() as $iThreshold => $aFoo) {
					$sThPrefix = $iThreshold.'_';
					if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix) {
						// The current threshold is concerned
						$sThresholdCode = substr($sItemCode, strlen($sThPrefix));
						switch ($sThresholdCode) {
							case 'deadline':
								if ($value != '') {
									$oDateTime = new DateTime();
									$oDateTime->setTimestamp($value);
									$oDateTimeFormat = AttributeDateTime::GetFormat();
									$sRet = $sTextQualifier.$oDateTimeFormat->Format($oDateTime).$sTextQualifier;
								}
								break;

							case 'passed':
							case 'triggered':
								$sRet = $sTextQualifier.$this->GetBooleanLabel($value).$sTextQualifier;
								break;

							case 'overrun':
								$sRet = $sTextQualifier.AttributeDuration::FormatDuration($value).$sTextQualifier;
								break;
						}
					}
				}
		}

		return $sRet;
	}

	public function GetSubItemAsXML($sItemCode, $value)
	{
		$sRet = Str::pure2xml((string)$value);

		switch ($sItemCode) {
			case 'timespent':
			case 'started':
			case 'laststart':
			case 'stopped':
				break;

			default:
				foreach ($this->ListThresholds() as $iThreshold => $aFoo) {
					$sThPrefix = $iThreshold.'_';
					if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix) {
						// The current threshold is concerned
						$sThresholdCode = substr($sItemCode, strlen($sThPrefix));
						switch ($sThresholdCode) {
							case 'deadline':
							case 'overrun':
								break;

							case 'triggered':
							case 'passed':
								$sRet = $this->GetBooleanLabel($value);
								break;
						}
					}
				}
		}

		return $sRet;
	}

	/**
	 * Implemented for the HTML spreadsheet format!
	 *
	 * @param string $sItemCode
	 * @param ormStopWatch $value
	 *
	 * @return false|string
	 */
	public function GetSubItemAsEditValue($sItemCode, $value)
	{
		$sRet = $value;

		switch ($sItemCode) {
			case 'timespent':
				break;

			case 'started':
			case 'laststart':
			case 'stopped':
				if (is_null($value)) {
					$sRet = ''; // Undefined
				} else {
					$sRet = date((string)AttributeDateTime::GetFormat(), $value);
				}
				break;

			default:
				foreach ($this->ListThresholds() as $iThreshold => $aFoo) {
					$sThPrefix = $iThreshold.'_';
					if (substr($sItemCode, 0, strlen($sThPrefix)) == $sThPrefix) {
						// The current threshold is concerned
						$sThresholdCode = substr($sItemCode, strlen($sThPrefix));
						switch ($sThresholdCode) {
							case 'deadline':
								if ($value) {
									$sRet = date((string)AttributeDateTime::GetFormat(), $value);
								} else {
									$sRet = '';
								}
								break;
							case 'passed':
							case 'triggered':
								$sRet = $this->GetBooleanLabel($value);
								break;
							case 'overrun':
								break;
						}
					}
				}
		}

		return $sRet;
	}

	/**
	 * @inheritDoc
	 */
	public function HasAValue($proposedValue): bool
	{
		// A stopwatch always has a value
		return true;
	}

	public function RecordAttChange(DBObject $oObject, $original, $value): void
	{
		// Stop watches - record changes for sub items only (they are visible, the rest is not visible)
		//
		foreach ($this->ListSubItems() as $sSubItemAttCode => $oSubItemAttDef) {
			$item_value = $this->GetSubItemValue($oSubItemAttDef->Get('item_code'), $value, $oObject);
			$item_original = $this->GetSubItemValue($oSubItemAttDef->Get('item_code'), $original, $oObject);

			if ($item_value != $item_original) {
				$oMyChangeOp = MetaModel::NewObject(CMDBChangeOpSetAttributeScalar::class);
				$oMyChangeOp->Set("objclass", get_class($oObject));
				$oMyChangeOp->Set("objkey", $oObject->GetKey());
				$oMyChangeOp->Set("attcode", $sSubItemAttCode);

				$oMyChangeOp->Set("oldvalue", $item_original);
				$oMyChangeOp->Set("newvalue", $item_value);

				$oMyChangeOp->DBInsertNoReload();
			}
		}
	}
}