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

namespace Combodo\iTop\Application\UI\Base\Component\GlobalSearch;


use Combodo\iTop\Application\UI\Base\UIBlock;
use Combodo\iTop\Application\UI\Hook\iKeyboardShortcut;
use MetaModel;
use utils;

/**
 * Class GlobalSearch
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\GlobalSearch
 * @internal
 * @since 3.0.0
 */
class GlobalSearch extends UIBlock implements iKeyboardShortcut
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-global-search';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/global-search/layout';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'base/components/global-search/layout';
	public const DEFAULT_JS_FILES_REL_PATH = [
		'js/components/global-search.js',
	];

	public const DEFAULT_ENDPOINT_REL_URL = 'pages/UI.php';

	/** @var string $sEndpoint Absolute endpoint URL of the search form */
	protected $sEndpoint;
	/** @var string Query currently in the input */
	protected $sQuery;
	/** @var array $aLastQueries */
	protected $aLastQueries;
	/** @var bool $bShowHistory Whether or not to display the elements in the history */
	protected $bShowHistory;
	/** @var int $iMaxHistoryResults Max. number of elements in the history */
	protected $iMaxHistoryResults;

	/**
	 * GlobalSearch constructor.
	 *
	 * @param array $aLastQueries
	 * @param string|null $sId
	 *
	 * @throws \Exception
	 */
	public function __construct(array $aLastQueries = [], ?string $sId = null)
	{
		parent::__construct($sId);
		$this->SetEndpoint(static::DEFAULT_ENDPOINT_REL_URL);
		$this->SetQuery('');
		$this->SetLastQueries($aLastQueries);
		$this->bShowHistory = (bool)MetaModel::GetConfig()->Get('global_search.show_history');
		$this->iMaxHistoryResults = (int)MetaModel::GetConfig()->Get('global_search.max_history_results');
	}

	/**
	 * Set the search form endpoint URL.
	 * If $bRelativeUrl is true, then $sEndpoint will be complete with the app_root_url
	 *
	 * @param string $sEndpoint URL to the endpoint
	 * @param bool $bRelativeUrl Whether or not the $sEndpoint parameter is a relative URL
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function SetEndpoint(string $sEndpoint, bool $bRelativeUrl = true)
	{
		$this->sEndpoint = (($bRelativeUrl) ? utils::GetAbsoluteUrlAppRoot() : '').$sEndpoint;

		return $this;
	}

	/**
	 * Return the absolute URL of the search form
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function GetEndpoint(): string
	{
		return $this->sEndpoint;
	}

	/**
	 * @uses $sQuery
	 * @param string $sQuery
	 *
	 * @return $this
	 */
	public function SetQuery(string $sQuery)
	{
		$this->sQuery = $sQuery;
		return $this;
	}

	/**
	 * @uses $sQuery
	 * @return string
	 */
	public function GetQuery(): string
	{
		return $this->sQuery;
	}

	/**
	 * @uses $sQuery
	 * @return bool
	 */
	public function HasQuery(): bool
	{
		return !empty($this->sQuery);
	}

	/**
	 * Set all the last queries at once
	 *
	 * @param array $aLastQueries
	 *
	 * @return $this
	 */
	public function SetLastQueries(array $aLastQueries)
	{
		$this->aLastQueries = $aLastQueries;

		return $this;
	}

	/**
	 * Return the last queries (query itself, label as HTML)
	 *
	 * @return array
	 */
	public function GetLastQueries(): array
	{
		return $this->aLastQueries;
	}

	/**
	 * @see $bShowHistory
	 * @return bool
	 */
	public function GetShowHistory(): bool
	{
		return $this->bShowHistory;
	}

	/**
	 * @see $iMaxHistoryResults
	 * @return int
	 */
	public function GetMaxHistoryResults(): int
	{
		return $this->iMaxHistoryResults;
	}

	public static function GetShortcutKeys(): array
	{
		return [['id' => 'ibo-open-global-search', 'label' => 'UI:Component:GlobalSearch:KeyboardShortcut:OpenDrawer', 'key' => 'g', 'event' => 'open_drawer']];
	}

	public static function GetShortcutTriggeredElementSelector(): string
	{
		return "[data-role='".static::BLOCK_CODE."']";
	}
}