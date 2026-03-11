<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\PropertyType;

use Combodo\iTop\DesignDocument;
use Combodo\iTop\DesignElement;
use Exception;
use Throwable;

/**
 * @since 3.3.0
 */
class PropertyTypeException extends Exception
{
	public function __construct(string $message = "", ?DesignElement $oNode = null, ?Throwable $previous = null)
	{
		if (!is_null($oNode)) {
			$message = DesignDocument::GetItopNodePath($oNode).': '.$message;
		}
		parent::__construct($message, 0, $previous);
	}
}
