<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\EventRegister;

use Combodo\iTop\Service\Events\Description\EventDescription;
use Combodo\iTop\Service\Events\EventService;
use Combodo\iTop\Service\Events\iEventServiceSetup;

/**
 * Class ApplicationEvents
 *
 * @author Eric Espie <eric.espie@combodo.com>
 * @package Combodo\iTop\Application\EventRegister
 * @since 3.1.0
 */
class ApplicationEvents implements iEventServiceSetup
{
	// Startup events
	const APPLICATION_EVENT_METAMODEL_STARTED = 'APPLICATION_EVENT_METAMODEL_STARTED';

	/**
	 * @inheritDoc
	 */
	public function RegisterEventsAndListeners()
	{
		EventService::RegisterEvent(new EventDescription(
			self::APPLICATION_EVENT_METAMODEL_STARTED,
			null,
			'The MetaModel is fully started',
			'',
			[],
			'application'));
	}
}