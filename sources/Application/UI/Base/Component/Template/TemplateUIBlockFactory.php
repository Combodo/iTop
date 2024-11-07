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

namespace Combodo\iTop\Application\UI\Base\Component\Template;

use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;

/**
 * Class TemplateUIBlockFactory
 *
 * @api
 *
 * @author Benjamin Dalsass <benjamin.dalsass@combodo.com>
 * @package UIBlockAPI
 * @since 3.1.0
 * @link
 */
class TemplateUIBlockFactory extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UITemplate';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = Template::class;

	/**
	 * Make a Template component
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Template\Template
	 */
	public static function MakeStandard(string $sId)
	{
		return new Template($sId);
	}

	/**
	 * Make a Template component with a block inside.
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\Template\Template
	 */
	public static function MakeForBlock(string $sId, UIContentBlock $oContentBlock)
	{
		$oBlock = TemplateUIBlockFactory::MakeStandard($sId);
		$oBlock->AddSubBlock($oContentBlock);

		return $oBlock;
	}
}