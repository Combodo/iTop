<?php
// Copyright (C) 2016-2017 Combodo SARL
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
 * Simple helper class for keeping track of the context inside the call stack
 * 
 * To check (anywhere in the code) if a particular context tag is present
 * in the call stack simply do:
 * 
 * if (ContextTag::Check(<the_tag>)) ...
 * 
 * For example to know if the code is being executed in the context of a portal do:
 * 
 * if (ContextTag::Check('GUI:Portal'))
 *
 * @copyright   Copyright (C) 2016-2017 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class ContextTag
{
	protected static $aStack = array();
	
	/**
	 * Store a context tag on the stack
	 * @param string $sTag
	 */
	public function __construct($sTag)
	{
		static::$aStack[] = $sTag;
	}
	
	/**
	 * Cleanup the context stack
	 */
	public function __destruct()
	{
		array_pop(static::$aStack);
	}
	
	/**
	 * Check if a given tag is present in the stack
	 * @param string $sTag
	 * @return bool
	 */
	public static function Check($sTag)
	{
		return in_array($sTag, static::$aStack);
	}
	
	/**
	 * Get the whole stack as an array
	 * @return hash
	 */
	public static function GetStack()
	{
		return static::$aStack;
	}
}