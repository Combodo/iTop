<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Layout\Object;


use cmdbAbstractObject;
use DBObject;

/**
 * Class ObjectFactory
 *
 * @internal
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Layout\Object
 * @since   3.0.0
 */
class ObjectFactory
{
	/**
	 * Make a standard object details layout.
	 *
	 * @param \DBObject   $oObject
	 * @param string|null $sMode
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\Object\ObjectDetails
	 * @throws \CoreException
	 */
	public static function MakeDetails(DBObject $oObject, ?string $sMode = cmdbAbstractObject::DEFAULT_DISPLAY_MODE)
	{
		$oObjectDetails = new ObjectDetails($oObject, $sMode);
		$oObjectDetails->SetIsHeaderVisibleOnScroll(true);

		return $oObjectDetails;
	}
}