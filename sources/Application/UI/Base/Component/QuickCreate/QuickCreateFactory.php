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

namespace Combodo\iTop\Application\UI\Base\Component\QuickCreate;


/**
 * Class QuickCreateFactory
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\QuickCreate
 * @internal
 * @since 3.0.0
 */
class QuickCreateFactory
{
	/**
	 * Make a QuickCreate component with the last classes from the current user
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\QuickCreate\QuickCreate
	 * @throws \CoreException
	 * @throws \CoreUnexpectedValue
	 * @throws \MySQLException
	 */
	public static function MakeFromUserHistory()
	{
		$aLastClasses = QuickCreateHelper::GetLastClasses();

		return new QuickCreate($aLastClasses, QuickCreate::BLOCK_CODE);
	}
}