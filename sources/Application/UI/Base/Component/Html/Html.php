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

namespace Combodo\iTop\Application\UI\Base\Component\Html;


use Combodo\iTop\Application\UI\Base\UIBlock;

/**
 * Class Html
 *
 * @author Stephen Abello <stephen.abello@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\Html
 * @since 3.0.0
 */
class Html extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-html';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/html/layout';

	/** @var string $sHtml The raw HTML, must be already sanitized */
	protected $sHtml;

	/**
	 * Html constructor.
	 *
	 * @param string $sHtml
	 * @param string|null $sId
	 */
	public function __construct(string $sHtml = '', ?string $sId = null)
	{
		$this->sHtml = $sHtml;
		parent::__construct($sId);
	}

	/**
	 * Return the raw HTML, should have been sanitized
	 *
	 * @return string
	 */
	public function GetHtml(): string
	{
		return $this->sHtml;
	}

	/**
	 * Set the raw HTML, must be already sanitized
	 *
	 * @param string $sHtml
	 *
	 * @return $this
	 */
	public function SetHtml(string $sHtml)
	{
		$this->sHtml = $sHtml;

		return $this;
	}

	/**
	 * Add HTML, must be already sanitized
	 *
	 * @param string $sHtml
	 *
	 * @return $this
	 */
	public function AddHtml(string $sHtml)
	{
		$this->sHtml .= $sHtml;

		return $this;
	}
}