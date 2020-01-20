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
}
