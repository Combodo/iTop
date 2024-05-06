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

namespace Combodo\iTop\Form\Field;

use Closure;
use Combodo\iTop\Form\Validator\LinkedSetValidator;

/**
 * Description of LinkedSetField
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since 2.3.0
 */
class LinkedSetField extends AbstractSimpleField
{
	/** @var bool DEFAULT_INDIRECT */
	const DEFAULT_INDIRECT = false;
	/** @var bool DEFAULT_DISPLAY_OPENED */
	const DEFAULT_DISPLAY_OPENED = false;
	/** @var bool DEFAULT_DISPLAY_LIMITED_ACCESS_ITEMS */
	const DEFAULT_DISPLAY_LIMITED_ACCESS_ITEMS = false;

	/** @var string $sTargetClass */
	protected $sTargetClass;
	/** @var string $sLinkedClass */
	protected $sLinkedClass;
	/** @var string $sExtKeyToRemote */
	protected $sExtKeyToRemote;
	/** @var bool $bIndirect */
	protected $bIndirect;
	/** @var bool $bDisplayOpened */
	protected $bDisplayOpened;
	/** @var bool $bDisplayLimitedAccessItems */
	protected $bDisplayLimitedAccessItems;
	/** @var array $aLimitedAccessItemIDs IDs of the items that are not visible or cannot be edited */
	protected $aLimitedAccessItemIDs;
	/** @var array $aAttributesToDisplay */
	protected $aAttributesToDisplay;
	/** @var array $aLnkAttributesToDisplay attcode as key */
	protected $aLnkAttributesToDisplay;
	/** @var string $sSearchEndpoint */
	protected $sSearchEndpoint;
	/** @var string $sInformationEndpoint */
	protected $sInformationEndpoint;

	/**
	 * @inheritDoc
	 */
	public function __construct(string $sId, Closure $onFinalizeCallback = null)
	{
		$this->sTargetClass = null;
		$this->sExtKeyToRemote = null;
		$this->bIndirect = static::DEFAULT_INDIRECT;
		$this->bDisplayOpened = static::DEFAULT_DISPLAY_OPENED;
		$this->bDisplayLimitedAccessItems = static::DEFAULT_DISPLAY_LIMITED_ACCESS_ITEMS;
		$this->aLimitedAccessItemIDs = array();
		$this->aAttributesToDisplay = array();
		$this->aLnkAttributesToDisplay = array();
		$this->sSearchEndpoint = null;
		$this->sInformationEndpoint = null;

		parent::__construct($sId, $onFinalizeCallback);
	}

	/**
	 *
	 * @return string
	 */
	public function GetTargetClass()
	{
		return $this->sTargetClass;
	}

	/**
	 *
	 * @param string $sTargetClass
	 *
	 * @return $this
	 */
	public function SetTargetClass(string $sTargetClass)
	{
		$this->sTargetClass = $sTargetClass;

		return $this;
	}

	/**
	 * @return string
	 * @since 3.1
	 *
	 */
	public function GetLinkedClass()
	{
		return $this->sLinkedClass;
	}

	/**
	 *
	 * @since 3.1
	 *
	 * @param string $sLinkedClass
	 *
	 * @return $this
	 */
	public function SetLinkedClass(string $sLinkedClass)
	{
		$this->sLinkedClass = $sLinkedClass;

		return $this;
	}

	/**
	 *
	 * @return string
	 */
	public function GetExtKeyToRemote()
	{
		return $this->sExtKeyToRemote;
	}

	/**
	 *
	 * @param string $sExtKeyToRemote
	 *
	 * @return $this
	 */
	public function SetExtKeyToRemote(string $sExtKeyToRemote)
	{
		$this->sExtKeyToRemote = $sExtKeyToRemote;

		return $this;
	}

	/**
	 *
	 * @return boolean
	 */
	public function IsIndirect()
	{
		return $this->bIndirect;
	}

	/**
	 *
	 * @param boolean $bIndirect
	 *
	 * @return $this
	 */
	public function SetIndirect(bool $bIndirect)
	{
		$this->bIndirect = $bIndirect;

		return $this;
	}

	/**
	 * Returns if the field should be displayed opened on initialization
	 *
	 * @return boolean
	 */
	public function GetDisplayOpened()
	{
		return $this->bDisplayOpened;
	}

	/**
	 * Sets if the field should be displayed opened on initialization
	 *
	 * @param $bDisplayOpened
	 *
	 * @return $this
	 */
	public function SetDisplayOpened(bool $bDisplayOpened)
	{
		$this->bDisplayOpened = $bDisplayOpened;

		return $this;
	}

	/**
	 * Returns if the field should display limited access items
	 *
	 * @return boolean
	 */
	public function GetDisplayLimitedAccessItems()
	{
		return $this->bDisplayLimitedAccessItems;
	}

	/**
	 * Sets if the field should display limited access items
	 *
	 * @param boolean $bDisplayLimitedAccessItems
	 *
	 * @return $this
	 */
	public function SetDisplayLimitedAccessItems(bool $bDisplayLimitedAccessItems)
	{
		$this->bDisplayLimitedAccessItems = $bDisplayLimitedAccessItems;

		return $this;
	}
	
	/**
	 * Returns IDs of the linked items with a limited access (not visible or not editable)
	 *
	 * @return array
	 */
	public function GetLimitedAccessItemIDs()
	{
		return $this->aLimitedAccessItemIDs;
	}

	/**
	 * Set the IDs of items with a limited access (not visible ot no editable)
	 *
	 * @param array $aLimitedAccessItemIDs
	 *
	 * @return $this
	 */
	public function SetLimitedAccessItemIDs(array $aLimitedAccessItemIDs)
	{
		$this->aLimitedAccessItemIDs = $aLimitedAccessItemIDs;

		return $this;
	}

	/**
	 * Returns a hash array of attributes to be displayed in the linkedset in the form $sAttCode => $sAttLabel
	 *
	 * @param boolean $bAttCodesOnly If set to true, will return only the attcodes
	 *
	 * @return array
	 */
	public function GetAttributesToDisplay(bool $bAttCodesOnly = false)
	{
		return ($bAttCodesOnly) ? array_keys($this->aAttributesToDisplay) : $this->aAttributesToDisplay;
	}

	/**
	 *
	 * @param array $aAttributesToDisplay
	 *
	 * @return $this
	 */
	public function SetAttributesToDisplay(array $aAttributesToDisplay)
	{
		$this->aAttributesToDisplay = $aAttributesToDisplay;

		return $this;
	}

	/**
	 * Returns a hash array of attributes to be displayed in the linkedset in the form $sAttCode => $sAttLabel
	 *
	 * @since 3.1
	 *
	 * @param boolean $bAttCodesOnly If set to true, will return only the attcodes
	 *
	 * @return array
	 */
	public function GetLnkAttributesToDisplay(bool $bAttCodesOnly = false)
	{
		return ($bAttCodesOnly) ? array_keys($this->aLnkAttributesToDisplay) : $this->aLnkAttributesToDisplay;
    }

    /**
     * @param array $aAttributesToDisplay
     * @return $this
     * @since 3.1.0 N°803
     */
    public function SetLnkAttributesToDisplay(array $aAttributesToDisplay)
    {
        $this->aLnkAttributesToDisplay = $aAttributesToDisplay;

        $this->RemoveValidatorsOfClass(LinkedSetValidator::class);
        $this->AddValidator(new LinkedSetValidator($aAttributesToDisplay));

        return $this;
    }

    /**
     * @return string|null
	 */
	public function GetSearchEndpoint()
	{
		return $this->sSearchEndpoint;
	}

	/**
	 * @param string $sSearchEndpoint
	 *
	 * @return $this
	 */
	public function SetSearchEndpoint(string $sSearchEndpoint)
	{
		$this->sSearchEndpoint = $sSearchEndpoint;

		return $this;
	}

	/**
	 * @return string|null
	 */
	public function GetInformationEndpoint()
	{
		return $this->sInformationEndpoint;
	}

	/**
	 * @param string $sInformationEndpoint
	 *
	 * @return $this
	 */
	public function SetInformationEndpoint(string $sInformationEndpoint)
	{
		$this->sInformationEndpoint = $sInformationEndpoint;

		return $this;
	}

	/**
	 * Returns true if the remote object with $iItemID ID has limited access
	 *
	 * @param int $iItemID
	 *
	 * @return bool
	 */
	public function IsLimitedAccessItem(int $iItemID)
	{
		return in_array($iItemID, $this->aLimitedAccessItemIDs, false);
	}
}
