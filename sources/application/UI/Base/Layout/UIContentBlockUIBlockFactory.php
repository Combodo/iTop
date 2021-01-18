<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Layout;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

class UIContentBlockUIBlockFactory extends AbstractUIBlockFactory
{
	public const UI_BLOCK_CLASS_NAME = "Combodo\\iTop\\Application\\UI\\Base\\Layout\\UIContentBlock";
	public const TWIG_TAG_NAME = 'UIContentBlock';

	public static function MakeStandard(string $sId = null, string $sContainerClass = '')
	{
		return new UIContentBlock($sId, $sContainerClass);
	}
}