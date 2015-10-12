<?php
/**
 * Minimal file (with all the needed includes) to check the validity of an OQL by verifying:
 * - The syntax (of the OQL query string)
 * - The consistency with a given data model (represented by an instance of ModelReflection)
 * 
 * Usage:
 * 
 * require_once(APPROOT.'core/oql/check_oql.php');
 * 
 * $sOQL = "SELECT Zerver WHERE status = 'production'";
 * $oModelReflection = new ModelReflectionRuntime();
 * $aResults = CheckOQL($sOQL, $oModelReflection);
 * if ($aResults['status'] == 'error')
 * {
 *     echo "The query '$sOQL' is not a valid query. Reason: {$aResults['message']}";
 * }
 * else
 * {
 *     echo "Ok, '$sOQL' is a valid query";
 * }
 */
class CoreException extends Exception
{

}

require_once(__DIR__.'/expression.class.inc.php');
require_once(__DIR__.'/oqlquery.class.inc.php');
require_once(__DIR__.'/oqlexception.class.inc.php');
require_once(__DIR__.'/oql-parser.php');
require_once(__DIR__.'/oql-lexer.php');
require_once(__DIR__.'/oqlinterpreter.class.inc.php');

function CheckOQL($sOQL, ModelReflection $oModelReflection)
{
	$aRes = array('status' => 'ok', 'message' => '');
	try
	{
		$oOql = new OqlInterpreter($sOQL);
		$oOqlQuery = $oOql->ParseQuery(); // Exceptions thrown in case of issue
		$oOqlQuery->Check($oModelReflection,$sOQL); // Exceptions thrown in case of issue
	}
	catch(Exception $e)
	{
		$aRes['status'] = 'error';
		$aRes['message'] = $e->getMessage();
	}
	return $aRes;
}