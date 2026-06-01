<?php

/**
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\DataFeatureRemoval\Helper;

use Exception;
use Throwable;

class DataFeatureRemovalException extends Exception
{
	public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null, array $aContext = [])
	{
		if (!is_null($previous)) {
			$sStack = $previous->getTraceAsString();
			$sError = $previous->getMessage();
		} else {
			$sStack = $this->getTraceAsString();
			$sError = '';
		}

		$aContext['error'] = $sError;
		$aContext['stack'] = $sStack;
		DataFeatureRemovalLog::Error($message, null, $aContext);
		parent::__construct($message, $code, $previous);
	}
}
