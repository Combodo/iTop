<?php

class CellStatus_Issue extends CellStatus_Modify
{
    protected $m_sReason;

    public function __construct($proposedValue, $previousValue, $sReason)
    {
        $this->m_sReason = $sReason;
        parent::__construct($proposedValue, $previousValue);
    }

    public function GetCLIValue(bool $bLocalizedValues = false): string
    {
        if (is_null($this->m_proposedValue)) {
            return Dict::Format('UI:CSVReport-Value-SetIssue');
        }
        return Dict::Format('UI:CSVReport-Value-ChangeIssue', $this->m_proposedValue);
    }

    public function GetHTMLValue(bool $bLocalizedValues = false): string
    {
        if (is_null($this->m_proposedValue)) {
            return Dict::Format('UI:CSVReport-Value-SetIssue');
        }
        if ($this->m_proposedValue instanceof ReportValue) {
            return Dict::Format('UI:CSVReport-Value-ChangeIssue', $this->m_proposedValue->GetAsHTML($bLocalizedValues));
        }
        return Dict::Format('UI:CSVReport-Value-ChangeIssue', utils::EscapeHtml($this->m_proposedValue));
    }

    public function GetDescription()
    {
        return $this->m_sReason;
    }

    /*
     * @since 3.2.0
     */
    public function GetCLIValueAndDescription(): string
    {
        return sprintf("%s. %s",
            $this->GetCLIValue(),
            $this->GetDescription()
        );
    }
}