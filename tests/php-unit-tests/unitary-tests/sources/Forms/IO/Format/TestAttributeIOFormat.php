<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\sources\Forms\IO\Format;

use Combodo\iTop\Forms\IO\Format\AttributeIOFormat;
use Combodo\iTop\Test\UnitTest\sources\Forms\AbstractFormsTest;
use Symfony\Component\Form\FormEvents;

class TestAttributeIOFormat extends AbstractFormsTest
{
	public function testAttributeIOIsAString()
	{
		$oInputIO = $this->GivenInput('test', AttributeIOFormat::class);
		$oInputIO->SetValue(FormEvents::POST_SUBMIT, 'name');

		$this->assertEquals('name', $oInputIO->GetValue());
	}
}
