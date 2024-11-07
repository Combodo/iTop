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

	const OP_CONTAINS = 'contains';
	const OP_EQUALS = '=';
	const OP_STARTS_WITH = 'starts_with';
	const OP_ENDS_WITH = 'ends_with';
	const OP_EMPTY = 'empty';
	const OP_NOT_EMPTY = 'not_empty';
	const OP_IN = 'IN';
	const OP_BETWEEN_DATES = 'between_dates';
	const OP_BETWEEN = 'between';
	const OP_REGEXP = 'REGEXP';
	const OP_ALL = 'all';
	const OP_MATCHES = 'MATCHES';

}

