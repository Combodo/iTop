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

use Str;
use Closure;
use utils;

/**
 * Description of UrlField
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class UrlField extends StringField
{
    /** @var string */
	const DEFAULT_TARGET = '_blank';

    /** @var string */
	protected $sTarget;

    /**
     * @inheritDoc
     */
    public function __construct(string $sId, Closure $onFinalizeCallback = null)
    {
        parent::__construct($sId, $onFinalizeCallback);

        $this->sTarget = static::DEFAULT_TARGET;
    }

    public function SetTarget($sTarget)
    {
        $this->sTarget = $sTarget;

        return $this;
    }

	/**
	 * @inheritDoc
	 */
    public function GetDisplayValue()
    {
        $sLabel = Str::pure2html($this->currentValue);
        if (utils::StrLen($sLabel) > 128)
        {
            // Truncate the length to 128 characters, by removing the middle
            $sLabel = substr($sLabel, 0, 100).'.....'.substr($sLabel, -20);
        }

        return "<a target=\"$this->sTarget\" href=\"$this->currentValue\">$sLabel</a>";
    }
}
