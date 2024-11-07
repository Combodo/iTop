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
use DBObject;
use InlineImage;
use AttributeText;

/**
 * Description of TextAreaField
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package \Combodo\iTop\Form\Field
 * @since 2.3.0
 */
class TextAreaField extends TextField
{
	/** @var string */
	const ENUM_FORMAT_TEXT = 'text';
	/** @var string */
	const ENUM_FORMAT_HTML = 'html';
	/** @var string */
	const DEFAULT_FORMAT = 'html';

	/** @var string */
	protected $sFormat;
	protected $oObject;
	/** @var string|null */
	protected $sTransactionId;

	/**
	 * TextAreaField constructor.
	 *
	 * @param string         $sId
	 * @param \Closure|null  $onFinalizeCallback
	 * @param \DBObject|null $oObject
	 */
	public function __construct(string $sId, Closure $onFinalizeCallback = null, DBObject $oObject = null)
	{
		parent::__construct($sId, $onFinalizeCallback);
		$this->sFormat = static::DEFAULT_FORMAT;
		$this->oObject = $oObject;
		$this->sTransactionId = null;
	}

	/**
	 *
	 * @return string
	 */
	public function GetFormat()
	{
		return $this->sFormat;
	}

	/**
	 *
	 * @param string $sFormat
	 * @return \Combodo\iTop\Form\Field\TextAreaField
	 */
	public function SetFormat(string $sFormat)
	{
		$this->sFormat = $sFormat;
		return $this;
	}

	/**
	 *
	 * @return DBObject
	 */
	public function GetObject()
	{
		return $this->oObject;
	}

	/**
	 *
	 * @param DBObject $oObject
	 * @return \Combodo\iTop\Form\Field\TextAreaField
	 */
	public function SetObject(DBObject $oObject)
	{
		$this->oObject = $oObject;
		return $this;
	}

	/**
	 * Returns the transaction id for the field. This is usally used/setted when using a html format that allows upload of files/images
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
	 * @return \Combodo\iTop\Form\Field\TextAreaField
	 */
	public function SetTransactionId(string $sTransactionId)
	{
		$this->sTransactionId = $sTransactionId;
		return $this;
	}
	
	public function GetDisplayValue()
	{
		if ($this->GetFormat() == TextAreaField::ENUM_FORMAT_TEXT)
		{
		    $sValue = \Str::pure2html($this->GetCurrentValue());
			$sValue = AttributeText::RenderWikiHtml($sValue);
			return "<div>".str_replace("\n", "<br>\n", $sValue).'</div>';			
		}
		else
		{
			$sValue = AttributeText::RenderWikiHtml($this->GetCurrentValue(), true /* wiki only */);
			return "<div class=\"HTML\">".InlineImage::FixUrls($sValue).'</div>';
		}
	}

}
