<?php

/**
 * CellChangeSpec
 * A series of classes, keeping the information about a given cell: could it be changed or not (and why)?
 *
 * @package     iTopORM
 */
abstract class CellChangeSpec
{
    protected $m_proposedValue;
    protected $m_sOql; // in case of ambiguity

    public function __construct($proposedValue, $sOql = '')
    {
        $this->m_proposedValue = $proposedValue;
        $this->m_sOql = $sOql;
    }

    public function GetPureValue()
    {
        // Todo - distinguish both values
        return $this->m_proposedValue;
    }

    /**
     * @throws \Exception
     * @since 3.2.0
     */
    public function GetCLIValue(bool $bLocalizedValues = false): string
    {
        if (is_object($this->m_proposedValue)) {
            if ($this->m_proposedValue instanceof ReportValue) {
                return $this->m_proposedValue->GetAsCSV($bLocalizedValues, ',', '"');
            }
            throw new Exception('Unexpected class : ' . get_class($this->m_proposedValue));
        }
        return $this->m_proposedValue;
    }

    /**
     * @throws \Exception
     * @since 3.2.0
     */
    public function GetHTMLValue(bool $bLocalizedValues = false): string
    {
        if (is_object($this->m_proposedValue)) {
            if ($this->m_proposedValue instanceof ReportValue) {
                return $this->m_proposedValue->GetAsHTML($bLocalizedValues);
            }
            throw new Exception('Unexpected class : ' . get_class($this->m_proposedValue));
        }
        return utils::EscapeHtml($this->m_proposedValue);
    }


    /**
     * @since 3.1.0 N°5305
     */
    public function SetDisplayableValue(string $sDisplayableValue)
    {
        $this->m_proposedValue = $sDisplayableValue;
    }

    public function GetOql()
    {
        return $this->m_sOql;
    }

    /**
     * @since 3.2.0
     */
    public function GetCLIValueAndDescription(): string
    {
        return sprintf("%s%s",
            $this->GetCLIValue(),
            $this->GetDescription()
        );
    }

    abstract public function GetDescription();
}