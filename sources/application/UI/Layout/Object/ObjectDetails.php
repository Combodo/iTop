<?php
/*
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Layout\Object;


use Combodo\iTop\Application\UI\Component\Panel\Panel;
use Combodo\iTop\Application\UI\Helper\UIHelper;
use DBObject;
use MetaModel;

class ObjectDetails extends Panel
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-object-details';
	public const HTML_TEMPLATE_REL_PATH = 'layouts/object/object-details/layout';

	/** @var string */
	protected $sClassName;
	/** @var string */
	protected $sClassLabel;
	/** @var string */
	protected $sName;
	/** @var string */
	protected $sIconUrl;
	/** @var string */
	protected $sStatusCode;
	/** @var string */
	protected $sStatusLabel;
	/** @var string */
	protected $sStatusColor;

	/**
	 * ObjectDetails constructor.
	 *
	 * @param \DBObject   $oObject
	 * @param string|null $sId
	 *
	 * @throws \CoreException
	 */
	public function __construct(DBObject $oObject, ?string $sId = null) {
		$this->sClassName = get_class($oObject);
		$this->sClassLabel = MetaModel::GetName($this->GetClassName());
		// Note: We get the raw name as only the front-end consumer knows when and how to encode it.
		$this->sName = $oObject->GetRawName();
		$this->sIconUrl = $oObject->GetIcon(false);

		if(MetaModel::HasStateAttributeCode($this->sClassName)) {
			$this->sStatusCode = $oObject->GetState();
			$this->sStatusLabel = $oObject->GetStateLabel();
			$this->sStatusColor = UIHelper::GetColorFromStatus($this->sClassName, $this->sStatusCode);
		}

		parent::__construct('', [], static::DEFAULT_COLOR, $sId);

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
		return $this->sName;
	}

	public function SetStatus($sCode, $sLabel, $sColor)
	{
		$this->sStatusCode = $sColor;
		$this->sStatusLabel = $sLabel;
		$this->sStatusColor = $sColor;

		return $this;
	}

	public function GetStatusCode(): string
	{
		return $this->sStatusCode;
	}

	public function GetStatusLabel(): string
	{
		return $this->sStatusLabel;
	}

	public function GetStatusColor(): string
	{
		return $this->sStatusColor;
	}
}