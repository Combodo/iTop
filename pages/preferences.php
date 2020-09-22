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

use Combodo\iTop\Application\UI\Component\Button\ButtonFactory;
use Combodo\iTop\Application\UI\Component\Form\Form;
use Combodo\iTop\Application\UI\Component\Html\Html;
use Combodo\iTop\Application\UI\Component\Input\InputFactory;
use Combodo\iTop\Application\UI\Component\Panel\Panel;
use Combodo\iTop\Application\UI\Component\Title\TitleFactory;
use Combodo\iTop\Application\UI\Layout\PageContent\PageContentFactory;

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

	$oContentLayout->AddMainBlock(TitleFactory::MakeForPage(Dict::S('UI:Preferences:Title')));

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
	$sMiscSettingsHtml = '';
	$sMiscSettingsHtml .= '<p>'.Dict::Format('UI:Favorites:Default_X_ItemsPerPage', '<input id="default_page_size" name="default_page_size" type="text" size="3" value="'.$iDefaultPageSize.'"/><span id="v_default_page_size"></span>').'</p>';

	$bShow = utils::IsArchiveMode() || appUserPreferences::GetPref('show_obsolete_data',
			MetaModel::GetConfig()->Get('obsolescence.show_obsolete_data'));
	$sSelected = $bShow ? ' checked="checked"' : '';
	$sDisabled = utils::IsArchiveMode() ? 'disabled="disabled"' : '';
	$sMiscSettingsHtml .=
		'<p>'
		.'<input type="checkbox" id="show_obsolete_data" name="show_obsolete_data" value="1"'.$sSelected.$sDisabled.'>'
		.'<label for="show_obsolete_data" title="'.Dict::S('UI:Favorites:ShowObsoleteData+').'">'.Dict::S('UI:Favorites:ShowObsoleteData').'</label>'
		.'</p>';
	$sMiscSettingsHtml .= $oAppContext->GetForForm();
	$oMiscSettingsHtml = new Html($sMiscSettingsHtml);

	// - Cancel button
	$oMiscSettingsCancelButton = ButtonFactory::MakeForSecondaryAction(Dict::S('UI:Button:Cancel'));
	$oMiscSettingsCancelButton->SetOnClickJsCode("window.location.href = '$sURL'");
	// - Submit button
	$oMiscSettingsSubmitButton = ButtonFactory::MakeForValidationAction(Dict::S('UI:Button:Apply'), 'operation', 'apply_others', true);

	$oMiscSettingsEndHtmlBlock = new Html('</form>');

	$oMiscSettingsBlock->AddSubBlock($oMiscSettingsStartForm);
	$oMiscSettingsBlock->AddSubBlock($oMiscSettingsHtml);
	$oMiscSettingsBlock->AddSubBlock($oMiscSettingsCancelButton);
	$oMiscSettingsBlock->AddSubBlock($oMiscSettingsSubmitButton);
	$oMiscSettingsBlock->AddSubBlock($oMiscSettingsEndHtmlBlock);

	$oContentLayout->AddMainBlock($oMiscSettingsBlock);

	$oP->add_script(
		<<<EOF
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
EOF
	);

	//////////////////////////////////////////////////////////////////////////
	//
	// Favorite Organizations
	//
	//////////////////////////////////////////////////////////////////////////

	$oFavoriteOrganizationsBlock = new Panel(Dict::S('UI:FavoriteOrganizations'), array(), 'grey', 'ibo-favorite-organizations');

	$sFavoriteOrganizationsHtml = '';
	$sFavoriteOrganizationsHtml .= Dict::S('UI:FavoriteOrganizations+');
	$sFavoriteOrganizationsHtml .= '<form method="post">';
	// Favorite organizations: the organizations listed in the drop-down menu
	$sOQL = ApplicationMenu::GetFavoriteSiloQuery();
	$oFilter = DBObjectSearch::FromOQL($sOQL);
	$oBlock = new DisplayBlock($oFilter, 'list', false);
	$sFavoriteOrganizationsHtml .= $oBlock->GetDisplay($oP, 1, array(
		'menu' => false,
		'selection_mode' => true,
		'selection_type' => 'multiple',
		'cssCount' => '.selectedCount',
		'table_id' => 'user_prefs',
	));
	$sFavoriteOrganizationsHtml .= $oAppContext->GetForForm();

	// - Cancel button
	$oFavoriteOrganizationsCancelButton = ButtonFactory::MakeForSecondaryAction(Dict::S('UI:Button:Cancel'));
	$oFavoriteOrganizationsCancelButton->SetOnClickJsCode("window.location.href = '$sURL'");
	// - Submit button
	$oFavoriteOrganizationsSubmitButton = ButtonFactory::MakeForValidationAction(Dict::S('UI:Button:Apply'), 'operation', 'apply', true);

	$sFavoriteOrganizationsEndHtml = '</form>';
	$oFavoriteOrganizationsEndHtmlBlock = new Html($sFavoriteOrganizationsEndHtml);

	$aFavoriteOrgs = appUserPreferences::GetPref('favorite_orgs', null);
	if ($aFavoriteOrgs == null)
	{
		// All checked
		$oP->add_ready_script(
			<<<EOF
	if ($('#user_prefs table.pagination').length > 0)
	{
		// paginated display, restore the selection
		var pager = $('#user_prefs form .pager');
		$(':input[name=selectionMode]', pager).val('negative');
		$('#user_prefs table.listResults').trigger('load_selection');
	}
	else
	{
		$('#user_prefs table.listResults').trigger('check_all');
	}
EOF
);

	}
	else
	{
		$sChecked = implode('","', $aFavoriteOrgs);
		$oP->add_ready_script(
<<<EOF
	var aChecked = ["$sChecked"];
	if ($('#user_prefs table.pagination').length > 0)
	{
		// paginated display, restore the selection
		var pager = $('#user_prefs form .pager');
		$(':input[name=selectionMode]', pager).val('positive');
		for (i=0; i<aChecked.length; i++)
		{
			pager.append('<input type="hidden" name="storedSelection[]" id="'+aChecked[i]+'" value="'+aChecked[i]+'"/>');
		}
		$('#user_prefs table.listResults').trigger('load_selection');
		
	}
	else
	{
		$('#user_prefs form :checkbox[name^=selectObject]').each( function()
			{
				if ($.inArray($(this).val(), aChecked) > -1)
				{
					$(this).prop('checked', true);
					$(this).trigger('change');
				}
			});
	}
EOF
);
	}
	
	$oFavoriteOrganizationsHtmlBlock = new Html($sFavoriteOrganizationsHtml);
	$oFavoriteOrganizationsBlock->AddSubBlock($oFavoriteOrganizationsHtmlBlock);
	$oFavoriteOrganizationsBlock->AddSubBlock($oFavoriteOrganizationsCancelButton);
	$oFavoriteOrganizationsBlock->AddSubBlock($oFavoriteOrganizationsSubmitButton);
	$oFavoriteOrganizationsBlock->AddSubBlock($oFavoriteOrganizationsEndHtmlBlock);
	
	$oContentLayout->AddMainBlock($oFavoriteOrganizationsBlock);

	//////////////////////////////////////////////////////////////////////////
	//
	// Shortcuts
	//
	//////////////////////////////////////////////////////////////////////////

	$oShortcutsBlock = new Panel(Dict::S('Menu:MyShortcuts'), array(), 'grey', 'ibo-shortcuts');
	$sShortcutsHtml = '';
	$oBMSearch = new DBObjectSearch('Shortcut');
	$oBMSearch->AddCondition('user_id', UserRights::GetUserId(), '=');

	$aExtraParams = array();
	$oBlock = new DisplayBlock($oBMSearch, 'list', false, $aExtraParams);
	$sShortcutsHtml .= $oBlock->GetDisplay($oP, 'shortcut_list', array('view_link' => false, 'menu' => false, 'toolkit_menu' => false, 'selection_mode' => true, 'selection_type' => 'multiple', 'cssCount'=> '#shortcut_selection_count', 'table_id' => 'user_prefs_shortcuts'));
	$sShortcutsHtml .='<p>';

	$oSet = new DBObjectSet($oBMSearch);
	if ($oSet->Count() > 0)
	{
		$sButtons = '<img src="../images/tv-item-last.gif">';
		$sButtons .= '<button id="shortcut_btn_rename">'.Dict::S('UI:Button:Rename').'</button>';
		$sButtons .= '<button id="shortcut_btn_delete">'.Dict::S('UI:Button:Delete').'</button>';

		// Selection count updated by the pager, and used to enable buttons
		$sShortcutsHtml .= '<input type="hidden" id="shortcut_selection_count"/>';
		$sConfirmDelete = addslashes(Dict::S('UI:ShortcutDelete:Confirm'));
	
		$oP->add_ready_script(
<<<EOF
function OnShortcutBtnRename()
{
	var oParams = $('#datatable_shortcut_list').datatable('GetMultipleSelectionParams');
	oParams.operation = 'shortcut_rename_dlg';

	$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', oParams, function(data){
		$('body').append(data);
	});
	return false;
}

function OnShortcutBtnDelete()
{
	if (confirm('$sConfirmDelete'))
	{
		var oParams = $('#datatable_shortcut_list').datatable('GetMultipleSelectionParams');
		oParams.operation = 'shortcut_delete_go';

		$.post(GetAbsoluteUrlAppRoot()+'pages/ajax.render.php', oParams, function(data){
			$('body').append(data);
		});
	}
	return false;
}

function OnSelectionCountChange()
{
	var iCountSelected = $("#shortcut_selection_count").val();
	if (iCountSelected == 0)
	{
		$('#shortcut_btn_rename').prop('disabled', true);
		$('#shortcut_btn_delete').prop('disabled', true);
	}
	else if (iCountSelected == 1)
	{
		$('#shortcut_btn_rename').prop('disabled', false);
		$('#shortcut_btn_delete').prop('disabled', false);
	}
	else
	{
		$('#shortcut_btn_rename').prop('disabled', true);
		$('#shortcut_btn_delete').prop('disabled', false);
	}
}

var oUpperCheckBox = $('#datatable_shortcut_list .checkAll').first();
oUpperCheckBox.parent().width(oUpperCheckBox.width() + 2);

$('#datatable_shortcut_list').append('<tr><td colspan="2">&nbsp;&nbsp;&nbsp;$sButtons</td></tr>');
$('#shortcut_selection_count').bind('change', OnSelectionCountChange);
$('#shortcut_btn_rename').bind('click', OnShortcutBtnRename);
$('#shortcut_btn_delete').bind('click', OnShortcutBtnDelete);
OnSelectionCountChange();
EOF
		);
	} // if count > 0
	$oShortcutsHtmlBlock = new Html($sShortcutsHtml);
	$oShortcutsBlock->AddSubBlock($oShortcutsHtmlBlock);
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
		$aProviderParams = array();
		$iCountProviders = 0;
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
		$oNewsroomResetCacheButton = ButtonFactory::MakeForAlternativeDestructiveAction(Dict::S('UI:Newsroom:ResetCache'));
		$oNewsroomResetCacheButton->SetOnClickJsCode("$('#ibo-navigation-menu--notifications-menu').newsroom_menu('clearCache')");
		// - Cancel button
		$oNewsroomCancelButton = ButtonFactory::MakeForSecondaryAction(Dict::S('UI:Button:Cancel'));
		$oNewsroomCancelButton->SetOnClickJsCode("window.location.href = '$sURL'");
		// - Submit button
		$oNewsroomSubmitButton = ButtonFactory::MakeForValidationAction(Dict::S('UI:Button:Apply'), 'operation',
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
 * @return \Combodo\iTop\Application\UI\Component\Form\Form
 */
function GetUserLanguageForm(ApplicationContext $oAppContext, string $sURL): Form
{
	$oUserLanguageForm = new Form();
	$oUserLanguageForm->AddSubBlock(InputFactory::MakeForHidden('operation', 'apply_language'));

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
	$oUserLanguageBlockSelect = InputFactory::MakeForSelect('language', Dict::S('UI:Favorites:SelectYourLanguage'));
	foreach ($aSortedLang as $sCode) {
		$bSelected = ($sCode == Dict::GetUserLanguage());
		$oUserLanguageBlockSelect->AddOption(InputFactory::MakeForSelectOption($sCode, $aLanguages[$sCode]['description'].' ('.$aLanguages[$sCode]['localized_description'].')', $bSelected));
	}
	$oUserLanguageForm->AddSubBlock($oUserLanguageBlockSelect);

	$oUserLanguageForm->AddSubBlock($oAppContext->GetForFormBlock());
	// - Cancel button
	$oUserLanguageCancelButton = ButtonFactory::MakeForSecondaryAction(Dict::S('UI:Button:Cancel'));
	$oUserLanguageCancelButton->SetOnClickJsCode("window.location.href = '$sURL'");
	$oUserLanguageForm->AddSubBlock($oUserLanguageCancelButton);
	// - Submit button
	$oUserLanguageSubmitButton = ButtonFactory::MakeForValidationAction(Dict::S('UI:Button:Apply'), null, null, true);
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
	
try
{
	/** @var iPreferencesExtension $oLoginExtensionInstance */
	$bOperationUsed = false;
	foreach(MetaModel::EnumPlugins('iPreferencesExtension') as $oPreferencesExtensionInstance)
	{
		if ($oPreferencesExtensionInstance->ApplyPreferences($oPage, $sOperation))
		{
			$bOperationUsed = true;
			break;
		}
	}

	if (!$bOperationUsed)
	{
		switch ($sOperation)
		{
			case 'apply':
				$oFilter = DBObjectSearch::FromOQL('SELECT Organization');
				$sSelectionMode = utils::ReadParam('selectionMode', '');
				$aExceptions = utils::ReadParam('storedSelection', array());
				if (($sSelectionMode == 'negative') && (count($aExceptions) == 0))
				{
					// All Orgs selected
					appUserPreferences::SetPref('favorite_orgs', null);
				}
				else
				{
					// Some organizations selected... store them
					$aSelectOrgs = utils::ReadMultipleSelection($oFilter);
					appUserPreferences::SetPref('favorite_orgs', $aSelectOrgs);
				}
				DisplayPreferences($oPage);
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
