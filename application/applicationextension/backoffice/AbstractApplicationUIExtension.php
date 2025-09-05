<?php

/**
 * Extend this class instead of implementing iApplicationUIExtension if you don't need to overload
 *
 * @api
 * @package     UIExtensibilityAPI
 * @since       2.7.0
 */
abstract class AbstractApplicationUIExtension implements iApplicationUIExtension
{
    /**
     * @inheritDoc
     */
    public function OnDisplayProperties($oObject, \Combodo\iTop\Application\WebPage\WebPage $oPage, $bEditMode = false)
    {
    }

    /**
     * @inheritDoc
     */
    public function OnDisplayRelations($oObject, \Combodo\iTop\Application\WebPage\WebPage $oPage, $bEditMode = false)
    {
    }

    /**
     * @inheritDoc
     */
    public function OnFormSubmit($oObject, $sFormPrefix = '')
    {
    }

    /**
     * @inheritDoc
     */
    public function OnFormCancel($sTempId)
    {
    }

    /**
     * @inheritDoc
     */
    public function EnumUsedAttributes($oObject)
    {
        return array();
    }

    /**
     * @inheritDoc
     */
    public function GetIcon($oObject)
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function GetHilightClass($oObject)
    {
        return HILIGHT_CLASS_NONE;
    }

    /**
     * @inheritDoc
     */
    public function EnumAllowedActions(DBObjectSet $oSet)
    {
        return array();
    }

}