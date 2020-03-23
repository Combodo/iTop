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


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\VarDumper\Cloner\Data;

class LegacyQueryDataCollector extends DataCollector
{
	/**
	 * @var array|Data
	 */
	protected $data = [
		'queries' => [],
	];
//	public function OnDBObjectSet(\DBObjectSet $DBObjectSet)
//	{
//		$row = array(
//			'string' => $DBObjectSet->__toString(),
//			'OQL' => $DBObjectSet->GetFilter()->ToOQL(),
//			'count' => $DBObjectSet->Count(),
//		);
//		$this->data['qyery'][] = $row;
//	}
	public function ComputeStats($sOperation, $sArguments, $aDetails)
	{
		$row = array(
			'operation' => $sOperation,
			'arguments' => $sArguments,
			'details'   => $aDetails,
		);

		$this->data['queries'][] = $row;
	}


	public function collect(Request $request, Response $response, \Exception $exception = null)
	{
		$this->data['count'] = count(@$this->data['queries']);
	}


	public function reset()
	{
		$this->data = [];
	}

	public function getName()
	{
		return 'app.legacy_query.collector';
	}

	public function getCount()
	{
		return count(@$this->data['queries']);
	}

	public function getQueries()
	{
		return $this->data['queries'];
	}


}