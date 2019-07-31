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
 */


namespace Combodo\iTop\Form\Field;

use Closure;

/**
 * Fields pointing to a remote object
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since  2.7.0
 */
abstract class RemoteObjectField extends Field
{
	/** @var bool DEFAULT_IS_REMOTE_OBJECT_ACCESSIBLE */
	const DEFAULT_IS_REMOTE_OBJECT_ACCESSIBLE = true;

	/** @var boolean $bIsRemoteObjectAccessible */
	protected $bIsRemoteObjectAccessible;

	/**
	 * @inheritDoc
	 */
	public function __construct($sId, Closure $onFinalizeCallback = null)
	{
		parent::__construct($sId, $onFinalizeCallback);
		$this->bIsRemoteObjectAccessible = static::DEFAULT_IS_REMOTE_OBJECT_ACCESSIBLE;
	}

	/**
	 * Return true if the remote object pointed by this field is accessible
	 *
	 * @return boolean
	 */
	public function GetRemoteObjectAccessible()
	{
		return $this->bIsRemoteObjectAccessible;
	}

	/**
	 * @param boolean $bIsRemoteObjectAccessible
	 */
	public function SetRemoteObjectAccessible($bIsRemoteObjectAccessible)
	{
		$this->bIsRemoteObjectAccessible = $bIsRemoteObjectAccessible;
	}
}