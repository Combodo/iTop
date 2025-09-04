<?php

class RowStatus_Disappeared extends RowStatus_Modify
{
    public function GetDescription()
    {
        return Dict::Format('UI:CSVReport-Row-Disappeared', $this->m_iChanged);
    }
}