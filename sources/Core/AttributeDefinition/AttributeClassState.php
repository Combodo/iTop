<?php

/**
 * An attribute that matches a class state
 *
 * @package     iTopORM
 */
class AttributeClassState extends AttributeString
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

    public static function ListExpectedParams()
    {
        return array_merge(parent::ListExpectedParams(), array('class_field'));
    }

    public function GetAllowedValues($aArgs = array(), $sContains = '')
    {
        if (isset($aArgs['this'])) {
            $oHostObj = $aArgs['this'];
            $sTargetClass = $this->Get('class_field');
            $sClass = $oHostObj->Get($sTargetClass);

            $aAllowedStates = array();
            foreach (MetaModel::EnumChildClasses($sClass, ENUM_CHILD_CLASSES_ALL) as $sChildClass) {
                $aValues = MetaModel::EnumStates($sChildClass);
                foreach (array_keys($aValues) as $sState) {
                    $aAllowedStates[$sState] = $sState . ' (' . MetaModel::GetStateLabel($sChildClass, $sState) . ')';
                }
            }
            return $aAllowedStates;
        }

        return null;
    }

    public function GetAsHTML($sValue, $oHostObject = null, $bLocalize = true)
    {
        if (empty($sValue)) {
            return '';
        }

        if (!empty($oHostObject)) {
            $sTargetClass = $this->Get('class_field');
            $sClass = $oHostObject->Get($sTargetClass);
            foreach (MetaModel::EnumChildClasses($sClass, ENUM_CHILD_CLASSES_ALL) as $sChildClass) {
                $aValues = MetaModel::EnumStates($sChildClass);
                if (in_array($sValue, $aValues)) {
                    $sLabelForHtmlAttribute = utils::EscapeHtml($sValue . ' (' . MetaModel::GetStateLabel($sChildClass, $sValue) . ')');
                    $sHTML = '<span class="attribute-set-item" data-code="' . $sValue . '" data-label="' . $sLabelForHtmlAttribute . '" data-description="" data-tooltip-content="' . $sLabelForHtmlAttribute . '">' . $sValue . '</span>';

                    return $sHTML;
                }
            }
        }

        return $sValue;
    }

}