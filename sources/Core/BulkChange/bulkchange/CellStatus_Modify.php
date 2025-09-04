<?php

class CellStatus_Modify extends CellChangeSpec
{
    protected $m_previousValue;

    public function __construct($proposedValue, $previousValue = null)
    {
        // Unused (could be costly to know -see the case of reconciliation on ext keys)
        //$this->m_previousValue = $previousValue;
        parent::__construct($proposedValue);
    }

    public function GetDescription()
    {
        return Dict::S('UI:CSVReport-Value-Modified');
    }

    //public function GetPreviousValue()
    //{
    //	return $this->m_previousValue;
    //}
}