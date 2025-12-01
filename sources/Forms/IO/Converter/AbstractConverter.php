<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\IO\Converter;

/**
 * Abstract converter.
 *
 * @package Combodo\iTop\Forms\IO\Converter
 * @since 3.3.0
 */
abstract class AbstractConverter
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
