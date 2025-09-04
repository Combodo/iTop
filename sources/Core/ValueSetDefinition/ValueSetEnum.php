<?php

/**
 * Fixed set values (could be hardcoded in the business model)
 *
 * @package     iTopORM
 */
class ValueSetEnum extends ValueSetDefinition
{
    protected $m_values;
    /**
     * @var bool $bSortByValues If true, values will be sorted at runtime (on their values, not their keys), otherwise it is sorted at compile time in a predefined order.
     *                         {@see \MFCompiler::CompileAttributeEnumValues()} for complete reasons.
     * @since 3.1.0 N°1646
     */
    protected bool $bSortByValues;

    /**
     * @param array|string $Values
     * @param bool $bLocalizedSort
     *
     * @since 3.1.0 N°1646 Add $bLocalizedSort parameter
     * @since 3.2.0 N°7157 $Values can be an array of backed-enum cases
     */
    public function __construct($Values, bool $bSortByValues = false)
    {
        $this->m_values = $Values;
        $this->bSortByValues = $bSortByValues;
    }

    /**
     * @return bool
     * @see \ValueSetEnum::$bSortByValues
     * @since 3.1.0 N°1646
     */
    public function IsSortedByValues(): bool
    {
        return $this->bSortByValues;
    }

    // Helper to export the data model
    public function GetValueList()
    {
        $this->LoadValues(null);
        return $this->m_aValues;
    }

    /**
     * @inheritDoc
     * @since 3.1.0 N°1646 Overload method
     */
    public function SortValues(array &$aValues): void
    {
        // Force sort by values only if necessary
        if ($this->bSortByValues) {
            natcasesort($aValues);
            return;
        }

        // Don't sort values as we rely on the order defined during compilation
        return;
    }

    /**
     * @param array|string $aArgs
     *
     * @return true
     */
    protected function LoadValues($aArgs)
    {
        $aValues = [];
        if (is_array($this->m_values)) {
            foreach ($this->m_values as $key => $value) {
                // Handle backed-enum case
                if (is_object($value) && enum_exists(get_class($value))) {
                    $aValues[$value->value] = $value->value;
                    continue;
                }

                $aValues[$key] = $value;
            }
        } elseif (is_string($this->m_values) && strlen($this->m_values) > 0) {
            foreach (explode(",", $this->m_values) as $sVal) {
                $sVal = trim($sVal);
                $sKey = $sVal;
                $aValues[$sKey] = $sVal;
            }
        } else {
            $aValues = [];
        }
        $this->m_aValues = $aValues;
        return true;
    }
}