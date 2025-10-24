<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\IO;


class FormBinding
{
	public function __construct(public readonly FormInput $oDestinationIO, public readonly AbstractFormIO $oSourceIO)
	{

	}
}