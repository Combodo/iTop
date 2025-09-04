<?php

/**
 * SodiumEngine requires Sodium extension
 * Every encryption of the same string with the same key
 * will return a different encrypted string.
 * The key has to be SODIUM_CRYPTO_SECRETBOX_KEYBYTES bytes long.
 */
class SimpleCryptSodiumEngine implements CryptEngine
{
    public static function GetNewDefaultParams()
    {
        return array('lib' => 'Sodium', 'key' => bin2hex(sodium_crypto_secretbox_keygen()));
    }

    public function Encrypt($key, $sString)
    {
        $key = hex2bin($key);
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $encrypted_string = sodium_crypto_secretbox($sString, $nonce, $key);
        sodium_memzero($sString);
        sodium_memzero($key);
        return base64_encode($nonce . $encrypted_string);
    }

    public function Decrypt($key, $encrypted_data)
    {
        $key = hex2bin($key);
        $encrypted_data = base64_decode($encrypted_data);
        $nonce = mb_substr($encrypted_data, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
        $encrypted_data = mb_substr($encrypted_data, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');
        $plaintext = sodium_crypto_secretbox_open($encrypted_data, $nonce, $key);
        if ($plaintext === false) {
            $plaintext = Dict::S("Core:AttributeEncryptFailedToDecrypt");
        }
        sodium_memzero($encrypted_data);
        sodium_memzero($key);
        return $plaintext;
    }

}