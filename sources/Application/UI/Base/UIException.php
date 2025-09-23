<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base;


use Exception;
use Throwable;

class UIException extends Exception
{
	public function __construct(iUIBlock $oBlock, string $message = "", int $code = 0, Throwable $previous = null)
	{
		parent::__construct($oBlock->GetId().': '.$message, $code, $previous);
	}
}
