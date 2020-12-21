<?php
/**
 * Copyright (C) 2013-2020 Combodo SARL
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

namespace Combodo\iTop\Application\UI\Base\Component\CollapsibleSection;


use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Combodo\iTop\Application\UI\Base\tUIContentAreas;

/**
 * @package Combodo\iTop\Application\UI\Base\Component\CollapsibleSection
 * @since 3.0.0
 */
class CollapsibleSection extends UIContentBlock
{
	use tUIContentAreas;

	// Overloaded constants
	public const BLOCK_CODE = 'ibo-collapsible-section';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/collapsible-section/layout';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'base/components/collapsible-section/layout';
	public const DEFAULT_JS_FILES_REL_PATH = [
		'js/components/collapsible-section.js',
	];

	/** @var bool */
	protected $bIsOpenedByDefault = false;
	/** @var string */
	private $sTitle;

	public function __construct(string $sTitle, array $aSubBlocks = [], ?string $sId = null)
	{
		parent::__construct($sId);
		$this->sTitle = $sTitle;
		$this->aSubBlocks = $aSubBlocks;
	}


	public function IsOpenedByDefault()
	{
		return $this->bIsOpenedByDefault;
	}

	/**
	 * @param bool $bIsOpenedByDefault
	 *
	 * @return $this
	 */
	public function SetOpenedByDefault(bool $bIsOpenedByDefault)
	{
		$this->bIsOpenedByDefault = $bIsOpenedByDefault;

		return $this;
	}

	public function GetTitle()
	{
		return $this->sTitle;
	}
}