<?php

namespace Combodo\iTop\Core\Authentication\Client\Smtp;

use Combodo\iTop\Core\Authentication\Client\OAuth\OAuthClientProviderAbstract;
use IssueLog;
use Laminas\Mail\Protocol\Exception\RuntimeException;
use Laminas\Mail\Protocol\Smtp\Auth\Login;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class Oauth extends Login
{
	/**
	 * LOGIN username
	 *
	 * @var OAuthClientProviderAbstract
	 */
	protected static $oProvider;

	const LOG_CHANNEL = 'OAuth';
	/**
	 * Constructor.
	 *
	 * @param string $host (Default: 127.0.0.1)
	 * @param null $port (Default: null)
	 * @param null $config Auth-specific parameters
	 */
	public function __construct($host = '127.0.0.1', $port = null, $config = null)
	{
		$origConfig = $config;
		if (is_array($host)) {
			// Merge config array with principal array, if provided
			if (is_array($config)) {
				$config = array_replace_recursive($host, $config);
			} else {
				$config = $host;
			}
		}

		if (is_array($config)) {
			if (isset($config['username'])) {
				$this->setUsername($config['username']);
			}
		}

		// Call parent with original arguments
		parent::__construct($host, $port, $origConfig);
	}

	/**
	 * @param OAuthClientProviderAbstract $oProvider
	 *
	 * @return void
	 */
	public static function setProvider(OAuthClientProviderAbstract $oProvider)
	{
		self::$oProvider = $oProvider;
	}

	/**
	 * Perform LOGIN authentication with supplied credentials
	 *
	 */
	public function auth()
	{
		try {
			if (empty(self::$oProvider->GetAccessToken())) {
				throw new IdentityProviderException('Not prior authentication to OAuth', 255, []);
			} elseif (self::$oProvider->GetAccessToken()->hasExpired()) {
				self::$oProvider->SetAccessToken(self::$oProvider->GetVendorProvider()->getAccessToken('refresh_token', [
					'refresh_token' => self::$oProvider->GetAccessToken()->getRefreshToken(),
					'scope'         => self::$oProvider->GetScope(),
				]));
			}
		}
		catch (IdentityProviderException $e) {
			IssueLog::Error('Failed to get oAuth credentials for outgoing mails for provider '.self::$oProvider::GetVendorName().' '.$e->getMessage(), static::LOG_CHANNEL);

			return false;
		}
		$sAccessToken = self::$oProvider->GetAccessToken()->getToken();

		if (empty($sAccessToken)) {
			IssueLog::Error('No OAuth token for outgoing mails for provider '.self::$oProvider::GetVendorName(), static::LOG_CHANNEL);

			return false;
		}

		$this->_send('AUTH XOAUTH2 '.base64_encode("user=$this->username\1auth=Bearer $sAccessToken\1\1"));
		IssueLog::Debug("SMTP Oauth sending AUTH XOAUTH2 user=$this->username auth=Bearer $sAccessToken", static::LOG_CHANNEL);

		try {
			while (true) {
				$sResponse = $this->_receive(30);

				IssueLog::Debug("SMTP Oauth receiving ".trim($sResponse), static::LOG_CHANNEL);

				if ($sResponse === '+') {
					// Send empty client response.
					$this->_send('');
				} else {
					if (preg_match('/Unauthorized/i', $sResponse) ||
						preg_match('/Rejected/i', $sResponse) ||
						preg_match('/^(535|432|454|534|500|530|538|334)/', $sResponse)) {
						IssueLog::Error('Unable to authenticate for outgoing mails for provider '.self::$oProvider::GetVendorName()." Error: $sResponse", static::LOG_CHANNEL);

						return false;
					}
					if (preg_match("/OK /i", $sResponse) ||
						preg_match('/Accepted/i', $sResponse) ||
						preg_match('/^235/i', $sResponse)) {
						$this->auth = true;

						return true;
					}

				}
			}
		} catch (RuntimeException $e) {
			IssueLog::Error('Timeout connection for outgoing mails for provider '.self::$oProvider::GetVendorName(), static::LOG_CHANNEL);
		}
		return false;
	}
}
