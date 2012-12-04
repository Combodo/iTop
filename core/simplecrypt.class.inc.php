<?php
// Copyright (C) 2010-2012 Combodo SARL
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
 * @copyright   Copyright (C) 2010-2012 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class SimpleCrypt
{
    /**
     * Constructor
     * @param string $sEngineName Engine for encryption. Values: Simple, Mcrypt
     */       
    function __construct($sEngineName = 'Mcrypt')
    {
    	if (($sEngineName == 'Mcrypt') && (!function_exists('mcrypt_module_open')))
    	{
    		// Defaults to Simple encryption if the mcrypt module is not present
    		$sEngineName = 'Simple';
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
 
	public function __construct()
	{
		$this->td = mcrypt_module_open($this->alg,'','cbc','');
	}
	
    public function Encrypt($key, $sString)
    {
		$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($this->td), MCRYPT_RAND); // MCRYPT_RAND is the only choice on Windows prior to PHP 5.3     
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
			$decrypted_data = '** decryption error **';
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
?>