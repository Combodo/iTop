<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Controller\Base\Layout;

use Combodo\iTop\Controller\AbstractController;
use Exception;
use Combodo\iTop\Application\WebPage\JsonPage;
use ModelReflection;
use ModelReflectionRuntime;

class OqlController extends AbstractController
{
	public const ROUTE_NAMESPACE = 'oql';

	public function OperationValidateQuery()
	{
		$oPage = new JsonPage();
		$oPage->SetOutputDataOnly(true);

		$data = json_decode(file_get_contents('php://input'), true);
		$sOql = $data['query'];

		try {
			/** @var ModelReflection $oModelReflection */
			$oModelReflexion = new ModelReflectionRuntime();
			$oModelReflexion->GetQuery($sOql);
		} catch (Exception $e) {

		}

		$oPage->SetData([
			'is_valid' => !isset($e),
		]);

		$oPage->output();
	}

}
