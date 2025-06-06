<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Application\Newsroom\iTopNewsroomProvider;
use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\FieldSet\FieldSetUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Form\Form;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectOptionUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Panel\Panel;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Title\TitleUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Toolbar\ToolbarUIBlockFactory;
use Combodo\iTop\Application\UI\Base\iUIBlock;
use Combodo\iTop\Application\UI\Base\Layout\MultiColumn\Column\Column;
use Combodo\iTop\Application\UI\Base\Layout\MultiColumn\MultiColumn;
use Combodo\iTop\Application\UI\Base\Layout\PageContent\PageContentFactory;
use Combodo\iTop\Application\UI\Preferences\BlockShortcuts\BlockShortcuts;
use Combodo\iTop\Application\WebPage\ErrorPage;
use Combodo\iTop\Application\WebPage\iTopWebPage;
use Combodo\iTop\Application\WebPage\WebPage;
use Combodo\iTop\Controller\Notifications\NotificationsCenterController;
use Combodo\iTop\Service\InterfaceDiscovery\InterfaceDiscovery;
use Combodo\iTop\Service\Router\Router;

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/startup.inc.php');
IssueLog::Trace('----- Request: '.utils::GetRequestUri(), LogChannels::WEB_REQUEST);

/**
 * Displays the user's changeable preferences
 * @param $oP WebPage The web page used for the output
 */
function DisplayPreferences($oP)
{
	$oContentLayout = PageContentFactory::MakeStandardEmpty();
	$oAppContext = new ApplicationContext();
	$sURL = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?'.$oAppContext->GetForLink();

	$oContentLayout->AddMainBlock(TitleUIBlockFactory::MakeForPage(Dict::S('UI:Preferences:Title')));

	//////////////////////////////////////////////////////////////////////////
	//
	// User interface
	//
	//////////////////////////////////////////////////////////////////////////
	// Create panel
	$oUIPanel = PanelUIBlockFactory::MakeNeutral(Dict::S('UI:Preferences:UserInterface:Title'));
	$oContentLayout->AddMainBlock($oUIPanel);

	// Create form
	$oUIForm = new Form('ibo-form-for-user-interface-preferences');
	$oUIPanel->AddSubBlock($oUIForm);

	// Prepare form
	$oUIForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('operation', 'apply_user_interface'))
		->AddSubBlock($oAppContext->GetForFormBlock())
		->SetOnSubmitJsCode('return ValidateOtherSettings();');

	$oMultiColContainer = new MultiColumn();
	$oUIForm->AddSubBlock($oMultiColContainer);

	$oFirstColumn = new Column();
	$oMultiColContainer->AddColumn($oFirstColumn);

	$oSecondColumn = new Column();
	$oMultiColContainer->AddColumn($oSecondColumn);

	// Prepare buttons
	$oUIToolbar = ToolbarUIBlockFactory::MakeForButton(null, ['ibo-is-fullwidth']);
	$oUIForm->AddSubBlock($oUIToolbar);

	// - Cancel button
	$oUICancelButton = ButtonUIBlockFactory::MakeForCancel();
	$oUIToolbar->AddSubBlock($oUICancelButton);
	$oUICancelButton->SetOnClickJsCode("window.location.href = '$sURL'");

	// - Submit button
	$oUISubmitButton = ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('UI:Button:Apply'), 'operation', 'apply_user_interface', true);
	$oUIToolbar->AddSubBlock($oUISubmitButton);

	// General
	$oGeneralFieldset = FieldSetUIBlockFactory::MakeStandard(Dict::S('UI:Preferences:General:Title'), 'ibo-fieldset-for-language-preferences');
	$oGeneralFieldset->AddSubBlock(GetLanguageFieldBlock());
	if (true === MetaModel::GetConfig()->Get('user_preferences.allow_backoffice_theme_override')) {
		$oGeneralFieldset->AddSubBlock(GetThemeFieldBlock());
	}
	$oFirstColumn->AddSubBlock($oGeneralFieldset);

	// Lists
	$oListsFieldset = FieldSetUIBlockFactory::MakeStandard(Dict::S('UI:Preferences:Lists:Title'), 'ibo-fieldset-for-lists-preferences');
	$oFirstColumn->AddSubBlock($oListsFieldset);
	$oListsFieldset->AddSubBlock(GetListPageSizeFieldBlock());

	// Tabs
	$oTabsFieldset = FieldSetUIBlockFactory::MakeStandard(Dict::S('UI:Preferences:Tabs:Title'), 'ibo-fieldset-for-tabs-preferences');
	$oFirstColumn->AddSubBlock($oTabsFieldset);
	$oTabsFieldset->AddSubBlock(GetTabsLayoutFieldBlock());
	$oTabsFieldset->AddSubBlock(GetTabsNavigationFieldBlock());

	// Activity panel
	$oActivityPanelfieldset = FieldSetUIBlockFactory::MakeStandard(Dict::S('UI:Preferences:ActivityPanel:Title'), 'ibo-fieldset-for-activity-panel');
	$oSecondColumn->AddSubBlock($oActivityPanelfieldset);
	$oActivityPanelfieldset->AddSubBlock(GetActivityPanelEntryFormOpenedFieldBlock());

	// Misc. options
	$oMiscOptionsFieldset = FieldSetUIBlockFactory::MakeStandard(Dict::S('UI:FavoriteOtherSettings'), 'ibo-fieldset-for-misc-options');
	$oSecondColumn->AddSubBlock($oMiscOptionsFieldset);
	$oMiscOptionsFieldset->AddSubBlock(GetObsoleteDataFieldBlock());
	$oMiscOptionsFieldset->AddSubBlock(GetSummaryCardsFieldBlock());
	$oMiscOptionsFieldset->AddSubBlock(GetToastsPositionFieldBlock());

	$oP->add_script(
		<<<JS
function ValidateOtherSettings()
{
	var sPageLength = $('#default_page_size').val();
	var iPageLength = parseInt(sPageLength , 10);
	if (/^[0-9]+$/.test(sPageLength) && (iPageLength > 0))
	{
		$('#v_default_page_size').html('');
		$('#ibo-misc-settings-submit').prop('disabled', false);
		return true;
	}
	else
	{
		$('#v_default_page_size').html('<img src="' + GetAbsoluteUrlAppRoot() + 'images/validation_error.png"/>');
		$('#ibo-misc-settings-submit').prop('disabled', true);
		return false;
	}
}
JS
	);

	//////////////////////////////////////////////////////////////////////////
	//
	// Notifications
	//
	//////////////////////////////////////////////////////////////////////////
	$oNotificationsBlock = new Panel(Dict::S('UI:Preferences:Notifications'), array(), Panel::ENUM_COLOR_SCHEME_GREY, 'ibo-notifications');
	$sNotificationsCenterUrl = Router::GetInstance()->GenerateUrl(NotificationsCenterController::ROUTE_NAMESPACE.'.display_page', [], true);
	$oNotificationsBlock->AddSubBlock(new Html('<p>'.Dict::Format('UI:Preferences:Notifications+', $sNotificationsCenterUrl).'</p>'));
	$oContentLayout->AddMainBlock($oNotificationsBlock);


	//////////////////////////////////////////////////////////////////////////
	//
	// Favorite Organizations
	//
	//////////////////////////////////////////////////////////////////////////

	$oFavoriteOrganizationsBlock = new Panel(Dict::S('UI:FavoriteOrganizations'), array(), 'grey', 'ibo-favorite-organizations');
	$oFavoriteOrganizationsBlock->SetSubTitle(Dict::S('UI:FavoriteOrganizations+'));
	$oFavoriteOrganizationsBlock->AddCSSClass('ibo-datatable-panel');
	$oFavoriteOrganizationsForm = new Form();
	$oFavoriteOrganizationsBlock->AddSubBlock($oFavoriteOrganizationsForm);
	// Favorite organizations: the organizations listed in the drop-down menu
	$sOQL = ApplicationMenu::GetFavoriteSiloQuery();
	$oFilter = DBObjectSearch::FromOQL($sOQL);
	$oBlock = new DisplayBlock($oFilter, 'list', false);

	$aFavoriteOrgs = appUserPreferences::GetPref('favorite_orgs', null);

	$sIdFavoriteOrganizations = 1;
	$oFavoriteOrganizationsForm->AddSubBlock($oBlock->GetDisplay($oP, $sIdFavoriteOrganizations, [
		'menu'                => false,
		'selection_mode'      => true,
		'selection_type'      => 'multiple',
		'table_id'            => 'user_prefs',
		'surround_with_panel' => false,
		'selected_rows'       => $aFavoriteOrgs,
	]));
	$oFavoriteOrganizationsForm->AddSubBlock($oAppContext->GetForFormBlock());

	// Button toolbar
	$oFavoriteOrganizationsToolBar = ToolbarUIBlockFactory::MakeForButton(null, ['ibo-is-fullwidth']);
	$oFavoriteOrganizationsForm->AddSubBlock($oFavoriteOrganizationsToolBar);

	// - Cancel button
	$oFavoriteOrganizationsCancelButton = ButtonUIBlockFactory::MakeForCancel(Dict::S('UI:Button:Cancel'));
	$oFavoriteOrganizationsToolBar->AddSubBlock($oFavoriteOrganizationsCancelButton);
	$oFavoriteOrganizationsCancelButton->SetOnClickJsCode("window.location.href = '$sURL'");
	// - Submit button
	$oFavoriteOrganizationsSubmitButton = ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('UI:Button:Apply'), 'operation', 'apply', true);
	$oFavoriteOrganizationsToolBar->AddSubBlock($oFavoriteOrganizationsSubmitButton);

	// TODO 3.0 have this code work again, currently it prevents the display of favorite organizations and shortcuts.
	//	if ($aFavoriteOrgs == null) {
	//		// All checked
	//		$oP->add_ready_script(
	//			<<<JS
	//	$('#$sIdFavoriteOrganizations.checkAll').prop('checked', true);
	//	checkAllDataTable('datatable_$sIdFavoriteOrganizations',true,'$sIdFavoriteOrganizations');
	//JS
	//		);
	//
	//	}

	$oContentLayout->AddMainBlock($oFavoriteOrganizationsBlock);

	//////////////////////////////////////////////////////////////////////////
	//
	// Shortcuts
	//
	//////////////////////////////////////////////////////////////////////////

	$oShortcutsBlock = new BlockShortcuts(Dict::S('Menu:MyShortcuts'), array(), 'grey', 'ibo-shortcuts');
	$oShortcutsBlock->AddCSSClass('ibo-datatable-panel');

	$oShortcutsBlock->sIdShortcuts = 'shortcut_list';
	$oShortcutsFilter = new DBObjectSearch('Shortcut');
	$oShortcutsFilter->AddCondition('user_id', UserRights::GetUserId(), '=');

	$oBlock = new DisplayBlock($oShortcutsFilter, 'list', false);
	$oShortcutsBlock->AddSubBlock($oBlock->GetDisplay($oP, $oShortcutsBlock->sIdShortcuts, [
		'view_link'           => false,
		'menu'                => false,
		'toolkit_menu'        => false,
		'selection_mode'      => true,
		'selection_type'      => 'multiple',
		'table_id'            => 'user_prefs_shortcuts',
		'surround_with_panel' => false,
		'id_for_select'       => 'Shortcut/_key_',
	]));

	$oSet = new DBObjectSet($oShortcutsFilter);
	if ($oSet->Count() > 0) {
		$oShortcutsToolBar = ToolbarUIBlockFactory::MakeForButton();
		$oShortcutsBlock->AddSubBlock($oShortcutsToolBar);
		// - Rename button
		$oShortcutsRenameButton = ButtonUIBlockFactory::MakeForSecondaryAction(Dict::S('UI:Button:Rename'), null, null, false,
			"shortcut_btn_rename");
		$oShortcutsToolBar->AddSubBlock($oShortcutsRenameButton);
		// - Delete button
		$oShortcutsDeleteButton = ButtonUIBlockFactory::MakeForSecondaryAction(Dict::S('UI:Button:Delete'), null, null, false,
			"shortcut_btn_delete");
		$oShortcutsToolBar->AddSubBlock($oShortcutsDeleteButton);
	}
	$oContentLayout->AddMainBlock($oShortcutsBlock);

	//////////////////////////////////////////////////////////////////////////
	//
	// Newsroom
	//
	//////////////////////////////////////////////////////////////////////////
	$iCountProviders = 0;
	$oUser = UserRights::GetUserObject();
	/** @var iNewsroomProvider[] $aProviders */
	$aProviders = InterfaceDiscovery::GetInstance()->FindItopClasses(iNewsroomProvider::class);
	foreach($aProviders as $cProvider) 
	{
		$oProvider = new $cProvider();
		if ($oProvider->IsApplicable($oUser))
		{
			$iCountProviders++;
		}
	}

	$bNewsroomEnabled = (MetaModel::GetConfig()->Get('newsroom_enabled') !== false);
	if ($bNewsroomEnabled && ($iCountProviders > 0))
	{
		$oNewsroomBlock = new Panel(Dict::S('UI:Newsroom:Preferences'), array(), 'grey', 'ibo-newsroom');

		$sNewsroomHtml = '';
		$sNewsroomHtml .= '<form method="post">';
		$iNewsroomDisplaySize = (int)appUserPreferences::GetPref('newsroom_display_size', 7);

		if ($iNewsroomDisplaySize < 1) $iNewsroomDisplaySize = 1;
		if ($iNewsroomDisplaySize > 20) $iNewsroomDisplaySize = 20;
		$sInput = '<input min="1" max="20" id="newsroom_display_size" type="number" size="2" name="newsroom_display_size" value="'.$iNewsroomDisplaySize.'">';
		$sIcon = '<i id="newsroom_menu_icon" class="top-right-icon icon-additional-arrow fas fa-bell" style="top: 0;"></i>';
		$sNewsroomHtml .= Dict::Format('UI:Newsroom:DisplayAtMost_X_Messages', $sInput, $sIcon);

		/**
		 * @var iNewsroomProvider[] $aProviders
		 */
		$sAppRootUrl = utils::GetAbsoluteUrlAppRoot();
		foreach($aProviders as $cProvider) 
		{
			$oProvider = new $cProvider();
			if ($oProvider->IsApplicable($oUser))
			{
				$sUrl = $oProvider->GetPreferencesUrl();
				$sProviderClass = get_class($oProvider);
				$sPreferencesLink = '';
				if ($sUrl !== null)
				{
					if(substr($sUrl, 0, strlen($sAppRootUrl)) === $sAppRootUrl)
					{
						$sTarget = ''; // Internal link, open in the same window
					}
					else
					{
						$sTarget = ' target="_blank"'; // External link, open in new window
					}
					$sPreferencesLink = ' - <a class=".newsroom-configuration-link" href="'.$sUrl.'"'.$sTarget.'>'.Dict::S('UI:Newsroom:ConfigurationLink').'</a>';
				}

				$sCheckedForHtml = appUserPreferences::GetPref('newsroom_provider_'.$sProviderClass, true) ? 'checked' : '';
				// Forbid disabling internal newsroom provider
				$sDisabledForHtml = $sProviderClass === iTopNewsroomProvider::class ? 'disabled' : '';

				$sNewsroomHtml .= '<div><input type="checkbox" id="newsroom_provider_'.$sProviderClass.'" value="on" '.$sCheckedForHtml.' '.$sDisabledForHtml.' name="newsroom_provider_'.$sProviderClass.'"><label for="newsroom_provider_'.$sProviderClass.'">'.Dict::Format('UI:Newsroom:DisplayMessagesFor_Provider',
						$oProvider->GetLabel()).'</label> '.$sPreferencesLink.'</div>';
			}
		}

		$sNewsroomHtml .= $oAppContext->GetForForm();

		$oNewsroomToolbar = ToolbarUIBlockFactory::MakeForButton();

		// - Reset button
		$oNewsroomResetCacheButton = ButtonUIBlockFactory::MakeForAlternativeDestructiveAction(Dict::S('UI:Newsroom:ResetCache'));
		$oNewsroomResetCacheButton->SetOnClickJsCode(<<<JS
$('#ibo-navigation-menu--notifications-menu').newsroom_menu('clearCache')
CombodoToast.OpenSuccessToast(Dict.S('UI:Newsroom:ResetCache:Success:Message'));
JS
		);
		$oNewsroomToolbar->AddSubBlock($oNewsroomResetCacheButton);
		// - Cancel button
		$oNewsroomCancelButton = ButtonUIBlockFactory::MakeForCancel(Dict::S('UI:Button:Cancel'));
		$oNewsroomCancelButton->SetOnClickJsCode("window.location.href = '$sURL'");
		$oNewsroomToolbar->AddSubBlock($oNewsroomCancelButton);
		// - Submit button
		$oNewsroomSubmitButton = ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('UI:Button:Apply'), 'operation',
			'apply_newsroom_preferences', true);
		$oNewsroomToolbar->AddSubBlock($oNewsroomSubmitButton);


		$sNewsroomEndHtml = '</form>';
		$oNewsroomEndHtmlBlock = new Html($sNewsroomEndHtml);

		$oNewsroomHtmlBlock = new Html($sNewsroomHtml);
		$oNewsroomBlock->AddSubBlock($oNewsroomHtmlBlock);
		$oNewsroomBlock->AddSubBlock($oNewsroomToolbar);
		$oNewsroomBlock->AddSubBlock($oNewsroomEndHtmlBlock);
		$oContentLayout->AddMainBlock($oNewsroomBlock);
	}

	//////////////////////////////////////////////////////////////////////////
	//
	// User defined keyboard shortcut
	//
	//////////////////////////////////////////////////////////////////////////

	// Panel
	$oKeyboardShortcutBlock = new Panel(Dict::S('UI:Preferences:PersonalizeKeyboardShortcuts:Title'), array(), 'grey', 'ibo_keyboard_shortcuts');
	// Form
	$oKeyboardShortcutForm = new Form('ibo-form-for-user-interface-preferences');
	$oKeyboardShortcutForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('operation', 'apply_keyboard_shortcuts'))
		->AddSubBlock($oAppContext->GetForFormBlock());

	$oKeyboardShortcutBlock->AddSubBlock($oKeyboardShortcutForm);

	$sKeyboardShortcutBlockId = $oKeyboardShortcutBlock->GetId();
	// JS keyboard listener
	$oP->add_script(
		<<<JS
    function recordSequence$sKeyboardShortcutBlockId(fCallback) {
         Mousetrap.record(function(sequence) {
            fCallback(sequence.join(' '));
        });
    }
JS
	);
	// For each existing shortcut keyboard existing in iTop
	$aKeyboardShortcuts = utils::GetAllKeyboardShortcutsPrefs();
	$sKeyboardShortcutsInputHint = Dict::S('UI:Preferences:PersonalizeKeyboardShortcuts:Input:Hint');
	$sKeyboardShortcutsButtonTooltip = Dict::S('UI:Preferences:PersonalizeKeyboardShortcuts:Button:Tooltip');
	foreach ($aKeyboardShortcuts as $sKeyboardShortcutId => $aKeyboardShortcut) {
		// Recording button
		$oButton = ButtonUIBlockFactory::MakeForAlternativeSecondaryAction('');
		$oButton->SetIconClass('fas fa-pen')->SetTooltip($sKeyboardShortcutsButtonTooltip)->SetOnClickJsCode(
			<<<JS
let oPanel = $(this).siblings('input');
var fCallback = function(sVal){
	oPanel.removeClass('ibo-is-focus').val(sVal);
}
oPanel.addClass('ibo-is-focus').val('$sKeyboardShortcutsInputHint')
recordSequence$sKeyboardShortcutBlockId(fCallback);
JS
		);

		$oInput = InputUIBlockFactory::MakeForInputWithLabel(Dict::S($aKeyboardShortcut['label']), $sKeyboardShortcutId, $aKeyboardShortcut['key'], $sKeyboardShortcutId, 'text');
		$oInput->GetInput()->AddCSSClasses(['ibo-keyboard-shortcut--input']);
		$oKeyboardShortcutForm->AddSubBlock(new Html('<div class="ibo-keyboard-shortcut--shortcut">'));
		$oKeyboardShortcutForm->AddSubBlock($oInput);
		$oKeyboardShortcutForm->AddSubBlock($oButton);
		$oKeyboardShortcutForm->AddSubBlock(new Html('</div>'));
	}

	// Prepare buttons
	$oKeyboardShortcutToolbar = ToolbarUIBlockFactory::MakeForButton(null, ['ibo-is-fullwidth']);
	$oKeyboardShortcutForm->AddSubBlock($oKeyboardShortcutToolbar);

	// - Cancel button
	$oKeyboardShortcutCancelButton = ButtonUIBlockFactory::MakeForCancel();
	$oKeyboardShortcutToolbar->AddSubBlock($oKeyboardShortcutCancelButton);
	$oKeyboardShortcutCancelButton->SetOnClickJsCode("window.location.href = '$sURL'");

	// - Reset button
	$oKeyboardShortcutResetButton = ButtonUIBlockFactory::MakeForSecondaryAction(Dict::S('UI:Preferences:PersonalizeKeyboardShortcuts:Button:Reset'), 'operation', 'reset_keyboard_shortcuts', true);
	$oKeyboardShortcutResetButton->SetTooltip(Dict::S('UI:Preferences:PersonalizeKeyboardShortcuts:Button:Reset:Tooltip'));
	$oKeyboardShortcutToolbar->AddSubBlock($oKeyboardShortcutResetButton);
	// - Submit button
	$oKeyboardShortcutSubmitButton = ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('UI:Button:Apply'), 'operation', 'apply_keyboard_shortcuts', true);
	$oKeyboardShortcutToolbar->AddSubBlock($oKeyboardShortcutSubmitButton);


	$oContentLayout->AddMainBlock($oKeyboardShortcutBlock);

	//////////////////////////////////////////////////////////////////////////
	//
	// User picture placeholder
	//
	//////////////////////////////////////////////////////////////////////////

	$oUserPicturePlaceHolderBlock = new Panel(Dict::S('UI:Preferences:ChooseAPlaceholder'), array(), 'grey', 'ibo-user-picture-placeholder');

	$sUserPicturesFolderRelPath = 'images/user-pictures/';
	$sUserPicturesFolderAbsPath = APPROOT . $sUserPicturesFolderRelPath;
	$sUserPicturesFolderAbsUrl = utils::GetAbsoluteUrlAppRoot() . $sUserPicturesFolderRelPath;
	$sUserDefaultPicture = appUserPreferences::GetPref('user_picture_placeholder', 'default-placeholder.png');
	$sUserPicturePlaceHolderHtml = '';
	$sUserPicturePlaceHolderHtml .= '<p>'.Dict::S('UI:Preferences:ChooseAPlaceholder+').'</p> <div class="ibo-preferences--user-preferences--picture-placeholder">';
	foreach (scandir($sUserPicturesFolderAbsPath) as $sUserPicture)
	{
		if ($sUserPicture === '.' || $sUserPicture === '..')
		{
			continue;
		}
		$sAdditionalClass = '';
		if ($sUserDefaultPicture === $sUserPicture)
		{
			$sAdditionalClass = ' ibo-is-active';
		}
		$sUserPicturePlaceHolderHtml .= '<a class="ibo-preferences--user-preferences--picture-placeholder--image'.$sAdditionalClass.'" data-image-name="'.$sUserPicture.'" data-role="ibo-preferences--user-preferences--picture-placeholder--image" href="#"> <img src="'.$sUserPicturesFolderAbsUrl.$sUserPicture.'"/> </a>';
	}
	$sUserPictureChangedSuccessMessage = Dict::S('UI:Preferences:ChooseAPlaceholder:Success:Message');
	$oP->add_ready_script(
		<<<JS
$('[data-role="ibo-preferences--user-preferences--picture-placeholder--image"]').on('click',function(){
	const me = this;
	
	// Save new preference
	$.post(
		GetAbsoluteUrlAppRoot()+'pages/ajax.render.php',
		{
			'operation': 'preferences.set_user_picture',
			'image_filename': $(this).attr('data-image-name')
		}
	)
	.done(function(oData){
		if(false === oData.success){
			return;
		}
		
		// Update selection
		$('[data-role="ibo-preferences--user-preferences--picture-placeholder--image"]').removeClass('ibo-is-active');
		$(me).addClass('ibo-is-active');
		
		// Update navigation menu
		$('[data-role="ibo-navigation-menu--user-picture--image"]').attr('src', oData.data.image_url);
		
		// Display success message
		CombodoToast.OpenSuccessToast('{$sUserPictureChangedSuccessMessage}');
	});
});
JS
);
	$sUserPicturePlaceHolderHtml .=
		<<<HTML
</div>
HTML
	;
	$oUserPicturePlaceHolderHtmlBlock = new Html($sUserPicturePlaceHolderHtml);
	$oUserPicturePlaceHolderBlock->AddSubBlock($oUserPicturePlaceHolderHtmlBlock);
	$oContentLayout->AddMainBlock($oUserPicturePlaceHolderBlock);

	/** @var iPreferencesExtension $oLoginExtensionInstance */
	foreach (MetaModel::EnumPlugins('iPreferencesExtension') as $oPreferencesExtensionInstance)
	{
		$oPreferencesExtensionInstance->DisplayPreferences($oP);
	}

	//////////////////////////////////////////////////////////////////////////
	//
	// Footer
	//
	$oP->SetContentLayout($oContentLayout);
}

/**
 * @return \Combodo\iTop\Application\UI\Base\iUIBlock
 * @since 3.0.0
 */
function GetLanguageFieldBlock(): iUIBlock
{
	$aAvailableLanguages = Dict::GetLanguages();
	$aSortedLanguages = array();
	foreach ($aAvailableLanguages as $sCode => $aLang) {
		if (MetaModel::GetConfig()->Get('demo_mode') && ($sCode !== Dict::GetUserLanguage())) {
			// Demo mode: only the current user language is listed in the available choices
			continue;
		}
		$aSortedLanguages[$aLang['description']] = $sCode;
	}
	ksort($aSortedLanguages);

	$oSelect = SelectUIBlockFactory::MakeForSelectWithLabel('language', Dict::S('UI:FavoriteLanguage'));
	/** @var \Combodo\iTop\Application\UI\Base\Component\Input\Select\Select $oSelectInput */
	foreach ($aSortedLanguages as $sCode) {
		$bSelected = ($sCode === Dict::GetUserLanguage());
		$oSelect->AddSubBlock(SelectOptionUIBlockFactory::MakeForSelectOption($sCode, $aAvailableLanguages[$sCode]['description'].' ('.$aAvailableLanguages[$sCode]['localized_description'].')', $bSelected));
	}

	return $oSelect;
}

/**
 * @return \Combodo\iTop\Application\UI\Base\iUIBlock
 * @since 3.0.0
 */
function GetThemeFieldBlock(): iUIBlock
{
	$aAvailableThemes = ThemeHandler::GetAvailableThemes();

	$oSelect = SelectUIBlockFactory::MakeForSelectWithLabel('theme', Dict::S('UI:Preferences:General:Theme'));
	foreach ($aAvailableThemes as $sCode => $sLabel) {
		if (MetaModel::GetConfig()->Get('demo_mode') && ($sCode !== ThemeHandler::GetApplicationThemeId())) {
			// Demo mode: only the current app. theme is listed in the available choices
			continue;
		}

		$bSelected = ($sCode === ThemeHandler::GetCurrentUserThemeId());
		if ($sCode === MetaModel::GetConfig()->Get('backoffice_default_theme')) {
			$sLabel = Dict::Format('UI:Preferences:General:Theme:DefaultThemeLabel', $sLabel);
		}
		$oSelect->AddSubBlock(SelectOptionUIBlockFactory::MakeForSelectOption($sCode, $sLabel, $bSelected));
	}

	return $oSelect;
}

/**
 * @return \Combodo\iTop\Application\UI\Base\iUIBlock
 * @throws \CoreException
 * @throws \CoreUnexpectedValue
 * @throws \MySQLException
 * @since 3.0.0
 */
function GetListPageSizeFieldBlock(): iUIBlock
{
	$iDefaultPageSize = (int)appUserPreferences::GetPref('default_page_size', MetaModel::GetConfig()->GetMinDisplayLimit());

	$sInputHtml = '<input id="default_page_size" name="default_page_size" type="text" size="3" value="'.$iDefaultPageSize.'"/><span id="v_default_page_size"></span>';
	$sHtml = '<p>'.Dict::Format('UI:Favorites:Default_X_ItemsPerPage', $sInputHtml).'</p>';

	return new Html($sHtml);
}

/**
 * @return \Combodo\iTop\Application\UI\Base\iUIBlock
 * @return 3.0.0
 * @throws \CoreUnexpectedValue
 * @throws \MySQLException
 * @throws \CoreException
 */
function GetTabsLayoutFieldBlock(): iUIBlock
{
	$sCurrentValue = appUserPreferences::GetPref('tab_layout', false);

	$aOptionsValues = [
		'horizontal',
		'vertical',
	];
	$oSelect = SelectUIBlockFactory::MakeForSelectWithLabel('tab_layout', Dict::S('UI:Preferences:Tabs:Layout:Label'));
	foreach ($aOptionsValues as $sValue) {
		$oSelect->AddSubBlock(SelectOptionUIBlockFactory::MakeForSelectOption(
			$sValue,
			Dict::S('UI:Preferences:Tabs:Layout:'.ucfirst($sValue)),
			$sValue === $sCurrentValue)
		);
	}

	return $oSelect;
}

/**
 * @return \Combodo\iTop\Application\UI\Base\iUIBlock
 * @throws \CoreException
 * @throws \CoreUnexpectedValue
 * @throws \MySQLException
 * @since 3.0.0
 */
function GetTabsNavigationFieldBlock(): iUIBlock
{
	$bCurrentValue = appUserPreferences::GetPref('tab_scrollable', false);
	$sCurrentValueAsString = $bCurrentValue ? 'true' : 'false';

	$aOptionsValues = [
		'true' => 'Scrollable',
		'false' => 'Classic',
	];
	$oSelect = SelectUIBlockFactory::MakeForSelectWithLabel('tab_scrollable', Dict::S('UI:Preferences:Tabs:Scrollable:Label'));
	foreach ($aOptionsValues as $sValue => $sDictEntrySuffix) {
		$oSelect->AddSubBlock(SelectOptionUIBlockFactory::MakeForSelectOption(
			$sValue,
			Dict::S('UI:Preferences:Tabs:Scrollable:'.$sDictEntrySuffix),
			$sValue === $sCurrentValueAsString)
		);
	}

	return $oSelect;
}

/**
 * @return \Combodo\iTop\Application\UI\Base\iUIBlock
 * @throws \CoreException
 * @throws \CoreUnexpectedValue
 * @throws \MySQLException
 * @since 3.0.0
 */
function GetActivityPanelEntryFormOpenedFieldBlock(): iUIBlock
{
	// First check if user has a pref.
	$bOpened = appUserPreferences::GetPref('activity_panel.is_entry_form_opened', null);
	if (null === $bOpened) {
		// Otherwise get the default config. param.
		$bOpened = MetaModel::GetConfig()->Get('activity_panel.entry_form_opened_by_default');
	}
	$sCheckedForHtmlAttribute = $bOpened ? 'checked="checked"' : '';

	$sLabel = Dict::S('UI:Preferences:ActivityPanel:EntryFormOpened');
	$sLabelDescription = Dict::S('UI:Preferences:ActivityPanel:EntryFormOpened+');
	$sHtml = <<<HTML
<p>
	<label data-tooltip-content="{$sLabelDescription}">
		<span>{$sLabel}</span>
		<input type="checkbox" name="activity_panel_entry_form_opened" value="1" {$sCheckedForHtmlAttribute}>
	</label>
</p>
HTML;

	return new Html($sHtml);
}

/**
 * @return \Combodo\iTop\Application\UI\Base\iUIBlock
 * @throws \CoreException
 * @throws \CoreUnexpectedValue
 * @throws \MySQLException
 * @since 3.0.0
 */
function GetObsoleteDataFieldBlock(): iUIBlock
{
	$bShow = utils::IsArchiveMode() || appUserPreferences::GetPref('show_obsolete_data', MetaModel::GetConfig()->Get('obsolescence.show_obsolete_data'));
	$sSelectedForHtmlAttribute = $bShow ? ' checked="checked"' : '';
	$sDisabledForHtmlAttribute = utils::IsArchiveMode() ? 'disabled="disabled"' : '';

	$sLabel = Dict::S('UI:Favorites:ShowObsoleteData');
	$sLabelDescription = Dict::S('UI:Favorites:ShowObsoleteData+');
	$sHtml = <<<HTML
<p>
	<label data-tooltip-content="{$sLabelDescription}">
		<span>{$sLabel}</span>
		<input type="checkbox" name="show_obsolete_data" value="1"{$sSelectedForHtmlAttribute}{$sDisabledForHtmlAttribute}>
	</label>
</p>
HTML;

	return new Html($sHtml);
}


/**
 * @return \Combodo\iTop\Application\UI\Base\iUIBlock
 * @throws \CoreException
 * @throws \CoreUnexpectedValue
 * @throws \MySQLException
 * @since 3.1.0
 */
function GetSummaryCardsFieldBlock(): iUIBlock
{
	$bShow = appUserPreferences::GetPref('show_summary_cards', true);
	$sSelectedForHtmlAttribute = $bShow ? 'checked="checked"' : '';

	$sLabel = Dict::S('UI:Favorites:General:ShowSummaryCards');
	$sLabelDescription = Dict::S('UI:Favorites:General:ShowSummaryCards+');
	$sHtml = <<<HTML
<p>
	<label data-tooltip-content="{$sLabelDescription}">
		<span>{$sLabel}</span>
		<input type="checkbox" name="show_summary_cards" value="1" {$sSelectedForHtmlAttribute}>
	</label>
</p>
HTML;

	return new Html($sHtml);
}

/**
 * @return \Combodo\iTop\Application\UI\Base\iUIBlock
 * @throws \CoreException
 * @throws \CoreUnexpectedValue
 * @throws \MySQLException
 * @since 3.2.0
 */
function GetToastsPositionFieldBlock(): iUIBlock
{
	$sPosition = appUserPreferences::GetPref('toasts_vertical_position', "bottom");

	$oSelect = SelectUIBlockFactory::MakeForSelectWithLabel('toasts_vertical_position', Dict::S('UI:Preferences:General:Toasts'));
	
	$oSelect->AddSubBlock(SelectOptionUIBlockFactory::MakeForSelectOption("bottom", Dict::S('UI:Preferences:General:Toasts:Bottom'), $sPosition === "bottom"));
	$oSelect->AddSubBlock(SelectOptionUIBlockFactory::MakeForSelectOption("top", Dict::S('UI:Preferences:General:Toasts:Top'), $sPosition === "top"));

	return $oSelect;
}

/////////////////////////////////////////////////////////////////////////////
//
// Main program
//
/////////////////////////////////////////////////////////////////////////////

require_once(APPROOT.'/application/loginwebpage.class.inc.php');
LoginWebPage::DoLogin(); // Check user rights and prompt if needed

$iStep = utils::ReadParam('step', 1);

$oPage = new iTopWebPage(Dict::S('UI:Preferences'));
$oPage->DisableBreadCrumb();
$sOperation = utils::ReadParam('operation', '');

try {
	$bOperationUsed = false;
	/** @var iPreferencesExtension $oLoginExtensionInstance */
	foreach (MetaModel::EnumPlugins('iPreferencesExtension') as $oPreferencesExtensionInstance) {
		if ($oPreferencesExtensionInstance->ApplyPreferences($oPage, $sOperation)) {
			$bOperationUsed = true;
			break;
		}
	}

	if (!$bOperationUsed) {
		switch ($sOperation) {
			case 'apply':
				$oFilter = DBObjectSearch::FromOQL('SELECT Organization');
				$sSelectionMode = utils::ReadParam('selectionMode', '');
				$aExceptions = utils::ReadParam('storedSelection', array());
				if (($sSelectionMode == 'negative') && (count($aExceptions) == 0)) {
					// All Orgs selected
					appUserPreferences::SetPref('favorite_orgs', null);
				} else {
					// Some organizations selected... store them
					$aSelectOrgs = utils::ReadMultipleSelection($oFilter);
					appUserPreferences::SetPref('favorite_orgs', $aSelectOrgs);
				}
				$oPage->ResetNavigationMenuLayout();
				DisplayPreferences($oPage);
				break;

			case 'apply_user_interface':
				// Language
				$sLangCode = utils::ReadParam('language', 'EN US');
				$oUser = UserRights::GetUserObject();
				$oUser->Set('language', $sLangCode);

				utils::PushArchiveMode(false);
				$oUser->AllowWrite(true);
				$oUser->DBUpdate();
				utils::PopArchiveMode();

				// Theme
				$sThemeId = utils::ReadParam('theme', '');
				if (!empty($sThemeId) && ThemeHandler::IsValidTheme($sThemeId)) {
					appUserPreferences::SetPref('backoffice_theme', $sThemeId);
				}

				// List
				$iDefaultPageSize = (int)utils::ReadParam('default_page_size', -1);
				if ($iDefaultPageSize > 0) {
					appUserPreferences::SetPref('default_page_size', $iDefaultPageSize);
				}

				// Tabs
				// - Layout
				$sLayout = utils::ReadParam('tab_layout', 'horizontal');
				$sLayoutAllowedValues = ['horizontal', 'vertical'];
				if (in_array($sLayout, $sLayoutAllowedValues, true)) {
					appUserPreferences::SetPref('tab_layout', $sLayout);
				}

				// - Navigation
				$bScrollable = utils::ReadParam('tab_scrollable', 'false') === 'true';
				appUserPreferences::SetPref('tab_scrollable', $bScrollable);

				// Rich text editor
				$bToolbarExpanded = utils::ReadParam('toolbarexpanded', 'false') === 'true';
				$aRichTextConfig = json_decode(appUserPreferences::GetPref('richtext_config', '{}'), true);
				$aRichTextConfig['toolbarStartupExpanded'] = $bToolbarExpanded;
				appUserPreferences::SetPref('richtext_config', json_encode($aRichTextConfig));

				// Activity panel
				$bActivityPanelEntryFormOpened = (bool)utils::ReadParam('activity_panel_entry_form_opened', 0);
				appUserPreferences::SetPref('activity_panel.is_entry_form_opened', $bActivityPanelEntryFormOpened);

				// Misc.
				// - Obsolete data
				$bShowObsoleteData = (bool)utils::ReadParam('show_obsolete_data', 0);
				appUserPreferences::SetPref('show_obsolete_data', $bShowObsoleteData);
				
				// - Summary cards
				$bShowSummaryCards = (bool)utils::ReadParam('show_summary_cards', 0);
				appUserPreferences::SetPref('show_summary_cards', $bShowSummaryCards);

				// - Toast notifications
				$sToastsVerticalPosition = utils::ReadParam('toasts_vertical_position', "bottom");
				if(utils::IsNotNullOrEmptyString($sToastsVerticalPosition) && in_array($sToastsVerticalPosition, ["bottom", "top"], true)) {
					appUserPreferences::SetPref('toasts_vertical_position', $sToastsVerticalPosition);
				}
				
				// Redirect to force a reload/display of the page in case language has been changed
				$oAppContext = new ApplicationContext();
				$sURL = utils::GetAbsoluteUrlAppRoot().'pages/preferences.php?'.$oAppContext->GetForLink();
				$oPage->add_header('Location: '.$sURL);
				break;
			case 'apply_keyboard_shortcuts':
				/** @var iKeyboardShortcut[] $aShortcutClasses */
				$aShortcutClasses = InterfaceDiscovery::GetInstance()->FindItopClasses(iKeyboardShortcut::class);
				$aShortcutPrefs = [];
				foreach ($aShortcutClasses as $cShortcutPlugin) {
					foreach ($cShortcutPlugin::GetShortcutKeys() as $aShortcutKey) {
						$sKey = utils::ReadParam($aShortcutKey['id'], $aShortcutKey['key'], true, 'raw_data');
						$aShortcutPrefs[$aShortcutKey['id']] = strtolower(utils::HtmlEntities($sKey));
					}
				}
				appUserPreferences::SetPref('keyboard_shortcuts', $aShortcutPrefs);

				DisplayPreferences($oPage);
				break;
			case 'reset_keyboard_shortcuts':
				appUserPreferences::UnsetPref('keyboard_shortcuts');

				DisplayPreferences($oPage);
				break;
			case 'apply_newsroom_preferences':
				$iCountProviders = 0;
				$oUser = UserRights::GetUserObject();
				/** @var iNewsroomProvider[] $aProviders */
				$aProviders = InterfaceDiscovery::GetInstance()->FindItopClasses(iNewsroomProvider::class);
				foreach ($aProviders as $cProvider) {
					$oProvider = new $cProvider();
					if ($oProvider->IsApplicable($oUser)) {
						$iCountProviders++;
					}
				}
				$bNewsroomEnabled = (MetaModel::GetConfig()->Get('newsroom_enabled') !== false);
				if ($bNewsroomEnabled && ($iCountProviders > 0)) {
					$iNewsroomDisplaySize = (int)utils::ReadParam('newsroom_display_size', 7);
					if ($iNewsroomDisplaySize < 1) {
						$iNewsroomDisplaySize = 1;
					}
					if ($iNewsroomDisplaySize > 20) {
						$iNewsroomDisplaySize = 20;
					}
					$iCurrentDisplaySize = (int)appUserPreferences::GetPref('newsroom_display_size', $iNewsroomDisplaySize);
					if ($iCurrentDisplaySize != $iNewsroomDisplaySize) {
						// Save the preference only if it differs from the current (or default) value
						appUserPreferences::SetPref('newsroom_display_size', $iNewsroomDisplaySize);
					}
				}
				$bProvidersModified = false;
				foreach ($aProviders as $cProvider)
				{
					// Forbid disabling internal newsroom provider
					if ($cProvider === iTopNewsroomProvider::class) {
						continue;
					}

					$oProvider = new $cProvider();
					if ($oProvider->IsApplicable($oUser))
					{
						$sProviderClass = get_class($oProvider);
						$bProviderEnabled = (utils::ReadParam('newsroom_provider_'.$sProviderClass, 'off') == 'on');
						$bCurrentValue = appUserPreferences::GetPref('newsroom_provider_'.$sProviderClass, true);
						if ($bCurrentValue != $bProviderEnabled)
						{
							// Save the preference only if it differs from the current value
							$bProvidersModified = true;
							appUserPreferences::SetPref('newsroom_provider_'.$sProviderClass, $bProviderEnabled);
						}
					}
				}
				if ($bProvidersModified)
				{
					$oPage->add_ready_script(
						<<<JS
$('#ibo-navigation-menu--notifications-menu').newsroom_menu("clearCache");
CombodoToast.OpenSuccessToast(Dict.S('UI:Newsroom:ResetCache:Success:Message'));
JS
					);
				}
				DisplayPreferences($oPage);
				break;

			case 'display':
			default:
				$oPage->SetBreadCrumbEntry('ui-tool-preferences', Dict::S('UI:Preferences'), Dict::S('UI:Preferences'), '',
					'fas fa-user-cog', iTopWebPage::ENUM_BREADCRUMB_ENTRY_ICON_TYPE_CSS_CLASSES);
				DisplayPreferences($oPage);
		}
	}
	
	$oPage->output();
}
catch(CoreException $e)
{
	require_once(APPROOT.'/setup/setuppage.class.inc.php');
	$oP = new ErrorPage(Dict::S('UI:PageTitle:FatalError'));
	$oP->add("<h1>".Dict::S('UI:FatalErrorMessage')."</h1>\n");	
	$oP->error(Dict::Format('UI:Error_Details', $e->getHtmlDesc()));	
	$oP->output();

	if (MetaModel::IsLogEnabledIssue())
	{
		if (MetaModel::IsValidClass('EventIssue'))
		{
			$oLog = new EventIssue();

			$oLog->Set('message', $e->getMessage());
			$oLog->Set('userinfo', '');
			$oLog->Set('issue', $e->GetIssue());
			$oLog->Set('impact', 'Page could not be displayed');
			$oLog->Set('callstack', $e->getTrace());
			$oLog->Set('data', $e->getContextData());
			$oLog->DBInsertNoReload();
		}

		IssueLog::Error($e->getMessage());
	}

	// For debugging only
	//throw $e;
}
catch(Exception $e)
{
	require_once(APPROOT.'/setup/setuppage.class.inc.php');
	$oP = new ErrorPage(Dict::S('UI:PageTitle:FatalError'));
	$oP->add("<h1>".Dict::S('UI:FatalErrorMessage')."</h1>\n");	
	$oP->error(Dict::Format('UI:Error_Details', $e->getMessage()));	
	$oP->output();

	if (MetaModel::IsLogEnabledIssue())
	{
		if (MetaModel::IsValidClass('EventIssue'))
		{
			$oLog = new EventIssue();

			$oLog->Set('message', $e->getMessage());
			$oLog->Set('userinfo', '');
			$oLog->Set('issue', 'PHP Exception');
			$oLog->Set('impact', 'Page could not be displayed');
			$oLog->Set('callstack', $e->getTrace());
			$oLog->Set('data', array());
			$oLog->DBInsertNoReload();
		}

		IssueLog::Error($e->getMessage());
	}
}
