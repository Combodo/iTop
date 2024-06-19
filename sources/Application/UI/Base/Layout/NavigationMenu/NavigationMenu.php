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

namespace Combodo\iTop\Application\UI\Base\Layout\NavigationMenu;

use ApplicationContext;
use ApplicationMenu;
use appUserPreferences;
use CMDBObjectSet;
use Combodo\iTop\Application\Branding;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\NewsroomMenu\NewsroomMenu;
use Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu;
use Combodo\iTop\Application\UI\Base\UIBlock;
use Combodo\iTop\Application\WebPage\CaptureWebPage;
use DBObjectSearch;
use Dict;
use Combodo\iTop\Application\UI\Hook\iKeyboardShortcut;
use MetaModel;
use UIExtKeyWidget;
use UserRights;
use utils;

/**
 * Class NavigationMenu
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Layout\NavigationMenu
 * @internal
 * @since 3.0.0
 */
class NavigationMenu extends UIBlock implements iKeyboardShortcut
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-navigation-menu';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/navigation-menu/layout';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'base/layouts/navigation-menu/layout';
	public const DEFAULT_JS_FILES_REL_PATH = [
		'js/layouts/navigation-menu.js',
		'js/extkeywidget.js',
		'js/forms-json-utils.js',
	];

	// Specific constants
	/** @var bool DEFAULT_SHOW_MENU_FILTER_HINT */
	public const DEFAULT_SHOW_MENU_FILTER_HINT = true;

	/** @var string $sAppRevisionNumber */
	protected $sAppRevisionNumber;
	/** @var string Logo to display when the menu is collapsed */
	protected $sAppSquareIconUrl;
	/** @var string Logo to display when the menu is expanded */
	protected $sAppFullIconUrl;
	/** @var string URL of the link on both $AppXXXIconUrl */
	protected $sAppIconLink;
	/** @var array Data to render the silo selection area */
	protected $aSiloSelection;
	/** @var bool Whether a silo is currently selected or not */
	protected $bHasSiloSelected;
	/** @var string|null Current silo label */
	protected $sSiloLabel;
	/** @var array $aMenuGroups */
	protected $aMenuGroups;
	/** @var array $aUserData */
	protected $aUserData;
	/** @var \Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu $oUserMenu */
	private $oUserMenu;
	/** @var \Combodo\iTop\Application\UI\Base\Component\PopoverMenu\NewsroomMenu\NewsroomMenu $oNewsroomMenu */
	private $oNewsroomMenu;
	/** @var bool $bIsExpanded */
	protected $bIsExpanded;
	/** @var bool Whether the hint on how the menu filter works shoudl be displayed or not */
	protected $bShowMenuFilterHint;
	/** @var bool */
	protected $bShowMenusCount;


	/**
	 * NavigationMenu constructor.
	 *
	 * @param \ApplicationContext $oAppContext
	 * @param \Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu $oUserMenu
	 * @param \Combodo\iTop\Application\UI\Base\Component\PopoverMenu\NewsroomMenu\NewsroomMenu|null $oNewsroomMenu
	 * @param string|null $sId
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \DictExceptionMissingString
	 * @throws \MySQLException
	 * @throws \Exception
	 */
	public function __construct(
		ApplicationContext $oAppContext, PopoverMenu $oUserMenu, NewsroomMenu $oNewsroomMenu = null, ?string $sId = null
	) {
		parent::__construct($sId);

		$oConfig = MetaModel::GetConfig();

		$this->sAppRevisionNumber = utils::GetAppRevisionNumber();
		$this->sAppSquareIconUrl = Branding::GetCompactMainLogoAbsoluteUrl();
		$this->sAppFullIconUrl = Branding::GetFullMainLogoAbsoluteUrl();
		$this->sAppIconLink = $oConfig->Get('app_icon_url');
		$this->SetShowMenusCount($oConfig->Get('navigation_menu.show_menus_count'));
		$this->aSiloSelection = array();
		$this->aMenuGroups = ApplicationMenu::GetMenuGroups($oAppContext->GetAsHash());
		$this->oUserMenu = $oUserMenu;
		$this->oNewsroomMenu = $oNewsroomMenu;

		$this->ComputeAppIconLink();
		$this->ComputeExpandedState();
		$this->ComputeMenuFilterHintState();
		$this->ComputeUserData();
		$this->ComputeSiloSelection();
	}

	/**
	 * @return string
	 */
	public function GetAppRevisionNumber(): string
	{
		return $this->sAppRevisionNumber;
	}

	/**
	 * @uses $sAppSquareIconUrl
	 * @return string
	 */
	public function GetAppSquareIconUrl(): string
	{
		return $this->sAppSquareIconUrl;
	}

	/**
	 * @uses $sAppFullIconUrl
	 * @return string
	 */
	public function GetAppFullIconUrl(): string
	{
		return $this->sAppFullIconUrl;
	}

	/**
	 * @uses $sAppIconLink
	 * @return string
	 */
	public function GetAppIconLink(): string
	{
		return $this->sAppIconLink;
	}

	/**
	 * @return array
	 */
	public function GetSiloSelection()
	{
		return $this->aSiloSelection;
	}

	/**
	 * @uses $bHasSiloSelected
	 * @return bool
	 */
	public function HasSiloSelected(): bool
	{
		return $this->bHasSiloSelected;
	}

	/**
	 * @uses $sSiloLabel
	 * @return string|null
	 */
	public function GetSiloLabel()
	{
		return $this->sSiloLabel;
	}

	/**
	 * @return string The current organization ID of the app. context
	 */
	public function GetOrgId(): string
	{
		$oAppContext = new ApplicationContext();
		$sCurrentOrganization = $oAppContext->GetCurrentValue('org_id');

		if(!empty($sCurrentOrganization)) {
			return $sCurrentOrganization;
		}
		return '';
	}

	/**
	 * @return array
	 */
	public function GetMenuGroups(): array
	{
		return $this->aMenuGroups;
	}

	/**
	 * @return array
	 */
	public function GetUserData(): array
	{
		return $this->aUserData;
	}

	/**
	 * @return \Combodo\iTop\Application\UI\Base\Component\PopoverMenu\PopoverMenu
	 */
	public function GetUserMenu()
	{
		return $this->oUserMenu;
	}

	/**
	 * @return \Combodo\iTop\Application\UI\Base\Component\PopoverMenu\NewsroomMenu\NewsroomMenu
	 */
	public function GetNewsroomMenu()
	{
		return $this->oNewsroomMenu;
	}

	/**
	 * Return true if the menu is expanded
	 *
	 * @return bool
	 */
	public function IsExpanded(): bool
	{
		return $this->bIsExpanded;
	}

	/**
	 * @uses $bShowMenuFilterHint
	 * @return bool
	 */
	public function HasMenuFilterHint(): bool
	{
		return $this->bShowMenuFilterHint;
	}

	/**
	 * @inheritDoc
	 */
	public function GetSubBlocks(): array
	{
		$aSubBlocks = [
			$this->oUserMenu->GetId() => $this->oUserMenu,
		];

		if ($this->IsNewsroomEnabled()) {
			$aSubBlocks[$this->GetNewsroomMenu()->GetId()] = $this->GetNewsroomMenu();
		}

		return $aSubBlocks;
	}

	/**
	 * @return boolean
	 */
	public function IsNewsroomEnabled(): bool
	{
		return MetaModel::GetConfig()->Get('newsroom_enabled');
	}

	/**
	 * @uses $sAppIconLink
	 * @return void
	 */
	public function ComputeAppIconLink(): void
	{
		$sPropCode = 'app_icon_url';

		// Try if a custom URL was set in the configuration file
		if(MetaModel::GetConfig()->IsCustomValue($sPropCode)) {
			$this->sAppIconLink = MetaModel::GetConfig()->Get($sPropCode);
		}
		// Otherwise use the home page
		else {
			$this->sAppIconLink = utils::GetAbsoluteUrlAppRoot();
		}
	}

	/**
	 * @return True if the silo selection is enabled, false otherwise
	 * @since 3.1.0
	 */
	public function IsSiloSelectionEnabled() : bool {
		return MetaModel::GetConfig()->Get('navigation_menu.show_organization_filter');
	}

	/**
	 * @return void
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public function ComputeSiloSelection()
	{
		$this->bHasSiloSelected = false;
		$this->sSiloLabel = null;

		if (! $this->IsSiloSelectionEnabled()){
			return;
		}

		//TODO 3.0 Use components if we have the time to build select/autocomplete components before release
		// List of visible Organizations
		$iCount = 0;
		$oSet = null;
		if (MetaModel::IsValidClass('Organization'))
		{
			// Display the list of *favorite* organizations... but keeping in mind what is the real number of organizations
			$aFavoriteOrgs = appUserPreferences::GetPref('favorite_orgs', null);
			$oSearchFilter = new DBObjectSearch('Organization');
			$oSearchFilter->SetModifierProperty('UserRightsGetSelectFilter', 'bSearchMode', true);
			$oSet = new CMDBObjectSet($oSearchFilter);
			$iCount = $oSet->Count(); // total number of existing Orgs

			// Now get the list of Orgs to be displayed in the menu
			$oSearchFilter = DBObjectSearch::FromOQL(ApplicationMenu::GetFavoriteSiloQuery());
			$oSearchFilter->SetModifierProperty('UserRightsGetSelectFilter', 'bSearchMode', true);
			if (!empty($aFavoriteOrgs))
			{
				$oSearchFilter->AddCondition('id', $aFavoriteOrgs, 'IN');
			}
			$oSet = new CMDBObjectSet($oSearchFilter); // List of favorite orgs
		}
		switch ($iCount)
		{
			case 0:
			case 1:
				// No such dimension/silo or only one possible choice => nothing to select
				break;

			default:
				$oAppContext = new ApplicationContext();
				$iCurrentOrganization = $oAppContext->GetCurrentValue('org_id');

				if(!empty($iCurrentOrganization)) {
					$oCurrentOrganization = MetaModel::GetObject('Organization', $iCurrentOrganization, true, true);
					$this->bHasSiloSelected = true;
					$this->sSiloLabel = $oCurrentOrganization->GetRawName();
				}

				$this->aSiloSelection['html'] = '<form data-role="ibo-navigation-menu--silo-selection--form" action="'.utils::GetAbsoluteUrlAppRoot().'pages/UI.php">'; //<select class="org_combo" name="c[org_id]" title="Pick an organization" onChange="this.form.submit();">';

				$oPage = new CaptureWebPage();

				$oWidget = new UIExtKeyWidget('Organization', 'org_id', '', true /* search mode */);
				$iMaxComboLength = MetaModel::GetConfig()->Get('max_combo_length');
				$this->aSiloSelection['html'] .= $oWidget->DisplaySelect($oPage, $iMaxComboLength, false, Dict::S('UI:Layout:NavigationMenu:Silo:Label'), $oSet, $iCurrentOrganization, false, 'c[org_id]', '',
					array(
						'iFieldSize' => 20,
						'iMinChars' => MetaModel::GetConfig()->Get('min_autocomplete_chars'),
						'sDefaultValue' => Dict::S('UI:AllOrganizations'),
					));
				$this->aSiloSelection['html'] .= $oPage->GetHtml();
				// Add other dimensions/context information to this form
				$oAppContext->Reset('org_id'); // org_id is handled above and we want to be able to change it here !
				$oAppContext->Reset('menu'); // don't pass the menu, since a menu may expect more parameters
				$this->aSiloSelection['html'] .= $oAppContext->GetForForm(); // Pass what remains, if anything...
				$this->aSiloSelection['html'] .= '</form>';

				$sAddClearButton = "";
				if ($this->bHasSiloSelected ){
					$sAddClearButton = "$('#mini_clear_org_id').removeClass('ibo-is-hidden');";
				}

				$sPageJS = $oPage->GetJS();
				$sPageReadyJS = $oPage->GetReadyJS();
				$this->aSiloSelection['js'] =
					<<<JS
$sPageJS
$sPageReadyJS
$('[data-role="ibo-navigation-menu--silo-selection--form"] #org_id').on('extkeychange', function() { $('[data-role="ibo-navigation-menu--silo-selection--form"]').submit(); } )
$('[data-role="ibo-navigation-menu--silo-selection--form"] #label_org_id').on('click', function() { if ($('[data-role="ibo-navigation-menu--silo-selection--form"] #org_id').val() == '') { $(this).val(''); } } );
$sAddClearButton
JS;
		}
	}

	/**
	 * Compute if the menu is expanded or collapsed
	 *
	 * @return $this
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	protected function ComputeExpandedState()
	{
		$bIsExpanded = false;
		// Check if menu should be opened only if we re not in demo mode
		if (false === MetaModel::GetConfig()->Get('demo_mode'))
		{
			// Force state if necessary...
			if (utils::ReadParam('force_menu_pane', null) === 0)
			{
				$bIsExpanded = false;
			}
			// ... otherwise look for the new user pref ...
			elseif (appUserPreferences::GetPref('navigation_menu.expanded', null) !== null)
			{
				$bIsExpanded = appUserPreferences::GetPref('navigation_menu.expanded', null) === 'expanded';
			}
			// ... or fallback on the old one
			elseif (appUserPreferences::GetPref('menu_pane', null) !== null)
			{
				$bIsExpanded = appUserPreferences::GetPref('menu_pane', null) === 'opened';
			}
		}

		$this->bIsExpanded = $bIsExpanded;

		return $this;
	}

	/**
	 * Compute if the menu filter hint should be displayed or not
	 *
	 * @see $bShowMenuFilterHint
	 *
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	protected function ComputeMenuFilterHintState(): void
	{
		$this->bShowMenuFilterHint = (true === appUserPreferences::GetPref('navigation_menu.show_filter_hint', static::DEFAULT_SHOW_MENU_FILTER_HINT));
	}

	/**
	 * Compute the user data displayed in the menu (organization, name, picture, ...)
	 *
	 * @return $this
	 * @throws \Exception
	 */
	protected function ComputeUserData()
	{
		// Use a picture set in the preferences is there is none in the user's contact
		$sPictureUrl = UserRights::GetUserPictureAbsUrl();

		// TODO 3.0.0 : what do we show if no contact is linked to the user ?
		$aData = [
			'sOrganization' => UserRights::GetContactOrganizationFriendlyname(),
			'sFirstname' => UserRights::GetContactFirstname(),
			'sPictureUrl' => $sPictureUrl,
			'sWelcomeMessage' => Dict::Format('UI:Layout:NavigationMenu:UserInfo:WelcomeMessage:Text', UserRights::GetContactFirstname())
		];

		// Logon message
		$sLogonMessageDictCode = (UserRights::IsAdministrator()) ? 'UI:LoggedAsMessage+Admin' : 'UI:LoggedAsMessage';

		$aData['sLogonMessage'] = Dict::Format($sLogonMessageDictCode, UserRights::GetContactFriendlyname(), UserRights::GetUser());

		$this->aUserData = $aData;

		return $this;
	}

	/**
	 * @return string
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public function GetMenuFilterHotkeyLabel(): string
	{
		return utils::GetKeyboardShortcutPref('ibo-open-navigation-menu-filter')['key_for_display'];
	}

	public static function GetShortcutKeys(): array
	{
		return [['id' => 'ibo-open-navigation-menu-filter', 'label' => 'UI:Layout:NavigationMenu:KeyboardShortcut:FocusFilter', 'key' => 'alt+m', 'event' => 'filter_shortcut']];
	}

	public static function GetShortcutTriggeredElementSelector(): string
	{
		return "[data-role='".static::BLOCK_CODE."']";
	}

	/**
	 * @return bool
	 */
	public function GetShowMenusCount(): bool
	{
		return $this->bShowMenusCount;
	}

	/**
	 * @param bool $bShowMenusCount
	 */
	public function SetShowMenusCount(bool $bShowMenusCount): void
	{
		$this->bShowMenusCount = $bShowMenusCount;
	}

}
