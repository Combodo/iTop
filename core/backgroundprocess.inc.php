<?php
// Copyright (C) 2010-2013 Combodo SARL
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
 * interface iProcess
 * Something that can be executed 
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
interface iProcess
{
	/**
	 * @param int $iUnixTimeLimit
	 *
	 * @return string status message
	 * @throws \ProcessException
	 * @throws \ProcessFatalException
	 * @throws MySQLHasGoneAwayException
	 */
	public function Process($iUnixTimeLimit);
}

/**
 * interface iBackgroundProcess
 * Any extension that must be called regularly to be executed in the background 
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
interface iBackgroundProcess extends iProcess
{
	/**
	 * @return int repetition rate in seconds
	 */
	public function GetPeriodicity();
}

/**
 * interface iScheduledProcess
 * A variant of process that must be called at specific times
 *
 * @copyright   Copyright (C) 2013 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
interface iScheduledProcess extends iProcess
{
	/**
	 * @return DateTime exact time at which the process must be run next time
	 */
	public function GetNextOccurrence();
}

/**
 * Class ProcessException
 * Exception for iProcess implementations.<br>
 * An error happened during the processing but we can go on with the next implementations.
 */
class ProcessException extends CoreException
{

}

/**
 * Class ProcessFatalException
 * Exception for iProcess implementations.<br>
 * A big error occurred, we have to stop the iProcess processing.
 */
class ProcessFatalException extends CoreException
{

}
