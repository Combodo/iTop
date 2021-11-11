<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Toolbar\Separator;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

/**
 * Class ToolbarSeparatorUIBlockFactory
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\Toolbar\Separator
 * @since 3.0.0
 * @api
 */
class ToolbarSeparatorUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UIToolbarSeparator';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = Toolbar::class;

	/**
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Toolbar\Separator\VerticalSeparator
	 */
	public static function MakeVertical(string $sId = null)
	{
		return new VerticalSeparator($sId);
	}
}