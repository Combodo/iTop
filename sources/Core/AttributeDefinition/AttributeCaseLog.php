<?php

/**
 * An attibute that stores a case log (i.e journal)
 *
 * @package     iTopORM
 */
class AttributeCaseLog extends AttributeLongText
{
    const SEARCH_WIDGET_TYPE = self::SEARCH_WIDGET_TYPE_STRING;

    /**
     * Useless constructor, but if not present PHP 7.4.0/7.4.1 is crashing :( (N°2329)
     *
     * @see https://www.php.net/manual/fr/language.oop5.decon.php states that child constructor can be ommited
     * @see https://bugs.php.net/bug.php?id=79010 bug solved in PHP 7.4.9
     *
     * @param string $sCode
     * @param array $aParams
     *
     * @throws \Exception
     * @noinspection SenselessProxyMethodInspection
     */
    public function __construct($sCode, $aParams)
    {
        parent::__construct($sCode, $aParams);
    }

    public function GetNullValue()
    {
        return '';
    }

    public function IsNull($proposedValue)
    {
        if (!($proposedValue instanceof ormCaseLog)) {
            return ($proposedValue == '');
        }

        return ($proposedValue->GetText() == '');
    }

    /**
     * @inheritDoc
     * @param \ormCaseLog $proposedValue
     */
    public function HasAValue($proposedValue): bool
    {
        // Protection against wrong value type
        if (false === ($proposedValue instanceof ormCaseLog)) {
            return parent::HasAValue($proposedValue);
        }

        // We test if there is at least 1 entry in the log, not if the user is adding one
        return $proposedValue->GetEntryCount() > 0;
    }


    public function ScalarToSQL($value)
    {
        if (!is_string($value) && !is_null($value)) {
            throw new CoreWarning('Expected the attribute value to be a string', array(
                'found_type' => gettype($value),
                'value' => $value,
                'class' => $this->GetCode(),
                'attribute' => $this->GetHostClass()
            ));
        }

        return $value;
    }

    public function GetEditClass()
    {
        return "CaseLog";
    }

    public function GetEditValue($sValue, $oHostObj = null)
    {
        if (!($sValue instanceof ormCaseLog)) {
            return '';
        }

        return $sValue->GetModifiedEntry();
    }

    /**
     * For fields containing a potential markup, return the value without this markup
     *
     * @param mixed $value
     * @param \DBObject $oHostObj
     *
     * @return string
     */
    public function GetAsPlainText($value, $oHostObj = null)
    {
        if ($value instanceof ormCaseLog) {
            /** ormCaseLog $value */
            return $value->GetAsPlainText();
        } else {
            return (string)$value;
        }
    }

    public function GetDefaultValue(DBObject $oHostObject = null)
    {
        return new ormCaseLog();
    }

    public function Equals($val1, $val2)
    {
        return ($val1->GetText() == $val2->GetText());
    }


    /**
     * Facilitate things: allow the user to Set the value from a string
     *
     * @param $proposedValue
     * @param \DBObject $oHostObj
     *
     * @return mixed|null|\ormCaseLog|string
     * @throws \Exception
     */
    public function MakeRealValue($proposedValue, $oHostObj)
    {
        if ($proposedValue instanceof ormCaseLog) {
            // Passthrough
            $ret = clone $proposedValue;
        } else {
            // Append the new value if an instance of the object is supplied
            //
            $oPreviousLog = null;
            if ($oHostObj != null) {
                $oPreviousLog = $oHostObj->Get($this->GetCode());
                if (!is_object($oPreviousLog)) {
                    $oPreviousLog = $oHostObj->GetOriginal($this->GetCode());;
                }

            }
            if (is_object($oPreviousLog)) {
                $oCaseLog = clone($oPreviousLog);
            } else {
                $oCaseLog = new ormCaseLog();
            }

            if ($proposedValue instanceof stdClass) {
                $oCaseLog->AddLogEntryFromJSON($proposedValue);
            } else {
                if (utils::StrLen($proposedValue) > 0) {
                    //N°5135 - add impersonation information in caselog
                    if (UserRights::IsImpersonated()) {
                        $sOnBehalfOf = Dict::Format('UI:Archive_User_OnBehalfOf_User', UserRights::GetRealUserFriendlyName(), UserRights::GetUserFriendlyName());
                        $oCaseLog->AddLogEntry($proposedValue, $sOnBehalfOf, UserRights::GetConnectedUserId());
                    } else {
                        $oCaseLog->AddLogEntry($proposedValue);
                    }
                }
            }
            $ret = $oCaseLog;
        }

        return $ret;
    }

    public function GetSQLExpressions($sPrefix = '')
    {
        if ($sPrefix == '') {
            $sPrefix = $this->Get('sql');
        }
        $aColumns = array();
        // Note: to optimize things, the existence of the attribute is determined by the existence of one column with an empty suffix
        $aColumns[''] = $sPrefix;
        $aColumns['_index'] = $sPrefix . '_index';

        return $aColumns;
    }

    /**
     * @param array $aCols
     * @param string $sPrefix
     *
     * @return \ormCaseLog
     * @throws \MissingColumnException
     */
    public function FromSQLToValue($aCols, $sPrefix = '')
    {
        if (!array_key_exists($sPrefix, $aCols)) {
            $sAvailable = implode(', ', array_keys($aCols));
            throw new MissingColumnException("Missing column '$sPrefix' from {$sAvailable}");
        }
        $sLog = $aCols[$sPrefix];

        if (isset($aCols[$sPrefix . '_index'])) {
            $sIndex = $aCols[$sPrefix . '_index'];
        } else {
            // For backward compatibility, allow the current state to be: 1 log, no index
            $sIndex = '';
        }

        if (strlen($sIndex) > 0) {
            $aIndex = unserialize($sIndex);
            $value = new ormCaseLog($sLog, $aIndex);
        } else {
            $value = new ormCaseLog($sLog);
        }

        return $value;
    }

    public function GetSQLValues($value)
    {
        if (!($value instanceof ormCaseLog)) {
            $value = new ormCaseLog('');
        }
        $aValues = array();
        $aValues[$this->GetCode()] = $value->GetText();
        $aValues[$this->GetCode() . '_index'] = serialize($value->GetIndex());

        return $aValues;
    }

    public function GetSQLColumns($bFullSpec = false)
    {
        $aColumns = array();
        $aColumns[$this->GetCode()] = 'LONGTEXT' // 2^32 (4 Gb)
            . CMDBSource::GetSqlStringColumnDefinition();
        $aColumns[$this->GetCode() . '_index'] = 'BLOB';

        return $aColumns;
    }

    public function GetAsHTML($value, $oHostObject = null, $bLocalize = true)
    {
        if ($value instanceof ormCaseLog) {
            $sContent = $value->GetAsHTML(null, false, array(__class__, 'RenderWikiHtml'));
        } else {
            $sContent = '';
        }
        $aStyles = array();
        if ($this->GetWidth() != '') {
            $aStyles[] = 'width:' . $this->GetWidth();
        }
        if ($this->GetHeight() != '') {
            $aStyles[] = 'height:' . $this->GetHeight();
        }
        $sStyle = '';
        if (count($aStyles) > 0) {
            $sStyle = 'style="' . implode(';', $aStyles) . '"';
        }

        return "<div class=\"caselog\" $sStyle>" . $sContent . '</div>';
    }


    public function GetAsCSV(
        $value, $sSeparator = ',', $sTextQualifier = '"', $oHostObject = null, $bLocalize = true,
        $bConvertToPlainText = false
    )
    {
        if ($value instanceof ormCaseLog) {
            return parent::GetAsCSV($value->GetText($bConvertToPlainText), $sSeparator, $sTextQualifier, $oHostObject,
                $bLocalize, $bConvertToPlainText);
        } else {
            return '';
        }
    }

    public function GetAsXML($value, $oHostObject = null, $bLocalize = true)
    {
        if ($value instanceof ormCaseLog) {
            return parent::GetAsXML($value->GetText(), $oHostObject, $bLocalize);
        } else {
            return '';
        }
    }

    /**
     * List the available verbs for 'GetForTemplate'
     */
    public function EnumTemplateVerbs()
    {
        return array(
            '' => 'Plain text representation of all the log entries',
            'head' => 'Plain text representation of the latest entry',
            'head_html' => 'HTML representation of the latest entry',
            'html' => 'HTML representation of all the log entries',
        );
    }

    /**
     * Get various representations of the value, for insertion into a template (e.g. in Notifications)
     *
     * @param $value mixed The current value of the field
     * @param $sVerb string The verb specifying the representation of the value
     * @param $oHostObject DBObject The object
     * @param $bLocalize bool Whether or not to localize the value
     *
     * @return mixed
     * @throws \Exception
     */
    public function GetForTemplate($value, $sVerb, $oHostObject = null, $bLocalize = true)
    {
        switch ($sVerb) {
            case '':
                return $value->GetText(true);

            case 'head':
                return $value->GetLatestEntry('text');

            case 'head_html':
                return $value->GetLatestEntry('html');

            case 'html':
                return $value->GetAsEmailHtml();

            default:
                throw new Exception("Unknown verb '$sVerb' for attribute " . $this->GetCode() . ' in class ' . get_class($oHostObject));
        }
    }

    public function GetForJSON($value)
    {
        return $value->GetForJSON();
    }

    public function FromJSONToValue($json)
    {
        if (is_string($json)) {
            // Will be correctly handled in MakeRealValue
            $ret = $json;
        } else {
            if (isset($json->add_item)) {
                // Will be correctly handled in MakeRealValue
                $ret = $json->add_item;
                if (!isset($ret->message)) {
                    throw new Exception("Missing mandatory entry: 'message'");
                }
            } else {
                $ret = ormCaseLog::FromJSON($json);
            }
        }

        return $ret;
    }

    public function Fingerprint($value)
    {
        $sFingerprint = '';
        if ($value instanceof ormCaseLog) {
            $sFingerprint = $value->GetText();
        }

        return $sFingerprint;
    }

    /**
     * The actual formatting of the text: either text (=plain text) or html (= text with HTML markup)
     *
     * @return string
     */
    public function GetFormat()
    {
        return $this->GetOptional('format', 'html'); // default format for case logs is now HTML
    }

    public static function GetFormFieldClass()
    {
        return '\\Combodo\\iTop\\Form\\Field\\CaseLogField';
    }

    public function MakeFormField(DBObject $oObject, $oFormField = null)
    {
        // First we call the parent so the field is build
        $oFormField = parent::MakeFormField($oObject, $oFormField);
        // Then only we set the value
        $oFormField->SetCurrentValue($this->GetEditValue($oObject->Get($this->GetCode())));
        // And we set the entries
        $oFormField->SetEntries($oObject->Get($this->GetCode())->GetAsArray());

        return $oFormField;
    }

    protected function GetChangeRecordAdditionalData(CMDBChangeOp $oMyChangeOp, DBObject $oObject, $original, $value): void
    {
        /** @var \ormCaseLog $value */
        $oMyChangeOp->Set("lastentry", $value->GetLatestEntryIndex());
    }

    protected function GetChangeRecordClassName(): string
    {
        return CMDBChangeOpSetAttributeCaseLog::class;
    }
}