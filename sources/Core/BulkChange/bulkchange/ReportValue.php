<?php

/**
 * Class to differ formatting depending on the caller
 */
class ReportValue
{
    /**
     * @param DBObject $oObject
     * @param string $sAttCode
     * @param bool $bOriginal
     */
    public function __construct(protected DBObject $oObject, protected string $sAttCode, protected bool $bOriginal)
    {
    }

    public function GetAsHTML(bool $bLocalizedValues)
    {
        if ($this->bOriginal) {
            return $this->oObject->GetOriginalAsHTML($this->sAttCode, $bLocalizedValues);
        }
        return $this->oObject->GetAsHTML($this->sAttCode, $bLocalizedValues);
    }

    public function GetAsCSV(bool $bLocalizedValues, string $sCsvSep, string $sCsvDelimiter)
    {
        if ($this->bOriginal) {
            return $this->oObject->GetOriginalAsCSV($this->sAttCode, $sCsvSep, $sCsvDelimiter, $bLocalizedValues);
        }
        return $this->oObject->GetAsCSV($this->sAttCode, $sCsvSep, $sCsvDelimiter, $bLocalizedValues);
    }
}