<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * Exception management
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */



class CoreException extends Exception
{
	/**
	 * CoreException constructor.
	 *
	 * @param string $sIssue error message
	 * @param array|null $aContextData key/value array, value MUST implements _toString
	 * @param string $sImpact
	 * @param Exception|null $oPrevious
	 */
	public function __construct($sIssue, $aContextData = null, $sImpact = '', $oPrevious = null)
	{
		$this->m_sIssue = $sIssue;
		$this->m_sImpact = $sImpact;

		if (is_array($aContextData)) {
			$this->m_aContextData = $aContextData;
		} else {
			$this->m_aContextData = [];
		}

		$sMessage = $sIssue;
		if (!empty($sImpact)) {
			$sMessage .= "($sImpact)";
		}
		if (count($this->m_aContextData) > 0) {
			$sMessage .= ": ";
			$aContextItems = array();
			foreach ($this->m_aContextData as $sKey => $value) {
				if (is_array($value)) {
					$aPairs = array();
					foreach ($value as $key => $val) {
						if (is_array($val)) {
							$aPairs[] = $key.'=>('.implode(', ', $val).')';
						} else {
							$aPairs[] = $key.'=>'.$val;
						}
					}
					$sValue = '{'.implode('; ', $aPairs).'}';
				}
				else
				{
					$sValue = $value;
				}
				$aContextItems[] = "$sKey = $sValue";
			}
			$sMessage .= implode(', ', $aContextItems);
		}
		parent::__construct($sMessage, 0, $oPrevious);
	}

	/**
	 * @return string code and message for log purposes
	 */
	public function getInfoLog()
	{
		return 'error_code='.$this->getCode().', message="'.$this->getMessage().'"';
	}
	public function getHtmlDesc($sHighlightHtmlBegin = '<b>', $sHighlightHtmlEnd = '</b>')
	{
		return $this->getMessage();
	}

	/**
	 * getTraceAsString() cannot be overrided and it is limited as only current exception stack is returned.
	 * we need stack of all previous exceptions
	 * @uses __tostring() already does the work.
	 * @since 2.7.2/ 2.8.0
	 */
	public function getFullStackTraceAsString(){
		return "" . $this;
	}

	public function getTraceAsHtml()
	{
		$aBackTrace = $this->getTrace();
		return MyHelpers::get_callstack_html(0, $this->getTrace());
		// return "<pre>\n".$this->getTraceAsString()."</pre>\n";
	}

	public function addInfo($sKey, $value)
	{
		$this->m_aContextData[$sKey] = $value;
	}

	public function getIssue()
	{
		return $this->m_sIssue;
	}
	public function getImpact()
	{
		return $this->m_sImpact;
	}
	public function getContextData()
	{
		return $this->m_aContextData;
	}
}

/**
 * Class CoreCannotSaveObjectException
 *
 * Specialized exception to raise if {@link DBObject::CheckToWrite()} fails, which allow easy data retrieval
 *
 * @see \DBObject::DBInsertNoReload()
 * @see \DBObject::DBUpdate()
 *
 * @since 2.6.0 N°659 uniqueness constraint
 */
class CoreCannotSaveObjectException extends CoreException
{
	/** @var string[] */
	private $aIssues;
	/** @var int */
	private $iObjectId;
	/** @var string */
	private $sObjectClass;

	/**
	 * CoreCannotSaveObjectException constructor.
	 *
	 * @param array $aContextData containing at least those keys : issues, id, class
	 */
	public function __construct($aContextData, $oPrevious = null)
	{
		$this->aIssues = $aContextData['issues'];
		$this->iObjectId = $aContextData['id'];
		$this->sObjectClass = $aContextData['class'];

		$sIssues = implode(', ', $this->aIssues);
		parent::__construct($sIssues, $aContextData, '', $oPrevious);
	}

	/**
	 * @return string
	 */
	public function getHtmlMessage()
	{
		$sTitle = Dict::S('UI:Error:SaveFailed');
		$sContent = "<span><strong>".utils::HtmlEntities($sTitle)."</strong></span>";

		if (count($this->aIssues) == 1) {
			$sIssue = reset($this->aIssues);
			$sContent .= " <span>".utils::HtmlEntities($sIssue)."</span>";
		} else {
			$sContent .= '<ul>';
			foreach ($this->aIssues as $sError) {
				$sContent .= "<li>".utils::HtmlEntities($sError)."</li>";
			}
			$sContent .= '</ul>';
		}

		return $sContent;
	}

	public function getIssues()
	{
		return $this->aIssues;
	}

	public function getObjectId()
	{
		return $this->iObjectId;
	}

	public function getObjectClass()
	{
		return $this->sObjectClass;
	}
}

/**
 * @since 2.7.0 N°2555
 */
class CorePortalInvalidActionRuleException extends CoreException
{

}

/**
 * @since 2.7.0 N°2555
 */
class CoreOqlException extends CoreException
{

}

/**
 * @since 2.7.0 N°2555
 */
class CoreOqlMultipleResultsForbiddenException extends CoreOqlException
{

}

class CoreWarning extends CoreException
{
}

class CoreUnexpectedValue extends CoreException
{
}

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

class SecurityException extends CoreException
{
}

/**
 * Thrown when querying on an object that exists in the database but is archived
 *
 * @see N.1108
 * @since 2.5.1
 */
class ArchivedObjectException extends CoreException
{
}

/**
 * A parameter stored in the {@link Config} is invalid
 *
 * @since 2.7.0
 */
class InvalidConfigParamException extends CoreException
{
}


/**
 * Throwned when the password is not valid
 *
 * @since 2.7.0
 */
class InvalidPasswordAttributeOneWayPassword extends CoreException
{
}