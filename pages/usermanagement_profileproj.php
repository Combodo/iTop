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
 * Specific to the addon 'user management by profile'
 * Was developed for testing purposes only 
 *
 * @author      Erwan Taloc <erwan.taloc@combodo.com>
 * @author      Romain Quetiez <romain.quetiez@combodo.com>
 * @author      Denis Flaven <denis.flaven@combodo.com>
 * @license     http://www.opensource.org/licenses/gpl-3.0.html LGPL
 */

require_once('../application/application.inc.php');
require_once('../application/itopwebpage.class.inc.php');

require_once('../application/startup.inc.php');


function ComputeProjections($oPage)
{
	// Load the profiles for a further usage
	//
	$aProfiles = array();
	$oProfileSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_Profiles"));
	while ($oProfile = $oProfileSet->Fetch())
	{
		$aProfiles[$oProfile->GetKey()] = $oProfile; 
	}
	
	// Load the dimensions for a further usage
	//
	$aDimensions = array();
	$oDimensionSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_Dimensions"));
	while ($oDimension = $oDimensionSet->Fetch())
	{
		$aDimensions[$oDimension->GetKey()] = $oDimension; 
	}
	
	// Load the profile projections for a further usage
	//
	$aProPro = array();
	$oProProSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_ProfileProjection"));
	while ($oProPro = $oProProSet->Fetch())
	{
		$aProPros[$oProPro->Get('profileid')][$oProPro->Get('dimensionid')] = $oProPro; 
	}
	
	// Setup display structure
	//
	$aDisplayConfig = array();
	$aDisplayConfig['user'] = array('label' => Dict::S('UI:UserManagement:User'), 'description' => Dict::S('UI:UserManagement:User+'));
	$aDisplayConfig['profile'] = array('label' => Dict::S('UI:UserManagement:Profile'), 'description' => Dict::S('UI:UserManagement:Profile+'));
	foreach ($aDimensions as $iDimension => $oDimension)
	{
		$aDisplayConfig['dim'.$oDimension->GetKey()] = array('label' => $oDimension->GetName(), 'description' => $oDimension->Get('description'));
	}
	
	// Load users, and create a record per couple user/profile
	//
	$aDisplayData = array();
	$oUserSet = new DBObjectSet(DBObjectSearch::FromOQL("User"));
	while ($oUser = $oUserSet->Fetch())
	{
		$oUserProfileSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_UserProfile WHERE userid = :user->id"), array(), array('user' => $oUser));
		while ($oUserProfile = $oUserProfileSet->Fetch())
		{
			$iProfile = $oUserProfile->Get('profileid');
			$oProfile = $aProfiles[$iProfile];
	
			$aUserProfileProj = array();
			$aUserProfileProj['user'] = $oUser->GetName();
			$aUserProfileProj['profile'] = $oProfile->GetName();
			foreach ($aDimensions as $iDimension => $oDimension)
			{
				// #@# to be moved, may be time consuming
				$oDimension->CheckProjectionSpec($aProPros[$iProfile][$iDimension], get_class($oUser));
	
				$aValues = $aProPros[$iProfile][$iDimension]->ProjectUser($oUser);
				if (is_null($aValues))
				{
					$sValues = htmlentities(Dict::S('UI:UserManagement:AnyObject'));
				}
				else
				{
					$sValues = implode(', ', $aValues);
				}
				$aUserProfileProj['dim'.$oDimension->GetKey()] = $sValues;
			}
		
			$aDisplayData[] = $aUserProfileProj;
		}
	}

	$oPage->table($aDisplayConfig, $aDisplayData);

//$oPage->SetCurrentTab('Attributes');
//$oPage->p("[<a href=\"?operation='list'\">All classes</a>]");
//$oPage->add("</ul>\n");

}


require_once('../application/loginwebpage.class.inc.php');
LoginWebPage::DoLogin(); // Check user rights and prompt if needed

// Display the menu on the left
$oContext = new UserContext();
$oAppContext = new ApplicationContext();
$iActiveNodeId = utils::ReadParam('menu', -1);
$currentOrganization = utils::ReadParam('org_id', 1);

$oPage = new iTopWebPage(Dict::S('UI:PageTitle:ProfileProjections'), $currentOrganization);
$oPage->no_cache();

ComputeProjections($oPage);
$oPage->output();

?>
