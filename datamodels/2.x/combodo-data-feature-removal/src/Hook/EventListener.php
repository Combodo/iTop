<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\DataFeatureRemoval\Hook;

use Combodo\iTop\DataFeatureRemoval\Helper\DataFeatureRemovalHelper;
use Combodo\iTop\Service\Events\EventData;
use Combodo\iTop\Service\Events\EventService;
use Combodo\iTop\Service\Events\iEventServiceSetup;
use MetaModel;
use utils;

class EventListener implements iEventServiceSetup
{
	/**
	 * @inheritDoc
	 */
	public function RegisterEventsAndListeners()
	{
		EventService::RegisterListener(
			sEvent: \EVENT_SERVICE_LOCATOR_INITIALIZED,
			callback: [$this, 'OnServiceLocatorInitialized']
		);
	}

	public function OnServiceLocatorInitialized(EventData $oEventData): void
	{
		MetaModel::GetServiceLocator()->AddClassesFromFile(utils::GetAbsoluteModulePath(DataFeatureRemovalHelper::MODULE_NAME).'Config/ServiceLocator.xml');
	}
}
