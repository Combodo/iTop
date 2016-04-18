<?php

// Copyright (C) 2010-2016 Combodo SARL
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

namespace Combodo\iTop\Form\Field;

use \Combodo\iTop\Form\Field\Field;

/**
 * Description of LinkedSetField
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class LinkedSetField extends Field
{
	protected $sTargetClass;
	protected $sExtKeyToRemote;
	protected $bIndirect;
	protected $aAttributesToDisplay;
	protected $sSearchEndpoint;
	protected $sInformationEndpoint;

	public function __construct($sId, \Closure $onFinalizeCallback = null)
	{
		$this->sTargetClass = null;
		$this->sExtKeyToRemote = null;
		$this->bIndirect = false;
		$this->aAttributesToDisplay = array();
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
	 * @return \Combodo\iTop\Form\Field\LinkedSetField
	 */
	public function SetTargetClass($sTargetClass)
	{
		$this->sTargetClass = $sTargetClass;
		return $sTargetClass;
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
	 * @return \Combodo\iTop\Form\Field\LinkedSetField
	 */
	public function SetExtKeyToRemote($sExtKeyToRemote)
	{
		$this->sExtKeyToRemote = $sExtKeyToRemote;
		return $sExtKeyToRemote;
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
	 * @return \Combodo\iTop\Form\Field\LinkedSetField
	 */
	public function SetIndirect($bIndirect)
	{
		$this->bIndirect = $bIndirect;
		return $this;
	}

	/**
	 * Returns a hash array of attributes to be displayed in the linkedset in the form $sAttCode => $sAttLabel
	 *
	 * @param $bAttCodesOnly If set to true, will return only the attcodes
	 * @return array
	 */
	public function GetAttributesToDisplay($bAttCodesOnly = false)
	{
		return ($bAttCodesOnly) ? array_keys($this->aAttributesToDisplay) : $this->aAttributesToDisplay;
	}

	/**
	 *
	 * @param array $aAttCodes
	 * @return \Combodo\iTop\Form\Field\LinkedSetField
	 */
	public function SetAttributesToDisplay(array $aAttributesToDisplay)
	{
		$this->aAttributesToDisplay = $aAttributesToDisplay;
		return $this;
	}

	public function GetSearchEndpoint()
	{
		return $this->sSearchEndpoint;
	}

	public function SetSearchEndpoint($sSearchEndpoint)
	{
		$this->sSearchEndpoint = $sSearchEndpoint;
		return $this;
	}

	public function GetInformationEndpoint()
	{
		return $this->sInformationEndpoint;
	}

	public function SetInformationEndpoint($sInformationEndpoint)
	{
		$this->sInformationEndpoint = $sInformationEndpoint;
		return $this;
	}

}
