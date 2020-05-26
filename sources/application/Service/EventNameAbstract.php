<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Service;


abstract class EventNameAbstract implements iEventName
{
	const MODULE_CODE = '';

	public function __construct()
	{
//		The interface force an empty & public constructor... let's make it useful:
//
//               __
//             <(o )___             _      _      _
//              ( ._> /           >(.)__ <(.)__ =(.)__
//               `---'             (___/  (___/  (___/              quack !
	}

	/**
	 * @inheritDoc
	 */
	public function GetEventNameList()
	{
		$oReflectionClass = new \ReflectionClass(static::class);
		$aRawEventNameList = $oReflectionClass->getConstants();

		unset($aRawEventNameList['MODULE_CODE']);

		$aEventNameList = array();
		foreach ($aRawEventNameList as $sConstName => $sConstValue)
		{
			$sConstName = static::class.'::'.$sConstName;
			$aEventNameList[$sConstName] = $sConstValue;
		}

		return array(
			'module' => static::MODULE_CODE,
			'events' => $aEventNameList,
		);
	}
}
