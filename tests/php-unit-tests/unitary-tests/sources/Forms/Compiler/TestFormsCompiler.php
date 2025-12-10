<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Forms\Block\Base\CollectionBlock;
use Combodo\iTop\Forms\Compiler\FormsCompiler;
use Combodo\iTop\ItopSdkFormDemonstrator\Form\Block\Person\PersonFormBlock;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class TestFormsCompiler extends ItopDataTestCase
{
	/**
	 * @dataProvider CompileFormFromXMLProvider
	 * @param string $sXMLContent
	 * @param string $sExpectedPHP
	 *
	 * @return void
	 * @throws \Combodo\iTop\Forms\Compiler\FormsCompilerException
	 * @throws \Combodo\iTop\PropertyTree\PropertyTreeException
	 */
	public function testCompileFormFromXML(string $sXMLContent, string $sExpectedPHP, string $sMessage = '')
	{
		$sProducedPHP = FormsCompiler::GetInstance()->CompileFormFromXML($sXMLContent);

		$this->AssertPHPCodeIsValid($sProducedPHP);

		$this->assertEquals($sExpectedPHP, $sProducedPHP, $sMessage);
	}

	public function CompileFormFromXMLProvider()
	{
		return [

			'Basic properties' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<node id="basic_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
        <node id="title_property" xsi:type="Combodo-Property">
            <label>UI:BasicTest:Prop-Title</label>
            <value-type xsi:type="Combodo-ValueTypeLabel">
            </value-type>
        </node>
        <node id="class_property" xsi:type="Combodo-Property">
            <label>UI:BasicTest:Prop-Class</label>
            <value-type xsi:type="Combodo-ValueTypeClass">
            </value-type>
        </node>
    </nodes>
</node>
XML,
				'sExpectedPHP' => <<<PHP
class FormFor__basic_test extends Combodo\iTop\Forms\Block\Base\FormBlock
{
	protected function BuildForm(): void
	{
		\$this->Add('title_property', 'Combodo\iTop\Forms\Block\DataModel\LabelFormBlock', [
			'label' => 'UI:BasicTest:Prop-Title',
		]);

		\$this->Add('class_property', 'Combodo\iTop\Forms\Block\Base\TextFormBlock', [
			'label' => 'UI:BasicTest:Prop-Class',
		]);
	}
}
PHP,
				'sMessage' => 'Basic scalar properties should generate PHP',
			],

			'Empty property tree' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<node id="EmptyTest" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
	</nodes>
</node>
XML,
				'sExpectedPHP' => <<<PHP
class FormFor__EmptyTest extends Combodo\iTop\Forms\Block\Base\FormBlock
{
	protected function BuildForm(): void
	{	}
}
PHP,
				'sMessage' => 'Empty property tree should generate minimal PHP',
			],

			'Empty property tree lower case' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<node id="empty_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
	</nodes>
</node>
XML,
				'sExpectedPHP' => <<<PHP
class FormFor__empty_test extends Combodo\iTop\Forms\Block\Base\FormBlock
{
	protected function BuildForm(): void
	{	}
}
PHP,
				'sMessage' => 'Empty property tree should generate minimal PHP',
			],

			'Properties with all value-types' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<node id="AllValueTypesTest" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
		<node id="aggregate_function_property" xsi:type="Combodo-Property">
			<label>UI:AggregateFunction</label>
            <value-type xsi:type="Combodo-ValueTypeAggregateFunction">
            </value-type>
        </node>
		<node id="choice_property" xsi:type="Combodo-Property">
			<label>UI:Choice</label>
            <value-type xsi:type="Combodo-ValueTypeChoice">
            </value-type>
        </node>
		<node id="class_property" xsi:type="Combodo-Property">
			<label>UI:Class</label>
            <value-type xsi:type="Combodo-ValueTypeClass">
            </value-type>
        </node>
		<node id="class_attribute_property" xsi:type="Combodo-Property">
			<label>UI:ClassAttribute</label>
            <value-type xsi:type="Combodo-ValueTypeClassAttribute">
            </value-type>
        </node>
		<node id="class_attribute_group_by_property" xsi:type="Combodo-Property">
			<label>UI:ClassAttributeGroupBy</label>
            <value-type xsi:type="Combodo-ValueTypeClassAttributeGroupBy">
            </value-type>
        </node>
		<node id="class_attribute_value_property" xsi:type="Combodo-Property">
			<label>UI:ClassAttributeValue</label>
            <value-type xsi:type="Combodo-ValueTypeClassAttributeValue">
            </value-type>
        </node>
		<node id="integer_property" xsi:type="Combodo-Property">
			<label>UI:Integer</label>
            <value-type xsi:type="Combodo-ValueTypeInteger">
            </value-type>
        </node>
		<node id="label_property" xsi:type="Combodo-Property">
			<label>UI:Label</label>
            <value-type xsi:type="Combodo-ValueTypeLabel">
            </value-type>
        </node>
		<node id="oql_property" xsi:type="Combodo-Property">
			<label>UI:OQL</label>
            <value-type xsi:type="Combodo-ValueTypeOQL">
            </value-type>
        </node>
		<node id="string_property" xsi:type="Combodo-Property">
			<label>UI:String</label>
            <value-type xsi:type="Combodo-ValueTypeString">
            </value-type>
        </node>
	</nodes>
</node>
XML,
				'sExpectedPHP' => <<<PHP
class FormFor__AllValueTypesTest extends Combodo\iTop\Forms\Block\Base\FormBlock
{
	protected function BuildForm(): void
	{
		\$this->Add('aggregate_function_property', 'Combodo\iTop\Forms\Block\DataModel\Dashlet\AggregateFunctionFormBlock', [
			'label' => 'UI:AggregateFunction',
		]);

		\$this->Add('choice_property', 'Combodo\iTop\Forms\Block\Base\ChoiceFormBlock', [
			'label' => 'UI:Choice',
		]);

		\$this->Add('class_property', 'Combodo\iTop\Forms\Block\Base\TextFormBlock', [
			'label' => 'UI:Class',
		]);

		\$this->Add('class_attribute_property', 'Combodo\iTop\Forms\Block\DataModel\AttributeChoiceFormBlock', [
			'label' => 'UI:ClassAttribute',
		]);

		\$this->Add('class_attribute_group_by_property', 'Combodo\iTop\Forms\Block\DataModel\Dashlet\ClassAttributeGroupByFormBlock', [
			'label' => 'UI:ClassAttributeGroupBy',
		]);

		\$this->Add('class_attribute_value_property', 'Combodo\iTop\Forms\Block\DataModel\AttributeValueChoiceFormBlock', [
			'label' => 'UI:ClassAttributeValue',
		]);

		\$this->Add('integer_property', 'Combodo\iTop\Forms\Block\Base\IntegerFormBlock', [
			'label' => 'UI:Integer',
		]);

		\$this->Add('label_property', 'Combodo\iTop\Forms\Block\DataModel\LabelFormBlock', [
			'label' => 'UI:Label',
		]);

		\$this->Add('oql_property', 'Combodo\iTop\Forms\Block\DataModel\OqlFormBlock', [
			'label' => 'UI:OQL',
		]);

		\$this->Add('string_property', 'Combodo\iTop\Forms\Block\Base\TextFormBlock', [
			'label' => 'UI:String',
		]);
	}
}
PHP,
				'sMessage' => '',
			],

			'Collection of trees' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<node id="collection_of_trees_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
	    <node id="sub_tree_collection" xsi:type="Combodo-CollectionOfTrees">
	      <label>UI:SubTree</label>
	      <button-label>UI:AddSubTree</button-label>
	      <prototype>
	        <node id="string_property" xsi:type="Combodo-Property">
	          <label>UI:String</label>
	          <value-type xsi:type="Combodo-ValueTypeString">
	          </value-type>
	        </node>
			<node id="integer_property" xsi:type="Combodo-Property">
				<label>UI:Integer</label>
	            <value-type xsi:type="Combodo-ValueTypeInteger">
	            </value-type>
	        </node>
	      </prototype>
	    </node>
	</nodes>
</node>
XML,
				'sExpectedPHP' => <<<PHP
class SubFormFor__collection_of_trees_test__sub_tree_collection extends Combodo\iTop\Forms\Block\Base\FormBlock
{
	protected function BuildForm(): void
	{
		\$this->Add('string_property', 'Combodo\iTop\Forms\Block\Base\TextFormBlock', [
			'label' => 'UI:String',
		]);

		\$this->Add('integer_property', 'Combodo\iTop\Forms\Block\Base\IntegerFormBlock', [
			'label' => 'UI:Integer',
		]);
	}
}

class FormFor__collection_of_trees_test extends Combodo\iTop\Forms\Block\Base\FormBlock
{
	protected function BuildForm(): void
	{
		\$this->Add('collection_of_trees_test__sub_tree_collection', 'Combodo\iTop\Forms\Block\Base\CollectionBlock', [
			'label' => 'UI:SubTree',
			'button_label' => 'UI:AddSubTree',
			'block_entry_type' => 'SubFormFor__collection_of_trees_test__sub_tree_collection',
		]);
	}
}
PHP,
				'sMessage' => '',
			],

			'Input static' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<node id="input_static_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
		<node id="class_attribute_property" xsi:type="Combodo-Property">
			<label>UI:ClassAttribute</label>
			<value-type xsi:type="Combodo-ValueTypeClassAttribute">
				<class>Contact</class>
			</value-type>
        </node>
	</nodes>
</node>
XML,
				'sExpectedPHP' => <<<PHP
class FormFor__input_static_test extends Combodo\iTop\Forms\Block\Base\FormBlock
{
	protected function BuildForm(): void
	{
		\$this->Add('class_attribute_property', 'Combodo\iTop\Forms\Block\DataModel\AttributeChoiceFormBlock', [
			'label' => 'UI:ClassAttribute',
		])
			->SetInputValue('class', 'Contact');
	}
}
PHP,
				'sMessage' => '',
			],

			'test' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<node id="Test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
	</nodes>
</node>
XML,
				'sExpectedPHP' => <<<PHP
class FormFor__Test extends Combodo\iTop\Forms\Block\Base\FormBlock
{
	protected function BuildForm(): void
	{	}
}
PHP,
				'sMessage' => '',
			],
		];
	}
}
