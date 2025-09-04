<?php

/**
 * Set of existing values for an attribute, given a search filter
 *
 * @package     iTopORM
 */
class ValueSetObjects extends ValueSetDefinition
{
    protected $m_sContains;
    protected $m_sOperation;
    protected $m_sFilterExpr; // in OQL
    protected $m_sValueAttCode;
    protected $m_aOrderBy;
    protected $m_oExtraCondition;
    private $m_bAllowAllData;
    private $m_aModifierProperties;
    private $m_bSort;
    private $m_iLimit;


    /**
     * @param hash $aOrderBy Array of '[<classalias>.]attcode' => bAscending
     */
    public function __construct($sFilterExp, $sValueAttCode = '', $aOrderBy = array(), $bAllowAllData = false, $aModifierProperties = array())
    {
        $this->m_sContains = '';
        $this->m_sOperation = '';
        $this->m_sFilterExpr = $sFilterExp;
        $this->m_sValueAttCode = $sValueAttCode;
        $this->m_aOrderBy = $aOrderBy;
        $this->m_bAllowAllData = $bAllowAllData;
        $this->m_aModifierProperties = $aModifierProperties;
        $this->m_oExtraCondition = null;
        $this->m_bSort = true;
        $this->m_iLimit = 0;
    }

    public function SetModifierProperty($sPluginClass, $sProperty, $value)
    {
        $this->m_aModifierProperties[$sPluginClass][$sProperty] = $value;
        $this->m_bIsLoaded = false;
    }

    /**
     * @param \DBSearch $oFilter
     * @deprecated use SetCondition instead
     *
     */
    public function AddCondition(DBSearch $oFilter)
    {
        DeprecatedCallsLog::NotifyDeprecatedPhpMethod('use SetCondition instead');
        $this->SetCondition($oFilter);
    }

    public function SetCondition(DBSearch $oFilter)
    {
        $this->m_oExtraCondition = $oFilter;
        $this->m_bIsLoaded = false;
    }

    public function SetOrderBy(array $aOrderBy)
    {
        $this->m_aOrderBy = $aOrderBy;
    }

    public function ToObjectSet($aArgs = array(), $sContains = '', $iAdditionalValue = null)
    {
        if ($this->m_bAllowAllData) {
            $oFilter = DBObjectSearch::FromOQL_AllData($this->m_sFilterExpr);
        } else {
            $oFilter = DBObjectSearch::FromOQL($this->m_sFilterExpr);
        }
        if (!is_null($this->m_oExtraCondition)) {
            $oFilter = $oFilter->Intersect($this->m_oExtraCondition);
        }
        foreach ($this->m_aModifierProperties as $sPluginClass => $aProperties) {
            foreach ($aProperties as $sProperty => $value) {
                $oFilter->SetModifierProperty($sPluginClass, $sProperty, $value);
            }
        }
        if ($iAdditionalValue > 0) {
            $oSearchAdditionalValue = new DBObjectSearch($oFilter->GetClass());
            $oSearchAdditionalValue->AddConditionExpression(new BinaryExpression(
                    new FieldExpression('id', $oSearchAdditionalValue->GetClassAlias()),
                    '=',
                    new VariableExpression('current_extkey_id'))
            );
            $oSearchAdditionalValue->AllowAllData();
            $oSearchAdditionalValue->SetArchiveMode(true);
            $oSearchAdditionalValue->SetInternalParams(array('current_extkey_id' => $iAdditionalValue));

            $oFilter = new DBUnionSearch(array($oFilter, $oSearchAdditionalValue));
        }

        return new DBObjectSet($oFilter, $this->m_aOrderBy, $aArgs);
    }

    /**
     * @inheritDoc
     * @throws CoreException
     * @throws OQLException
     */
    public function GetValues($aArgs, $sContains = '', $sOperation = 'contains')
    {
        if (!$this->m_bIsLoaded || ($sContains != $this->m_sContains) || ($sOperation != $this->m_sOperation)) {
            $this->LoadValues($aArgs, $sContains, $sOperation);
            $this->m_bIsLoaded = true;
        }
        // The results are already filtered and sorted (on friendly name)
        $aRet = $this->m_aValues;
        return $aRet;
    }

    /**
     * @param $aArgs
     * @param string $sContains
     * @param string $sOperation 'contains' or 'equals_start_with'
     *
     * @return bool
     * @throws \CoreException
     * @throws \OQLException
     */
    protected function LoadValues($aArgs, $sContains = '', $sOperation = 'contains')
    {
        $this->m_sContains = $sContains;
        $this->m_sOperation = $sOperation;

        $this->m_aValues = array();

        $oFilter = $this->GetFilter($sOperation, $sContains);

        $oObjects = new DBObjectSet($oFilter, $this->m_aOrderBy, $aArgs, null, $this->m_iLimit, 0, $this->m_bSort);
        if (empty($this->m_sValueAttCode)) {
            $aAttToLoad = array($oFilter->GetClassAlias() => array('friendlyname'));
        } else {
            $aAttToLoad = array($oFilter->GetClassAlias() => array($this->m_sValueAttCode));
        }
        $oObjects->OptimizeColumnLoad($aAttToLoad);
        while ($oObject = $oObjects->Fetch()) {
            if (empty($this->m_sValueAttCode)) {
                $this->m_aValues[$oObject->GetKey()] = $oObject->GetName();
            } else {
                $this->m_aValues[$oObject->GetKey()] = $oObject->Get($this->m_sValueAttCode);
            }
        }

        return true;
    }


    /**
     * Get filter for functions LoadValues and LoadValuesForAutocomplete
     *
     * @param $sOperation
     * @param $sContains
     *
     * @return \DBObjectSearch|\DBSearch|\DBUnionSearch|false|mixed
     * @throws \CoreException
     * @throws \OQLException
     * @since 3.0.3 3.1.0
     */
    protected function GetFilter($sOperation, $sContains)
    {
        $this->m_sContains = $sContains;
        $this->m_sOperation = $sOperation;

        if ($this->m_bAllowAllData) {
            $oFilter = DBObjectSearch::FromOQL_AllData($this->m_sFilterExpr);
        } else {
            $oFilter = DBObjectSearch::FromOQL($this->m_sFilterExpr);
            $oFilter->SetShowObsoleteData(utils::ShowObsoleteData());
        }
        if (!$oFilter) {
            return false;
        }
        if (!is_null($this->m_oExtraCondition)) {
            $oFilter = $oFilter->Intersect($this->m_oExtraCondition);
        }
        foreach ($this->m_aModifierProperties as $sPluginClass => $aProperties) {
            foreach ($aProperties as $sProperty => $value) {
                $oFilter->SetModifierProperty($sPluginClass, $sProperty, $value);
            }
        }

        $sClass = $oFilter->GetClass();

        switch ($this->m_sOperation) {
            case 'equals':
            case 'start_with':
                if ($this->m_sOperation === 'start_with') {
                    $this->m_sContains .= '%';
                    $sOperator = 'LIKE';
                } else {
                    $sOperator = '=';
                }

                $aAttributes = MetaModel::GetFriendlyNameAttributeCodeList($sClass);
                if (count($aAttributes) > 0) {
                    $sClassAlias = $oFilter->GetClassAlias();
                    $aFilters = array();
                    $oValueExpr = new ScalarExpression($this->m_sContains);
                    foreach ($aAttributes as $sAttribute) {
                        $oNewFilter = $oFilter->DeepClone();
                        $oNameExpr = new FieldExpression($sAttribute, $sClassAlias);
                        $oCondition = new BinaryExpression($oNameExpr, $sOperator, $oValueExpr);
                        $oNewFilter->AddConditionExpression($oCondition);
                        $aFilters[] = $oNewFilter;
                    }
                    // Unions are much faster than OR conditions
                    $oFilter = new DBUnionSearch($aFilters);
                } else {
                    $oValueExpr = new ScalarExpression($this->m_sContains);
                    $oNameExpr = new FieldExpression('friendlyname', $oFilter->GetClassAlias());
                    $oNewCondition = new BinaryExpression($oNameExpr, $sOperator, $oValueExpr);
                    $oFilter->AddConditionExpression($oNewCondition);
                }
                break;

            default:
                $oValueExpr = new ScalarExpression('%' . $this->m_sContains . '%');
                $oNameExpr = new FieldExpression('friendlyname', $oFilter->GetClassAlias());
                $oNewCondition = new BinaryExpression($oNameExpr, 'LIKE', $oValueExpr);
                $oFilter->AddConditionExpression($oNewCondition);
                break;
        }

        return $oFilter;
    }

    public function GetValuesDescription()
    {
        return 'Filter: ' . $this->m_sFilterExpr;
    }

    public function GetFilterExpression()
    {
        return $this->m_sFilterExpr;
    }

    /**
     * @param $iLimit
     */
    public function SetLimit($iLimit)
    {
        $this->m_iLimit = $iLimit;
    }

    /**
     * @param $bSort
     */
    public function SetSort($bSort)
    {
        $this->m_bSort = $bSort;
    }

    public function GetValuesForAutocomplete($aArgs, $sContains = '', $sOperation = 'contains')
    {
        if (!$this->m_bIsLoaded || ($sContains != $this->m_sContains) || ($sOperation != $this->m_sOperation)) {
            $this->LoadValuesForAutocomplete($aArgs, $sContains, $sOperation);
            $this->m_bIsLoaded = true;
        }
        // The results are already filtered and sorted (on friendly name)
        $aRet = $this->m_aValues;
        return $aRet;
    }

    /**
     * @param $aArgs
     * @param string $sContains
     * @param string $sOperation 'contains' or 'equals_start_with'
     *
     * @return bool
     * @throws \CoreException
     * @throws \OQLException
     */
    protected function LoadValuesForAutocomplete($aArgs, $sContains = '', $sOperation = 'contains')
    {
        $this->m_aValues = array();

        $oFilter = $this->GetFilter($sOperation, $sContains);
        $sClass = $oFilter->GetClass();
        $sClassAlias = $oFilter->GetClassAlias();

        $oObjects = new DBObjectSet($oFilter, $this->m_aOrderBy, $aArgs, null, $this->m_iLimit, 0, $this->m_bSort);
        if (empty($this->m_sValueAttCode)) {
            $aAttToLoad = ['friendlyname'];
        } else {
            $aAttToLoad = [$this->m_sValueAttCode];
        }

        $sImageAttr = MetaModel::GetImageAttributeCode($sClass);
        if (!empty($sImageAttr)) {
            $aAttToLoad [] = $sImageAttr;
        }

        $aComplementAttributeSpec = MetaModel::GetNameSpec($sClass, \Combodo\iTop\Core\MetaModel\FriendlyNameType::COMPLEMENTARY);
        $sFormatAdditionalField = $aComplementAttributeSpec[0];
        $aAdditionalField = $aComplementAttributeSpec[1];

        if (count($aAdditionalField) > 0) {
            if (is_array($aAdditionalField)) {
                $aAttToLoad = array_merge($aAttToLoad, $aAdditionalField);
            } else {
                $aAttToLoad [] = $aAdditionalField;
            }
        }

        $oObjects->OptimizeColumnLoad([$sClassAlias => $aAttToLoad]);
        while ($oObject = $oObjects->Fetch()) {
            $aData = [];
            if (empty($this->m_sValueAttCode)) {
                $aData['label'] = $oObject->GetName();
            } else {
                $aData['label'] = $oObject->Get($this->m_sValueAttCode);
            }
            if ($oObject->IsObsolete()) {
                $aData['obsolescence_flag'] = '1';
            } else {
                $aData['obsolescence_flag'] = '0';
            }
            if (count($aAdditionalField) > 0) {
                $aArguments = [];
                foreach ($aAdditionalField as $sAdditionalField) {
                    array_push($aArguments, $oObject->Get($sAdditionalField));
                }
                $aData['additional_field'] = utils::VSprintf($sFormatAdditionalField, $aArguments);
            } else {
                $aData['additional_field'] = '';
            }
            if (!empty($sImageAttr)) {
                /** @var \ormDocument $oImage */
                $oImage = $oObject->Get($sImageAttr);
                if (!$oImage->IsEmpty()) {
                    $aData['picture_url'] = $oImage->GetDisplayURL($sClass, $oObject->GetKey(), $sImageAttr);
                    $aData['initials'] = '';
                } else {
                    $aData['initials'] = utils::ToAcronym($aData['label']);
                }
            }
            $this->m_aValues[$oObject->GetKey()] = $aData;
        }
        return true;
    }
}