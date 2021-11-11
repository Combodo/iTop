<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Toolbar;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

/**
 * Class ToolbarUIBlockFactory
 *
 * @author Eric Espie <eric.espie@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\Toolbar
 * @since 3.0.0
 * @internal
 */
class ToolbarUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UIToolbar';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = Toolbar::class;

	public static function MakeForAction(string $sId = null)
	{
		return new Toolbar($sId, ['ibo-toolbar--action']);
	}

	public static function MakeStandard(string $sId = null, array $aContainerClasses = [])
	{
		return new Toolbar($sId, $aContainerClasses);
	}

	public static function MakeForButton(string $sId = null, array $aContainerClasses = [])
	{
		return new Toolbar($sId, array_merge($aContainerClasses, ['ibo-toolbar--button']));
	}
}