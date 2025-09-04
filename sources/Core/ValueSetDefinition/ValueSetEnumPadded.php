<?php

class ValueSetEnumPadded extends ValueSetEnum
{
    /**
     * @inheritDoc
     * @since 3.1.0 N°6448 Add $bSortByValues parameter
     */
    public function __construct($Values, bool $bSortByValues = false)
    {
        parent::__construct($Values, $bSortByValues);
        if (is_string($Values)) {
            $this->LoadValues(null);
        } else {
            $this->m_aValues = $Values;
        }
        $aPaddedValues = array();
        foreach ($this->m_aValues as $sKey => $sVal) {
            // Pad keys to the min. length required by the \AttributeSet
            $sKey = str_pad($sKey, 3, '_', STR_PAD_LEFT);
            $aPaddedValues[$sKey] = $sVal;
        }
        $this->m_values = $aPaddedValues;
    }
}