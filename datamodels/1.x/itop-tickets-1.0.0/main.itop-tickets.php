<?php
// Copyright (C) 2010-2012 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>


class ProcessSLAResponseTicket implements iBackgroundProcess
{
	public function GetPeriodicity()
	{	
		return 2; // seconds
	}

	public function Process($iTimeLimit)
	{
		$oMyChange = new CMDBChange();
		$oMyChange->Set("date", time());
		$oMyChange->Set("userinfo", "Automatic updates");
		$iChangeId = $oMyChange->DBInsertNoReload();

      $aReport = array();

		$oSet = new DBObjectSet(DBObjectSearch::FromOQL('SELECT ResponseTicket WHERE status = \'new\' AND tto_escalation_deadline <= NOW()'));
		while ((time() < $iTimeLimit) && $oToEscalate = $oSet->Fetch())
		{
			$oToEscalate->ApplyStimulus('ev_timeout');
			//$oToEscalate->Set('tto_escalation_deadline', null);
			$oToEscalate->DBUpdateTracked($oMyChange, true);
			$aReport['reached TTO ESCALATION deadline'][] = $oToEscalate->Get('ref');
		}
		
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL('SELECT ResponseTicket WHERE status = \'assigned\' AND ttr_escalation_deadline <= NOW()'));
		while ((time() < $iTimeLimit) && $oToEscalate = $oSet->Fetch())
		{
			$oToEscalate->ApplyStimulus('ev_timeout');
			//$oToEscalate->Set('ttr_escalation_deadline', null);
			$oToEscalate->DBUpdateTracked($oMyChange, true);
			$aReport['reached TTR ESCALATION deadline'][] = $oToEscalate->Get('ref');
		}
		
		$oSet = new DBObjectSet(DBObjectSearch::FromOQL('SELECT ResponseTicket WHERE status = \'resolved\' AND closure_deadline <= NOW()'));
		while ((time() < $iTimeLimit) && $oToEscalate = $oSet->Fetch())
		{
			$oToEscalate->ApplyStimulus('ev_close');
			//$oToEscalate->Set('closure_deadline', null);
			$oToEscalate->DBUpdateTracked($oMyChange, true);
			$aReport['reached closure deadline'][] = $oToEscalate->Get('ref');
		}

		$aStringReport = array();
		foreach ($aReport as $sOperation => $aTicketRefs)
		{
			if (count($aTicketRefs) > 0)
			{
				$aStringReport[] = $sOperation.': '.count($aTicketRefs).' {'.implode(', ', $aTicketRefs).'}';
			}
		}
		if (count($aStringReport) == 0)
		{
			return "No ticket to process";
		}
		else
		{
			return "Some tickets reached the limit - ".implode('; ', $aStringReport);
		}
	}
}

?>
