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


use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;

/**
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Dialog
 * @since 3.1.0
 */
class Dialog extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE                            = 'ibo-dialog';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH        = 'base/components/dialog/layout';
	public const DEFAULT_JS_ON_READY_TEMPLATE_REL_PATH = 'base/components/dialog/layout';
	public const DEFAULT_JS_FILES_REL_PATH             = [
		'js/components/dialog.js',
	];

	/** @var string $sTitle */
	protected string $sTitle;

	/** @var string $sContent */
	protected string $sContent;

	/**
	 * Alert constructor.
	 *
	 * @param string $sTitle
	 * @param string $sContent
	 * @param string|null $sId
	 */
	public function __construct(string $sTitle = '', string $sContent = '', ?string $sId = null)
	{
		parent::__construct($sId);
		$this->sContent = $sContent;
		if (!empty($sContent)) {
			$this->AddSubBlock(new Html($sContent));
		}
	}

	/**
	 * @return string
	 */
	public function GetTitle(): string
	{
		return $this->sTitle;
	}

	/**
	 * @param string $sTitle Title of the alert
	 *
	 * @return $this
	 */
	public function SetTitle(string $sTitle): Dialog
	{
		$this->sTitle = $sTitle;

		return $this;
	}

	/**
	 * Return the raw HTML content, should be already sanitized.
	 *
	 * @return string
	 */
	public function GetContent(): string
	{
		return $this->sContent;
	}

	/**
	 * Set the raw HTML content, must be already sanitized.
	 *
	 * @param string $sContent The raw HTML content, must be already sanitized
	 *
	 * @return $this
	 */
	public function SetContent(string $sContent): Dialog
	{
		$this->sContent = $sContent;

		return $this;
	}



}