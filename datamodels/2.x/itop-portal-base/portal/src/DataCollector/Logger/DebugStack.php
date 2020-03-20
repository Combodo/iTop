<?php
/**
 * Copyright (C) 2010-2020 Combodo SARL
 *
 *   This file is part of iTop.
 *
 *   iTop is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU Affero General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   iTop is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU Affero General Public License for more details.
 *
 *   You should have received a copy of the GNU Affero General Public License
 *   along with iTop. If not, see <http: *www.gnu.org/licenses/>
 *
 */


namespace Combodo\iTop\Portal\DataCollector\Logger;


class DebugStack
{
	/**
	 * Executed SQL queries.
	 *
	 * @var mixed[][]
	 */
	public $queries = [];

	/**
	 * If Debug Stack is enabled (log queries) or not.
	 *
	 * @var bool
	 */
	public $enabled = true;

	/** @var float|null */
	public $start = null;

	/** @var int */
	public $currentQuery = 0;

	/**
	 * {@inheritdoc}
	 */
	public function startQuery()
	{
		if (! $this->enabled) {
			return;
		}

		$this->start                          = microtime(true);
		$this->queries[++$this->currentQuery] = [];
	}

	/**
	 * {@inheritdoc}
	 */
	public function stopQuery(\DBObjectSet $DBObjectSet)
	{
		if (! $this->enabled) {
			return;
		}

		$this->queries[$this->currentQuery] = [
			'sql' => $DBObjectSet->GetFilter()->MakeSelectQuery(),
			'OQL' => $DBObjectSet->GetFilter()->ToOQL(),
			'count' => $DBObjectSet->Count(),
			'params' => $DBObjectSet->GetArgs(),
			'types' => array_map('gettype', $DBObjectSet->GetArgs()),
			'executionMS' => microtime(true) - $this->start,
		];


	}
}