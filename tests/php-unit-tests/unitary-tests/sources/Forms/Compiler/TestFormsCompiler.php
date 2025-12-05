<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Forms\Compiler\FormsCompiler;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class TestFormsCompiler extends ItopDataTestCase
{
	public function testCompileFormFromXML()
	{
		$sXMLContent = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<node id="DashletTest" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree">
    <nodes>
        <node id="title" xsi:type="Combodo-Property">
            <label>UI:DashletTest:Prop-Title</label>
            <value-type xsi:type="Combodo-ValueTypeLabel">
            </value-type>
        </node>
        <node id="class" xsi:type="Combodo-Property">
            <label>UI:DashletTest:Prop-Class</label>
            <value-type xsi:type="Combodo-ValueTypeClass">
            </value-type>
        </node>
    </nodes>
</node>
XML;

		$sExpectedPHP = <<<PHP
class FormForDashletTest extends Combodo\iTop\Forms\Block\Base\FormBlock
{
	protected function BuildForm(): void
	{
		\$this->Add('title', 'Combodo\iTop\Forms\Block\DataModel\LabelFormBlock', [
			'label' => 'UI:DashletTest:Prop-Title',
		]);

		\$this->Add('class', 'Combodo\iTop\Forms\Block\Base\TextFormBlock', [
			'label' => 'UI:DashletTest:Prop-Class',
		]);
	}
}
PHP;

		$sProducedPHP = FormsCompiler::GetInstance()->CompileFormFromXML($sXMLContent);
		eval($sProducedPHP);
		$this->assertEquals($sExpectedPHP, $sProducedPHP);

	}
}
