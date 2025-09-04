<?php

/**
 * Simple Engine doesn't need any PHP extension.
 * Every encryption of the same string with the same key
 * will return the same encrypted string
 */
class SimpleCryptSimpleEngine implements CryptEngine
{
    public static function GetNewDefaultParams()
    {
        return array('lib' => 'Simple', 'key' => null);
    }

    public function Encrypt($key, $sString)
    {
        $result = '';
        for ($i = 1; $i <= strlen($sString); $i++) {
            $char = substr($sString, $i - 1, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result .= $char;
        }
        return $result;
    }

    public function Decrypt($key, $encrypted_data)
    {
        $result = '';
        for ($i = 1; $i <= strlen($encrypted_data); $i++) {
            $char = substr($encrypted_data, $i - 1, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result .= $char;
        }
        return $result;
    }
}