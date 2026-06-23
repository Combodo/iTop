<?php

/**
 * Helpers for implementing REST services
 *
 * @api
 * @package     RESTAPI
 */
class RestUtils
{
	/**
	 * Registering tracking information. Any further object modification be associated with the given comment, when the modification gets
	 * recorded into the DB
	 *
	 * @param StdClass $oData Structured input data. Must contain 'comment'.
	 *
	 * @return void
	 * @throws Exception
	 * @api
	 *
	 */
	public static function InitTrackingComment($oData)
	{
		$sComment = self::GetMandatoryParam($oData, 'comment');
		CMDBObject::SetTrackInfo($sComment);
	}

	/**
	 * Read a mandatory parameter from  from a Rest/Json structure.
	 *
	 * @param string $sParamName Name of the parameter to fetch from the input data
	 * @param StdClass $oData Structured input data. Must contain the entry defined by sParamName.
	 *
	 * @return mixed parameter value if present
	 * @throws Exception If the parameter is missing
	 * @api
	 */
	public static function GetMandatoryParam($oData, $sParamName)
	{
		if (isset($oData->$sParamName)) {
			return $oData->$sParamName;
		} else {
			throw new Exception("Missing parameter '$sParamName'");
		}
	}

	/**
	 * Read an optional parameter from a Rest/Json structure.
	 *
	 * @param string $sParamName Name of the parameter to fetch from the input data
	 * @param mixed $default Default value if the parameter is not found in the input data
	 *
	 * @param StdClass $oData Structured input data.
	 *
	 * @return mixed
	 * @throws Exception
	 * @api
	 */
	public static function GetOptionalParam($oData, $sParamName, $default)
	{
		if (isset($oData->$sParamName)) {
			return $oData->$sParamName;
		} else {
			return $default;
		}
	}

	/**
	 * Read a class from a Rest/Json structure.
	 *
	 * @param string $sParamName Name of the parameter to fetch from the input data
	 * @param StdClass $oData Structured input data. Must contain the entry defined by sParamName.
	 *
	 * @return string
	 * @throws Exception If the parameter is missing or the class is unknown
	 * @api
	 */
	public static function GetClass($oData, $sParamName)
	{
		$sClass = self::GetMandatoryParam($oData, $sParamName);
		if (!MetaModel::IsValidClass($sClass)) {
			throw new Exception("$sParamName: '$sClass' is not a valid class'");
		}

		return $sClass;
	}

	/**
	 * Read a list of attribute codes from a Rest/Json structure.
	 *
	 * @param StdClass $oData Structured input data.
	 * @param string $sParamName Name of the parameter to fetch from the input data
	 *
	 * @param string $sClass Name of the class
	 *
	 * @return array of class => list of attributes (see RestResultWithObjects::AddObject that uses it)
	 * @throws Exception
	 * @api
	 */
	public static function GetFieldList($sClass, $oData, $sParamName, $bFailIfNotFound = true)
	{
		$sFields = self::GetOptionalParam($oData, $sParamName, '*');
		return match($sFields) {
			'*' => self::GetFieldListForClass($sClass),
			'*+' => self::GetFieldListForParentClass($sClass),
			default => self::GetLimitedFieldListForClass($sClass, $sFields, $sParamName, $bFailIfNotFound),
		};
	}

	/**
	 * Check if the requested field list asks for an extended output.
	 *
	 * Extended output is requested when using '*+' or class-scoped field definitions.
	 *
	 * @param string $sFields Requested field specification.
	 *
	 * @return bool
	 */
	public static function HasRequestedExtendedOutput(string $sFields): bool
	{
		return match($sFields) {
			'*' => false,
			'*+' => true,
			default => substr_count($sFields, ':') > 1,
		};
	}

	/**
	 * Check if the requested field list asks for all output fields.
	 *
	 * @param string $sFields Requested field specification.
	 *
	 * @return bool
	 */
	public static function HasRequestedAllOutputFields(string $sFields): bool
	{
		return match($sFields) {
			'*', '*+' => true,
			default => false,
		};
	}

	protected static function GetFieldListForClass(string $sClass): array
	{
		return [$sClass => array_keys(MetaModel::ListAttributeDefs($sClass))];
	}

	/**
	 * Build a field list for all child classes of the given parent class.
	 *
	 * @param string $sClass Parent class name.
	 *
	 * @return array Array of class => list of attribute codes.
	 */
	protected static function GetFieldListForParentClass(string $sClass): array
	{
		$aFieldList = [];
		foreach (MetaModel::EnumChildClasses($sClass, ENUM_CHILD_CLASSES_ALL) as $sRefClass) {
			$aFieldList = array_merge($aFieldList, self::GetFieldListForClass($sRefClass));
		}
		return $aFieldList;
	}

	/**
	 * Build a restricted field list for one class from a comma-separated attribute list.
	 *
	 * @param string $sClass Class name.
	 * @param string $sFields Comma-separated list of requested attribute codes.
	 * @param string $sParamName Input parameter name used in error messages.
	 * @param bool $bFailIfNotFound If true, throws when an attribute code is invalid.
	 *
	 * @return array Array containing one class => list of attribute codes.
	 * @throws Exception When an attribute code is invalid and $bFailIfNotFound is true.
	 */
	protected static function GetLimitedFieldListForSingleClass(string $sClass, string $sFields, string $sParamName, bool $bFailIfNotFound = true): array
	{
		$aFieldList = [$sClass => []];
		foreach (explode(',', $sFields) as $sAttCode) {
			$sAttCode = trim($sAttCode);
			if (($sAttCode == 'id') || (MetaModel::IsValidAttCode($sClass, $sAttCode))) {
				$aFieldList[$sClass][] = $sAttCode;
			} else {
				if ($bFailIfNotFound) {
					throw new Exception("$sParamName: invalid attribute code '$sAttCode' for class '$sClass'");
				}
			}
		}
		return $aFieldList;
	}

	/**
	 * Build a restricted field list for one or several classes.
	 *
	 * Accepted formats are either "att1,att2" for a single class, or
	 * "ClassA:att1,att2;ClassB:att3" for class-scoped field definitions.
	 *
	 * @param string $sClass Default class name used when no class scope is specified.
	 * @param string $sFields Requested field specification.
	 * @param string $sParamName Input parameter name used in error messages.
	 * @param bool $bFailIfNotFound If true, throws when an attribute code is invalid.
	 *
	 * @return array Array of class => list of attribute codes.
	 * @throws Exception Propagated from GetLimitedFieldListForSingleClass.
	 */
	protected static function GetLimitedFieldListForClass(string $sClass, string $sFields, string $sParamName, bool $bFailIfNotFound = true): array
	{
		if (!str_contains($sFields, ':')) {
			return self::GetLimitedFieldListForSingleClass($sClass, $sFields, $sParamName, $bFailIfNotFound);
		}

		$aFieldList = [];
		$aFieldListParts = explode(';', $sFields);
		foreach ($aFieldListParts as $sClassFields) {
			list($sSubClass, $sSubClassFields) = explode(':', $sClassFields);
			$aFieldList = array_merge($aFieldList, self::GetLimitedFieldListForSingleClass(trim($sSubClass), trim($sSubClassFields), $sParamName, $bFailIfNotFound));
		}
		return $aFieldList;
	}
	/**
	 * Read and interpret object search criteria from a Rest/Json structure
	 *
	 * @param string $sClass Name of the class
	 * @param StdClass $oCriteria Hash of attribute code => value (can be a substructure or a scalar, depending on the nature of the
	 *     attriute)
	 *
	 * @return object The object found
	 * @throws Exception If the input structure is not valid or it could not find exactly one object
	 * @api
	 */
	protected static function FindObjectFromCriteria($sClass, $oCriteria)
	{
		$aCriteriaReport = [];
		if (isset($oCriteria->finalclass)) {
			if (!MetaModel::IsValidClass($oCriteria->finalclass)) {
				throw new Exception("finalclass: Unknown class '".$oCriteria->finalclass."'");
			}
			if (!MetaModel::IsParentClass($sClass, $oCriteria->finalclass)) {
				throw new Exception("finalclass: '".$oCriteria->finalclass."' is not a child class of '$sClass'");
			}
			$sClass = $oCriteria->finalclass;
		}
		$oSearch = new DBObjectSearch($sClass);
		foreach ($oCriteria as $sAttCode => $value) {
			$realValue = static::MakeValue($sClass, $sAttCode, $value);
			$oSearch->AddCondition($sAttCode, $realValue, '=');
			if (is_object($value) || is_array($value)) {
				$value = json_encode($value);
			}
			$aCriteriaReport[] = "$sAttCode: $value ($realValue)";
		}
		$oSet = new DBObjectSet($oSearch);
		$iCount = $oSet->Count();
		if ($iCount == 0) {
			throw new Exception("No item found with criteria: ".implode(', ', $aCriteriaReport));
		} elseif ($iCount > 1) {
			throw new Exception("Several items found ($iCount) with criteria: ".implode(', ', $aCriteriaReport));
		}
		$res = $oSet->Fetch();

		return $res;
	}

	/**
	 * Find an object from a polymorph search specification (Rest/Json)
	 *
	 * @param mixed $key Either search criteria (substructure), or an object or an OQL string.
	 * @param bool $bAllowNullValue Allow the cases such as key = 0 or key = {null} and return null then
	 * @param string $sClass Name of the class
	 *
	 * @return DBObject The object found
	 * @throws Exception If the input structure is not valid or it could not find exactly one object
	 *
	 * @api
	 * @see DBObject::CheckChangedExtKeysValues() generic method to check that we can access the linked object isn't used in that use case because values can be literal, OQL, friendlyname
	 */
	public static function FindObjectFromKey($sClass, $key, $bAllowNullValue = false)
	{
		if (is_object($key)) {
			$res = static::FindObjectFromCriteria($sClass, $key);
		} elseif (is_numeric($key)) {
			if ($bAllowNullValue && ($key == 0)) {
				$res = null;
			} else {
				$res = MetaModel::GetObject($sClass, $key, false);
				if (is_null($res)) {
					throw new Exception("Invalid object $sClass::$key");
				}
			}
		} elseif (is_string($key)) {
			// OQL
			$oSearch = DBObjectSearch::FromOQL($key);
			$oSet = new DBObjectSet($oSearch);
			$iCount = $oSet->Count();
			if ($iCount == 0) {
				throw new Exception("No item found for query: $key");
			} elseif ($iCount > 1) {
				throw new Exception("Several items found ($iCount) for query: $key");
			}
			$res = $oSet->Fetch();
		} else {
			throw new Exception("Wrong format for key");
		}

		return $res;
	}

	/**
	 * Search objects from a polymorph search specification (Rest/Json)
	 *
	 * @param string $sClass Name of the class
	 * @param mixed $key Either search criteria (substructure), or an object or an OQL string.
	 * @param int $iLimit The limit of results to return
	 * @param int $iOffset The offset of results to return
	 *
	 * @return DBObjectSet The search result set
	 * @throws Exception If the input structure is not valid
	 * @api
	 */
	public static function GetObjectSetFromKey($sClass, $key, $iLimit = 0, $iOffset = 0)
	{
		if (is_object($key)) {
			if (isset($key->finalclass)) {
				$sClass = $key->finalclass;
				if (!MetaModel::IsValidClass($sClass)) {
					throw new Exception("finalclass: Unknown class '$sClass'");
				}
			}

			$oSearch = new DBObjectSearch($sClass);
			foreach ($key as $sAttCode => $value) {
				$realValue = static::MakeValue($sClass, $sAttCode, $value);
				$oSearch->AddCondition($sAttCode, $realValue, '=');
			}
		} elseif (is_numeric($key)) {
			$oSearch = new DBObjectSearch($sClass);
			$oSearch->AddCondition('id', $key);
		} elseif (is_string($key)) {
			// OQL
			try {
				$oSearch = DBObjectSearch::FromOQL($key);
			} catch (Exception $e) {
				throw new CoreOqlException('Query failed to execute', [
					'query' => $key,
					'exception_class' => get_class($e),
					'exception_message' => $e->getMessage(),
				]);
			}
		} else {
			throw new Exception("Wrong format for key");
		}
		$oObjectSet = new DBObjectSet($oSearch, [], [], null, $iLimit, $iOffset);

		return $oObjectSet;
	}

	/**
	 * Interpret the Rest/Json value and get a valid attribute value
	 *
	 * @param string $sAttCode Attribute code
	 * @param mixed $value Depending on the type of attribute (a scalar, or search criteria, or list of related objects...)
	 * @param string $sClass Name of the class
	 *
	 * @return mixed The value that can be used with DBObject::Set()
	 * @throws Exception If the specification of the value is not valid.
	 * @api
	 */
	public static function MakeValue($sClass, $sAttCode, $value)
	{
		try {
			if (!MetaModel::IsValidAttCode($sClass, $sAttCode)) {
				throw new Exception("Unknown attribute");
			}
			$oAttDef = MetaModel::GetAttributeDef($sClass, $sAttCode);
			if ($oAttDef instanceof AttributeExternalKey) {
				$oExtKeyObject = static::FindObjectFromKey($oAttDef->GetTargetClass(), $value, true /* allow null */);
				$value = ($oExtKeyObject != null) ? $oExtKeyObject->GetKey() : 0;
			} elseif ($oAttDef instanceof AttributeLinkedSet) {
				if (!is_array($value)) {
					throw new Exception("A link set must be defined by an array of objects");
				}
				$sLnkClass = $oAttDef->GetLinkedClass();
				$aLinks = [];
				foreach ($value as $oValues) {
					$oLnk = static::MakeObjectFromFields($sLnkClass, $oValues);
					// Fix for N°1939
					if (($oAttDef instanceof AttributeLinkedSetIndirect) && ($oLnk->Get($oAttDef->GetExtKeyToRemote()) == 0)) {
						continue;
					}
					$aLinks[] = $oLnk;
				}
				$value = DBObjectSet::FromArray($sLnkClass, $aLinks);
			} elseif ($oAttDef instanceof AttributeTagSet) {
				if (!is_array($value)) {
					throw new Exception("A tag set must be defined by an array of tag codes");
				}
				$value = $oAttDef->FromJSONToValue($value);
			} else {
				$value = $oAttDef->FromJSONToValue($value);
			}
		} catch (Exception $e) {
			throw new Exception("$sAttCode: ".$e->getMessage(), $e->getCode());
		}

		return $value;
	}

	/**
	 * Interpret a Rest/Json structure that defines attribute values, and build an object
	 *
	 * @param array $aFields A hash of attribute code => value specification.
	 * @param string $sClass Name of the class
	 *
	 * @return DBObject The newly created object
	 * @throws Exception If the specification of the values is not valid
	 * @api
	 */
	public static function MakeObjectFromFields($sClass, $aFields)
	{
		$oObject = MetaModel::NewObject($sClass);
		foreach ($aFields as $sAttCode => $value) {
			$realValue = static::MakeValue($sClass, $sAttCode, $value);
			try {
				$oObject->Set($sAttCode, $realValue);
			} catch (Exception $e) {
				throw new Exception("$sAttCode: ".$e->getMessage(), $e->getCode());
			}
		}

		return $oObject;
	}

	/**
	 * Interpret a Rest/Json structure that defines attribute values, and update the given object
	 *
	 * @param array $aFields A hash of attribute code => value specification.
	 * @param DBObject $oObject The object being modified
	 *
	 * @return DBObject The object modified
	 * @throws Exception If the specification of the values is not valid
	 * @api
	 */
	public static function UpdateObjectFromFields($oObject, $aFields)
	{
		$sClass = get_class($oObject);
		foreach ($aFields as $sAttCode => $value) {
			$realValue = static::MakeValue($sClass, $sAttCode, $value);
			try {
				$oObject->Set($sAttCode, $realValue);
			} catch (Exception $e) {
				throw new Exception("$sAttCode: ".$e->getMessage(), $e->getCode());
			}
		}

		return $oObject;
	}
}
