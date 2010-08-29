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
 * Mesures operations duration
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

class Duration
{
	static $m_bEnabled = false;

	static public function Enable()
	{
		self::$m_bEnabled = true;
	}

	protected $m_fStarted = null;

	public function __construct()
	{
		if (!self::$m_bEnabled) return;

		$this->m_fStarted = MyHelpers::getmicrotime();
	}

	// Get the duration since startup, and reset the counter for the next measure
	//
	public function Scratch($sMeasure)
	{
		if (!self::$m_bEnabled) return;

		$fStopped = MyHelpers::getmicrotime();
		$fDuration = $fStopped - $this->m_fStarted;
		$this->Report($sMeasure.': '.round($fDuration, 3));

		$this->m_fStarted = MyHelpers::getmicrotime();
	}

	protected function Report($sText)
	{
		echo "DURATION... $sText<br/>\n";
	}
}

// Prototype, to be finalized later
// Reports the function duration
// One single thing to do: construct it
class FunctionDuration
{
	protected $m_sFunction = null;

	public function __construct()
	{
		$this->m_sFunction = 'my_function_name_in_call_stack';
		$this->m_fStarted = MyHelpers::getmicrotime();
	}

	public function __destruct()
	{
		$this->Scratch('Exiting ');
	}
}

?>
