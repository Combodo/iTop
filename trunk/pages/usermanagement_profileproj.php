<?php
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
	$aDisplayConfig['user'] = array('label' => 'User', 'description' => 'User concerned by the projection');
	$aDisplayConfig['profile'] = array('label' => 'Profile', 'description' => 'Profile in which the projection is specified');
	foreach ($aDimensions as $iDimension => $oDimension)
	{
		$aDisplayConfig['dim'.$oDimension->GetKey()] = array('label' => $oDimension->GetName(), 'description' => $oDimension->Get('description'));
	}
	
	// Load users, and create a record per couple user/profile
	//
	$aDisplayData = array();
	$oUserSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_Users"));
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
				$oDimension->CheckProjectionSpec($aProPros[$iProfile][$iDimension]);
	
				$aValues = $aProPros[$iProfile][$iDimension]->ProjectUser($oUser);
				$sValues = implode(', ', $aValues);
				$aUserProfileProj['dim'.$oDimension->GetKey()] = htmlentities($sValues);
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
login_web_page::DoLogin(); // Check user rights and prompt if needed

// Display the menu on the left
$oContext = new UserContext();
$oAppContext = new ApplicationContext();
$iActiveNodeId = utils::ReadParam('menu', -1);
$currentOrganization = utils::ReadParam('org_id', 1);

$oPage = new iTopWebPage("iTop user management - profile projections", $currentOrganization);
$oPage->no_cache();

ComputeProjections($oPage);
$oPage->output();

?>
