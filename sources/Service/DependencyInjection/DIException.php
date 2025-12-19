<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\DependencyInjection;

use IssueLog;
use Throwable;

class DIException extends \Exception
{
	public function __construct(string $sMessage = '', int $iCode = 0, ?Throwable $oPrevious = null, array $aContext = [])
	{
		parent::__construct($sMessage, $iCode, $oPrevious);
		IssueLog::Exception(get_class($this).' occurs: '.$sMessage, $this, null, $aContext);
	}
}
