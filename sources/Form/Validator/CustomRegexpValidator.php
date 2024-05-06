<?php
/*
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Form\Validator;

/**
 * @since 3.1.0 N°6414
 */
class CustomRegexpValidator extends AbstractRegexpValidator
{
	public const VALIDATOR_NAME = 'custom_regexp';

	public function __construct(string $sRegExp, ?string $sErrorMessage = null)
	{
		parent::__construct($sErrorMessage);

		$this->sRegExp = $sRegExp; // must be done after parent constructor call !
	}
}