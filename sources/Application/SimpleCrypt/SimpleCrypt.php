<?php

/**
 * SimpleCrypt Class - crypto helpers
 * Simple encryption of strings, uses mcrypt or degrades to a pure PHP
 * implementation when mcrypt is not present.
 * Based on Miguel Ros' work found at:
 * http://rossoft.wordpress.com/2006/05/22/simple-encryption-class/
 *
 * Usage:
 * $oSimpleCrypt = new SimpleCrypt();
 * $encrypted = $oSimpleCrypt->encrypt('a_key','the_text');
 * $sClearText = $oSimpleCrypt->decrypt('a_key',$encrypted);
 *
 * The result is $plain equals to 'the_text'
 *
 * You can use a different engine if you don't have Mcrypt:
 * $oSimpleCrypt = new SimpleCrypt('Simple');
 *
 * A string encrypted with one engine can't be decrypted with
 * a different one even if the key is the same.
 *
 * @author      Miguel Ros <rossoft@gmail.com>
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */
class SimpleCrypt
{
    /**
     * @var \SimpleCrypt
     * @since 3.1.0 N°5388
     */
    protected $oEngine;

    public static function GetNewDefaultParams()
    {
        if (function_exists('sodium_crypto_secretbox_open') && function_exists('random_bytes')) {
            $sEngineName = 'Sodium';
        } else if (function_exists('openssl_decrypt')) {
            $sEngineName = 'OpenSSL';
        } else if (function_exists('mcrypt_module_open')) {
            $sEngineName = 'Mcrypt';
        } else {
            $sEngineName = 'Simple';
        }
        $sEngineName = 'SimpleCrypt' . $sEngineName . 'Engine';
        return $sEngineName::GetNewDefaultParams();
    }

    /**
     * Constructor
     * @param string $sEngineName Engine for encryption. Values: Simple, Mcrypt, Sodium or OpenSSL
     * @throws Exception This library is unkown
     */
    function __construct($sEngineName = 'Mcrypt')
    {
        switch ($sEngineName) {
            case 'Sodium':
                if (!function_exists('sodium_crypto_secretbox_open')) {
                    $sEngineName = 'Simple';
                }
                break;
            case 'Mcrypt':
                if (!function_exists('mcrypt_module_open')) {
                    if (function_exists('openssl_decrypt')) {
                        $sEngineName = 'OpenSSLMcryptCompatibility';
                    } else {
                        $sEngineName = 'Simple';
                    }
                }
                break;
            case 'OpenSSL':
            case 'OpenSSLMcryptCompatibility':
                if (!function_exists('openssl_decrypt')) {
                    $sEngineName = 'Simple';
                }
                break;
            case 'Simple':
                break;
            default:
                throw new Exception(Dict::Format("Core:AttributeEncryptUnknownLibrary", $sEngineName));
        }

        $sEngineName = 'SimpleCrypt' . $sEngineName . 'Engine';
        $this->oEngine = new $sEngineName;
    }

    /**
     * Encrypts the string with the given key
     * @param string $key
     * @param string $sString Plaintext string
     * @return string Ciphered string
     */
    function Encrypt($key, $sString)
    {
        return $this->oEngine->Encrypt($key, $sString);
    }


    /**
     * Decrypts the string by the given key
     * @param string $key
     * @param string $string Ciphered string
     * @return string Plaintext string
     */
    function Decrypt($key, $string)
    {
        return $this->oEngine->Decrypt($key, $string);
    }

    /**
     * Returns a random "salt" value, to be used when "hashing" a password
     * using a one-way encryption algorithm, to prevent an attack using a "rainbow table"
     * Tryes to use the best available random number generator
     * @return string The generated random "salt"
     */
    static function GetNewSalt()
    {
        // Copied from http://www.php.net/manual/en/function.mt-rand.php#83655
        // get 128 pseudorandom bits in a string of 16 bytes

        $sRandomBits = null;

        // Unix/Linux platform?
        $fp = @fopen('/dev/urandom', 'rb');
        if ($fp !== FALSE) {
            //echo "Random bits pulled from /dev/urandom<br/>\n";
            $sRandomBits .= @fread($fp, 16);
            @fclose($fp);
        } else {
            // MS-Windows platform?
            if (@class_exists('COM')) {
                // http://msdn.microsoft.com/en-us/library/aa388176(VS.85).aspx
                try {
                    $CAPI_Util = new COM('CAPICOM.Utilities.1');
                    $sBase64RandomBits = '' . $CAPI_Util->GetRandom(16, 0);

                    // if we ask for binary data PHP munges it, so we
                    // request base64 return value.  We squeeze out the
                    // redundancy and useless ==CRLF by hashing...
                    if ($sBase64RandomBits) {
                        //echo "Random bits got from CAPICOM.Utilities.1<br/>\n";
                        $sRandomBits = md5($sBase64RandomBits, TRUE);
                    }
                } catch (Exception $ex) {
                    // echo 'Exception: ' . $ex->getMessage();
                }
            }
        }
        if ($sRandomBits == null) {
            // No "strong" random generator available, use PHP's built-in mechanism
            //echo "Random bits generated from mt_rand<br/>\n";
            mt_srand(crc32(microtime()));
            $sRandomBits = '';
            for ($i = 0; $i < 4; $i++) {
                $sRandomBits .= sprintf('%04x', mt_rand(0, 65535));
            }


        }
        return $sRandomBits;
    }
}