<?php

namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use ContextTag;
use MetaModel;
use TriggerOnObjectCreate;

/**
 * Class TriggerTest
 *
 * @package Combodo\iTop\Test\UnitTest\Core
 *
 * @runTestsInSeparateProcesses
 */

define('APPROOT', dirname(__FILE__).'/../../');
define('APPCONF', APPROOT.'conf/');

class TriggerTest extends ItopDataTestCase
{
	const USE_TRANSACTION = false;

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
		$sStackTrace = "";
		try
		{
			try
			{
				MetaModel::NewObject('CoreException');
			}
			catch (\Exception $e)
			{
				$sStackTrace = $e->getTraceAsString();
				\utils::EnrichRaisedException($oTrigger, $e);
			}
			$this->assertTrue(false, "An exception should have been thrown");
		}
		catch(\Exception $e1)
		{
			$this->assertEquals('CoreException', get_class($e1));
			$this->assertEquals('Unknown class \'CoreException\' (TriggerOnObjectCreate::-1)', $e1->getMessage());
			$this->assertEquals($sStackTrace, $e1->getTraceAsString());
		}

	}
}
