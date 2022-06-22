<?php
/**
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Core\Authentication\Client\OAuth\OAuthClientProviderAbstract;

class OAuthClientGoogle extends OAuthClient
{
	public static function Init()
	{
		$aParams = array
		(
			'category'            => 'cloud',
			'key_type'            => 'autoincrement',
			'name_attcode'        => ['name', 'scope'],
			'state_attcode'       => '',
			'reconc_keys'         => ['provider', 'name'],
			'db_table'            => 'priv_oauth_client_google',
			'db_key_field'        => 'id',
			'icon'                => utils::GetAbsoluteUrlModulesRoot().'itop-oauth-client/assets/img/icons8-google.svg',
			'db_finalclass_field' => '',
			'uniqueness_rules'    => [
				'Username for scope' =>
					[
						'attributes'  => ['name', 'scope'],
						'filter'      => null,
						'disabled'    => false,
						'is_blocking' => true,
					],
				'OAuth Server' =>
					[
						'attributes'  => ['provider', 'scope', 'client_id', 'client_secret'],
						'filter'      => null,
						'disabled'    => false,
						'is_blocking' => true,
					],
			],
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();
		MetaModel::Init_AddAttribute(new AttributeEnum('scope', [
			'allowed_values'        => new ValueSetEnum('EMail'),
			'display_style'         => 'list',
			'sql'                   => 'scope',
			'default_value'         => 'EMail',
			'is_null_allowed'       => false,
			'depends_on'            => [],
			'always_load_in_tables' => true,
		]));

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
		$this->Set('scope', 'EMail');
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

	public function GetInitialStateAttributeFlags($sAttCode, &$aReasons = array())
	{
		if ($sAttCode == 'provider' || $sAttCode == 'scope' || $sAttCode == 'redirect_url') {
			return OPT_ATT_READONLY;
		}

		return parent::GetInitialStateAttributeFlags($sAttCode, $aReasons);
	}

	/**
	 * Compute read-only values
	 *
	 * @return void
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 */
	public function ComputeValues()
	{
		parent::ComputeValues();
		if (empty($this->Get('provider'))) {
			$this->Set('provider', 'Google');
		}
		if (empty($this->Get('redirect_url'))) {
			$this->Set('redirect_url', OAuthClientProviderAbstract::GetRedirectUri());
		}
		if (empty($this->Get('scope'))) {
			$this->Set('scope', 'EMail');
		}
	}

	public function GetScope()
	{
		return 'https://mail.google.com/';
	}

	public function AfterInsert()
	{
		parent::AfterInsert();
		$sClass = get_class($this);
		$sId = $this->GetKey();
		cmdbAbstractObject::SetSessionMessage(
			$sClass,
			$sId,
			"$sClass:$sId:OAuthClientCreated",
			Dict::S('itop-oauth-client:Message:OAuthClientCreated'),
			'info',
			100,
			true
		);
	}
}