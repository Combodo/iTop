<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\FieldSet;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

/**
 * Class FieldSetUIBlockFactory
 *
 * @author eric Espie <eric.espie@combodo.com>
 * @package UIBlockAPI
 * @api
 * @since 3.0.0
 */
class FieldSetUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UIFieldSet';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = FieldSet::class;

	/**
	 * @api
	 * @param string $sLegend
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\FieldSet\FieldSet A standard fieldset in which you can add UIBlocks
	 */
	public static function MakeStandard(string $sLegend, ?string $sId = null)
	{
		return new FieldSet($sLegend, $sId);
	}
}