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
use utils;

/**
 * Description of PhoneField
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class PhoneField extends StringField
{
	/**
	 * @inheritDoc
	 * @throws \ConfigException
	 * @throws \CoreException
	 */
    public function GetDisplayValue()
    {
        $sLabel = Str::pure2html($this->currentValue);
        if (utils::StrLen($sLabel) > 128)
        {
            // Truncate the length to 128 characters, by removing the middle
            $sLabel = substr($sLabel, 0, 100).'.....'.substr($sLabel, -20);
        }

        $sUrlDecorationClass = utils::GetConfig()->Get('phone_number_decoration_class');

        return "<a class=\"tel\" href=\"tel:$this->currentValue\"><span class=\"form_field_decoration text_decoration $sUrlDecorationClass\"></span>$sLabel</a>";
    }
}
