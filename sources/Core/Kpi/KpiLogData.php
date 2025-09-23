<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\Kpi;

class KpiLogData
{
    const TYPE_REPORT = 'report';
    const TYPE_STATS = 'stats';
    const TYPE_REQUEST = 'request';

    /** @var string */
    public $sType;
    /** @var string */
    public $sOperation;
    /** @var string */
    public $sArguments;
    /** @var float */
    public $fStartTime;
    /** @var float */
    public $fStopTime;
    /** @var string */
    public $sExtension;
    /** @var int */
    public $iInitialMemory;
    /** @var int */
    public $iCurrentMemory;
    /** @var int */
    public $iPeakMemory;
    /** @var array */
    public $aData;

    /**
     * @param string $sType
     * @param string $sOperation
     * @param string $sArguments
     * @param float $fStartTime
     * @param float $fStopTime
     * @param string $sExtension
     * @param int $iInitialMemory
     * @param int $iCurrentMemory
     * @param array $aData
     */
    public function __construct($sType, $sOperation, $sArguments, $fStartTime, $fStopTime, $sExtension, $iInitialMemory = 0, $iCurrentMemory = 0, $iPeakMemory = 0, $aData = [])
    {
        $this->sType = $sType;
        $this->sOperation = $sOperation;
        $this->sArguments = @iconv(mb_detect_encoding($sArguments, mb_detect_order(), true), 'UTF-8', $sArguments);
        $this->fStartTime = $fStartTime;
        $this->fStopTime = $fStopTime;
        $this->sExtension = $sExtension;
        $this->iInitialMemory = $iInitialMemory;
        $this->iCurrentMemory = $iCurrentMemory;
        $this->iPeakMemory = $iPeakMemory;
        $this->aData = $aData;
    }

    /**
     * Return the CSV Header
     *
     * @return string
     */
    public static function GetCSVHeader()
    {
        return "Type,Operation,Arguments,StartTime,StopTime,Duration,Extension,InitialMemory,CurrentMemory,PeakMemory";
    }

    /**
     * Return the CSV line for the values
     * @return string
     */
    public function GetCSV()
    {
        $fDuration = sprintf('%01.4f', $this->fStopTime - $this->fStartTime);
        $sType = $this->RemoveQuotes($this->sType);
        $sOperation = $this->RemoveQuotes($this->sOperation);
        $sArguments = $this->RemoveQuotes($this->sArguments);
        $sExtension = $this->RemoveQuotes($this->sExtension);
        return "\"$sType\",\"$sOperation\",\"$sArguments\",$this->fStartTime,$this->fStopTime,$fDuration,\"$sExtension\",$this->iInitialMemory,$this->iCurrentMemory,$this->iPeakMemory";
    }

    private function RemoveQuotes(string $sEntry): string
    {
        return str_replace('"', "'", $sEntry);
    }

    /**
     * @param \Combodo\iTop\Core\Kpi\KpiLogData $oOther
     *
     * @return float
     */
    public function Compare(KpiLogData $oOther): float
    {
        if ($oOther->fStartTime > $this->fStartTime) {
            return -1;
        }
        return 1;
    }

    public function Contains(KpiLogData $oOther): bool
    {
        if ($oOther->fStartTime < $this->fStartTime) {
            return false;
        }

        if ($oOther->fStartTime > $this->fStopTime) {
            return false;
        }

        return true;
    }

    public function __toString()
    {
        return "$this->sType:$this->sOperation:$this->sArguments";
    }

    public function GetUUID(): string
    {
        return sha1($this->__toString());
    }
}