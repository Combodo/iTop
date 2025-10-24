<?php

namespace Combodo\iTop\Forms\Block\IO\Format;

class AttributeIOFormat
{
	public function __construct(public string $sAttributeName)
	{
		// validation du format sinon exception
	}
}