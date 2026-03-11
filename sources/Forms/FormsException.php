<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms;

use Exception;
use IssueLog;
use Throwable;

/**
 * Form exception.
 *
 * @package Combodo\iTop\Forms
 * @since 3.3.0
 */
class FormsException extends Exception
{
	public function __construct(string $sMessage = '', int $iCode = 0, ?Throwable $oPrevious = null, array $aContext = [])
	{
		parent::__construct($sMessage, $iCode, $oPrevious);
		IssueLog::Exception(get_class($this).' occurs: '.$sMessage, $this, null, $aContext);
	}
}
