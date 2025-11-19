<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\IO\Format;

use JsonSerializable;

abstract class AbstractIOFormat implements JsonSerializable
{
	abstract public function jsonSerialize(): mixed;

	abstract public static function IsCompatible(string $sOtherFormatClass): bool;
}
