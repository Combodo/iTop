<?php

// Copyright (C) 2010-2024 Combodo SAS
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

/**
 * Description of CaseLogField
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since iTop 2.3.0
 */
class CaseLogField extends TextAreaField
{
	/** @var array */
	protected $aEntries;

	/**
	 * @inheritDoc
	 */
	public function SetMustChange(bool $bMustChange)
	{
		$this->SetMandatory($bMustChange);
		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function GetEntries()
	{
		return $this->aEntries;
	}

	/**
	 *
	 * @param array $aEntries
	 * @return \Combodo\iTop\Form\Field\TextAreaField
	 */
	public function SetEntries(array $aEntries)
	{
		$this->aEntries = $aEntries;
		return $this;
	}

}
