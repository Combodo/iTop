<?php
/*
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Layout\Object;


use DBObject;

/**
 * Class ObjectFactory
 *
 * @internal
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Base\Layout\Object
 * @since   3.0.0
 */
class ObjectFactory {
	/**
	 * Make a standard object details layout.
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Layout\Object\ObjectDetails
	 */
	public static function MakeDetails(DBObject $oObject) {
		return new ObjectDetails($oObject);
	}
}