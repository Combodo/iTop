<?php
/**
 * Copyright (C) 2010-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 */


/**
 * Any extension to compute things like a stop watch deadline or working hours
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Metric computing for stop watches.
 * Can be used for AttributeStopWatch goal (iTop XML node xpath: /itop_design/classes/class/fields/field/goal)
 */ 
interface iMetricComputer
{
	public static function GetDescription();

	/**
	 * @param \DBObject $oObject
	 *
	 * @return float number of seconds for the time limit
	 */
	public function ComputeMetric($oObject);
}

/**
 * Working time computing for stop watches
 */ 
interface iWorkingTimeComputer
{
	public static function GetDescription();

	/**
	 * @param DBObject $oObject The object for which to compute the deadline
	 * @param integer $iDuration The duration (in seconds) in the future
	 * @param DateTime $oStartDate The starting point for the computation
	 *
	 * @return DateTime The date/time corresponding to a given delay in the future from the present
	 *      considering only the valid (open) hours for a specified object
	 */
	public function GetDeadline($oObject, $iDuration, DateTime $oStartDate);
	
	/**
	 * @param DBObject $oObject The object for which to compute the duration
	 * @param DateTime $oStartDate The starting point for the computation (default = now)
	 * @param DateTime $oEndDate The ending point for the computation (default = now)
	 *
	 * @return integer The duration (number of seconds) elapsed between two given dates, considering only open hours
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
	 * @inheritDoc
	 */
	public function GetDeadline($oObject, $iDuration, DateTime $oStartDate)
	{
		if (class_exists('WorkingTimeRecorder'))
		{
			WorkingTimeRecorder::Trace(WorkingTimeRecorder::TRACE_DEBUG, __class__.'::'.__function__);
		}
		//echo "GetDeadline - default: ".$oStartDate->format('Y-m-d H:i:s')." + $iDuration<br/>\n";
		// Default implementation: 24x7, no holidays: to compute the deadline, just add
		// the specified duration to the given date/time
		$oResult = clone $oStartDate;
		$oResult->modify('+'.$iDuration.' seconds');
		if (class_exists('WorkingTimeRecorder'))
		{
			WorkingTimeRecorder::SetValues($oStartDate->format('U'), $oResult->format('U'), $iDuration, WorkingTimeRecorder::COMPUTED_END);
		}
		return $oResult;
	}
	
	/**
	 * @inheritDoc
	 */
	public function GetOpenDuration($oObject, DateTime $oStartDate, DateTime $oEndDate)
	{
		if (class_exists('WorkingTimeRecorder'))
		{
			WorkingTimeRecorder::Trace(WorkingTimeRecorder::TRACE_DEBUG, __class__.'::'.__function__);
		}
		//echo "GetOpenDuration - default: ".$oStartDate->format('Y-m-d H:i:s')." to ".$oEndDate->format('Y-m-d H:i:s')."<br/>\n";
		$iDuration = abs($oEndDate->format('U') - $oStartDate->format('U'));
		if (class_exists('WorkingTimeRecorder'))
		{
			WorkingTimeRecorder::SetValues($oStartDate->format('U'), $oEndDate->format('U'), $iDuration, WorkingTimeRecorder::COMPUTED_DURATION);
		}
		return $iDuration;
	}
}
