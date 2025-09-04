<?php

/**
 * View of a subvalue of another attribute
 * If an attribute implements the verbs GetSubItem.... then it can expose
 * internal values, each of them being an attribute and therefore they
 * can be displayed at different times in the object lifecycle, and used for
 * reporting (as a condition in OQL, or as an additional column in an export)
 * Known usages: Stop Watches can expose threshold statuses
 */
class AttributeSubItem extends AttributeDefinition
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

    /**
     * Return the search widget type corresponding to this attribute
     * the computation is made by AttributeStopWatch::GetSubItemSearchType
     *
     * @return string
     */
    public function GetSearchType()
    {
        /** @var AttributeStopWatch $oParent */
        $oParent = $this->GetTargetAttDef();

        return $oParent->GetSubItemSearchType($this->Get('item_code'));
    }

    public function GetAllowedValues($aArgs = array(), $sContains = '')
    {
        /** @var AttributeStopWatch $oParent */
        $oParent = $this->GetTargetAttDef();

        return $oParent->GetSubItemAllowedValues($this->Get('item_code'), $aArgs, $sContains);
    }

    public function IsNullAllowed()
    {
        /** @var AttributeStopWatch $oParent */
        $oParent = $this->GetTargetAttDef();

        $bDefaultValue = parent::IsNullAllowed();

        return $oParent->IsSubItemNullAllowed($this->Get('item_code'), $bDefaultValue);
    }

    public static function ListExpectedParams()
    {
        return array_merge(parent::ListExpectedParams(), array('target_attcode', 'item_code'));
    }

    public function GetParentAttCode()
    {
        return $this->Get("target_attcode");
    }

    /**
     * Helper : get the attribute definition to which the execution will be forwarded
     */
    public function GetTargetAttDef()
    {
        $sClass = $this->GetHostClass();
        $oParentAttDef = MetaModel::GetAttributeDef($sClass, $this->Get('target_attcode'));

        return $oParentAttDef;
    }

    public function GetEditClass()
    {
        return "";
    }

    public function GetValuesDef()
    {
        return null;
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
        return false;
    }

    public function GetDefaultValue(DBObject $oHostObject = null)
    {
        return null;
    }

//	public function IsNullAllowed() {return false;}

    public static function LoadInObject()
    {
        return false;
    } // if this verb returns false, then GetValues must be implemented

    /**
     * Used by DBOBject::Get()
     *
     * @param \DBObject $oHostObject
     *
     * @return \AttributeSubItem
     * @throws \CoreException
     */
    public function GetValue($oHostObject)
    {
        /** @var \AttributeStopWatch $oParent */
        $oParent = $this->GetTargetAttDef();
        $parentValue = $oHostObject->GetStrict($oParent->GetCode());
        $res = $oParent->GetSubItemValue($this->Get('item_code'), $parentValue, $oHostObject);

        return $res;
    }

    //
//	protected function ScalarToSQL($value) {return $value;} // format value as a valuable SQL literal (quoted outside)

    public function FromSQLToValue($aCols, $sPrefix = '')
    {
    }

    public function GetSQLColumns($bFullSpec = false)
    {
        return array();
    }

    public function GetBasicFilterOperators()
    {
        return array();
    }

    public function GetBasicFilterLooseOperator()
    {
        return "=";
    }

    public function GetBasicFilterSQLExpr($sOpCode, $value)
    {
        $sQValue = CMDBSource::Quote($value);
        switch ($sOpCode) {
            case '!=':
                return $this->GetSQLExpr() . " != $sQValue";
                break;
            case '=':
            default:
                return $this->GetSQLExpr() . " = $sQValue";
        }
    }

    public function GetSQLExpressions($sPrefix = '')
    {
        $oParent = $this->GetTargetAttDef();
        $res = $oParent->GetSubItemSQLExpression($this->Get('item_code'));

        return $res;
    }

    public function GetAsPlainText($value, $oHostObject = null, $bLocalize = true)
    {
        $oParent = $this->GetTargetAttDef();
        $res = $oParent->GetSubItemAsPlainText($this->Get('item_code'), $value);

        return $res;
    }

    public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
    {
        $oParent = $this->GetTargetAttDef();
        $res = $oParent->GetSubItemAsHTML($this->Get('item_code'), $value);

        return $res;
    }

    public function GetAsHTMLForHistory($value, $oHostObject = null, $bLocalize = true)
    {
        $oParent = $this->GetTargetAttDef();
        $res = $oParent->GetSubItemAsHTMLForHistory($this->Get('item_code'), $value);

        return $res;
    }

    public function GetAsCSV(
        $value, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
        $bConvertToPlainText = false
    )
    {
        $oParent = $this->GetTargetAttDef();
        $res = $oParent->GetSubItemAsCSV($this->Get('item_code'), $value, $sSeparator, $sTextQualifier,
            $bConvertToPlainText);

        return $res;
    }

    public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
    {
        $oParent = $this->GetTargetAttDef();
        $res = $oParent->GetSubItemAsXML($this->Get('item_code'), $value);

        return $res;
    }

    /**
     * As of now, this function must be implemented to have the value in spreadsheet format
     */
    public function GetEditValue($value, $oHostObj = null)
    {
        $oParent = $this->GetTargetAttDef();
        $res = $oParent->GetSubItemAsEditValue($this->Get('item_code'), $value);

        return $res;
    }

    public function IsPartOfFingerprint()
    {
        return false;
    }

    public static function GetFormFieldClass()
    {
        return '\\Combodo\\iTop\\Form\\Field\\LabelField';
    }

    public function MakeFormField(DBObject $oObject, $oFormField = null)
    {
        if ($oFormField === null) {
            $sFormFieldClass = static::GetFormFieldClass();
            $oFormField = new $sFormFieldClass($this->GetCode());
        }
        parent::MakeFormField($oObject, $oFormField);

        // Note : As of today, this attribute is -by nature- only supported in readonly mode, not edition
        $sAttCode = $this->GetCode();
        $oFormField->SetCurrentValue(html_entity_decode($oObject->GetAsHTML($sAttCode), ENT_QUOTES, 'UTF-8'));
        $oFormField->SetReadOnly(true);

        return $oFormField;
    }

}