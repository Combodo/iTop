<?php

class RowStatus_NewObj extends RowStatus
{
    public function GetDescription()
    {
        return Dict::S('UI:CSVReport-Row-Created');
    }
}