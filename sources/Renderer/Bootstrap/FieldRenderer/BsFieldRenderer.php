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

namespace Combodo\iTop\Renderer\Bootstrap\FieldRenderer;

use Combodo\iTop\Renderer\FieldRenderer;
use Combodo\iTop\Renderer\RenderingOutput;
use utils;

/**
 * Description of BsFieldRenderer
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @since 2.7.0
 */
class BsFieldRenderer extends FieldRenderer
{

	/**
	 * @inheritDoc
	 */
	public function Render()
	{
		$oOutput = parent::Render();
		$oOutput->AddCssClass('form_field_'.$this->oField->GetDisplayMode());

		return $oOutput;
	}
}
