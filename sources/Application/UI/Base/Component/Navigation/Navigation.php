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
	protected $sFilter;
	protected $sClass;

	/**
	 * Panel constructor.
	 *
	 * @param string $sTitle
	 * @param \Combodo\iTop\Application\UI\Base\iUIBlock[] $aSubBlocks
	 * @param string $sColorScheme Color scheme code such as "success", "failure", "active", ... {@see css/backoffice/components/_panel.scss}
	 * @param string|null $sId
	 */
	public function __construct(string $sClass, int $iIdx, array $aList, string $sFilter = '', ?string $sId = null)
	{
		parent::__construct($sId);
		$this->iCount = count($aList);
		if ( $this->iCount == 0) {
			return new UIContentBlock();
		}
		$this->sClass = $sClass;
		$this->aList = $aList;
		$this->sFilter = $sFilter;
		$this->iIdx = $iIdx;
		if ($this->iIdx>0) {
			$this->iIdFirst = $aList[0];
			$this->iIdPrev = $aList[$iIdx - 1];
		}
		if ($this->iIdx < $this->iCount -1) {
			$this->iIdNext = $aList[$iIdx + 1];
			$this->iIdLast = $aList[$this->iCount - 1];
		}
	}

	/**
	 * @return int
	 */
	public function GetIdx(): int
	{
		return $this->iIdx+1;
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
		$sUrl = iTopStandardURLMaker::MakeObjectURL($this->sClass, $iId).'&filter='.urlencode($this->sFilter);
		return $sUrl;
	}
	/**
	 * @return int|mixed
	 */
	public function GetUrlFirst()
	{
		return $this->GetUrlFromId( $this->iIdFirst);
	}

	/**
	 * @return int|mixed
	 */
	public function GetUrlPrev()
	{
		return $this->GetUrlFromId( $this->iIdPrev);
	}

	/**
	 * @return int|mixed
	 */
	public function GetUrlNext()
	{
		return $this->GetUrlFromId( $this->iIdNext);
	}

	/**
	 * @return int|mixed
	 */
	public function GetUrlLast()
	{
		return $this->GetUrlFromId( $this->iIdLast);
	}

	/**
	 * @return string
	 */
	public function GetList(): string
	{
		return json_encode($this->aList);
	}

	public function GetUrlSearch(){
		$sAbsoluteUrl = utils::GetAbsoluteUrlAppRoot();
		return "{$sAbsoluteUrl}pages/UI.php?operation=search&filter=".urlencode(urlencode('["'.$this->sFilter.'",[],[]]'));
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
