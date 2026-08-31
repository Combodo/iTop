<?php

/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Application\UI\Base\Component\ButtonBar;

use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Button\Button;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonSeparator;

/**
 * Class ButtonBarUIBlockFactory
 *
 * @api
 * @since 3.3.0
 */
class ButtonBarUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UIButtonBar';

	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = ButtonBar::class;

	/**
	 * Build a button bar from a list of items.
	 *
	 * Each item can be:
	 * - an iUIBlock instance (label defaults to block id)
	 * - an array ['block' => iUIBlock, 'label' => '...']
	 *
	 * @param array $aItems
	 * @param string|null $sMoreButtonTooltipText
	 * @param string|null $sId
	 *
	 * @return ButtonBar
	 */
	public static function MakeStandard(
		array $aItems = [],
		?string $sMoreButtonTooltipText = null,
		?string $sId = null
	): ButtonBar {
		return static::BuildButtonBar($aItems, $sMoreButtonTooltipText, $sId, ButtonBar::OVERFLOW_MODE_FIT);
	}

	/**
	 * Build a button bar using a fixed visible item count before overflow.
	 *
	 * @param array $aItems
	 * @param int $iOverflowCount Number of items to keep visible in the bar
	 * @param string|null $sMoreButtonTooltipText
	 * @param string|null $sId
	 *
	 * @return ButtonBar
	 */
	public static function MakeWithCountOverflow(
		array $aItems = [],
		int $iOverflowCount = 3,
		?string $sMoreButtonTooltipText = null,
		?string $sId = null
	): ButtonBar {
		return static::BuildButtonBar($aItems, $sMoreButtonTooltipText, $sId, ButtonBar::OVERFLOW_MODE_COUNT, $iOverflowCount);
	}

	/**
	 * Build a button bar overflowing everything after a marker button.
	 *
	 * @param array $aItems
	 * @param string $sOverflowStartAfterButtonId Button id used as marker
	 * @param string|null $sMoreButtonTooltipText
	 * @param string|null $sId
	 *
	 * @return ButtonBar
	 */
	public static function MakeWithAfterMarkerOverflow(
		string $sOverflowStartAfterButtonId,
		array $aItems = [],
		?string $sMoreButtonTooltipText = null,
		?string $sId = null
	): ButtonBar {
		return static::BuildButtonBar(
			$aItems,
			$sMoreButtonTooltipText,
			$sId,
			ButtonBar::OVERFLOW_MODE_AFTER_MARKER,
			3,
			$sOverflowStartAfterButtonId
		);
	}

	/**
	 * Internal builder shared by the public named constructors.
	 */
	protected static function BuildButtonBar(
		array $aItems,
		?string $sMoreButtonTooltipText,
		?string $sId,
		string $sOverflowMode,
		int $iOverflowCount = 3,
		?string $sOverflowStartAfterButtonId = null
	): ButtonBar {
		$oButtonBar = new ButtonBar([], $sMoreButtonTooltipText, $sId);
		$oButtonBar
			->SetOverflowMode($sOverflowMode)
			->SetOverflowCount($iOverflowCount)
			->SetOverflowStartAfterButtonId($sOverflowStartAfterButtonId);

		foreach ($aItems as $oItem) {
			if ($oItem instanceof Button || $oItem instanceof ButtonSeparator) {
				$oButtonBar->AddButton($oItem);
			}
		}

		return $oButtonBar;
	}
}
