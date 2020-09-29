<?php
/**
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Component\Title;


class TitleForObjectDetails extends Title
{
	public const HTML_TEMPLATE_REL_PATH = 'components/title/titleforobjectdetails';

	/** @var string */
	protected $sClassName;
	/** @var string */
	protected $sObjectName;
	protected $sStatusCode;
	protected $sStatusLabel;
	protected $sStatusColor;

	public function __construct(string $sClassName, string $sObjectName, ?string $sId = null)
	{
		parent::__construct('', 2, $sId);
		$this->sClassName = $sClassName;
		$this->sObjectName = $sObjectName;
		$this->sStatusCode = null;
		$this->sStatusLabel = null;
		$this->sStatusColor = null;
	}

	/**
	 * @return string
	 */
	public function GetClassName(): string
	{
		return $this->sClassName;
	}

	/**
	 * @return string
	 */
	public function GetObjectName(): string
	{
		return $this->sObjectName;
	}

	public function SetStatus($sCode, $sLabel, $sColor)
	{
		$this->sStatusCode = $sColor;
		$this->sStatusLabel = $sLabel;
		$this->sStatusColor = $sColor;

		return $this;
	}

	public function GetStatusCode()
	{
		return $this->sStatusCode;
	}

	public function GetStatusLabel()
	{
		return $this->sStatusLabel;
	}

	public function GetStatusColor()
	{
		return $this->sStatusColor;
	}
}