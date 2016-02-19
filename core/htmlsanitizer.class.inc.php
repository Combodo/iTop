<?php
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
	protected static $aTagsWhiteList = array(
		'html' => array(),
		'body' => array(),
		'a' => array('href', 'name', 'style'),
		'p' => array('style'),
		'br' => array(),
		'span' => array('style'),
		'div' => array('style'),
		'b' => array(),
		'i' => array(),
		'em' => array(),
		'strong' => array(),
		'img' => array('src','style'),
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
		'table' => array('style', 'width'),
		'thead' => array('style'),
		'tbody' => array('style'),
		'tr' => array('style'),
		'td' => array('style', 'colspan'),
		'th' => array('style'),
		'fieldset' => array('style'),
		'legend' => array('style'),
		'font' => array('face', 'color', 'style', 'size'),
		'big' => array(),
		'small' => array(),
		'tt' => array(),
		'code' => array(),
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
		'center' => array(),
	);
	
	protected static $aAttrsWhiteList = array(
		'href' => '/^(http:|https:)/i',
		'src' => '/^(http:|https:|data:)/i',
	);
	
	protected static $aStylesWhiteList = array(
		'background-color', 'color', 'font', 'font-style', 'font-size', 'font-family', 'padding', 'margin', 'border', 'cellpadding', 'cellspacing', 'bordercolor', 'border-collapse', 'width', 'height',
	);
	
	public function DoSanitize($sHTML)
	{
		$this->oDoc = new DOMDocument();
		$this->oDoc->preserveWhitespace = true;
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
	 * Add an extra attribute data-att-id for images which are based on an actual attachment
	 * so that we can later reconstruct the full "src" URL when needed
	 * @param DOMNode $oElement
	 */
	protected function ProcessImage(DOMNode $oElement)
	{
		$sSrc = $oElement->getAttribute('src');
		$sDownloadUrl = str_replace(array('.', '?'), array('\.', '\?'), ATTACHMENT_DOWNLOAD_URL); // Escape . and ?
		$sUrlPattern = '|'.$sDownloadUrl.'([0-9]+)|';
		if (preg_match($sUrlPattern, $sSrc, $aMatches))
		{
			$oElement->setAttribute('data-att-id', $aMatches[1]);
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