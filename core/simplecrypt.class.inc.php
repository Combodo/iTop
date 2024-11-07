<?php
// Copyright (C) 2010-2024 Combodo SAS
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

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
		if(function_exists('sodium_crypto_secretbox_open') && function_exists('random_bytes')){
			$sEngineName = 'Sodium';
		}
		else if (function_exists('openssl_decrypt'))
		{
			$sEngineName = 'OpenSSL';
		}
		else if(function_exists('mcrypt_module_open')){
			$sEngineName = 'Mcrypt';
		}
		else
		{
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
    	switch($sEngineName){
		    case 'Sodium':
		    	if(!function_exists('sodium_crypto_secretbox_open')){
					    $sEngineName = 'Simple';
			    }
		    	break;
		    case 'Mcrypt':
			    if(!function_exists('mcrypt_module_open')){
				    if (function_exists('openssl_decrypt'))
				    {
					    $sEngineName = 'OpenSSLMcryptCompatibility';
				    }
				    else
				    {
					    $sEngineName = 'Simple';
				    }
			    }
			    break;
		    case 'OpenSSL':
		    case 'OpenSSLMcryptCompatibility':
		    if(!function_exists('openssl_decrypt')){
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
        return $this->oEngine->Encrypt($key,$sString);
    }


    /**
     * Decrypts the string by the given key
     * @param string $key
     * @param string $string Ciphered string
     * @return string Plaintext string
     */
    function Decrypt($key, $string)
    {
        return $this->oEngine->Decrypt($key,$string);
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
		$fp = @fopen('/dev/urandom','rb');
		if ($fp !== FALSE)
		{
			//echo "Random bits pulled from /dev/urandom<br/>\n";
		    $sRandomBits .= @fread($fp,16);
		    @fclose($fp);
		}
		else
		{
			// MS-Windows platform?
			if (@class_exists('COM'))
			{
				// http://msdn.microsoft.com/en-us/library/aa388176(VS.85).aspx
				try
				{
				    $CAPI_Util = new COM('CAPICOM.Utilities.1');
				    $sBase64RandomBits = ''.$CAPI_Util->GetRandom(16,0);

				    // if we ask for binary data PHP munges it, so we
				    // request base64 return value.  We squeeze out the
				    // redundancy and useless ==CRLF by hashing...
				    if ($sBase64RandomBits)
				    {
						//echo "Random bits got from CAPICOM.Utilities.1<br/>\n";
				    	$sRandomBits = md5($sBase64RandomBits, TRUE);
				    }
				}
				catch (Exception $ex)
				{
				    // echo 'Exception: ' . $ex->getMessage();
				}
			}
		}
		if ($sRandomBits == null)
		{
			// No "strong" random generator available, use PHP's built-in mechanism
			//echo "Random bits generated from mt_rand<br/>\n";
			mt_srand(crc32(microtime()));
			$sRandomBits = '';
			for($i = 0; $i < 4; $i++)
			{
				$sRandomBits .= sprintf('%04x', mt_rand(0, 65535));
			}


		}
		return $sRandomBits;
    }
}

/**
 * Interface for encryption engines
 */
interface CryptEngine
{
	public static function GetNewDefaultParams();
	function Encrypt($key, $sString);
	function Decrypt($key, $encrypted_data);
}

/**
 * Simple Engine doesn't need any PHP extension.
 * Every encryption of the same string with the same key
 * will return the same encrypted string
 */
class SimpleCryptSimpleEngine implements CryptEngine
{
	public static function GetNewDefaultParams()
	{
		return array( 'lib' => 'Simple', 'key' => null);
	}

	public function Encrypt($key, $sString)
    {
        $result = '';
        for($i=1; $i<=strlen($sString); $i++)
        {
            $char = substr($sString, $i-1, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char)+ord($keychar));
            $result.=$char;
        }
        return $result;
    }

    public function Decrypt($key, $encrypted_data)
    {
        $result = '';
        for($i=1; $i<=strlen($encrypted_data); $i++)
        {
            $char = substr($encrypted_data, $i-1, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char)-ord($keychar));
            $result.=$char;
        }
        return $result;
    }
}

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
		$this->td = mcrypt_module_open($this->alg,'','cbc','');
	}

    public function Encrypt($key, $sString)
    {
		$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($this->td), MCRYPT_DEV_URANDOM); // MCRYPT_DEV_URANDOM is now useable since itop requires php >= 5.6
	    if (false === $iv) {
		    throw new Exception('IV generation failed');
	    }
		mcrypt_generic_init($this->td, $key, $iv);
		if (empty($sString))
		{
			$sString = str_repeat("\0", 8);
		}
		$encrypted_data = mcrypt_generic($this->td, $sString);
		mcrypt_generic_deinit($this->td);
        return $iv.$encrypted_data;
    }

    public function Decrypt($key, $encrypted_data)
    {
        $iv = substr($encrypted_data, 0, mcrypt_enc_get_iv_size($this->td));
        $string = substr($encrypted_data, mcrypt_enc_get_iv_size($this->td));
		$r = mcrypt_generic_init($this->td, $key, $iv);
		if (($r < 0) || ($r === false))
		{
			$decrypted_data = Dict::S("Core:AttributeEncryptFailedToDecrypt");
		}
		else
		{
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
		return base64_encode($nonce.$encrypted_string);
	}

	public function Decrypt($key, $encrypted_data)
	{
		$key = hex2bin($key);
		$encrypted_data = base64_decode($encrypted_data);
		$nonce = mb_substr($encrypted_data, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
		$encrypted_data = mb_substr($encrypted_data, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');
		$plaintext = sodium_crypto_secretbox_open($encrypted_data, $nonce, $key);
		if ($plaintext === false)
		{
			$plaintext = Dict::S("Core:AttributeEncryptFailedToDecrypt");
		}
		sodium_memzero($encrypted_data);
		sodium_memzero($key);
		return $plaintext;
	}

}
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
		$encrypted_string = openssl_encrypt($sString, "AES-256-CBC", $key, 0 , $iv);
		return $iv.$encrypted_string;
	}

	public function Decrypt($key, $encrypted_data)
	{
		$key = hex2bin($key);
		$iv = mb_substr($encrypted_data, 0, openssl_cipher_iv_length("AES-256-CBC"), '8bit');
		$encrypted_data = mb_substr($encrypted_data, openssl_cipher_iv_length("AES-256-CBC"), null, '8bit');
		$plaintext = openssl_decrypt($encrypted_data,"AES-256-CBC", $key, 0 , $iv);
		if ($plaintext === false)
		{
			$plaintext = Dict::S("Core:AttributeEncryptFailedToDecrypt");
		}
		return trim($plaintext);
	}

}

class SimpleCryptOpenSSLMcryptCompatibilityEngine implements CryptEngine
{
	public static function GetNewDefaultParams()
	{
		return array('lib' => 'OpenSSLMcryptCompatibility', 'key' => null);
	}
	//fix for php < 7.1.8 (keys are Zero padded instead of cycle padded)
	static private function MakeOpenSSLBlowfishKey($key)
	{
		if("$key" === '')
		{
			return $key;
		}
		$len = (16+2)*4;
		while(strlen($key) < $len)
		{
			$key .= $key;
		}
		$key = substr($key, 0, $len);
		return $key;
	}
	public function Encrypt($key, $sString)
	{
		$key = SimpleCryptOpenSSLMcryptCompatibilityEngine::MakeOpenSSLBlowfishKey($key);
		$blockSize = 8;
		$len = strlen($sString);
		$paddingLen = intval (($len + $blockSize -1) / $blockSize) * $blockSize - $len;
		$padding = str_repeat("\0", $paddingLen);
		$sData = $sString . $padding;
		$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length("BF-CBC"));
		$encrypted_string = openssl_encrypt($sData, "BF-CBC", $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
		return $iv.$encrypted_string;
	}

	public function Decrypt($key, $encrypted_data)
	{
		$key = SimpleCryptOpenSSLMcryptCompatibilityEngine::MakeOpenSSLBlowfishKey($key);
		$iv = mb_substr($encrypted_data, 0, openssl_cipher_iv_length("BF-CBC"), '8bit');
		$encrypted_data = mb_substr($encrypted_data, openssl_cipher_iv_length("BF-CBC"), null, '8bit');
		$plaintext = openssl_decrypt($encrypted_data,"BF-CBC", $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
		if ($plaintext === false)
		{
			$plaintext = Dict::S("Core:AttributeEncryptFailedToDecrypt");
		}
		return trim($plaintext);
	}

}
