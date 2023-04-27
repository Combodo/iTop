<?php
/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

class DictExceptionUnknownLanguage extends DictException
{
	public function __construct($sLanguageCode)
	{
		$aContext = array();
		$aContext['language_code'] = $sLanguageCode;
		parent::__construct('Unknown localization language', $aContext);
	}
}