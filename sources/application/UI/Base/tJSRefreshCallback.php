<?php
/*
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base;

use Combodo\iTop\Application\UI\Base\Layout\iUIContentBlock;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Dict;

/**
 * Trait tJSRefreshCallback
 *
 * This brings the ability to a UIBlock to have give the JS to use in order to refresh it
 *
 * @package Combodo\iTop\Application\UI\Base
 * @internal
 * @since 3.0.0
 */
trait tJSRefreshCallback {
	/** @var string */
	protected $sJSRefresh = "";


	/**
	 * @return string
	 */
	public function GetJSRefresh():string
	{
		$sJSRefresh = $this->sJSRefresh;
		foreach ($this->GetSubBlocks() as $oSubBlock) {
			if( $oSubBlock->GetJSRefresh() !="") {
				$sJSRefresh =$oSubBlock->GetJSRefresh().'\n'.$sJSRefresh;
			}
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
