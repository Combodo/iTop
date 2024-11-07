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

/**
 * Class CompileCSSService : used to ease testing ThemeHander class via mocks
 *
 * @author Olivier DAIN <olivier.dain@combodo.com>
 * @since 3.0.0 N°2982
 */
class CompileCSSService
{
	/**
	 * CompileCSSService constructor.
	 */
	public function __construct()
	{
	}

	public function CompileCSSFromSASS($sSassContent, $aImportPaths =  [], $aVariables = []){
		return utils::CompileCSSFromSASS($sSassContent, $aImportPaths, $aVariables);
	}
}