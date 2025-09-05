<?php

/**
 * Implement this interface to perform specific operations when objects are manipulated
 *
 * Note that those methods will be called when objects are manipulated, either in a programmatic way
 * or through the GUI.
 *
 * @api
 * @deprecated  3.1.0 N°4756 use the new event service instead, see {@see DBObject::FireEvent()} method. More details on each method PHPDoc.
 * @package     ORMExtensibilityAPI
 */
interface iApplicationObjectExtension
{
    /**
     * Invoked to determine whether an object has been modified in memory
     *
     * The GUI calls this verb to determine the message that will be displayed to the end-user.
     * Anyhow, this API can be called in other contexts such as the CSV import tool.
     *
     * If the extension returns false, then the framework will perform the usual evaluation.
     * Otherwise, the answer is definitively "yes, the object has changed".
     *
     * @param /cmdbAbstractObject $oObject The target object
     *
     * @return boolean True if something has changed for the target object
     * @api
     * @deprecated 3.1.0 N°4756 No alternative available, this API was unstable and is abandoned
     */
    public function OnIsModified($oObject);

    /**
     * Invoked to determine whether an object can be written to the database
     *
     * The GUI calls this verb and reports any issue.
     * Anyhow, this API can be called in other contexts such as the CSV import tool.
     *
     * @param \cmdbAbstractObject $oObject The target object
     *
     * @return string[] A list of errors message. An error message is made of one line and it can be displayed to the end-user.
     * @api
     * @deprecated 3.1.0 N°4756 Use EVENT_DB_CHECK_TO_WRITE event instead
     */
    public function OnCheckToWrite($oObject);

    /**
     * Invoked to determine wether an object can be deleted from the database
     *
     * The GUI calls this verb and stops the deletion process if any issue is reported.
     *
     * Please not that it is not possible to cascade deletion by this mean: only stopper issues can be handled.
     *
     * @param \cmdbAbstractObject $oObject The target object
     *
     * @return string[] A list of errors message. An error message is made of one line and it can be displayed to the end-user.
     * @api
     * @deprecated 3.1.0 N°4756 Use EVENT_DB_CHECK_TO_DELETE event instead
     */
    public function OnCheckToDelete($oObject);

    /**
     * Invoked when an object is updated into the database. The method is called right <b>after</b> the object has been written to the
     * database.
     *
     * Useful methods you can call on $oObject :
     *
     * * {@see DBObject::ListPreviousValuesForUpdatedAttributes()} : list of changed attributes and their values before the change
     * * {@see DBObject::Get()} : for a given attribute the new value that was persisted
     *
     * @param \cmdbAbstractObject $oObject The target object
     * @param CMDBChange|null $oChange A change context. Since 2.0 it is fine to ignore it, as the framework does maintain this information
     *     once for all the changes made within the current page
     *
     * @return void
     *
     * @deprecated 3.1.0 N°4756 Use EVENT_DB_AFTER_WRITE event instead
     * @api
     * @since 2.7.0 N°2293 can access object changes by calling {@see DBObject::ListPreviousValuesForUpdatedAttributes()} on $oObject
     */
    public function OnDBUpdate($oObject, $oChange = null);

    /**
     * Invoked when an object is created into the database
     *
     * The method is called right <b>after</b> the object has been written to the database.
     *
     * @param \cmdbAbstractObject $oObject The target object
     * @param CMDBChange|null $oChange A change context. Since 2.0 it is fine to ignore it, as the framework does maintain this information
     *     once for all the changes made within the current page
     *
     * @return void
     * @deprecated 3.1.0 N°4756 Use EVENT_DB_AFTER_WRITE event instead
     * @api
     */
    public function OnDBInsert($oObject, $oChange = null);

    /**
     * Invoked when an object is deleted from the database
     *
     * The method is called right <b>before</b> the object will be deleted from the database.
     *
     * @param \cmdbAbstractObject $oObject The target object
     * @param CMDBChange|null $oChange A change context. Since 2.0 it is fine to ignore it, as the framework does maintain this information
     *     once for all the changes made within the current page
     *
     * @return void
     * @deprecated 3.1.0 N°4756 Use EVENT_DB_AFTER_DELETE event instead
     * @api
     */
    public function OnDBDelete($oObject, $oChange = null);
}