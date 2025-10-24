<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Converter;

/**
 * Output converter.
 */
abstract class AbstractOutputConverter
{
	/**
	 * Convert the date to output format.
	 *
	 * @param mixed $oData
	 *
	 * @return mixed
	 */
	abstract public function Convert(mixed $oData): mixed;
}