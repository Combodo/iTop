<?php

/**
 * class dedicated to testability
 * not used/ignored in csv imports UI/CLI
 * @since 3.1.0 N°5305
 */
class RowStatus_Error extends RowStatus
{
    /** @var string */
    protected $m_sError;

    public function __construct($sError)
    {
        $this->m_sError = $sError;
    }

    public function GetDescription()
    {
        return $this->m_sError;
    }
}