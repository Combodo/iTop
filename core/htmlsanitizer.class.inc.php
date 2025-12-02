<?php

// Copyright (C) 2016-2024 Combodo SAS
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
use Masterminds\HTML5;

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
	 *
	 * @param string $sHTML
	 * @param string $sConfigKey eg. 'html_sanitizer', 'svg_sanitizer'
	 *
	 * @return string
	 */
	public static function Sanitize($sHTML, $sConfigKey = 'html_sanitizer')
	{
		$sSanitizerClass = utils::GetConfig()->Get($sConfigKey);
		if (!class_exists($sSanitizerClass)) {
			IssueLog::Warning('The configured "'.$sConfigKey.'" class "'.$sSanitizerClass.'" is not a valid class. Will use HTMLDOMSanitizer as the default sanitizer.');
			$sSanitizerClass = 'HTMLDOMSanitizer';
		} elseif (!is_subclass_of($sSanitizerClass, 'HTMLSanitizer')) {
			if ($sConfigKey === 'html_sanitizer') {
				IssueLog::Warning('The configured "'.$sConfigKey.'" class "'.$sSanitizerClass.'" is not a subclass of '.HTMLSanitizer::class.'. Will use HTMLDOMSanitizer as the default sanitizer.');
				$sSanitizerClass = 'HTMLDOMSanitizer';
			} else {
				IssueLog::Error('The configured "'.$sConfigKey.'" class "'.$sSanitizerClass.'" is not a subclass of '.HTMLSanitizer::class.' ! Won\'t sanitize string.');

				return $sHTML;
			}
		}

		try {
			$oSanitizer = new $sSanitizerClass();
			$sCleanHTML = $oSanitizer->DoSanitize($sHTML);
		} catch (Exception $e) {
			if ($sSanitizerClass != 'HTMLDOMSanitizer') {
				IssueLog::Warning('Failed to sanitize an HTML string with "'.$sSanitizerClass.'". The following exception occured: '.$e->getMessage());
				IssueLog::Warning('Will try to sanitize with HTMLDOMSanitizer.');
				// try again with the HTMLDOMSanitizer
				$oSanitizer = new HTMLDOMSanitizer();
				$sCleanHTML = $oSanitizer->DoSanitize($sHTML);
			} else {
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
 *
 * Can be used if HTML Sanitization is not important
 * (for example when importing "safe" data during an on-boarding)
 * and performance is at stake
 *
 * **Warning** : this won't filter HTML inserted in iTop at all, so this is a great security issue !
 * Also, the InlineImage objects processing won't be called.
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
 * Common implementation for sanitizer using DOM parsing
 */
abstract class DOMSanitizer extends HTMLSanitizer
{
	/** @var DOMDocument */
	protected $oDoc;
	/**
	 * @var string Class to use for InlineImage static method calls
	 * @used-by \Combodo\iTop\Test\UnitTest\Core\Sanitizer\HTMLDOMSanitizerTest::testDoSanitizeCallInlineImageProcessImageTag
	 */
	protected $sInlineImageClassName;

	public function __construct($sInlineImageClassName = InlineImage::class)
	{
		parent::__construct();

		$this->sInlineImageClassName = $sInlineImageClassName;
	}

	abstract public function GetTagsWhiteList();

	abstract public function GetTagsBlackList();

	abstract public function GetAttrsWhiteList();

	abstract public function GetAttrsBlackList();

	abstract public function GetStylesWhiteList();

	public function DoSanitize($sHTML)
	{
		$this->oDoc = new DOMDocument();
		$this->oDoc->preserveWhiteSpace = true;

		// MS outlook implements empty lines by the mean of <p><o:p></o:p></p>
		// We have to transform that into <p><br></p> (which is how Thunderbird implements empty lines)
		// Unfortunately, DOMDocument::loadHTML does not take the tag namespaces into account (once loaded there is no way to know if the tag did have a namespace)
		// therefore we have to do the transformation upfront
		$sHTML = preg_replace('@<o:p>(\s|&nbsp;)*</o:p>@', '<br>', $sHTML);

		$this->LoadDoc($sHTML);

		$this->CleanNode($this->oDoc);

		$sCleanHtml = $this->PrintDoc();

		return $sCleanHtml;
	}

	abstract public function LoadDoc($sHTML);

	/**
	 * @return string cleaned source
	 * @uses \DOMSanitizer::oDoc
	 */
	abstract public function PrintDoc();

	protected function CleanNode(DOMNode $oElement)
	{
		$aAttrToRemove = [];
		// Gather the attributes to remove
		if ($oElement->hasAttributes()) {
			foreach ($oElement->attributes as $oAttr) {
				$sAttr = strtolower($oAttr->name);
				if ((false === empty($this->GetAttrsBlackList()))
					&& (in_array($sAttr, $this->GetAttrsBlackList(), true))) {
					$aAttrToRemove[] = $oAttr->name;
				} elseif ((false === empty($this->GetTagsWhiteList()))
					&& (false === in_array($sAttr, $this->GetTagsWhiteList()[strtolower($oElement->tagName)]))) {
					$aAttrToRemove[] = $oAttr->name;
				} elseif (!$this->IsValidAttributeContent($sAttr, $oAttr->value)) {
					// Invalid content
					$aAttrToRemove[] = $oAttr->name;
				} elseif ($sAttr == 'style') {
					// Special processing for style tags
					$sCleanStyle = $this->CleanStyle($oAttr->value);
					if ($sCleanStyle == '') {
						// Invalid content
						$aAttrToRemove[] = $oAttr->name;
					} else {
						$oElement->setAttribute($oAttr->name, $sCleanStyle);
					}
				}
			}
			// Now remove them
			foreach ($aAttrToRemove as $sName) {
				$oElement->removeAttribute($sName);
			}
		}

		if ($oElement->hasChildNodes()) {
			$aChildElementsToRemove = [];
			// Gather the child noes to remove
			foreach ($oElement->childNodes as $oNode) {
				if ($oNode instanceof DOMElement) {
					$sNodeTagName = strtolower($oNode->tagName);
				}
				if (($oNode instanceof DOMElement)
					&& (false === empty($this->GetTagsBlackList()))
					&& (in_array($sNodeTagName, $this->GetTagsBlackList(), true))) {
					$aChildElementsToRemove[] = $oNode;
				} elseif (($oNode instanceof DOMElement)
					&& (false === empty($this->GetTagsWhiteList()))
					&& (false === array_key_exists($sNodeTagName, $this->GetTagsWhiteList()))) {
					$aChildElementsToRemove[] = $oNode;
				} elseif ($oNode instanceof DOMComment) {
					$aChildElementsToRemove[] = $oNode;
				} else {
					// Recurse
					$this->CleanNode($oNode);
					if (($oNode instanceof DOMElement) && (strtolower($oNode->tagName) == 'img')) {
						$this->sInlineImageClassName::ProcessImageTag($oNode);
					}
				}
			}
			// Now remove them
			foreach ($aChildElementsToRemove as $oDomElement) {
				$oElement->removeChild($oDomElement);
			}
		}
	}

	protected function IsValidAttributeContent($sAttributeName, $sValue)
	{
		if ((false === empty($this->GetAttrsBlackList()))
			&& (in_array($sAttributeName, $this->GetAttrsBlackList(), true))) {
			return true;
		}

		if (array_key_exists($sAttributeName, $this->GetAttrsWhiteList())) {
			return preg_match($this->GetAttrsWhiteList()[$sAttributeName], $sValue);
		}

		return true;
	}

	protected function CleanStyle($sStyle)
	{
		if (empty($this->GetStylesWhiteList())) {
			return $sStyle;
		}

		$aAllowedStyles = [];
		$aItems = explode(';', $sStyle);
		{
			foreach ($aItems as $sItem) {
				$aElements = explode(':', trim($sItem));
				if (in_array(trim(strtolower($aElements[0])), $this->GetStylesWhiteList())) {
					$aAllowedStyles[] = trim($sItem);
				}
			}
		}

		return implode(';', $aAllowedStyles);
	}
}

class HTMLDOMSanitizer extends DOMSanitizer
{
	/**
	 * @var array
	 * @see https://www.itophub.io/wiki/page?id=2_6_0%3Aadmin%3Arich_text_limitations
	 */
	protected static $aTagsWhiteList = [
		'html' => [],
		'body' => [],
		'a' => ['href', 'name', 'style', 'class', 'target', 'title', 'data-role', 'data-object-class', 'data-object-id', 'data-object-key'],
		'p' => ['style', 'class'],
		'blockquote' => ['style', 'class'],
		'br' => [],
		'span' => ['style', 'class'],
		'div' => ['style', 'class'],
		'b' => ['class'],
		'i' => ['class'],
		'u' => ['class'],
		'em' => ['class'],
		'strong' => ['class'],
		'img' => ['src', 'style', 'class', 'alt', 'title', 'width', 'height'],
		'ul' => ['style', 'class'],
		'ol' => ['reversed', 'start', 'style', 'class', 'type'],
		'li' => ['style', 'class', 'value'],
		'h1' => ['style', 'class'],
		'h2' => ['style', 'class'],
		'h3' => ['style', 'class'],
		'h4' => ['style', 'class'],
		'nav' => ['style', 'class'],
		'section' => ['style', 'class'],
		'code' => ['style', 'class'],
		'table' => ['style', 'class', 'width', 'summary', 'align', 'border', 'cellpadding', 'cellspacing'],
		'colgroup' => [],
		'col' => ['style'],
		'thead' => ['style', 'class'],
		'tbody' => ['style', 'class'],
		'tr' => ['style', 'class', 'colspan', 'rowspan'],
		'td' => ['style', 'class', 'colspan', 'rowspan'],
		'th' => ['style', 'class', 'colspan', 'rowspan'],
		'fieldset' => ['style', 'class'],
		'legend' => ['style', 'class'],
		'font' => ['face', 'color', 'style', 'class', 'size'],
		'big' => [],
		'small' => [],
		'tt' => [],
		'kbd' => [],
		'samp' => [],
		'var' => [],
		'del' => [],
		's' => [], // strikethrough
		'ins' => [],
		'cite' => [],
		'q' => [],
		'hr' => ['style', 'class'],
		'pre' => ['class'],
		'center' => [],
		'figure' => ['style', 'class'], // Ckeditor 5 puts images in figures
		'figcaption' => ['class'],
		'mark' => ['class'],
	];

	protected static $aAttrsWhiteList = [
		'src' => '/^(http:|https:|data:)/i',
	];

	/**
	 * @var array
	 * @see https://www.itophub.io/wiki/page?id=2_6_0%3Aadmin%3Arich_text_limitations
	 */
	protected static $aStylesWhiteList = [
		'aspect-ratio',
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
		'margin-left',
		'margin-right',
		'padding',
		'text-align',
		'vertical-align',
		'width',
		'white-space',
	];

	public function __construct($sInlineImageClassName = InlineImage::class)
	{
		parent::__construct($sInlineImageClassName);

		// Building href validation pattern from url and email validation patterns as the patterns are not used the same way in HTML content than in standard attributes value.
		// eg. "foo@bar.com" vs "mailto:foo@bar.com?subject=Title&body=Hello%20world"
		if (!array_key_exists('href', self::$aAttrsWhiteList)) {
			// Regular urls
			$sUrlPattern = utils::GetConfig()->Get('url_validation_pattern');

			// Mailto urls
			$sMailtoPattern = '(mailto:('.utils::GetConfig()->Get('email_validation_pattern').')(?:\?(?:subject|body)=([a-zA-Z0-9+\$_.-]*)(?:&(?:subject|body)=([a-zA-Z0-9+\$_.-]*))?)?)';

			// Notification placeholders
			// eg. $this->caller_id$, $this->hyperlink()$, $this->hyperlink(portal)$, $APP_URL$, $MODULES_URL$, ...
			// Note: Authorize both $xxx$ and %24xxx%24 as the latter one is encoded when used in HTML attributes (eg. a[href])
			$sPlaceholderPattern = '(\$|%24)[\w-]*(->[\w]*(\([\w-]*?\))?)?(\$|%24)';

			$sPattern = $sUrlPattern.'|'.$sMailtoPattern.'|'.$sPlaceholderPattern;
			$sPattern = '/'.str_replace('/', '\/', $sPattern).'/i';
			self::$aAttrsWhiteList['href'] = $sPattern;
		}
	}

	public function GetTagsWhiteList()
	{
		return static::$aTagsWhiteList;
	}

	public function GetTagsBlackList()
	{
		return [];
	}

	public function GetAttrsWhiteList()
	{
		return static::$aAttrsWhiteList;
	}

	public function GetAttrsBlackList()
	{
		return [];
	}

	public function GetStylesWhiteList()
	{
		return static::$aStylesWhiteList;
	}

	public function LoadDoc($sHTML)
	{
		@$this->oDoc->loadHTML('<?xml encoding="UTF-8"?>'.$sHTML); // For loading HTML chunks where the character set is not specified
		$this->oDoc->preserveWhiteSpace = true;
	}

	public function PrintDoc()
	{
		$oXPath = new DOMXPath($this->oDoc);
		$sXPath = "//body";
		$oNodesList = $oXPath->query($sXPath);

		if ($oNodesList->length == 0) {
			// No body, save the whole document
			$sCleanHtml = $this->oDoc->saveHTML();
		} else {
			// Export only the content of the body tag
			$sCleanHtml = $this->oDoc->saveHTML($oNodesList->item(0));
			// remove the body tag itself
			$sCleanHtml = str_replace(['<body>', '</body>'], '', $sCleanHtml);
		}

		return $sCleanHtml;
	}
}

/**
 * @since 2.6.5 2.7.6 3.0.0 N°4360
 */
class SVGDOMSanitizer extends DOMSanitizer
{
	public function GetTagsWhiteList()
	{
		return [];
	}

	/**
	 * @return string[]
	 * @link https://developer.mozilla.org/en-US/docs/Web/SVG/Element/script
	 */
	public function GetTagsBlackList()
	{
		return [
			'script',
		];
	}

	public function GetAttrsWhiteList()
	{
		return [];
	}

	/**
	 * @return string[]
	 * @link https://developer.mozilla.org/en-US/docs/Web/SVG/Attribute/Events#document_event_attributes
	 */
	public function GetAttrsBlackList()
	{
		return [
			'onbegin',
			'onbegin',
			'onrepeat',
			'onabort',
			'onerror',
			'onerror',
			'onscroll',
			'onunload',
			'oncopy',
			'oncut',
			'onpaste',
			'oncancel',
			'oncanplay',
			'oncanplaythrough',
			'onchange',
			'onclick',
			'onclose',
			'oncuechange',
			'ondblclick',
			'ondrag',
			'ondragend',
			'ondragenter',
			'ondragleave',
			'ondragover',
			'ondragstart',
			'ondrop',
			'ondurationchange',
			'onemptied',
			'onended',
			'onerror',
			'onfocus',
			'oninput',
			'oninvalid',
			'onkeydown',
			'onkeypress',
			'onkeyup',
			'onload',
			'onloadeddata',
			'onloadedmetadata',
			'onloadstart',
			'onmousedown',
			'onmouseenter',
			'onmouseleave',
			'onmousemove',
			'onmouseout',
			'onmouseover',
			'onmouseup',
			'onmousewheel',
			'onpause',
			'onplay',
			'onplaying',
			'onprogress',
			'onratechange',
			'onreset',
			'onresize',
			'onscroll',
			'onseeked',
			'onseeking',
			'onselect',
			'onshow',
			'onstalled',
			'onsubmit',
			'onsuspend',
			'ontimeupdate',
			'ontoggle',
			'onvolumechange',
			'onwaiting',
			'onactivate',
			'onfocusin',
			'onfocusout',
		];
	}

	public function GetStylesWhiteList()
	{
		return [];
	}

	public function LoadDoc($sHTML)
	{
		@$this->oDoc->loadXml($sHTML, LIBXML_NOBLANKS);
	}

	public function PrintDoc()
	{
		return $this->oDoc->saveXML();
	}
}
