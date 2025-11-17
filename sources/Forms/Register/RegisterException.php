<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Register;

use Exception;
use Throwable;

class RegisterException extends Exception
{
	public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null, array $aContext = [])
	{
		parent::__construct($message, $code, $previous);
	}
}