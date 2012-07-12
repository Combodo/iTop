<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * Any extension to compute things like a stop watch deadline or working hours 
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

/**
 * Metric computing for stop watches
 */ 
interface iMetricComputer
{
	public static function GetDescription();
	public function ComputeMetric($oObject);
}

/**
 * Working time computing for stop watches
 */ 
interface iWorkingTimeComputer
{
	public static function GetDescription();

	/**
	 * Get the date/time corresponding to a given delay in the future from the present
	 * considering only the valid (open) hours for a specified object
	 * @param $oObject DBObject The object for which to compute the deadline
	 * @param $iDuration integer The duration (in seconds) in the future
	 * @param $oStartDate DateTime The starting point for the computation
	 * @return DateTime The date/time for the deadline
	 */
	public function GetDeadline($oObject, $iDuration, DateTime $oStartDate);
	
	/**
	 * Get duration (considering only open hours) elapsed bewteen two given DateTimes
	 * @param $oObject DBObject The object for which to compute the duration
	 * @param $oStartDate DateTime The starting point for the computation (default = now)
	 * @param $oEndDate DateTime The ending point for the computation (default = now)
	 * @return integer The duration (number of seconds) of open hours elapsed between the two dates
	 */
	public function GetOpenDuration($oObject, DateTime $oStartDate, DateTime $oEndDate);
}

/**
 * Default implementation oof deadline computing: NO deadline
 */
class DefaultMetricComputer implements iMetricComputer
{
	public static function GetDescription()
	{
		return "Null";
	}

	public function ComputeMetric($oObject)
	{
		return null;
	}
}

/**
 * Default implementation of working time computing
 */ 
class DefaultWorkingTimeComputer implements iWorkingTimeComputer
{
	public static function GetDescription()
	{
		return "24x7, no holidays";
	}

	/**
	 * Get the date/time corresponding to a given delay in the future from the present
	 * considering only the valid (open) hours for a specified object
	 * @param $oObject DBObject The object for which to compute the deadline
	 * @param $iDuration integer The duration (in seconds) in the future
	 * @param $oStartDate DateTime The starting point for the computation
	 * @return DateTime The date/time for the deadline
	 */
	public function GetDeadline($oObject, $iDuration, DateTime $oStartDate)
	{
		//echo "GetDeadline - default: ".$oStartDate->format('Y-m-d H:i:s')." + $iDuration<br/>\n";
		// Default implementation: 24x7, no holidays: to compute the deadline, just add
		// the specified duration to the given date/time
		$oResult = clone $oStartDate;
		$oResult->modify('+'.$iDuration.' seconds');
		return $oResult;
	}
	
	/**
	 * Get duration (considering only open hours) elapsed bewteen two given DateTimes
	 * @param $oObject DBObject The object for which to compute the duration
	 * @param $oStartDate DateTime The starting point for the computation (default = now)
	 * @param $oEndDate DateTime The ending point for the computation (default = now)
	 * @return integer The duration (number of seconds) of open hours elapsed between the two dates
	 */
	public function GetOpenDuration($oObject, DateTime $oStartDate, DateTime $oEndDate)
	{
		//echo "GetOpenDuration - default: ".$oStartDate->format('Y-m-d H:i:s')." to ".$oEndDate->format('Y-m-d H:i:s')."<br/>\n";
		return abs($oEndDate->format('U') - $oStartDate->format('U'));
	}
}


?>
