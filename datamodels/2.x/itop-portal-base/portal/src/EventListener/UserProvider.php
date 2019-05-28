<?php
/**
 * Copyright (C) 2013-2019 Combodo SARL
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
 *
 *
 */

namespace Combodo\iTop\Portal\EventListener;

use Combodo\iTop\Portal\Security\ItopUser;
use Combodo\iTop\Portal\VariableAccessor\CombodoCurrentContactPhotoUrl;
use MetaModel;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use utils;
use Dict;
use LoginWebPage;
use UserRights;
use ModuleDesign;

/**
 * Class UserProvider
 *
 * @package Combodo\iTop\Portal\EventListener
 * @since 2.7.0
 */
class UserProvider
{
    /** @var \ModuleDesign */
    private $oModuleDesign;
	/** @var ItopUser */
	private $oItopUser;
    /** @var string */
    private $sCombodoPortalBaseAbsoluteUrl;

	/**
	 * UserProvider constructor.
	 *
	 * @param \ModuleDesign $oModuleDesign
	 * @param \Combodo\iTop\Portal\Security\ItopUser $oItopUser
	 * @param string $sCombodoPortalBaseAbsolutePath Automatically bind to parameter of the name (see services.yml for other examples)
	 */
    public function __construct(ModuleDesign $oModuleDesign, ItopUser $oItopUser, $sCombodoPortalBaseAbsolutePath)
    {
        $this->oModuleDesign = $oModuleDesign;
	    $this->oItopUser = $oItopUser;
        $this->sCombodoPortalBaseAbsoluteUrl = $sCombodoPortalBaseAbsolutePath;
    }

	/**
	 * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $oGetResponseEvent
	 *
	 * @throws \Exception
	 */
    public function onKernelRequest(GetResponseEvent $oGetResponseEvent)
    {
        // User pre-checks
        // Note: At this point the Exception handler is not registered, so we can't use $oApp::abort() method, hence the die().
        // - Checking user rights and prompt if needed (401 HTTP code returned if XHR request)
        $iExitMethod = ($oGetResponseEvent->getRequest()->isXmlHttpRequest()) ? LoginWebPage::EXIT_RETURN : LoginWebPage::EXIT_PROMPT;
        $iLogonRes = LoginWebPage::DoLoginEx(PORTAL_ID, false, $iExitMethod);
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
            throw new \Exception('Could not load connected user.');
        }
	    $this->oItopUser->setUser($oUser);

        // Contact
        $sContactPhotoUrl = "{$this->sCombodoPortalBaseAbsoluteUrl}img/user-profile-default-256px.png";
        // - Checking if we can load the contact
        try
        {
            $oContact = UserRights::GetContactObject();
        }
        catch (Exception $e)
        {
            $oAllowedOrgSet = $oUser->Get('allowed_org_list');
            if ($oAllowedOrgSet->Count() > 0)
            {
                throw new Exception('Could not load contact related to connected user. (Tip: Make sure the contact\'s organization is among the user\'s allowed organizations)');
            }
            else
            {
                throw new Exception('Could not load contact related to connected user.');
            }
        }
        // - Retrieving picture
        if ($oContact)
        {
            if (MetaModel::IsValidAttCode(get_class($oContact), 'picture'))
            {
                $oImage = $oContact->Get('picture');
                if (is_object($oImage) && !$oImage->IsEmpty())
                {
                    $sContactPhotoUrl = $oImage->GetDownloadURL(get_class($oContact), $oContact->GetKey(), 'picture');
                }
                else
                {
                    $sContactPhotoUrl = MetaModel::GetAttributeDef(get_class($oContact), 'picture')->Get('default_image');
                }
            }
        }
	    $this->oItopUser->setContactPhotoUrl($sContactPhotoUrl);
    }


}