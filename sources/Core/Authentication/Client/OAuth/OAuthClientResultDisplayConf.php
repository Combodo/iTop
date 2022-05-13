<?php
namespace Combodo\iTop\Core\Authentication\Client\OAuth;
use Combodo\iTop\Application\UI\Base\Component\Panel\Panel;
use Combodo\iTop\Application\UI\Base\Component\Panel\PanelUIBlockFactory;
use Dict;
use League\OAuth2\Client\Token\AccessToken;

class OAuthClientResultDisplayConf implements IOAuthClientResultDisplay
{
	public static function GetResultDisplayBlock()
	{
		$oConfResultPanel = new Panel(Dict::S('UI:OAuth:Wizard:ResultConf:Panel:Title'), [],Panel::DEFAULT_COLOR_SCHEME, 'ibo-oauth-wizard--conf--panel');
		$oConfResultPanel->AddCSSClass('ibo-oauth-wizard--result--panel');
		$oConfResultPanel->SetIsCollapsible(true);
		$oConfResultPanel->AddHtml('<p>'.Dict::S('UI:OAuth:Wizard:ResultConf:Panel:Description').'</p>');
		$oConfResultPanel->AddHtml('<pre><code id="ibo-oauth-wizard--conf--result"></code></pre>');
		return $oConfResultPanel;
	}

	public static function GetResultDisplayScript($sClientId, $sClientSecret, $sVendor, AccessToken $oAccessToken)
	{
		$sAccessToken = $oAccessToken->getToken();
		$sRefreshToken = $oAccessToken->getRefreshToken();
		$sConf = <<<EOF
'email_transport' => 'SMTP_OAuth',
'email_transport_smtp.oauth.provider' => '$sVendor',
'email_transport_smtp.oauth.client_id' => '$sClientId',
'email_transport_smtp.oauth.client_secret' => '$sClientSecret',
'email_transport_smtp.oauth.access_token' => '$sAccessToken',
'email_transport_smtp.oauth.refresh_token' => '$sRefreshToken',
EOF;
		$sConf = json_encode($sConf);
		
		return <<<JS
$('#ibo-oauth-wizard--conf--panel .ibo-panel--collapsible-toggler').click();
$('#ibo-oauth-wizard--conf--result').text($sConf);
JS;

	}

	public static function GetResultDisplayTemplate()
	{
		return 'DisplayConfig.html.twig';
	}
}