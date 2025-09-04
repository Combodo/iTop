<?php

class BulkExportException extends Exception
{
    protected $sLocalizedMessage;

    public function __construct($message, $sLocalizedMessage, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->sLocalizedMessage = $sLocalizedMessage;
    }

    public function GetLocalizedMessage()
    {
        return $this->sLocalizedMessage;
    }
}