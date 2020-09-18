<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Component\Title;


class TitleFactory
{

	public static function MakeForPage(string $sTitle, ?string $sId = null)
	{
		return new Title($sTitle, 1, $sId);
	}
}