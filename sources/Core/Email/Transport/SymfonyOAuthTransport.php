<?php

namespace Combodo\iTop\Core\Email\Transport;

use Combodo\iTop\Core\Authentication\Client\OAuth\OAuthClientProviderAbstract;
use Combodo\iTop\Core\Authentication\Client\OAuth\OAuthClientProviderFactory;
use IssueLog;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\Smtp\Auth\LoginAuthenticator;
use Symfony\Component\Mailer\Transport\Smtp\Auth\PlainAuthenticator;
use Symfony\Component\Mailer\Transport\Smtp\Auth\XOAuth2Authenticator;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;

/**
 * Transport that will request/refresh an OAuth access token and keep EsmtpTransport behavior
 */
class SymfonyOAuthTransport extends EsmtpTransport
{
	/** @var OAuthClientProviderAbstract|null */
	protected static $oProvider = null;
	const LOG_CHANNEL = 'OAuth';


	public function __construct($aConfig = [], ?LoggerInterface $oLogger = null)
	{
		$sHost = '127.0.0.1';
		$iPort = 25;
		$sUsername = null;
		$sEncryption = null;

		if (is_array($aConfig)) {
			if (!empty($aConfig['host'])) {
				$sHost = $aConfig['host'];
			}
			if (!empty($aConfig['port'])) {
				$iPort = (int)$aConfig['port'];
			}
			if (!empty($aConfig['username'])) {
				$sUsername = $aConfig['username'];
			}
			if (!empty($aConfig['encryption'])) {
				$sEncryption = $aConfig['encryption']; // 'ssl' / 'tls' / null
			}
		}

		$bTls = is_null($sEncryption) ? null : $sEncryption === 'tls';
		// Construct parent EsmtpTransport
		parent::__construct($sHost, (int)$iPort, $bTls);

		if ($sUsername !== null) {
			$this->setUsername($sUsername);
		}

		// Make XOAUTH2 be attempted first, then LOGIN/PLAIN as fallback.
		$this->setAuthenticators([
			new XOAuth2Authenticator(),
			new LoginAuthenticator(),
			new PlainAuthenticator(),
		]);
	}

	public static function setProvider(OAuthClientProviderAbstract $oProvider): void
	{
		self::$oProvider = $oProvider;
	}

	/**
	 * Fetch the provider if not explicitly set
	 */
	protected function getProvider(): OAuthClientProviderAbstract
	{
		if (self::$oProvider === null) {
			self::$oProvider = OAuthClientProviderFactory::GetProviderForSMTP();
		}

		return self::$oProvider;
	}

	/**
	 * Ensure we have a fresh access token and set it as the current SMTP password*
	 *
	 * @throws IdentityProviderException
	 */
	protected function ensureOAuthTokenIsReady(): void
	{
		$oProvider = $this->getProvider();

		try {
			$oAccessToken = $oProvider->GetAccessToken();
		}
		catch (\Throwable $e) {
			IssueLog::Error('Failed to get OAuth provider access token: '.$e->getMessage(), 'OAuth');
			throw new TransportException('Failed to obtain OAuth access token for SMTP', 0, $e);
		}

		if ($oAccessToken === null) {
			throw new IdentityProviderException('Not prior authentication to OAuth', 255, []);
		}
		elseif ($oAccessToken->hasExpired()) {
			self::$oProvider->SetAccessToken(self::$oProvider->GetVendorProvider()->getAccessToken('refresh_token', [
				'refresh_token' => $oAccessToken->getRefreshToken(),
				'scope'         => self::$oProvider->GetScope(),
			]));
		}

		$sAccessToken = $oAccessToken->getToken();
		if (empty($sAccessToken)) {
			IssueLog::Error('OAuth access token is empty for outgoing mails.', 'OAuth');
			throw new TransportException('OAuth access token is empty.');
		}

		// Set the token as the SMTP "password" as Symfony's XOAuth2Authenticator expects
		$this->setPassword($sAccessToken);
	}

	/**
	 * Override send hook so we can refresh the token just before
	 */
	protected function doSend(SentMessage $message): void
	{
		// Ensure a fresh token is available and set as SMTP password
		try {
			$this->ensureOAuthTokenIsReady();
		}
		catch (IdentityProviderException $e) {
			IssueLog::Error('Failed to get SMTP oAuth credentials for incoming mails for provider '.self::$oProvider::GetVendorName(), static::LOG_CHANNEL, [
				'exception.message' => $e->getMessage(),
				'exception.stack'   => $e->getTraceAsString(),
			]);
		}

		$sUsername = $this->getUsername();
		$sAccessToken = $this->getPassword();

		// Let the normal EsmtpTransport flow continue
		IssueLog::Debug("SMTP OAuth trying to login in with user=$sUsername token=$sAccessToken", static::LOG_CHANNEL);
		parent::doSend($message);
	}
}
