<?php
require_once('../application/application.inc.php');
require_once('../application/itopwebpage.class.inc.php');

require_once('../application/startup.inc.php');


function ComputeProjections($oPage, $sScope)
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
	$aDisplayConfig['class'] = array('label' => 'Class', 'description' => 'Class');
	$aDisplayConfig['object'] = array('label' => 'Object', 'description' => 'Projected object');
	foreach ($aDimensions as $iDimension => $oDimension)
	{
		$aDisplayConfig['dim'.$oDimension->GetKey()] = array('label' => $oDimension->GetName(), 'description' => $oDimension->Get('description'));
	}
	
	// Load objects
	//
	$aDisplayData = array();
	$oObjectSet = new DBObjectSet(DBObjectSearch::FromOQL($sScope));
	$sClass = $oObjectSet->GetClass();
	while ($oObject = $oObjectSet->Fetch())
	{
		$aObjectProj = array();
		$oObjectProj['class'] = $sClass;
		$oObjectProj['object'] = $oObject->GetName();
		foreach ($aDimensions as $iDimension => $oDimension)
		{
			// #@# to be moved, may be time consuming
			$oDimension->CheckProjectionSpec($aClassProjs[$sClass][$iDimension]);

			$aValues = $aClassProjs[$sClass][$iDimension]->ProjectObject($oObject);
			$sValues = implode(', ', $aValues);
			$oObjectProj['dim'.$oDimension->GetKey()] = htmlentities($sValues);
		}
	
		$aDisplayData[] = $oObjectProj;
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
$sScope = utils::ReadParam('scope', 'SELECT bizDevice');

$oPage = new iTopWebPage("iTop user management - class projections", $currentOrganization);
$oPage->no_cache();

ComputeProjections($oPage, $sScope);
$oPage->output();

?>
