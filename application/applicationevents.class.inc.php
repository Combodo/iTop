<?php

/*
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Service\EventService;
use Combodo\iTop\Service\iEventEnrolment;

class ApplicationEvents implements iEventEnrolment
{
	// Startup events
	const APPLICATION_EVENT_REQUEST_RECEIVED = 'APPLICATION_EVENT_REQUEST_RECEIVED';
	const APPLICATION_EVENT_METAMODEL_STARTED = 'APPLICATION_EVENT_METAMODEL_STARTED';

	/**
	 * @inheritDoc
	 */
	public function InitEvents()
	{
		EventService::RegisterEvent(self::APPLICATION_EVENT_REQUEST_RECEIVED, [
			'description' => 'A request was received from the network, at this point only the session is started, the configuration is not even loaded',
		], 'application');
		EventService::RegisterEvent(self::APPLICATION_EVENT_METAMODEL_STARTED, [
			'description' => 'The MetaModel is fully started',
		], 'application');
	}
}