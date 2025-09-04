<?php

class CellStatus_SearchIssue extends CellStatus_Issue
{
    /** @var string|null $m_sAllowedValues */
    private $m_sAllowedValues;

    /**
     * @since 3.1.0 N°5305
     * @var string $sSerializedSearch
     */
    private $sSerializedSearch;

    /** @var string|null $m_sTargetClass */
    private $m_sTargetClass;

    /**
     * @since 3.1.0 N°5305
     * @var string $sAllowedValuesSearch
     */
    private $sAllowedValuesSearch;

    /**
     * CellStatus_SearchIssue constructor.
     * @param string $sOql : main message
     * @param string $sReason : main message
     * @param null $sClass : used for additional message that provides allowed values for current class $sClass
     * @param null $sAllowedValues : used for additional message that provides allowed values $sAllowedValues for current class
     * @param string|null $sAllowedValuesSearch : used to search all allowed values
     * @since 3.1.0 N°5305
     *
     */
    public function __construct($sSerializedSearch, $sReason, $sClass = null, $sAllowedValues = null, string $sAllowedValuesSearch = null)
    {
        parent::__construct(null, null, $sReason);
        $this->sSerializedSearch = $sSerializedSearch;
        $this->m_sAllowedValues = $sAllowedValues;
        $this->m_sTargetClass = $sClass;
        $this->sAllowedValuesSearch = $sAllowedValuesSearch;
    }

    public function GetCLIValue(bool $bLocalizedValues = false): string
    {
        if (null === $this->m_sReason) {
            return Dict::Format('UI:CSVReport-Value-NoMatch', '');
        }

        return $this->m_sReason;
    }

    public function GetHTMLValue(bool $bLocalizedValues = false): string
    {
        if (null === $this->m_sReason) {
            return Dict::Format('UI:CSVReport-Value-NoMatch', '');
        }

        return utils::EscapeHtml($this->m_sReason);
    }

    public function GetDescription()
    {
        if (\utils::IsNullOrEmptyString($this->m_sAllowedValues) ||
            \utils::IsNullOrEmptyString($this->m_sTargetClass)) {
            return '';
        }

        return Dict::Format('UI:CSVReport-Value-NoMatch-PossibleValues', $this->m_sTargetClass, $this->m_sAllowedValues);
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

    /**
     * @return null|string
     * @since 3.1.0 N°5305
     */
    public function GetAllowedValuesLinkUrl(): ?string
    {
        return sprintf("UI.php?operation=search&filter=%s",
            rawurlencode($this->sAllowedValuesSearch ?? "")
        );
    }
}