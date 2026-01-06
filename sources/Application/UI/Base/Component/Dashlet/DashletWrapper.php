<?php

namespace Combodo\iTop\Application\UI\Base\Component\Dashlet;


use Combodo\iTop\Application\UI\Base\UIBlock;

class DashletWrapper extends UIBlock {
	public const BLOCK_CODE = 'ibo-dashlet-wrapper';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/dashlet/dashlet-wrapper';

	protected $oDashlet;
	protected $sDashletClass;
	protected $sDashletId;

	public function __construct($oDashlet, ?string $sDashletId = null, ?string $sDashletClass = null) {
		parent::__construct(null);

		$this->oDashlet = $oDashlet;
		$this->sDashletId = $sDashletId;
		$this->sDashletClass = $sDashletClass;
	}

	public function GetDashlet() {
		return $this->oDashlet;
	}

	public function GetDashletId(): ?string
	{
		return $this->sDashletId;
	}

	public function SetDashletId(?string $sDashletId): DashletWrapper
	{
		$this->sDashletId = $sDashletId;

		return $this;
	}

	public function GetDashletClass(): ?string
	{
		return $this->sDashletClass;
	}

	public function SetDashletClass(?string $sDashletClass)
	{
		$this->sDashletClass = $sDashletClass;

		return $this;
	}
}
