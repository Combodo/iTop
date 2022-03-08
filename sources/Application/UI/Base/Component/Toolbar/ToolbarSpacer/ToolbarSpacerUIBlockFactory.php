<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarSpacer;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

/**
 * Class ToolbarSpacerUIBlockFactory
 *
 * @author Eric Espie <eric.espie@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarSpacer
 * @since 3.0.0
 * @internal
 */
class ToolbarSpacerUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UIToolbarSpacer';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = ToolbarSpacer::class;

	/**
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarSpacer\ToolbarSpacer
	 */
	public static function MakeStandard(string $sId = null)
	{
		return new ToolbarSpacer($sId);
	}
}