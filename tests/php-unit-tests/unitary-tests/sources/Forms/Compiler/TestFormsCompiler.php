<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Forms\Compiler\FormsCompiler;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class TestFormsCompiler extends ItopDataTestCase
{
	/**
	 * @dataProvider CompileFormFromXMLProvider
	 *
	 * @param string $sXMLContent
	 * @param string $sExpectedPHP
	 * @param string $sMessage
	 *
	 * @return void
	 * @throws \Combodo\iTop\Forms\Compiler\FormsCompilerException
	 * @throws \Combodo\iTop\PropertyTree\PropertyTreeException
	 * @throws \DOMFormatException
	 */
	public function testCompileFormFromXML(string $sXMLContent, string $sExpectedPHP)
	{
		$sProducedPHP = FormsCompiler::GetInstance()->CompileFormFromXML($sXMLContent);

		$this->AssertPHPCodeIsValid($sProducedPHP);
		$sMessage = $this->dataName();
		$this->assertEquals($sExpectedPHP, $sProducedPHP, $sMessage);
	}

	public function CompileFormFromXMLProvider()
	{
		return [
			'Basic scalar properties should generate PHP' => [
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
			],

			'Empty property tree should generate minimal PHP' => [
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
			],

			'Empty property tree lower case should generate lower case class name' => [
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
		\$this->Add('sub_tree_collection', 'Combodo\iTop\Forms\Block\Base\CollectionBlock', [
			'label' => 'UI:SubTree',
			'button_label' => 'UI:AddSubTree',
			'block_entry_type' => 'SubFormFor__collection_of_trees_test__sub_tree_collection',
		]);
	}
}
PHP,
			],

			'Static inputs should be bound and invalid input should be ignored' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<node id="input_static_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
		<node id="class_attribute_property" xsi:type="Combodo-Property">
			<label>UI:ClassAttribute</label>
			<value-type xsi:type="Combodo-ValueTypeClassAttribute">
				<class>Contact</class>
				<invalid-input>Test</invalid-input>
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
			],

			'Dynamic input should be bound' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<node id="input_binding_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
		<node id="class_property" xsi:type="Combodo-Property">
			<label>UI:Class</label>
            <value-type xsi:type="Combodo-ValueTypeClass">
            </value-type>
        </node>
		<node id="class_attribute_property" xsi:type="Combodo-Property">
			<label>UI:ClassAttribute</label>
			<value-type xsi:type="Combodo-ValueTypeClassAttribute">
				<class>{{class_property.text}}</class>
			</value-type>
        </node>
	</nodes>
</node>
XML,
				'sExpectedPHP' => <<<PHP
class FormFor__input_binding_test extends Combodo\iTop\Forms\Block\Base\FormBlock
{
	protected function BuildForm(): void
	{
		\$this->Add('class_property', 'Combodo\iTop\Forms\Block\Base\TextFormBlock', [
			'label' => 'UI:Class',
		]);

		\$this->Add('class_attribute_property', 'Combodo\iTop\Forms\Block\DataModel\AttributeChoiceFormBlock', [
			'label' => 'UI:ClassAttribute',
		])
			->InputDependsOn('class', 'class_property', 'text');
	}
}
PHP,
			],

			'Relevance condition should generate a boolean block expression' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<node id="RelevanceCondition" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
		<node id="source_property" xsi:type="Combodo-Property">
			<label>UI:Source</label>
            <value-type xsi:type="Combodo-ValueTypeString">
            </value-type>
        </node>
		<node id="dependant_property" xsi:type="Combodo-Property">
			<label>UI:Dependant</label>
			<relevance-condition>source_property.text != 'count'</relevance-condition>
            <value-type xsi:type="Combodo-ValueTypeString">
            </value-type>
        </node>
	</nodes>
</node>
XML,
				'sExpectedPHP' => <<<PHP
class FormFor__RelevanceCondition extends Combodo\iTop\Forms\Block\Base\FormBlock
{
	protected function BuildForm(): void
	{
		\$this->Add('source_property', 'Combodo\iTop\Forms\Block\Base\TextFormBlock', [
			'label' => 'UI:Source',
		]);

		\$this->Add('dependant_property_relevance_condition', 'Combodo\iTop\Forms\Block\Expression\BooleanExpressionFormBlock', [
			'expression' => "source_property.text != 'count'",
		])
			->AddInputDependsOn('source_property.text', 'source_property', 'text');

		\$this->Add('dependant_property', 'Combodo\iTop\Forms\Block\Base\TextFormBlock', [
			'label' => 'UI:Dependant',
		])
			->InputDependsOn('visible', 'dependant_property_relevance_condition', 'result');
	}
}
PHP,
			],

			'Complex Relevance condition should generate a boolean block expression' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<node id="ComplexRelevanceCondition" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
		<node id="source_a_property" xsi:type="Combodo-Property">
			<label>UI:Source</label>
            <value-type xsi:type="Combodo-ValueTypeString">
            </value-type>
        </node>
		<node id="source_b_property" xsi:type="Combodo-Property">
			<label>UI:Source</label>
            <value-type xsi:type="Combodo-ValueTypeString">
            </value-type>
        </node>
		<node id="dependant_property" xsi:type="Combodo-Property">
			<label>UI:Dependant</label>
			<relevance-condition>IF(source_a_property.text != '', source_a_property.text, source_b_property.text)</relevance-condition>
            <value-type xsi:type="Combodo-ValueTypeString">
            </value-type>
        </node>
	</nodes>
</node>
XML,
				'sExpectedPHP' => <<<PHP
class FormFor__ComplexRelevanceCondition extends Combodo\iTop\Forms\Block\Base\FormBlock
{
	protected function BuildForm(): void
	{
		\$this->Add('source_a_property', 'Combodo\iTop\Forms\Block\Base\TextFormBlock', [
			'label' => 'UI:Source',
		]);

		\$this->Add('source_b_property', 'Combodo\iTop\Forms\Block\Base\TextFormBlock', [
			'label' => 'UI:Source',
		]);

		\$this->Add('dependant_property_relevance_condition', 'Combodo\iTop\Forms\Block\Expression\BooleanExpressionFormBlock', [
			'expression' => "IF(source_a_property.text != '', source_a_property.text, source_b_property.text)",
		])
			->AddInputDependsOn('source_a_property.text', 'source_a_property', 'text')
			->AddInputDependsOn('source_b_property.text', 'source_b_property', 'text');

		\$this->Add('dependant_property', 'Combodo\iTop\Forms\Block\Base\TextFormBlock', [
			'label' => 'UI:Dependant',
		])
			->InputDependsOn('visible', 'dependant_property_relevance_condition', 'result');
	}
}
PHP,
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
			],
		];
	}

	/**
	 * @dataProvider CompileFormFromInvalidXMLProvider
	 * @param string $sXMLContent
	 * @param string $sExpectedClass
	 * @param string $sExpectedMessage
	 *
	 * @return void
	 * @throws \Combodo\iTop\Forms\Compiler\FormsCompilerException
	 * @throws \Combodo\iTop\PropertyTree\PropertyTreeException
	 * @throws \DOMFormatException
	 */
	public function testCompileFormFromInvalidXML(string $sXMLContent, string $sExpectedClass, string $sExpectedMessage)
	{
		$this->expectException($sExpectedClass);
		$this->expectExceptionMessage($sExpectedMessage);
		FormsCompiler::GetInstance()->CompileFormFromXML($sXMLContent);
	}

	public function CompileFormFromInvalidXMLProvider()
	{
		return [
			'Invalid OQL expression in condition' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<node id="RelevanceCondition" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
		<node id="dependant_property" xsi:type="Combodo-Property">
			<label>UI:Dependant</label>
			<relevance-condition>source_property.text == 'count'</relevance-condition>
            <value-type xsi:type="Combodo-ValueTypeString">
            </value-type>
        </node>
	</nodes>
</node>
XML,
				'sExpectedClass' => 'Combodo\iTop\PropertyTree\PropertyTreeException',
				'sExpectedMessage' => 'Node: dependant_property, invalid syntax in relevance condition: Unexpected token EQ - found \'=\' at 22 in \'source_property.text == \'count\'\'',
			],

			'Unknown source in relevance condition' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<node id="RelevanceCondition" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
		<node id="dependant_property" xsi:type="Combodo-Property">
			<label>UI:Dependant</label>
			<relevance-condition>source_property.text = 'count'</relevance-condition>
            <value-type xsi:type="Combodo-ValueTypeString">
            </value-type>
        </node>
	</nodes>
</node>
XML,
				'sExpectedClass' => 'Combodo\iTop\PropertyTree\PropertyTreeException',
				'sExpectedMessage' => 'Node: dependant_property, invalid source in relevance condition: source_property',
			],

			'Unknown output in relevance condition' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<node id="RelevanceCondition" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
		<node id="source_property" xsi:type="Combodo-Property">
			<label>UI:Source</label>
            <value-type xsi:type="Combodo-ValueTypeString">
            </value-type>
        </node>
		<node id="dependant_property" xsi:type="Combodo-Property">
			<label>UI:Dependant</label>
			<relevance-condition>source_property.text_output != 'count'</relevance-condition>
            <value-type xsi:type="Combodo-ValueTypeString">
            </value-type>
        </node>
	</nodes>
</node>
XML,
				'sExpectedClass' => 'Combodo\iTop\PropertyTree\PropertyTreeException',
				'sExpectedMessage' => 'Node: dependant_property, invalid output in relevance condition: source_property.text_output',
			],

			'Missing output or source in relevance condition' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<node id="RelevanceCondition" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
		<node id="source_property" xsi:type="Combodo-Property">
			<label>UI:Source</label>
            <value-type xsi:type="Combodo-ValueTypeString">
            </value-type>
        </node>
		<node id="dependant_property" xsi:type="Combodo-Property">
			<label>UI:Dependant</label>
			<relevance-condition>source_property != 'count'</relevance-condition>
            <value-type xsi:type="Combodo-ValueTypeString">
            </value-type>
        </node>
	</nodes>
</node>
XML,
				'sExpectedClass' => 'Combodo\iTop\PropertyTree\PropertyTreeException',
				'sExpectedMessage' => 'Node: dependant_property, missing output or source in relevance condition: source_property',
			],

			'Missing value-type in node specification' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<node id="RelevanceCondition" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
		<node id="source_property" xsi:type="Combodo-Property">
			<label>UI:Source</label>
        </node>
	</nodes>
</node>
XML,
				'sExpectedClass' => 'Combodo\iTop\PropertyTree\PropertyTreeException',
				'sExpectedMessage' => 'Node: source_property, missing value-type in node specification',
			],
		];
	}
}
