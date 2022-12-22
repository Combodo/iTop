<?php

// Copyright (C) 2010-2021 Combodo SARL
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

class StringField extends TextField
{
	const DISPLAY_CONDITION_VALIDATION_PATTERN = '/^:template->([A-Za-z0-9_]+)->([A-Za-z0-9_]+)$/';

	/**
	 * @inheritDoc
	 */
	public function GetCurrentValue()
	{
		$value = parent::GetCurrentValue();
		\IssueLog::Error("StringField".$value);
		if ($this->GetReadOnly() && !empty($value)) {
			\IssueLog::Error("GetCurrentValue".$value);
			$aMatches = [];
			if (preg_match(StringField::DISPLAY_CONDITION_VALIDATION_PATTERN, $value, $aMatches, PREG_OFFSET_CAPTURE)) {
				$sLinkedField = $aMatches[1][0];
				if (array_key_exists($aMatches[1][0], $aValues['user_data'])) {
					if (isset($aValues['user_data_objclass'][$sLinkedField])) {
						if ($oObject = MetaModel::GetObject($aValues['user_data_objclass'][$sLinkedField], $aValues['user_data_objkey'][$sLinkedField], false)) {
							$sRet = $oObject->Get($aMatches[2][0]);
						} else {
							$sRet = Dict::Format('UI:Error:Object_Class_Id_NotFound', $aValues['user_data_objclass'][$sLinkedField], $aValues['user_data_objkey'][$sLinkedField]);
						}
					}
				}
			} else {
				$sRet = '<div>'.utils::TextToHtml($value).'</div>';
			}
		}

		return $value;
	}
}
