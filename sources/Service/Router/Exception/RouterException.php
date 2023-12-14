<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\Router\Exception;

use DeprecatedCallsLog;
use Exception;


/**
 * Class RouterException
 *
 * Base router exception class in case we need to catch all kind of router exceptions (see derived exceptions)
 *
 * @internal
 *
 * @deprecated 3.2.0 N°6935 As we now use Symfony routing component, use the corresponding exceptions instead
 *
 *                          Note that we can't call \DeprecatedCallsLog::NotifyDeprecatedFile() at the beginning at the file instead.
 *                          Because
 *                               - As the class is part of the autoloader it will be read when something calls \utils::GetClassesForInterface() which will pop the deprecation message (and break redirection)
 *                               - Not all controllers using Combodo\iTop\Service\Router\Router service can be migrated yet for backward compatibility with extensions reasons
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Service\Router\Exception
 * @since 3.1.0
 */
class RouterException extends Exception
{
	public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
	{
		/**
		 * @deprecated 3.2.0 N°6935
		 *
		 * Note that we can't call \DeprecatedCallsLog::NotifyDeprecatedFile() at the beginning at the file instead.
		 * Because
		 *      - As the class is part of the autoloader it will be read when something calls \utils::GetClassesForInterface() which will pop the deprecation message (and break redirection)
		 *      - Not all controllers using Combodo\iTop\Service\Router\Router service can be migrated yet for backward compatibility with extensions reasons
		 */
		DeprecatedCallsLog::NotifyDeprecatedFile("As we now use Symfony routing component, use the corresponding exceptions instead");

		parent::__construct($message, $code, $previous);
	}
}