<?php

/**
 * @since 2.7.10 3.0.4 3.1.1 3.2.0 N°6458 object creation
 */
class InvalidExternalKeyValueException extends CoreUnexpectedValue
{
	private const ENUM_PARAMS_OBJECT = 'current_object';
	private const ENUM_PARAMS_ATTCODE = 'attcode';
	private const ENUM_PARAMS_ATTVALUE = 'attvalue';
	private const ENUM_PARAMS_USER = 'current_user';

	public function __construct($oObject, $sAttCode, $aContextData = null, $oPrevious = null)
	{
		$aContextData[self::ENUM_PARAMS_OBJECT] = get_class($oObject) . '::' . $oObject->GetKey();
		$aContextData[self::ENUM_PARAMS_ATTCODE] = $sAttCode;
		$aContextData[self::ENUM_PARAMS_ATTVALUE] = $oObject->Get($sAttCode);

		$oCurrentUser = UserRights::GetUserObject();
		if (false === is_null($oCurrentUser)) {
			$aContextData[self::ENUM_PARAMS_USER] = get_class($oCurrentUser) . '::' . $oCurrentUser->GetKey();
		}

		parent::__construct('Attribute pointing to an object that is either non existing or not readable by the current user', $aContextData, '', $oPrevious);
	}

	public function GetAttCode(): string
	{
		return $this->getContextData()[self::ENUM_PARAMS_ATTCODE];
	}

	public function GetAttValue(): string
	{
		return $this->getContextData()[self::ENUM_PARAMS_ATTVALUE];
	}
}
