<?php
// Copyright (C) 2016-2024 Combodo SAS
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
 * Beware:
 * As the destructor removes the last context, don't use the same variable if you want to keep your context.
 *      $oCtx = new ContextTag("Tag1");
 *      $oCtx = new ContextTag("Tag2");  // Bad the destructor will remove "Tag2"
 *      $oCtx = new ContextTag("Tag3");  // Bad the destructor will remove "Tag3"
 *
 * Instead, use separate variables:
 *      $oCtx1 = new ContextTag("Tag1");
 *      $oCtx2 = new ContextTag("Tag2");
 *      $oCtx3 = new ContextTag("Tag3");
 *
 * And don't forget that any destructor (of $oCtx1, $oCtx2 or $oCtx3) will remove the LAST added tag ("Tag3" in our example)
 *
 * If you want to declare permanent contexts (request lifetime) you should use:
 *      ContextTag::AddContext("Tag1");
 *      ContextTag::AddContext("Tag2");
 *      ContextTag::AddContext("Tag3");
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
 * @copyright   Copyright (C) 2016-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
class ContextTag
{
	public const TAG_PORTAL  = 'GUI:Portal';
	public const TAG_CRON    = 'CRON';
	public const TAG_CONSOLE = 'GUI:Console';
	public const TAG_SETUP   = 'Setup';
	public const TAG_SYNCHRO = 'Synchro';
	public const TAG_REST    = 'REST/JSON';

	/**
	 * @since 3.1.0 N°6047
	 */
	public const TAG_IMPORT    = 'Import';
		/**
	 * @since 3.1.0 N°6047
	 */
	public const TAG_EXPORT    = 'Export';

	/**
	 * @var string
	 * @since 3.1.0 N°3200
	 */
	public const TAG_OBJECT_SEARCH = 'ObjectSearch';

	protected static $aStack = array();

	/**
	 * Store a context tag on the stack
	 *
	 * @param string $sTag
	 */
	public function __construct($sTag)
	{
		static::$aStack[] = $sTag;
	}

	public static function AddContext($sTag)
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
	 * @return array
	 */
	public static function GetStack()
	{
		return static::$aStack;
	}

	/**
	 * Get all the predefined context tags
	 * @return array
	 */
	public static function GetTags()
	{
		$aRawTags = array(
			ContextTag::TAG_REST,
			ContextTag::TAG_SYNCHRO,
			ContextTag::TAG_SETUP,
			ContextTag::TAG_CONSOLE,
			ContextTag::TAG_CRON,
			ContextTag::TAG_PORTAL);

		$aTags = array();

		foreach ($aRawTags as $sRawTag)
		{
			$aTags[$sRawTag] = Dict::S("Core:Context={$sRawTag}");
		}

		$aPortalsConf = PortalDispatcherData::GetData();
		$aDispatchers = array();
		foreach ($aPortalsConf as $sPortalId => $aConf)
		{
			$sHandlerClass = $aConf['handler'];
			$aDispatchers[$sPortalId] = new $sHandlerClass($sPortalId);
		}

		foreach ($aDispatchers as $sPortalId => $oDispatcher)
		{
			if ($sPortalId != 'backoffice')
			{
				$aTags['Portal:'.$sPortalId] = $oDispatcher->GetLabel();
			}
		}

		return $aTags;
	}
}
