<?php
require_once('../application/application.inc.php');
require_once('../application/itopwebpage.class.inc.php');

require_once('../application/startup.inc.php');


function ComputeObjectProjections($oPage, $oObject)
{
	// Load the classes for a further usage
	//
	$aClasses = MetaModel::GetClasses();
	
	// Load the dimensions for a further usage
	//
	$aDimensions = array();
	$oDimensionSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_Dimensions"));
	while ($oDimension = $oDimensionSet->Fetch())
	{
		$aDimensions[$oDimension->GetKey()] = $oDimension; 
	}
	
	// Load the class projections for a further usage
	//
	$aClassProj = array();
	$oClassProjSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_ClassProjection"));
	while ($oClassProj = $oClassProjSet->Fetch())
	{
		$aClassProjs[$oClassProj->Get('class')][$oClassProj->Get('dimensionid')] = $oClassProj; 
	}
	
	// Setup display structure
	//
	$aDisplayConfig = array();
	foreach ($aDimensions as $iDimension => $oDimension)
	{
		$aDisplayConfig['dim'.$oDimension->GetKey()] = array('label' => $oDimension->GetName(), 'description' => $oDimension->Get('description'));
	}
	
	// Load objects
	//
	$aDisplayData = array();
	$sClass = get_class($oObject);
	$aObjectProj = array();
	foreach ($aDimensions as $iDimension => $oDimension)
	{
		// #@# to be moved, may be time consuming
		$oDimension->CheckProjectionSpec($aClassProjs[$sClass][$iDimension]);

		$aValues = $aClassProjs[$sClass][$iDimension]->ProjectObject($oObject);
		if (is_null($aValues))
		{
			$sValues = '<any>';
		}
		else
		{
			$sValues = implode(', ', $aValues);
		}
		$oObjectProj['dim'.$oDimension->GetKey()] = htmlentities($sValues);
	}

	$aDisplayData[] = $oObjectProj;

	$oPage->table($aDisplayConfig, $aDisplayData);
}


function ComputeUserProjections($oPage, $oUser)
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
	$aDisplayConfig['profile'] = array('label' => 'Profile', 'description' => 'Profile in which the projection is specified');
	foreach ($aDimensions as $iDimension => $oDimension)
	{
		$aDisplayConfig['dim'.$oDimension->GetKey()] = array('label' => $oDimension->GetName(), 'description' => $oDimension->Get('description'));
	}
	
	// Create a record per profile
	//
	$aDisplayData = array();
	$oUserProfileSet = new DBObjectSet(DBObjectSearch::FromOQL("SELECT URP_UserProfile WHERE userid = :user->id"), array(), array('user' => $oUser));
	while ($oUserProfile = $oUserProfileSet->Fetch())
	{
		$iProfile = $oUserProfile->Get('profileid');
		$oProfile = $aProfiles[$iProfile];

		$aUserProfileProj = array();
		$aUserProfileProj['profile'] = $oProfile->GetName();
		foreach ($aDimensions as $iDimension => $oDimension)
		{
			// #@# to be moved, may be time consuming
			$oDimension->CheckProjectionSpec($aProPros[$iProfile][$iDimension]);

			$aValues = $aProPros[$iProfile][$iDimension]->ProjectUser($oUser);
			if (is_null($aValues))
			{
				$sValues = '<any>';
			}
			else
			{
				$sValues = implode(', ', $aValues);
			}
			$aUserProfileProj['dim'.$oDimension->GetKey()] = htmlentities($sValues);
		}
	
		$aDisplayData[] = $aUserProfileProj;
	}

	$oPage->table($aDisplayConfig, $aDisplayData);
}


function ComputeUserRights($oPage, $oUser, $oObject)
{
	// Set the stage
	//
	$iUser = $oUser->GetKey();
	$sClass = get_class($oObject);
	$iPKey = $oObject->GetKey();
	$oInstances = DBObjectSet::FromArray($sClass, array($oObject));
	$aPermissions = array(
		UR_ALLOWED_NO => '<span style="background-color: #ffdddd;">UR_ALLOWED_NO</span>',
		UR_ALLOWED_YES => '<span style="background-color: #ddffdd;">UR_ALLOWED_YES</span>',
		UR_ALLOWED_DEPENDS => '<span style="">UR_ALLOWED_DEPENDS</span>',
	);
	$aActions = array(
		UR_ACTION_READ => 'Read',
		UR_ACTION_MODIFY => 'Modify',
		UR_ACTION_DELETE => 'Delete',
		UR_ACTION_BULK_READ => 'Bulk Read',
		UR_ACTION_BULK_MODIFY => 'Bulk Modify',
		UR_ACTION_BULK_DELETE => 'Bulk Delete',
	);
	$aAttributeActions = array(
		UR_ACTION_READ => 'Read',
		UR_ACTION_MODIFY => 'Modify',
		UR_ACTION_BULK_READ => 'Bulk Read',
		UR_ACTION_BULK_MODIFY => 'Bulk Modify',
	);

	// Determine allowed actions for the object
	//
	$aDisplayData = array();
	foreach($aActions as $iActionCode => $sActionDesc)
	{
		$iPermission = UserRights::IsActionAllowed($sClass, $iActionCode, $oInstances, $iUser);
		$aDisplayData[] = array(
			'action' => $sActionDesc,
			'permission' => $aPermissions[$iPermission],
		);
	}	
	$aDisplayConfig = array();
	$aDisplayConfig['action'] = array('label' => 'Action', 'description' => '');
	$aDisplayConfig['permission'] = array('label' => 'Permission', 'description' => '');
	$oPage->p('<h3>Actions</h3>');
	$oPage->table($aDisplayConfig, $aDisplayData);


	// Determine allowed actions for the object
	//
	$aDisplayData = array();
	foreach(MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
	{
		if (!$oAttDef->IsDirectField()) continue;

		foreach($aAttributeActions as $iActionCode => $sActionDesc)
		{
			$iPermission = UserRights::IsActionAllowedOnAttribute($sClass, $sAttCode, $iActionCode, $oInstances, $iUser);
			$aDisplayData[] = array(
				'attribute' => $sAttCode,
				'action' => $sActionDesc,
				'permission' => $aPermissions[$iPermission],
			);
		}
	}
	$oPage->p('<h3>Attributes</h3>');
	if (count($aDisplayData) > 0)
	{
		$aDisplayConfig = array();
		$aDisplayConfig['attribute'] = array('label' => 'Attribute', 'description' => '');
		$aDisplayConfig['action'] = array('label' => 'Action', 'description' => '');
		$aDisplayConfig['permission'] = array('label' => 'Permission', 'description' => '');
		$oPage->table($aDisplayConfig, $aDisplayData);
	}
	else
	{
		$oPage->p('<em>none</em>');
	}

	// Determine allowed stimuli
	//
	$aDisplayData = array();
	foreach(MetaModel::EnumStimuli($sClass) as $sStimulusCode => $oStimulus)
	{
		$iPermission = UserRights::IsStimulusAllowed($sClass, $sStimulusCode, $oInstances, $iUser);
		$aDisplayData[] = array(
			'stimulus' => $sStimulusCode,
			'permission' => $aPermissions[$iPermission],
		);
	}
	$oPage->p('<h3>Stimuli</h3>');
	if (count($aDisplayData) > 0)
	{
		$aDisplayConfig = array();
		$aDisplayConfig['stimulus'] = array('label' => 'Stimulus', 'description' => '');
		$aDisplayConfig['permission'] = array('label' => 'Permission', 'description' => '');
		$oPage->table($aDisplayConfig, $aDisplayData);
	}
	else
	{
		$oPage->p('<em>none</em>');
	}
}


require_once('../application/loginwebpage.class.inc.php');
login_web_page::DoLogin(); // Check user rights and prompt if needed

// Display the menu on the left
$oContext = new UserContext();
$oAppContext = new ApplicationContext();
$iActiveNodeId = utils::ReadParam('menu', -1);
$currentOrganization = utils::ReadParam('org_id', 1);
$iUser = utils::ReadParam('user_id', -1);
$sObjectClass = utils::ReadParam('object_class', '');
$iObjectId = utils::ReadParam('object_id', 0);

$oPage = new iTopWebPage("iTop user management - user status", $currentOrganization);
$oPage->no_cache();


if ($iUser == -1)
{
	$oPage->p('Missing parameter "user_id" - current user is '.UserRights::GetUserId());
}
else
{
	$oUser = MetaModel::GetObject('URP_Users', $iUser);

	$oPage->p('<h2>Projections for user '.$oUser->GetName().'</h2>');
	ComputeUserProjections($oPage, $oUser);

	if (strlen($sObjectClass) != 0)
	{
		$oObject = MetaModel::GetObject($sObjectClass, $iObjectId);

		$oPage->p('<h2>Projections for object '.$oObject->GetName().'</h2>');
		ComputeObjectProjections($oPage, $oObject);

		$oPage->p('<h2>Resulting rights</h2>');
		ComputeUserRights($oPage, $oUser, $oObject);
	}
}

$oPage->output();

?>
