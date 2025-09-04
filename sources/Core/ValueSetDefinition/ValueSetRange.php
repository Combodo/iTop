<?php

/**
 * Fixed set values, defined as a range: 0..59 (with an optional increment)
 *
 * @package     iTopORM
 */
class ValueSetRange extends ValueSetDefinition
{
    protected $m_iStart;
    protected $m_iEnd;

    public function __construct($iStart, $iEnd, $iStep = 1)
    {
        $this->m_iStart = $iStart;
        $this->m_iEnd = $iEnd;
        $this->m_iStep = $iStep;
    }

    protected function LoadValues($aArgs)
    {
        $iValue = $this->m_iStart;
        for ($iValue = $this->m_iStart; $iValue <= $this->m_iEnd; $iValue += $this->m_iStep) {
            $this->m_aValues[$iValue] = $iValue;
        }
        return true;
    }
}