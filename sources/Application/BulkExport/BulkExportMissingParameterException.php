<?php

class BulkExportMissingParameterException extends BulkExportException
{
    public function __construct($sFieldCode)
    {
        parent::__construct('Missing parameter: ' . $sFieldCode, Dict::Format('Core:BulkExport:MissingParameter_Param', $sFieldCode));
    }

}