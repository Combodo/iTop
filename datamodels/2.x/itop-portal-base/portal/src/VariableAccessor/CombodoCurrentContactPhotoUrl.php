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

/**
 * Created by Bruno DA SILVA, working for Combodo
 * Date: 28/02/19
 * Time: 16:59
 */

namespace Combodo\iTop\Portal\VariableAccessor;


use Exception;
use MetaModel;
use User;
use UserRights;

class CombodoCurrentContactPhotoUrl
{
	/**
	 * @var \User
	 */
	private $oUser;
	private $sCombodoPortalInstanceAbsoluteUrl;
	private $sContactPhotoUrl;

	public function __construct(User $oUser, $sCombodoPortalInstanceAbsoluteUrl)
	{
		$this->oUser = $oUser;
		$this->sCombodoPortalInstanceAbsoluteUrl = $sCombodoPortalInstanceAbsoluteUrl;
		$this->sContactPhotoUrl = null;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		if($this->sContactPhotoUrl === null)
		{
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
		$sContactPhotoUrl = "{$this->sCombodoPortalInstanceAbsoluteUrl}img/user-profile-default-256px.png";
		// - Checking if we can load the contact
		try
		{
			$oContact = UserRights::GetContactObject();
		}
		catch (Exception $e)
		{
			$oAllowedOrgSet = $this->oUser->Get('allowed_org_list');
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

		return $sContactPhotoUrl;
	}
}