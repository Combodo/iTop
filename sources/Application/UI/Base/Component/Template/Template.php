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

namespace Combodo\iTop\Application\UI\Base\Component\Template;

use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;

/**
 * Class Template
 *
 * @author Benjamin Dalsass<benjamin.dalsass@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Component\Template
 * @since 3.1.0
 */
class Template extends UIContentBlock
{
	// Overloaded constants
	public const BLOCK_CODE                     = 'ibo-template';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/template/layout';

}
