<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Portal\Controller\ObjectController;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use ContextTag;
use MetaModel;
use PHPUnit\Exception;
use TriggerOnObjectCreate;

/**
 * Class TriggerTest
 *
 * @package Combodo\iTop\Test\UnitTest\Core
 *
 * @runTestsInSeparateProcesses
 */

class TriggerTest extends ItopDataTestCase
{
	const USE_TRANSACTION = false;


	protected function setUp()
	{
		parent::setUp();
	}

	public function testIsContextValid()
	{
		/** @var TriggerOnObjectCreate $oTrigger */
		$oTrigger = MetaModel::NewObject('TriggerOnObjectCreate');
		$oTrigger->Set('context', ContextTag::TAG_PORTAL.', '.ContextTag::TAG_CRON);
		$this->assertFalse($oTrigger->IsContextValid());
		ContextTag::AddContext(ContextTag::TAG_SETUP);
		$this->assertFalse($oTrigger->IsContextValid());
		ContextTag::AddContext(ContextTag::TAG_CRON);
		$this->assertTrue($oTrigger->IsContextValid());
	}

	public function testEnrichRaisedException_Trigger()
	{
		$oTrigger = MetaModel::NewObject('TriggerOnObjectCreate');
		try
		{
			try
			{
				MetaModel::NewObject('Toto');
			}
			catch (\Exception $e)
			{
				\utils::EnrichRaisedException($oTrigger, $e);
			}
			$this->assertTrue(false, "An exception should have been thrown");
		}
		catch(\CoreException $e1)
		{
			$this->assertEquals('CoreException', get_class($e1));
			$this->assertEquals('Unknown class \'Toto\' (<b title="Trigger">TriggerOnObjectCreate</b>::-1 ()<br/>)', $e1->getMessage());

			$fullStackTraceAsString = $e1->getFullStackTraceAsString();
			$this->assertContains("MetaModel::NewObject", $fullStackTraceAsString,"new enriched exception should contain root cause method: " . $fullStackTraceAsString);
		}
	}

	public function NoEnrichmentProvider()
	{
		return [
			[ null ],
			[ new NonCmdbAbstractObject() ],
		]	;
	}

	/**
	 * @param $oCmdbAbstract
	 * @dataProvider NoEnrichmentProvider
	 */
	public function testEnrichRaisedException_NoEnrichment($oCmdbAbstract)
	{
		try
		{
			try
			{
				MetaModel::NewObject('CoreException');
			}
			catch (\Exception $e)
			{
				\utils::EnrichRaisedException($oCmdbAbstract, $e);
			}
			$this->assertTrue(false, "An exception should have been thrown");
		}
		catch(\Exception $e1)
		{
			$this->assertEquals($e, $e1);
		}
	}
}

class NonCmdbAbstractObject{

}
