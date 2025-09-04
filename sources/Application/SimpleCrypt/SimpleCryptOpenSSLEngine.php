<?php

class SimpleCryptOpenSSLEngine implements CryptEngine
{
    public static function GetNewDefaultParams()
    {
        return array('lib' => 'OpenSSL', 'key' => bin2hex(openssl_random_pseudo_bytes(32)));
    }

    public function Encrypt($key, $sString)
    {
        $key = hex2bin($key);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length("AES-256-CBC"));
        $encrypted_string = openssl_encrypt($sString, "AES-256-CBC", $key, 0, $iv);
        return $iv . $encrypted_string;
    }

    public function Decrypt($key, $encrypted_data)
    {
        $key = hex2bin($key);
        $iv = mb_substr($encrypted_data, 0, openssl_cipher_iv_length("AES-256-CBC"), '8bit');
        $encrypted_data = mb_substr($encrypted_data, openssl_cipher_iv_length("AES-256-CBC"), null, '8bit');
        $plaintext = openssl_decrypt($encrypted_data, "AES-256-CBC", $key, 0, $iv);
        if ($plaintext === false) {
            $plaintext = Dict::S("Core:AttributeEncryptFailedToDecrypt");
        }
        return trim($plaintext);
    }

}