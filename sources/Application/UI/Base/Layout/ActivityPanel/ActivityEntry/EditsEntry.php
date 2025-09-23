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
use Dict;
use Exception;
use MetaModel;

/**
 * Class EditsEntry
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry
 * @internal
 * @since 3.0.0
 */
class EditsEntry extends ActivityEntry
{
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-edits-entry';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/activity-panel/activity-entry/edits-entry';

	public const DEFAULT_TYPE = 'edits';
	public const DEFAULT_DECORATION_CLASSES = 'fas fa-fw fa-pen';

	/** @var string $sObjectClass */
	protected $sObjectClass;
	/** @var array $aAttributes Array of edited attributes with their code, label and descriptions (an attribute can have several descriptions at once, eg. linkedset items added/removed) */
	protected $aAttributes;

	/**
	 * EditsEntry constructor.
	 *
	 * @param \DateTime $oDateTime
	 * @param string $sAuthorLogin
	 * @param string $sObjectClass Class of the object concerned by the edits
	 * @param string|null $sId
	 *
	 * @throws \OQLException
	 */
	public function __construct(DateTime $oDateTime, string $sAuthorLogin, string $sObjectClass, ?string $sId = null)
	{
		parent::__construct($oDateTime, $sAuthorLogin, null, $sId);

		$this->sObjectClass = $sObjectClass;
		$this->SetAttributes([]);
	}

	/**
	 * Return the class of the object concerned by the edits
	 *
	 * @return string
	 */
	public function GetObjectClass(): string
	{
		return $this->sObjectClass;
	}

	/**
	 * Set all attributes at once, replacing all existing.
	 *
	 * @param array $aAttributes
	 *
	 * @return $this
	 */
	public function SetAttributes(array $aAttributes)
	{
		$this->aAttributes = $aAttributes;

		return $this;
	}

	/**
	 * Return an array of edited attributes with their code, label and description
	 *
	 * @return array
	 */
	public function GetAttributes(): array
	{
		return $this->aAttributes;
	}

	/**
	 * Add the attribute identified by $sAttCode to the edited attribute.
	 * Note that if an attribute with the same $sAttCode already exists, it's description will be append to the existing one.
	 *
	 * @param string $sAttCode
	 * @param string $sEditDescriptionAsHtml The description of the edit already in HTML, it MUSt have been sanitized first (Already in
	 *     HTML because most of the time it comes from CMDBChangeOp::GetDescription())
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function AddAttribute(string $sAttCode, string $sEditDescriptionAsHtml)
	{
		// Create it if not already existing
		if (!array_key_exists($sAttCode, $this->aAttributes)) {
			$this->aAttributes[$sAttCode] = [
				'code' => $sAttCode,
				'label' => MetaModel::GetLabel($this->sObjectClass, $sAttCode),
				'descriptions' => [],
			];
		}

		// Append description
		$this->aAttributes[$sAttCode]['descriptions'][] = $sEditDescriptionAsHtml;

		return $this;
	}

	/**
	 * Remove the attribute of code $sAttCode from the edited attributes.
	 * Note that if there is no attribute with this code, it will proceed silently.
	 *
	 * @param string $sAttCode
	 *
	 * @return array
	 */
	public function RemoveAttribute(string $sAttCode)
	{
		if (array_key_exists($sAttCode, $this->aAttributes))
		{
			unset($this->aAttributes[$sAttCode]);
		}

		return $this->aAttributes;
	}

	/**
	 * Merge $oEntry into the current one ($this).
	 * Note that edits on any existing attribute codes will be replaced.
	 *
	 * @param \Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityEntry\EditsEntry $oEntry
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function Merge(EditsEntry $oEntry)
	{
		if ($oEntry->GetObjectClass() !== $this->GetObjectClass()) {
			throw new Exception("Cannot merge an entry from {$oEntry->GetObjectClass()} into {$this->GetObjectClass()}, they must be for the same class");
		}

		// Merging attributes
		foreach ($oEntry->GetAttributes() as $sAttCode => $aAttData) {
			foreach ($aAttData['descriptions'] as $sDescription) {
				$this->AddAttribute($sAttCode, $sDescription);
			}
		}

		return $this;
	}

	/**
	 * Return the short description of the edits entry in HTML
	 *
	 * @return string
	 */
	public function GetShortDescriptionAsHtml(): string
	{
		// We need the array to be indexed by numbers instead of being associative
		$aAttributesData = array_values($this->GetAttributes());
		$iAttributesCount = count($aAttributesData);
		switch($iAttributesCount)
		{
			case 0:
				$sDescriptionAsHtml = '';
				break;

			case 1:
				$iDescriptionsCount = count($aAttributesData[0]['descriptions']);
				switch ($iDescriptionsCount) {
					case 1:
						$sDescriptionAsHtml = $aAttributesData[0]['descriptions'][0];
						break;

					default:
						$sDescriptionAsHtml = '<span class="ibo-edits-entry--attribute-label" data-attribute-code="'.$aAttributesData[0]['code'].'">'.$aAttributesData[0]['label'].'</span>';
						break;
				}

				break;

			default:
				$sFirstAttLabelAsHtml = '<span class="ibo-edits-entry--attribute-label" data-attribute-code="'.$aAttributesData[0]['code'].'">'.$aAttributesData[0]['label'].'</span>';
				$sSecondAttLabelAsHtml = '<span class="ibo-edits-entry--attribute-label" data-attribute-code="'.$aAttributesData[1]['code'].'">'.$aAttributesData[1]['label'].'</span>';

				switch($iAttributesCount)
				{
					case 2:
						$sDescriptionAsHtml = Dict::Format('Change:TwoAttributesChanged', $sFirstAttLabelAsHtml, $sSecondAttLabelAsHtml);
						break;

					case 3:
						$sDescriptionAsHtml = Dict::Format('Change:ThreeAttributesChanged', $sFirstAttLabelAsHtml, $sSecondAttLabelAsHtml);
						break;

					default:
						$sDescriptionAsHtml = Dict::Format('Change:FourOrMoreAttributesChanged', $sFirstAttLabelAsHtml, $sSecondAttLabelAsHtml, count($aAttributesData) - 2);
						break;
				}
		}

		return $sDescriptionAsHtml;
	}
}