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

namespace Combodo\iTop\Portal\Helper;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * RequestManipulatorHelper class
 *
 * Handle basic requests manipulation.
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since  2.5.1
 */
class RequestManipulatorHelper
{
	/** @var \Symfony\Component\HttpFoundation\RequestStack $oRequestStack */
	protected $oRequestStack;

	/**
	 * RequestManipulatorHelper constructor.
	 *
	 * @param \Symfony\Component\HttpFoundation\RequestStack $oRequestStack
	 */
	public function __construct(RequestStack $oRequestStack)
	{
		$this->oRequestStack = $oRequestStack;
	}

	/**
	 * @return \Symfony\Component\HttpFoundation\Request
	 */
	public function GetCurrentRequest()
	{
		return $this->oRequestStack->getCurrentRequest();
	}

	/**
	 * Returns if the request has a $sKey parameter.
	 * This looks in the GET arguments first, then PATH and finally the POST data.
	 *
	 * @param string $sKey
	 *
	 * @return bool
	 */
	public function HasParam($sKey)
	{
		if ($this->GetCurrentRequest()->query->has($sKey))
		{
			return true;
		}

		if ($this->GetCurrentRequest()->attributes->has($sKey))
		{
			return true;
		}

		if ($this->GetCurrentRequest()->request->has($sKey))
		{
			return true;
		}

		return false;
	}

	/**
	 * Returns the $sKey parameter from the request filtered with $iFilter.
	 * This looks in the GET arguments first, then the PATH and finally the POST data.
	 *
	 * Note: It is inspired by the \Symfony\Component\HttpFoundation\ParameterBag::filter() function and was necessary as we sometimes have parameters that can be either in the GET/PATH/POST arguments and need to be filtered. Silex only offer the possibility to filter parameter from a single ParameterBag, so we created this helper.
	 *
	 * @param string $sKey
	 * @param mixed  $default
	 * @param int    $iFilter Default is FILTER_SANITIZE_SPECIAL_CHARS
     * @param int    $aFilterOptions @since 3.2.0 - N°6934 - Symfony 6.4 - upgrade Symfony bundles to 6.4
	 *
	 * @return mixed|null
	 *
	 * @since 2.5.1
	 */
	public function ReadParam($sKey, $default = null, $iFilter = FILTER_SANITIZE_SPECIAL_CHARS, $aFilterOptions = [])
	{
		if ($this->GetCurrentRequest()->query->has($sKey))
		{
			return $this->GetCurrentRequest()->query->filter($sKey, $default, $iFilter, $aFilterOptions);
		}

		if ($this->GetCurrentRequest()->attributes->has($sKey))
		{
			return $this->GetCurrentRequest()->attributes->filter($sKey, $default, $iFilter, $aFilterOptions);
		}

		if ($this->GetCurrentRequest()->request->has($sKey))
		{
			return $this->GetCurrentRequest()->request->filter($sKey, $default, $iFilter, $aFilterOptions);
		}

		return $default;
	}

}
