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

namespace Combodo\iTop\DataCollector;


use Combodo\iTop\DataCollector\Logger\DebugStackDBObjectSet;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\VarDumper\Cloner\Data;

class CopycatDoctrineDataCollector extends DataCollector
{
	/** @var string[] */
	private $groupedQueries;

	/**
	 * @var array|Data
	 */
	protected $data = [
		'queries' => ['main' => []],
		'errors' => [],
		'entities' => [],
		'connections' => ['main'], //TODO: remove this concept that do not exists in itop
		'caches' => [                //TODO: comput the values below, cf vendor/doctrine/doctrine-bundle/DataCollector/DoctrineDataCollector.php:61
			'enabled' => true,
			'log_enabled' => false,
			'counts' => [
				'puts' => 0,
				'hits' => 0,
				'misses' => 0,
			],
			'regions' => [
				'puts' => [],
				'hits' => [],
				'misses' => [],
			],
		],
	];
	/**
	 * @var \Combodo\Portal\DataCollector\Logger\DebugStackDBObjectSet
	 */
	private $debugStack;

	public function __construct(DebugStackDBObjectSet $debugStack)
	{

		$this->debugStack = $debugStack;
	}

	public function onFetch(\DBObjectSet $DBObjectSet)
	{
//		$query = array(
//			'sql' => $DBObjectSet->GetFilter()->MakeSelectQuery(),
//			'OQL' => $DBObjectSet->GetFilter()->ToOQL(),
//			'count' => $DBObjectSet->Count(),
//			'params' => $DBObjectSet->GetArgs(),
//			'explainable' => true,
//			'runnable' => true,
//			'types' => [],
//			'executionMS' => rand(1,42) / 100, //TODO: compute this
//			'count' => 42, //TODO: compute this
//			'index' => 42, //TODO: compute this
//
//		);
//
//		$this->data['queries']['main'][] = $query;


		usleep(1);;
	}



	public function collect(Request $request, Response $response, \Exception $exception = null)
	{
		$queries['main'] = [];
		foreach ($this->debugStack->queries as $i => $query)
		{
			$queries['main'][$i] = array_merge(
				[
					'explainable' => true,
					'runnable' => true,
					'index' => $i,
				],
				$query
			);
		}


		$this->data = [
			'queries' => $queries,
			'connections' => 'main',
		];
	}


	public function reset()
	{
		$this->data = [];
	}

	public function getName()
	{
		return 'app.copycat_doctrine.collector';
	}

	public function getCount()
	{
		return count($this->data['queries']);
	}

	public function getQueries()
	{
		return $this->data['queries'];
	}

	public function getConnections()
	{
		return isset($this->data['connections']) ? $this->data['connections'] : 'main';//TODO: this should fail if not set properly
	}

	public function getCacheEnabled()
	{
		return isset($this->data['caches']['enabled']) ? $this->data['caches']['enabled'] : true;//TODO: this should fail if not set properly
	}

	public function getCacheHitsCount()
	{
		return isset($this->data['caches']['counts']['hits']) ? $this->data['caches']['counts']['hits'] : null;//TODO: this should fail if not set properly
	}

	public function getCacheMissesCount()
	{
		return isset($this->data['caches']['counts']['misses']) ? $this->data['caches']['counts']['misses'] : null;//TODO: this should fail if not set properly
	}

	public function getCachePutsCount()
	{
		return isset($this->data['caches']['counts']['puts']) ? $this->data['caches']['counts']['puts'] : null;//TODO: this should fail if not set properly
	}

	public function getTime()
	{
		$time = 0;
		foreach ($this->data['queries'] as $queries) {
			foreach ($queries as $query) {
				$time += $query['executionMS'];
			}
		}

		return $time;
	}

	public function getQueryCount()
	{
		return array_sum(array_map('count', $this->data['queries']));
	}

	public function getGroupedQueryCount()
	{
		$count = 0;
		foreach ($this->getGroupedQueries() as $connectionGroupedQueries) {
			$count += count($connectionGroupedQueries);
		}

		return $count;
	}

	public function getGroupedQueries()
	{
		if ($this->groupedQueries !== null) {
			return $this->groupedQueries;
		}

		$this->groupedQueries = [];
		$totalExecutionMS     = 0;
		foreach ($this->data['queries'] as $connection => $queries) {
			$connectionGroupedQueries = [];
			foreach ($queries as $i => $query) {
				$key = $query['sql'];
				if (! isset($connectionGroupedQueries[$key])) {
					$connectionGroupedQueries[$key]                = $query;
					$connectionGroupedQueries[$key]['executionMS'] = 0;
					$connectionGroupedQueries[$key]['count']       = 0;
					$connectionGroupedQueries[$key]['index']       = $i; // "Explain query" relies on query index in 'queries'.
				}
				$connectionGroupedQueries[$key]['executionMS'] += $query['executionMS'];
				$connectionGroupedQueries[$key]['count']++;
				$totalExecutionMS += $query['executionMS'];
			}
			usort($connectionGroupedQueries, static function ($a, $b) {
				if ($a['executionMS'] === $b['executionMS']) {
					return 0;
				}

				return $a['executionMS'] < $b['executionMS'] ? 1 : -1;
			});
			$this->groupedQueries[$connection] = $connectionGroupedQueries;
		}

		foreach ($this->groupedQueries as $connection => $queries) {
			foreach ($queries as $i => $query) {
				$this->groupedQueries[$connection][$i]['executionPercent'] =
					$this->executionTimePercentage($query['executionMS'], $totalExecutionMS);
			}
		}

		return $this->groupedQueries;
	}

	private function executionTimePercentage($executionTimeMS, $totalExecutionTimeMS)
	{
		if ($totalExecutionTimeMS === 0.0 || $totalExecutionTimeMS === 0) {
			return 0;
		}

		return $executionTimeMS / $totalExecutionTimeMS * 100;
	}
}