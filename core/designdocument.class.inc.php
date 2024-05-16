<?php
/**
 * Copyright (c) 2010-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 *
 */

/**
 * Design document and associated nodes
 * @package Core
 */

namespace Combodo\iTop;

use DOMDocument;
use DOMFormatException;
use DOMNode;
use DOMNodeList;
use DOMXPath;
use Exception;
use IssueLog;
use LogAPI;
use MFElement;
use utils;

/**
 * Class \Combodo\iTop\DesignDocument
 *
 * A design document is the DOM tree that modelize behaviors. One of its
 * characteristics is that it can be altered by the mean of the same kind of document.
 *
 */
class DesignDocument extends DOMDocument
{

	/** To fix DOMNode::getLineNo() ref https://www.php.net/manual/en/domnode.getlineno.php */
	public const XML_PARSE_BIG_LINES = 4194304;


	/**
	 * @throws \Exception
	 */
	public function __construct()
	{
		parent::__construct('1.0', 'UTF-8');
		$this->Init();
	}

	/**
	 * Overloadable. Called prior to data loading.
	 */
	protected function Init()
	{
		$this->registerNodeClass('DOMElement', '\Combodo\iTop\DesignElement');

		$this->formatOutput = true; // indent (must be loaded with option LIBXML_NOBLANKS)
		$this->preserveWhiteSpace = true; // otherwise the formatOutput option would have no effect
	}

	/**
	 * Overload of the standard API
	 *
	 * @param string $filename
	 * @param int $options
	 *
	 * @return bool
	 */
	public function load(string $filename, int $options = 0): bool
	{
		if (is_file($filename)) {
			libxml_clear_errors();
			if (parent::load($filename, LIBXML_NOBLANKS | LIBXML_BIGLINES | LIBXML_PARSEHUGE | self::XML_PARSE_BIG_LINES) === false) {
				$aErrors = libxml_get_errors();
				IssueLog::Error("Error loading $filename", LogAPI::CHANNEL_DEFAULT, $aErrors);
			}
		}

		return true;
	}

	/**
	 * Overload of the standard API
	 *
	 * @param $filename
	 * @param int $options
	 *
	 * @return int
	 */
	// Return type union is not supported by PHP 7.4, we can remove the following PHP attribute and add the return type once iTop min PHP version is PHP 8.0+
	#[\ReturnTypeWillChange]
	public function save($filename, $options = null)
	{
		$this->documentElement->setAttribute('xmlns:xsi', "http://www.w3.org/2001/XMLSchema-instance");
		return parent::save($filename);
	}

	/**
	 * Create an HTML representation of the DOM, for debugging purposes
	 * @param bool|false $bReturnRes Echoes or returns the HTML representation
	 * @return mixed void or the HTML representation of the DOM
	 */
	public function Dump($bReturnRes = false)
	{
		$sXml = $this->saveXML();
		if ($bReturnRes) {
			return $sXml;
		}

		echo "<pre>\n";
		echo utils::EscapeHtml($sXml);
		echo "</pre>\n";

		return '';
	}

	/**
	 * Quote and escape strings for use within an XPath expression
	 * Usage: DesignDocument::GetNodes('class[@id='.DesignDocument::XPathQuote($sId).']');
	 * @param string $sValue The value to be quoted
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
	 * @param DesignElement $oContextNode The node to start the search from
	 * @return \DOMNodeList
	 */
	public function GetNodes($sXPath, $oContextNode = null)
	{
		$oXPath = new \DOMXPath($this);
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
	 * @param DesignElement $oNode The node to describe
	 * @return string
	 */
	public static function GetItopNodePath($oNode)
	{
		if ($oNode instanceof \DOMDocument) return '';
		if (is_null($oNode)) return '';

		$sId = $oNode->getAttribute('id');
		$sNodeDesc = ($sId != '') ? $oNode->nodeName.'['.$sId.']' : $oNode->nodeName;
		return self::GetItopNodePath($oNode->parentNode).'/'.$sNodeDesc;
	}
}

/**
 * DesignElement: helper to read/change the DOM
 * @package ModelFactory
 */
class DesignElement extends \DOMElement
{
	/**
	 * Extracts some nodes from the DOM
	 * @param string $sXPath A XPath expression
	 * @return \DOMNodeList
	 */
	public function GetNodes($sXPath)
	{
		return $this->ownerDocument->GetNodes($sXPath, $this);
	}

	public static function ToArray(DesignElement $oNode)
	{
		$aRes = [];

		if ($oNode->GetNodes('./*')->length == 0) {
			return $oNode->GetText('');
		}
		foreach ($oNode->GetNodes('./*') as $oSubNode) {
			/** @var \Combodo\iTop\DesignElement $oSubNode */
			$aSubArray = DesignElement::ToArray($oSubNode);
			if ($oSubNode->hasAttribute('id')) {
				$aRes[$oSubNode->getAttribute('id')] = $aSubArray;
			} else {
				$aRes[$oSubNode->tagName] = $aSubArray;
			}
		}

		return $aRes;
	}

	/**
	 * Create an HTML representation of the DOM, for debugging purposes
	 *
	 * @param bool|false $bReturnRes Echoes or returns the HTML representation
	 *
	 * @return mixed void or the HTML representation of the DOM
	 * @throws \Exception
	 */
	public function Dump($bReturnRes = false)
	{
		$oDoc = new DesignDocument();
		$oClone = $oDoc->importNode($this->cloneNode(true), true);
		$oDoc->appendChild($oClone);

		$sXml = $oDoc->saveXML($oClone);
		if ($bReturnRes) {
			return $sXml;
		}
		echo "<pre>\n";
		echo utils::EscapeHtml($sXml);
		echo "</pre>\n";

		return '';
	}

	/**
	 * Compatibility with PHP8.0
	 *
	 * @return \DOMElement|null
	 *
	 * @since 3.1.2
	 */
	public function GetFirstElementChild()
	{
		if (property_exists($this, 'firstElementChild')) {
			return $this->firstElementChild;
		}

		$oChildNode = $this->firstChild;
		while (!is_null($oChildNode)) {
			if ($oChildNode instanceof \DOMElement) {
				return $oChildNode;
			}
			$oChildNode = $oChildNode->nextSibling;
		}

		return null;
	}

	/**
	 * Compatibility with PHP8.0
	 *
	 * @return \DOMElement|null
	 *
	 * @since 3.1.2
	 */
	public function GetNextElementSibling()
	{
		if (property_exists($this, 'nextElementSibling')) {
			return $this->nextElementSibling;
		}

		$oSibling = $this->nextSibling;
		while (!is_null($oSibling)) {
			if ($oSibling instanceof \DOMElement) {
				return $oSibling;
			}
			$oSibling = $oSibling->nextSibling;
		}

		return null;

	}

	/**
	 * Returns the node directly under the given node
	 * @param $sTagName
	 * @param bool|true $bMustExist
	 * @return \MFElement
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
	 * @return \MFElement
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
			if ($oChildNode instanceof \DOMText)
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
	 *
	 * @param string $sTagName
	 * @param string|null $sDefault
	 *
	 * @return string
	 * @throws \DOMFormatException
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

	/**
	 * Check that the current node is actually a class node, under classes
	 * @since 3.1.2 3.2.0 N°6974
	 */
	public function IsClassNode(): bool
	{
		if ($this->tagName == 'class') {
			// Beware: classes/class also exists in the group definition
			if (($this->parentNode->tagName == 'classes') && ($this->parentNode->parentNode->tagName == 'itop_design')) {
				return true;
			}
		}

		return false;
	}

	/**
	 * True if the node is contained in a _delta="merge" tree
	 * @return bool
	 */
	public function IsInSpecifiedMerge(): bool
	{
		// Iterate through the parents: reset the flag if any of them has a flag set
		for ($oParent = $this; $oParent instanceof MFElement; $oParent = $oParent->parentNode) {
			$sDeltaSpec = $oParent->getAttribute('_delta');
			if ($sDeltaSpec === 'merge') {
				return true;
			}
			if (in_array($sDeltaSpec, ['define', 'define_if_not_exists', 'force', 'redefine'])) {
				return false;
			}
		}

		return false;
	}

	/**
	 * Find the child node matching the given node.
	 * UNSAFE: may return nodes marked as _alteration="removed"
	 * A method with the same signature MUST exist in MFDocument for the recursion to work fine
	 *
	 * @param DesignElement $oRefNode The node to search for
	 * @param null|string $sSearchId substitutes to the value of the 'id' attribute
	 *
	 * @return DesignElement|null
	 * @throws \Exception
	 * @since 3.1.2 3.2.0 N°6974
	 */
	public function _FindChildNode(DesignElement $oRefNode, $sSearchId = null): ?DesignElement
	{
		return self::_FindNode($this, $oRefNode, $sSearchId);
	}


	/**
	 * Find the child node matching the given node.
	 * UNSAFE: may return nodes marked as _alteration="removed"
	 * A method with the same signature MUST exist in MFDocument for the recursion to work fine
	 *
	 * @param DesignElement $oRefNode The node to search for
	 *
	 * @return DesignElement|null
	 * @throws \Exception
	 * @since 3.1.2 3.2.0 N°6974
	 */
	public function _FindChildNodes(DesignElement $oRefNode): ?DesignElement
	{
		return self::_FindNodes($this, $oRefNode);
	}

	/**
	 * Find the child node matching the given node under the specified parent.
	 * UNSAFE: may return nodes marked as _alteration="removed"
	 *
	 * @param \DOMNode $oParent
	 * @param DesignElement $oRefNode
	 * @param string|null $sSearchId
	 *
	 * @return DesignElement|null
	 * @throws Exception
	 * @since 3.1.2 3.2.0 N°6974
	 */
	public static function _FindNode(DOMNode $oParent, DesignElement $oRefNode, string $sSearchId = null): ?DesignElement
	{
		$oNodes = self::_FindNodes($oParent, $oRefNode, $sSearchId);
		if ($oNodes instanceof DOMNodeList) {
			/** @var DesignElement $oNode */
			$oNode = $oNodes->item(0);

			return $oNode;
		}

		return null;
	}

	/**
	 * Find the child node matching the given node under the specified parent.
	 * UNSAFE: may return nodes marked as _alteration="removed"
	 *
	 * @param \DOMNode $oParent
	 * @param DesignElement $oRefNode
	 * @param string|null $sSearchId
	 *
	 * @return \DOMNodeList|false|mixed
	 * @since 3.1.2 3.2.0 N°6974
	 */
	public static function _FindNodes(DOMNode $oParent, DesignElement $oRefNode, string $sSearchId = null)
	{
		if ($oParent instanceof DOMDocument)
		{
			$oDoc = $oParent->firstChild->ownerDocument;
			$oRoot = $oParent;
		}
		else
		{
			$oDoc = $oParent->ownerDocument;
			$oRoot = $oParent;
		}

		$oXPath = new DOMXPath($oDoc);
		if ($oRefNode->hasAttribute('id'))
		{
			// Find the elements having the same tag name and id
			if (!$sSearchId)
			{
				$sSearchId = $oRefNode->getAttribute('id');
			}
			$sXPath = './'.$oRefNode->tagName."[@id='$sSearchId']";

			$oRes = $oXPath->query($sXPath, $oRoot);
		}
		else
		{
			// Get the elements having the same tag name
			$sXPath = './'.$oRefNode->tagName;

			$oRes = $oXPath->query($sXPath, $oRoot);
		}

		return $oRes;
	}
}
