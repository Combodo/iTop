<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use CMDBChangeOp;
use CMDBChangeOpSetAttributeHTML;
use CMDBChangeOpSetAttributeText;
use CMDBSource;
use Combodo\iTop\Form\Field\TextAreaField;
use CoreException;
use DBObject;
use Dict;
use Exception;
use HTMLSanitizer;
use InlineImage;
use MetaModel;
use ormCaseLog;
use Str;
use utils;

/**
 * Map a text column (size > ?) to an attribute
 *
 * @package     iTopORM
 */
class AttributeText extends AttributeString
{
	/**
	 * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
	 *
	 * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
	 * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
	 *
	 * @param string $sCode
	 * @param array $aParams
	 *
	 * @throws Exception
	 * @noinspection SenselessProxyMethodInspection
	 */
	public function __construct($sCode, $aParams)
	{
		parent::__construct($sCode, $aParams);
	}

	public function GetEditClass()
	{
		return ($this->GetFormat() == 'text') ? 'Text' : "HTML";
	}

	protected function GetSQLCol($bFullSpec = false)
	{
		return "TEXT".CMDBSource::GetSqlStringColumnDefinition();
	}

	public function GetSQLColumns($bFullSpec = false)
	{
		$aColumns = [];
		$aColumns[$this->Get('sql')] = $this->GetSQLCol($bFullSpec);
		if ($this->GetOptional('format', null) != null) {
			// Add the extra column only if the property 'format' is specified for the attribute
			$aColumns[$this->Get('sql').'_format'] = "ENUM('text','html')".CMDBSource::GetSqlStringColumnDefinition();
			if ($bFullSpec) {
				$aColumns[$this->Get('sql').'_format'] .= " DEFAULT 'text'"; // default 'text' is for migrating old records
			}
		}

		return $aColumns;
	}

	public function GetSQLExpressions($sPrefix = '')
	{
		if ($sPrefix == '') {
			$sPrefix = $this->Get('sql');
		}
		$aColumns = [];
		// Note: to optimize things, the existence of the attribute is determined by the existence of one column with an empty suffix
		$aColumns[''] = $sPrefix;
		if ($this->GetOptional('format', null) != null) {
			// Add the extra column only if the property 'format' is specified for the attribute
			$aColumns['_format'] = $sPrefix.'_format';
		}

		return $aColumns;
	}

	public function GetMaxSize()
	{
		// Is there a way to know the current limitation for mysql?
		// See mysql_field_len()
		return 65535;
	}

	/**
	 * @param string|null $sText
	 * @param bool $bWikiOnly
	 *
	 * @return string
	 * @throws \ArchivedObjectException
	 * @throws \ConfigException
	 * @throws \CoreException
	 * @throws \DictExceptionMissingString
	 *
	 * @since 3.3.0 N°8681 Add type hint for parameters and return value
	 */
	public static function RenderWikiHtml(string|null $sText, bool $bWikiOnly = false): string
	{
		// N°8681 - Ensure to have a string value
		$sText = $sText ?? '';

		if (!$bWikiOnly) {
			$sPattern = '/'.str_replace('/', '\/', utils::GetConfig()->Get('url_validation_pattern')).'/i';
			if (preg_match_all(
				$sPattern,
				$sText,
				$aAllMatches,
				PREG_SET_ORDER /* important !*/ | PREG_OFFSET_CAPTURE /* important ! */
			)) {
				$i = count($aAllMatches);
				// Replace the URLs by an actual hyperlink <a href="...">...</a>
				// Let's do it backwards so that the initial positions are not modified by the replacement
				// This works if the matches are captured: in the order they occur in the string  AND
				// with their offset (i.e. position) inside the string
				while ($i > 0) {
					$i--;
					$sUrl = $aAllMatches[$i][0][0]; // String corresponding to the main pattern
					$iPos = $aAllMatches[$i][0][1]; // Position of the main pattern
					$sText = substr_replace($sText, "<a href=\"$sUrl\">$sUrl</a>", $iPos, strlen($sUrl));

				}
			}
		}
		if (preg_match_all(WIKI_OBJECT_REGEXP, $sText, $aAllMatches, PREG_SET_ORDER)) {
			foreach ($aAllMatches as $iPos => $aMatches) {
				$sClass = trim($aMatches[1]);
				$sName = trim($aMatches[2]);
				$sLabel = (!empty($aMatches[4])) ? trim($aMatches[4]) : null;

				if (MetaModel::IsValidClass($sClass)) {
					$bFound = false;

					// Try to find by name, then by id
					if (is_object($oObj = MetaModel::GetObjectByName($sClass, $sName, false /* MustBeFound */))) {
						$bFound = true;
					} elseif (is_object($oObj = MetaModel::GetObject($sClass, (int)$sName, false /* MustBeFound */, true))) {
						$bFound = true;
					}

					if ($bFound === true) {
						// Propose a std link to the object
						$sHyperlinkLabel = (empty($sLabel)) ? $oObj->GetName() : $sLabel;
						$sText = str_replace($aMatches[0], $oObj->GetHyperlink(null, true, $sHyperlinkLabel), $sText);
					} else {
						// Propose a std link to the object
						$sClassLabel = MetaModel::GetName($sClass);
						$sToolTipForHtml = utils::EscapeHtml(Dict::Format('Core:UnknownObjectLabel', $sClass, $sName));
						$sReplacement = "<span class=\"wiki_broken_link ibo-is-broken-hyperlink\" data-tooltip-content=\"$sToolTipForHtml\">$sClassLabel:$sName".(!empty($sLabel) ? " ($sLabel)" : "")."</span>";
						$sText = str_replace($aMatches[0], $sReplacement, $sText);
						// Later: propose a link to create a new object
						// Anyhow... there is no easy way to suggest default values based on the given FRIENDLY name
						//$sText = preg_replace('/\[\[(.+):(.+)\]\]/', '<a href="'.utils::GetAbsoluteUrlAppRoot().'pages/UI.php?operation=new&class='.$sClass.'&default[att1]=xxx&default[att2]=yyy">'.$sName.'</a>', $sText);
					}
				}
			}
		}

		return $sText;
	}

	public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
	{
		$aStyles = [];
		if ($this->GetWidth() != '') {
			$aStyles[] = 'width:'.$this->GetWidth();
		}
		if ($this->GetHeight() != '') {
			$aStyles[] = 'height:'.$this->GetHeight();
		}
		$sStyle = '';
		if (count($aStyles) > 0) {
			$sStyle = 'style="'.implode(';', $aStyles).'"';
		}

		if ($this->GetFormat() == 'text') {
			$sValue = parent::GetAsHTML($sValue, $oHostObject, $bLocalize);
			$sValue = self::RenderWikiHtml($sValue);
			$sValue = nl2br($sValue);

			return "<div $sStyle>$sValue</div>";
		} else {
			$sValue = self::RenderWikiHtml($sValue, true /* wiki only */);

			return "<div class=\"HTML ibo-is-html-content\" $sStyle>".InlineImage::FixUrls($sValue).'</div>';
		}

	}

	public function GetEditValue($sValue, $oHostObj = null)
	{
		// N°4517 - PHP 8.1 compatibility: str_replace call with null cause deprecated message
		if ($sValue == null) {
			return '';
		}

		if ($this->GetFormat() == 'text') {
			if (preg_match_all(WIKI_OBJECT_REGEXP, $sValue, $aAllMatches, PREG_SET_ORDER)) {
				foreach ($aAllMatches as $iPos => $aMatches) {
					$sClass = trim($aMatches[1]);
					$sName = trim($aMatches[2]);
					$sLabel = (!empty($aMatches[4])) ? trim($aMatches[4]) : null;

					if (MetaModel::IsValidClass($sClass)) {
						$sClassLabel = MetaModel::GetName($sClass);
						$sReplacement = "[[$sClassLabel:$sName".(!empty($sLabel) ? " | $sLabel" : "")."]]";
						$sValue = str_replace($aMatches[0], $sReplacement, $sValue);
					}
				}
			}
		}

		return $sValue;
	}

	/**
	 * For fields containing a potential markup, return the value without this markup
	 *
	 * @param string $sValue
	 * @param DBObject $oHostObj
	 *
	 * @return string
	 */
	public function GetAsPlainText($sValue, $oHostObj = null)
	{
		if ($this->GetFormat() == 'html') {
			return (string)utils::HtmlToText($this->GetEditValue($sValue, $oHostObj));
		} else {
			return parent::GetAsPlainText($sValue, $oHostObj);
		}
	}

	public function MakeRealValue($proposedValue, $oHostObj)
	{
		$sValue = $proposedValue;

		// N°4517 - PHP 8.1 compatibility: str_replace call with null cause deprecated message
		if ($sValue == null) {
			return '';
		}

		switch ($this->GetFormat()) {
			case 'html':
				if (($sValue !== null) && ($sValue !== '')) {
					$sValue = HTMLSanitizer::Sanitize($sValue);
				}
				break;

			case 'text':
			default:
				if (preg_match_all(WIKI_OBJECT_REGEXP, $sValue, $aAllMatches, PREG_SET_ORDER)) {
					foreach ($aAllMatches as $iPos => $aMatches) {
						$sClassLabel = trim($aMatches[1]);
						$sName = trim($aMatches[2]);
						$sLabel = (!empty($aMatches[4])) ? trim($aMatches[4]) : null;

						if (!MetaModel::IsValidClass($sClassLabel)) {
							$sClass = MetaModel::GetClassFromLabel($sClassLabel);
							if ($sClass) {
								$sReplacement = "[[$sClassLabel:$sName".(!empty($sLabel) ? " | $sLabel" : "")."]]";
								$sValue = str_replace($aMatches[0], $sReplacement, $sValue);
							}
						}
					}
				}
		}

		return $sValue;
	}

	public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
	{
		return Str::pure2xml($value);
	}

	public function GetWidth()
	{
		return $this->GetOptional('width', '');
	}

	public function GetHeight()
	{
		return $this->GetOptional('height', '');
	}

	public static function GetFormFieldClass()
	{
		return '\\Combodo\\iTop\\Form\\Field\\TextAreaField';
	}

	/**
	 * @param DBObject $oObject
	 * @param TextAreaField $oFormField
	 *
	 * @return TextAreaField
	 * @throws CoreException
	 */
	public function MakeFormField(DBObject $oObject, $oFormField = null)
	{
		if ($oFormField === null) {
			$sFormFieldClass = static::GetFormFieldClass();
			/** @var TextAreaField $oFormField */
			$oFormField = new $sFormFieldClass($this->GetCode(), null, $oObject);
			$oFormField->SetFormat($this->GetFormat());
		}
		parent::MakeFormField($oObject, $oFormField);

		return $oFormField;
	}

	/**
	 * The actual formatting of the field: either text (=plain text) or html (= text with HTML markup)
	 *
	 * @return string
	 */
	public function GetFormat()
	{
		return $this->GetOptional('format', 'text');
	}

	/**
	 * Read the value from the row returned by the SQL query and transorms it to the appropriate
	 * internal format (either text or html)
	 *
	 * @see AttributeDBFieldVoid::FromSQLToValue()
	 *
	 * @param string $sPrefix
	 *
	 * @param array $aCols
	 *
	 * @return string
	 */
	public function FromSQLToValue($aCols, $sPrefix = '')
	{
		$value = $aCols[$sPrefix.''];
		if ($this->GetOptional('format', null) != null) {
			// Read from the extra column only if the property 'format' is specified for the attribute
			$sFormat = $aCols[$sPrefix.'_format'];
		} else {
			$sFormat = $this->GetFormat();
		}

		switch ($sFormat) {
			case 'text':
				if ($this->GetFormat() == 'html') {
					$value = utils::TextToHtml($value);
				}
				break;

			case 'html':
				if ($this->GetFormat() == 'text') {
					$value = utils::HtmlToText($value);
				} else {
					$value = InlineImage::FixUrls((string)$value);
				}
				break;

			default:
				// unknown format ??
		}

		return $value;
	}

	public function GetSQLValues($value)
	{
		$aValues = [];
		$aValues[$this->Get("sql")] = $this->ScalarToSQL($value);
		if ($this->GetOptional('format', null) != null) {
			// Add the extra column only if the property 'format' is specified for the attribute
			$aValues[$this->Get("sql").'_format'] = $this->GetFormat();
		}

		return $aValues;
	}

	public function GetAsCSV(
		$sValue,
		$sSeparator = ',',
		$sTextQualifier = '"',
		$oHostObject = null,
		$bLocalize = true,
		$bConvertToPlainText = false
	) {
		switch ($this->GetFormat()) {
			case 'html':
				if ($bConvertToPlainText) {
					$sValue = utils::HtmlToText((string)$sValue);
				}
				$sFrom = ["\r\n", $sTextQualifier];
				$sTo = ["\n", $sTextQualifier.$sTextQualifier];
				$sEscaped = str_replace($sFrom, $sTo, (string)$sValue);

				return $sTextQualifier.$sEscaped.$sTextQualifier;
				break;

			case 'text':
			default:
				return parent::GetAsCSV(
					$sValue,
					$sSeparator,
					$sTextQualifier,
					$oHostObject,
					$bLocalize,
					$bConvertToPlainText
				);
		}
	}

	protected function GetChangeRecordAdditionalData(CMDBChangeOp $oMyChangeOp, DBObject $oObject, $original, $value): void
	{
		/** @noinspection PhpConditionCheckedByNextConditionInspection */
		if (false === is_null($original) && ($original instanceof ormCaseLog)) {
			$original = $original->GetText();
		}
		$oMyChangeOp->Set("prevdata", $original);
	}

	protected function GetChangeRecordClassName(): string
	{
		return ($this->GetFormat() === 'html')
			? CMDBChangeOpSetAttributeHTML::class
			: CMDBChangeOpSetAttributeText::class;
	}
}
