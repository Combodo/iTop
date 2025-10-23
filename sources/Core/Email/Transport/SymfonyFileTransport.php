<?php

namespace Combodo\iTop\Core\Email\Transport;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mailer\SentMessage;

class SymfonyFileTransport extends AbstractTransport
{
	protected string $sDir;
	protected string $sFilename;

	/**
	 * @param string|null $sLogDir Directory where the file will be written. Defaults to APPROOT.'log/'.
	 * @param string $sFilename Filename (default 'mail.log').
	 */
	public function __construct(?string $sLogDir = null, string $sFilename = 'mail.log', ?EventDispatcherInterface $oDispatcher = null, ?LoggerInterface $oLogger = null)
	{
		parent::__construct($oDispatcher, $oLogger);
		$this->sDir = rtrim($sLogDir ?? APPROOT . 'log/', DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
		$this->sFilename = $sFilename;
	}

	/**
	 * Write the message to the file.
	 *
	 * @param SentMessage $message
	 *
	 * @throws \RuntimeException
	 */
	protected function doSend(SentMessage $message): void
	{
		// Ensure directory exists
		if (!is_dir($this->sDir) && !mkdir($concurrentDirectory = $this->sDir, 0755, true) && !is_dir($concurrentDirectory)) {
			throw new \RuntimeException("Unable to create log directory: {$this->sDir}");
		}

		$sPath = $this->sDir.$this->sFilename;

		// Build an entry header to separate messages
		$sEntry = "=== ".date('c')." ===\n";

		// Get the raw message
		$oRawMessage = $message->getOriginalMessage();

		if (is_object($oRawMessage) && method_exists($oRawMessage, 'toString')) {
			$sEntry .= $oRawMessage->toString();
		}

		// Write using LOCK_EX to avoid race conditions
		if (@file_put_contents($sPath, $sEntry, FILE_APPEND | LOCK_EX) === false) {
			throw new \RuntimeException("Unable to write email entry to log file: {$sPath}");
		}
	}

	public function __toString(): string
	{
		return 'logfile';
	}
}

