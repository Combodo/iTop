<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\ServiceLocator;

use Exception;
use IssueLog;
use Psr\Container\ContainerExceptionInterface;
use Throwable;

class ServiceLocatorException extends Exception implements ContainerExceptionInterface
{
	public function __construct(string $sMessage = '', ?Throwable $oPrevious = null, array $aContext = [])
	{
		parent::__construct($sMessage, 0, $oPrevious);
		IssueLog::Exception(get_class($this).' occurs: '.$sMessage, $this, null, $aContext);
	}
}
