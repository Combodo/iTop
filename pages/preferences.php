<?php
// Copyright (C) 2010 Combodo SARL
//
//   This program is free software; you can redistribute it and/or modify
//   it under the terms of the GNU General Public License as published by
//   the Free Software Foundation; version 3 of the License.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of the GNU General Public License
//   along with this program; if not, write to the Free Software
//   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

/**
 * User preferences page
 * Displays / edit some user preferences
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
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

	// Favorite organizations: the organizations listed in the drop-down menu
	$sOQL = ApplicationMenu::GetFavoriteSiloQuery();
	$oFilter = DBObjectSearch::FromOQL($sOQL);
	$oBlock = new DisplayBlock($oFilter, 'list', false);

	$oP->add('<div class="page_header"><h1><img style="vertical-align:middle" src="../images/preferences.png"/>&nbsp;'.Dict::S('UI:Preferences')."</h1></div>\n");
	$oP->add('<div id="user_prefs" style="max-width:800px; min-width:400px;">');
	$oP->add('<fieldset><legend>'.Dict::S('UI:FavoriteOrganizations').'</legend>');
	$oP->p(Dict::S('UI:FavoriteOrganizations+'));
	$oP->add('<form method="post">');	
	$oBlock->Display($oP, 1, array('menu' => false, 'selection_mode' => true, 'selection_type' => 'multiple', 'cssCount'=> '.selectedCount'));
	$oP->add($oAppContext->GetForForm());
	$oP->add('<input type="hidden" name="operation" value="apply"/>');
	$oP->add('</fieldset>');
	$sURL = utils::GetAbsoluteUrlAppRoot().'pages/UI.php';
	$oP->add('<p><input type="button" onClick="window.location.href=\''.$sURL.'\'" value="'.Dict::S('UI:Button:Cancel').'"/>');
	$oP->add('&nbsp;&nbsp;');
	$oP->add('<input type="submit" value="'.Dict::S('UI:Button:Apply').'"/></p>');
	$oP->add('</form>');
	$oP->add('</div>');

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
		CheckAll('#user_prefs .listResults :checkbox:not(:disabled)', true);
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
	}}

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
		
		case 'display':
		default:
		DisplayPreferences($oPage);
	}
	$oPage->output();
}
catch(CoreException $e)
{
	require_once(APPROOT.'/setup/setuppage.class.inc.php');
	$oP = new SetupWebPage(Dict::S('UI:PageTitle:FatalError'));
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
	$oP = new SetupWebPage(Dict::S('UI:PageTitle:FatalError'));
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
