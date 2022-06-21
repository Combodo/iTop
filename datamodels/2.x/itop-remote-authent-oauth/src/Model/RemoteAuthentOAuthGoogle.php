<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Core\Authentication\Client\OAuth\OAuthClientProviderAbstract;

class RemoteAuthentOAuthGoogle extends RemoteAuthentOAuth
{
	public static function Init()
	{
		$aParams = array
		(
			'category'            => 'cloud',
			'key_type'            => 'autoincrement',
			'name_attcode'        => ['provider','name'],
			'state_attcode'       => '',
			'reconc_keys'         => ['provider','name'],
			'db_table'            => 'priv_remote_authent_oauth_google',
			'db_key_field'        => 'id',
			'icon' => utils::GetAbsoluteUrlModulesRoot().'itop-remote-authent-oauth/assets/img/icons8-google.svg',
			'db_finalclass_field' => '',
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_SetZListItems('details', [
			0 => 'name',
			1 => 'description',
			2 => 'provider',
			3 => 'scope',
			4 => 'redirect_url',
			5 => 'client_id',
			6 => 'client_secret',
			7 => 'mailbox_list',
		]);
		MetaModel::Init_SetZListItems('standard_search', [
			0 => 'name',
			2 => 'provider',
		]);
		MetaModel::Init_SetZListItems('list', [
		]);
	}

	public function PrefillCreationForm(&$aContextParam)
	{
		$this->Set('provider', 'Google');
		$this->Set('scope', 'https://mail.google.com/');
		$this->Set('redirect_url', OAuthClientProviderAbstract::GetRedirectUri());

		parent::PrefillCreationForm($aContextParam);
	}

	public function GetDefaultMailServer()
	{
		return 'imap.gmail.com';
	}

	public function GetAttributeFlags($sAttCode, &$aReasons = array(), $sTargetState = '')
	{
		if ($sAttCode == 'provider' || $sAttCode == 'scope' || $sAttCode == 'redirect_url') {
			return OPT_ATT_READONLY;
		}

		return parent::GetAttributeFlags($sAttCode, $aReasons, $sTargetState);
	}
}