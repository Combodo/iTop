<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\FieldSet;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

class FieldSetUIBlockFactory extends AbstractUIBlockFactory
{
	public const TWIG_TAG_NAME = 'UIFieldSet';
	public const UI_BLOCK_CLASS_NAME = FieldSet::class;

	public static function MakeStandard(string $sLegend, ?string $sId = null): FieldSet
	{
		return new FieldSet($sLegend, $sId);
	}
}