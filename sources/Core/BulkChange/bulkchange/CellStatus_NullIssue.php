<?php

class CellStatus_NullIssue extends CellStatus_Issue
{
    public function __construct()
    {
        parent::__construct(null, null, null);
    }

    public function GetDescription()
    {
        return Dict::S('UI:CSVReport-Value-Missing');
    }
}