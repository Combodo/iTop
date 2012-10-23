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
 * Declarations required for the WSDL
 *
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


// Note: the attributes must have the same names (case sensitive) as in the WSDL specification
//

class SOAPSearchCondition
{
	public $attcode; // string
	public $value; // mixed

	public function __construct($sAttCode, $value)
	{
		$this->attcode = $sAttCode;
		$this->value = $value;
	}
}


class SOAPExternalKeySearch
{
	public $conditions; // array of SOAPSearchCondition

	public function __construct($aConditions = null)
	{
		$this->conditions = $aConditions;
	}

	public function IsVoid()
	{
		if (is_null($this->conditions)) return true;
		if (count($this->conditions) == 0) return true;
	}
}


class SOAPAttributeValue
{
	public $attcode; // string
	public $value; // mixed

	public function __construct($sAttCode, $value)
	{
		$this->attcode = $sAttCode;
		$this->value = $value;
	}
}


class SOAPLinkCreationSpec
{
	public $class;
	public $conditions; // array of SOAPSearchCondition
	public $attributes; // array of SOAPAttributeValue

	public function __construct($sClass, $aConditions, $aAttributes)
	{
		$this->class = $sClass;
		$this->conditions = $aConditions;
		$this->attributes = $aAttributes;
	}
}


class SOAPLogMessage
{
	public $text; // string

	public function __construct($sText)
	{
		$this->text = $sText;
	}
}


class SOAPResultLog
{
	public $messages; // array of SOAPLogMessage

	public function __construct($aMessages)
	{
		$this->messages = $aMessages;
	}
}


class SOAPKeyValue
{
	public $key; // string
	public $value; // string

	public function __construct($sKey, $sValue)
	{
		$this->key = $sKey;
		$this->value = $sValue;
	}
}

class SOAPResultMessage
{
	public $label; // string
	public $values; // array of SOAPKeyValue

	public function __construct($sLabel, $aValues)
	{
		$this->label = $sLabel;
		$this->values = $aValues;
	}
}


class SOAPResult
{
	public $status; // boolean
	public $result; // array of SOAPResultMessage
	public $errors; // array of SOAPResultLog
	public $warnings; // array of SOAPResultLog
	public $infos; // array of SOAPResultLog

	public function __construct($bStatus, $aResult, $aErrors, $aWarnings, $aInfos)
	{
		$this->status = $bStatus;
		$this->result = $aResult;
		$this->errors = $aErrors;
		$this->warnings = $aWarnings;
		$this->infos = $aInfos;
	}
}

class SOAPSimpleResult
{
	public $status; // boolean
	public $message; // string

	public function __construct($bStatus, $sMessage)
	{
		$this->status = $bStatus;
		$this->message = $sMessage;
	}
}


class SOAPMapping
{
	static function GetMapping()
	{
		$aSOAPMapping = array(
			'SearchCondition' => 'SOAPSearchCondition',
			'ExternalKeySearch' => 'SOAPExternalKeySearch',
			'AttributeValue' => 'SOAPAttributeValue',
			'LinkCreationSpec' => 'SOAPLinkCreationSpec',
			'KeyValue' => 'SOAPKeyValue',
			'LogMessage' => 'SOAPLogMessage',
			'ResultLog' => 'SOAPResultLog',
			'ResultData' => 'SOAPKeyValue',
			'ResultMessage' => 'SOAPResultMessage',
			'Result' => 'SOAPResult',
			'SimpleResult' => 'SOAPSimpleResult',
		);
		return $aSOAPMapping;
	}
}

?>
