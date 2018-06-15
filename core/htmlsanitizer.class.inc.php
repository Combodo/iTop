<?php
// Copyright (C) 2016-2017 Combodo SARL
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
 * Base class for all possible implementations of HTML Sanitization
 */
abstract class HTMLSanitizer
{
	public function __construct()
	{
		// Do nothing..
	}
	
	/**
	 * Sanitizes the given HTML document
	 * @param string $sHTML
	 * @return string
	 */
	abstract public function DoSanitize($sHTML);
	
	/**
	 * Sanitize an HTML string with the configured sanitizer, falling back to HTMLDOMSanitizer in case of Exception or invalid configuration
	 * @param string $sHTML
	 * @return string
	 */
	public static function Sanitize($sHTML)
	{
		$sSanitizerClass = MetaModel::GetConfig()->Get('html_sanitizer');
		if(!class_exists($sSanitizerClass))
		{
			IssueLog::Warning('The configured "html_sanitizer" class "'.$sSanitizerClass.'" is not a valid class. Will use HTMLDOMSanitizer as the default sanitizer.');
			$sSanitizerClass = 'HTMLDOMSanitizer';
		}
		else if(!is_subclass_of($sSanitizerClass, 'HTMLSanitizer'))
		{
			IssueLog::Warning('The configured "html_sanitizer" class "'.$sSanitizerClass.'" is not a subclass of HTMLSanitizer. Will use HTMLDOMSanitizer as the default sanitizer.');
			$sSanitizerClass = 'HTMLDOMSanitizer';
		}
		
		try
		{
			$oSanitizer = new $sSanitizerClass();
			$sCleanHTML = $oSanitizer->DoSanitize($sHTML);
		}
		catch(Exception $e)
		{
			if($sSanitizerClass != 'HTMLDOMSanitizer')
			{
				IssueLog::Warning('Failed to sanitize an HTML string with "'.$sSanitizerClass.'". The following exception occured: '.$e->getMessage());
				IssueLog::Warning('Will try to sanitize with HTMLDOMSanitizer.');
				// try again with the HTMLDOMSanitizer
				$oSanitizer = new HTMLDOMSanitizer();
				$sCleanHTML = $oSanitizer->DoSanitize($sHTML);
			}
			else
			{
				IssueLog::Error('Failed to sanitize an HTML string with "HTMLDOMSanitizer". The following exception occured: '.$e->getMessage());
				IssueLog::Error('The HTML will NOT be sanitized.');
				$sCleanHTML = $sHTML;	
			}
		}
		return $sCleanHTML;
	}
}

/**
 * Dummy HTMLSanitizer which does nothing at all!
 * Can be used if HTML Sanitization is not important
 * (for example when importing "safe" data during an on-boarding)
 * and performance is at stake
 *
 */
class HTMLNullSanitizer extends HTMLSanitizer
{
	/**
	 * (non-PHPdoc)
	 * @see HTMLSanitizer::Sanitize()
	 */
	public function DoSanitize($sHTML)
	{
		return $sHTML;
	}
	
}

/**
 * A standard-compliant HTMLSanitizer based on the HTMLPurifier library by Edward Z. Yang
 * Complete but quite slow
 * http://htmlpurifier.org
 */
/*
class HTMLPurifierSanitizer extends HTMLSanitizer
{
	protected static $oPurifier = null;
	
	public function __construct()
	{
		if (self::$oPurifier == null)
		{
			$sLibPath = APPROOT.'lib/htmlpurifier/HTMLPurifier.auto.php';
			if (!file_exists($sLibPath))
			{
				throw new Exception("Missing library '$sLibPath', cannot use HTMLPurifierSanitizer.");
			}
			require_once($sLibPath);
			
			$oPurifierConfig = HTMLPurifier_Config::createDefault();
			$oPurifierConfig->set('Core.Encoding', 'UTF-8'); // defaults to 'UTF-8'
			$oPurifierConfig->set('HTML.Doctype', 'XHTML 1.0 Strict'); // defaults to 'XHTML 1.0 Transitional'
			$oPurifierConfig->set('URI.AllowedSchemes', array (
				'http' => true,
				'https' => true,
				'data' => true, // This one is not present by default
			));
			$sPurifierCache = APPROOT.'data/HTMLPurifier';
			if (!is_dir($sPurifierCache))
			{
				mkdir($sPurifierCache);
			}
			if (!is_dir($sPurifierCache))
			{
				throw new Exception("Could not create the cache directory '$sPurifierCache'");
			}
			$oPurifierConfig->set('Cache.SerializerPath', $sPurifierCache); // no trailing slash
			self::$oPurifier = new HTMLPurifier($oPurifierConfig);
		}
	}
	
	public function DoSanitize($sHTML)
	{
		$sCleanHtml = self::$oPurifier->purify($sHTML);
		return $sCleanHtml;		
	}
}
*/

class HTMLDOMSanitizer extends HTMLSanitizer
{
	protected $oDoc;

	/**
	 * @var array
	 * @see https://www.itophub.io/wiki/page?id=2_5_0%3Aadmin%3Arich_text_limitations
	 */
	protected static $aTagsWhiteList = array(
		'html' => array(),
		'body' => array(),
		'a' => array('href', 'name', 'style', 'target', 'title'),
		'p' => array('style'),
		'blockquote' => array('style'),
		'br' => array(),
		'span' => array('style'),
		'div' => array('style'),
		'b' => array(),
		'i' => array(),
		'u' => array(),
		'em' => array(),
		'strong' => array(),
		'img' => array('src', 'style', 'alt', 'title'),
		'ul' => array('style'),
		'ol' => array('style'),
		'li' => array('style'),
		'h1' => array('style'),
		'h2' => array('style'),
		'h3' => array('style'),
		'h4' => array('style'),
		'nav' => array('style'),
		'section' => array('style'),
		'code' => array('style'),
		'table' => array('style', 'width', 'summary', 'align', 'border', 'cellpadding', 'cellspacing'),
		'thead' => array('style'),
		'tbody' => array('style'),
		'tr' => array('style', 'colspan', 'rowspan'),
		'td' => array('style', 'colspan', 'rowspan'),
		'th' => array('style', 'colspan', 'rowspan'),
		'fieldset' => array('style'),
		'legend' => array('style'),
		'font' => array('face', 'color', 'style', 'size'),
		'big' => array(),
		'small' => array(),
		'tt' => array(),
		'kbd' => array(),
		'samp' => array(),
		'var' => array(),
		'del' => array(),
		's' => array(), // strikethrough
		'ins' => array(),
		'cite' => array(),
		'q' => array(),
		'hr' => array('style'),
		'pre' => array(),
	);

	protected static $aAttrsWhiteList = array(
		'src' => '/^(http:|https:|data:)/i',
	);

	/**
	 * @var array
	 * @see https://www.itophub.io/wiki/page?id=2_5_0%3Aadmin%3Arich_text_limitations
	 */
	protected static $aStylesWhiteList = array(
		'background-color',
		'border',
		'border-collapse',
		'bordercolor',
		'cellpadding',
		'cellspacing',
		'color',
		'float',
		'font',
		'font-family',
		'font-size',
		'font-style',
		'height',
		'margin',
		'padding',
		'text-align',
		'width',
	);

	public function __construct()
	{
		// Building href validation pattern from url and email validation patterns as the patterns are not used the same way in HTML content than in standard attributes value.
		// eg. "foo@bar.com" vs "mailto:foo@bar.com?subject=Title&body=Hello%20world"
		if (!array_key_exists('href', self::$aAttrsWhiteList))
		{
			// Regular urls
			$sUrlPattern = utils::GetConfig()->Get('url_validation_pattern');
			// Mailto urls
			$sMailtoPattern = '(mailto:(' . utils::GetConfig()->Get('email_validation_pattern') . ')(?:\?(?:subject|body)=([a-zA-Z0-9+\$_.-]*)(?:&(?:subject|body)=([a-zA-Z0-9+\$_.-]*))?)?)';

			$sPattern = $sUrlPattern . '|' . $sMailtoPattern;
			$sPattern = '/'.str_replace('/', '\/', $sPattern).'/i';
			self::$aAttrsWhiteList['href'] = $sPattern;
		}
	}

	public function DoSanitize($sHTML)
	{
		$this->oDoc = new DOMDocument();
		$this->oDoc->preserveWhitespace = true;

		// MS outlook implements empty lines by the mean of <p><o:p></o:p></p>
		// We have to transform that into <p><br></p> (which is how Thunderbird implements empty lines)
		// Unfortunately, DOMDocument::loadHTML does not take the tag namespaces into account (once loaded there is no way to know if the tag did have a namespace)
		// therefore we have to do the transformation upfront
		$sHTML = preg_replace('@<o:p>\s*</o:p>@', '<br>', $sHTML);

		@$this->oDoc->loadHTML('<?xml encoding="UTF-8"?>'.$sHTML); // For loading HTML chunks where the character set is not specified
		
		$this->CleanNode($this->oDoc);
		
		$oXPath = new DOMXPath($this->oDoc);
		$sXPath = "//body";
		$oNodesList = $oXPath->query($sXPath);
		
		if ($oNodesList->length == 0)
		{
			// No body, save the whole document
			$sCleanHtml = $this->oDoc->saveHTML();
		}
		else
		{
			// Export only the content of the body tag
			$sCleanHtml = $this->oDoc->saveHTML($oNodesList->item(0));
			// remove the body tag itself
			$sCleanHtml = str_replace( array('<body>', '</body>'), '', $sCleanHtml);
		}
		
		return $sCleanHtml;
	}
	
	protected function CleanNode(DOMNode $oElement)
	{
		$aAttrToRemove = array();
		// Gather the attributes to remove
		if ($oElement->hasAttributes())
		{
			foreach($oElement->attributes as $oAttr)
			{
				$sAttr = strtolower($oAttr->name);
				if (!in_array($sAttr, self::$aTagsWhiteList[strtolower($oElement->tagName)]))
				{
					// Forbidden (or unknown) attribute
					$aAttrToRemove[] = $oAttr->name;
				}
				else if (!$this->IsValidAttributeContent($sAttr, $oAttr->value))
				{
					// Invalid content
					$aAttrToRemove[] = $oAttr->name;
				}
				else if ($sAttr == 'style')
				{
					// Special processing for style tags
					$sCleanStyle = $this->CleanStyle($oAttr->value);
					if ($sCleanStyle == '')
					{
						// Invalid content
						$aAttrToRemove[] = $oAttr->name;
					}
					else
					{
						$oElement->setAttribute($oAttr->name, $sCleanStyle);
					}
				}
			}
			// Now remove them
			foreach($aAttrToRemove as $sName)
			{
				$oElement->removeAttribute($sName);
			}
		}
		
		if ($oElement->hasChildNodes())
		{
			$aChildElementsToRemove = array();
			// Gather the child noes to remove
			foreach($oElement->childNodes as $oNode)
			{
				if (($oNode instanceof DOMElement) && (!array_key_exists(strtolower($oNode->tagName), self::$aTagsWhiteList)))
				{
					$aChildElementsToRemove[] = $oNode;
				}
				else if ($oNode instanceof DOMComment)
				{
					$aChildElementsToRemove[] = $oNode;
				}
				else
				{
					// Recurse
					$this->CleanNode($oNode);
					if (($oNode instanceof DOMElement) && (strtolower($oNode->tagName) == 'img'))
					{
						$this->ProcessImage($oNode);
					}
				}
			}
			// Now remove them
			foreach($aChildElementsToRemove as $oDomElement)
			{
				$oElement->removeChild($oDomElement);
			}
		}
	}
	
	/**
	 * Add an extra attribute data-img-id for images which are based on an actual InlineImage
	 * so that we can later reconstruct the full "src" URL when needed
	 * @param DOMNode $oElement
	 */
	protected function ProcessImage(DOMNode $oElement)
	{
		$sSrc = $oElement->getAttribute('src');
		$sDownloadUrl = str_replace(array('.', '?'), array('\.', '\?'), INLINEIMAGE_DOWNLOAD_URL); // Escape . and ?
		$sUrlPattern = '|'.$sDownloadUrl.'([0-9]+)&s=([0-9a-f]+)|';
		if (preg_match($sUrlPattern, $sSrc, $aMatches))
		{
			$oElement->setAttribute('data-img-id', $aMatches[1]);
			$oElement->setAttribute('data-img-secret', $aMatches[2]);
		}
	}
	
	protected function CleanStyle($sStyle)
	{
		$aAllowedStyles = array();
		$aItems = explode(';', $sStyle);
		{
			foreach($aItems as $sItem)
			{
				$aElements = explode(':', trim($sItem));
				if (in_array(trim(strtolower($aElements[0])), static::$aStylesWhiteList))
				{
					$aAllowedStyles[] = trim($sItem);
				}
			}
		}
		return implode(';', $aAllowedStyles);
	}
	
	protected function IsValidAttributeContent($sAttributeName, $sValue)
	{
		if (array_key_exists($sAttributeName, self::$aAttrsWhiteList))
		{
			return preg_match(self::$aAttrsWhiteList[$sAttributeName], $sValue);
		}
		return true;
	}
}