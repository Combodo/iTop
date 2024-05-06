<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarSpacer;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

/**
 * Class ToolbarSpacerUIBlockFactory
 *
 * @author Eric Espie <eric.espie@combodo.com>
 * @package UIBlockAPI
 * @api
 * @since 3.0.0
 */
class ToolbarSpacerUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UIToolbarSpacer';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = ToolbarSpacer::class;

	/**
	 * @api
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarSpacer\ToolbarSpacer
	 */
	public static function MakeStandard(string $sId = null)
	{
		return new ToolbarSpacer($sId);
	}
}