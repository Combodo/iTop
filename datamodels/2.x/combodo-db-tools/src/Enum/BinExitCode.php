<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\DBTools\Enum;

/**
 * Enum for the exit codes of the bin scripts
 */
enum BinExitCode: int
{
	case SUCCESS = 0;
	case ERROR = -1;
	case FATAL = -2;
}
