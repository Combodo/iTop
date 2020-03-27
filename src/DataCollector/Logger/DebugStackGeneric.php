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

class DebugStackGeneric
{
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
	public function start($IdOrObject, $sName, $sCategory)
	{
		$sEventId = $this->getEventIdFrom($IdOrObject);

		if ($this->stopwatch) {
			$this->events[$sEventId] = $this->stopwatch->start($sName , $sCategory);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function stop($IdOrObject)
	{
		$sEventId = $this->getEventIdFrom($IdOrObject);

		if ($this->stopwatch) {
			$this->events[$sEventId]->stop();;
			unset($this->events[$sEventId]);
		}

	}

	private function getEventIdFrom($IdOrObject)
	{
		if (is_object($IdOrObject))
		{
			return spl_object_hash($IdOrObject);
		}

		return $IdOrObject;
	}
}