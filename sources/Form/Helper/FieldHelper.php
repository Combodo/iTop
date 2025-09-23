<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Form\Helper;

use BinaryExpression;
use DBObjectSet;
use DBSearch;
use FieldExpression;
use ScalarExpression;

/**
 * Utility methods for {@see \Combodo\iTop\Form\Field\Field} classes
 *
 * @since 3.1.0 N°6414
 */
class FieldHelper {
	/**
	 * @since 3.1.0 N°6414 Method creation to factorize between uses in {@see \Combodo\iTop\Form\Field\Field} and {@see \Combodo\iTop\Form\Validator\SelectObjectValidator}
	 */
	public static function GetObjectsSetFromSearchAndCurrentValueId(DBSearch $oSearch, string $sCurrentValueId) {
		$oSearchForExistingCurrentValue = $oSearch->DeepClone();
		$oCheckIdAgainstCurrentValueExpression = new BinaryExpression(
			new FieldExpression('id', $oSearchForExistingCurrentValue->GetClassAlias()),
			'=',
			new ScalarExpression($sCurrentValueId)
		);
		$oSearchForExistingCurrentValue->AddConditionExpression($oCheckIdAgainstCurrentValueExpression);

		return new DBObjectSet($oSearchForExistingCurrentValue);
	}

}