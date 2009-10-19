<?php
require_once('../application/application.inc.php');
require_once('../application/itopwebpage.class.inc.php');

require_once('../application/startup.inc.php');

require_once('../application/loginwebpage.class.inc.php');
login_web_page::DoLogin(); // Check user rights and prompt if needed

$sOperation = utils::ReadParam('operation', 'menu');
$oContext = new UserContext();
$oAppContext = new ApplicationContext();
$iActiveNodeId = utils::ReadParam('menu', -1);
$currentOrganization = utils::ReadParam('org_id', '');

$oP = new iTopWebPage("iTop - Database backup & restore", $currentOrganization);

// Main program
switch($sOperation)
{
	case 'create':
	$oP->add('<form method="get">');
	$oP->add('<p style="text-align:center; font-family:Georgia, \'Times New Roman\', Times, serif; font-size:24px;">Creation of an archive for the business model: '.$sBizModel.'</p>');
	$oP->p('Title of the archive: <input type="text" name="title" size="40">');
	$oP->p('Description of this archive: <textarea name="description" rows="5" cols="40"></textarea>');
	$oP->p('Select the packages you want to include into this archive (When restoring the archive you will prompted to pick a package):');
	$oP->p('<input type="checkbox" checked name="full" value="1"> The full database (schema + data).');
	$oP->p('<input type="checkbox" checked name="full_schema" value="1"> Only the schema but of the complete database.');
	$oP->p('<input type="checkbox" checked name="biz" value="1"> The complete business model (all the tables used by the business model, schema + data).');
	$oP->p('<input type="checkbox" checked name="biz_schema" value="1"> Only the schema of the business model.');
	$oP->add('<input type="hidden" name="biz_model" value="'.$sBizModel.'">');
	$oP->add('<input type="hidden" name="operation" value="do_create">');
	$oP->add('<input type="submit" name="" value=" Create the archive ">');
	$oP->add($oAppContext->GetForForm());
	$oP->add('</form>');
	$oP->p('<a href="?operation=menu&biz_model='.$sBizModel.'">Back to menu</a>');
	break;
	
	case 'do_create':
	$sTitle = utils::ReadParam('title', 'Unknown archive');
	$sDescription = utils::ReadParam('description', 'No description provided for this archive.');
	$bfullDump = utils::ReadParam('full', false);
	$bfullSchemaDump = utils::ReadParam('full_schema', false);
	$bBizDump = utils::ReadParam('biz', false);
	$bBizSchemaDump = utils::ReadParam('biz_schema', false);
	$sArchiveFile = '../tmp/archive1.zip';

	$oArchive = new iTopArchive($sArchiveFile, iTopArchive::create);
	$oArchive->SetTitle($sTitle);
	$oArchive->SetDescription($sDescription);
	if ($bfullDump)
	{
		$oArchive->AddDatabaseDump("Full Database Dump", "Choose this option to completely reload your database. All current data will be deleted and replaced by the backup", "full-db.sql", 'itop', array(), false);
	}
	
	if ($bfullSchemaDump)
	{
		$oArchive->AddDatabaseDump("Full Schema Dump", "Choose this option to completely wipe out your database and start from an empty database", "full-schema.sql", 'itop', array(), true);
	}
	
	if ($bBizDump || $bBizSchemaDump)
	{
		// compute the list of the tables involved in the business model
		$aBizTables = array();
		foreach(MetaModel::GetClasses('bizmodel') as $sClass)
		{
			$sTable = MetaModel::DBGetTable($sClass);
			$aBizTables[$sTable] = $sTable;
		}
		unset($aBizTables['']);
		if ($bfullDump)
		{
			$oArchive->AddDatabaseDump("Full Business Model Dump", "Choose this option to completely reload the business model part of your database. All current business data will be deleted and replaced by the backup. Application data (like menus...) are preserved.", "biz-db.sql", 'itop', $aBizTables, false);
		}
		
		if ($bfullSchemaDump)
		{
			$oArchive->AddDatabaseDump("Full Business Model Schema Dump", "Choose this option to wipe out the business data and start from an empty database. All current business data will be deleted. Application data (like menus...) are preserved.", "biz-schema.sql", 'itop', $aBizTables, true);
		}
	}
	$oArchive->WriteCatalog();
	$oP->p("The archive '$sTitle' has been created in <a href=\"$sArchiveFile\">$sArchiveFile</a>.");			
	$oP->p('<a href="?operation=menu&biz_model='.$sBizModel.'&'.$oAppContext->GetForLink().'">Back to menu</a>');			
	break;

	case 'select_archive':
	$sArchivesDir = '../tmp';
	$oP->add('<form method="get">');
	$oP->add('<p style="text-align:center; font-family:Georgia, \'Times New Roman\', Times, serif; font-size:24px;">Importation of an archive</p>');
	$oP->p('Select the archive you want to import:');
	$aArchives = array();
	if ($handle = opendir($sArchivesDir))
	{
		while (false !== ($sFileName = readdir($handle)))
		{
			if (strtolower(substr($sFileName, -3, 3)) == 'zip')
			{
				$oArchive = new iTopArchive('../tmp/'.$sFileName, iTopArchive::read);
				if ($oArchive->IsValid())
				{
					$oArchive->ReadCatalog();
					$aArchives['../tmp/'.$sFileName] = $oArchive->GetTitle();
				}
			}
    	}
    	closedir($handle);
	}
	foreach($aArchives as $sFileName => $sTitle)
	{
		$oP->p('<input type="radio" name="archive_file" value="'.$sFileName.'">'.$sTitle);
	}
	$oP->add('<input type="hidden" name="biz_model" value="'.$sBizModel.'">');
	$oP->add('<input type="hidden" name="operation" value="select_package">');
	$oP->add('<input type="submit" name="" value=" Next >> ">');
	$oP->add($oAppContext->GetForForm());
	$oP->add('</form>');
	$oP->p("<small>(Archives are searched into the directory: $sArchivesDir.)</small>");
	$oP->p('<a href="?operation=menu&biz_model='.$sBizModel.'&'.$oAppContext->GetForLink().'">Cancel</a>');
	break;

	case 'select_package':
	$sArchiveFile = utils::ReadParam('archive_file', '');
	$oArchive = new iTopArchive($sArchiveFile, iTopArchive::read);
	$oArchive->ReadCatalog();
	$oP->add('<form method="post">');
	$oP->add('<p style="text-align:center; font-family:Georgia, \'Times New Roman\', Times, serif; font-size:24px;">Selection of a package inside '.$oArchive->GetTitle().'</p>');
	$oP->p('Select the package you want to apply:');
	$aPackages = $oArchive->GetPackages();
	foreach($aPackages as $sPackageName => $aPackage)
	{
		$oP->p('<input type="radio" name="package_name" value="'.$sPackageName.'">'.$aPackage['title']);
		$oP->p($aPackage['description']);
	}
	$oP->add('<input type="hidden" name="archive_file" value="'.$sArchiveFile.'">');
	$oP->add('<input type="hidden" name="biz_model" value="'.$sBizModel.'">');
	$oP->add('<input type="hidden" name="operation" value="import_package">');
	$oP->add('<input type="submit" name="" value=" Apply Package ! ">');
	$oP->add($oAppContext->GetForForm());
	$oP->add('</form>');
	$oP->p('<a href="?operation=menu&biz_model='.$sBizModel.'">Cancel</a>');
	break;

	case 'import_package':
	$sArchiveFile = utils::ReadParam('archive_file', '');
	$sPackageName = utils::ReadParam('package_name', '');
	$oArchive = new iTopArchive($sArchiveFile, iTopArchive::read);
	$oArchive->ReadCatalog();
	$oP->add('<p style="text-align:center; font-family:Georgia, \'Times New Roman\', Times, serif; font-size:24px;">Applying the package '.$sPackageName.'</p>');
	if($oArchive->ImportSQL($sPackageName))
	{
		$oP->p('Done.');
	}
	else
	{
		$oP->p('Sorry, an error occured while applying the package...');
	}
	$oP->p('<a href="?operation=select_package&biz_model='.$sBizModel.'&archive_file='.$sArchiveFile.'&'.$oAppContext->GetForLink().'">Apply another package from the same archive</a>');
	$oP->p('<a href="?operation=select_archive&biz_model='.$sBizModel.'&'.$oAppContext->GetForLink().'">Select another archive</a>');
	$oP->p('<a href="?operation=menu&biz_model='.$sBizModel.'&'.$oAppContext->GetForLink().'">Back to the menu</a>');
	break;
	
	case 'menu':
	default:
	$oP->add('<p style="text-align:center; font-family:Georgia, \'Times New Roman\', Times, serif; font-size:24px;">Database backup &amp; restore</p>');
	$oP->add('<p style="text-align:center;">Select one of the actions below:</p>');
	$oP->add('<p style="text-align:center;"><a href="?operation=create&biz_model='.$sBizModel.'&'.$oAppContext->GetForLink().'">Export the database to an archive</a></p>');
	$oP->add('<p style="text-align:center;"><a href="?operation=select_archive&biz_model='.$sBizModel.'&'.$oAppContext->GetForLink().'">Reload the database from an archive</a></p>');
}

$oP->output();
?>
