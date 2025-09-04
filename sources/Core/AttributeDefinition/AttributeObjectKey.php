<?php

/**
 * An external key for which the class is defined as the value of another attribute
 *
 * @package     iTopORM
 */
class AttributeObjectKey extends AttributeDBFieldVoid
{
    const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_EXTERNAL_KEY;

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
        return array_merge(parent::ListExpectedParams(), array('class_attcode', 'is_null_allowed'));
    }

    public function GetEditClass()
    {
        return "String";
    }

    protected function GetSQLCol($bFullSpec = false)
    {
        return "INT(11)" . ($bFullSpec ? " DEFAULT 0" : "");
    }

    public function GetDefaultValue(DBObject $oHostObject = null)
    {
        return 0;
    }

    public function IsNullAllowed()
    {
        return $this->Get("is_null_allowed");
    }


    public function GetBasicFilterOperators()
    {
        return parent::GetBasicFilterOperators();
    }

    public function GetBasicFilterLooseOperator()
    {
        return parent::GetBasicFilterLooseOperator();
    }

    public function GetBasicFilterSQLExpr($sOpCode, $value)
    {
        return parent::GetBasicFilterSQLExpr($sOpCode, $value);
    }

    public function GetNullValue()
    {
        return 0;
    }

    public function IsNull($proposedValue)
    {
        return ($proposedValue == 0);
    }

    /**
     * @inheritDoc
     */
    public function HasAValue($proposedValue): bool
    {
        return ((int)$proposedValue) !== 0;
    }

    /**
     * @inheritDoc
     *
     * @param int|DBObject $proposedValue Object key or valid ({@see MetaModel::IsValidObject()}) datamodel object
     */
    public function MakeRealValue($proposedValue, $oHostObj)
    {
        if (is_null($proposedValue)) {
            return 0;
        }
        if ($proposedValue === '') {
            return 0;
        }
        if (MetaModel::IsValidObject($proposedValue)) {
            return $proposedValue->GetKey();
        }

        return (int)$proposedValue;
    }
}