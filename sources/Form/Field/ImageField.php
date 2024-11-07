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

use utils;
use Dict;

/**
 * Description of ImageField
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 */
class ImageField extends BlobField
{
	/**
	 * @inheritDoc
	 */
	public function GetDisplayValue()
	{
		if ($this->currentValue->IsEmpty())
		{
			$sValue = Dict::S('Portal:File:None');
		}
		else
		{
			$sFilename = $this->currentValue->GetFileName();
			$iSize = utils::BytesToFriendlyFormat(utils::StrLen($this->currentValue->GetData()));
			$sOpenLink = $this->GetDisplayUrl();
			$sDownloadLink = $this->GetDownloadUrl();

			$sValue = Dict::Format('Portal:File:DisplayInfo+', $sFilename, $iSize, $sOpenLink, $sDownloadLink);
		}

		return $sValue;
	}

}
