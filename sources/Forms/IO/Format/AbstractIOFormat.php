<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\IO\Format;

use JsonSerializable;

/**
 * Abstract IO format.
 *
 * @package Combodo\iTop\Forms\IO\Format
 * @since 3.3.0
 */
abstract class AbstractIOFormat implements JsonSerializable
{
	abstract public function jsonSerialize(): mixed;
}
