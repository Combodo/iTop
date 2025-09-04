<?php

/**
 * Specialization of a string: OQL expression
 *
 * @package     iTopORM
 */
class AttributeOQL extends AttributeText
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

    public function GetEditClass()
    {
        return "OQLExpression";
    }
}