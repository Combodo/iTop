<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use AttributeDateTime;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use DateTime;
use MetaModel;

class AttributeSubItemTest extends ItopDataTestCase
{
	public const CREATE_TEST_ORG = true;

	/**
	 * @param string $sAttCode
	 * @param string $sVerb
	 * @param string $sExpectedValue
	 *
	 * @return void
	 */
	public function testGetForTemplate()
	{
		$aUserRequestCustomParams = [
			'title' => "Test DisplayStopwatch",
		];
		$oUserRequest = $this->CreateUserRequest(456, $aUserRequestCustomParams);

		$iStartDate =  time() - 200;
		$oStopwatch = $oUserRequest->Get('ttr');
		$oStopwatch->DefineThreshold(100, $iStartDate);
		$oUserRequest->Set('ttr', $oStopwatch);

		$sValue = $oUserRequest->Get('ttr_escalation_deadline');
		$oAttDef = MetaModel::GetAttributeDef(get_class($oUserRequest), 'ttr_escalation_deadline');

		self::assertEquals('Missed by 3 min', $oAttDef->GetForTemplate($sValue, 'html', $oUserRequest));
		$oDateTime = new DateTime();
		$oDateTime->setTimestamp($iStartDate);
		$sDate = $oDateTime->format(AttributeDateTime::GetFormat());
		self::assertEquals($sDate, $oAttDef->GetForTemplate($sValue, 'label', $oUserRequest), 'label() should render the date in the format specified in the configuration file, in parameter "date_and_time_format"');
		self::assertEquals('Missed by 3 min', $oAttDef->GetForTemplate($sValue, 'text', $oUserRequest), 'text() should render the deadline as specified in the configuration file, in parameter "deadline_format", and depending on the user language');
		self::assertEquals($iStartDate, $oAttDef->GetForTemplate($sValue, '', $oUserRequest));
	}
}
