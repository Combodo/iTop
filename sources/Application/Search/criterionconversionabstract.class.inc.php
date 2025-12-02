<?php

/**
 * Copyright (C) 2010-2024 Combodo SAS
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

abstract class CriterionConversionAbstract
{
	public const OP_CONTAINS = 'contains';
	public const OP_EQUALS = '=';
	public const OP_STARTS_WITH = 'starts_with';
	public const OP_ENDS_WITH = 'ends_with';
	public const OP_EMPTY = 'empty';
	public const OP_NOT_EMPTY = 'not_empty';
	public const OP_IN = 'IN';
	public const OP_BETWEEN_DATES = 'between_dates';
	public const OP_BETWEEN = 'between';
	public const OP_REGEXP = 'REGEXP';
	public const OP_ALL = 'all';
	public const OP_MATCHES = 'MATCHES';

}
