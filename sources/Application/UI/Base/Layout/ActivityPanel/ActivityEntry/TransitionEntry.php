<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry;


use DateTime;
use MetaModel;

/**
 * Class TransitionEntry
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry
 * @internal
 * @since 3.0.0
 */
class TransitionEntry extends ActivityEntry
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-transition-entry';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/activity-panel/activity-entry/transition-entry';

	public const DEFAULT_TYPE = 'transition';
	public const DEFAULT_DECORATION_CLASSES = 'fas fa-fw fa-map-signs';

	/** @var string $sOriginStateCode Code of the state before the transition */
	protected $sOriginStateCode;
	/** @var string $sOriginStateLabel Label of the $sOriginStateCode state */
	protected $sOriginStateLabel;
	/** @var string $sTargetStateCode Code of the state after the transition */
	protected $sTargetStateCode;
	/** @var string $sTargetStateLabel Label of the $sTargetStateCode state */
	protected $sTargetStateLabel;

	/**
	 * TransitionEntry constructor.
	 *
	 * @param \DateTime $oDateTime
	 * @param \User $sAuthorLogin
	 * @param string $sObjectClass Class of the object which made the transition
	 * @param string $sOriginStateCode
	 * @param string $sTargetStateCode
	 * @param string|null $sId
	 *
	 * @throws \CoreException
	 * @throws \OQLException
	 */
	public function __construct(
		DateTime $oDateTime, string $sAuthorLogin, string $sObjectClass, string $sOriginStateCode, string $sTargetStateCode,
		?string $sId = null
	) {
		parent::__construct($oDateTime, $sAuthorLogin, null, $sId);

		$this->SetOriginalState($sObjectClass, $sOriginStateCode);
		$this->SetTargetState($sObjectClass, $sTargetStateCode);
	}

	/**
	 * Set the code / label of the state before the transition
	 *
	 * @param string $sObjectClass Class of the object the state is from
	 * @param string $sStateCode
	 *
	 * @return $this
	 * @throws \CoreException
	 */
	public function SetOriginalState(string $sObjectClass, string $sStateCode)
	{
		$this->sOriginStateCode = $sStateCode;
		$this->sOriginStateLabel = MetaModel::GetStateLabel($sObjectClass, $sStateCode);

		return $this;
	}

	/**
	 * Return the code of the state before the transition
	 *
	 * @return string
	 */
	public function GetOriginalStateCode(): string
	{
		return $this->sOriginStateCode;
	}

	/**
	 * Return the label of the state before the transition
	 *
	 * @return string
	 */
	public function GetOriginalStateLabel(): string
	{
		return $this->sOriginStateLabel;
	}

	/**
	 * Set the code / label of the state after the transition
	 *
	 * @param string $sObjectClass
	 * @param string $sStateCode
	 *
	 * @return $this
	 * @throws \CoreException
	 */
	public function SetTargetState(string $sObjectClass, string $sStateCode)
	{
		$this->sTargetStateCode = $sStateCode;
		$this->sTargetStateLabel = MetaModel::GetStateLabel($sObjectClass, $sStateCode);

		return $this;
	}

	/**
	 * Return the code of the state after the transition
	 *
	 * @return string
	 */
	public function GetTargetStateCode(): string
	{
		return $this->sTargetStateCode;
	}

	/**
	 * Return the label of the state after the transition
	 *
	 * @return string
	 */
	public function GetTargetStateLabel(): string
	{
		return $this->sTargetStateLabel;
	}
}