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

namespace Combodo\iTop\Application\UI\Base\Component\Navigation;


use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use iTopStandardURLMaker;
use utils;

/**
 * Class Navigation
 *
 * @package Combodo\iTop\Application\UI\Base\Component\Navigation
 * @since 3.1.0
 */
class Navigation extends UIContentBlock
{

	// Overloaded constants
	public const BLOCK_CODE = 'ibo-navigation';
	/** @inheritDoc */
	public const REQUIRES_ANCESTORS_DEFAULT_JS_FILES = true;
	/** @inheritDoc */
	public const REQUIRES_ANCESTORS_DEFAULT_CSS_FILES = true;
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/navigation/layout';
	public const DEFAULT_JS_TEMPLATE_REL_PATH =  'base/components/navigation/layout';
	public const DEFAULT_JS_FILES_REL_PATH = [	];

	protected $iIdx;
	protected $iCount;
	protected $iIdFirst = 0 ;
	protected $iIdPrev = 0;
	protected $iIdNext = 0;
	protected $iIdLast = 0;
	protected $aList = [];
	protected $sBasketFilter;
	protected $sBackUrl;
	protected $sBasketClass;
	protected $sPostedFields;

	/**
	 * Panel constructor.
	 *
	 * @param string $sTitle
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock[] $aSubBlocks
	 * @param string $sColorScheme Color scheme code such as "success", "failure", "active", ... {@see css/backoffice/components/_panel.scss}
	 * @param string|null $sId
	 */
	public function __construct(string $sBasketClass, int $iIdx, array $aList, string $sBasketFilter, string $sBackUrl, string $sPostedFieldsForBackUrl = "", ?string $sId = null)
	{
		parent::__construct($sId);
		$this->iCount = count($aList);
		if ($this->iCount == 0) {
			return new UIContentBlock();
		}
		$this->sBasketClass = $sBasketClass;
		$this->aList = $aList;
		$this->sBasketFilter = $sBasketFilter;
		$this->sBackUrl = $sBackUrl;
		$this->iIdx = $iIdx;
		if ($this->iIdx>0) {
			$this->iIdFirst = $aList[0];
			$this->iIdPrev = $aList[$iIdx - 1];
		}
		if ($this->iIdx < $this->iCount -1) {
			$this->iIdNext = $aList[$iIdx + 1];
			$this->iIdLast = $aList[$this->iCount - 1];
		}
		$this->sPostedFields = $sPostedFieldsForBackUrl;
	}

	/**
	 * @return int
	 */
	public function GetIdx(): int
	{
		return $this->iIdx + 1;
	}

	/**
	 * @return string
	 */
	public function GetPostedFields(): string
	{
		return $this->sPostedFields;
	}

	/**
	 * @return int
	 */
	public function GetCount(): int
	{
		return $this->iCount;
	}

	private function GetUrlFromId($iId)
	{
		$sUrl = iTopStandardURLMaker::MakeObjectURL($this->sBasketClass, $iId);
		return $sUrl;
	}

	/**
	 * @return string
	 */
	public function GetUrlFirst(): string
	{
		return $this->GetUrlFromId($this->iIdFirst);
	}

	/**
	 * @return string
	 */
	public function GetUrlPrev(): string
	{
		return $this->GetUrlFromId($this->iIdPrev);
	}

	/**
	 * @return string
	 */
	public function GetUrlNext(): string
	{
		return $this->GetUrlFromId($this->iIdNext);
	}

	/**
	 * @return int|mixed
	 */
	public function GetUrlLast(): string
	{
		return $this->GetUrlFromId($this->iIdLast);
	}

	/**
	 * @return string
	 */
	public function GetBackUrl(): string
	{
		return $this->sBackUrl;
	}

	/**
	 * @return string
	 */
	public function GetBasketFilter(): string
	{
		return $this->sBasketFilter;
	}

	/**
	 * @return string
	 */
	public function GetList(): string
	{
		return json_encode($this->aList);
	}

	/**
	 * @return bool
	 */
	public function HasPrec(): bool
	{
		return $this->iIdx > 0;
	}
	/**
	 * @return bool
	 */
	public function HasNext(): bool
	{
		return $this->iIdx+1 < $this->iCount;
	}

}
