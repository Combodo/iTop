<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base;

/**
 * Trait tJSRefreshCallback
 *
 * This brings the ability to a UIBlock to give the JS to use in order to refresh it
 * All bricks with JS refresh must use directly this trait in order to be found by the function class_uses in GetRecursiveJSRefresh
 *
 * @internal
 * @package Combodo\iTop\Application\UI\Base
 * @since 3.0.0
 */
trait tJSRefreshCallback
{
	/** @var string */
	protected $sJSRefresh = "";


	/**
	 * Get JS refresh for $this
	 *
	 * @return string
	 */
	public function GetJSRefresh(): string
	{
		return $this->sJSRefresh;
	}

	/**
	 * Get the global JS refresh for all subblocks
	 *
	 * @return string
	 */
	public function GetJSRefreshCallback(): string
	{
		$sJSRefresh = $this->GetJSRefresh();
		self::GetRecursiveJSRefresh($this, $sJSRefresh);

		return $sJSRefresh;
	}

	/**
	 * method only for private use in GetJSRefreshCallback
	 *
	 * @param $oBlock
	 * @param $sJSRefresh
	 *
	 * @return string
	 */
	public static function GetRecursiveJSRefresh($oBlock, &$sJSRefresh): string
	{
		foreach ($oBlock->GetSubBlocks() as $oSubBlock) {
			$usingTrait = in_array('Combodo\iTop\Application\UI\Base\tJSRefreshCallback', class_uses(get_class($oSubBlock)));
			if ($usingTrait && $oSubBlock->GetJSRefresh() != "") {
				$sJSRefresh = $oSubBlock->GetJSRefresh()."\n".$sJSRefresh;
			}
			self::GetRecursiveJSRefresh($oSubBlock, $sJSRefresh);
		}

		return $sJSRefresh;
	}

	/**
	 * @param string $sJSRefresh
	 */
	public function SetJSRefresh(string $sJSRefresh)
	{
		$this->sJSRefresh = $sJSRefresh;
	}
}
