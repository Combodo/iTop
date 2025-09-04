<?php

class RowStatus_Issue extends RowStatus
{
    protected $m_sReason;

    public function __construct($sReason)
    {
        $this->m_sReason = $sReason;
    }

    public function GetDescription()
    {
        return Dict::Format('UI:CSVReport-Row-Issue', $this->m_sReason);
    }
}