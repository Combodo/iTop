<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\CollapsibleSection;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

/**
 * Class CollapsibleSectionUIBlockFactory
 *
 * @author Pierre Goiffon <pierre.goiffon@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\CollapsibleSection
 * @since 3.0.0
 * @api
 */
class CollapsibleSectionUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UICollapsibleSection';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = CollapsibleSection::class;

	/**
	 * @param string $sTitle
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\CollapsibleSection\CollapsibleSection
	 */
	public static function MakeStandard(string $sTitle, ?string $sId = null)
	{
		return new CollapsibleSection($sTitle, [], $sId);
	}
}