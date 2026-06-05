<?php

namespace Combodo\iTop\Exception;

use Exception;
use IssueLog;
use LogChannels;
use Throwable;

class ItopException extends Exception
{
	private array $aContext;

	public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null, array $aContext = [])
	{
		$aContext['code'] = $code;
		IssueLog::Debug($message, LogChannels::EXCEPTION, $aContext);
		parent::__construct($message, $code, $previous);
		$this->aContext = $aContext;
	}

	public function getContext(): array
	{
		return $this->aContext;
	}


}