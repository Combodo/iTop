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
 *
 *
 */

use Combodo\iTop\Portal\UrlMaker\AbstractPortalUrlMaker;

// Global autoloader (portal autoloader is already required through itop-portal-base/module.itop-portal-base.php)
require_once APPROOT.'/lib/autoload.php';

/**
 * iTopPortalEditUrlMaker
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @author Bruno Da Silva <bruno.dasilva@combodo.com>
 * @since  2.3.0
 */
class iTopPortalEditUrlMaker extends AbstractPortalUrlMaker
{
	/** @var string PORTAL_ID */
	const PORTAL_ID = 'itop-portal';
}

/**
 * Hyperlinks to the "view" of the object (vs edition)
 *
 * @author Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @author Bruno Da Silva <bruno.dasilva@combodo.com>
 * @since  2.3.0
 */
class iTopPortalViewUrlMaker extends iTopPortalEditUrlMaker
{
	/**
	 * @inheritDoc
	 */
	public static function MakeObjectURL($sClass, $iId)
	{
		return static::PrepareObjectURL($sClass, $iId, 'view');
	}

}

// Default portal hyperlink (for notifications) is the edit hyperlink
DBObject::RegisterURLMakerClass('portal', 'iTopPortalEditUrlMaker');
DBObject::RegisterURLMakerClass('itop-portal', 'iTopPortalEditUrlMaker');
DBObject::RegisterURLMakerClass('itop-portal-edit', 'iTopPortalEditUrlMaker');
DBObject::RegisterURLMakerClass('itop-portal-view', 'iTopPortalViewUrlMaker');

