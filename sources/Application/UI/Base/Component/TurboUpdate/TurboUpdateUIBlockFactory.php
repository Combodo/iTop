<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\TurboUpdate;

use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

/**
 * Class TurboUpdateUIBlockFactory
 *
 * @api
 * @since 3.3.0
 * @package UIBlockAPI
 */
class TurboUpdateUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UITurboUpdate';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = TurboUpdate::class;

	/**
	 * @api
	 *
	 * @param string $sTarget   Id of the block to update
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\TurboUpdate\TurboUpdate An HTML form in which you can add UIBlocks
	 */
	public static function MakeStandard(string $sTarget, string $sId = null): TurboUpdate
	{
		return new TurboUpdate($sTarget, $sId);
	}
}
