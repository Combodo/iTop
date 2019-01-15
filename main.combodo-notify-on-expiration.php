<?php
/**
 * Module combodo-notify-on-expiration
 *
 * @copyright   Copyright (C) 2010-2019 Combodo SARL
 * @license     https://www.combodo.com/documentation/combodo-software-license.html
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @author      Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @author      Vincent Dumas <vincent.dumas@combodo.com>
 */


/**
 * Class NotifyOnExpirationExec
 */
class NotifyOnExpiration implements iScheduledProcess
{
	const MODULE_CODE = 'combodo-notify-on-expiration';
	const MODULE_SETTING_ENABLED = 'enabled';
	const MODULE_SETTING_DEBUG = 'debug';
	const MODULE_SETTING_WEEKDAYS = 'week_days';
	const MODULE_SETTING_TIME = 'time';

	const DEFAULT_MODULE_SETTING_ENABLED = true;
	const DEFAULT_MODULE_SETTING_DEBUG = false;
	const DEFAULT_MODULE_SETTING_WEEKDAYS = 'monday, tuesday, wednesday, thursday, friday, saturday, sunday';
	const DEFAULT_MODULE_SETTING_TIME = '03:00';

	protected $bDebug;

	/**
	 * NotifyOnExpiration constructor.
	 */
	function __construct()
	{
		$this->bDebug = (bool) MetaModel::GetModuleSetting(static::MODULE_CODE, static::MODULE_SETTING_DEBUG, static::DEFAULT_MODULE_SETTING_DEBUG);
	}

	/**
	 * Gives the exact time at which the process must be run next time
	 *
	 * @return \DateTime
	 */
	public function GetNextOccurrence()
	{
		$bEnabled = MetaModel::GetConfig()->GetModuleSetting(static::MODULE_CODE, static::MODULE_SETTING_ENABLED, static::DEFAULT_MODULE_SETTING_ENABLED);
		if (!$bEnabled)
		{
			$oRet = new DateTime('3000-01-01');
		}
		else
		{
			// 1st - Interpret the list of days as ordered numbers (monday = 1)
			//
			$aDays = $this->InterpretWeekDays();

			// 2nd - Find the next active week day
			//
			$sRunTime = MetaModel::GetConfig()->GetModuleSetting(static::MODULE_CODE, static::MODULE_SETTING_TIME, static::DEFAULT_MODULE_SETTING_TIME);
			if (!preg_match('/^([01]?\d|2[0-3]):[0-5]?\d(:[0-5]?\d)?$/', $sRunTime, $aMatches))
			{
				throw new Exception(static::MODULE_CODE.": wrong format for setting 'time' (found '$sRunTime')");
			}
			$oNow = new DateTime();
			$iNextPos = false;
			for ($iDay = $oNow->format('N') ; $iDay <= 7 ; $iDay++)
			{
				$iNextPos = array_search($iDay, $aDays);
				if ($iNextPos !== false)
				{
					if (($iDay > $oNow->format('N')) || ($oNow->format('H:i') < $sRunTime))
					{
						break;
					}
					$iNextPos = false; // necessary on sundays
				}
			}

			// 3rd - Compute the result
			//
			if ($iNextPos === false)
			{
				// Jump to the first day within the next week
				$iFirstDayOfWeek = $aDays[0];
				$iDayMove = $oNow->format('N') - $iFirstDayOfWeek;
				$oRet = clone $oNow;
				$oRet->modify('-'.$iDayMove.' days');
				$oRet->modify('+1 weeks');
			}
			else
			{
				$iNextDayOfWeek = $aDays[$iNextPos];
				$iMove = $iNextDayOfWeek - $oNow->format('N');
				$oRet = clone $oNow;
				$oRet->modify('+'.$iMove.' days');
			}
			$oRet->setTime((int)$aMatches[1], (int) $aMatches[2]);
		}
		return $oRet;
	}

	/**
	 * @inheritdoc
	 */
	public function Process($iTimeLimit)
	{
		$aReport = array(
			'reached_deadline' => 0,
			'triggered' => array(),
			'not_triggered' => array(),
		);

		$oRulesSearch = DBObjectSearch::FromOQL('SELECT ExpirationRule WHERE status = "active"');
		$oRulesSet = new DBObjectSet($oRulesSearch);

		$this->Trace('Processing '.$oRulesSet->Count().' active expiration rules...');

		$iTotalProcessedObjectsCount = 0;
		while($oRule = $oRulesSet->Fetch())
		{
			$iRuleProcessedObjectsCount = 0;
			$this->Trace('Processing rule "'.$oRule->Get('friendlyname').'" (#'.$oRule->GetKey().')...');
			
			try
			{
				// Retrieving rule's params
				$sClass = $oRule->Get('class');
				$oSearch = $oRule->GetFilter();
				$this->Trace('|- Parameters:');
				$this->Trace('|  |- Class: '.$sClass);
				$this->Trace('|  |- OQL scope: '.$oSearch->ToOQL(true));

				// Prepare the Rule information to be passed to the notification
				$aRuleContext = $oRule->ToArgs('rule');
				
                // Get applicable Triggers for this object class
				$sClassList = implode("', '", MetaModel::EnumParentClasses($sClass, ENUM_PARENT_CLASSES_ALL));
				$oTriggerSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT TriggerOnExpirationRule AS t WHERE t.target_class IN ('$sClassList')"));
				
				$oSet = new DBObjectSet($oSearch);
				$this->Trace('|- Objects:');
				/** @var $oToTrigger DBObject */
				while ((time() < $iTimeLimit) && $oToTrigger = $oSet->Fetch())
				{
					// Catching exceptions so the process don't get stucked on this object
					try
					{
						$aReport['reached_deadline']++;
						// 
						// $aContext['ruleName'] = $oRule->Get('name');
						// Combine the current object :this and :rule to be available in the notification
						$aContext = $oToTrigger->ToArgs('this');
						$aContext = array_merge($aContext, $aRuleContext);
						while ($oTrigger = $oTriggerSet->Fetch())
						{
							$oTrigger->DoActivate($aContext);
						}
                        // The same set of Triggers is reused for each object returned by the Rule as they all belongs to the same class
						$oTriggerSet->Rewind();
						
						$iRuleProcessedObjectsCount++;
						$iTotalProcessedObjectsCount++;

						$aReport['triggered'][] = $oToTrigger->Get('friendlyname');
						$this->Trace('|  |- [OK] '.$sClass.' #'.$oToTrigger->GetKey());

					} // Trigger was NOT applied because of an exception, which is NOT normal
					catch (Exception $e)
					{
						$aReport['not_triggered'][] = $oToClose->Get('friendlyname');
						$this->Trace('|  |- [KO] /!\\ '.$sClass.' #'.$oToTrigger->GetKey().' exception raised! Error message: '.$e->getMessage());
					}

				}
				$this->Trace('|- Processed rule "'.$oRule->Get('friendlyname').'" (#'.$oRule->GetKey().') : '.$iRuleProcessedObjectsCount.' out of '.$oSet->Count().'.');

				// Info to help understand why not all objects have been processed during this batch.
				if (time() >= $iTimeLimit)
				{
					$this->Trace('Stopped because time limit exceeded!');
				}
			}
			catch(Exception $e)
			{
				$this->Trace('Skipping rule as there was an exception! ('.$e->getMessage().')');
			}
		}

		// Report
		if($aReport['reached_deadline'] === 0)
		{
			return 'No object to process';
		}
		else
		{
			$iClosedCount = count($aReport['triggered']);
			$iNotClosedCount = count($aReport['not_triggered']);

			$sReport = $aReport['reached_deadline'] . " objects reached triggering date";
			$sReport .= " - ".$iClosedCount." were triggered";
			if($iClosedCount > 0)
			{
				$sReport .= " (".implode(", ", $aReport['triggered']).")";
			}
			$sReport .= " - ".$iNotClosedCount." were not triggered";
			if($iNotClosedCount > 0)
			{
				$sReport .= " (".implode(", ", $aReport['not_triggered']).")";
			}
			return $sReport;
		}
	}

	/**
	 * Interpret current setting for the week days
	 *
	 * Note: This comes from itop-backup scheduled task.
	 *
	 * @returns array of int (monday = 1)
	 */
	public function InterpretWeekDays()
	{
		static $aWEEKDAYTON = array('monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6, 'sunday' => 7);
		$aDays = array();
		$sWeekDays = MetaModel::GetConfig()->GetModuleSetting(static::MODULE_CODE, static::MODULE_SETTING_WEEKDAYS, static::DEFAULT_MODULE_SETTING_WEEKDAYS);
		if ($sWeekDays != '')
		{
			$aWeekDaysRaw = explode(',', $sWeekDays);
			foreach ($aWeekDaysRaw as $sWeekDay)
			{
				$sWeekDay = strtolower(trim($sWeekDay));
				if (array_key_exists($sWeekDay, $aWEEKDAYTON))
				{
					$aDays[] = $aWEEKDAYTON[$sWeekDay];
				}
				else
				{
					throw new Exception(static::MODULE_CODE.": wrong format for setting 'week_days' (found '$sWeekDay')");
				}
			}
		}
		if (count($aDays) == 0)
		{
			throw new Exception(static::MODULE_CODE.": missing setting 'week_days'");
		}
		$aDays = array_unique($aDays);
		sort($aDays);
		return $aDays;
	}

	/**
	 * Prints a $sMessage in the CRON output.
	 *
	 * @param string $sMessage
	 */
	protected function Trace($sMessage)
	{
		// In the CRON output
		if ($this->bDebug)
		{
			echo $sMessage."\n";
		}
	}
}
