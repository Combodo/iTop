<?php
require_once('../application/utils.inc.php');
require_once('../application/itopwebpage.class.inc.php');
//require_once('../application/menunode.class.inc.php');
require_once('../application/applicationcontext.class.inc.php');
require_once('../business/data.samples.inc.php');
require_once('../core/data.generator.class.inc.php');

require_once('../application/startup.inc.php');

// Display the menu on the left
$oContext = new UserContext();
$oAppContext = new ApplicationContext();
$iActiveNodeId = utils::ReadParam('menu', -1);
$currentOrganization = utils::ReadParam('org_id', 1);
$operation = utils::ReadParam('operation', '');
if (!isset($_SERVER['PHP_AUTH_USER']))
{
    header('WWW-Authenticate: Basic realm="iTop"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Sorry, this page requires authentication. (No user)';
    echo "<pre>\n";
    echo "DEBUG: \$_SERVER\n";
    print_r($_SERVER);
    echo "</pre>\n";
    exit;
}
if (!UserRights::Login($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']))
{
    header('WWW-Authenticate: Basic realm="iTop"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Authentication failed !';
    exit;
}

$oPage = new iTopWebPage("iTop Data Generator", $currentOrganization);
$oPage->no_cache();

// From now on the context is limited to the the selected organization ??

// Retrieve the root node for the menu
$oSearchFilter = $oContext->NewFilter("menuNode");
$oSearchFilter->AddCondition('parent_id', 0, '=');
// There may be more criteria added later to have a specific menu based on the user's profile
$oSet = new CMDBObjectSet($oSearchFilter, array('rank' => true));
while ($oRootMenuNode = $oSet->Fetch())
{
	$oRootMenuNode->DisplayMenu($oPage, $oContext, $iActiveNodeId, null, $oAppContext->GetAsHash());
}
/**
 * The (ordered) list of classes for which to generate objects
 *
 * Each class in this list must implement a non-static Generate(cmdbDataGenerator $oGenerator) method
 */
//$aClassesToGenerate = array('bizOrganization' /* This one is special and must go first */, 'bizService', 'bizContact', 'bizPC', 'bizNetworkDevice');
$aClassesToGenerate = array('bizOrganization' /* This one is special and must go first */, 'bizLocation', 'bizPC', 'bizNetworkDevice', 'bizPerson', 'bizIncidentTicket', 'bizInfraGroup', 'bizInfraInfra');

/////////////////////////////////////////////////////////////////////////////////////
//  Actual actions of the page
/////////////////////////////////////////////////////////////////////////////////////

/**
 * Populate an organization with objects of each class listed in the (global) $aClassesToGenerate array
 *
 * @param WebPage $oPage The object used for the HTML output
 * @param cmdbGenerator $oGenerator The object used for the generation of the objects
 * @param string $sSize An enum specifying (roughly) how many objects of each class to create: one of 'small', 'medium', 'big', 'huge' or 'max'
 */
function PopulateOrganization(CMDBChange $oMyChange, WebPage $oPage, cmdbDataGenerator $oGenerator, $sSize = 'small')
{
	global $aClassesToGenerate;
	
	for ($i=1 /* skip the first one (i=0) which is the org itself */; $i<count($aClassesToGenerate); $i++)
	{
		switch($sSize)
		{
			case 'max':
				$nbObjects = 50000;
				break;
			case 'huge':
				$nbObjects = rand(1000,50000);
				break;
			case 'big':
				$nbObjects = rand(30,500);
				break;
			case 'medium':
				$nbObjects = rand(5,50);
				break;
			case 'small':
			default:
				$nbObjects = rand(2,20);
		}
		$sClass = $aClassesToGenerate[$i];
		for($j=0; $j<$nbObjects; $j++)
		{
			$oObject = MetaModel::NewObject($sClass);
			if (method_exists($oObject, 'Generate'))
			{
				$oObject->Generate($oGenerator);
				// By rom
				// $oObject->DBInsert();
				$oObject->DBInsertTracked($oMyChange);
				
				//$oObject->DisplayDetails($oPage);
			}
		}
		$oPage->p("$nbObjects $sClass objects created.");
	}
}

/**
 * Delete an organization and all the instances of 'Object' belonging to this organization
 *
 * @param WebPage $oPage The object used for the HTML output
 * @param string $sOrganizationCode The code (pkey) of the organization to delete
 */
function DeleteOrganization($oMyChange, $oPage, $sOrganizationCode)
{
	$oOrg = MetaModel::GetObject('bizOrganization', $sOrganizationCode);
	if ($oOrg == null)
	{
		$oPage->p("<strong>Organization '$sOrganizationCode' already deleted!!</strong>");
	}
	else
	{
		// Delete all the objects linked to this organization
		$oFilter = new CMDBSearchFilter('logRealObject');
		$oFilter->AddCondition('organization', $sOrganizationCode, '=');
		$oSet = new CMDBObjectSet($oFilter);
		$countDeleted = $oSet->Count();
		MetaModel::BulkDeleteTracked($oMyChange, $oFilter); // Should do a one by one delete to let the objects do their own cleanup !
		$oPage->p("<strong>$countDeleted object(s) deleted!!</strong>");
		
		$oOrg->DBDeleteTracked($oMyChange);
		$oPage->p("<strong>Ok, organization '$sOrganizationCode' deleted!</strong>");
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////////
//
//	M a i n   P r o g r a m
//
/////////////////////////////////////////////////////////////////////////////////////////////////

$operation = utils::ReadParam('operation', '');

$oPage->add("<div style=\"border:1px solid #ffffff; margin:0.5em;\">\n");
$oPage->add("<div style=\"padding:0.25em;text-align:center\">\n");

switch($operation)
{

	case 'specify_generate':
	// Display a form to specify what to generate
	$oPage->p("<strong>iTop Data Generator</strong>\n");
	$oPage->add("<form method=\"post\"\">\n");
	$oPage->add("Number of organizations to generate: \n");
	$oPage->add("<input name=\"org_count\" size=\"3\" value=\"\"> (max ".count($aCompanies).")&nbsp;\n");
	$oPage->add("Size of the organizations\n");
	$oPage->add("<select name=\"org_size\"\">\n");
	$oPage->add("<option value=\"small\">Small (1 - 20 contacts)</option>\n");
	$oPage->add("<option value=\"medium\">Medium (5 - 50 contacts)</option>\n");
	$oPage->add("<option value=\"big\">Big (30 - 500 contacts)</option>\n");
	$oPage->add("<option value=\"huge\">Huge (1000 - 50000 contacts)</option>\n");
	$oPage->add("<option value=\"max\">Max (50000 contacts)</option>\n");
	$oPage->add("</select>\n");
	$oPage->add("<input type=\"hidden\" name=\"operation\" value=\"generate\">\n");
	$oPage->add("<input type=\"submit\" value=\" Generate ! \">\n");
	$oPage->add("</form>\n");
	break;
	
	case 'generate':
	// perform the actual generation
	$iOrgCount = utils::ReadParam('org_count', 0);
	$sOrgSize = utils::ReadParam('org_size', 'small');
	// By rom
	$oMyChange = MetaModel::NewObject("CMDBChange");
	$oMyChange->Set("date", time());
	$oMyChange->Set("userinfo", "Made by data generator ($iOrgCount orgs of size '$sOrgSize')");
	$oMyChange->DBInsert();
	while($iOrgCount > 0)
	{
		set_time_limit(5*60); // let it run for 5 minutes for each organization
		$oGenerator = new cmdbDataGenerator();
		// Create the new organization
		$oOrg = MetaModel::NewObject('bizOrganization');
		$oOrg->Generate($oGenerator);

		// By rom
		// $oOrg->DBInsert();
		$oOrg->DBInsertTracked($oMyChange);
		$oGenerator->SetOrganizationId($oOrg->GetKey());

		$oPage->p("Organization '".$oOrg->GetKey()."' created\n");
		$oOrg->DisplayDetails($oPage);
		$oPage->add("<hr />\n");
		PopulateOrganization($oMyChange, $oPage, $oGenerator, $sOrgSize);
		$oPage->add("<hr />\n");
		unset($oGenerator);
		$iOrgCount--;
	}
	break;

	case 'specify_update':
	// Specify which organization to update
	$oPage->add("<form method=\"post\"\">\n");
	$oPage->add("Select the organization to update: \n");
	$oPage->add("<select name=\"org\"\">\n");
	$oSearchFilter = new CMDBSearchFilter("bizOrganization");
	$oSet = new CMDBObjectSet($oSearchFilter); // All organizations
	while($oOrg = $oSet->Fetch())
	{
		$oPage->add("<option value=\"".$oOrg->GetKey()."\">".$oOrg->Get('name')."</option>\n");
	}
	$oPage->add("</select>\n");
	$oPage->add("<input type=\"hidden\" name=\"operation\" value=\"update\">\n");
	$oPage->p("");
	$oPage->add("Quantity of of objects to add in each class: \n");
	$oPage->add("<select name=\"org_size\"\">\n");
	$oPage->add("<option value=\"small\">A few (1 - 20 objects)</option>\n");
	$oPage->add("<option value=\"medium\">Some (5 - 50 objects)</option>\n");
	$oPage->add("<option value=\"big\">Many (30 - 500 objects)</option>\n");
	$oPage->add("<option value=\"huge\">Too many (1000 - 50000 objects)</option>\n");
	$oPage->add("<option value=\"max\">Max (50000 objects)</option>\n");
	$oPage->add("</select>\n");
	$oPage->p("");
	$oPage->add("<input type=\"button\" value=\" << Back \" onClick=\"javascript:window.history.back()\">&nbsp;&nbsp;\n");
	$oPage->add("<input type=\"submit\" value=\" Update \">\n");
	$oPage->add("</form>\n");
	break;

	case 'update':
	// perform the actual update
	set_time_limit(5*60); // let it run for 5 minutes	
	$sOrganizationCode = utils::ReadParam('org', '');
	$sOrgSize = utils::ReadParam('org_size', 'small');
	if ($sOrganizationCode == '')
	{
		$oPage->p("<strong>Error: please specify an organization (org).</strong>");
	}
	else
	{
		// By rom
		$oMyChange = MetaModel::NewObject("CMDBChange");
		$oMyChange->Set("date", time());
		$oMyChange->Set("userinfo", "Made by data generator (update org '$sOrganizationCode', size '$sOrgSize')");
		$oMyChange->DBInsert();

		$oPage->p("<strong>Organization '$sOrganizationCode' updated.</strong>");
		$oGenerator = new cmdbDataGenerator($sOrganizationCode);
		PopulateOrganization($oMyChange, $oPage, $oGenerator, $sOrgSize);
	}
	break;

	case 'specify_delete':
	// Select an organization to be deleted
	$oPage->add("<form method=\"post\"\">\n");
	$oPage->add("Select the organization to delete: \n");
	$oPage->add("<select name=\"org\"\">\n");
	$oSearchFilter = new CMDBSearchFilter("bizOrganization");
	$oSet = new CMDBObjectSet($oSearchFilter); // All organizations
	while($oOrg = $oSet->Fetch())
	{
		$oPage->add("<option value=\"".$oOrg->GetKey()."\">".$oOrg->Get('name')."</option>\n");
	}
	$oPage->add("</select>\n");
	$oPage->add("<input type=\"hidden\" name=\"operation\" value=\"confirm_delete\">\n");
	$oPage->p("");
	$oPage->add("<input type=\"button\" value=\" << Back \" onClick=\"javascript:window.history.back()\">&nbsp;&nbsp;\n");
	$oPage->add("<input type=\"submit\" value=\" Delete! \">\n");
	$oPage->add("</form>\n");
	break;
			
	case 'confirm_delete':
	// confirm the dangerous action
	$sOrganizationCode = ReadParam('org', '');
	$oPage->p("<strong>iTop Data Generator</strong>\n");
	$oPage->p("<strong>Warning: you are about to delete the organization '$sOrganizationCode' and all its related objects</strong>\n");
	$oPage->add("<form method=\"post\"\">\n");
	$oPage->add("<input type=\"hidden\" name=\"org\" value=\"$sOrganizationCode\">\n");
	$oPage->add("<input type=\"hidden\" name=\"operation\" value=\"delete\">\n");
	$oPage->add("<input type=\"button\" value=\" << Back \" onClick=\"javascript:window.history.back()\">&nbsp;&nbsp;\n");
	$oPage->add("<input type=\"submit\" value=\" Delete them ! \">\n");
	$oPage->add("</form>\n");
	break;

	case 'delete':
	// perform the actual deletion
	$sOrganizationCode = ReadParam('org', '');
	if ($sOrganizationCode == '')
	{
		$oPage->p("<strong>Error: please specify an organization (org).</strong>");
	}
	else
	{
		// By rom
		$oMyChange = MetaModel::NewObject("CMDBChange");
		$oMyChange->Set("date", time());
		$oMyChange->Set("userinfo", "Made by data generator (delete org '$sOrganizationCode'");
		$oMyChange->DBInsert();

		$oPage->p("<strong>Deleting '$sOrganizationCode'</strong>\n");
		DeleteOrganization($oMyChange, $oPage, $sOrganizationCode);
	}
	break;

	// display the menu of actions
	case 'menu':
	default:
	$oPage->p("<strong>Data Generator Menu</strong>");
	$oPage->p("<a href=\"?operation=specify_generate\">Generate one or more organizations</a>");
	$oPage->p("<a href=\"?operation=specify_update\">Add more objects into an organization</a>");
	$oPage->p("<a href=\"?operation=specify_delete\">Delete an organization</a>");
}
$oPage->add("</div>\n");
$oPage->add("</div>\n");

$oPage->p("<a href=\"?operation=menu\">Return to the data generator menu</a>");

$oPage->output();
?>
