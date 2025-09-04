<?php

/**
 * RowStatus
 * A series of classes, keeping the information about a given row: could it be changed or not (and why)?
 *
 * @package     iTopORM
 */
abstract class RowStatus
{
    public function __construct()
    {
    }

    abstract public function GetDescription();
}