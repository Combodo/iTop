<?php

// Include status functions
// Important: We can't use the APPROOT constant here as the current script will be executed via the PHP exec() function which won't have it loaded yet.
require_once __DIR__.'/../../../../sources/application/status/status.inc.php';

// Do check Status
try 
{
        \Combodo\iTop\Application\Status\StatusStartup();
        $aResult = array('status' => STATUS_RUNNING, 'code' => \RestResult::OK, 'message' => '');
} 
catch (\Exception $e) 
{
        $iCode = (defined('\RestResult::INTERNAL_ERROR')) ? \RestResult::INTERNAL_ERROR : 100;
        $aResult = array('status' => STATUS_ERROR, 'code' => $iCode, 'message' => $e->getMessage());
        http_response_code(500);
}

//Set headers, based on webservices/rest.php
$sContentType = 'application/json';
header('Content-type: ' . $sContentType);
header('Access-Control-Allow-Origin: *');

//Output result
$sResponse = json_encode($aResult);
echo $sResponse;
