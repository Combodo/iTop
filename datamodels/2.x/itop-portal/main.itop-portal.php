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

use Combodo\iTop\Portal\Kernel;

/**
 * iTopPortalEditUrlMaker
 * 
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @author Bruno Da Silva <bruno.dasilva@combodo.com>
 * @since 2.3.0
 */
class iTopPortalEditUrlMaker implements iDBObjectURLMaker
{
	private static $oKernel;

    /**
     * Generate an (absolute) URL to an object, either in view or edit mode.
     * Returns null if the current user is not allowed to view / edit object.
     *
     * @param string $sClass The class of the object
     * @param int $iId The identifier of the object
     * @param string $sMode edit|view
     *
     * @return string | null
     *
     * @throws Exception
     * @throws CoreException
     */
	public static function PrepareObjectURL($sClass, $iId, $sMode)
	{
		require_once APPROOT . 'lib/composer-vendor/autoload.php';
		require_once MODULESROOT . 'itop-portal-base/portal/config/bootstrap.php';

		$oKernel = self::GetKernelInstance();
		$oContainer = $oKernel->getContainer();

		/** @var string $sPortalId */
		$sPortalId = $oContainer->getParameter('combodo.portal.instance.id');

		/** @var \Combodo\iTop\Portal\Routing\UrlGenerator $oUrlGenerator */
		$oUrlGenerator = $oContainer->get('url_generator');
		/** @var \Combodo\iTop\Portal\Helper\SecurityHelper $oSecurityHelper */
		$oSecurityHelper = $oContainer->get('security_helper');

		// The object is reachable in the specified mode (edit/view)
		//
		// Note: Scopes only apply when URL check is triggered from the portal GUI.
        $sObjectQueryString = null;
		switch($sMode)
		{
			case 'view':
				if(!ContextTag::Check('GUI:Portal') || $oSecurityHelper->IsActionAllowed(UR_ACTION_READ, $sClass, $iId))
				{
					$sObjectQueryString = $oUrlGenerator->generate('p_object_view', array('sObjectClass' => $sClass, 'sObjectId' => $iId));
				}
			break;
					
			case 'edit':
			default:
				// Checking if user is allowed to edit object, if not we check if it can at least view it.
				if(!ContextTag::Check('GUI:Portal') || $oSecurityHelper->IsActionAllowed(UR_ACTION_MODIFY, $sClass, $iId))
				{
					$sObjectQueryString = $oUrlGenerator->generate('p_object_edit', array('sObjectClass' => $sClass, 'sObjectId' => $iId));
				}
				elseif(!ContextTag::Check('GUI:Portal') || $oSecurityHelper->IsActionAllowed(UR_ACTION_READ, $sClass, $iId))
				{
					$sObjectQueryString = $oUrlGenerator->generate('p_object_view', array('sObjectClass' => $sClass, 'sObjectId' => $iId));
				}
			break;
		}
		
		$sPortalAbsoluteUrl = utils::GetAbsoluteUrlModulePage($sPortalId, 'index.php');
		if($sObjectQueryString === null)
		{
			$sUrl = null;
		}
        elseif (strpos($sPortalAbsoluteUrl, '?') !== false)
		{
		    // Removing generated url query parameters so it can be replaced with those from the absolute url
            // Mostly necessary when iTop instance has multiple portals
		    if(strpos($sObjectQueryString, '?') !== false)
            {
                $sObjectQueryString = substr($sObjectQueryString, 0, strpos($sObjectQueryString, '?'));
            }

            $sUrl = substr($sPortalAbsoluteUrl, 0, strpos($sPortalAbsoluteUrl, '?')).$sObjectQueryString.substr($sPortalAbsoluteUrl, strpos($sPortalAbsoluteUrl, '?'));
		}
		else
		{
            $sUrl = $sPortalAbsoluteUrl.$sObjectQueryString;
		}

		return $sUrl;
	}

    /**
     * @param $sClass
     * @param $iId
     *
     * @return null|string
     *
     * @throws CoreException
     */
	public static function MakeObjectURL($sClass, $iId)
	{	
		return static::PrepareObjectURL($sClass, $iId, 'edit');
	}

	/**
	 * Returns the kernel singleton
	 *
	 * @return \Combodo\iTop\Portal\Kernel
	 * @since 2.7.0
	 */
	private static function GetKernelInstance()
	{
		if(self::$oKernel === null)
		{
			self::$oKernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
			self::$oKernel->boot();
		}

		return self::$oKernel;
	}
}

/**
 * Hyperlinks to the "view" of the object (vs edition)
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @author Bruno Da Silva <bruno.dasilva@combodo.com>
 * @since 2.3.0
 */
class iTopPortalViewUrlMaker extends iTopPortalEditUrlMaker
{
	public static function MakeObjectURL($sClass, $iId)
	{
		return static::PrepareObjectURL($sClass, $iId, 'view');
	}
	
}

// Default portal hyperlink (for notifications) is the edit hyperlink
DBObject::RegisterURLMakerClass('portal', 'iTopPortalEditUrlMaker');
DBObject::RegisterURLMakerClass('itop-portal', 'iTopPortalEditUrlMaker');
DBObject::RegisterURLMakerClass('itop-portal-edit', 'iTopPortalEditUrlMaker');
DBObject::RegisterURLMakerClass('itop-portal-view', 'iTopPortalViewUrlMaker');

