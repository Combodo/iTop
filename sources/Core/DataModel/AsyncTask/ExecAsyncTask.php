<?php

class ExecAsyncTask implements iBackgroundProcess
{
    public function GetPeriodicity()
    {
        return 2; // seconds
    }

    public function Process($iTimeLimit)
    {
        $sNow = date(AttributeDateTime::GetSQLFormat());
        // Criteria: planned, and expected to occur... ASAP or in the past
        $sOQL = "SELECT AsyncTask WHERE (status = 'planned') AND (ISNULL(planned) OR (planned < '$sNow'))";
        $iProcessed = 0;
        while (time() < $iTimeLimit) {
            // Next one ?
            $oSet = new CMDBObjectSet(DBObjectSearch::FromOQL($sOQL), array('created' => true) /* order by*/, array(), null, 1 /* limit count */);
            $oTask = $oSet->Fetch();
            if (is_null($oTask)) {
                // Nothing to be done
                break;
            }
            $iProcessed++;
            if ($oTask->Process()) {
                $oTask->DBDelete();
            }
        }
        return "processed $iProcessed tasks";
    }
}