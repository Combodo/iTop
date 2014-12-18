<?php
// Copyright (C) 2010-2014 Combodo SARL
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

require_once('backgroundprocess.inc.php');

/**
 * ormStopWatch
 * encapsulate the behavior of a stop watch that will be stored as an attribute of class AttributeStopWatch 
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


/**
 * ormStopWatch
 * encapsulate the behavior of a stop watch that will be stored as an attribute of class AttributeStopWatch 
 *
 * @package     itopORM
 */
class ormStopWatch
{
	protected $iTimeSpent; // seconds
	protected $iStarted; // unix time (seconds)
	protected $iLastStart; // unix time (seconds)
	protected $iStopped; // unix time (seconds)
	protected $aThresholds;
	
	/**
	 * Constructor
	 */
	public function __construct($iTimeSpent = 0, $iStarted = null, $iLastStart = null, $iStopped = null)
	{
		$this->iTimeSpent = (int) $iTimeSpent;
		$this->iStarted = $iStarted;
		$this->iLastStart = $iLastStart;
		$this->iStopped = $iStopped;

		$this->aThresholds = array();
	}

	/**
	 * Necessary for the triggers
	 */	 	
	public function __toString()
	{
		return (string) $this->iTimeSpent;
	}

	public function DefineThreshold($iPercent, $tDeadline = null, $bPassed = false, $bTriggered = false, $iOverrun = null, $aHighlightDef = null)
	{
		$this->aThresholds[$iPercent] = array(
			'deadline' => $tDeadline, // unix time (seconds)
			'triggered' => $bTriggered,
			'overrun' => $iOverrun,
			'highlight' => $aHighlightDef, // array('code' => string, 'persistent' => boolean)
		);
	}

	public function MarkThresholdAsTriggered($iPercent)
	{
		$this->aThresholds[$iPercent]['triggered'] = true;
	}

	public function GetTimeSpent()
	{
		return $this->iTimeSpent;
	}

	/**
	 * Get the working elapsed time since the start of the stop watch
	 * even if it is currently running
	 * @param oAttDef	AttributeDefinition Attribute hosting the stop watch
	 */
	public function GetElapsedTime($oAttDef)
	{
		if (is_null($this->iLastStart))
		{
			return $this->GetTimeSpent();
		}
		else
		{
			$iElapsed = $this->ComputeDuration($this, $oAttDef, $this->iLastStart, time());
			return $this->iTimeSpent + $iElapsed;
		}
	}


	public function GetStartDate()
	{
		return $this->iStarted;
	}

	public function GetLastStartDate()
	{
		return $this->iLastStart;
	}

	public function GetStopDate()
	{
		return $this->iStopped;
	}

	public function GetThresholdDate($iPercent)
	{
		if (array_key_exists($iPercent, $this->aThresholds))
		{
			return $this->aThresholds[$iPercent]['deadline'];
		}
		else
		{
			return null;
		}
	}

	public function GetOverrun($iPercent)
	{
		if (array_key_exists($iPercent, $this->aThresholds))
		{
			return $this->aThresholds[$iPercent]['overrun'];
		}
		else
		{
			return null;
		}
	}
	public function IsThresholdPassed($iPercent)
	{
		$bRet = false;
		if (array_key_exists($iPercent, $this->aThresholds))
		{
			$aThresholdData = $this->aThresholds[$iPercent];
			if (!is_null($aThresholdData['deadline']) && ($aThresholdData['deadline'] <= time()))
			{
				$bRet = true;
			}
			if (isset($aThresholdData['overrun']) && ($aThresholdData['overrun'] > 0))
			{
				$bRet = true;
			}
		}
		return $bRet;
	}
	public function IsThresholdTriggered($iPercent)
	{
		if (array_key_exists($iPercent, $this->aThresholds))
		{
			return $this->aThresholds[$iPercent]['triggered'];
		}
		else
		{
			return false;
		}
	}
	
	public function GetHighlightCode()
	{
		$sCode = '';
		// Process the thresholds in ascending order
		$aPercents = array();
		foreach($this->aThresholds as $iPercent => $aDefs)
		{
			$aPercents[] = $iPercent;
		}
		sort($aPercents, SORT_NUMERIC);
		foreach($aPercents as $iPercent)
		{
			$aDefs = $this->aThresholds[$iPercent];
			if (array_key_exists('highlight', $aDefs) && is_array($aDefs['highlight']) && $this->IsThresholdPassed($iPercent))
			{
				// If persistant or SW running...
				if (($aDefs['highlight']['persistent'] == true) || (($aDefs['highlight']['persistent'] == false) && !is_null($this->iLastStart)))
				{
					$sCode = $aDefs['highlight']['code'];
				}
			}
		}
		return $sCode;
	}

	public function GetAsHTML($oAttDef, $oHostObject = null)
	{
		$aProperties = array();

		$aProperties['States'] = implode(', ', $oAttDef->GetStates());

		if (is_null($this->iLastStart))
		{
			if (is_null($this->iStarted))
			{
				$aProperties['Elapsed'] = 'never started';
			}
			else
			{
				$aProperties['Elapsed'] = $this->iTimeSpent.' s';
			}
		}
		else
		{
			$aProperties['Elapsed'] = 'running <img src="../images/indicator.gif">';
		}

		$aProperties['Started'] = $oAttDef->SecondsToDate($this->iStarted);
		$aProperties['LastStart'] = $oAttDef->SecondsToDate($this->iLastStart);
		$aProperties['Stopped'] = $oAttDef->SecondsToDate($this->iStopped);

		foreach ($this->aThresholds as $iPercent => $aThresholdData)
		{
			$sThresholdDesc = $oAttDef->SecondsToDate($aThresholdData['deadline']);
			if ($aThresholdData['triggered'])
			{
				$sThresholdDesc .= " <b>TRIGGERED</b>";
			}
			if ($aThresholdData['overrun'])
			{
				$sThresholdDesc .= " Overrun:".(int) $aThresholdData['overrun']." sec.";
			}
			$aProperties[$iPercent.'%'] = $sThresholdDesc;
		}
		$sRes = "<TABLE>";
		$sRes .= "<TBODY>";
		foreach ($aProperties as $sProperty => $sValue)
		{
			$sRes .= "<TR>";
			$sCell = str_replace("\n", "<br>\n", $sValue);
			$sRes .= "<TD class=\"label\">$sProperty</TD><TD>$sCell</TD>";
			$sRes .= "</TR>";
		}
		$sRes .= "</TBODY>";
		$sRes .= "</TABLE>";
		return $sRes;
	}

	protected function ComputeGoal($oObject, $oAttDef)
	{
		$sMetricComputer = $oAttDef->Get('goal_computing');
		$oComputer = new $sMetricComputer();
		$aCallSpec = array($oComputer, 'ComputeMetric');
		if (!is_callable($aCallSpec))
		{
			throw new CoreException("Unknown class/verb '$sMetricComputer/ComputeMetric'");
		}
		$iRet = call_user_func($aCallSpec, $oObject);
		return $iRet;
	}

	protected function ComputeDeadline($oObject, $oAttDef, $iStartTime, $iDurationSec)
	{
		$sWorkingTimeComputer = $oAttDef->Get('working_time_computing');
		if ($sWorkingTimeComputer == '')
		{
			$sWorkingTimeComputer = class_exists('SLAComputation') ? 'SLAComputation' : 'DefaultWorkingTimeComputer';
		}
		$aCallSpec = array($sWorkingTimeComputer, '__construct');
		if (!is_callable($aCallSpec))
		{
			//throw new CoreException("Pas de constructeur pour $sWorkingTimeComputer!");
		}
		$oComputer = new $sWorkingTimeComputer();
		$aCallSpec = array($oComputer, 'GetDeadline');
		if (!is_callable($aCallSpec))
		{
			throw new CoreException("Unknown class/verb '$sWorkingTimeComputer/GetDeadline'");
		}
		// GetDeadline($oObject, $iDuration, DateTime $oStartDate)
		$oStartDate = new DateTime('@'.$iStartTime); // setTimestamp not available in PHP 5.2
		$oDeadline = call_user_func($aCallSpec, $oObject, $iDurationSec, $oStartDate);
		$iRet = $oDeadline->format('U');
		return $iRet;
	}

	protected function ComputeDuration($oObject, $oAttDef, $iStartTime, $iEndTime)
	{
		$sWorkingTimeComputer = $oAttDef->Get('working_time_computing');
		if ($sWorkingTimeComputer == '')
		{
			$sWorkingTimeComputer = class_exists('SLAComputation') ? 'SLAComputation' : 'DefaultWorkingTimeComputer';
		}
		$oComputer = new $sWorkingTimeComputer();
		$aCallSpec = array($oComputer, 'GetOpenDuration');
		if (!is_callable($aCallSpec))
		{
			throw new CoreException("Unknown class/verb '$sWorkingTimeComputer/GetOpenDuration'");
		}
		// GetOpenDuration($oObject, DateTime $oStartDate, DateTime $oEndDate)
		$oStartDate = new DateTime('@'.$iStartTime); // setTimestamp not available in PHP 5.2
		$oEndDate = new DateTime('@'.$iEndTime);
		$iRet = call_user_func($aCallSpec, $oObject, $oStartDate, $oEndDate);
		return $iRet;
	}

	public function Reset($oObject, $oAttDef)
	{
		$this->iTimeSpent = 0;
		$this->iStopped = null;
		$this->iStarted = null;

		foreach ($this->aThresholds as $iPercent => &$aThresholdData)
		{
			$aThresholdData['triggered'] = false;
			$aThresholdData['overrun'] = null;
		}

		if (!is_null($this->iLastStart))
		{
			// Currently running... starting again from now!
			$this->iStarted = time();
			$this->iLastStart = time();
			$this->ComputeDeadlines($oObject, $oAttDef);
		}
	}

	/**
	 * Start or continue
	 * It is the responsibility of the caller to compute the deadlines
	 * (to avoid computing twice for the same result) 	 
	 */	 	 	
	public function Start($oObject, $oAttDef, $iNow = null)
	{
		if (!is_null($this->iLastStart))
		{
			// Already started
			return false;
		}

		if (is_null($iNow))
		{
			$iNow = time();
		}

		if (is_null($this->iStarted))
		{
			$this->iStarted = $iNow;
		}
		$this->iLastStart = $iNow;
		$this->iStopped = null;

		return true;
	}

	/**
	 * Compute or recompute the goal and threshold deadlines
	 */	 	 	
	public function ComputeDeadlines($oObject, $oAttDef)
	{
		if (is_null($this->iLastStart))
		{
			// Currently stopped - do nothing
			return false;
		}

		$iDurationGoal = $this->ComputeGoal($oObject, $oAttDef);
		$iComputationRefTime = time();
		foreach ($this->aThresholds as $iPercent => &$aThresholdData)
		{
			if (is_null($iDurationGoal))
			{
				// No limit: leave null thresholds
				$aThresholdData['deadline'] = null;
			}
			else
			{
				$iThresholdDuration = round($iPercent * $iDurationGoal / 100);

				if (class_exists('WorkingTimeRecorder'))
				{
					$sClass = get_class($oObject);
					$sAttCode = $oAttDef->GetCode();
					WorkingTimeRecorder::Start($oObject, $iComputationRefTime, "ormStopWatch-Deadline-$iPercent-$sAttCode", 'Core:ExplainWTC:StopWatch-Deadline', array("Class:$sClass/Attribute:$sAttCode", $iPercent));
				}
				$aThresholdData['deadline'] = $this->ComputeDeadline($oObject, $oAttDef, $this->iLastStart, $iThresholdDuration - $this->iTimeSpent);
				// OR $aThresholdData['deadline'] = $this->ComputeDeadline($oObject, $oAttDef, $this->iStarted, $iThresholdDuration);

				if (class_exists('WorkingTimeRecorder'))
				{
					WorkingTimeRecorder::End();
				}
			}
			if (is_null($aThresholdData['deadline']) || ($aThresholdData['deadline'] > time()))
			{
				// The threshold is in the future, reset
				$aThresholdData['triggered'] = false;
				$aThresholdData['overrun'] = null;
			}
			else
			{
				// The new threshold is in the past
				// Note: the overrun can be wrong, but the correct algorithm to compute
				// the overrun of a deadline in the past requires that the ormStopWatch keeps track of all its history!!!
			}
		}

		return true;
	}

	/**
	 * Stop counting if not already done
	 */	 	 	
	public function Stop($oObject, $oAttDef)
	{
		if (is_null($this->iLastStart))
		{
			// Already stopped
			return false;
		}

		if (class_exists('WorkingTimeRecorder'))
		{
			$sClass = get_class($oObject);
			$sAttCode = $oAttDef->GetCode();
			WorkingTimeRecorder::Start($oObject, time(), "ormStopWatch-TimeSpent-$sAttCode", 'Core:ExplainWTC:StopWatch-TimeSpent', array("Class:$sClass/Attribute:$sAttCode"), true /*cumulative*/);
		}
		$iElapsed = $this->ComputeDuration($oObject, $oAttDef, $this->iLastStart, time());
		$this->iTimeSpent = $this->iTimeSpent + $iElapsed;
		if (class_exists('WorkingTimeRecorder'))
		{
			WorkingTimeRecorder::End();
		}

		foreach ($this->aThresholds as $iPercent => &$aThresholdData)
		{
			if (!is_null($aThresholdData['deadline']) && (time() > $aThresholdData['deadline']))
			{
				if ($aThresholdData['overrun'] > 0)
				{
					// Accumulate from last start
					$aThresholdData['overrun'] += $iElapsed;
				}
				else
				{
					// First stop after the deadline has been passed
					$iOverrun = $this->ComputeDuration($oObject, $oAttDef, $aThresholdData['deadline'], time());
					$aThresholdData['overrun'] = $iOverrun;
				}
			}
			$aThresholdData['deadline'] = null;
		}

		$this->iLastStart = null;
		$this->iStopped = time();

		return true;
	}
}

/**
 * CheckStopWatchThresholds
 * Implements the automatic actions 
 *
 * @package     itopORM
 */
class CheckStopWatchThresholds implements iBackgroundProcess
{
	public function GetPeriodicity()
	{	
		return 10; // seconds
	}

	public function Process($iTimeLimit)
	{
		$aList = array();
		foreach (MetaModel::GetClasses() as $sClass)
		{
			foreach (MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
			{
				if ($oAttDef instanceof AttributeStopWatch)
				{
					foreach ($oAttDef->ListThresholds() as $iThreshold => $aThresholdData)
					{
						$iPercent = $aThresholdData['percent']; // could be different than the index !
		
						$sNow = date('Y-m-d H:i:s');
						$sExpression = "SELECT $sClass WHERE {$sAttCode}_laststart AND {$sAttCode}_{$iThreshold}_triggered = 0 AND {$sAttCode}_{$iThreshold}_deadline < '$sNow'";
						$oFilter = DBObjectSearch::FromOQL($sExpression);
						$oSet = new DBObjectSet($oFilter);
						while ((time() < $iTimeLimit) && ($oObj = $oSet->Fetch()))
						{
							$sClass = get_class($oObj);

							$aList[] = $sClass.'::'.$oObj->GetKey().' '.$sAttCode.' '.$iThreshold;

							// Execute planned actions
							//
							foreach ($aThresholdData['actions'] as $aActionData)
							{
								$sVerb = $aActionData['verb'];
								$aParams = $aActionData['params'];
								$aValues = array();
								foreach($aParams as $def)
								{
									if (is_string($def))
									{
										// Old method (pre-2.1.0) non typed parameters
										$aValues[] = $def;
									}
									else // if(is_array($def))
									{
										$sParamType = array_key_exists('type', $def) ? $def['type'] : 'string';
										switch($sParamType)
										{
											case 'int':
												$value = (int)$def['value'];
												break;
										
											case 'float':
												$value = (float)$def['value'];
												break;
										
											case 'bool':
												$value = (bool)$def['value'];
												break;
										
											case 'reference':
												$value = ${$def['value']};
												break;
										
											case 'string':
											default:
												$value = (string)$def['value'];
										}
										$aValues[] = $value;
									}
								}
								$aCallSpec = array($oObj, $sVerb);
								call_user_func_array($aCallSpec, $aValues);
							}

							// Mark the threshold as "triggered"
							//
							$oSW = $oObj->Get($sAttCode);
							$oSW->MarkThresholdAsTriggered($iThreshold);
							$oObj->Set($sAttCode, $oSW);
		
							if($oObj->IsModified())
							{
								CMDBObject::SetTrackInfo("Automatic - threshold triggered");
					
								$oMyChange = CMDBObject::GetCurrentChange();
								$oObj->DBUpdateTracked($oMyChange, true /*skip security*/);
							}

							// Activate any existing trigger
							// 
							$sClassList = implode("', '", MetaModel::EnumParentClasses($sClass, ENUM_PARENT_CLASSES_ALL));
							$oTriggerSet = new DBObjectSet(
								DBObjectSearch::FromOQL("SELECT TriggerOnThresholdReached AS t WHERE t.target_class IN ('$sClassList') AND stop_watch_code=:stop_watch_code AND threshold_index = :threshold_index"),
								array(), // order by
								array('stop_watch_code' => $sAttCode, 'threshold_index' => $iThreshold)
							);
							while ($oTrigger = $oTriggerSet->Fetch())
							{
								$oTrigger->DoActivate($oObj->ToArgs('this'));
							}
						}
					}
				}
			}
		}

		$iProcessed = count($aList);
		return "Triggered $iProcessed threshold(s):".implode(", ", $aList);
	}
}
