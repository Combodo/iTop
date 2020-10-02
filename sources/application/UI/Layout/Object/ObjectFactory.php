<?php
/*
 * @copyright   Copyright (C) 2010-2020 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Layout\Object;


use Combodo\iTop\Application\UI\Layout\Object\ObjectDetails;
use DBObject;

/**
 * Class ObjectFactory
 *
 * @internal
 * @author  Guillaume Lajarige <guillaume.lajarige@combodo.com>
 * @package Combodo\iTop\Application\UI\Layout\Object
 * @since   2.8.0
 */
class ObjectFactory {
	/**
	 * Make a standard object details layout.
	 *
	 * @return \Combodo\iTop\Application\UI\Layout\Object\ObjectDetails
	 */
	public static function MakeDetails(DBObject $oObject) {
		return new ObjectDetails();
	}
}