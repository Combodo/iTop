<?php

/**
 * An attribute which corresponds to an external key (direct or indirect)
 *
 * @package     iTopORM
 */
class AttributeExternalField extends AttributeDefinition
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
     *
     * @return string
     * @throws \CoreException
     */
    public function GetSearchType()
    {
        // Not necessary the external key is already present
        if ($this->IsFriendlyName()) {
            return self::SEARCH_WIDGET_TYPE_RAW;
        }

        try {
            $oRemoteAtt = $this->GetFinalAttDef();
            switch (true) {
                case ($oRemoteAtt instanceof AttributeString):
                    return self::SEARCH_WIDGET_TYPE_EXTERNAL_FIELD;
                case ($oRemoteAtt instanceof AttributeExternalKey):
                    return self::SEARCH_WIDGET_TYPE_EXTERNAL_KEY;
            }
        } catch (CoreException $e) {
        }

        return self::SEARCH_WIDGET_TYPE_RAW;
    }

    function IsSearchable()
    {
        if ($this->IsFriendlyName()) {
            return true;
        }
        return parent::IsSearchable();
    }

    public static function ListExpectedParams()
    {
        return array_merge(parent::ListExpectedParams(), array("extkey_attcode", "target_attcode"));
    }

    public function GetEditClass()
    {
        return "ExtField";
    }

    /**
     * @return \AttributeDefinition
     * @throws \CoreException
     */
    public function GetFinalAttDef()
    {
        $oExtAttDef = $this->GetExtAttDef();

        return $oExtAttDef->GetFinalAttDef();
    }

    protected function GetSQLCol($bFullSpec = false)
    {
        // throw new CoreException("external attribute: does it make any sense to request its type ?");
        $oExtAttDef = $this->GetExtAttDef();

        return $oExtAttDef->GetSQLCol($bFullSpec);
    }

    public function GetSQLExpressions($sPrefix = '')
    {
        if ($sPrefix == '') {
            return array('' => $this->GetCode()); // Warning: Use GetCode() since AttributeExternalField does not have any 'sql' property
        } else {
            return $sPrefix;
        }
    }

    /**
     * @param string $sDefault
     *
     * @return string dict entry if defined, otherwise :
     *    <ul>
     *    <li>if field is a friendlyname then display the label of the ExternalKey
     *    <li>the class hierarchy -> field name
     *
     *    <p>For example, having this :
     *
     * <pre>
     *       +---------------------+     +--------------------+      +--------------+
     *       | Class A             |     | Class B            |      | Class C      |
     *       +---------------------+     +--------------------+      +--------------+
     *       | foo <ExternalField>-------->c_id_friendly_name--------->friendlyname |
     *       +---------------------+     +--------------------+      +--------------+
     * </pre>
     *
     *       <p>The ExternalField foo points to a magical field that is brought by c_id ExternalKey in class B.
     *
     *       <p>In the normal case the foo label would be : B -> C -> friendlyname<br>
     *       But as foo is a friendlyname its label will be the same as the one on A.b_id field
     *       This can be overrided with dict key Class:ClassA/Attribute:foo
     *
     * @throws \CoreException
     * @throws \Exception
     */
    public function GetLabel($sDefault = null)
    {
        $sLabelDefaultValue = '';
        $sLabel = parent::GetLabel($sLabelDefaultValue);
        if ($sLabelDefaultValue !== $sLabel) {
            return $sLabel;
        }

        if ($this->IsFriendlyName() && ($this->Get("target_attcode") === "friendlyname")) {
            // This will be used even if we are pointing to a friendlyname in a distance > 1
            // For example we can link to a magic friendlyname (like org_id_friendlyname)
            // If a specific label is needed, use a Dict key !
            // See N°2174
            $sKeyAttCode = $this->Get("extkey_attcode");
            $oExtKeyAttDef = MetaModel::GetAttributeDef($this->GetHostClass(), $sKeyAttCode);
            $sLabel = $oExtKeyAttDef->GetLabel($this->m_sCode);

            return $sLabel;
        }

        $oRemoteAtt = $this->GetExtAttDef();
        $sLabel = $oRemoteAtt->GetLabel($this->m_sCode);
        $oKeyAtt = $this->GetKeyAttDef();
        $sKeyLabel = $oKeyAtt->GetLabel($this->GetKeyAttCode());
        $sLabel = "{$sKeyLabel}->{$sLabel}";

        return $sLabel;
    }

    public function GetLabelForSearchField()
    {
        $sLabel = parent::GetLabel('');
        if (strlen($sLabel) == 0) {
            $sKeyAttCode = $this->Get("extkey_attcode");
            $oExtKeyAttDef = MetaModel::GetAttributeDef($this->GetHostClass(), $sKeyAttCode);
            $sLabel = $oExtKeyAttDef->GetLabel($this->m_sCode);

            $oRemoteAtt = $this->GetExtAttDef();
            $sLabel .= '->' . $oRemoteAtt->GetLabel($this->m_sCode);
        }

        return $sLabel;
    }

    public function GetDescription($sDefault = null)
    {
        $sLabel = parent::GetDescription('');
        if (strlen($sLabel) == 0) {
            $oRemoteAtt = $this->GetExtAttDef();
            $sLabel = $oRemoteAtt->GetDescription('');
        }

        return $sLabel;
    }

    public function GetHelpOnEdition($sDefault = null)
    {
        $sLabel = parent::GetHelpOnEdition('');
        if (strlen($sLabel) == 0) {
            $oRemoteAtt = $this->GetExtAttDef();
            $sLabel = $oRemoteAtt->GetHelpOnEdition('');
        }

        return $sLabel;
    }

    public function IsExternalKey($iType = EXTKEY_RELATIVE)
    {
        switch ($iType) {
            case EXTKEY_ABSOLUTE:
                // see further
                $oRemoteAtt = $this->GetExtAttDef();

                return $oRemoteAtt->IsExternalKey($iType);

            case EXTKEY_RELATIVE:
                return false;

            default:
                throw new CoreException("Unexpected value for argument iType: '$iType'");
        }
    }

    /**
     * @return bool
     * @throws \CoreException
     */
    public function IsFriendlyName()
    {
        $oRemoteAtt = $this->GetExtAttDef();
        if ($oRemoteAtt instanceof AttributeExternalField) {
            $bRet = $oRemoteAtt->IsFriendlyName();
        } elseif ($oRemoteAtt instanceof AttributeFriendlyName) {
            $bRet = true;
        } else {
            $bRet = false;
        }

        return $bRet;
    }

    public function GetTargetClass($iType = EXTKEY_RELATIVE)
    {
        return $this->GetKeyAttDef($iType)->GetTargetClass();
    }

    public static function IsExternalField()
    {
        return true;
    }

    public function GetKeyAttCode()
    {
        return $this->Get("extkey_attcode");
    }

    public function GetExtAttCode()
    {
        return $this->Get("target_attcode");
    }

    /**
     * @param int $iType
     *
     * @return \AttributeExternalKey
     * @throws \CoreException
     * @throws \Exception
     */
    public function GetKeyAttDef($iType = EXTKEY_RELATIVE)
    {
        switch ($iType) {
            case EXTKEY_ABSOLUTE:
                // see further
                /** @var \AttributeExternalKey $oRemoteAtt */
                $oRemoteAtt = $this->GetExtAttDef();
                if ($oRemoteAtt->IsExternalField()) {
                    return $oRemoteAtt->GetKeyAttDef(EXTKEY_ABSOLUTE);
                } else {
                    if ($oRemoteAtt->IsExternalKey()) {
                        return $oRemoteAtt;
                    }
                }

                return $this->GetKeyAttDef(EXTKEY_RELATIVE); // which corresponds to the code hereafter !

            case EXTKEY_RELATIVE:
                /** @var \AttributeExternalKey $oAttDef */
                $oAttDef = MetaModel::GetAttributeDef($this->GetHostClass(), $this->Get("extkey_attcode"));

                return $oAttDef;

            default:
                throw new CoreException("Unexpected value for argument iType: '$iType'");
        }
    }

    public function GetPrerequisiteAttributes($sClass = null)
    {
        return array($this->Get("extkey_attcode"));
    }


    /**
     * @return \AttributeExternalField
     * @throws \CoreException
     * @throws \Exception
     */
    public function GetExtAttDef()
    {
        $oKeyAttDef = $this->GetKeyAttDef();
        /** @var \AttributeExternalField $oExtAttDef */
        $oExtAttDef = MetaModel::GetAttributeDef($oKeyAttDef->GetTargetClass(), $this->Get("target_attcode"));
        if (!is_object($oExtAttDef)) {
            throw new CoreException("Invalid external field " . $this->GetCode() . " in class " . $this->GetHostClass() . ". The class " . $oKeyAttDef->GetTargetClass() . " has no attribute " . $this->Get("target_attcode"));
        }

        return $oExtAttDef;
    }

    /**
     * @return mixed
     * @throws \CoreException
     */
    public function GetSQLExpr()
    {
        $oExtAttDef = $this->GetExtAttDef();

        return $oExtAttDef->GetSQLExpr();
    }

    public function GetDefaultValue(DBObject $oHostObject = null)
    {
        $oExtAttDef = $this->GetExtAttDef();

        return $oExtAttDef->GetDefaultValue();
    }

    public function IsNullAllowed()
    {
        $oExtAttDef = $this->GetExtAttDef();

        return $oExtAttDef->IsNullAllowed();
    }

    public static function IsScalar()
    {
        return true;
    }

    public function GetBasicFilterOperators()
    {
        $oExtAttDef = $this->GetExtAttDef();

        return $oExtAttDef->GetBasicFilterOperators();
    }

    public function GetBasicFilterLooseOperator()
    {
        $oExtAttDef = $this->GetExtAttDef();

        return $oExtAttDef->GetBasicFilterLooseOperator();
    }

    public function GetBasicFilterSQLExpr($sOpCode, $value)
    {
        $oExtAttDef = $this->GetExtAttDef();

        return $oExtAttDef->GetBasicFilterSQLExpr($sOpCode, $value);
    }

    public function GetNullValue()
    {
        $oExtAttDef = $this->GetExtAttDef();

        return $oExtAttDef->GetNullValue();
    }

    public function IsNull($proposedValue)
    {
        $oExtAttDef = $this->GetExtAttDef();

        return $oExtAttDef->IsNull($proposedValue);
    }

    /**
     * @inheritDoc
     */
    public function HasAValue($proposedValue): bool
    {
        $oExtAttDef = $this->GetExtAttDef();

        return $oExtAttDef->HasAValue($proposedValue);
    }

    public function MakeRealValue($proposedValue, $oHostObj)
    {
        $oExtAttDef = $this->GetExtAttDef();

        return $oExtAttDef->MakeRealValue($proposedValue, $oHostObj);
    }

    /**
     * @inheritDoc
     * @since 3.1.0 N°6271 Delegate to remote attribute to ensure cascading computed values
     */
    public function GetSQLValues($value)
    {
        $oExtAttDef = $this->GetExtAttDef();

        return $oExtAttDef->GetSQLValues($value);
    }

    public function ScalarToSQL($value)
    {
        // This one could be used in case of filtering only
        $oExtAttDef = $this->GetExtAttDef();

        return $oExtAttDef->ScalarToSQL($value);
    }


    // Do not overload GetSQLExpression here because this is handled in the joins
    //public function GetSQLExpressions($sPrefix = '') {return array();}

    // Here, we get the data...
    public function FromSQLToValue($aCols, $sPrefix = '')
    {
        $oExtAttDef = $this->GetExtAttDef();

        return $oExtAttDef->FromSQLToValue($aCols, $sPrefix);
    }

    public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
    {
        $oExtAttDef = $this->GetExtAttDef();

        return $oExtAttDef->GetAsHTML($value, null, $bLocalize);
    }

    public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
    {
        $oExtAttDef = $this->GetExtAttDef();

        return $oExtAttDef->GetAsXML($value, null, $bLocalize);
    }

    public function GetAsCSV(
        $value, $sSeparator = ',', $sTestQualifier = '"', $oHostObject = null, $bLocalize = true,
        $bConvertToPlainText = false
    )
    {
        $oExtAttDef = $this->GetExtAttDef();

        return $oExtAttDef->GetAsCSV($value, $sSeparator, $sTestQualifier, null, $bLocalize, $bConvertToPlainText);
    }

    public static function GetFormFieldClass()
    {
        return '\\Combodo\\iTop\\Form\\Field\\LabelField';
    }

    /**
     * @param \DBObject $oObject
     * @param \Combodo\iTop\Form\Field\Field $oFormField
     *
     * @return null
     * @throws \CoreException
     */
    public function MakeFormField(DBObject $oObject, $oFormField = null)
    {
        // Retrieving AttDef from the remote attribute
        $oRemoteAttDef = $this->GetExtAttDef();

        if ($oFormField === null) {
            // ExternalField's FormField are actually based on the FormField from the target attribute.
            // Except for the AttributeExternalKey because we have no OQL and stuff
            if ($oRemoteAttDef instanceof AttributeExternalKey) {
                $sFormFieldClass = static::GetFormFieldClass();
            } else {
                $sFormFieldClass = $oRemoteAttDef::GetFormFieldClass();
            }
            /** @var \Combodo\iTop\Form\Field\Field $oFormField */
            $oFormField = new $sFormFieldClass($this->GetCode());
            switch ($sFormFieldClass) {
                case '\Combodo\iTop\Form\Field\SelectField':
                    $oFormField->SetChoices($oRemoteAttDef->GetAllowedValues($oObject->ToArgsForQuery()));
                    break;
                default:
                    break;
            }
        }
        parent::MakeFormField($oObject, $oFormField);
        if ($oFormField instanceof \Combodo\iTop\Form\Field\TextAreaField) {
            if (method_exists($oRemoteAttDef, 'GetFormat')) {
                /** @var \Combodo\iTop\Form\Field\TextAreaField $oFormField */
                $oFormField->SetFormat($oRemoteAttDef->GetFormat());
            }
        }

        // Manually setting for remote ExternalKey, otherwise, the id would be displayed.
        if ($oRemoteAttDef instanceof AttributeExternalKey) {
            $oFormField->SetCurrentValue($oObject->Get($this->GetCode() . '_friendlyname'));
        }

        // Readonly field because we can't update external fields
        $oFormField->SetReadOnly(true);

        return $oFormField;
    }

    public function IsPartOfFingerprint()
    {
        return false;
    }

    public function GetFormat()
    {
        $oRemoteAttDef = $this->GetExtAttDef();
        if (method_exists($oRemoteAttDef, 'GetFormat')) {
            /** @var \Combodo\iTop\Form\Field\TextAreaField $oFormField */
            return $oRemoteAttDef->GetFormat();
        }
        return 'text';
    }
}