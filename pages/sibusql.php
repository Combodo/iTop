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

$oP = new iTopWebPage("iTop - Expression Evaluation", $currentOrganization);

// Main program
$sExpression = utils::ReadParam('expression', '');
$sEncoding = utils::ReadParam('encoding', 'oql');

try
{
	if ($sEncoding == 'crypted')
	{
		// Translate $sExpression into a oql expression
		$sClearText = base64_decode($sExpression);
		echo "<strong>FYI: '$sClearText'</strong><br/>\n";
		$oFilter = DBObjectSearch::unserialize($sExpression);
		$sExpression = $oFilter->ToOQL();
		exit;
	}
	else
	{
		// leave $sExpression as is
	}
	
	$oP->add('<form method="get">'."\n");
	$oP->add('Expression to evaluate:<br/>'."\n");
	$oP->add('<textarea cols="50" rows="20" name="expression">'.$sExpression.'</textarea>'."<p> Example:<br/>SELECT bizPerson AS B WHERE  B.name LIKE '%A%'</p>\n");
	$oP->add('<input type="submit" value=" Evaluate ">'."\n");
	$oP->add('</form>'."\n");
	
	if (!empty($sExpression))
	{
		$oFilter = DBObjectSearch::FromOQL($sExpression);
		if ($oFilter)
		{
			$oP->p('Query expression: '.$oFilter->ToOQL());
			$oP->p('Serialized filter: '.$oFilter->serialize());
			
			$oSet = new CMDBObjectSet($oFilter);
			$oP->p('The query returned '.$oSet->count().' results(s)');
			cmdbAbstractObject::DisplaySet($oP, $oSet);
		}
	}
}
catch(CoreException $e)
{
	$oP->p('<b>An error occured while running the query:</b>');
	$oP->p($e->getHtmlDesc());
}
catch(Exception $e)
{
	$oP->p('<b>An error occured while running the query:</b>');
	$oP->p($e->getMessage());
}

$oP->output();
?>
