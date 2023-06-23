<?php

/**
 * @since 3.0.0 N°4515
 * @used-by \Combodo\iTop\Test\UnitTest\Core\AttributeURLTest
 */
class AttributeURLDefaultPattern extends AttributeURL {
	public function GetValidationPattern()
	{
		/** @noinspection OneTimeUseVariablesInspection */
		$oConfig = utils::GetConfig();
		return '^'.$oConfig->GetDefault('url_validation_pattern').'$';
	}
}
