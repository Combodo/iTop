<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Core\AttributeDefinition;

use DBObject;

/**
 * Base class for all kind of DB attributes, with the exception of external keys
 *
 * @package     iTopORM
 */
class AttributeDBField extends AttributeDBFieldVoid
{
	public static function ListExpectedParams()
	{
		return array_merge(parent::ListExpectedParams(), array("default_value", "is_null_allowed"));
	}

	public function GetDefaultValue(DBObject $oHostObject = null)
	{
		return $this->MakeRealValue($this->Get("default_value"), $oHostObject);
	}

	public function IsNullAllowed()
	{
		return $this->Get("is_null_allowed");
	}
}