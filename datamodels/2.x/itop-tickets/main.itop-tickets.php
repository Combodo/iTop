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


/**
 * Base class for computing TTO or TTR on a ticket
 */ 
class ResponseTicketSLT
{
	/**
	 * Determines the shortest SLT, for this ticket, for the given metric. Returns null is no SLT was found
	 * @param string $sMetric Type of metric 'TTO', 'TTR', etc as defined in the SLT class
	 * @return hash Array with 'SLT' => name of the SLT selected, 'value' => duration in seconds of the SLT metric, null if no SLT applies to this ticket
	 */
	protected static function ComputeSLT($oTicket, $sMetric = 'TTO')
	{
		$iDeadline = null;
		if (MetaModel::IsValidClass('SLT'))
		{
			$sType=get_class($oTicket);
			if ($sType == 'Incident')
			{
				$sRequestType = 'incident';
			}
			else
			{
				$sRequestType = $oTicket->Get('request_type');
			}
			
			$aArgs = $oTicket->ToArgs();
			$aArgs['metric'] = $sMetric;
			$aArgs['request_type'] = $sRequestType;
				
			//echo "<p>Managing:".$sMetric."-".$this->Get('request_type')."-".$this->Get('importance')."</p>\n";
			$oSLTSet = new DBObjectSet(DBObjectSearch::FromOQL(RESPONSE_TICKET_SLT_QUERY),
						array(),
						$aArgs
						);

			$iMinDuration = PHP_INT_MAX;
			$sSLTName = '';
	
			while($oSLT = $oSLTSet->Fetch())
			{
				$iDuration = (int)$oSLT->Get('value');
				$sUnit = $oSLT->Get('unit');
				switch($sUnit)
				{
					case 'days':
					$iDuration = $iDuration * 24; // 24 hours in 1 days
					// Fall though
					
					case 'hours':
					$iDuration = $iDuration * 60; // 60 minutes in 1 hour
					// Fall though
					
					case 'minutes':
					$iDuration = $iDuration * 60;
				}
				if ($iDuration < $iMinDuration)
				{
					$iMinDuration = $iDuration;
					$sSLTName = $oSLT->GetName();
				}
			}
			if ($iMinDuration == PHP_INT_MAX)
			{
				$iDeadline = null;
			}
			else
			{
				// Store $sSLTName to keep track of which SLT has been used
				$iDeadline = $iMinDuration;
			}
		}
		return $iDeadline;			

	}
}

/**
 * Compute the TTO of a ticket - null if the class 'SLT' does not exist
 */ 
class ResponseTicketTTO extends ResponseTicketSLT implements iMetricComputer
{
	public static function GetDescription()
	{
		return "Time to own a ticket";
	}

	public function ComputeMetric($oObject)
	{
		$iRes = $this->ComputeSLT($oObject, 'TTO');
		return $iRes;
	}
}

/**
 * Compute the TTR of a ticket - null if the class 'SLT' does not exist
 */ 
class ResponseTicketTTR extends ResponseTicketSLT implements iMetricComputer
{
	public static function GetDescription()
	{
		return "Time to resolve a ticket";
	}

	public function ComputeMetric($oObject)
	{
		$iRes = $this->ComputeSLT($oObject, 'TTR');
		return $iRes;
	}
}

?>