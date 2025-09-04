<?php

/**
 * Map a foreign key to an attribute
 *  AttributeExternalKey and AttributeExternalField may be an external key
 *  the difference is that AttributeExternalKey corresponds to a column into the defined table
 *  where an AttributeExternalField corresponds to a column into another table (class)
 *
 * @package     iTopORM
 */
class AttributeExternalKey extends AttributeDBFieldVoid
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

    /**
     * Return the search widget type corresponding to this attribute
     *
     * @return string
     */
    public function GetSearchType()
    {
        try {
            $oRemoteAtt = $this->GetFinalAttDef();
            $sTargetClass = $oRemoteAtt->GetTargetClass();
            if (MetaModel::IsHierarchicalClass($sTargetClass)) {
                return self::SEARCH_WIDGET_TYPE_HIERARCHICAL_KEY;
            }

            return self::SEARCH_WIDGET_TYPE_EXTERNAL_KEY;
        } catch (CoreException $e) {
        }

        return self::SEARCH_WIDGET_TYPE_RAW;
    }

    public static function ListExpectedParams()
    {
        return array_merge(parent::ListExpectedParams(), array("targetclass", "is_null_allowed", "on_target_delete"));
    }

    public function GetEditClass()
    {
        return "ExtKey";
    }

    protected function GetSQLCol($bFullSpec = false)
    {
        return "INT(11)" . ($bFullSpec ? " DEFAULT 0" : "");
    }

    public function RequiresIndex()
    {
        return true;
    }

    public function IsExternalKey($iType = EXTKEY_RELATIVE)
    {
        return true;
    }

    public function GetTargetClass($iType = EXTKEY_RELATIVE)
    {
        return $this->Get("targetclass");
    }

    public function GetKeyAttDef($iType = EXTKEY_RELATIVE)
    {
        return $this;
    }

    public function GetKeyAttCode()
    {
        return $this->GetCode();
    }

    public function GetDisplayStyle()
    {
        return $this->GetOptional('display_style', 'select');
    }


    public function GetDefaultValue(DBObject $oHostObject = null)
    {
        return 0;
    }

    public function IsNullAllowed()
    {
        if (MetaModel::GetConfig()->Get('disable_mandatory_ext_keys')) {
            return true;
        }

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

    // overloaded here so that an ext key always have the answer to
    // "what are your possible values?"
    public function GetValuesDef()
    {
        $oValSetDef = $this->Get("allowed_values");
        if (!$oValSetDef) {
            // Let's propose every existing value
            $oValSetDef = new ValueSetObjects('SELECT ' . $this->GetTargetClass());
        }

        return $oValSetDef;
    }

    public function GetAllowedValues($aArgs = array(), $sContains = '')
    {
        //throw new Exception("GetAllowedValues on ext key has been deprecated");
        try {
            return parent::GetAllowedValues($aArgs, $sContains);
        } catch (Exception $e) {
            // Some required arguments could not be found, enlarge to any existing value
            $oValSetDef = new ValueSetObjects('SELECT ' . $this->GetTargetClass());

            return $oValSetDef->GetValues($aArgs, $sContains);
        }
    }

    public function GetAllowedValuesForSelect($aArgs = array(), $sContains = '')
    {
        //$this->GetValuesDef();
        $oValSetDef = new ValueSetObjects('SELECT ' . $this->GetTargetClass());
        return $oValSetDef->GetValuesForAutocomplete($aArgs, $sContains);
    }


    public function GetAllowedValuesAsObjectSet($aArgs = array(), $sContains = '', $iAdditionalValue = null)
    {
        $oValSetDef = $this->GetValuesDef();
        $oSet = $oValSetDef->ToObjectSet($aArgs, $sContains, $iAdditionalValue);

        return $oSet;
    }

    public function GetAllowedValuesAsFilter($aArgs = array(), $sContains = '', $iAdditionalValue = null)
    {
        return DBObjectSearch::FromOQL($this->GetValuesDef()->GetFilterExpression());
    }

    public function GetDeletionPropagationOption()
    {
        return $this->Get("on_target_delete");
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

    /** @inheritdoc  @since 3.1 */
    public function WriteExternalValues(DBObject $oHostObject): void
    {
        $sTargetKey = $oHostObject->Get($this->GetCode());
        $oFilter = DBSearch::FromOQL('SELECT `' . TemporaryObjectDescriptor::class . '` WHERE item_class=:class AND item_id=:id');
        $oSet = new DBObjectSet($oFilter, [], ['class' => $this->GetTargetClass(), 'id' => $sTargetKey]);
        while ($oTemporaryObjectDescriptor = $oSet->Fetch()) {
            $oTemporaryObjectDescriptor->Set('host_class', get_class($oHostObject));
            $oTemporaryObjectDescriptor->Set('host_id', $oHostObject->GetKey());
            $oTemporaryObjectDescriptor->Set('host_att_code', $this->GetCode());
            $oTemporaryObjectDescriptor->DBUpdate();
        }
    }

    public function GetMaximumComboLength()
    {
        return $this->GetOptional('max_combo_length', MetaModel::GetConfig()->Get('max_combo_length'));
    }

    public function GetMinAutoCompleteChars()
    {
        return $this->GetOptional('min_autocomplete_chars', MetaModel::GetConfig()->Get('min_autocomplete_chars'));
    }

    /**
     * @return int
     * @since 3.0.0
     */
    public function GetMaxAutoCompleteResults(): int
    {
        return MetaModel::GetConfig()->Get('max_autocomplete_results');
    }

    public function AllowTargetCreation()
    {
        return $this->GetOptional('allow_target_creation', MetaModel::GetConfig()->Get('allow_target_creation'));
    }

    /**
     * Find the corresponding "link" attribute on the target class, if any
     *
     * @return null | AttributeDefinition
     * @throws \CoreException
     */
    public function GetMirrorLinkAttribute()
    {
        $oRet = null;
        $sRemoteClass = $this->GetTargetClass();
        foreach (MetaModel::ListAttributeDefs($sRemoteClass) as $sRemoteAttCode => $oRemoteAttDef) {
            if (!$oRemoteAttDef->IsLinkSet()) {
                continue;
            }
            if (!is_subclass_of($this->GetHostClass(),
                    $oRemoteAttDef->GetLinkedClass()) && $oRemoteAttDef->GetLinkedClass() != $this->GetHostClass()) {
                continue;
            }
            if ($oRemoteAttDef->GetExtKeyToMe() != $this->GetCode()) {
                continue;
            }
            $oRet = $oRemoteAttDef;
            break;
        }

        return $oRet;
    }

    public static function GetFormFieldClass()
    {
        return '\\Combodo\\iTop\\Form\\Field\\SelectObjectField';
    }

    public function MakeFormField(DBObject $oObject, $oFormField = null)
    {
        /** @var \Combodo\iTop\Form\Field\Field $oFormField */
        if ($oFormField === null) {
            // Later : We should check $this->Get('display_style') and create a Radio / Select / ... regarding its value
            $sFormFieldClass = static::GetFormFieldClass();
            $oFormField = new $sFormFieldClass($this->GetCode());
        }

        // Setting params
        $oFormField->SetMaximumComboLength($this->GetMaximumComboLength());
        $oFormField->SetMinAutoCompleteChars($this->GetMinAutoCompleteChars());
        $oFormField->SetMaxAutoCompleteResults($this->GetMaxAutoCompleteResults());
        $oFormField->SetHierarchical(MetaModel::IsHierarchicalClass($this->GetTargetClass()));
        // Setting choices regarding the field dependencies
        $aFieldDependencies = $this->GetPrerequisiteAttributes();
        if (!empty($aFieldDependencies)) {
            $oTmpAttDef = $this;
            $oTmpField = $oFormField;
            $oFormField->SetOnFinalizeCallback(function () use ($oTmpField, $oTmpAttDef, $oObject) {
                /** @var $oTmpField \Combodo\iTop\Form\Field\Field */
                /** @var $oTmpAttDef \AttributeDefinition */
                /** @var $oObject \DBObject */

                // We set search object only if it has not already been set (overrided)
                if ($oTmpField->GetSearch() === null) {
                    $oSearch = DBSearch::FromOQL($oTmpAttDef->GetValuesDef()->GetFilterExpression());
                    $oSearch->SetInternalParams(array('this' => $oObject));
                    $oTmpField->SetSearch($oSearch);
                }
            });
        } else {
            $oSearch = DBSearch::FromOQL($this->GetValuesDef()->GetFilterExpression());
            $oSearch->SetInternalParams(array('this' => $oObject));
            $oFormField->SetSearch($oSearch);
        }

        parent::MakeFormField($oObject, $oFormField);

        return $oFormField;
    }

    public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
    {
        if (!is_null($oHostObject)) {
            return $oHostObject->GetAsHTML($this->GetCode(), $oHostObject);
        }

        return DBObject::MakeHyperLink($this->GetTargetClass(), $sValue);
    }
}