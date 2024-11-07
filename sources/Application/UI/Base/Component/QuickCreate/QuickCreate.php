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

namespace Combodo\iTop\Application\UI\Base\Component\QuickCreate;


use Combodo\iTop\Application\UI\Base\UIBlock;
use Combodo\iTop\Application\UI\Hook\iKeyboardShortcut;
use MetaModel;
use UserRights;
use utils;

/**
 * Class QuickCreate
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\QuickCreate
 * @internal
 * @since 3.0.0
 */
class QuickCreate extends UIBlock implements iKeyboardShortcut
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-quick-create';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/quick-create/layout';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'base/components/quick-create/layout';
	public const DEFAULT_JS_FILES_REL_PATH = [
		'js/selectize.min.js',
		'js/components/quick-create.js',
	];
	public const DEFAULT_CSS_FILES_REL_PATH = [
		'css/selectize.default.css',
	];

	// Specific constants
	public const DEFAULT_ENDPOINT_REL_URL = 'pages/UI.php';

	/** @var string $sEndpoint Absolute endpoint URL of the creation form */
	protected $sEndpoint;
	/** @var array $aAvailableClasses */
	protected $aAvailableClasses;
	/** @var array $aLastClasses */
	protected $aLastClasses;
	/** @var int $iMaxAutocompleteResults Max. number of elements returned by the autocomplete */
	protected $iMaxAutocompleteResults;
	/** @var bool $bShowHistory Whether or not to display the elements in the history */
	protected $bShowHistory;
	/** @var int $iMaxHistoryResults Max. number of elements in the history */
	protected $iMaxHistoryResults;

	/**
	 * QuickCreate constructor.
	 *
	 * @param array $aLastClasses
	 * @param string|null $sId
	 *
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 */
	public function __construct(array $aLastClasses = [], ?string $sId = null)
	{
		parent::__construct($sId);
		$this->SetEndpoint(static::DEFAULT_ENDPOINT_REL_URL);
		$this->aAvailableClasses = $this->FilterAvailableClasses(UserRights::GetAllowedClasses(UR_ACTION_CREATE, array('bizmodel'), true));
		$this->aLastClasses = $aLastClasses;
		$this->iMaxAutocompleteResults = (int) MetaModel::GetConfig()->Get('quick_create.max_autocomplete_results');
		$this->bShowHistory = (bool) MetaModel::GetConfig()->Get('quick_create.show_history');
		$this->iMaxHistoryResults = (int) MetaModel::GetConfig()->Get('quick_create.max_history_results');
	}

	/**
	 * Set the creation form endpoint URL.
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
	 * Return the absolute URL of the creation form
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function GetEndpoint(): string
	{
		return $this->sEndpoint;
	}

	/**
	 * Return the available classes (to create) for the current user
	 *
	 * @return array
	 */
	public function GetAvailableClasses(): array
	{
		return $this->aAvailableClasses;
	}

	/**
	 * Return the $aClasses array of DM classes minus the classes that should not be proposed in the autocomplete:
	 * - not derivating from cmdbAbstractObject
	 * - n:n classes
	 *
	 * @param array $aClasses
	 *
	 * @return array
	 */
	protected function FilterAvailableClasses(array $aClasses): array
	{
		$aFilteredClasses = [];

		foreach ($aClasses as $sClassName => $sClassLabel){
			// Skip not derivating from cmdbAbstractObject
			if(false === is_a($sClassName, '\cmdbAbstractObject', true)) {
				continue;
			}

			// Skip n:n classes
			if(MetaModel::IsLinkClass($sClassName)) {
				continue;
			}

			$aFilteredClasses[$sClassName] = $sClassLabel;
		}

		return $aFilteredClasses;
	}

	/**
	 * Set all the last classes at once
	 *
	 * @param array $aLastClasses
	 *
	 * @return $this
	 */
	public function SetLastClasses(array $aLastClasses)
	{
		$this->aLastClasses = $aLastClasses;

		return $this;
	}

	/**
	 * Return the last classes (class name, label as HTML, icon URL, ...)
	 *
	 * @return array
	 */
	public function GetLastClasses(): array
	{
		return $this->aLastClasses;
	}

	/**
	 * @see $iMaxAutocompleteResults
	 * @return int
	 */
	public function GetMaxAutocompleteResults(): int
	{
		return $this->iMaxAutocompleteResults;
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
		return [['id' => 'ibo-open-quick-create', 'label' => 'UI:Component:QuickCreate:KeyboardShortcut:OpenDrawer', 'key' => 'c', 'event' => 'open_drawer']];
	}

	public static function GetShortcutTriggeredElementSelector(): string
	{
		return "[data-role='".static::BLOCK_CODE."']";
	}
}