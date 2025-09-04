<?php

/**
 * ValueSetDefinition
 * value sets API and implementations
 *
 * @package     iTopORM
 */
abstract class ValueSetDefinition
{
    protected $m_bIsLoaded = false;
    protected $m_aValues = array();


    // Displayable description that could be computed out of the std usage context
    public function GetValuesDescription()
    {
        $aValues = $this->GetValues(array(), '');
        $aDisplayedValues = array();
        foreach ($aValues as $key => $value) {
            $aDisplayedValues[] = "$key => $value";
        }
        $sAllowedValues = implode(', ', $aDisplayedValues);
        return $sAllowedValues;
    }

    /**
     * @param array $aArgs
     * @param string $sContains
     * @param string $sOperation for the values {@see static::LoadValues()}
     *
     * @return array hash array of keys => values
     */
    public function GetValues($aArgs, $sContains = '', $sOperation = 'contains')
    {
        if (!$this->m_bIsLoaded) {
            $this->LoadValues($aArgs);
            $this->m_bIsLoaded = true;
        }
        if (strlen($sContains) == 0) {
            // No filtering
            $aRet = $this->m_aValues;
        } else {
            // Filter on results containing the needle <sContain>
            $aRet = array();
            foreach ($this->m_aValues as $sKey => $sValue) {
                if (stripos($sValue, $sContains) !== false) {
                    $aRet[$sKey] = $sValue;
                }
            }
        }
        $this->SortValues($aRet);
        return $aRet;
    }

    /**
     * @param array $aValues Values to sort in the form keys => values
     *
     * @return void
     * @since 3.1.0 N°1646 Create method
     */
    public function SortValues(array &$aValues): void
    {
        // Sort alphabetically on values
        natcasesort($aValues);
    }

    abstract protected function LoadValues($aArgs);
}