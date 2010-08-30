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
 * Measures operations duration, memory usage, etc. (and some other KPIs)
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

class ExecutionKPI
{
	static protected $m_bEnabled_Duration = false;
	static protected $m_bEnabled_Memory = false;

	static protected $m_aStats = array();

	protected $m_fStarted = null;
	protected $m_iInitialMemory = null;

	static public function EnableDuration()
	{
		self::$m_bEnabled_Duration = true;
	}

	static public function EnableMemory()
	{
		self::$m_bEnabled_Memory = true;
	}

	static public function ReportStats()
	{
		foreach (self::$m_aStats as $sOperation => $aOpStats)
		{
			echo "====================<br/>\n";
			echo "KPIs for $sOperation<br/>\n";
			echo "====================<br/>\n";
			$fTotalOp = 0;
			$iTotalOp = 0;
			foreach ($aOpStats as $sArguments => $aEvents)
			{
				$fTotalInter = 0;
				$iTotalInter = 0;
				foreach ($aEvents as $fDuration)
				{
					$fTotalInter += $fDuration;
					$iTotalInter++;
				}
				$fTotalOp += $fTotalInter;
				$iTotalOp++;
				echo "$sArguments: $iTotalInter (".round($fTotalInter, 3).")<br/>\n";
			}
			echo "Total: $iTotalOp (".round($fTotalOp, 3).")<br/>\n";
			echo "====================<br/>\n";
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
		if (self::$m_bEnabled_Duration)
		{
			$fStopped = MyHelpers::getmicrotime();
			$fDuration = $fStopped - $this->m_fStarted;
			$this->Report($sOperationDesc.' / duration: '.round($fDuration, 3));
		}

		if (self::$m_bEnabled_Memory)
		{
			$iMemory = self::memory_get_usage();
			$iMemoryUsed = $iMemory - $this->m_iInitialMemory;
			$this->Report($sOperationDesc.' / memory: '.self::MemStr($iMemoryUsed).' (Total: '.self::MemStr($iMemory).')');
		}

		$this->ResetCounters();
	}

	public function ComputeStats($sOperation, $sArguments)
	{
		if (self::$m_bEnabled_Duration)
		{
			$fStopped = MyHelpers::getmicrotime();
			$fDuration = $fStopped - $this->m_fStarted;
			self::$m_aStats[$sOperation][$sArguments][] = $fDuration;
		}
	}

	protected function ResetCounters()
	{
		if (self::$m_bEnabled_Duration)
		{
			$this->m_fStarted = MyHelpers::getmicrotime();
		}

		if (self::$m_bEnabled_Memory)
		{
			$this->m_iInitialMemory = self::memory_get_usage();
		}
	}

	protected function Report($sText)
	{
		echo "$sText<br/>\n";
	}

	static protected function MemStr($iMemory)
	{
		return round($iMemory / 1024).' Kb';
	}

	static protected function memory_get_usage()
	{
		if (function_exists('memory_get_usage'))
		{
			return memory_get_usage(true);
		}

		// Copied from the PHP manual
		//
		//If its Windows
		//Tested on Win XP Pro SP2. Should work on Win 2003 Server too
		//Doesn't work for 2000
		//If you need it to work for 2000 look at http://us2.php.net/manual/en/function.memory-get-usage.php#54642
		if (substr(PHP_OS,0,3) == 'WIN') 
		{
			$output = array();
			exec('tasklist /FI "PID eq ' . getmypid() . '" /FO LIST', $output);

			return preg_replace( '/[\D]/', '', $output[5] ) * 1024;
		}
		else
		{
			//We now assume the OS is UNIX
			//Tested on Mac OS X 10.4.6 and Linux Red Hat Enterprise 4
			//This should work on most UNIX systems
			$pid = getmypid();
			exec("ps -eo%mem,rss,pid | grep $pid", $output);
			$output = explode("  ", $output[0]);
			//rss is given in 1024 byte units
			return $output[1] * 1024;
		}
	}
}

?>
