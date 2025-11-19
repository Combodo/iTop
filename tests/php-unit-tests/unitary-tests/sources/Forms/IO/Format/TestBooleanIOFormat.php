<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\sources\Forms\IO\Format;

use Combodo\iTop\Forms\IO\Format\BooleanIOFormat;
use Combodo\iTop\Test\UnitTest\sources\Forms\AbstractFormsTest;
use Symfony\Component\Form\FormEvents;

class TestBooleanIOFormat extends AbstractFormsTest
{
	public function testBooleanIOFormatIsABoolean()
	{
		$oInputIO = $this->GivenInput('test', BooleanIOFormat::class);

		$oInputIO->SetValue(FormEvents::POST_SUBMIT, 'true');

		$this->assertEquals(true, $oInputIO->GetValue());
	}
}
