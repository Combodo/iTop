<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use ContextTag;
use Exception;
use IssueLog;
use MetaModel;
use Person;
use TriggerOnObjectCreate;

/**
 * Class TriggerTest
 *
 * @package Combodo\iTop\Test\UnitTest\Core
 */
class TriggerTest extends ItopDataTestCase
{
	const USE_TRANSACTION = false;


	protected function setUp(): void
	{
		parent::setUp();
	}

	public function testIsContextValid()
	{
		/** @var TriggerOnObjectCreate $oTrigger */
		$oTrigger = MetaModel::NewObject('TriggerOnObjectCreate');
		$oTrigger->Set('context', ContextTag::TAG_PORTAL.', '.ContextTag::TAG_CRON);
		$this->assertFalse($oTrigger->IsContextValid());
		$oC1 = new ContextTag(ContextTag::TAG_SETUP);
		$this->assertFalse($oTrigger->IsContextValid());
		$oC2 = new ContextTag(ContextTag::TAG_CRON);
		$this->assertTrue($oTrigger->IsContextValid());
	}

	public function testEnrichRaisedException_Trigger()
	{
		$oTrigger = MetaModel::NewObject('TriggerOnObjectCreate');
		try {
			try {
				MetaModel::NewObject('Toto');
			}
			catch (\Exception $e) {
				\utils::EnrichRaisedException($oTrigger, $e);
			}
			$this->assertTrue(false, "An exception should have been thrown");
		}
		catch (\CoreException $e1) {
			$this->assertEquals('CoreException', get_class($e1));
			$this->assertStringStartsWith('Unknown class \'Toto\' (<b title="Trigger">TriggerOnObjectCreate</b>::-', $e1->getMessage());

			$fullStackTraceAsString = $e1->getFullStackTraceAsString();
			$this->assertStringContainsString("MetaModel::NewObject", $fullStackTraceAsString,"new enriched exception should contain root cause method: " . $fullStackTraceAsString);
		}
	}

	public function NoEnrichmentProvider()
	{
		return [
			[null],
			[new NonCmdbAbstractObject()],
		]	;
	}

	/**
	 * @param $oCmdbAbstract
	 * @dataProvider NoEnrichmentProvider
	 */
	public function testEnrichRaisedException_NoEnrichment($oCmdbAbstract)
	{
		try {
			try {
				MetaModel::NewObject('CoreException');
			}
			catch (\Exception $e) {
				\utils::EnrichRaisedException($oCmdbAbstract, $e);
			}
			$this->assertTrue(false, "An exception should have been thrown");
		}
		catch (\Exception $e1) {
			$this->assertEquals($e, $e1);
		}
	}

	public function testLogException()
	{
		$sTestLogPath = APPROOT.'log/TriggerTest__testLogException.log';
		IssueLog::Enable($sTestLogPath);

		try {
			$oPerson1 = MetaModel::GetObjectByName(Person::class, 'Claude Monet');
			$sExceptionMessage = 'My test exception message';
			$oException = new Exception($sExceptionMessage);

			/** @var TriggerOnObjectCreate $oTrigger */
			$oTrigger = MetaModel::NewObject(TriggerOnObjectCreate::class, [
				'description' => 'my trigger description',
			]);
			$oTrigger->DBWrite();
			$oTrigger->LogException($oException, $oPerson1);

			$sTestLogFileContent = file_get_contents($sTestLogPath);

			$this->assertStringContainsString('A trigger did throw an exception', $sTestLogFileContent);

			$this->assertStringContainsString($oPerson1->GetKey(), $sTestLogFileContent);
			/** @noinspection GetClassUsageInspection */
			$this->assertStringContainsString(get_class($oPerson1), $sTestLogFileContent);
			$this->assertStringContainsString($oPerson1->GetRawName(), $sTestLogFileContent);

			$this->assertStringContainsString($sExceptionMessage, $sTestLogFileContent);
		}
		finally {
			IssueLog::Enable(APPROOT.'log/error.log');
		}
	}
}

class NonCmdbAbstractObject{

}
