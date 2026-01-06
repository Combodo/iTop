<?php

namespace Combodo\iTop\Application\UI\Base\Layout\DashletPanel;

use Combodo\iTop\Application\UI\Base\UIBlock;
use utils;

class DashletEntry extends UIBlock {
	public const BLOCK_CODE = 'ibo-dashlet-entry';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/dashlet-panel/dashlet-entry';

	protected $sDashletLabel;
	protected $sDashletClass;
	protected $sDashletIcon;
	protected $sDashletDescription;
	protected $sDashletMinWidth;
	protected $sDashletMinHeight;
	protected $sDashletPreferredWidth;
	protected $sDashletPreferredHeight;


	public function __construct(string $sDashletClass, string $sDashletLabel, string $sDashletDescription, string $sDashletIconRelUrl, ?string $sId = null)
	{
		parent::__construct($sId);

		$this->sDashletClass = $sDashletClass;
		$this->sDashletLabel = $sDashletLabel;
		$this->sDashletIcon = utils::GetAbsoluteUrlAppRoot().$sDashletIconRelUrl;
		$this->sDashletDescription = $sDashletDescription;

		$this->AddDataAttribute('role', static::BLOCK_CODE);
	}

	public function GetDashletLabel(): string
	{
		return $this->sDashletLabel;
	}

	public function SetDashletLabel(string $sDashletLabel): DashletEntry
	{
		$this->sDashletLabel = $sDashletLabel;

		return $this;
	}

	public function GetDashletClass(): string
	{
		return $this->sDashletClass;
	}

	public function SetDashletClass(string $sDashletClass): DashletEntry
	{
		$this->sDashletClass = $sDashletClass;

		return $this;
	}

	public function GetDashletIcon(): string
	{
		return $this->sDashletIcon;
	}

	public function SetDashletIcon(string $sDashletIcon): DashletEntry
	{
		$this->sDashletIcon = $sDashletIcon;

		return $this;
	}

	public function GetDashletDescription(): string
	{
		return $this->sDashletDescription;
	}

	public function SetDashletDescription(string $sDashletDescription): DashletEntry
	{
		$this->sDashletDescription = $sDashletDescription;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function GetDashletPreferredHeight()
	{
		return $this->sDashletPreferredHeight;
	}

	/**
	 * @param mixed $sDashletPreferredHeight
	 *
	 * @return DashletEntry
	 */
	public function SetDashletPreferredHeight($sDashletPreferredHeight)
	{
		$this->sDashletPreferredHeight = $sDashletPreferredHeight;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function GetDashletPreferredWidth()
	{
		return $this->sDashletPreferredWidth;
	}

	/**
	 * @param mixed $sDashletPreferredWidth
	 *
	 * @return DashletEntry
	 */
	public function SetDashletPreferredWidth($sDashletPreferredWidth)
	{
		$this->sDashletPreferredWidth = $sDashletPreferredWidth;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function GetDashletMinHeight()
	{
		return $this->sDashletMinHeight;
	}

	/**
	 * @param mixed $sDashletMinHeight
	 *
	 * @return DashletEntry
	 */
	public function SetDashletMinHeight($sDashletMinHeight)
	{
		$this->sDashletMinHeight = $sDashletMinHeight;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function GetDashletMinWidth()
	{
		return $this->sDashletMinWidth;
	}

	/**
	 * @param mixed $sDashletMinWidth
	 *
	 * @return DashletEntry
	 */
	public function SetDashletMinWidth($sDashletMinWidth)
	{
		$this->sDashletMinWidth = $sDashletMinWidth;

		return $this;
	}


}
