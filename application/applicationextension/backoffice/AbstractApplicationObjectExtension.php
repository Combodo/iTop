<?php

/**
 * Extend this class instead of iApplicationObjectExtension if you don't need to overload all methods
 *
 * @api
 * @deprecated  3.1.0 N°4756 use the new event service instead, see {@see DBObject::FireEvent()} method
 * @package     ORMExtensibilityAPI
 * @since       2.7.0
 */
abstract class AbstractApplicationObjectExtension implements iApplicationObjectExtension
{
    /**
     * @inheritDoc
     */
    public function OnIsModified($oObject)
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function OnCheckToWrite($oObject)
    {
        return array();
    }

    /**
     * @inheritDoc
     */
    public function OnCheckToDelete($oObject)
    {
        return array();
    }

    /**
     * @inheritDoc
     */
    public function OnDBUpdate($oObject, $oChange = null)
    {
    }

    /**
     * @inheritDoc
     */
    public function OnDBInsert($oObject, $oChange = null)
    {
    }

    /**
     * @inheritDoc
     */
    public function OnDBDelete($oObject, $oChange = null)
    {
    }

}