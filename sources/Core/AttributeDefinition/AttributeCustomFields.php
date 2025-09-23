<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use CMDBChangeOp;
use CMDBChangeOpSetAttributeCustomFields;
use DBObject;
use Exception;
use ormCustomFieldsValue;
use Str;
use utils;

/**
 * Custom fields managed by an external implementation
 *
 * @package     iTopORM
 */
class AttributeCustomFields extends AttributeDefinition
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
		return array_merge(parent::ListExpectedParams(), array("handler_class"));
	}

	public function GetEditClass()
	{
		return "CustomFields";
	}

	public function IsWritable()
	{
		return true;
	}

	public static function LoadFromClassTables()
	{
		return false;
	} // See ReadValue...

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		return new ormCustomFieldsValue($oHostObject, $this->GetCode());
	}

	public function GetBasicFilterOperators()
	{
		return array();
	}

	public function GetBasicFilterLooseOperator()
	{
		return '';
	}

	public function GetBasicFilterSQLExpr($sOpCode, $value)
	{
		return '';
	}

	/**
	 * @param array|null $aValues
	 *
	 * @return TemplateFieldsHandler
	 */
	public function GetHandler($aValues = null)
	{
		$sHandlerClass = $this->Get('handler_class');
		/** @var TemplateFieldsHandler $oHandler */
		$oHandler = new $sHandlerClass($this->GetCode());
		if (!is_null($aValues)) {
			$oHandler->SetCurrentValues($aValues);
		}

		return $oHandler;
	}

	public function GetPrerequisiteAttributes($sClass = null)
	{
		$sHandlerClass = $this->Get('handler_class');

		return $sHandlerClass::GetPrerequisiteAttributes($sClass);
	}

	public function GetEditValue($sValue, $oHostObj = null)
	{
		return $this->GetForTemplate($sValue, '', $oHostObj, true);
	}

	/**
	 * Makes the string representation out of the values given by the form defined in GetDisplayForm
	 */
	public function ReadValueFromPostedForm($oHostObject, $sFormPrefix)
	{
		$aRawData = json_decode(utils::ReadPostedParam("attr_{$sFormPrefix}{$this->GetCode()}", '{}', 'raw_data'), true);
		if ($aRawData != null) {
			return new ormCustomFieldsValue($oHostObject, $this->GetCode(), $aRawData);
		} else {
			return null;
		}
	}

	public function MakeRealValue($proposedValue, $oHostObject)
	{
		if (is_object($proposedValue) && ($proposedValue instanceof ormCustomFieldsValue)) {
			if (false === $oHostObject->IsNew()) {
				// In that case we need additional keys : see \TemplateFieldsHandler::DoBuildForm
				$aRequestTemplateValues = $proposedValue->GetValues();
				if (false === array_key_exists('current_template_id', $aRequestTemplateValues)) {
					$aRequestTemplateValues['current_template_id'] = $aRequestTemplateValues['template_id'];
					$aRequestTemplateValues['current_template_data'] = $aRequestTemplateValues['template_data'];
					$proposedValue = new ormCustomFieldsValue($oHostObject, $this->GetCode(), $aRequestTemplateValues);
				}
			}

			if (is_null($proposedValue->GetHostObject())) {
				// the object might not be set : for example in \AttributeCustomFields::FromJSONToValue we don't have the object available :(
				$proposedValue->SetHostObject($oHostObject);
			}

			return $proposedValue;
		}

		if (is_string($proposedValue)) {
			$aValues = json_decode($proposedValue, true);

			return new ormCustomFieldsValue($oHostObject, $this->GetCode(), $aValues);
		}

		if (is_array($proposedValue)) {
			return new ormCustomFieldsValue($oHostObject, $this->GetCode(), $proposedValue);
		}

		if (is_null($proposedValue)) {
			return new ormCustomFieldsValue($oHostObject, $this->GetCode());
		}

		throw new Exception('Unexpected type for the value of a custom fields attribute: '.gettype($proposedValue));
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\SubFormField';
	}

	/**
	 * Override to build the relevant form field
	 *
	 * When called first, $oFormField is null and will be created (eg. Make). Then when the ::parent is called and the
	 * $oFormField is passed, MakeFormField behaves more like a Prepare.
	 */
	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		if ($oFormField === null) {
			$sFormFieldClass = static::GetFormFieldClass();
			$oFormField = new $sFormFieldClass($this->GetCode());
			$oFormField->SetForm($this->GetForm($oObject));
		}
		parent::MakeFormField($oObject, $oFormField);

		return $oFormField;
	}

	/**
	 * @param DBObject $oHostObject
	 * @param null $sFormPrefix
	 *
	 * @return Combodo\iTop\Form\Form
	 * @throws \Exception
	 */
	public function GetForm(DBObject $oHostObject, $sFormPrefix = null)
	{
		try {
			$oValue = $oHostObject->Get($this->GetCode());
			$oHandler = $this->GetHandler($oValue->GetValues());
			$sFormId = utils::IsNullOrEmptyString($sFormPrefix) ? 'cf_'.$this->GetCode() : $sFormPrefix.'_cf_'.$this->GetCode();
			$oHandler->BuildForm($oHostObject, $sFormId);
			$oForm = $oHandler->GetForm();
		}
		catch (Exception $e) {
			$oForm = new \Combodo\iTop\Form\Form('');
			$oField = new \Combodo\iTop\Form\Field\LabelField('');
			$oField->SetLabel('Custom field error: '.$e->getMessage());
			$oForm->AddField($oField);
			$oForm->Finalize();
		}

		return $oForm;
	}

	/**
	 * Read the data from where it has been stored. This verb must be implemented as soon as LoadFromClassTables returns false
	 * and LoadInObject returns true
	 *
	 * @param DBObject $oHostObject
	 *
	 * @return mixed|null
	 * @since 3.1.0
	 */
	public function ReadExternalValues(DBObject $oHostObject)
	{
		try {
			$oHandler = $this->GetHandler();
			$aValues = $oHandler->ReadValues($oHostObject);
			$oRet = new ormCustomFieldsValue($oHostObject, $this->GetCode(), $aValues);
		}
		catch (Exception $e) {
			$oRet = new ormCustomFieldsValue($oHostObject, $this->GetCode());
		}

		return $oRet;
	}

	/**
	 * @inheritDoc
	 *
	 * @since 3.1.0 N°6043 Move code contained in \AttributeCustomFields::WriteValue to this generic method
	 */
	public function WriteExternalValues(DBObject $oHostObject): void
	{
		$oValue = $oHostObject->Get($this->GetCode());
		if (!($oValue instanceof ormCustomFieldsValue)) {
			$oHandler = $this->GetHandler();
			$aValues = array();
		} else {
			// Pass the values through the form to make sure that they are correct
			$oHandler = $this->GetHandler($oValue->GetValues());
			$oHandler->BuildForm($oHostObject, '');
			$oForm = $oHandler->GetForm();
			$aValues = $oForm->GetCurrentValues();
		}

		$oHandler->WriteValues($oHostObject, $aValues);
	}

	/**
	 * The part of the current attribute in the object's signature, for the supplied value
	 *
	 * @param ormCustomFieldsValue $value The value of this attribute for the object
	 *
	 * @return string The "signature" for this field/attribute
	 */
	public function Fingerprint($value)
	{
		$oHandler = $this->GetHandler($value->GetValues());

		return $oHandler->GetValueFingerprint();
	}

	/**
	 * Check the validity of the data
	 *
	 * @param DBObject $oHostObject
	 * @param $value
	 *
	 * @return bool|string true or error message
	 */
	public function CheckValue(DBObject $oHostObject, $value)
	{
		try {
			$oHandler = $this->GetHandler($value->GetValues());
			$oHandler->BuildForm($oHostObject, '');
			$ret = $oHandler->Validate($oHostObject);
		}
		catch (Exception $e) {
			$ret = $e->getMessage();
		}

		return $ret;
	}

	/**
	 * Cleanup data upon object deletion (object id still available here)
	 *
	 * @param DBObject $oHostObject
	 *
	 * @throws \CoreException
	 * @since 3.1.0
	 */
	public function DeleteExternalValues(DBObject $oHostObject): void
	{
		$oValue = $oHostObject->Get($this->GetCode());
		$oHandler = $this->GetHandler($oValue->GetValues());

		$oHandler->DeleteValues($oHostObject);
	}

	public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
	{
		try {
			/** @var ormCustomFieldsValue $value */
			$sRet = $value->GetAsHTML($bLocalize);
		}
		catch (Exception $e) {
			$sRet = 'Custom field error: '.utils::EscapeHtml($e->getMessage());
		}

		return $sRet;
	}

	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		try {
			$sRet = $value->GetAsXML($bLocalize);
		}
		catch (Exception $e) {
			$sRet = Str::pure2xml('Custom field error: '.$e->getMessage());
		}

		return $sRet;
	}

	/**
	 * @param ormCustomFieldsValue $value
	 * @param string $sSeparator
	 * @param string $sTextQualifier
	 * @param DBObject $oHostObject
	 * @param bool $bLocalize
	 * @param bool $bConvertToPlainText
	 *
	 * @return string
	 * @noinspection PhpParameterNameChangedDuringInheritanceInspection
	 */
	public function GetAsCSV(
		$value, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
		$bConvertToPlainText = false
	)
	{
		try {
			$sRet = $value->GetAsCSV($sSeparator, $sTextQualifier, $bLocalize, $bConvertToPlainText);
		}
		catch (Exception $e) {
			$sFrom = array("\r\n", $sTextQualifier);
			$sTo = array("\n", $sTextQualifier.$sTextQualifier);
			$sEscaped = str_replace($sFrom, $sTo, 'Custom field error: '.$e->getMessage());
			$sRet = $sTextQualifier.$sEscaped.$sTextQualifier;
		}

		return $sRet;
	}

	/**
	 * List the available verbs for 'GetForTemplate'
	 */
	public function EnumTemplateVerbs()
	{
		$sHandlerClass = $this->Get('handler_class');

		return $sHandlerClass::EnumTemplateVerbs();
	}

	/**
	 * Get various representations of the value, for insertion into a template (e.g. in Notifications)
	 *
	 * @param $value mixed The current value of the field
	 * @param $sVerb string The verb specifying the representation of the value
	 * @param $oHostObject DBObject The object
	 * @param $bLocalize bool Whether or not to localize the value
	 *
	 * @return string
	 */
	public function GetForTemplate($value, $sVerb, $oHostObject = null, $bLocalize = true)
	{
		try {
			$sRet = $value->GetForTemplate($sVerb, $bLocalize);
		}
		catch (Exception $e) {
			$sRet = 'Custom field error: '.$e->getMessage();
		}

		return $sRet;
	}

	public function MakeValueFromString(
		$sProposedValue, $bLocalizedValue = false, $sSepItem = null, $sSepAttribute = null, $sSepValue = null,
		$sAttributeQualifier = null
	)
	{
		return null;
	}

	/**
	 * @inheritDoc
	 *
	 * @param ormCustomFieldsValue $value
	 *
	 * @return string|array
	 *
	 * @since 3.1.0 N°1150 now returns the value (was always returning null before)
	 */
	public function GetForJSON($value)
	{
		try {
			$sRet = $value->GetForJSON();
		}
		catch (Exception $e) {
			$sRet = 'Custom field error: '.$e->getMessage();
		}

		return $sRet;
	}

	/**
	 * @inheritDoc
	 *
	 * @return ?ormCustomFieldsValue with empty host object as we don't have it here (most consumers don't have an object in their context, for example in \RestUtils::GetObjectSetFromKey)
	 *                  The host object will be set in {@see MakeRealValue}
	 *                  All the necessary checks will be done in {@see CheckValue}
	 */
	public function FromJSONToValue($json)
	{
		return ormCustomFieldsValue::FromJSONToValue($json, $this);
	}

	public function Equals($val1, $val2)
	{
		try {
			$bEquals = $val1->Equals($val2);
		}
		catch (Exception $e) {
			$bEquals = false;
		}

		return $bEquals;
	}

	/**
	 * @inheritDoc
	 */
	public function HasAValue($proposedValue): bool
	{
		// Protection against wrong value type
		if (false === ($proposedValue instanceof ormCustomFieldsValue)) {
			return parent::HasAValue($proposedValue);
		}

		return count($proposedValue->GetValues()) > 0;
	}

	protected function GetChangeRecordAdditionalData(CMDBChangeOp $oMyChangeOp, DBObject $oObject, $original, $value): void
	{
		$oMyChangeOp->Set("prevdata", json_encode($original->GetValues()));
	}

	protected function GetChangeRecordClassName(): string
	{
		return CMDBChangeOpSetAttributeCustomFields::class;
	}
}