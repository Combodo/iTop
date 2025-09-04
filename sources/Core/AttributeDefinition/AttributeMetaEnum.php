<?php

/**
 * A meta enum is an aggregation of enum from subclasses into an enum of a base class
 * It has been designed is to cope with the fact that statuses must be defined in leaf classes, while it makes sense to
 * have a superstatus available on the root classe(s)
 *
 * @package     iTopORM
 */
class AttributeMetaEnum extends AttributeEnum
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
     * @throws \Exception
     * @noinspection SenselessProxyMethodInspection
     */
    public function __construct($sCode, $aParams)
    {
        parent::__construct($sCode, $aParams);
    }

    public static function ListExpectedParams()
    {
        return array('allowed_values', 'sql', 'default_value', 'mapping');
    }

    public function IsNullAllowed()
    {
        return false; // Well... this actually depends on the mapping
    }

    public function IsWritable()
    {
        return false;
    }

    public function RequiresIndex()
    {
        return true;
    }

    public function GetPrerequisiteAttributes($sClass = null)
    {
        if (is_null($sClass)) {
            $sClass = $this->GetHostClass();
        }
        $aMappingData = $this->GetMapRule($sClass);
        if ($aMappingData == null) {
            $aRet = array();
        } else {
            $aRet = array($aMappingData['attcode']);
        }

        return $aRet;
    }

    /**
     * Overload the standard so as to leave the data unsorted
     *
     * @param array $aArgs
     * @param string $sContains
     *
     * @return array|null
     */
    public function GetAllowedValues($aArgs = array(), $sContains = '')
    {
        $oValSetDef = $this->GetValuesDef();
        if (!$oValSetDef) {
            return null;
        }
        $aRawValues = $oValSetDef->GetValueList();

        if (is_null($aRawValues)) {
            return null;
        }
        $aLocalizedValues = array();
        foreach ($aRawValues as $sKey => $sValue) {
            $aLocalizedValues[$sKey] = $this->GetValueLabel($sKey);
        }

        return $aLocalizedValues;
    }

    /**
     * Returns the meta value for the given object.
     * See also MetaModel::RebuildMetaEnums() that must be maintained when MapValue changes
     *
     * @param $oObject
     *
     * @return mixed
     * @throws Exception
     */
    public function MapValue($oObject)
    {
        $aMappingData = $this->GetMapRule(get_class($oObject));
        if ($aMappingData == null) {
            $sRet = $this->GetDefaultValue();
        } else {
            $sAttCode = $aMappingData['attcode'];
            $value = $oObject->Get($sAttCode);
            if (array_key_exists($value, $aMappingData['values'])) {
                $sRet = $aMappingData['values'][$value];
            } elseif ($this->GetDefaultValue() != '') {
                $sRet = $this->GetDefaultValue();
            } else {
                throw new Exception('AttributeMetaEnum::MapValue(): mapping not found for value "' . $value . '" in ' . get_class($oObject) . ', on attribute ' . MetaModel::GetAttributeOrigin($this->GetHostClass(),
                        $this->GetCode()) . '::' . $this->GetCode());
            }
        }

        return $sRet;
    }

    public function GetMapRule($sClass)
    {
        $aMappings = $this->Get('mapping');
        if (array_key_exists($sClass, $aMappings)) {
            $aMappingData = $aMappings[$sClass];
        } else {
            $sParent = MetaModel::GetParentClass($sClass);
            if (is_null($sParent)) {
                $aMappingData = null;
            } else {
                $aMappingData = $this->GetMapRule($sParent);
            }
        }

        return $aMappingData;
    }
}