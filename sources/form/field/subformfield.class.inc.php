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

use \Closure;
use \Combodo\iTop\Form\Field\Field;
use \Combodo\iTop\Form\Form;

/**
 * Description of StringField
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class SubFormField extends Field
{
	protected $oForm;

	public function __construct($sId, $sParentFormId, Closure $onFinalizeCallback = null)
	{
		$this->oForm = new \Combodo\iTop\Form\Form($sParentFormId.'-subform_'.$sId);
		parent::__construct($sId, $onFinalizeCallback);
	}

	public function GetForm()
	{
		return $this->oForm;
	}

	/**
	 * Checks the validators to see if the field's current value is valid.
	 * Then sets $bValid and $aErrorMessages.
	 *
	 * @return boolean
	 */
	public function Validate()
	{
		$this->oForm->Validate();
	}

	public function GetValid()
	{
		return $this->oForm->GetValid();
	}

	public function GetErrorMessages()
	{
		return $this->oForm->GetErrorMessages();
	}

	public function GetCurrentValue()
	{
		return $this->oForm->GetCurrentValues();
	}

	public function SetCurrentValue($value)
	{
		return $this->oForm->SetCurrentValues($value);
	}
}
