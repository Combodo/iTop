<?php
// Copyright (C) 2010-2012 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

/**
 * User preferences page
 * Displays / edit some user preferences
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
require_once('../approot.inc.php');
require_once(APPROOT.'/application/application.inc.php');
require_once(APPROOT.'/application/itopwebpage.class.inc.php');
require_once(APPROOT.'/application/startup.inc.php');

/**
 * Displays the user's changeable preferences
 * @param $oP WebPage The web page used for the output
 */
function DisplayPreferences($oP)
{
	$oAppContext = new ApplicationContext();
	$sURL = utils::GetAbsoluteUrlAppRoot().'pages/UI.php?'.$oAppContext->GetForLink();
	
	$oP->add('<div class="page_header"><h1><img style="vertical-align:middle" src="../images/preferences.png"/>&nbsp;'.Dict::S('UI:Preferences')."</h1></div>\n");
	$oP->add('<div id="user_prefs" style="max-width:800px; min-width:400px;">');
	
	//////////////////////////////////////////////////////////////////////////
	//
	// User Language selection
	//
	//////////////////////////////////////////////////////////////////////////

	$oP->add('<fieldset><legend>'.Dict::S('UI:FavoriteLanguage').'</legend>');
	$oP->add('<form method="post">');
  	$aLanguages = Dict::GetLanguages();
  	$aSortedlang = array();
  	foreach($aLanguages as $sCode => $aLang)
  	{
		if (MetaModel::GetConfig()->Get('demo_mode'))
		{
			if ($sCode != Dict::GetUserLanguage())
			{
				// Demo mode: only the current user language is listed in the available choices
				continue;
			}
		}
  		$aSortedlang[$aLang['description']] = $sCode;
  	}
  	ksort($aSortedlang);
  	$oP->add('<p>'.Dict::S('UI:Favorites:SelectYourLanguage').' <select name="language">');
  	foreach($aSortedlang as $sCode)
  	{
  		$sSelected = ($sCode == Dict::GetUserLanguage()) ? 'selected' : '';
		$oP->add('<option value="'.$sCode.'" '.$sSelected.'/>'.$aLanguages[$sCode]['description'].' ('.$aLanguages[$sCode]['localized_description'].')</option>');
  	}
  	$oP->add('</select></p>');
  	$oP->add('<input type="hidden" name="operation" value="apply_language"/>');
	$oP->add($oAppContext->GetForForm());
	$oP->add('<p><input type="button" onClick="window.location.href=\''.$sURL.'\'" value="'.Dict::S('UI:Button:Cancel').'"/>');
	$oP->add('&nbsp;&nbsp;');
	$oP->add('<input type="submit" value="'.Dict::S('UI:Button:Apply').'"/></p>');
	$oP->add('</form>');
	$oP->add('</fieldset>');

	//////////////////////////////////////////////////////////////////////////
	//
	// Other (miscellaneous) settings
	//
	//////////////////////////////////////////////////////////////////////////
	
	$oP->add('<fieldset><legend>'.Dict::S('UI:FavoriteOtherSettings').'</legend>');
	$oP->add('<form method="post" onsubmit="return ValidateOtherSettings()">');
	$iDefaultPageSize = appUserPreferences::GetPref('default_page_size', MetaModel::GetConfig()->GetMinDisplayLimit());
	$oP->add('<p>'.Dict::Format('UI:Favorites:Default_X_ItemsPerPage', '<input id="default_page_size" name="default_page_size" type="text" size="3" value="'.$iDefaultPageSize.'"/><span id="v_default_page_size"></span>').'</p>');
	$oP->add('<input type="hidden" name="operation" value="apply_others"/>');
	$oP->add($oAppContext->GetForForm());
	$oP->add('<p><input type="button" onClick="window.location.href=\''.$sURL.'\'" value="'.Dict::S('UI:Button:Cancel').'"/>');
	$oP->add('&nbsp;&nbsp;');
	$oP->add('<input id="other_submit" type="submit" value="'.Dict::S('UI:Button:Apply').'"/></p>');
	$oP->add('</form>');
	$oP->add('</fieldset>');
	
	$oP->add_script(
<<<EOF
function ValidateOtherSettings()
{
	var sPageLength = $('#default_page_size').val();
	var iPageLength = parseInt(sPageLength , 10);
	if (/^[0-9]+$/.test(sPageLength) && (iPageLength > 0))
	{
		$('#v_default_page_size').html('');
		$('#other_submit').removeAttr('disabled');
		return true;
	}
	else
	{
		$('#v_default_page_size').html('<img src="../images/validation_error.png"/>');
		$('#other_submit').attr('disabled', 'disabled');
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

	$oP->add('<fieldset><legend>'.Dict::S('UI:FavoriteOrganizations').'</legend>');
	$oP->p(Dict::S('UI:FavoriteOrganizations+'));
	$oP->add('<form method="post">');	
	// Favorite organizations: the organizations listed in the drop-down menu
	$sOQL = ApplicationMenu::GetFavoriteSiloQuery();
	$oFilter = DBObjectSearch::FromOQL($sOQL);
	$oBlock = new DisplayBlock($oFilter, 'list', false);
	$oBlock->Display($oP, 1, array('menu' => false, 'selection_mode' => true, 'selection_type' => 'multiple', 'cssCount'=> '.selectedCount', 'table_id' => 'user_prefs'));
	$oP->add($oAppContext->GetForForm());
	$oP->add('<input type="hidden" name="operation" value="apply"/>');
	$oP->add('<p><input type="button" onClick="window.location.href=\''.$sURL.'\'" value="'.Dict::S('UI:Button:Cancel').'"/>');
	$oP->add('&nbsp;&nbsp;');
	$oP->add('<input type="submit" value="'.Dict::S('UI:Button:Apply').'"/></p>');
	$oP->add('</form>');
	$oP->add('</fieldset>');

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
					$(this).attr('checked', true);
					$(this).trigger('change');
				}
			});
	}
EOF
);
	}

	//////////////////////////////////////////////////////////////////////////
	//
	// Shortcuts
	//
	//////////////////////////////////////////////////////////////////////////

	$oP->add('<fieldset><legend>'.Dict::S('Menu:MyShortcuts').'</legend>');
	//$oP->p(Dict::S('UI:Menu:MyShortcuts+'));
	$oBMSearch = new DBObjectSearch('Shortcut');
	$oBMSearch->AddCondition('user_id', UserRights::GetUserId(), '=');

	//$aExtraParams = array('menu' => false, 'toolkit_menu' => false, 'display_limit' => false, 'localize_values' => $bLocalize, 'zlist' => 'details');
	$aExtraParams = array();
	$oBlock = new DisplayBlock($oBMSearch, 'list', false, $aExtraParams);
	$oBlock->Display($oP, 'shortcut_list', array('view_link' => false, 'menu' => false, 'toolkit_menu' => false, 'selection_mode' => true, 'selection_type' => 'multiple', 'cssCount'=> '#shortcut_selection_count', 'table_id' => 'user_prefs_shortcuts'));
	$oP->add('<p>');

	$oSet = new DBObjectSet($oBMSearch);
	if ($oSet->Count() > 0)
	{
		$sButtons = '<img src="../images/tv-item-last.gif">';
		$sButtons .= '&nbsp;';
		$sButtons .= '<button id="shortcut_btn_rename">'.Dict::S('UI:Button:Rename').'</button>';
		$sButtons .= '&nbsp;';
		$sButtons .= '<button id="shortcut_btn_delete">'.Dict::S('UI:Button:Delete').'</button>';

		// Selection count updated by the pager, and used to enable buttons
		$oP->add('<input type="hidden" id="shortcut_selection_count"/>');
		$oP->add('</fieldset>');
	
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
		$('#shortcut_btn_rename').attr('disabled', 'disabled');
		$('#shortcut_btn_delete').attr('disabled', 'disabled');
	}
	else if (iCountSelected == 1)
	{
		$('#shortcut_btn_rename').removeAttr('disabled');
		$('#shortcut_btn_delete').removeAttr('disabled');
	}
	else
	{
		$('#shortcut_btn_rename').attr('disabled', 'disabled');
		$('#shortcut_btn_delete').removeAttr('disabled');
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

	//////////////////////////////////////////////////////////////////////////
	//
	// Footer
	//
	$oP->add('</div>');
	$oP->add_ready_script("$('#fav_page_length').bind('keyup change', function(){ ValidateOtherSettings(); })");
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

$sOperation = utils::ReadParam('operation', ''); 
	
try
{
	switch($sOperation)
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
		$oUser->DBUpdate();
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
		DisplayPreferences($oPage);
		break;
		
		case 'display':
		default:
		DisplayPreferences($oPage);
	}
	$oPage->output();
}
catch(CoreException $e)
{
	require_once(APPROOT.'/setup/setuppage.class.inc.php');
	$oP = new SetupPage(Dict::S('UI:PageTitle:FatalError'));
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
	$oP = new SetupPage(Dict::S('UI:PageTitle:FatalError'));
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
?>
