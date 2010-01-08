<?php

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

	public function __construct($aConditions)
	{
		$this->conditions = $aConditions;
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


class SOAPResultData
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
	public $values; // array of SOAPResultData

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

$aSOAPMapping = array(
	'SearchCondition' => 'SOAPSearchCondition',
	'ExternalKeySearch' => 'SOAPExternalKeySearch',
	'AttributeValue' => 'SOAPAttributeValue',
	'LinkCreationSpec' => 'SOAPLinkCreationSpec',
	'LogMessage' => 'SOAPLogMessage',
	'ResultLog' => 'SOAPResultLog',
	'ResultData' => 'SOAPResultData',
	'ResultMessage' => 'SOAPResultMessage',
	'Result' => 'SOAPResult',
);


?>
