<?php

// Copyright (C) 2010-2016 Combodo SARL
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

use \Combodo\iTop\Form\Field\Field;

/**
 * Description of FileUploadField
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class FileUploadField extends Field
{
	const DEFAULT_ALLOW_DELETE = true;

	protected $sTransactionId;
	protected $oObject;
	protected $sUploadEndpoint;
	protected $sDownloadEndpoint;
	protected $bAllowDelete;

	public function __construct($sId, \Closure $onFinalizeCallback = null)
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
	 * @return \Combodo\iTop\Form\Field\FileUploadField
	 */
	public function SetTransactionId($sTransactionId)
	{
		$this->sTransactionId = $sTransactionId;
		return $this;
	}

	public function GetObject()
	{
		return $this->oObject;
	}

	public function SetObject($oObject)
	{
		$this->oObject = $oObject;
		return $this;
	}

	public function GetUploadEndpoint()
	{
		return $this->sUploadEndpoint;
	}

	public function SetUploadEndpoint($sUploadEndpoint)
	{
		$this->sUploadEndpoint = $sUploadEndpoint;
		return $this;
	}

	public function GetDownloadEndpoint()
	{
		return $this->sDownloadEndpoint;
	}

	public function SetDownloadEndpoint($sDownloadEndpoint)
	{
		$this->sDownloadEndpoint = $sDownloadEndpoint;
		return $this;
	}

	public function GetAllowDelete()
	{
		return $this->bAllowDelete;
	}

	public function SetAllowDelete($bAllowDelete)
	{
		$this->bAllowDelete = (boolean) $bAllowDelete;
		return $this;
	}

}
