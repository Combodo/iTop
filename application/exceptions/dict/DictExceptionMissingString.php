<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class DictExceptionMissingString extends DictException
{
	public function __construct($sLanguageCode, $sStringCode)
	{
		$aContext = array();
		$aContext['language_code'] = $sLanguageCode;
		$aContext['string_code'] = $sStringCode;
		parent::__construct('Missing localized string', $aContext);
	}
}