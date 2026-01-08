<?php

namespace Combodo\iTop\Application\UI\Base\Component\Dashlet;

use Combodo\iTop\Application\UI\Base\UIBlock;
use Dashlet;

class DashletWrapper extends UIBlock
{
	public const BLOCK_CODE                     = 'ibo-dashlet-wrapper';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/dashlet/dashlet-wrapper';

	protected DashletContainer $oDashletContainer;
	protected $sDashletClass;
	protected $sDashletId;
	private array $aFormViewData;

	public function __construct($oDashlet, string $sDashletClass, ?string $sDashletId = null, array $aFormViewData = [])
	{
		parent::__construct(null);

		$this->oDashletContainer = $oDashlet;
		$this->sDashletId = $sDashletId;
		$this->sDashletClass = $sDashletClass;
		$this->aFormViewData = $aFormViewData;
	}

	public function GetDashlet()
	{
		return $this->oDashletContainer;
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

	public function GetFormViewData()
	{
		return $this->aFormViewData;
	}
}
