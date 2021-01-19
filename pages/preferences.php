<?php
/**
 * Copyright (C) 2013-2019 Combodo SARL
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

use Combodo\iTop\Application\UI\Base\Component\Button\ButtonUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Form\Form;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;
use Combodo\iTop\Application\UI\Base\Component\Input\InputUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Input\Select\SelectOptionUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Panel\Panel;
use Combodo\iTop\Application\UI\Base\Component\Title\TitleUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Layout\PageContent\PageContentFactory;
use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;
use Combodo\iTop\Application\UI\Preferences\BlockShortcuts\BlockShortcuts;

require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/startup.inc.php');

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
	// User Language selection
	//
	//////////////////////////////////////////////////////////////////////////
	$oUserLanguageBlock = new Panel(Dict::S('UI:FavoriteLanguage'), array(), 'grey', 'ibo-user-language-selection');
	$oUserLanguageForm = GetUserLanguageForm($oAppContext, $sURL);
	$oUserLanguageBlock->AddSubBlock($oUserLanguageForm);
	$oContentLayout->AddMainBlock($oUserLanguageBlock);

	//////////////////////////////////////////////////////////////////////////
	//
	// Other (miscellaneous) settings
	//
	//////////////////////////////////////////////////////////////////////////

	$oMiscSettingsBlock = new Panel(Dict::S('UI:FavoriteOtherSettings'), array(), 'grey', 'ibo-misc-settings');

	$oMiscSettingsStartForm = new Html('<form method="post" onsubmit="return ValidateOtherSettings()">');

	$iDefaultPageSize = appUserPreferences::GetPref('default_page_size', MetaModel::GetConfig()->GetMinDisplayLimit());

	$bShow = utils::IsArchiveMode() || appUserPreferences::GetPref('show_obsolete_data',
			MetaModel::GetConfig()->Get('obsolescence.show_obsolete_data'));
	$sObsoleteSelected = $bShow ? ' checked="checked"' : '';
	$sObsoleteDisabled = utils::IsArchiveMode() ? 'disabled="disabled"' : '';


	$sMiscSettingsHtml = '';
	$sMiscSettingsHtml .= '<p>'.Dict::Format('UI:Favorites:Default_X_ItemsPerPage',
			'<input id="default_page_size" name="default_page_size" type="text" size="3" value="'.$iDefaultPageSize.'"/><span id="v_default_page_size"></span>').'</p>';
	$sObsoleteLabel = Dict::S('UI:Favorites:ShowObsoleteData');
	$sObsoleteLabelPlus = Dict::S('UI:Favorites:ShowObsoleteData+');
	$sMiscSettingsHtml .= <<<HTML
<p><input type="checkbox" id="show_obsolete_data" name="show_obsolete_data" value="1"{$sObsoleteSelected}{$sObsoleteDisabled}
>&nbsp;<label for="show_obsolete_data" title="{$sObsoleteLabelPlus}">{$sObsoleteLabel}</label></p>
HTML;
	$sMiscSettingsHtml .= $oAppContext->GetForForm();
	$oMiscSettingsHtml = new Html($sMiscSettingsHtml);

	// - Cancel button
	$oMiscSettingsCancelButton = ButtonUIBlockFactory::MakeForSecondaryAction(Dict::S('UI:Button:Cancel'));
	$oMiscSettingsCancelButton->SetOnClickJsCode("window.location.href = '$sURL'");
	// - Submit button
	$oMiscSettingsSubmitButton = ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('UI:Button:Apply'), 'operation', 'apply_others', true);

	$oMiscSettingsEndHtmlBlock = new Html('</form>');

	$oMiscSettingsBlock->AddSubBlock($oMiscSettingsStartForm);
	$oMiscSettingsBlock->AddSubBlock($oMiscSettingsHtml);
	$oMiscSettingsBlock->AddSubBlock($oMiscSettingsCancelButton);
	$oMiscSettingsBlock->AddSubBlock($oMiscSettingsSubmitButton);
	$oMiscSettingsBlock->AddSubBlock($oMiscSettingsEndHtmlBlock);

	$oContentLayout->AddMainBlock($oMiscSettingsBlock);

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
		$('#v_default_page_size').html('<img src="../images/validation_error.png"/>');
		$('#ibo-misc-settings-submit').prop('disabled', true);
		return false;
	}
}
JS
	);

	//////////////////////////////////////////////////////////////////////////
	//
	// Favorite Organizations
	//
	//////////////////////////////////////////////////////////////////////////

	$oFavoriteOrganizationsBlock = new Panel(Dict::S('UI:FavoriteOrganizations'), array(), 'grey', 'ibo-favorite-organizations');
	$oFavoriteOrganizationsBlock->AddHtml(Dict::S('UI:FavoriteOrganizations+'));
	$oFavoriteOrganizationsForm = new Form();
	$oFavoriteOrganizationsBlock->AddSubBlock($oFavoriteOrganizationsForm);
	// Favorite organizations: the organizations listed in the drop-down menu
	$sOQL = ApplicationMenu::GetFavoriteSiloQuery();
	$oFilter = DBObjectSearch::FromOQL($sOQL);
	$oBlock = new DisplayBlock($oFilter, 'list', false);

	$aFavoriteOrgs = appUserPreferences::GetPref('favorite_orgs', null);

	$sIdFavoriteOrganizations = 1;
	$oFavoriteOrganizationsForm->AddSubBlock($oBlock->GetDisplay($oP, $sIdFavoriteOrganizations, [
		'menu' => false,
		'selection_mode' => true,
		'selection_type' => 'multiple',
		'table_id' => 'user_prefs',
		'surround_with_panel' => false,
		'selected_rows' => $aFavoriteOrgs
	]));
	$oFavoriteOrganizationsForm->AddSubBlock($oAppContext->GetForFormBlock());

	$oFavoriteOrganizationsToolBar = new UIContentBlock(null, 'ibo-datatable--selection-validation-buttons-toolbar');
	$oFavoriteOrganizationsForm->AddSubBlock($oFavoriteOrganizationsToolBar);
	// - Cancel button
	$oFavoriteOrganizationsCancelButton = ButtonUIBlockFactory::MakeForSecondaryAction(Dict::S('UI:Button:Cancel'));
	$oFavoriteOrganizationsToolBar->AddSubBlock($oFavoriteOrganizationsCancelButton);
	$oFavoriteOrganizationsCancelButton->SetOnClickJsCode("window.location.href = '$sURL'");
	// - Submit button
	$oFavoriteOrganizationsSubmitButton = ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('UI:Button:Apply'), 'operation', 'apply', true);
	$oFavoriteOrganizationsToolBar->AddSubBlock($oFavoriteOrganizationsSubmitButton);

	if ($aFavoriteOrgs == null) {
		// All checked
		$oP->add_ready_script(
			<<<JS
	$('#$sIdFavoriteOrganizations .checkAll').prop('checked', true);
	checkAllDataTable('datatable_$sIdFavoriteOrganizations',true,'$sIdFavoriteOrganizations');
JS
		);

	}

	$oContentLayout->AddMainBlock($oFavoriteOrganizationsBlock);

	//////////////////////////////////////////////////////////////////////////
	//
	// Shortcuts
	//
	//////////////////////////////////////////////////////////////////////////

	$oShortcutsBlock = new BlockShortcuts(Dict::S('Menu:MyShortcuts'), array(), 'grey', 'ibo-shortcuts');
	$oShortcutsBlock->sIdShortcuts = 'shortcut_list';
	$oShortcutsFilter = new DBObjectSearch('Shortcut');
	$oShortcutsFilter->AddCondition('user_id', UserRights::GetUserId(), '=');

	$oBlock = new DisplayBlock($oShortcutsFilter, 'list', false);
	$oShortcutsBlock->AddSubBlock($oBlock->GetDisplay($oP, $oShortcutsBlock->sIdShortcuts, [
		'view_link' => false,
		'menu' => false,
		'toolkit_menu' => false,
		'selection_mode' => true,
		'selection_type' => 'multiple',
		'table_id' => 'user_prefs_shortcuts',
		'surround_with_panel' => false,
	]));

	$oSet = new DBObjectSet($oShortcutsFilter);
	if ($oSet->Count() > 0) {
		$oShortcutsToolBar = new UIContentBlock(null, 'ibo-datatable--selection-validation-buttons-toolbar');
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
	$aProviders = MetaModel::EnumPlugins('iNewsroomProvider');
	foreach($aProviders as $oProvider)
	{
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
		$sIcon = '<i id="newsroom_menu_icon" class="top-right-icon icon-additional-arrow fas fa-comment-dots" style="top: 0;"></i>';
		$sNewsroomHtml .= Dict::Format('UI:Newsroom:DisplayAtMost_X_Messages', $sInput, $sIcon);
		
		/**
		 * @var iNewsroomProvider[] $aProviders
		 */
		$sAppRootUrl = utils::GetAbsoluteUrlAppRoot();
		foreach($aProviders as $oProvider)
		{
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
				$sChecked = appUserPreferences::GetPref('newsroom_provider_'.$sProviderClass, true) ? ' checked="" ' : '';
				$sNewsroomHtml .= '<div><input type="checkbox" id="newsroom_provider_'.$sProviderClass.'" value="on"'.$sChecked.'name="newsroom_provider_'.$sProviderClass.'"><label for="newsroom_provider_'.$sProviderClass.'">'.Dict::Format('UI:Newsroom:DisplayMessagesFor_Provider',
						$oProvider->GetLabel()).'</label> '.$sPreferencesLink.'</div>';
			}
		}

		$sNewsroomHtml .= $oAppContext->GetForForm();

		// - Reset button
		$oNewsroomResetCacheButton = ButtonUIBlockFactory::MakeForAlternativeDestructiveAction(Dict::S('UI:Newsroom:ResetCache'));
		$oNewsroomResetCacheButton->SetOnClickJsCode("$('#ibo-navigation-menu--notifications-menu').newsroom_menu('clearCache')");
		// - Cancel button
		$oNewsroomCancelButton = ButtonUIBlockFactory::MakeForSecondaryAction(Dict::S('UI:Button:Cancel'));
		$oNewsroomCancelButton->SetOnClickJsCode("window.location.href = '$sURL'");
		// - Submit button
		$oNewsroomSubmitButton = ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('UI:Button:Apply'), 'operation',
			'apply_newsroom_preferences', true);


		$sNewsroomEndHtml = '</form>';
		$oNewsroomEndHtmlBlock = new Html($sNewsroomEndHtml);

		$oNewsroomHtmlBlock = new Html($sNewsroomHtml);
		$oNewsroomBlock->AddSubBlock($oNewsroomHtmlBlock);
		$oNewsroomBlock->AddSubBlock($oNewsroomResetCacheButton);
		$oNewsroomBlock->AddSubBlock($oNewsroomCancelButton);
		$oNewsroomBlock->AddSubBlock($oNewsroomSubmitButton);
		$oNewsroomBlock->AddSubBlock($oNewsroomEndHtmlBlock);
		$oContentLayout->AddMainBlock($oNewsroomBlock);
	}
	//////////////////////////////////////////////////////////////////////////
	//
	// Rich text editor preferences
	//
	//////////////////////////////////////////////////////////////////////////
	$oRichTextBlock = new Panel(Dict::S('UI:RichText:Preferences'), array(), 'grey', 'ibo-richtext');

	$oRichTextForm = new Form();
	$oRichTextForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('operation', 'apply_richtext_config'));

	$sRichTextToolbarDefaultState = isset(utils::GetCkeditorPref()['toolbarStartupExpanded']) ? (bool)utils::GetCkeditorPref()['toolbarStartupExpanded'] : false;
	$oRichTextToolbarDefaultStateInput = InputUIBlockFactory::MakeForSelectWithLabel('toolbarexpanded', Dict::S('UI:RichText:ToolbarState'));
	$oRichTextToolbarDefaultStateInput->GetInput()->AddOption(SelectOptionUIBlockFactory::MakeForSelectOption('true', Dict::S('UI:RichText:ToolbarState:Expanded'), $sRichTextToolbarDefaultState));
	$oRichTextToolbarDefaultStateInput->GetInput()->AddOption(SelectOptionUIBlockFactory::MakeForSelectOption('false', Dict::S('UI:RichText:ToolbarState:Collapsed'), !$sRichTextToolbarDefaultState));
	$oRichTextForm->AddSubBlock($oRichTextToolbarDefaultStateInput);

	// - Cancel button
	$oRichTextCancelButton = ButtonUIBlockFactory::MakeForSecondaryAction(Dict::S('UI:Button:Cancel'));
	$oRichTextCancelButton->SetOnClickJsCode("window.location.href = '$sURL'");
	$oRichTextForm->AddSubBlock($oRichTextCancelButton);
	// - Submit button
	$oRichTextSubmitButton = ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('UI:Button:Apply'), null, null, true);
	$oRichTextForm->AddSubBlock($oRichTextSubmitButton);

	$oRichTextBlock->AddSubBlock($oRichTextForm);
	$oContentLayout->AddMainBlock($oRichTextBlock);

	//////////////////////////////////////////////////////////////////////////
	//
	// Tabs preferences
	//
	//////////////////////////////////////////////////////////////////////////

	$oTabsBlock = new Panel(Dict::S('UI:Tabs:Preferences'), array(), 'grey', 'ibo-tabs');

	$oTabsForm = new Form();
	$oTabsForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('operation', 'apply_tab_config'));

	$sTabsScrollableValue = appUserPreferences::GetPref('tab_scrollable', false);;
	$oTabsScrollable = InputUIBlockFactory::MakeForSelectWithLabel('tab_scrollable', Dict::S('UI:Tabs:Scrollable:Label'));
	$oTabsScrollable->GetInput()->AddSubBlock(SelectOptionUIBlockFactory::MakeForSelectOption('true', Dict::S('UI:Tabs:Scrollable:Scrollable'), true === $sTabsScrollableValue));
	$oTabsScrollable->GetInput()->AddSubBlock(SelectOptionUIBlockFactory::MakeForSelectOption('false', Dict::S('UI:Tabs:Scrollable:Classic'), false === $sTabsScrollableValue));
	$oTabsForm->AddSubBlock($oTabsScrollable);

	// - Cancel button
	$oTabsCancelButton = ButtonUIBlockFactory::MakeForSecondaryAction(Dict::S('UI:Button:Cancel'));
	$oTabsCancelButton->SetOnClickJsCode("window.location.href = '$sURL'");
	$oTabsForm->AddSubBlock($oTabsCancelButton);
	// - Submit button
	$oTabsSubmitButton = ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('UI:Button:Apply'), null, null, true);
	$oTabsForm->AddSubBlock($oTabsSubmitButton);

	$oTabsBlock->AddSubBlock($oTabsForm);
	$oContentLayout->AddMainBlock($oTabsBlock);
	
	//////////////////////////////////////////////////////////////////////////
	//
	// User picture placeholder
	//
	//////////////////////////////////////////////////////////////////////////

	$oUserPicturePlaceHolderBlock = new Panel(Dict::S('UI:Preferences:ChooseAPlaceholder'), array(), 'grey', 'ibo-user-picture-placeholder');

	$sUserPicturesFolder = '../images/user-pictures/';
	$sUserDefaultPicture = appUserPreferences::GetPref('user_picture_placeholder', 'default-placeholder.png');
	$sUserPicturePlaceHolderHtml = '';
	$sUserPicturePlaceHolderHtml .= '<p>'.Dict::S('UI:Preferences:ChooseAPlaceholder+').'</p> <div class="ibo-preferences--user-preferences--picture-placeholder">';
	foreach (scandir($sUserPicturesFolder) as $sUserPicture)
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
		$sUserPicturePlaceHolderHtml .= '<a class="ibo-preferences--user-preferences--picture-placeholder--image'.$sAdditionalClass.'" data-image-name="'.$sUserPicture.'" data-role="ibo-preferences--user-preferences--picture-placeholder--image" href="#"> <img src="'.$sUserPicturesFolder.$sUserPicture.'"/> </a>';
	}
	$oP->add_ready_script(
		<<<JS
$('[data-role="ibo-preferences--user-preferences--picture-placeholder--image"]').on('click',function(){
SetUserPreference('user_picture_placeholder', $(this).attr('data-image-name'), true);
$('[data-role="ibo-preferences--user-preferences--picture-placeholder--image"]').removeClass('ibo-is-active');
$(this).addClass('ibo-is-active');
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
	$oP->add_ready_script("$('#fav_page_length').bind('keyup change', function(){ ValidateOtherSettings(); })");
	$oP->SetContentLayout($oContentLayout);
}

/**
 * @param \ApplicationContext $oAppContext
 * @param string $sURL
 *
 * @return \Combodo\iTop\Application\UI\Base\Component\Form\Form
 */
function GetUserLanguageForm(ApplicationContext $oAppContext, string $sURL): Form
{
	$oUserLanguageForm = new Form();
	$oUserLanguageForm->AddSubBlock(InputUIBlockFactory::MakeForHidden('operation', 'apply_language'));

	// Lang selector
	$aLanguages = Dict::GetLanguages();
	$aSortedLang = array();
	foreach ($aLanguages as $sCode => $aLang) {
		if (MetaModel::GetConfig()->Get('demo_mode')) {
			if ($sCode != Dict::GetUserLanguage()) {
				// Demo mode: only the current user language is listed in the available choices
				continue;
			}
		}
		$aSortedLang[$aLang['description']] = $sCode;
	}
	ksort($aSortedLang);
	$oUserLanguageBlockSelect = InputUIBlockFactory::MakeForSelectWithLabel('language', Dict::S('UI:Favorites:SelectYourLanguage'));
	/** @var \Combodo\iTop\Application\UI\Base\Component\Input\Select $oUserLanguageBlockSelectInput */
	$oUserLanguageBlockSelectInput = $oUserLanguageBlockSelect->GetInput();
	foreach ($aSortedLang as $sCode) {
		$bSelected = ($sCode == Dict::GetUserLanguage());
		$oUserLanguageBlockSelectInput->AddSubBlock(SelectOptionUIBlockFactory::MakeForSelectOption($sCode, $aLanguages[$sCode]['description'].' ('.$aLanguages[$sCode]['localized_description'].')', $bSelected));
	}
	$oUserLanguageForm->AddSubBlock($oUserLanguageBlockSelect);

	$oUserLanguageForm->AddSubBlock($oAppContext->GetForFormBlock());
	// - Cancel button
	$oUserLanguageCancelButton = ButtonUIBlockFactory::MakeForSecondaryAction(Dict::S('UI:Button:Cancel'));
	$oUserLanguageCancelButton->SetOnClickJsCode("window.location.href = '$sURL'");
	$oUserLanguageForm->AddSubBlock($oUserLanguageCancelButton);
	// - Submit button
	$oUserLanguageSubmitButton = ButtonUIBlockFactory::MakeForPrimaryAction(Dict::S('UI:Button:Apply'), null, null, true);
	$oUserLanguageForm->AddSubBlock($oUserLanguageSubmitButton);
	return $oUserLanguageForm;
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
				DisplayPreferences($oPage);
				break;
			case 'apply_richtext_config':
				$aRichTextConfig = 	json_decode(appUserPreferences::GetPref('richtext_config', '{}'), true);
				
				$bToolbarExpanded = utils::ReadParam('toolbarexpanded', 'false') === 'true';
				$aRichTextConfig['toolbarStartupExpanded'] = $bToolbarExpanded;
				
				appUserPreferences::SetPref('richtext_config', json_encode($aRichTextConfig));
				DisplayPreferences($oPage);
				break;
			case 'apply_tab_config':
				$bScrollable= utils::ReadParam('tab_scrollable', 'false') === 'true';
				appUserPreferences::SetPref('tab_scrollable', $bScrollable);
				break;
			case 'apply_language':
				$sLangCode = utils::ReadParam('language', 'EN US');
				$oUser = UserRights::GetUserObject();
				$oUser->Set('language', $sLangCode);
				utils::PushArchiveMode(false);
				$oUser->AllowWrite(true);
				$oUser->DBUpdate();
				utils::PopArchiveMode();
				// Redirect to force a reload/display of the page with the new language
				$oAppContext = new ApplicationContext();
				$sURL = utils::GetAbsoluteUrlAppRoot().'pages/preferences.php?'.$oAppContext->GetForLink();
				$oPage->add_header('Location: '.$sURL);
				break;
			case 'apply_others':
				$iDefaultPageSize = (int)utils::ReadParam('default_page_size', -1);
				if ($iDefaultPageSize > 0)
				{
					appUserPreferences::SetPref('default_page_size', $iDefaultPageSize);
				}
				$bShowObsoleteData = (bool)utils::ReadParam('show_obsolete_data', 0);
				appUserPreferences::SetPref('show_obsolete_data', $bShowObsoleteData);
				DisplayPreferences($oPage);
				break;

			case 'apply_newsroom_preferences':
				$iCountProviders = 0;
				$oUser = UserRights::GetUserObject();
				$aProviders = MetaModel::EnumPlugins('iNewsroomProvider');
				foreach ($aProviders as $oProvider)
				{
					if ($oProvider->IsApplicable($oUser))
					{
						$iCountProviders++;
					}
				}
				$bNewsroomEnabled = (MetaModel::GetConfig()->Get('newsroom_enabled') !== false);
				if ($bNewsroomEnabled && ($iCountProviders > 0))
				{
					$iNewsroomDisplaySize = (int)utils::ReadParam('newsroom_display_size', 7);
					if ($iNewsroomDisplaySize < 1)
					{
						$iNewsroomDisplaySize = 1;
					}
					if ($iNewsroomDisplaySize > 20)
					{
						$iNewsroomDisplaySize = 20;
					}
					$iCurrentDisplaySize = (int)appUserPreferences::GetPref('newsroom_display_size', $iNewsroomDisplaySize);
					if ($iCurrentDisplaySize != $iNewsroomDisplaySize)
					{
						// Save the preference only if it differs from the current (or default) value
						appUserPreferences::SetPref('newsroom_display_size', $iNewsroomDisplaySize);
					}
				}
				$bProvidersModified = false;
				foreach ($aProviders as $oProvider)
				{
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
					$oPage->add_ready_script('$(".itop-newsroom_menu").newsroom_menu("clearCache");');
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
