<?php

namespace Combodo\iTop\Forms\Register;

class Option
{
	public function __construct(public string $sName, public mixed $oValue, public bool $bIsTypeOption = true)
	{
	}
}
