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

namespace Combodo\iTop\Portal\UrlMaker;

use Combodo\iTop\Portal\Kernel;
use ContextTag;
use CoreException;
use Exception;
use iDBObjectURLMaker;
use utils;

/**
 * AbstractPortalUrlMaker
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @author Bruno Da Silva <bruno.dasilva@combodo.com>
 * @since  2.3.0
 */
abstract class AbstractPortalUrlMaker implements iDBObjectURLMaker
{
	/** @var string PORTAL_ID */
	const PORTAL_ID = '';

	/** @var \Combodo\iTop\Portal\Kernel[] $aKernels */
	private static $aKernels = array();

	/**
	 * Generate an (absolute) URL to an object, either in view or edit mode.
	 * Returns null if the current user is not allowed to view / edit object.
	 *
	 * @param string $sClass The class of the object
	 * @param int    $iId    The identifier of the object
	 * @param string $sMode  edit|view
	 *
	 * @return string | null
	 *
	 * @throws Exception
	 * @throws CoreException
	 */
	public static function PrepareObjectURL($sClass, $iId, $sMode)
	{
		$sPreviousPortalId = (isset($_ENV['PORTAL_ID'])) ? $_ENV['PORTAL_ID'] : '';
		$_ENV['PORTAL_ID'] = static::PORTAL_ID;

		require MODULESROOT.'itop-portal-base/portal/config/bootstrap.php';

		$sUrl = self::DoPrepareObjectURL($sClass, $iId, $sMode);

		if (!empty($sPreviousPortalId))
		{
			$_ENV['PORTAL_ID'] = $sPreviousPortalId;
		}

		return $sUrl;
	}

	/**
	 * @param string $sClass
	 * @param int    $iId
	 * @param string $sMode
	 *
	 * @return string|null
	 * @throws \CoreException
	 */
	private static function DoPrepareObjectURL($sClass, $iId, $sMode)
	{
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
		switch ($sMode)
		{
			case 'view':
				if (!ContextTag::Check(ContextTag::TAG_PORTAL) || $oSecurityHelper->IsActionAllowed(UR_ACTION_READ, $sClass, $iId))
				{
					$sObjectQueryString = $oUrlGenerator->generate('p_object_view', array('sObjectClass' => $sClass, 'sObjectId' => $iId));
				}
				break;

			case 'edit':
			default:
				// Checking if user is allowed to edit object, if not we check if it can at least view it.
				if (!ContextTag::Check(ContextTag::TAG_PORTAL) || $oSecurityHelper->IsActionAllowed(UR_ACTION_MODIFY, $sClass, $iId))
				{
					$sObjectQueryString = $oUrlGenerator->generate('p_object_edit', array('sObjectClass' => $sClass, 'sObjectId' => $iId));
				}
				elseif (!ContextTag::Check(ContextTag::TAG_PORTAL) || $oSecurityHelper->IsActionAllowed(UR_ACTION_READ, $sClass, $iId))
				{
					$sObjectQueryString = $oUrlGenerator->generate('p_object_view', array('sObjectClass' => $sClass, 'sObjectId' => $iId));
				}
				break;
		}

		$sPortalAbsoluteUrl = utils::GetAbsoluteUrlModulePage('itop-portal-base', 'index.php', array('portal_id' => $sPortalId));
		if ($sObjectQueryString === null)
		{
			$sUrl = null;
		}
		elseif (strpos($sPortalAbsoluteUrl, '?') !== false)
		{
			// Removing generated url query parameters so it can be replaced with those from the absolute url
			// Mostly necessary when iTop instance has multiple portals
			if (strpos($sObjectQueryString, '?') !== false)
			{
				$sObjectQueryString = substr($sObjectQueryString, 0, strpos($sObjectQueryString, '?'));
			}

			$sUrl = substr($sPortalAbsoluteUrl, 0, strpos($sPortalAbsoluteUrl, '?')).$sObjectQueryString.substr($sPortalAbsoluteUrl,
					strpos($sPortalAbsoluteUrl, '?'));
		}
		else
		{
			$sUrl = $sPortalAbsoluteUrl.$sObjectQueryString;
		}

		return $sUrl;
	}

	/**
	 * Returns the kernel singleton
	 *
	 * @return \Combodo\iTop\Portal\Kernel
	 * @since 2.7.0
	 */
	private static function GetKernelInstance()
	{
		if (!array_key_exists(static::PORTAL_ID, self::$aKernels))
		{
			self::$aKernels[static::PORTAL_ID] = new Kernel($_SERVER['APP_ENV'], (bool)$_SERVER['APP_DEBUG']);
			self::$aKernels[static::PORTAL_ID]->boot();
		}

		return self::$aKernels[static::PORTAL_ID];
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
}

