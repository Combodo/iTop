<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\IO;

use Combodo\iTop\Forms\Block\AbstractFormBlock;

class AbstractFormIO
{

	private AbstractFormBlock $oOwnerBlock;

	public function SetOwnerBlock(AbstractFormBlock $oOwnerBlock): void
	{
		$this->oOwnerBlock = $oOwnerBlock;
	}

	public function GetOwnerBlock(): AbstractFormBlock
	{
		return $this->oOwnerBlock;
	}

}