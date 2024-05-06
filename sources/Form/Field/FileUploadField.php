<?php

// Copyright (C) 2010-2024 Combodo SAS
//
//   This file is part of iTop.
//
//   iTop is free software; you can redistribute it and/or modify	
//   it under the terms of the GNU Affero General Public License as published by
//   the Free Software Foundation, either version 3 of the License, or
//   (at your option) any later version.
//
//   iTop is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU Affero General Public License for more details.
//
//   You should have received a copy of the GNU Affero General Public License
//   along with iTop. If not, see <http://www.gnu.org/licenses/>

namespace Combodo\iTop\Form\Field;

use Closure;

/**
 * Description of FileUploadField
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class FileUploadField extends AbstractSimpleField
{
	/** @var bool DEFAULT_ALLOW_DELETE */
	const DEFAULT_ALLOW_DELETE = true;

	/** @var string|null $sTransactionId */
	protected $sTransactionId;
	/** @var \DBObject|null $oObject */
	protected $oObject;
	/** @var string|null $sUploadEndpoint */
	protected $sUploadEndpoint;
	/** @var string|null $sDownloadEndpoint */
	protected $sDownloadEndpoint;
	/** @var bool $bAllowDelete */
	protected $bAllowDelete;

	/**
	 * @inheritDoc
	 */
	public function __construct(string $sId, Closure $onFinalizeCallback = null)
	{
		$this->sTransactionId = null;
		$this->oObject = null;
		$this->sUploadEndpoint = null;
		$this->sDownloadEndpoint = null;
		$this->bAllowDelete = static::DEFAULT_ALLOW_DELETE;

		parent::__construct($sId, $onFinalizeCallback);
	}

	/**
	 * Returns the transaction id for the field.
	 *
	 * @return string
	 */
	public function GetTransactionId()
	{
		return $this->sTransactionId;
	}

	/**
	 *
	 * @param string $sTransactionId
	 * @return $this
	 */
	public function SetTransactionId(string $sTransactionId)
	{
		$this->sTransactionId = $sTransactionId;
		return $this;
	}

	/**
	 * @return \DBObject|null
	 */
	public function GetObject()
	{
		return $this->oObject;
	}

	/**
	 * @param $oObject
	 *
	 * @return $this
	 */
	public function SetObject($oObject)
	{
		$this->oObject = $oObject;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function GetUploadEndpoint()
	{
		return $this->sUploadEndpoint;
	}

	/**
	 * @param $sUploadEndpoint
	 *
	 * @return $this
	 */
	public function SetUploadEndpoint(string $sUploadEndpoint)
	{
		$this->sUploadEndpoint = $sUploadEndpoint;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function GetDownloadEndpoint()
	{
		return $this->sDownloadEndpoint;
	}

	/**
	 * @param $sDownloadEndpoint
	 *
	 * @return $this
	 */
	public function SetDownloadEndpoint(string $sDownloadEndpoint)
	{
		$this->sDownloadEndpoint = $sDownloadEndpoint;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function GetAllowDelete()
	{
		return $this->bAllowDelete;
	}

	/**
	 * @param bool $bAllowDelete
	 *
	 * @return $this
	 */
	public function SetAllowDelete(bool $bAllowDelete)
	{
		$this->bAllowDelete = (boolean) $bAllowDelete;
		return $this;
	}

}
