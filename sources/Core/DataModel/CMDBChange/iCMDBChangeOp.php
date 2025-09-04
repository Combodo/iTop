<?php

/**
 * Interface iCMDBChangeOp
 *
 * @since 3.0.0
 */
interface iCMDBChangeOp
{
    /**
     * Describe (as an HTML string) the modifications corresponding to this change
     *
     * @return string
     */
    public function GetDescription();
}