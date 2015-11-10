<?php
// Copyright (C) 2015 Combodo SARL
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
 * Module specific customizations:
 * The customizations are done in XML, within a module_design section (itop_design/module_designs/module_design)
 * The module reads the cusomtizations by the mean of the ModuleDesign API
 * @package Core
 */

require_once(APPROOT.'application/utils.inc.php');


/**
 * Class ModuleDesign
 *
 * Usage from within a module:
 *
 * // Fetch the design
 * $oDesign = new ModuleDesign('tagada');
 *
 * // Read data from the root node
 * $oRoot = $oDesign->documentElement;
 * $oProperties = $oRoot->GetUniqueElement('properties');
 * $prop1 = $oProperties->GetChildText('property1');
 * $prop2 = $oProperties->GetChildText('property2');
 *
 * // Read data by searching the entire DOM
 * foreach ($oDesign->GetNodes('/module_design/bricks/brick') as $oBrickNode)
 * {
 *   $sId = $oBrickNode->getAttribute('id');
 *   $sType = $oBrickNode->getAttribute('xsi:type');
 * }
 *
 * // Search starting a given node
 * $oBricks = $oDesign->documentElement->GetUniqueElement('bricks');
 * foreach ($oBricks->GetNodes('brick') as $oBrickNode)
 * {
 *   ...
 * }
 */
class ModuleDesign extends DOMDocument
{
	/**
	 * @param string|null $sDesignSourceId Identifier of the section module_design (generally a module name), null to build an empty design
	 * @throws Exception
	 */
	public function __construct($sDesignSourceId = null)
	{
		parent::__construct('1.0', 'UTF-8');
		$this->Init();

		if (!is_null($sDesignSourceId))
		{
			$this->LoadFromCompiledDesigns($sDesignSourceId);
		}
	}

	/**
	 * Overloadable. Called prior to data loading.
	 */
	protected function Init()
	{
		$this->registerNodeClass('DOMElement', 'ModuleDesignElement');

		$this->formatOutput = true; // indent (must be loaded with option LIBXML_NOBLANKS)
		$this->preserveWhiteSpace = true; // otherwise the formatOutput option would have no effect
	}

	/**
	 * Gets the data where the compiler has left them...
	 * @param $sDesignSourceId Identifier of the section module_design (generally a module name)
	 * @throws Exception
	 */
	protected function LoadFromCompiledDesigns($sDesignSourceId)
	{
		$sDesignDir = APPROOT.'env-'.utils::GetCurrentEnvironment().'/core/module_designs/';
		$sFile = $sDesignDir.$sDesignSourceId.'.xml';
		if (!file_exists($sFile))
		{
			$aFiles = glob($sDesignDir.'/*.xml');
			if (count($aFiles) == 0)
			{
				$sAvailable = 'none!';
			}
			else
			{
				var_dump($aFiles);
				$aAvailable = array();
				foreach ($aFiles as $sFile)
				{
					$aAvailable[] = "'".basename($sFile, '.xml')."'";
				}
				$sAvailable = implode(', ', $aAvailable);
			}
			throw new Exception("Could not load module design '$sDesignSourceId'. Available designs: $sAvailable");
		}

		// Silently keep track of errors
		libxml_use_internal_errors(true);
		libxml_clear_errors();
		$this->load($sFile);
		//$bValidated = $oDocument->schemaValidate(APPROOT.'setup/itop_design.xsd');
		$aErrors = libxml_get_errors();
		if (count($aErrors) > 0)
		{
			$aDisplayErrors = array();
			foreach($aErrors as $oXmlError)
			{
				$aDisplayErrors[] = 'Line '.$oXmlError->line.': '.$oXmlError->message;
			}

			throw new Exception("Invalid XML in '$sFile'. Errors: ".implode(', ', $aDisplayErrors));
		}
	}

	/**
	 * Overload of the standard API
	 */
	public function load($filename, $options = 0)
	{
		parent::load($filename, LIBXML_NOBLANKS);
	}

	/**
	 * Overload of the standard API
	 */
	public function save($filename, $options = 0)
	{
		$this->documentElement->setAttribute('xmlns:xsi', "http://www.w3.org/2001/XMLSchema-instance");
		return parent::save($filename, LIBXML_NOBLANKS);
	}

	/**
	 * Create an HTML representation of the DOM, for debugging purposes
	 * @param bool|false $bReturnRes Echoes or returns the HTML representation
	 * @return mixed void or the HTML representation of the DOM
	 */
	public function Dump($bReturnRes = false)
	{
		$sXml = $this->saveXML();
		if ($bReturnRes)
		{
			return $sXml;
		}
		else
		{
			echo "<pre>\n";
			echo htmlentities($sXml);
			echo "</pre>\n";
		}
	}

	/**
	 * Quote and escape strings for use within an XPath expression
	 * Usage: DesignDocument::GetNodes('class[@id='.DesignDocument::XPathQuote($sId).']');
	 * @param $sValue The value to be quoted
	 * @return string to be used within an XPath
	 */
	public static function XPathQuote($sValue)
	{
		if (strpos($sValue, '"') !== false)
		{
			$aParts = explode('"', $sValue);
			$sRet = 'concat("'.implode('", \'"\', "', $aParts).'")';
		}
		else
		{
			$sRet = '"'.$sValue.'"';
		}
		return $sRet;
	}

	/**
	 * Extracts some nodes from the DOM
	 * @param string $sXPath A XPath expression
	 * @param DesignNode|null $oContextNode The node to start the search from
	 * @return DOMNodeList
	 */
	public function GetNodes($sXPath, $oContextNode = null)
	{
		$oXPath = new DOMXPath($this);
		if (is_null($oContextNode))
		{
			$oResult = $oXPath->query($sXPath);
		}
		else
		{
			$oResult = $oXPath->query($sXPath, $oContextNode);
		}
		return $oResult;
	}

	/**
	 * An alternative to getNodePath, that gives the id of nodes instead of the position within the children
	 */
	public static function GetItopNodePath($oNode)
	{
		if ($oNode instanceof DOMDocument) return '';

		$sId = $oNode->getAttribute('id');
		$sNodeDesc = ($sId != '') ? $oNode->nodeName.'['.$sId.']' : $oNode->nodeName;
		return self::GetItopNodePath($oNode->parentNode).'/'.$sNodeDesc;
	}
}


/**
 * ModuleDesignElement: helper to read/change the DOM
 * @package ModelFactory
 */
class ModuleDesignElement extends DOMElement
{
	/**
	 * Extracts some nodes from the DOM
	 * @param string $sXPath A XPath expression
	 * @return DOMNodeList
	 */
	public function GetNodes($sXPath)
	{
		return $this->ownerDocument->GetNodes($sXPath, $this);
	}

	/**
	 * Create an HTML representation of the DOM, for debugging purposes
	 * @param bool|false $bReturnRes Echoes or returns the HTML representation
	 * @return mixed void or the HTML representation of the DOM
	 */
	public function Dump($bReturnRes = false)
	{
		$oDoc = new iTopDesignDocument();
		$oClone = $oDoc->importNode($this->cloneNode(true), true);
		$oDoc->appendChild($oClone);

		$sXml = $oDoc->saveXML($oClone);
		if ($bReturnRes)
		{
			return $sXml;
		}
		else
		{
			echo "<pre>\n";
			echo htmlentities($sXml);
			echo "</pre>\n";
		}
	}

	/**
	 * Returns the node directly under the given node
	 * @param $sTagName
	 * @param bool|true $bMustExist
	 * @return null
	 * @throws DOMFormatException
	 */
	public function GetUniqueElement($sTagName, $bMustExist = true)
	{
		$oNode = null;
		foreach($this->childNodes as $oChildNode)
		{
			if ($oChildNode->nodeName == $sTagName)
			{
				$oNode = $oChildNode;
				break;
			}
		}
		if ($bMustExist && is_null($oNode))
		{
			throw new DOMFormatException('Missing unique tag: '.$sTagName);
		}
		return $oNode;
	}

	/**
	 * Returns the node directly under the current node, or null if missing
	 * @param $sTagName
	 * @return null
	 * @throws DOMFormatException
	 */
	public function GetOptionalElement($sTagName)
	{
		return $this->GetUniqueElement($sTagName, false);
	}

	/**
	 * Returns the TEXT of the current node (possibly from several child nodes)
	 * @param null $sDefault
	 * @return null|string
	 */
	public function GetText($sDefault = null)
	{
		$sText = null;
		foreach($this->childNodes as $oChildNode)
		{
			if ($oChildNode instanceof DOMText)
			{
				if (is_null($sText)) $sText = '';
				$sText .= $oChildNode->wholeText;
			}
		}
		if (is_null($sText))
		{
			return $sDefault;
		}
		else
		{
			return $sText;
		}
	}

	/**
	 * Get the TEXT value from a child node
	 * @param string $sTagName
	 * @param string|null $sDefault
	 * @return string
	 */
	public function GetChildText($sTagName, $sDefault = null)
	{
		$sRet = $sDefault;
		if ($oChild = $this->GetOptionalElement($sTagName))
		{
			$sRet = $oChild->GetText($sDefault);
		}
		return $sRet;
	}
}
