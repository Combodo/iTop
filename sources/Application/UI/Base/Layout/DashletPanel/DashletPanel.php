<?php

namespace Combodo\iTop\Application\UI\Base\Layout\DashletPanel;

use Combodo\iTop\Application\UI\Base\UIBlock;
use Dashlet;

class DashletPanel extends UIBlock
{
	public const BLOCK_CODE = 'ibo-dashlet-panel';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/dashlet-panel/layout';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = 'base/layouts/dashlet-panel/layout';

	protected $aDashletsEntries = [];

	public function __construct(string $sId = null, array $aContainerClasses = [])
	{
		parent::__construct($sId, $aContainerClasses);

		$this->AddDataAttribute('role', static::BLOCK_CODE);
	}

	public function AddDashletEntry(DashletEntry $oDashletEntry)
	{
		$this->aDashletsEntries[] = $oDashletEntry;

		return $this;
	}

	public function GetDashletEntries(): array
	{
		return $this->aDashletsEntries;
	}

	public function SetDashletEntries(array $aDashletEntries)
	{
		$this->aDashletsEntries = $aDashletEntries;

		return $this;
	}

	public function GetSubBlocks(): array
	{
		return $this->aDashletsEntries;
	}
}
