<?php

/**
 * McryptEngine requires Mcrypt extension
 * Every encryption of the same string with the same key
 * will return a different encrypted string.
 */
class SimpleCryptMcryptEngine implements CryptEngine
{
    var $alg = MCRYPT_BLOWFISH;
    var $td = null;

    public static function GetNewDefaultParams()
    {
        return array('lib' => 'Mcrypt', 'key' => null);
    }


    public function __construct()
    {
        $this->td = mcrypt_module_open($this->alg, '', 'cbc', '');
    }

    public function Encrypt($key, $sString)
    {
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($this->td), MCRYPT_DEV_URANDOM); // MCRYPT_DEV_URANDOM is now useable since itop requires php >= 5.6
        if (false === $iv) {
            throw new Exception('IV generation failed');
        }
        mcrypt_generic_init($this->td, $key, $iv);
        if (empty($sString)) {
            $sString = str_repeat("\0", 8);
        }
        $encrypted_data = mcrypt_generic($this->td, $sString);
        mcrypt_generic_deinit($this->td);
        return $iv . $encrypted_data;
    }

    public function Decrypt($key, $encrypted_data)
    {
        $iv = substr($encrypted_data, 0, mcrypt_enc_get_iv_size($this->td));
        $string = substr($encrypted_data, mcrypt_enc_get_iv_size($this->td));
        $r = mcrypt_generic_init($this->td, $key, $iv);
        if (($r < 0) || ($r === false)) {
            $decrypted_data = Dict::S("Core:AttributeEncryptFailedToDecrypt");
        } else {
            $decrypted_data = rtrim(mdecrypt_generic($this->td, $string), "\0");
            mcrypt_generic_deinit($this->td);
        }
        return $decrypted_data;
    }

    public function __destruct()
    {
        mcrypt_module_close($this->td);
    }
}