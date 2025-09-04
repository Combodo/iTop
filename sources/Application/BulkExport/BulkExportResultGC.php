<?php

/**
 * Garbage collector for cleaning "old" export results from the database and the disk.
 * This background process runs once per day and deletes the results of all exports which
 * are older than one day.
 */
class BulkExportResultGC implements iBackgroundProcess
{
    public function GetPeriodicity()
    {
        return 24 * 3600; // seconds
    }

    public function Process($iTimeLimit)
    {
        $sDateLimit = date(AttributeDateTime::GetSQLFormat(), time() - 24 * 3600); // Every BulkExportResult older than one day will be deleted

        $sOQL = "SELECT BulkExportResult WHERE created < '$sDateLimit'";
        $iProcessed = 0;
        while (time() < $iTimeLimit) {
            // Next one ?
            $oSet = new CMDBObjectSet(DBObjectSearch::FromOQL($sOQL), array('created' => true) /* order by*/, array(), null, 1 /* limit count */);
            $oSet->OptimizeColumnLoad(array('BulkExportResult' => array('temp_file_path')));
            $oResult = $oSet->Fetch();
            if (is_null($oResult)) {
                // Nothing to be done
                break;
            }
            $iProcessed++;
            @unlink($oResult->Get('temp_file_path'));
            utils::PushArchiveMode(false);
            $oResult->DBDelete();
            utils::PopArchiveMode();
        }
        return "Cleaned $iProcessed old export results(s).";
    }
}