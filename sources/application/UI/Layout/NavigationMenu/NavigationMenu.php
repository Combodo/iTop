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

namespace Combodo\iTop\Application\UI\Layout\NavigationMenu;


use ApplicationContext;
use ApplicationMenu;
use appUserPreferences;
use CMDBObjectSet;
use Combodo\iTop\Application\Branding;
use Combodo\iTop\Application\UI\Component\PopoverMenu\NewsroomMenu\NewsroomMenu;
use Combodo\iTop\Application\UI\Component\PopoverMenu\PopoverMenu;
use Combodo\iTop\Application\UI\UIBlock;
use DBObjectSearch;
use Dict;
use MetaModel;
use UIExtKeyWidget;
use UserRights;
use utils;

/**
 * Class NavigationMenu
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Layout\NavigationMenu
 * @internal
 * @since 3.0.0
 */
class NavigationMenu extends UIBlock
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-navigation-menu';
	public const HTML_TEMPLATE_REL_PATH = 'layouts/navigation-menu/layout';
	public const JS_TEMPLATE_REL_PATH = 'layouts/navigation-menu/layout';
	public const JS_FILES_REL_PATH = [
		'js/layouts/navigation-menu.js',
		'js/extkeywidget.js',
		'js/forms-json-utils.js',
	];

	/** @var string $sAppRevisionNumber */
	protected $sAppRevisionNumber;
	/** @var string $sAppSquareIconUrl */
	protected $sAppSquareIconUrl;
	/** @var string $sAppFullIconUrl */
	protected $sAppFullIconUrl;
	/** @var string $sAppIconLink */
	protected $sAppIconLink;
	/** @var array $aSiloSelection */
	protected $aSiloSelection;
	/** @var array $aMenuGroups */
	protected $aMenuGroups;
	/** @var array $aUserData */
	protected $aUserData;
	/** @var \Combodo\iTop\Application\UI\Component\PopoverMenu\PopoverMenu $oUserMenu */
	private $oUserMenu;
	/** @var \Combodo\iTop\Application\UI\Component\PopoverMenu\NewsroomMenu\NewsroomMenu $oNewsroomMenu */
	private $oNewsroomMenu;
	/** @var bool $bIsExpanded */
	protected $bIsExpanded;

	/**
	 * NavigationMenu constructor.
	 *
	 * @param \ApplicationContext $oAppContext
	 * @param \Combodo\iTop\Application\UI\Component\PopoverMenu\PopoverMenu $oUserMenu
	 * @param \Combodo\iTop\Application\UI\Component\PopoverMenu\NewsroomMenu\NewsroomMenu|null $oNewsroomMenu
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

		$this->sAppRevisionNumber = utils::GetAppRevisionNumber();
		$this->sAppSquareIconUrl = Branding::GetCompactMainLogoAbsoluteUrl();
		$this->sAppFullIconUrl = Branding::GetFullMainLogoAbsoluteUrl();
		$this->sAppIconLink = MetaModel::GetConfig()->Get('app_icon_url');
		$this->aSiloSelection = array();
		$this->aMenuGroups = ApplicationMenu::GetMenuGroups($oAppContext->GetAsHash());
		$this->oUserMenu = $oUserMenu;
		$this->oNewsroomMenu = $oNewsroomMenu;

		$this->ComputeExpandedState();
		$this->ComputeUserData();
		$this->ComputeSiloSelection();
	}

	/**
	 * @return string
	 */
	public function GetAppRevisionNumber()
	{
		return $this->sAppRevisionNumber;
	}

	/**
	 * @return string
	 */
	public function GetAppSquareIconUrl()
	{
		return $this->sAppSquareIconUrl;
	}

	/**
	 * @return string
	 */
	public function GetAppFullIconUrl()
	{
		return $this->sAppFullIconUrl;
	}

	/**
	 * @return string
	 */
	public function GetAppIconLink()
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
	 * @return array
	 */
	public function GetMenuGroups()
	{
		return $this->aMenuGroups;
	}

	/**
	 * @return array
	 */
	public function GetUserData()
	{
		return $this->aUserData;
	}

	/**
	 * @return \Combodo\iTop\Application\UI\Component\PopoverMenu\PopoverMenu
	 */
	public function GetUserMenu()
	{
		return $this->oUserMenu;
	}
	/**
	 * @return \Combodo\iTop\Application\UI\Component\PopoverMenu\NewsroomMenu\NewsroomMenu
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
	public function IsExpanded()
	{
		return $this->bIsExpanded;
	}

	/**
	 * @inheritDoc
	 */
	public function GetSubBlocks()
	{
		return [$this->oUserMenu->GetId() => $this->oUserMenu, $this->GetNewsroomMenu()->GetId() => $this->GetNewsroomMenu()];
	}

	/**
	 * @return boolean
	 */
	public function IsNewsroomEnabled()
	{
		return MetaModel::GetConfig()->Get('newsroom_enabled');
	}

	/**
	 * @return void
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public function ComputeSiloSelection()
	{
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
				$this->aSiloSelection['html'] = '<form data-role="ibo-navigation-menu--silo-selection--form" action="'.utils::GetAbsoluteUrlAppRoot().'pages/UI.php">'; //<select class="org_combo" name="c[org_id]" title="Pick an organization" onChange="this.form.submit();">';

				$oPage = new \CaptureWebPage();
				
				$oWidget = new UIExtKeyWidget('Organization', 'org_id', '', true /* search mode */);
				$iMaxComboLength = MetaModel::GetConfig()->Get('max_combo_length');
				$this->aSiloSelection['html'] .= $oWidget->DisplaySelect($oPage, $iMaxComboLength, false, '', $oSet, $iCurrentOrganization, false, 'c[org_id]', '',
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

				$sPageJS = $oPage->GetJS();
				$sPageReadyJS = $oPage->GetReadyJS();
				$this->aSiloSelection['js'] =
					<<<JS
$sPageJS
$sPageReadyJS
$('[data-role="ibo-navigation-menu--silo-selection--form"] #org_id').bind('extkeychange', function() { $('[data-role="ibo-navigation-menu--silo-selection--form"]').submit(); } )
$('[data-role="ibo-navigation-menu--silo-selection--form"] #label_org_id').click( function() { if ($('[data-role="ibo-navigation-menu--silo-selection--form"] #org_id').val() == '') { $(this).val(''); } } );
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
			if (utils::ReadParam('force_menu_pane', null) === 0)
			{
				$bIsExpanded = false;
			}
			elseif (appUserPreferences::GetPref('menu_pane', 'closed') === 'opened')
			{
				$bIsExpanded = true;
			}
		}

		$this->bIsExpanded = $bIsExpanded;

		return $this;
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
		$sPictureUrl = UserRights::GetContactPictureAbsUrl('', false);
		if(empty($sPictureUrl))
		{
			$sPictureUrl = utils::GetAbsoluteUrlAppRoot().'images/user-pictures/' . appUserPreferences::GetPref('user_picture_placeholder', 'user-profile-default-256px.png');
		}

		// TODO 3.0.0 : what do we show if no contact is linked to the user ?
		$aData = [
			'sOrganization' => UserRights::GetContactOrganizationFriendlyname(),
			'sFirstname' => UserRights::GetContactFirstname(),
			'sPictureUrl' => $sPictureUrl,
			'sWelcomeMessage' => Dict::Format('UI:Layout:NavigationMenu:UserInfo:WelcomeMessage:Text', UserRights::GetContactFirstname())
		];

		// Logon message
		$sLogonMessageDictCode = (UserRights::IsAdministrator()) ? 'UI:LoggedAsMessage+Admin' : 'UI:LoggedAsMessage';
		
		$aData['sLogonMessage'] = Dict::Format($sLogonMessageDictCode, UserRights::GetContactFriendlyname());

		$this->aUserData = $aData;

		return $this;
	}
	
}