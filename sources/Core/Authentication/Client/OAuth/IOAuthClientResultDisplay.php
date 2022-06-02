<?php
namespace Combodo\iTop\Core\Authentication\Client\OAuth;
use League\OAuth2\Client\Token\AccessToken;

interface IOAuthClientResultDisplay{
	//public static function GetResultDisplayBlock();
	public static function GetResultDisplayScript($sClientId, $sClientSecret, $sVendor, AccessToken $oAccessToken);

	public static function GetResultDisplayTemplate();
	//public static function GetResultDisplayParams($sClientId, $sClientSecret, $sVendor, AccessToken $oAccessToken);
}