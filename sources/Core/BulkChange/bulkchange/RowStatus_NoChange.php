<?php

class RowStatus_NoChange extends RowStatus
{
    public function GetDescription()
    {
        return Dict::S('UI:CSVReport-Row-Unchanged');
    }
}