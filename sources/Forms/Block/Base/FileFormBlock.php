<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\Block\Base;

use Combodo\iTop\Forms\Block\AbstractTypeFormBlock;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class FileFormBlock extends AbstractTypeFormBlock
{
	/**
	 * @inheritDoc
	 */
	public function GetFormType(): string
	{
		return FileType::class;
	}
}
