<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\TurboUpdate;

use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;

class TurboStream extends UIContentBlock
{
	// Overloaded constants
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/turbo-stream/layout';
	private string $sTarget;
	private string $sAction;

	public function __construct(string $sTarget, string $sAction, string $sId = null)
	{
		parent::__construct($sId);
		$this->sTarget = $sTarget;
		$this->sAction = $sAction;
	}

	public function GetTarget(): string
	{
		return $this->sTarget;
	}

	public function GetAction(): string
	{
		return $this->sAction;
	}
}
