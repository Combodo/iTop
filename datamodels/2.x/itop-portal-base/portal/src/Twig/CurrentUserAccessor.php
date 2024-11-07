<?php
/**
 * Copyright (C) 2013-2024 Combodo SAS
 *
 * This file is part of iTop.
 *
 * iTop is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 */

namespace Combodo\iTop\Portal\Twig;

use Combodo\iTop\Portal\EventListener\UserProvider;

/**
 * Class CurrentUserAccessor
 *
 * Compatibility purpose 3.1:
 * Twig templates access current user objet directly from container, but it's not possible anymore.
 * >> app['combodo.current_user'].Get('first_name')
 * To prevent changes in templates we expose a service CurrentUserAccessor with a bridge role.
 *
 * @author Benjamin Dalsass <benjamin.dalsass@combodo.com>
 * @package Combodo\iTop\Portal\Twig
 * @since   3.1.0
 */
class CurrentUserAccessor
{
	/** @var \Combodo\iTop\Portal\EventListener\UserProvider $userProvider */
	private $userProvider;

	/**
	 * Constructor.
	 *
	 * @param \Combodo\iTop\Portal\EventListener\UserProvider $userProvider
	 */
	public function __construct(UserProvider $userProvider)
	{
		$this->userProvider = $userProvider;
	}

	/**
	 * Get (UserLocal meme function)
	 *
	 * @param $key
	 *
	 * @return int|mixed|\ormLinkSet|string|null
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	public function Get($key)
	{
		return $this->userProvider->getCurrentUser()->Get($key);
	}

	/**
	 * @return bool
	 * @since 3.1.2 3.2.0
	 */
	public function CanLogOff(): bool
	{
		return $this->userProvider->getCurrentUserCanLogOff();
	}
}