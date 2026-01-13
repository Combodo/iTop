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

namespace Combodo\iTop\Core;

/**
 * Class WebResponse
 *
 * @package Combodo\iTop\Core
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class WebResponse
{
	/** @var array $aHeaders */
	protected $aHeaders = array();
	/** @var string $sBody */
	protected $sBody = null;

	/**
	 * Constructor
	 */
	public function __construct()
	{
	}

	/**
	 * Set the headers as an associative array (header name => header value)
	 *
	 * @param array $aHeaders
	 *
	 * @return $this;
	 */
	public function SetHeaders(array $aHeaders)
	{
		$this->aHeaders = $aHeaders;
		return $this;
	}

	/**
	 * Return headers as an associative array
	 *
	 * @return array
	 */
	public function GetHeaders()
	{
		return $this->aHeaders;
	}

	/**
	 * Set the raw body of the response
	 *
	 * @param string $sBody
	 *
	 * @return $this
	 */
	public function SetBody(string $sBody)
	{
		$this->sBody = $sBody;
		return $this;
	}

	/**
	 * Return the raw body
	 *
	 * @return string
	 */
	public function GetBody()
	{
		return $this->sBody;
	}
}