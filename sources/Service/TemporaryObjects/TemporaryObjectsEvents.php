<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\TemporaryObjects;

use Combodo\iTop\Service\Events\Description\EventDataDescription;
use Combodo\iTop\Service\Events\Description\EventDescription;
use Combodo\iTop\Service\Events\EventService;
use Combodo\iTop\Service\Events\iEventServiceSetup;

/**
 * TemporaryObjectsEvents.
 *
 * Handle temporary objects events
 *
 * @experimental do not use, this feature will be part of a future version
 *
 * @since 3.1
 */
class TemporaryObjectsEvents implements iEventServiceSetup
{

	// Startup events
	const TEMPORARY_OBJECT_EVENT_CONFIRM_CREATE = 'TEMPORARY_OBJECT_EVENT_CONFIRM_CREATE';

	/**
	 * @inheritDoc
	 */
	public function RegisterEventsAndListeners()
	{
		EventService::RegisterEvent(new EventDescription(
			self::TEMPORARY_OBJECT_EVENT_CONFIRM_CREATE,
			[
				'cmdbAbstractObject' => 'cmdbAbstractObject',
			],
			'The MetaModel is fully started',
			'',
			[
				new EventDataDescription(
					'object',
					'The object concerned by the creation confirmation',
					'DBObject',
				),
				new EventDataDescription(
					'debug_info',
					'Debug string',
					'string',
				),
			],
			'application'));
	}

}