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
	$aDisplayConfig['class'] = array('label' => Dict::S('UI:UserManagement:Class'), 'description' => Dict::S('UI:UserManagement:Class+'));
	$aDisplayConfig['object'] = array('label' => Dict::S('UI:UserManagement:ProjectedObject'), 'description' => Dict::S('UI:UserManagement:ProjectedObject+'));
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
			$oDimension->CheckProjectionSpec($aClassProjs[$sClass][$iDimension], $sClass);

			$aValues = $aClassProjs[$sClass][$iDimension]->ProjectObject($oObject);
			if (is_null($aValues))
			{
				$sValues = htmlentities(Dict::S('UI:UserManagement:AnyObject'));
			}
			else
			{
				$sValues = implode(', ', $aValues);
			}
			$oObjectProj['dim'.$oDimension->GetKey()] = $sValues;
		}
	
		$aDisplayData[] = $oObjectProj;
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
$sScope = utils::ReadParam('scope', 'SELECT bizDevice');

$oPage = new iTopWebPage(Dict::S('UI:PageTitle:ClassProjections'), $currentOrganization);
$oPage->no_cache();

ComputeProjections($oPage, $sScope);
$oPage->output();

?>
