<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarSpacer;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

class ToolbarSpacerUIBlockFactory extends AbstractUIBlockFactory
{
	public const TWIG_TAG_NAME = 'UIToolbarSpacer';
	public const UI_BLOCK_CLASS_NAME = ToolbarSpacer::class;

	/**
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarSpacer\ToolbarSpacer
	 */
	public static function MakeStandard(string $sId = null): ToolbarSpacer
	{
		return new ToolbarSpacer($sId);
	}
}