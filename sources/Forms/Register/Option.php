<?php

namespace Combodo\iTop\Forms\Register;

/**
 * Option.
 *
 * @package Combodo\iTop\Forms\Register
 * @since 3.3.0
 */
class Option
{
	public function __construct(public string $sName, public mixed $oValue, public bool $bIsTypeOption = true)
	{
	}
}
