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


namespace Combodo\iTop\DataCollector\Logger;


use Symfony\Component\Stopwatch\Stopwatch;

class DebugStackWebPage
{
	/**
	 * generated Pages .
	 *
	 * @var mixed[][]
	 */
	public $aWebPage = [];

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
	 * @var null|\Symfony\Component\Stopwatch\Stopwatch
	 */
	private $stopwatch;

	public function __construct(Stopwatch $stopwatch = null)
	{
		$this->stopwatch = $stopwatch;
	}

	/**
	 * {@inheritdoc}
	 */
	public function startPage(\WebPage $page, $sTitle)
	{
		if (! $this->enabled) {
			return;
		}

		$this->start                          = microtime(true);
		$this->aWebPage[++$this->currentQuery] = [];

		if ($this->stopwatch) {
			$spl_object_hash = spl_object_hash($page);
			$this->events[$spl_object_hash] = $this->stopwatch->start(get_class($page) . ': '.$sTitle, 'WebPage');
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function stopPage(\WebPage $page)
	{
		if (! $this->enabled) {
			return;
		}

		$this->aWebPage[$this->currentQuery] = [
			'outputFormat' => $page->GetOutputFormat(),
			'transactionId' => $page->GetTransactionId(),

			'isPdf' => $page->is_pdf(),
			'isPrintableVersion' => $page->IsPrintableVersion(),
		];

		if ($this->stopwatch) {
			$spl_object_hash = spl_object_hash($page);
			$this->events[$spl_object_hash]->stop();;
			unset($this->events[$spl_object_hash]);
		}

	}
}