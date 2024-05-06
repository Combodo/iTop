<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Core\Kpi\KpiLogData;
use Combodo\iTop\Service\Module\ModuleService;


/**
 * Measures operations duration, memory usage, etc. (and some other KPIs)
 */

class ExecutionKPI
{
	static protected $m_bEnabled_Duration = false;
	static protected $m_bEnabled_Memory = false;
	static protected $m_bBlameCaller = false;
	static protected $m_sAllowedUser = '*';
    static protected $m_bGenerateLegacyReport = true;
    static protected $m_fSlowQueries = 0;

	static protected $m_aStats = []; // Recurrent operations
	static protected $m_aExecData = []; // One shot operations
	/**
	 * @var array[ExecutionKPI]
	 */
	static protected $m_aExecutionStack = []; // embedded execution stats

	protected $m_fStarted = null;
	protected $m_fChildrenDuration = 0; // Count embedded
	protected $m_iInitialMemory = null;

	static public function EnableDuration($iLevel)
	{
		if ($iLevel > 0) {
			self::$m_bEnabled_Duration = true;
			if ($iLevel > 1) {
				self::$m_bBlameCaller = true;
			} else {
				self::$m_bBlameCaller = false;
			}
		} else {
			self::$m_bEnabled_Duration = false;
			self::$m_bBlameCaller = false;
		}
	}

	static public function EnableMemory($iLevel)
	{
		if ($iLevel > 0) {
			self::$m_bEnabled_Memory = true;
		} else {
			self::$m_bEnabled_Memory = false;
		}
	}

	/**
	 * @param string sUser A user login or * for all users	
	 */	
	static public function SetAllowedUser($sUser)
	{
		self::$m_sAllowedUser = $sUser;
	}

	static public function IsEnabled()
	{
		if (self::$m_bEnabled_Duration || self::$m_bEnabled_Memory)
		{
			if ((self::$m_sAllowedUser == '*') || (UserRights::GetUser() == trim(self::$m_sAllowedUser)))
			{
				return true;
			}
		}
		return false;
	}

    static public function SetGenerateLegacyReport($bReportExtensionsOnly)
    {
        self::$m_bGenerateLegacyReport = $bReportExtensionsOnly;
    }

    static public function SetSlowQueries($fSlowQueries)
    {
        self::$m_fSlowQueries = $fSlowQueries;
    }

	static public function GetDescription()
	{
		$aFeatures = array();
		if (self::$m_bEnabled_Duration) $aFeatures[] = 'Duration'; 
		if (self::$m_bEnabled_Memory)   $aFeatures[] = 'Memory usage';
		$sFeatures = 'Measures: '.implode(', ', $aFeatures);
		$sFor = self::$m_sAllowedUser == '*' ? 'EVERYBODY' : "'".trim(self::$m_sAllowedUser)."'";
        $sSlowQueries = '';
        if (self::$m_fSlowQueries > 0) {
            $sSlowQueries = ". Slow Queries: ".self::$m_fSlowQueries."s";
        }

        $aExtensions = [];
        /** @var \iKPILoggerExtension $oExtensionInstance */
        foreach (MetaModel::EnumPlugins('iKPILoggerExtension') as $oExtensionInstance) {
            $aExtensions[] = ModuleService::GetInstance()->GetModuleNameFromObject($oExtensionInstance);
        }
        $sExtensions = '';
        if (count($aExtensions) > 0) {
            $sExtensions = '. KPI Extensions: ['.implode(', ', $aExtensions).']';
        }

        return "KPI logging is active for $sFor. $sFeatures$sSlowQueries$sExtensions";
	}

	static public function ReportStats()
	{
		if (!self::IsEnabled()) return;

		global $fItopStarted;
        global $iItopInitialMemory;
		$sExecId = microtime(); // id to differentiate the hrefs!
        $sRequest = $_SERVER['REQUEST_URI'].' ('.$_SERVER['REQUEST_METHOD'].')';
        if (isset($_POST['operation'])) {
            $sRequest .= ' operation: '.$_POST['operation'];
        }

        $fStop = MyHelpers::getmicrotime();
        if (($fStop - $fItopStarted) > self::$m_fSlowQueries) {
            // Invoke extensions to log the KPI operation
            /** @var \iKPILoggerExtension $oExtensionInstance */
            $iCurrentMemory = self::memory_get_usage();
            $iPeakMemory = self::memory_get_peak_usage();
            foreach (MetaModel::EnumPlugins('iKPILoggerExtension') as $oExtensionInstance) {
                $oKPILogData = new KpiLogData(KpiLogData::TYPE_REQUEST, 'Page', $sRequest, $fItopStarted, $fStop, '', $iItopInitialMemory, $iCurrentMemory, $iPeakMemory);
                $oExtensionInstance->LogOperation($oKPILogData);
            }
        }

        if (!self::$m_bGenerateLegacyReport) {
            return;
        }

		$aBeginTimes = array();
		foreach (self::$m_aExecData as $aOpStats)
		{
			$aBeginTimes[] = $aOpStats['time_begin'];
		}
		array_multisort($aBeginTimes, self::$m_aExecData);

		$sTableStyle = 'background-color: #ccc; margin: 10px;';

		$sHtml = "<hr/>";
		$sHtml .= "<div style=\"background-color: grey; padding: 10px;\">";
        $sHtml .= "<h3><a name=\"".md5($sExecId)."\">KPIs</a> - $sRequest</h3>";
		$oStarted = DateTime::createFromFormat('U.u', $fItopStarted);
		$sHtml .= '<p>'.$oStarted->format('Y-m-d H:i:s.u').'</p>';
		$sHtml .= "<p>log_kpi_user_id: ".UserRights::GetUserId()."</p>";
		$sHtml .= "<div>";
		$sHtml .= "<table border=\"1\" style=\"$sTableStyle\">";
		$sHtml .= "<thead>";
		$sHtml .= "   <th>Operation</th><th>Begin</th><th>End</th><th>Duration</th><th>Memory start</th><th>Memory end</th><th>Memory peak</th>";
		$sHtml .= "</thead>";
		foreach (self::$m_aExecData as $aOpStats)
		{
			$sOperation = $aOpStats['op'];
			$sBegin = round($aOpStats['time_begin'], 3);
			$sEnd = round($aOpStats['time_end'], 3);
			$fDuration = $aOpStats['time_end'] - $aOpStats['time_begin'];
			$sDuration = round($fDuration, 3);

			$sMemBegin = 'n/a';
			$sMemEnd = 'n/a';
			$sMemPeak = 'n/a';
			if (isset($aOpStats['mem_begin']))
			{
				$sMemBegin = self::MemStr($aOpStats['mem_begin']);
				$sMemEnd = self::MemStr($aOpStats['mem_end']);
				if (isset($aOpStats['mem_peak']))
				{
					$sMemPeak = self::MemStr($aOpStats['mem_peak']);
				}
			}

			$sHtml .= "<tr>";
			$sHtml .= "   <td>$sOperation</td><td>$sBegin</td><td>$sEnd</td><td>$sDuration</td><td>$sMemBegin</td><td>$sMemEnd</td><td>$sMemPeak</td>";
			$sHtml .= "</tr>";
		}
		$sHtml .= "</table>";
		$sHtml .= "</div>";

		$aConsolidatedStats = array();
		foreach (self::$m_aStats as $sOperation => $aOpStats)
		{
			$fTotalOp = 0;
			$iTotalOp = 0;
			$fMinOp = null;
			$fMaxOp = 0;
			$sMaxOpArguments = null;
			foreach ($aOpStats as $sArguments => $aEvents)
			{
				foreach ($aEvents as $aEventData)
				{
					$fDuration = $aEventData['time'];
					$fTotalOp += $fDuration;
					$iTotalOp++;

					$fMinOp = is_null($fMinOp) ? $fDuration : min($fMinOp, $fDuration);
					if ($fDuration > $fMaxOp)
					{
						$sMaxOpArguments = $sArguments;
						$fMaxOp = $fDuration;
					}
				}
			}
			$aConsolidatedStats[$sOperation] = array(
				'count' => $iTotalOp,
				'duration' => $fTotalOp,
				'min' => $fMinOp,
				'max' => $fMaxOp,
				'avg' => $fTotalOp / $iTotalOp,
				'max_args' => $sMaxOpArguments
			);
		}

		$sHtml .= "<div>";
		$sHtml .= "<table border=\"1\" style=\"$sTableStyle\">";
		$sHtml .= "<thead>";
		$sHtml .= "   <th>Operation</th><th>Count</th><th>Duration</th><th>Min</th><th>Max</th><th>Avg</th>";
		$sHtml .= "</thead>";
		foreach ($aConsolidatedStats as $sOperation => $aOpStats)
		{
			$sOperation = '<a href="#'.md5($sExecId.$sOperation).'">'.$sOperation.'</a>';
			$sCount = $aOpStats['count'];
			$sDuration = round($aOpStats['duration'], 3);
			$sMin = round($aOpStats['min'], 3);
			$sMax = '<a href="#'.md5($sExecId.$aOpStats['max_args']).'">'.round($aOpStats['max'], 3).'</a>';
			$sAvg = round($aOpStats['avg'], 3);

			$sHtml .= "<tr>";
			$sHtml .= "   <td>$sOperation</td><td>$sCount</td><td>$sDuration</td><td>$sMin</td><td>$sMax</td><td>$sAvg</td>";
			$sHtml .= "</tr>";
		}
		$sHtml .= "</table>";
		$sHtml .= "</div>";

		$sHtml .= "</div>";

		$sHtml .= "<p><a href=\"#end-".md5($sExecId)."\">Next page stats</a></p>";

		$fSlowQueries = MetaModel::GetConfig()->Get('log_kpi_slow_queries');
		self::Report($sHtml);

		// Report operation details
		foreach (self::$m_aStats as $sOperation => $aOpStats)
		{
			$sHtml = '';
			$bDisplayHeader = true;
			foreach ($aOpStats as $sArguments => $aEvents)
			{
				$sHtmlArguments = '<a name="'.md5($sExecId.$sArguments).'"><div style="white-space: pre-wrap;">'.$sArguments.'</div></a>';
				if ($aConsolidatedStats[$sOperation]['max_args'] == $sArguments)
				{
					$sHtmlArguments = '<span style="color: red;">'.$sHtmlArguments.'</span>';
				}
				if (isset($aEvents[0]['callers']))
				{
					$sHtmlArguments .= '<div style="padding: 10px;">';
					$sHtmlArguments .= '<table border="1" bgcolor="#cfc">';
					$sHtmlArguments .= '<tr><td colspan="2" bgcolor="#e9b96">Call stack for the <b>FIRST</b> caller</td></tr>';

					foreach ($aEvents[0]['callers'] as $aCall)
					{
						$sHtmlArguments .= '<tr>';
						$sHtmlArguments .= '<td>'.$aCall['Function'].'</td>';
						$sHtmlArguments .= '<td>'.$aCall['File'].':'.$aCall['Line'].'</td>';
						$sHtmlArguments .= '</tr>';
					}
					$sHtmlArguments .= '</table>';
					$sHtmlArguments .= '</div>';
				}

				$fTotalInter = 0;
				$fMinInter = null;
				$fMaxInter = 0;
				foreach ($aEvents as $aEventData)
				{
					$fDuration = $aEventData['time'];
					$fTotalInter += $fDuration;
					$fMinInter = is_null($fMinInter) ? $fDuration : min($fMinInter, $fDuration);
					$fMaxInter = max($fMaxInter, $fDuration);
				}

				$iCountInter = count($aEvents);
				$sTotalInter = round($fTotalInter, 3);
				$sMinInter = round($fMinInter, 3);
				$sMaxInter = round($fMaxInter, 3);
				if (($fTotalInter >= self::$m_fSlowQueries))
				{
					if ($bDisplayHeader)
					{
						$sOperationHtml = '<a name="'.md5($sExecId.$sOperation).'">'.$sOperation.'</a>';
						$sHtml .= "<h4>$sOperationHtml</h4>";
						$sHtml .= "<table border=\"1\" style=\"$sTableStyle\">";
						$sHtml .= "<thead>";
						$sHtml .= "   <th>Operation details (+ blame caller if log_kpi_duration = 2)</th><th>Count</th><th>Duration</th><th>Min</th><th>Max</th>";
						$sHtml .= "</thead>";
						$bDisplayHeader = false;
					}
					$sHtml .= "<tr>";
					$sHtml .= "   <td>$sHtmlArguments</td><td>$iCountInter</td><td>$sTotalInter</td><td>$sMinInter</td><td>$sMaxInter</td>";
					$sHtml .= "</tr>";
				}
			}
			if (!$bDisplayHeader)
			{
				$sHtml .= "</table>";
				$sHtml .= "<p><a href=\"#".md5($sExecId)."\">Back to page stats</a></p>";
			}
			self::Report($sHtml);
		}
		$sHtml = '<a name="end-'.md5($sExecId).'">&nbsp;</a>';
		self::Report($sHtml);
	}

    public static function InitStats()
    {
        // Invoke extensions to initialize the KPI statistics
        /** @var \iKPILoggerExtension $oExtensionInstance */
        foreach (MetaModel::EnumPlugins('iKPILoggerExtension') as $oExtensionInstance) {
            $oExtensionInstance->InitStats();
        }
    }

	public function __construct()
	{
		$this->ResetCounters();
    }

	// Get the duration since startup, and reset the counter for the next measure
	//
	public function ComputeAndReport($sOperationDesc)
	{
		global $fItopStarted;

        if (!self::IsEnabled()) {
            return;
        }

		$aNewEntry = null;

        $fStarted = $this->m_fStarted;
        $fStopped = $this->m_fStarted;
		if (self::$m_bEnabled_Duration)	{
			$fStopped = MyHelpers::getmicrotime();
			$aNewEntry = array(
				'op'         => $sOperationDesc,
				'time_begin' => $this->m_fStarted - $fItopStarted,
				'time_end'   => $fStopped - $fItopStarted,
			);
			// Reset for the next operation (if the object is recycled)
			$this->m_fStarted = $fStopped;
		}

        $iInitialMemory = is_null($this->m_iInitialMemory) ? 0 : $this->m_iInitialMemory;
        $iCurrentMemory = 0;
        $iPeakMemory = 0;
		if (self::$m_bEnabled_Memory)
		{
			$iCurrentMemory = self::memory_get_usage();
			if (is_null($aNewEntry))
			{
				$aNewEntry = array('op' => $sOperationDesc);
			}
			$aNewEntry['mem_begin'] = $this->m_iInitialMemory;
			$aNewEntry['mem_end'] = $iCurrentMemory;
            $iPeakMemory = self::memory_get_peak_usage();
            $aNewEntry['mem_peak'] = $iPeakMemory;
			// Reset for the next operation (if the object is recycled)
			$this->m_iInitialMemory = $iCurrentMemory;
		}

        if (self::$m_bEnabled_Duration || self::$m_bEnabled_Memory) {
            // Invoke extensions to log the KPI operation
            /** @var \iKPILoggerExtension $oExtensionInstance */
            foreach(MetaModel::EnumPlugins('iKPILoggerExtension') as $oExtensionInstance)
            {
                $sExtension = ModuleService::GetInstance()->GetModuleNameFromCallStack(1);
                $oKPILogData = new KpiLogData(
                        KpiLogData::TYPE_REPORT,
                        'Step',
                        $sOperationDesc,
                        $fStarted,
                        $fStopped,
                        $sExtension,
                        $iInitialMemory,
                        $iCurrentMemory,
                        $iPeakMemory);
                $oExtensionInstance->LogOperation($oKPILogData);
            }
        }

		if (!is_null($aNewEntry) && self::$m_bGenerateLegacyReport)
		{
			self::$m_aExecData[] = $aNewEntry;
		}
		$this->ResetCounters();
	}

	/**
	 * Compute statistics for a call to an extension
	 * Note: not working in dev mode (with links to env-production)
	 *
	 * @param object|string $object object called
	 * @param string $sMethod       method called on the object
	 * @param string $sMessage      additional message
	 *
	 * @return bool true if an extension was found for this object::method
	 * @throws \ReflectionException
	 */
	public function ComputeStatsForExtension($object, string $sMethod, string $sMessage = ''): bool
    {
        if (!self::IsEnabled()) {
            return true;
        }

        $sSignature = ModuleService::GetInstance()->GetModuleMethodSignature($object, $sMethod);
        if (utils::StartsWith($sSignature, '[')) {
            $this->ComputeStats('Extension', "$sSignature $sMessage");

			return true;
        }

		return false;
    }

	public function ComputeStats($sOperation, $sArguments)
	{
		if (!self::IsEnabled()) {
			return;
		}

		$fDuration = 0;
		if (self::$m_bEnabled_Duration) {
			$fStopped = MyHelpers::getmicrotime();
			$fDuration = $fStopped - $this->m_fStarted;
            $aCallstack = [];
            if (self::$m_bGenerateLegacyReport) {
                if (self::$m_bBlameCaller) {
                    $aCallstack = MyHelpers::get_callstack(1);
                    self::$m_aStats[$sOperation][$sArguments][] = [
                            'time' => $fDuration,
                            'callers' => $aCallstack,
                    ];
                } else {
                    self::$m_aStats[$sOperation][$sArguments][] = [
                            'time' => $fDuration
                    ];
                }
            }

            $iInitialMemory = is_null($this->m_iInitialMemory) ? 0 : $this->m_iInitialMemory;
            $iCurrentMemory = 0;
            $iPeakMemory = 0;
            if (self::$m_bEnabled_Memory)
            {
                $iCurrentMemory = self::memory_get_usage();
                $iPeakMemory = self::memory_get_peak_usage();
            }

            // Invoke extensions to log the KPI operation
            /** @var \iKPILoggerExtension $oExtensionInstance */
            foreach (MetaModel::EnumPlugins('iKPILoggerExtension') as $oExtensionInstance) {
                $sExtension = ModuleService::GetInstance()->GetModuleNameFromCallStack(1);
                $oKPILogData = new KpiLogData(
                        KpiLogData::TYPE_STATS,
                        $sOperation,
                        $sArguments,
                        $this->m_fStarted,
                        $fStopped,
                        $sExtension,
                        $iInitialMemory,
                        $iCurrentMemory,
                        $iPeakMemory,
                        $aCallstack);
                $oExtensionInstance->LogOperation($oKPILogData);
            }
        }
	}

	protected function ResetCounters()
	{
		if (self::$m_bEnabled_Duration)
		{
			$this->m_fStarted = microtime(true);
		}

		if (self::$m_bEnabled_Memory)
		{
			$this->m_iInitialMemory = self::memory_get_usage();
		}
	}

	const HTML_REPORT_FILE = 'log/kpi.html';

	static protected function Report($sText)
	{
		file_put_contents(APPROOT.self::HTML_REPORT_FILE, "$sText\n", FILE_APPEND | LOCK_EX);
	}

	static protected function MemStr($iMemory)
	{
		return round($iMemory / 1024).' Kb';
	}

	static protected function memory_get_usage()
	{
        return memory_get_usage(true);
	}

	static public function memory_get_peak_usage($bRealUsage = false)
	{
		if (function_exists('memory_get_peak_usage'))
		{
			return memory_get_peak_usage($bRealUsage);
		}
		// PHP > 5.2.1 - this verb depends on a compilation option
		return 0;
	}
}
