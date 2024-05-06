<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Controller\TemporaryObjects;

use Combodo\iTop\Controller\AbstractController;
use Combodo\iTop\Service\TemporaryObjects\TemporaryObjectManager;
use Combodo\iTop\Application\WebPage\JsonPage;
use utils;

/**
 * TemporaryObjectController.
 *
 * Temporary object endpoints.
 *
 * @experimental do not use, this feature will be part of a future version
 *
 * @since 3.1
 */
class TemporaryObjectController extends AbstractController
{
	public const ROUTE_NAMESPACE = 'temporary_object';

	/** @var \Combodo\iTop\Service\TemporaryObjects\TemporaryObjectManager Temporary object manager */
	private TemporaryObjectManager $oTemporaryObjectManager;

	/**
	 * Constructor.
	 *
	 */
	public function __construct()
	{
		// Retrieve controller dependencies
		$this->oTemporaryObjectManager = TemporaryObjectManager::GetInstance();
	}

	/**
	 * OperationWatchDog.
	 *
	 * Watchdog for delaying expiration date of temporary objects linked to the provided temporary id.
	 *
	 * @return JsonPage
	 */
	public function OperationWatchDog(): JsonPage
	{
		$oPage = new JsonPage();

		// Retrieve temp id
		$sTempId = utils::ReadParam('temp_id', '', false, utils::ENUM_SANITIZATION_FILTER_STRING);

		// Delay temporary objects expiration
		$bResult = $this->oTemporaryObjectManager->ExtendTemporaryObjectsLifetime($sTempId);

		return $oPage->SetData([
			'success' => $bResult,
		]);
	}

	/**
	 * OperationGarbage.
	 *
	 * Garbage temporary objects based on expiration date.
	 *
	 * @return JsonPage
	 */
	public function OperationGarbage(): JsonPage
	{
		$oPage = new JsonPage();

		// Garbage expired temporary objects
		$bResult = $this->oTemporaryObjectManager->GarbageExpiredTemporaryObjects();

		return $oPage->SetData([
			'success' => $bResult,
		]);
	}
}