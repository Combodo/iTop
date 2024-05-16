<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Toolbar;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

/**
 * Class ToolbarUIBlockFactory
 *
 * @author Eric Espie <eric.espie@combodo.com>
 * @package UIBlockAPI
 * @api
 * @since 3.0.0
 */
class ToolbarUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UIToolbar';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = Toolbar::class;

	/**
	 * @api
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Toolbar\Toolbar
	 */
	public static function MakeForAction(string $sId = null)
	{
		return new Toolbar($sId, ['ibo-toolbar--action']);
	}

	/**
	 * @api
	 * @param string|null $sId
	 * @param array $aContainerClasses
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Toolbar\Toolbar
	 */
	public static function MakeStandard(string $sId = null, array $aContainerClasses = [])
	{
		return new Toolbar($sId, $aContainerClasses);
	}

	/**
	 * @api
	 * @param string|null $sId
	 * @param array $aContainerClasses
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Toolbar\Toolbar
	 */
	public static function MakeForButton(string $sId = null, array $aContainerClasses = [])
	{
		return new Toolbar($sId, array_merge($aContainerClasses, ['ibo-toolbar--button']));
	}
}