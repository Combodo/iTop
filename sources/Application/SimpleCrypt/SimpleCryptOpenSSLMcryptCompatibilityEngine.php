<?php

class SimpleCryptOpenSSLMcryptCompatibilityEngine implements CryptEngine
{
	public static function GetNewDefaultParams()
	{
		return array('lib' => 'OpenSSLMcryptCompatibility', 'key' => null);
	}

	//fix for php < 7.1.8 (keys are Zero padded instead of cycle padded)
	static private function MakeOpenSSLBlowfishKey($key)
	{
		if ("$key" === '') {
			return $key;
		}
		$len = (16 + 2) * 4;
		while (strlen($key) < $len) {
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
		$paddingLen = intval(($len + $blockSize - 1) / $blockSize) * $blockSize - $len;
		$padding = str_repeat("\0", $paddingLen);
		$sData = $sString.$padding;
		$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length("BF-CBC"));
		$encrypted_string = openssl_encrypt($sData, "BF-CBC", $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);

		return $iv.$encrypted_string;
	}

	public function Decrypt($key, $encrypted_data)
	{
		$key = SimpleCryptOpenSSLMcryptCompatibilityEngine::MakeOpenSSLBlowfishKey($key);
		$iv = mb_substr($encrypted_data, 0, openssl_cipher_iv_length("BF-CBC"), '8bit');
		$encrypted_data = mb_substr($encrypted_data, openssl_cipher_iv_length("BF-CBC"), null, '8bit');
		$plaintext = openssl_decrypt($encrypted_data, "BF-CBC", $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
		if ($plaintext === false) {
			$plaintext = Dict::S("Core:AttributeEncryptFailedToDecrypt");
		}

		return trim($plaintext);
	}

}