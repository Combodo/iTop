<?php

class CellStatus_Ambiguous extends CellStatus_Issue
{
    protected $m_iCount;
    /**
     * @since 3.1.0 N°5305
     * @var string
     */
    protected $sSerializedSearch;

    /**
     * @param $previousValue
     * @param int $iCount
     * @param string $sSerializedSearch
     *
     * @since 3.1.0 N°5305
     *
     */
    public function __construct($previousValue, $iCount, $sSerializedSearch)
    {
        $this->m_iCount = $iCount;
        $this->sSerializedSearch = $sSerializedSearch;
        parent::__construct(null, $previousValue, '');
    }

    public function GetDescription()
    {
        $sCount = $this->m_iCount;
        return Dict::Format('UI:CSVReport-Value-Ambiguous', $sCount);
    }

    /**
     * @return string
     * @since 3.1.0 N°5305
     */
    public function GetSearchLinkUrl()
    {
        return sprintf("UI.php?operation=search&filter=%s",
            rawurlencode($this->sSerializedSearch ?? "")
        );
    }
}