<?php

/**
 * Copyright (C) 2013-2026 Combodo SAS
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

namespace Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityPanelAction;

/**
 * Class ActivityPanelActionSeparator
 *
 * @package Combodo\iTop\Application\UI\Base\Layout\ActivityPanel\ActivityPanelAction
 * @internal
 * @since 3.3.0
 */
class ActivityPanelActionSeparator extends ActivityPanelAction
{
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/activity-panel/activity-action/activity-action-separator';
	public const DEFAULT_JS_TEMPLATE_REL_PATH = null;

	/**
	 * @param string $sUid
	 */
	public function __construct(string $sUid)
	{
		parent::__construct('', '', null, $sUid);
	}
}
