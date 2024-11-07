<?php
// Copyright (C) 2010-2024 Combodo SAS
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>


/**
 * Authent Local
 * User authentication Module, password stored in the local database
 *
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


class UserLocalPasswordValidity
{
	/** @var bool */
	protected $m_bPasswordValidity;
	/** @var string|null */
	protected $m_sPasswordValidityMessage;

	/**
	 * UserLocalPasswordValidity constructor.
	 *
	 * @param bool $m_bPasswordValidity
	 * @param string $m_sPasswordValidityMessage
	 */
	public function __construct($m_bPasswordValidity, $m_sPasswordValidityMessage = null)
	{
		$this->m_bPasswordValidity = $m_bPasswordValidity;
		$this->m_sPasswordValidityMessage = $m_sPasswordValidityMessage;
	}

	/**
	 * @return bool
	 */
	public function isPasswordValid()
	{
		return $this->m_bPasswordValidity;
	}


	/**
	 * @return string
	 */
	public function getPasswordValidityMessage()
	{
		return $this->m_sPasswordValidityMessage;
	}
}

class UserLocal extends UserInternal
{
	const EXPIRE_CAN   = 'can_expire';
	const EXPIRE_NEVER = 'never_expire';
	const EXPIRE_FORCE = 'force_expire';
	const EXPIRE_ONE_TIME_PWD = 'otp_expire';

	/** @var UserLocalPasswordValidity|null */
	protected $m_oPasswordValidity = null;

	public static function Init()
	{
		$aParams = array
		(
			"category" => "addon/authentication,grant_by_profile,silo",
			"key_type" => "autoincrement",
			"name_attcode" => "login",
			"state_attcode" => "",
			"reconc_keys" => array('login'),
			"db_table" => "priv_user_local",
			"db_key_field" => "id",
			"db_finalclass_field" => "",
		);
		MetaModel::Init_Params($aParams);
		MetaModel::Init_InheritAttributes();

		MetaModel::Init_AddAttribute(new AttributeOneWayPassword("password", array("allowed_values"=>null, "sql"=>"pwd", "default_value"=>null, "is_null_allowed"=>false, "depends_on"=>array())));

		$sExpireEnum = implode(',', array(self::EXPIRE_CAN, self::EXPIRE_NEVER, self::EXPIRE_FORCE, self::EXPIRE_ONE_TIME_PWD));
		MetaModel::Init_AddAttribute(new AttributeEnum("expiration", array("allowed_values"=>new ValueSetEnum($sExpireEnum), "sql"=>"expiration", "default_value"=>self::EXPIRE_NEVER, "is_null_allowed"=>false, "depends_on"=>array())));
		MetaModel::Init_AddAttribute(new AttributeDate("password_renewed_date", array("allowed_values"=>null, "sql"=>"password_renewed_date", "default_value"=>"", "is_null_allowed"=>true, "depends_on"=>array())));

		// Display lists
		MetaModel::Init_SetZListItems('details',
			array(
				'col:col1' =>
					array(
						'fieldset:User:info' => array('contactid', 'org_id', 'email', 'login', 'password', 'language', 'status'),
					),
				'col:col2' =>
					array(
						'fieldset:User:profiles'                 => array('profile_list',),
						'fieldset:UserLocal:password:expiration' => array('expiration', 'password_renewed_date',),
					),
				'allowed_org_list',
				'log',
			)
		); // Attributes to be displayed for the complete details
		MetaModel::Init_SetZListItems('list', array('first_name', 'last_name', 'login', 'org_id')); // Attributes to be displayed for a list
		// Search criteria
		MetaModel::Init_SetZListItems('standard_search', array('login', 'contactid', 'status', 'org_id')); // Criteria of the std search form
	}

	public function CheckCredentials($sPassword)
	{
		$oPassword = $this->Get('password'); // ormPassword object
		// Cannot compare directly the values since they are hashed, so
		// Let's ask the password to compare the hashed values
		if ($oPassword->CheckPassword($sPassword))
		{
			return true;
		}
		return false;
	}

	public function TrustWebServerContext()
	{
		return true;
	}

	public function CanChangePassword()
	{
		if (MetaModel::GetConfig()->Get('demo_mode'))
		{
			return false;
		}
		if($this->Get('expiration') == self::EXPIRE_ONE_TIME_PWD)
		{
			return false;
		}
		return true;
	}

	public function ChangePassword($sOldPassword, $sNewPassword)
	{
		/** @var \ormPassword $oPassword */
		$oPassword = $this->Get('password');
		// Cannot compare directly the values since they are hashed, so
		// Let's ask the password to compare the hashed values
		if ($oPassword->CheckPassword($sOldPassword))
		{
			$this->SetPassword($sNewPassword);
			return $this->IsPasswordValid();
		}
		return false;
	}

	/**
	 * Use with care!
	 */
	public function SetPassword($sNewPassword)
	{
		$this->Set('password', $sNewPassword);
		$this->DBUpdate();
	}

	public function Set($sAttCode, $value)
	{
		$result = parent::Set($sAttCode, $value);

		if ('password' == $sAttCode)
		{
			$this->ValidatePassword($value);
		}

		return $result;
	}

	protected function OnUpdate()
	{
		parent::OnUpdate();

		$this->OnWrite();
	}

	protected function OnInsert()
	{
		parent::OnInsert();
		$sToday = date(\AttributeDate::GetInternalFormat());
		$this->Set('password_renewed_date', $sToday);

		$this->OnWrite();
	}

	protected function OnWrite()
	{
		if (array_key_exists('password_renewed_date', $this->ListChanges()))
		{
			return;
		}

		if (empty($this->m_oPasswordValidity))
		{
			//password unchanged
			if (is_null($this->Get('password_renewed_date')))
			{
				//initialize password_renewed_date with User creation date
				$sKey = $this->GetKey();
$sOql = <<<OQL
SELECT CMDBChangeOpCreate AS ccc
JOIN CMDBChange AS c ON ccc.change = c.id
WHERE ccc.objclass="UserLocal" AND ccc.objkey="$sKey"
OQL;
					$oCmdbChangeOpSearch = \DBObjectSearch::FromOQL($sOql);
					$oSet = new \DBObjectSet($oCmdbChangeOpSearch);
					$oCMDBChangeOpCreate = $oSet->Fetch();
					if (! is_null($oCMDBChangeOpCreate))
					{
						$oUserCreationDateTime = \DateTime::createFromFormat(AttributeDateTime::GetInternalFormat(), $oCMDBChangeOpCreate->Get('date'));
						$sCreationDate = $oUserCreationDateTime->format(\AttributeDate::GetInternalFormat());
						$this->Set('password_renewed_date', $sCreationDate);
					}
			}
			return;
		}

		$sNow = date(\AttributeDate::GetInternalFormat());
		$this->Set('password_renewed_date', $sNow);

		// Reset the "force" expiration flag when the user updates her/his own password!
		if ($this->IsCurrentUser())
		{
			if (($this->Get('expiration') == self::EXPIRE_FORCE))
			{
				$this->Set('expiration', self::EXPIRE_CAN);
			}
		}
	}

	public function IsPasswordValid()
	{
		if (ContextTag::Check(ContextTag::TAG_SETUP))
		{
			// during the setup, the admin account can have whatever password you want ...
			return true;
		}

		return (empty($this->m_oPasswordValidity)) || ($this->m_oPasswordValidity->isPasswordValid());
	}


	public function getPasswordValidityMessage()
	{
		if (ContextTag::Check(ContextTag::TAG_SETUP))
		{
			// during the setup, the admin account can have whatever password you want ...
			return null;
		}

		if (empty($this->m_oPasswordValidity))
		{
			return null;
		}

		return $this->m_oPasswordValidity->getPasswordValidityMessage();
	}

	/**
	 * set the $m_oPasswordValidity based on UserLocalPasswordValidator instances vote.
	 *
	 * @param string $proposedValue
	 * @param Config|null $config internal use (unit tests)
	 * @param null|UserLocalPasswordValidator[] $aValidatorCollection internal use (unit tests)
	 *
	 * @return void
	 */
	public function ValidatePassword($proposedValue, $config = null, $aValidatorCollection = null)
	{
		if (null == $config)
		{
			$config =  MetaModel::GetConfig();
		}

		//if the $proposedValue is an ormPassword, then it cannot be checked
		//this if is even more permissive as we can only check against strings
		if (!is_string($proposedValue) && !empty($proposedValue))
		{
			$this->m_oPasswordValidity = new UserLocalPasswordValidity(true);
			return;
		}

		if (null == $aValidatorCollection)
		{
			$aValidatorCollection = MetaModel::EnumPlugins('iModuleExtension', 'UserLocalPasswordValidator');
		}

		foreach ($aValidatorCollection as $oUserLocalPasswordValidator)
		{
			$this->m_oPasswordValidity = $oUserLocalPasswordValidator->ValidatePassword($proposedValue, $this, $config);

			if (!$this->m_oPasswordValidity->isPasswordValid())
			{
				return;
			}
		}
	}

	public function DoCheckToWrite()
	{
		if (! $this->IsPasswordValid())
		{
			$this->m_aCheckIssues[] = $this->m_oPasswordValidity->getPasswordValidityMessage();
		}

		// A User cannot force a one-time password on herself/himself
		if ($this->IsCurrentUser()) {
			if (array_key_exists('expiration', $this->ListChanges()) && ($this->Get('expiration') == self::EXPIRE_ONE_TIME_PWD)) {
				$this->m_aCheckIssues[] = Dict::S('Class:UserLocal/Error:OneTimePasswordChangeIsNotAllowed');
			}
		}
		parent::DoCheckToWrite();
	}

	/**
	 * Returns the set of flags (OPT_ATT_HIDDEN, OPT_ATT_READONLY, OPT_ATT_MANDATORY...)
	 * for the given attribute in the current state of the object
	 *
	 * @param $sAttCode string $sAttCode The code of the attribute
	 * @param $aReasons array To store the reasons why the attribute is read-only (info about the synchro replicas)
	 * @param $sTargetState string The target state in which to evaluate the flags, if empty the current state will be used
	 *
	 * @return integer Flags: the binary combination of the flags applicable to this attribute
	 * @throws \CoreException
	 */
	public function GetAttributeFlags($sAttCode, &$aReasons = array(), $sTargetState = '')
	{
		$iFlags = parent::GetAttributeFlags($sAttCode, $aReasons, $sTargetState);
		if (MetaModel::GetConfig()->Get('demo_mode'))
		{
			if (strpos('contactid,login,language,password,status,profile_list,allowed_org_list', $sAttCode) !== false)
			{
				// contactid and allowed_org_list are disabled to make sure the portal remains accessible
				$aReasons[] = 'Sorry, this attribute is read-only in the demonstration mode!';
				$iFlags |= OPT_ATT_READONLY;
			}
		}
		return $iFlags;
	}
}



interface UserLocalPasswordValidator extends iModuleExtension
{
	/**
	 * @param string $proposedValue
	 * @param UserLocal $oUserLocal
	 * @param Config $config
	 *
	 * @return UserLocalPasswordValidity
	 */
	public function ValidatePassword($proposedValue, UserLocal $oUserLocal, $config);
}

class UserPasswordPolicyRegex implements UserLocalPasswordValidator
{
	public function __construct()
	{
	}

	/**
	 * @param string $proposedValue
	 * @param UserLocal $oUserLocal
	 * @param Config $config
	 *
	 * @return UserLocalPasswordValidity
	 */
	public function ValidatePassword($proposedValue, UserLocal $oUserLocal, $config)
	{
		$sPattern = $config->GetModuleSetting('authent-local', 'password_validation.pattern');

		if ('' == $sPattern)
		{
			return new UserLocalPasswordValidity(true);
		}

		$isMatched = preg_match("/{$sPattern}/", $proposedValue);

		if ($isMatched === false)
		{
			return new UserLocalPasswordValidity(
				false,
				'Unknown error : Failed to check the password.'
			);
		}

		if ($isMatched === 1)
		{
			return new UserLocalPasswordValidity(true);
		}

		$sUserLanguage = Dict::GetUserLanguage();
		$customMessages = $config->GetModuleSetting('authent-local', 'password_validation.message', null);
		if (is_string($customMessages) )
		{
			$sMessage = $customMessages;
		}
		elseif (isset($customMessages) && array_key_exists($sUserLanguage, $customMessages))
		{
			$sMessage = $customMessages[$sUserLanguage];
		}
		elseif (isset($customMessages) && array_key_exists('EN US', $customMessages))
		{
			$sMessage = $customMessages['EN US'];
		}
		else
		{
			$sMessage = Dict::S('Error:UserLocalPasswordValidator:UserPasswordPolicyRegex:ValidationFailed');
		}

		return new UserLocalPasswordValidity(
			false,
			$sMessage
		);
	}
}
