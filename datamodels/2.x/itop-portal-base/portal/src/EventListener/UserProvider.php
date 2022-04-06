<?php
/**
 * Copyright (C) 2013-2021 Combodo SARL
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

namespace Combodo\iTop\Portal\EventListener;

use Dict;
use Exception;
use LoginWebPage;
use ModuleDesign;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use UserRights;

/**
 * Class UserProvider
 *
 * @package Combodo\iTop\Portal\EventListener
 * @since 2.7.0
 */
class UserProvider implements ContainerAwareInterface
{
	/** @var \ModuleDesign $oModuleDesign */
	private $oModuleDesign;
	/** @var string $sPortalId */
	private $sPortalId;
	/** @var \Symfony\Component\DependencyInjection\ContainerInterface $container */
	private $oContainer;

	/**
	 * UserProvider constructor.
	 *
	 * @param \ModuleDesign $oModuleDesign
	 * @param string        $sPortalId
	 */
	public function __construct(ModuleDesign $oModuleDesign, $sPortalId)
	{
		$this->oModuleDesign = $oModuleDesign;
		$this->sPortalId = $sPortalId;
	}

	/**
	 * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $oGetResponseEvent
	 *
	 * @throws \Exception
	 */
	public function onKernelRequest(GetResponseEvent $oGetResponseEvent)
	{
		// User pre-checks
		// Note: The following note and handling of the $iExitMethod were for the old login mechanism
		// and hasn't been reworked after the introduction of the new one as we saw it too late.
		// $iExitMethod and $iLogonRes may be useless now as the DoLoginEx method exits directly sometimes.
		//
		// Note: At this point the Exception handler is not registered, so we can't use $oApp::abort() method, hence the die().
		// - Checking user rights and prompt if needed (401 HTTP code returned if XHR request)
		$iExitMethod = ($oGetResponseEvent->getRequest()->isXmlHttpRequest()) ? LoginWebPage::EXIT_RETURN : LoginWebPage::EXIT_PROMPT;
		$iLogonRes = LoginWebPage::DoLoginEx($this->sPortalId, false, $iExitMethod);
		if( ($iExitMethod === LoginWebPage::EXIT_RETURN) && ($iLogonRes != 0) )
		{
			die(Dict::S('Portal:ErrorUserLoggedOut'));
		}
		// - User must be associated with a Contact
		if (UserRights::GetContactId() == 0)
		{
			die(Dict::S('Portal:ErrorNoContactForThisUser'));
		}

		// User
		$oUser = UserRights::GetUserObject();
		if ($oUser === null)
		{
			throw new Exception('Could not load connected user.');
		}
		$this->oContainer->set('combodo.current_user', $oUser);

		// Allowed portals
		$aAllowedPortals = UserRights::GetAllowedPortals();

		// Checking that user is allowed this portal
		$bAllowed = false;
		foreach ($aAllowedPortals as $aAllowedPortal)
		{
			if ($aAllowedPortal['id'] === $this->sPortalId)
			{
				$bAllowed = true;
				break;
			}
		}
		if (!$bAllowed)
		{
			throw new HttpException(Response::HTTP_NOT_FOUND);
		}
		/** @noinspection PhpParamsInspection It's an array and it's gonna stay that way */
		$this->oContainer->set('combodo.current_user.allowed_portals', $aAllowedPortals);
	}

	/**
	 * @inheritDoc
	 */
	public function setContainer(ContainerInterface $oContainer = null)
	{
		$this->oContainer = $oContainer;
	}
}