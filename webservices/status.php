<?php

require_once(__DIR__.'/../approot.inc.php');

//Include status functions
use Combodo\iTop\Application\Status\Status;

//Do check Status
try 
{
		new Status();
        $aResult = ['status' => STATUS_RUNNING, 'code' => RestResult::OK, 'message' => ''];
} 
catch (Exception $e)
{
        $iCode = (defined('\RestResult::INTERNAL_ERROR')) ? RestResult::INTERNAL_ERROR : 100;
        $aResult = ['status' => STATUS_ERROR, 'code' => $iCode, 'message' => $e->getMessage()];
        http_response_code(500);
}

//Set headers, based on webservices/rest.php
$sContentType = 'application/json';
header('Content-type: ' . $sContentType);
header('Access-Control-Allow-Origin: *');

//Output result
$sResponse = json_encode($aResult);
echo $sResponse;
