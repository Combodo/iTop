<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service\Events;

/**
 * Interface to implement in order to register the events and listeners
 *
 * @api
 * @package EventsAPI
 * @since 3.1.0
 */
interface iEventServiceSetup
{
	/**
	 * Extension point to register the events and events listeners
	 *
	 * @api
	 * @return void
	 */
	public function RegisterEventsAndListeners();
}