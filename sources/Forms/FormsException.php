<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms;

use IssueLog;
use Throwable;

class FormsException extends \Exception
{
	public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null, array $aContext = [])
	{
		IssueLog::Exception(get_class($this).' occurs', $this, null, $aContext);
		parent::__construct($message, $code, $previous);
	}
}