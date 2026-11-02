<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\EventRegister;

use Combodo\iTop\Service\Events\Description\EventDataDescription;
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
	public const APPLICATION_EVENT_METAMODEL_STARTED = 'APPLICATION_EVENT_METAMODEL_STARTED';
	/** @since 3.3.0 N°1365 - Allow extensions to react to CaseLog edits */
	public const APPLICATION_EVENT_CASELOG_ENTRY_EDITED = 'EVENT_CASELOG_ENTRY_EDITED';

	/**
	 * @inheritDoc
	 * @since 3.3.0 N°1365 - Register the CaseLog edit event
	 */
	public function RegisterEventsAndListeners()
	{
		EventService::RegisterEvent(new EventDescription(
			self::APPLICATION_EVENT_METAMODEL_STARTED,
			null,
			'The MetaModel is fully started',
			'',
			[],
			'application'
		));

		if (false === EventService::IsEventRegistered(self::APPLICATION_EVENT_CASELOG_ENTRY_EDITED)) {
			EventService::RegisterEvent(new EventDescription(
				self::APPLICATION_EVENT_CASELOG_ENTRY_EDITED,
				[
					'cmdbAbstractObject' => 'cmdbAbstractObject',
				],
				'An existing CaseLog entry was edited',
				'Triggered after a CaseLog entry has been edited. Event data contains the object, attribute_code, and entry_index.',
				[
					new EventDataDescription('object', 'The object containing the edited CaseLog.', 'DBObject'),
					new EventDataDescription('attribute_code', 'The edited CaseLog attribute code.', 'string'),
					new EventDataDescription('entry_index', 'The zero-based internal index of the edited entry.', 'integer'),
					new EventDataDescription('debug_info', 'Debug string.', 'string'),
				],
				'application'
			));
		}
	}
}
