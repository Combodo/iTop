<?php
/**
 * Copyright (C) 2010-2018 Combodo SARL
 *
 * This file is part of iTop.
 *
 *  iTop is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with iTop. If not, see <http://www.gnu.org/licenses/>
 *
 */


namespace Combodo\iTop\Application\Search;


use CMDBObjectSet;
use CoreException;
use Dict;
use MetaModel;
use WebPage;

class SearchForm
{
	public static function GetSearchForm(WebPage $oPage, CMDBObjectSet $oSet, $aExtraParams = array())
	{
		$sHtml = '';

		return $sHtml;
	}

	/**
	 * @param $sClassName
	 *
	 * @throws CoreException
	 */
	public static function GetFields($sClassName)
	{
		$aFields = array();
		$aList = MetaModel::GetZListItems($sClassName, 'standard_search');
		$aAttrDefs = MetaModel::ListAttributeDefs($sClassName);
		foreach($aList as $sFilterCode)
		{
			$aField = array();
			$aField['code'] = $sFilterCode;
			$aField['class'] = $sClassName;
			$aField['class_alias'] = $sClassName;
			$aField['label'] = Dict::S('Class:'.$sClassName.'/Attribute:'.$sFilterCode);
		}

		return $aFields;
	}

}