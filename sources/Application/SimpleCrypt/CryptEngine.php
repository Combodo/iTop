<?php

/**
 * Interface for encryption engines
 */
interface CryptEngine
{
    public static function GetNewDefaultParams();

    function Encrypt($key, $sString);

    function Decrypt($key, $encrypted_data);
}