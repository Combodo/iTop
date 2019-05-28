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
 * Date: 29/01/19
 * Time: 15:04
 */

namespace Combodo\iTop\Portal\Security;


class ItopUser
{

    private $oUser;
    private $sContactPhotoUrl;

    public function setUser($oUser)
    {
        $this->oUser = $oUser;
    }

    public function setContactPhotoUrl($sContactPhotoUrl)
    {
        $this->sContactPhotoUrl = $sContactPhotoUrl;
    }

	/**
	 * @return mixed
	 */
	public function getUser()
	{
		return $this->oUser;
	}

	/**
	 * @return mixed
	 */
	public function getContactPhotoUrl()
	{
		return $this->sContactPhotoUrl;
	}
}