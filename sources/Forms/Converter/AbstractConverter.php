<?php

namespace Combodo\iTop\Forms\Converter;

abstract class AbstractConverter
{
	abstract public function Convert(mixed $oData): mixed;
}