<?php
/*
 * @copyright   Copyright (C) 2010-2022 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Service;

interface iEventEnrolment
{
	/**
	 * Extension point to register the events and events listeners
	 * @return void
	 */
	public function InitEvents();
}