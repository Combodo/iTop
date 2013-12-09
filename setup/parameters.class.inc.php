<?php
class InvalidParameterException extends Exception
{
}

abstract class Parameters
{
	protected $aData = null;
	
	public function __construct()
	{
		$this->aData = null;
	}

	public function Get($sCode, $default = '')
	{
		if (array_key_exists($sCode, $this->aData))
		{
			return $this->aData[$sCode];
		}
		return $default;
	}

	public function ToXML(DOMNode $oRoot, $data = null, $sNodeName = null)
	{
		if ($data === null)
		{
			$data = $this->aData;
		}
				
		if (is_array($data))
		{
			if ($oRoot instanceof DOMDocument)
			{
				$oNode = $oRoot->createElement($sNodeName);
			}
			else
			{
				$oNode = $oRoot->ownerDocument->createElement($sNodeName);
			}
			$oRoot->appendChild($oNode);

			$aKeys = array_keys($data);
			$bNumericKeys = true;
			foreach($aKeys as $idx => $subkey)
			{
				if(((int)$subkey) !== $subkey)
				{
					$bNumericKeys = false;
					break;
				}
			}
			if ($bNumericKeys)
			{
				$oNode->setAttribute("type", "array");
				foreach($data as $key => $value)
				{
					$this->ToXML($oNode, $value , 'item');
				}
			}
			else
			{
				foreach($data as $key => $value)
				{
					$this->ToXML($oNode, $value , $key);
				}
			}
		}
		else
		{
			$oNode = $oRoot->ownerDocument->createElement($sNodeName);
			$oRoot->appendChild($oNode);
			$oTextNode = $oRoot->ownerDocument->createTextNode($data);
			$oNode->appendChild($oTextNode);
		}
		return $oNode;
	}
}

class PHPParameters extends Parameters
{
	public function LoadFromHash($aData)
	{
		$this->aData = $aData;
	}
	
	public function LoadFromFile($sParametersFile)
	{
		if ($this->aData == null)
		{
			require_once($sParametersFile);
			$this->aData = $ITOP_PARAMS;
		}
	}
}

class XMLParameters extends Parameters
{
	protected $sParametersFile;
	
	public function __construct($sParametersFile)
	{
		parent::__construct();
		$this->LoadFromFile($sParametersFile);
	}
	
	public function LoadFromFile($sParametersFile)
	{
		$this->sParametersFile = $sParametersFile;
		if ($this->aData == null)
		{
			libxml_use_internal_errors(true);
			$oXML = @simplexml_load_file($this->sParametersFile);
			if (!$oXML)
			{
				$aMessage = array();
				foreach(libxml_get_errors() as $oError)
				{
					$aMessage[] = "(line: {$oError->line}) ".$oError->message; // Beware: $oError->columns sometimes returns wrong (misleading) value
				}
				libxml_clear_errors();
				throw new InvalidParameterException("Invalid Parameters file '{$this->sParametersFile}': ".implode(' ', $aMessage));
			}
			
			$this->aData = array();
			foreach($oXML as $key => $oElement)
			{
				$this->aData[(string)$key] = $this->ReadElement($oElement);
			}
		}
	}
	
	protected function ReadElement(SimpleXMLElement $oElement)
	{
		$sDefaultNodeType = (count($oElement->children()) > 0) ? 'hash' : 'string';
		$sNodeType = $this->GetAttribute('type', $oElement, $sDefaultNodeType);
		switch($sNodeType)
		{
			case 'array':
			$value = array();
			// Treat the current element as zero based array, child tag names are NOT meaningful
			$sFirstTagName = null;
			foreach($oElement->children() as $oChildElement)
			{
				if ($sFirstTagName == null)
				{
					$sFirstTagName = $oChildElement->getName();
				}
				else if ($sFirstTagName != $oChildElement->getName())
				{
					throw new InvalidParameterException("Invalid Parameters file '{$this->sParametersFile}': mixed tags ('$sFirstTagName' and '".$oChildElement->getName()."') inside array '".$oElement->getName()."'");
				}
				$val = $this->ReadElement($oChildElement);
				$value[] = $val;
			}
			break;
			
			case 'hash':
			$value = array();
			// Treat the current element as a hash, child tag names are keys
			foreach($oElement->children() as $oChildElement)
			{
				if (array_key_exists($oChildElement->getName(), $value))
				{
					throw new InvalidParameterException("Invalid Parameters file '{$this->sParametersFile}': duplicate tags '".$oChildElement->getName()."' inside hash '".$oElement->getName()."'");
				}
				$val = $this->ReadElement($oChildElement);
				$value[$oChildElement->getName()] = $val;
			}
			break;
			
			case 'int':
			case 'integer':
			$value = (int)$oElement;
			break;
			
			case 'string':
			default:
			$value = (string)$oElement;
		}
		return $value;
	}
	
	protected function GetAttribute($sAttName, $oElement, $sDefaultValue)
	{
		$sRet = $sDefaultValue;

		foreach($oElement->attributes() as $sKey => $oChildElement)
		{
			if ((string)$sKey == $sAttName)
			{
				$sRet = (string)$oChildElement;
				break;
			}
		}
		return $sRet;
	}
}
