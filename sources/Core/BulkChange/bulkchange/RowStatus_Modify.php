<?php

class RowStatus_Modify extends RowStatus
{
    protected $m_iChanged;

    public function __construct($iChanged)
    {
        $this->m_iChanged = $iChanged;
    }

    public function GetDescription()
    {
        return Dict::Format('UI:CSVReport-Row-Updated', $this->m_iChanged);
    }
}