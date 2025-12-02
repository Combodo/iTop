<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use CMDBSource;
use Combodo\iTop\Application\UI\Links\Set\BlockLinkSetDisplayAsProperty;
use Combodo\iTop\Form\Field\LinkedSetField;
use Combodo\iTop\Renderer\Console\ConsoleBlockRenderer;
use Combodo\iTop\Service\Links\LinkSetModel;
use CoreException;
use CSVParser;
use DBObject;
use DBObjectSearch;
use DBObjectSet;
use Dict;
use Exception;
use ExceptionLog;
use IssueLog;
use MetaModel;
use ormLinkSet;
use ValueSetObjects;

/**
 * Set of objects directly linked to an object, and being part of its definition
 *
 * @package     iTopORM
 */
class AttributeLinkedSet extends AttributeDefinition
{
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
		$this->aCSSClasses[] = 'attribute-set';
	}

	public static function ListExpectedParams()
	{
		return array_merge(
			parent::ListExpectedParams(),
			["allowed_values", "depends_on", "linked_class", "ext_key_to_me", "count_min", "count_max"]
		);
	}

	public function GetEditClass()
	{
		return "LinkedSet";
	}

	/** @inheritDoc */
	public static function IsBulkModifyCompatible(): bool
	{
		return false;
	}

	public function IsWritable()
	{
		return true;
	}

	public static function IsLinkSet()
	{
		return true;
	}

	public function IsIndirect()
	{
		return false;
	}

	public function GetValuesDef()
	{
		$oValSetDef = $this->Get("allowed_values");
		if (!$oValSetDef) {
			// Let's propose every existing value
			$oValSetDef = new ValueSetObjects('SELECT '.LinkSetModel::GetTargetClass($this));
		}

		return $oValSetDef;
	}

	public function GetEditValue($value, $oHostObj = null)
	{
		/** @var ormLinkSet $value * */
		if ($value->Count() === 0) {
			return '';
		}

		/** Return linked objects key as string **/
		$aValues = $value->GetValues();

		return implode(' ', $aValues);
	}

	public function GetPrerequisiteAttributes($sClass = null)
	{
		return $this->Get("depends_on");
	}

	/**
	 * @param \DBObject|null $oHostObject
	 *
	 * @return \ormLinkSet
	 *
	 * @throws Exception
	 * @throws CoreException
	 * @throws CoreWarning
	 */
	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		if ($oHostObject === null) {
			return null;
		}

		$sLinkClass = $this->GetLinkedClass();
		$sExtKeyToMe = $this->GetExtKeyToMe();

		// The class to target is not the current class, because if this is a derived class,
		// it may differ from the target class, then things start to become confusing
		/** @var AttributeExternalKey $oRemoteExtKeyAtt */
		$oRemoteExtKeyAtt = MetaModel::GetAttributeDef($sLinkClass, $sExtKeyToMe);
		$sMyClass = $oRemoteExtKeyAtt->GetTargetClass();

		$oMyselfSearch = new DBObjectSearch($sMyClass);
		if ($oHostObject !== null) {
			$oMyselfSearch->AddCondition('id', $oHostObject->GetKey(), '=');
		}

		$oLinkSearch = new DBObjectSearch($sLinkClass);
		$oLinkSearch->AddCondition_PointingTo($oMyselfSearch, $sExtKeyToMe);
		if ($this->IsIndirect()) {
			// Join the remote class so that the archive flag will be taken into account
			/** @var AttributeLinkedSetIndirect $this */
			$sExtKeyToRemote = $this->GetExtKeyToRemote();
			/** @var AttributeExternalKey $oExtKeyToRemote */
			$oExtKeyToRemote = MetaModel::GetAttributeDef($sLinkClass, $sExtKeyToRemote);
			$sRemoteClass = $oExtKeyToRemote->GetTargetClass();
			if (MetaModel::IsArchivable($sRemoteClass)) {
				$oRemoteSearch = new DBObjectSearch($sRemoteClass);
				/** @var \AttributeLinkedSetIndirect $this */
				$oLinkSearch->AddCondition_PointingTo($oRemoteSearch, $this->GetExtKeyToRemote());
			}
		}
		$oLinks = new DBObjectSet($oLinkSearch);
		$oLinkSet = new ormLinkSet($this->GetHostClass(), $this->GetCode(), $oLinks);

		return $oLinkSet;
	}

	public function GetTrackingLevel()
	{
		return $this->GetOptional('tracking_level', MetaModel::GetConfig()->Get('tracking_level_linked_set_default'));
	}

	/**
	 * @return string see LINKSET_EDITMODE_* constants
	 */
	public function GetEditMode()
	{
		return $this->GetOptional('edit_mode', LINKSET_EDITMODE_ACTIONS);
	}

	/**
	 * @return int see LINKSET_EDITWHEN_* constants
	 * @since 3.1.1 3.2.0 N°6385
	 */
	public function GetEditWhen(): int
	{
		return $this->GetOptional('edit_when', LINKSET_EDITWHEN_ALWAYS);
	}

	/**
	 * @return string see LINKSET_DISPLAY_STYLE_* constants
	 * @since 3.1.0 N°3190
	 */
	public function GetDisplayStyle()
	{
		$sDisplayStyle = $this->GetOptional('display_style', LINKSET_DISPLAY_STYLE_TAB);
		if ($sDisplayStyle === '') {
			$sDisplayStyle = LINKSET_DISPLAY_STYLE_TAB;
		}

		return $sDisplayStyle;
	}

	/**
	 * Indicates if the current Attribute has constraints (php constraints or datamodel constraints)
	 *
	 * @return bool true if Attribute has constraints
	 * @since 3.1.0 N°6228
	 */
	public function HasPHPConstraint(): bool
	{
		return $this->GetOptional('with_php_constraint', false);
	}

	/**
	 * @return bool true if Attribute has computation (DB_LINKS_CHANGED event propagation, `with_php_computation` attribute xml property), false otherwise
	 * @since 3.1.1 3.2.0 N°6228
	 */
	public function HasPHPComputation(): bool
	{
		return $this->GetOptional('with_php_computation', false);
	}

	public function GetLinkedClass()
	{
		return $this->Get('linked_class');
	}

	public function GetExtKeyToMe()
	{
		return $this->Get('ext_key_to_me');
	}

	public function GetBasicFilterOperators()
	{
		return [];
	}

	public function GetBasicFilterLooseOperator()
	{
		return '';
	}

	public function GetBasicFilterSQLExpr($sOpCode, $value)
	{
		return '';
	}

	/** @inheritDoc * */
	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		if ($this->GetDisplayStyle() === LINKSET_DISPLAY_STYLE_TAB) {
			return $this->GetAsHTMLForTab($sValue, $oHostObject, $bLocalize);
		} else {
			return $this->GetAsHTMLForProperty($sValue, $oHostObject, $bLocalize);
		}
	}

	public function GetAsHTMLForTab($sValue, $oHostObject = null, $bLocalize = true)
	{
		if (is_object($sValue) && ($sValue instanceof ormLinkSet)) {
			$sValue->Rewind();
			$aItems = [];
			while ($oObj = $sValue->Fetch()) {
				// Show only relevant information (hide the external key to the current object)
				$aAttributes = [];
				foreach (MetaModel::ListAttributeDefs($this->GetLinkedClass()) as $sAttCode => $oAttDef) {
					if ($sAttCode == $this->GetExtKeyToMe()) {
						continue;
					}
					if ($oAttDef->IsExternalField()) {
						continue;
					}
					$sAttValue = $oObj->GetAsHTML($sAttCode);
					if (strlen($sAttValue) > 0) {
						$aAttributes[] = $sAttValue;
					}
				}
				$sAttributes = implode(', ', $aAttributes);
				$aItems[] = $sAttributes;
			}

			return implode('<br/>', $aItems);
		}

		return null;
	}

	public function GetAsHTMLForProperty($sValue, $oHostObject = null, $bLocalize = true): string
	{
		try {

			/** @var ormLinkSet $sValue */
			if (is_null($sValue) || $sValue->Count() === 0) {
				return '';
			}

			$oLinkSetBlock = new BlockLinkSetDisplayAsProperty($this->GetCode(), $this, $sValue);

			return ConsoleBlockRenderer::RenderBlockTemplates($oLinkSetBlock);
		} catch (Exception $e) {
			$sMessage = "Error while displaying attribute {$this->GetCode()}";
			IssueLog::Error($sMessage, IssueLog::CHANNEL_DEFAULT, [
				'host_object_class' => $this->GetHostClass(),
				'host_object_key'   => $oHostObject->GetKey(),
				'attribute'         => $this->GetCode(),
			]);

			return $sMessage;
		}
	}

	/**
	 * @param string $sValue
	 * @param DBObject $oHostObject
	 * @param bool $bLocalize
	 *
	 * @return string
	 *
	 * @throws CoreException
	 */
	public function GetAsXML($sValue, $oHostObject = null, $bLocalize = true)
	{
		if (is_object($sValue) && ($sValue instanceof ormLinkSet)) {
			$sValue->Rewind();
			$sRes = "<Set>\n";
			while ($oObj = $sValue->Fetch()) {
				$sObjClass = get_class($oObj);
				$sRes .= "<$sObjClass id=\"".$oObj->GetKey()."\">\n";
				// Show only relevant information (hide the external key to the current object)
				foreach (MetaModel::ListAttributeDefs($sObjClass) as $sAttCode => $oAttDef) {
					if ($sAttCode == 'finalclass') {
						if ($sObjClass == $this->GetLinkedClass()) {
							// Simplify the output if the exact class could be determined implicitely
							continue;
						}
					}
					if ($sAttCode == $this->GetExtKeyToMe()) {
						continue;
					}
					if ($oAttDef->IsExternalField()) {
						/** @var \AttributeExternalField $oAttDef */
						if ($oAttDef->GetKeyAttCode() == $this->GetExtKeyToMe()) {
							continue;
						}
						/** @var AttributeExternalField $oAttDef */
						if ($oAttDef->IsFriendlyName()) {
							continue;
						}
					}
					if ($oAttDef instanceof AttributeFriendlyName) {
						continue;
					}
					if (!$oAttDef->IsScalar()) {
						continue;
					}
					$sAttValue = $oObj->GetAsXML($sAttCode, $bLocalize);
					$sRes .= "<$sAttCode>$sAttValue</$sAttCode>\n";
				}
				$sRes .= "</$sObjClass>\n";
			}
			$sRes .= "</Set>\n";
		} else {
			$sRes = '';
		}

		return $sRes;
	}

	/**
	 * @param $sValue
	 * @param string $sSeparator
	 * @param string $sTextQualifier
	 * @param DBObject $oHostObject
	 * @param bool $bLocalize
	 * @param bool $bConvertToPlainText
	 *
	 * @return mixed|string
	 * @throws CoreException
	 */
	public function GetAsCSV(
		$sValue,
		$sSeparator = ',',
		$sTextQualifier = '"',
		$oHostObject = null,
		$bLocalize = true,
		$bConvertToPlainText = false
	) {
		$sSepItem = MetaModel::GetConfig()->Get('link_set_item_separator');
		$sSepAttribute = MetaModel::GetConfig()->Get('link_set_attribute_separator');
		$sSepValue = MetaModel::GetConfig()->Get('link_set_value_separator');
		$sAttributeQualifier = MetaModel::GetConfig()->Get('link_set_attribute_qualifier');

		if (is_object($sValue) && ($sValue instanceof ormLinkSet)) {
			$sValue->Rewind();
			$aItems = [];
			while ($oObj = $sValue->Fetch()) {
				$sObjClass = get_class($oObj);
				// Show only relevant information (hide the external key to the current object)
				$aAttributes = [];
				foreach (MetaModel::ListAttributeDefs($sObjClass) as $sAttCode => $oAttDef) {
					if ($sAttCode == 'finalclass') {
						if ($sObjClass == $this->GetLinkedClass()) {
							// Simplify the output if the exact class could be determined implicitely
							continue;
						}
					}
					if ($sAttCode == $this->GetExtKeyToMe()) {
						continue;
					}
					if ($oAttDef->IsExternalField()) {
						continue;
					}
					if (!$oAttDef->IsBasedOnDBColumns()) {
						continue;
					}
					if (!$oAttDef->IsScalar()) {
						continue;
					}
					$sAttValue = $oObj->GetAsCSV($sAttCode, $sSepValue, '', $bLocalize);
					if (strlen($sAttValue) > 0) {
						$sAttributeData = str_replace(
							$sAttributeQualifier,
							$sAttributeQualifier.$sAttributeQualifier,
							$sAttCode.$sSepValue.$sAttValue
						);
						$aAttributes[] = $sAttributeQualifier.$sAttributeData.$sAttributeQualifier;
					}
				}
				$sAttributes = implode($sSepAttribute, $aAttributes);
				$aItems[] = $sAttributes;
			}
			$sRes = implode($sSepItem, $aItems);
		} else {
			$sRes = '';
		}
		$sRes = str_replace($sTextQualifier, $sTextQualifier.$sTextQualifier, $sRes);
		$sRes = $sTextQualifier.$sRes.$sTextQualifier;

		return $sRes;
	}

	/**
	 * List the available verbs for 'GetForTemplate'
	 */
	public function EnumTemplateVerbs()
	{
		return [
			''     => 'Plain text (unlocalized) representation',
			'html' => 'HTML representation (unordered list)',
		];
	}

	/**
	 * Get various representations of the value, for insertion into a template (e.g. in Notifications)
	 *
	 * @param mixed $value The current value of the field
	 * @param string $sVerb The verb specifying the representation of the value
	 * @param DBObject $oHostObject The object
	 * @param bool $bLocalize Whether or not to localize the value
	 *
	 * @return string
	 * @throws Exception
	 */
	public function GetForTemplate($value, $sVerb, $oHostObject = null, $bLocalize = true)
	{
		$sRemoteName = $this->IsIndirect() ?
			/** @var AttributeLinkedSetIndirect $this */
			$this->GetExtKeyToRemote().'_friendlyname' : 'friendlyname';

		$oLinkSet = clone $value; // Workaround/Safety net for Trac #887
		$iLimit = MetaModel::GetConfig()->Get('max_linkset_output');
		$iCount = 0;
		$aNames = [];
		foreach ($oLinkSet as $oItem) {
			if (($iLimit > 0) && ($iCount == $iLimit)) {
				$iTotal = $oLinkSet->Count();
				$aNames[] = '... '.Dict::Format('UI:TruncatedResults', $iCount, $iTotal);
				break;
			}
			$aNames[] = $oItem->Get($sRemoteName);
			$iCount++;
		}

		switch ($sVerb) {
			case '':
				return implode("\n", $aNames);

			case 'html':
				return '<ul><li>'.implode("</li><li>", $aNames).'</li></ul>';

			default:
				throw new Exception("Unknown verb '$sVerb' for attribute ".$this->GetCode().' in class '.get_class($oHostObject));
		}
	}

	public function DuplicatesAllowed()
	{
		return false;
	} // No duplicates for 1:n links, never

	public function GetImportColumns()
	{
		$aColumns = [];
		$aColumns[$this->GetCode()] = 'MEDIUMTEXT'.CMDBSource::GetSqlStringColumnDefinition();

		return $aColumns;
	}

	/**
	 * @param string $sProposedValue
	 * @param bool $bLocalizedValue
	 * @param string $sSepItem
	 * @param string $sSepAttribute
	 * @param string $sSepValue
	 * @param string $sAttributeQualifier
	 *
	 * @return DBObjectSet
	 * @throws CSVParserException
	 * @throws CoreException
	 * @throws CoreUnexpectedValue
	 * @throws MissingQueryArgument
	 * @throws MySQLException
	 * @throws MySQLHasGoneAwayException
	 * @throws Exception
	 */
	public function MakeValueFromString(
		$sProposedValue,
		$bLocalizedValue = false,
		$sSepItem = null,
		$sSepAttribute = null,
		$sSepValue = null,
		$sAttributeQualifier = null
	) {
		if (is_null($sSepItem) || empty($sSepItem)) {
			$sSepItem = MetaModel::GetConfig()->Get('link_set_item_separator');
		}
		if (is_null($sSepAttribute) || empty($sSepAttribute)) {
			$sSepAttribute = MetaModel::GetConfig()->Get('link_set_attribute_separator');
		}
		if (is_null($sSepValue) || empty($sSepValue)) {
			$sSepValue = MetaModel::GetConfig()->Get('link_set_value_separator');
		}
		if (is_null($sAttributeQualifier) || empty($sAttributeQualifier)) {
			$sAttributeQualifier = MetaModel::GetConfig()->Get('link_set_attribute_qualifier');
		}

		$sTargetClass = $this->Get('linked_class');

		$sInput = str_replace($sSepItem, "\n", $sProposedValue);
		$oCSVParser = new CSVParser($sInput, $sSepAttribute, $sAttributeQualifier);

		$aInput = $oCSVParser->ToArray(0 /* do not skip lines */);

		$aLinks = [];
		foreach ($aInput as $aRow) {
			// 1st - get the values, split the extkey->searchkey specs, and eventually get the finalclass value
			$aExtKeys = [];
			$aValues = [];
			foreach ($aRow as $sCell) {
				$iSepPos = strpos($sCell, $sSepValue);
				if ($iSepPos === false) {
					// Houston...
					throw new CoreException('Wrong format for link attribute specification', ['value' => $sCell]);
				}

				$sAttCode = trim(substr($sCell, 0, $iSepPos));
				$sValue = substr($sCell, $iSepPos + strlen($sSepValue));

				if (preg_match('/^(.+)->(.+)$/', $sAttCode, $aMatches)) {
					$sKeyAttCode = $aMatches[1];
					$sRemoteAttCode = $aMatches[2];
					$aExtKeys[$sKeyAttCode][$sRemoteAttCode] = $sValue;
					if (!MetaModel::IsValidAttCode($sTargetClass, $sKeyAttCode)) {
						throw new CoreException(
							'Wrong attribute code for link attribute specification',
							['class' => $sTargetClass, 'attcode' => $sKeyAttCode]
						);
					}
					/** @var \AttributeExternalKey $oKeyAttDef */
					$oKeyAttDef = MetaModel::GetAttributeDef($sTargetClass, $sKeyAttCode);
					$sRemoteClass = $oKeyAttDef->GetTargetClass();
					if (!MetaModel::IsValidAttCode($sRemoteClass, $sRemoteAttCode)) {
						throw new CoreException(
							'Wrong attribute code for link attribute specification',
							['class' => $sRemoteClass, 'attcode' => $sRemoteAttCode]
						);
					}
				} else {
					if (!MetaModel::IsValidAttCode($sTargetClass, $sAttCode)) {
						throw new CoreException(
							'Wrong attribute code for link attribute specification',
							['class' => $sTargetClass, 'attcode' => $sAttCode]
						);
					}
					$oAttDef = MetaModel::GetAttributeDef($sTargetClass, $sAttCode);
					$aValues[$sAttCode] = $oAttDef->MakeValueFromString(
						$sValue,
						$bLocalizedValue,
						$sSepItem,
						$sSepAttribute,
						$sSepValue,
						$sAttributeQualifier
					);
				}
			}

			// 2nd - Instanciate the object and set the value
			if (isset($aValues['finalclass'])) {
				$sLinkClass = $aValues['finalclass'];
				if (!is_subclass_of($sLinkClass, $sTargetClass)) {
					throw new CoreException(
						'Wrong class for link attribute specification',
						['requested_class' => $sLinkClass, 'expected_class' => $sTargetClass]
					);
				}
			} elseif (MetaModel::IsAbstract($sTargetClass)) {
				throw new CoreException('Missing finalclass for link attribute specification');
			} else {
				$sLinkClass = $sTargetClass;
			}

			$oLink = MetaModel::NewObject($sLinkClass);
			foreach ($aValues as $sAttCode => $sValue) {
				$oLink->Set($sAttCode, $sValue);
			}

			// 3rd - Set external keys from search conditions
			foreach ($aExtKeys as $sKeyAttCode => $aReconciliation) {
				$oKeyAttDef = MetaModel::GetAttributeDef($sTargetClass, $sKeyAttCode);
				$sKeyClass = $oKeyAttDef->GetTargetClass();
				$oExtKeyFilter = new DBObjectSearch($sKeyClass);
				$aReconciliationDesc = [];
				foreach ($aReconciliation as $sRemoteAttCode => $sValue) {
					$oExtKeyFilter->AddCondition($sRemoteAttCode, $sValue, '=');
					$aReconciliationDesc[] = "$sRemoteAttCode=$sValue";
				}
				$oExtKeySet = new DBObjectSet($oExtKeyFilter);
				switch ($oExtKeySet->Count()) {
					case 0:
						$sReconciliationDesc = implode(', ', $aReconciliationDesc);
						throw new CoreException(
							"Found no match",
							['ext_key' => $sKeyAttCode, 'reconciliation' => $sReconciliationDesc]
						);
						break;
					case 1:
						$oRemoteObj = $oExtKeySet->Fetch();
						$oLink->Set($sKeyAttCode, $oRemoteObj->GetKey());
						break;
					default:
						$sReconciliationDesc = implode(', ', $aReconciliationDesc);
						throw new CoreException(
							"Found several matches",
							['ext_key' => $sKeyAttCode, 'reconciliation' => $sReconciliationDesc]
						);
						// Found several matches, ambiguous
				}
			}

			// Check (roughly) if such a link is valid
			$aErrors = [];
			foreach (MetaModel::ListAttributeDefs($sTargetClass) as $sAttCode => $oAttDef) {
				if ($oAttDef->IsExternalKey()) {
					/** @var \AttributeExternalKey $oAttDef */
					if (($oAttDef->GetTargetClass() == $this->GetHostClass()) || (is_subclass_of(
						$this->GetHostClass(),
						$oAttDef->GetTargetClass()
					))) {
						continue; // Don't check the key to self
					}
				}

				if ($oAttDef->IsWritable() && $oAttDef->IsNull($oLink->Get($sAttCode)) && !$oAttDef->IsNullAllowed()) {
					$aErrors[] = $sAttCode;
				}
			}
			if (count($aErrors) > 0) {
				throw new CoreException("Missing value for mandatory attribute(s): ".implode(', ', $aErrors));
			}

			$aLinks[] = $oLink;
		}
		$oSet = DBObjectSet::FromArray($sTargetClass, $aLinks);

		return $oSet;
	}

	/**
	 * @inheritDoc
	 *
	 * @param ormLinkSet $value
	 */
	public function GetForJSON($value)
	{
		$aRet = [];
		if (is_object($value) && ($value instanceof ormLinkSet)) {
			$value->Rewind();
			while ($oObj = $value->Fetch()) {
				$sObjClass = get_class($oObj);
				// Show only relevant information (hide the external key to the current object)
				$aAttributes = [];
				foreach (MetaModel::ListAttributeDefs($sObjClass) as $sAttCode => $oAttDef) {
					if ($sAttCode == 'finalclass') {
						if ($sObjClass == $this->GetLinkedClass()) {
							// Simplify the output if the exact class could be determined implicitely
							continue;
						}
					}
					if ($sAttCode == $this->GetExtKeyToMe()) {
						continue;
					}
					if ($oAttDef->IsExternalField()) {
						continue;
					}
					if (!$oAttDef->IsBasedOnDBColumns()) {
						continue;
					}
					if (!$oAttDef->IsScalar()) {
						continue;
					}
					$attValue = $oObj->Get($sAttCode);
					$aAttributes[$sAttCode] = $oAttDef->GetForJSON($attValue);
				}
				$aRet[] = $aAttributes;
			}
		}

		return $aRet;
	}

	/**
	 * @inheritDoc
	 *
	 * @return DBObjectSet
	 * @throws CoreException
	 * @throws CoreUnexpectedValue
	 * @throws Exception
	 */
	public function FromJSONToValue($json)
	{
		$sTargetClass = $this->Get('linked_class');

		$aLinks = [];
		foreach ($json as $aValues) {
			if (isset($aValues['finalclass'])) {
				$sLinkClass = $aValues['finalclass'];
				if (!is_subclass_of($sLinkClass, $sTargetClass)) {
					throw new CoreException(
						'Wrong class for link attribute specification',
						['requested_class' => $sLinkClass, 'expected_class' => $sTargetClass]
					);
				}
			} elseif (MetaModel::IsAbstract($sTargetClass)) {
				throw new CoreException('Missing finalclass for link attribute specification');
			} else {
				$sLinkClass = $sTargetClass;
			}

			$oLink = MetaModel::NewObject($sLinkClass);
			foreach ($aValues as $sAttCode => $sValue) {
				$oLink->Set($sAttCode, $sValue);
			}

			// Check (roughly) if such a link is valid
			$aErrors = [];
			foreach (MetaModel::ListAttributeDefs($sTargetClass) as $sAttCode => $oAttDef) {
				if ($oAttDef->IsExternalKey()) {
					/** @var AttributeExternalKey $oAttDef */
					if (($oAttDef->GetTargetClass() == $this->GetHostClass()) || (is_subclass_of(
						$this->GetHostClass(),
						$oAttDef->GetTargetClass()
					))) {
						continue; // Don't check the key to self
					}
				}

				if ($oAttDef->IsWritable() && $oAttDef->IsNull($oLink->Get($sAttCode)) && !$oAttDef->IsNullAllowed()) {
					$aErrors[] = $sAttCode;
				}
			}
			if (count($aErrors) > 0) {
				throw new CoreException("Missing value for mandatory attribute(s): ".implode(', ', $aErrors));
			}

			$aLinks[] = $oLink;
		}
		$oSet = DBObjectSet::FromArray($sTargetClass, $aLinks);

		return $oSet;
	}

	/**
	 * @param $proposedValue
	 * @param $oHostObj
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function MakeRealValue($proposedValue, $oHostObj)
	{
		if ($proposedValue === null) {
			$sLinkedClass = $this->GetLinkedClass();
			$aLinkedObjectsArray = [];
			$oSet = DBObjectSet::FromArray($sLinkedClass, $aLinkedObjectsArray);

			return new ormLinkSet(
				get_class($oHostObj),
				$this->GetCode(),
				$oSet
			);
		}

		return $proposedValue;
	}

	/**
	 * @param ormLinkSet $val1
	 * @param ormLinkSet $val2
	 *
	 * @return bool
	 */
	public function Equals($val1, $val2)
	{
		if ($val1 === $val2) {
			$bAreEquivalent = true;
		} else {
			$bAreEquivalent = ($val2->HasDelta() === false);
		}

		return $bAreEquivalent;
	}

	/**
	 * Find the corresponding "link" attribute on the target class, if any
	 *
	 * @return null | AttributeDefinition
	 * @throws Exception
	 */
	public function GetMirrorLinkAttribute()
	{
		$oRemoteAtt = MetaModel::GetAttributeDef($this->GetLinkedClass(), $this->GetExtKeyToMe());

		return $oRemoteAtt;
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\LinkedSetField';
	}

	/**
	 * @param DBObject $oObject
	 * @param LinkedSetField $oFormField
	 *
	 * @return LinkedSetField
	 * @throws CoreException
	 * @throws DictExceptionMissingString
	 * @throws Exception
	 */
	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		if ($oFormField === null) {
			$sFormFieldClass = static::GetFormFieldClass();
			$oFormField = new $sFormFieldClass($this->GetCode());
		}

		// Setting target class
		if (!$this->IsIndirect()) {
			$sTargetClass = $this->GetLinkedClass();
		} else {
			/** @var \AttributeExternalKey $oRemoteAttDef */
			/** @var \AttributeLinkedSetIndirect $this */
			$oRemoteAttDef = MetaModel::GetAttributeDef($this->GetLinkedClass(), $this->GetExtKeyToRemote());
			$sTargetClass = $oRemoteAttDef->GetTargetClass();

			/** @var \AttributeLinkedSetIndirect $this */
			$oFormField->SetExtKeyToRemote($this->GetExtKeyToRemote());
		}
		$oFormField->SetTargetClass($sTargetClass);
		$oFormField->SetLinkedClass($this->GetLinkedClass());
		$oFormField->SetIndirect($this->IsIndirect());
		// Setting attcodes to display
		$aAttCodesToDisplay = MetaModel::FlattenZList(MetaModel::GetZListItems($sTargetClass, 'list'));
		// - Adding friendlyname attribute to the list is not already in it
		$sTitleAttCode = MetaModel::GetFriendlyNameAttributeCode($sTargetClass);
		if (($sTitleAttCode !== null) && !in_array($sTitleAttCode, $aAttCodesToDisplay)) {
			$aAttCodesToDisplay = array_merge([$sTitleAttCode], $aAttCodesToDisplay);
		}
		// - Adding attribute properties
		$aAttributesToDisplay = [];
		foreach ($aAttCodesToDisplay as $sAttCodeToDisplay) {
			$oAttDefToDisplay = MetaModel::GetAttributeDef($sTargetClass, $sAttCodeToDisplay);
			$aAttributesToDisplay[$sAttCodeToDisplay] = [
				'att_code' => $sAttCodeToDisplay,
				'label'    => $oAttDefToDisplay->GetLabel(),
			];
		}
		$oFormField->SetAttributesToDisplay($aAttributesToDisplay);

		// Append lnk attributes (filtered from zlist)
		if ($this->IsIndirect()) {
			$aLnkAttDefToDisplay = MetaModel::GetZListAttDefsFilteredForIndirectLinkClass($this->m_sHostClass, $this->m_sCode);
			$aLnkAttributesToDisplay = [];
			foreach ($aLnkAttDefToDisplay as $oLnkAttDefToDisplay) {
				$aLnkAttributesToDisplay[$oLnkAttDefToDisplay->GetCode()] = [
					'att_code'  => $oLnkAttDefToDisplay->GetCode(),
					'label'     => $oLnkAttDefToDisplay->GetLabel(),
					'mandatory' => !$oLnkAttDefToDisplay->IsNullAllowed(),
				];
			}
			$oFormField->SetLnkAttributesToDisplay($aLnkAttributesToDisplay);
		}

		parent::MakeFormField($oObject, $oFormField);

		return $oFormField;
	}

	public function IsPartOfFingerprint()
	{
		return false;
	}

	/**
	 * @inheritDoc
	 *
	 * @param ormLinkSet $proposedValue
	 */
	public function HasAValue($proposedValue): bool
	{
		// Protection against wrong value type
		if (false === ($proposedValue instanceof ormLinkSet)) {
			return parent::HasAValue($proposedValue);
		}

		// We test if there is at least 1 item in the linkset (new or existing), not if an item is being added to it.
		return $proposedValue->Count() > 0;
	}

	/**
	 * SearchSpecificLabel.
	 *
	 * @param string $sDictEntrySuffix
	 * @param string $sDefault
	 * @param bool $bUserLanguageOnly
	 * @param ...$aArgs
	 *
	 * @return string
	 * @since 3.1.0
	 */
	public function SearchSpecificLabel(string $sDictEntrySuffix, string $sDefault, bool $bUserLanguageOnly, ...$aArgs): string
	{
		try {
			$sNextClass = $this->m_sHostClass;

			do {
				$sKey = "Class:{$sNextClass}/Attribute:{$this->m_sCode}/{$sDictEntrySuffix}";
				if (Dict::S($sKey, null, $bUserLanguageOnly) !== $sKey) {
					return Dict::Format($sKey, ...$aArgs);
				}
				$sNextClass = MetaModel::GetParentClass($sNextClass);
			} while ($sNextClass !== null);

			if (Dict::S($sDictEntrySuffix, null, $bUserLanguageOnly) !== $sKey) {
				return Dict::Format($sDictEntrySuffix, ...$aArgs);
			} else {
				return $sDefault;
			}
		} catch (Exception $e) {
			ExceptionLog::LogException($e);

			return $sDefault;
		}
	}
}
