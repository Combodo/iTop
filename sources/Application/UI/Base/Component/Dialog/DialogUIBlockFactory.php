<?php
/**
 * Copyright (C) 2013-2021 Combodo SARL
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

namespace Combodo\iTop\Application\UI\Base\Component\Dialog;

use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

/**
 * Class DialogUIBlockFactory
 *
 * @api
 *
 * @since 3.1.0
 * @package Combodo\iTop\Application\UI\Base\Component\Dialog
 */
class DialogUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UIDialog';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = Dialog::class;

	/**
	 * Make a basis Dialog component
	 *
	 * @param string $sTitle Title of the alert
	 * @param string $sContent The raw HTML content, must be already sanitized
	 * @param string|null $sId id of the html block
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Alert\Alert
	 */
	public static function MakeNeutral(string $sTitle = '', string $sContent = '', ?string $sId = null)
	{
		return new Dialog($sTitle, $sContent, $sId);
	}


}