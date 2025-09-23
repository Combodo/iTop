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

namespace Combodo\iTop\Portal\VariableAccessor;


use Combodo\iTop\Portal\EventListener\UserProvider;
use Exception;
use MetaModel;
use Symfony\Component\DependencyInjection\ContainerInterface;
use UserRights;

/**
 * Class CombodoCurrentContactPhotoUrl
 *
 * @package Combodo\iTop\Portal\VariableAccessor
 * @since   2.7.0
 * @author  Bruno Da Silva <bruno.dasilva@combodo.com>
 */
class CombodoCurrentContactPhotoUrl
{
	/** @var string $sCombodoPortalBaseAbsoluteUrl */
	private $sCombodoPortalBaseAbsoluteUrl;
	/** @var string|null $sContactPhotoUrl */
	private $sContactPhotoUrl;
	/** @var \Symfony\Component\DependencyInjection\ContainerInterface */
	private $oContainer;
	/** @var UserProvider $oUserProvider */
	private $oUserProvider;

	/**
	 * CombodoCurrentContactPhotoUrl constructor.
	 *
	 * @param \Symfony\Component\DependencyInjection\ContainerInterface $oContainer
	 * @param string $sCombodoPortalBaseAbsoluteUrl
	 */
	public function __construct(UserProvider $userProvider, ContainerInterface $oContainer, $sCombodoPortalBaseAbsoluteUrl)
	{
		$this->oUserProvider = $userProvider;
		$this->oContainer = $oContainer;
		$this->sCombodoPortalBaseAbsoluteUrl = $sCombodoPortalBaseAbsoluteUrl;
		$this->sContactPhotoUrl = null;
	}

	/**
	 * @return string
	 * @throws \CoreException
	 */
	public function __toString()
	{
		if ($this->sContactPhotoUrl === null) {
			$this->sContactPhotoUrl = $this->ComputeContactPhotoUrl();
		}

		return $this->sContactPhotoUrl;
	}

	/**
	 * @return string
	 *
	 * @throws \CoreException
	 * @throws \Exception
	 */
	private function ComputeContactPhotoUrl()
	{
		// Contact
		$sContactPhotoUrl = "{$this->sCombodoPortalBaseAbsoluteUrl}img/user-profile-default-256px.png";
		// - Checking if we can load the contact
		try {
			/** @var \cmdbAbstractObject $oContact */
			$oContact = UserRights::GetContactObject();
		}
		catch (Exception $e) {
			$oUser = $this->oUserProvider->getCurrentUser();
			$oAllowedOrgSet = $oUser->Get('allowed_org_list');
			if ($oAllowedOrgSet->Count() > 0) {
				throw new Exception('Could not load contact related to connected user. (Tip: Make sure the contact\'s organization is among the user\'s allowed organizations)');
			} else {
				throw new Exception('Could not load contact related to connected user.');
			}
		}
		// - Retrieving picture
		if ($oContact) {
			$sPictureAttCode = 'picture';
			if (MetaModel::IsValidAttCode(get_class($oContact), $sPictureAttCode)) {
				/** @var \ormDocument $oImage */
				$oImage = $oContact->Get($sPictureAttCode);
				if (is_object($oImage) && !$oImage->IsEmpty()) {
					// TODO: This should be changed when refactoring the ormDocument GetDisplayUrl() and GetDownloadUrl() in iTop 3.0
					$sContactPhotoUrl = $this->oContainer->get('url_generator')->generate('p_object_document_display', [
						'sObjectClass' => get_class($oContact),
						'sObjectId'    => $oContact->GetKey(),
						'sObjectField' => $sPictureAttCode,
						'cache'        => 86400,
						's'            => $oImage->GetSignature(),
					]);
				} else {
					$sContactPhotoUrl = MetaModel::GetAttributeDef(get_class($oContact), $sPictureAttCode)->Get('default_image');
				}
			}
		}

		return $sContactPhotoUrl;
	}
}