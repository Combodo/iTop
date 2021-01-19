<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\CollapsibleSection;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

class CollapsibleSectionUIBlockFactory extends AbstractUIBlockFactory
{
	public const TWIG_TAG_NAME = 'UICollapsibleSection';
	public const UI_BLOCK_CLASS_NAME = CollapsibleSection::class;

	public static function MakeStandard(string $sTitle, ?string $sId = null)
	{
		return new CollapsibleSection($sTitle, [], $sId);
	}
}