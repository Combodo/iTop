<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\Service;


use Combodo\iTop\Application\UI\iUIBlock;
use DBSearch;
use DisplayBlock;
use WebPage;

class DisplayBlockFactory
{
	public static function GetUIBlockForList(DBSearch $oFilter, WebPage $oPage, $sId, $aFilterParams = array(), $aExtraParams = array()): iUIBlock
	{
		$oDisplayBlock = new DisplayBlock($oFilter, 'list', false, $aFilterParams);
		return $oDisplayBlock->GetDisplay($oPage, $sId, $aExtraParams);
	}
}