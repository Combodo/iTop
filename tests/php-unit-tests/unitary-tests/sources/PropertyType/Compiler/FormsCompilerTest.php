<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\PropertyType\Compiler\PropertyTypeCompiler;
use Combodo\iTop\Service\DependencyInjection\ServiceLocator;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class FormsCompilerTest extends ItopDataTestCase
{
	/**
	 * @dataProvider CompileFormFromXMLProvider
	 *
	 * @param string $sXMLContent
	 * @param string $sExpectedPHP
	 *
	 * @return void
	 * @throws \Combodo\iTop\PropertyType\Compiler\PropertyTypeCompilerException
	 * @throws \Combodo\iTop\PropertyType\PropertyTypeException
	 * @throws \DOMFormatException
	 */
	public function testCompileFormFromXML(string $sXMLContent, string $sExpectedPHP)
	{
		ServiceLocator::GetInstance()->RegisterService('ModelReflection', new ModelReflectionRuntime());

		$sProducedPHP = PropertyTypeCompiler::GetInstance()->CompileFormFromXML($sXMLContent);

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
<property_type id="basic_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-PropertyTree">
	    <nodes>
	        <node id="title_property" xsi:type="Combodo-ValueType-Label">
	            <label>UI:BasicTest:Prop-Title</label>
	        </node>
	        <node id="class_property" xsi:type="Combodo-ValueType-Class">
	            <label>UI:BasicTest:Prop-Class</label>
	            <categories-csv>test</categories-csv>
	        </node>
	    </nodes>
    </definition>
</property_type>
XML,
				'sExpectedPHP' => <<<PHP
class FormFor__basic_test extends Combodo\iTop\Forms\Block\Base\FormBlock
{
	protected function BuildForm(): void
	{
		\$this->Add('title_property', 'Combodo\iTop\Forms\Block\DataModel\LabelFormBlock', [
			'label' => 'UI:BasicTest:Prop-Title',
		]);

		\$this->Add('class_property', 'Combodo\iTop\Forms\Block\Base\ChoiceFormBlock', [
			'label' => 'UI:BasicTest:Prop-Class',
			'choices' => [
			],
		]);
	}
}
PHP,
			],

			'Empty property tree should generate minimal PHP' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="EmptyTest" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-PropertyTree">
	    <nodes>
		</nodes>
	</definition>
</property_type>
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
<property_type id="empty_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-PropertyTree">
	    <nodes>
		</nodes>
	</definition>
</property_type>
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
<property_type id="AllValueTypesTest" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-PropertyTree">
	    <nodes>
			<node id="aggregate_function_property" xsi:type="Combodo-ValueType-AggregateFunction">
				<label>UI:AggregateFunction</label>
	        </node>
			<node id="choice_property" xsi:type="Combodo-ValueType-Choice">
				<label>UI:Choice</label>
	              <values>
	                <value id="value_a">
	                  <label>Label A</label>
	                </value>
	                <value id="value_b">
	                  <label>Label B</label>
	                </value>
	              </values>
	        </node>
			<node id="class_property" xsi:type="Combodo-ValueType-Class">
				<label>UI:Class</label>
	            <categories-csv>test</categories-csv>
	        </node>
			<node id="class_attribute_property" xsi:type="Combodo-ValueType-ClassAttribute">
				<label>UI:ClassAttribute</label>
	        </node>
			<node id="class_attribute_group_by_property" xsi:type="Combodo-ValueType-ClassAttributeGroupBy">
				<label>UI:ClassAttributeGroupBy</label>
	        </node>
			<node id="class_attribute_value_property" xsi:type="Combodo-ValueType-ClassAttributeValue">
				<label>UI:ClassAttributeValue</label>
	        </node>
			<node id="integer_property" xsi:type="Combodo-ValueType-Integer">
				<label>UI:Integer</label>
	        </node>
			<node id="label_property" xsi:type="Combodo-ValueType-Label">
				<label>UI:Label</label>
	        </node>
			<node id="oql_property" xsi:type="Combodo-ValueType-OQL">
				<label>UI:OQL</label>
	        </node>
			<node id="string_property" xsi:type="Combodo-ValueType-String">
				<label>UI:String</label>
	        </node>
	        <node id="choice_from_input" xsi:type="Combodo-ValueType-ChoiceFromInput">
	            <label>UI:ChoiceFromInput</label>
	              <values>
	                <value id="value_a">
	                  <label>{{class_attribute_property.label}}</label>
	                </value>
	                <value id="value_b">
	                  <label>{{class_attribute_group_by_property.label}}</label>
	                </value>
	              </values>
	        </node>
		</nodes>
	</definition>
</property_type>
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
			'choices' => [
				\Dict::S('Label A') => 'value_a',
				\Dict::S('Label B') => 'value_b',
			],
		]);

		\$this->Add('class_property', 'Combodo\iTop\Forms\Block\Base\ChoiceFormBlock', [
			'label' => 'UI:Class',
			'choices' => [
			],
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

		\$this->Add('choice_from_input', 'Combodo\iTop\Forms\Block\Base\ChoiceFromInputsBlock', [
			'label' => 'UI:ChoiceFromInput',
		])
			->AddInputDependsOn('value_a', 'class_attribute_property', 'label')
			->AddInputDependsOn('value_b', 'class_attribute_group_by_property', 'label');
	}
}
PHP,
			],

			'Collection of trees' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="collection_of_trees_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-PropertyTree">
    <nodes>
	    <node id="sub_tree_collection" xsi:type="Combodo-ValueType-Collection">
	      <label>UI:SubTree</label>
	      <xml-format xsi:type="Combodo-XMLFormat-FlatArray">
	        <count-tag>item_count</count-tag>
            <tag-format>item_\$rank\$_\$id\$</tag-format>
	      </xml-format>
	      <prototype>
	        <node id="string_property" xsi:type="Combodo-ValueType-String">
	          <label>UI:String</label>
	        </node>
			<node id="integer_property" xsi:type="Combodo-ValueType-Integer">
				<label>UI:Integer</label>
				<relevance-condition>{{string_property.text != 'no-display'}}</relevance-condition>
	        </node>
	      </prototype>
	    </node>
	</nodes>
	</definition>
</property_type>
XML,
				'sExpectedPHP' => <<<PHP
class SubFormFor__collection_of_trees_test__sub_tree_collection extends Combodo\iTop\Forms\Block\Base\FormBlock
{
	protected function BuildForm(): void
	{
		\$this->Add('string_property', 'Combodo\iTop\Forms\Block\Base\TextFormBlock', [
			'label' => 'UI:String',
		]);

		\$this->Add('integer_property_visible_expression', 'Combodo\iTop\Forms\Block\Expression\BooleanExpressionFormBlock', [
			'expression' => 'string_property.text != \'no-display\'',
		])
			->AddInputDependsOn('string_property.text', 'string_property', 'text');

		\$this->Add('integer_property', 'Combodo\iTop\Forms\Block\Base\IntegerFormBlock', [
			'label' => 'UI:Integer',
		])
			->InputDependsOn('visible', 'integer_property_visible_expression', 'result');
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
<property_type id="input_static_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-PropertyTree">
	    <nodes>
			<node id="class_attribute_property" xsi:type="Combodo-ValueType-ClassAttribute">
				<label>UI:ClassAttribute</label>
				<class>Contact</class>
				<invalid-input>Test</invalid-input>
	        </node>
		</nodes>
	</definition>
</property_type>
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

			'Quotes should be handled gracefully' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="input_quotes_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-PropertyTree">
	    <nodes>
			<node id="class_attribute_property" xsi:type="Combodo-ValueType-ClassAttribute">
				<label>'Class' and "Attribute"</label>
				<class>{{CONCAT("'", '"')}}</class>
				<category>'Class' and "Attribute"</category>
	        </node>
		</nodes>
	</definition>
</property_type>
XML,
				'sExpectedPHP' => <<<PHP
class FormFor__input_quotes_test extends Combodo\iTop\Forms\Block\Base\FormBlock
{
	protected function BuildForm(): void
	{
		\$this->Add('class_attribute_property_class_expression', 'Combodo\iTop\Forms\Block\Expression\StringExpressionFormBlock', [
			'expression' => 'CONCAT("\'", \'"\')',
		]);

		\$this->Add('class_attribute_property', 'Combodo\iTop\Forms\Block\DataModel\AttributeChoiceFormBlock', [
			'label' => '\'Class\' and "Attribute"',
		])
			->InputDependsOn('class', 'class_attribute_property_class_expression', 'result')
			->SetInputValue('category', '\'Class\' and "Attribute"');
	}
}
PHP,
			],

			'Dynamic input should be bound' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="input_binding_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-PropertyTree">
	    <nodes>
			<node id="class_property" xsi:type="Combodo-ValueType-Class">
				<label>UI:Class</label>
	            <categories-csv>test</categories-csv>
	        </node>
			<node id="class_attribute_property" xsi:type="Combodo-ValueType-ClassAttribute">
				<label>UI:ClassAttribute</label>
				<class>{{class_property.text}}</class>
	        </node>
		</nodes>
	</definition>
</property_type>
XML,
				'sExpectedPHP' => <<<PHP
class FormFor__input_binding_test extends Combodo\iTop\Forms\Block\Base\FormBlock
{
	protected function BuildForm(): void
	{
		\$this->Add('class_property', 'Combodo\iTop\Forms\Block\Base\ChoiceFormBlock', [
			'label' => 'UI:Class',
			'choices' => [
			],
		]);

		\$this->Add('class_attribute_property', 'Combodo\iTop\Forms\Block\DataModel\AttributeChoiceFormBlock', [
			'label' => 'UI:ClassAttribute',
		])
			->InputDependsOn('class', 'class_property', 'text');
	}
}
PHP,
			],

			'Dynamic input can be an expression' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="input_binding_expression" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-PropertyTree">
    <nodes>
		<node id="class_property" xsi:type="Combodo-ValueType-Class">
			<label>UI:Class</label>
              <categories-csv>test</categories-csv>
        </node>
		<node id="class_attribute_property" xsi:type="Combodo-ValueType-ClassAttribute">
			<label>UI:ClassAttribute</label>
				<class>{{IF(class_property.value = '', 'Person', class_property.value)}}</class>
        </node>
	</nodes>
	</definition>
</property_type>
XML,
				'sExpectedPHP' => <<<PHP
class FormFor__input_binding_expression extends Combodo\iTop\Forms\Block\Base\FormBlock
{
	protected function BuildForm(): void
	{
		\$this->Add('class_property', 'Combodo\iTop\Forms\Block\Base\ChoiceFormBlock', [
			'label' => 'UI:Class',
			'choices' => [
			],
		]);

		\$this->Add('class_attribute_property_class_expression', 'Combodo\iTop\Forms\Block\Expression\StringExpressionFormBlock', [
			'expression' => 'IF(class_property.value = \'\', \'Person\', class_property.value)',
		])
			->AddInputDependsOn('class_property.value', 'class_property', 'value');

		\$this->Add('class_attribute_property', 'Combodo\iTop\Forms\Block\DataModel\AttributeChoiceFormBlock', [
			'label' => 'UI:ClassAttribute',
		])
			->InputDependsOn('class', 'class_attribute_property_class_expression', 'result');
	}
}
PHP,
			],

			'Relevance condition should generate a boolean block expression' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="RelevanceCondition" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-PropertyTree">
    <nodes>
		<node id="source_property" xsi:type="Combodo-ValueType-String">
			<label>UI:Source</label>
        </node>
		<node id="dependant_property" xsi:type="Combodo-ValueType-String">
			<label>UI:Dependant</label>
			<relevance-condition>{{source_property.text != 'count'}}</relevance-condition>
        </node>
	</nodes>
	</definition>
</property_type>
XML,
				'sExpectedPHP' => <<<PHP
class FormFor__RelevanceCondition extends Combodo\iTop\Forms\Block\Base\FormBlock
{
	protected function BuildForm(): void
	{
		\$this->Add('source_property', 'Combodo\iTop\Forms\Block\Base\TextFormBlock', [
			'label' => 'UI:Source',
		]);

		\$this->Add('dependant_property_visible_expression', 'Combodo\iTop\Forms\Block\Expression\BooleanExpressionFormBlock', [
			'expression' => 'source_property.text != \'count\'',
		])
			->AddInputDependsOn('source_property.text', 'source_property', 'text');

		\$this->Add('dependant_property', 'Combodo\iTop\Forms\Block\Base\TextFormBlock', [
			'label' => 'UI:Dependant',
		])
			->InputDependsOn('visible', 'dependant_property_visible_expression', 'result');
	}
}
PHP,
			],

			'Complex Relevance condition should generate a boolean block expression' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="ComplexRelevanceCondition" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-PropertyTree">
    <nodes>
		<node id="source_a_property" xsi:type="Combodo-ValueType-String">
			<label>UI:Source</label>
        </node>
		<node id="source_b_property" xsi:type="Combodo-ValueType-String">
			<label>UI:Source</label>
        </node>
		<node id="dependant_property" xsi:type="Combodo-ValueType-String">
			<label>UI:Dependant</label>
			<relevance-condition>{{IF(source_a_property.text != '', source_a_property.text, source_b_property.text)}}</relevance-condition>
        </node>
	</nodes>
	</definition>
</property_type>
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

		\$this->Add('dependant_property_visible_expression', 'Combodo\iTop\Forms\Block\Expression\BooleanExpressionFormBlock', [
			'expression' => 'IF(source_a_property.text != \'\', source_a_property.text, source_b_property.text)',
		])
			->AddInputDependsOn('source_a_property.text', 'source_a_property', 'text')
			->AddInputDependsOn('source_b_property.text', 'source_b_property', 'text');

		\$this->Add('dependant_property', 'Combodo\iTop\Forms\Block\Base\TextFormBlock', [
			'label' => 'UI:Dependant',
		])
			->InputDependsOn('visible', 'dependant_property_visible_expression', 'result');
	}
}
PHP,
			],
			'Sub form generate a sub-form' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_typeproperty_type id="SubFormTest" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-PropertyTree">
	    <nodes>
	      <node id="sub_form_property" xsi:type="Combodo-ValueType-PropertyTree">
			<label>UI:SubForm:Title</label>
	        <nodes>
	          <node id="string_property" xsi:type="Combodo-ValueType-String">
				<label>UI:String</label>
              </node>
			</nodes>
	      </node>
		</nodes>
	</definition>
</property_typeproperty_type>
XML,
				'sExpectedPHP' => <<<PHP
class SubFormFor__SubFormTest__sub_form_property extends Combodo\iTop\Forms\Block\Base\FormBlock
{
	protected function BuildForm(): void
	{
		\$this->Add('string_property', 'Combodo\iTop\Forms\Block\Base\TextFormBlock', [
			'label' => 'UI:String',
		]);
	}
}

class FormFor__SubFormTest extends Combodo\iTop\Forms\Block\Base\FormBlock
{
	protected function BuildForm(): void
	{
		\$this->Add('sub_form_property', 'SubFormFor__SubFormTest__sub_form_property', [
			'label' => 'UI:SubForm:Title',
		]);
	}
}
PHP,
			],
			'Collection of values' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_typeproperty_type id="CollectionOfValuesTest" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-PropertyTree">
	    <nodes>
	      <node id="coll" xsi:type="Combodo-ValueType-CollectionOfValues">
	          <label>UI:ClassAttributeValue</label>
	          <xml-format xsi:type="Combodo-XMLFormat-CSV"/>
              <value-type xsi:type="Combodo-ValueType-ClassAttributeValue">
                <class>Contact</class>
                <attribute>status</attribute>
              </value-type>
	      </node>
		</nodes>
	</definition>
</property_typeproperty_type>
XML,
				'sExpectedPHP' => <<<PHP
class FormFor__CollectionOfValuesTest extends Combodo\iTop\Forms\Block\Base\FormBlock
{
	protected function BuildForm(): void
	{
		\$this->Add('coll', 'Combodo\iTop\Forms\Block\DataModel\AttributeValueChoiceFormBlock', [
			'label' => 'UI:ClassAttributeValue',
			'multiple' => true,
		]);
	}
}
PHP,
			],
			'Single value' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_typeproperty_type id="SingleValueTest" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-Label">
      <label>Single Test</label>
	</definition>
</property_typeproperty_type>
XML,
				'sExpectedPHP' => <<<PHP
class FormFor__SingleValueTest extends Combodo\iTop\Forms\Block\DataModel\LabelFormBlock
{
}
PHP,
			],
			'test' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_typeproperty_type id="Test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-PropertyTree">
	    <nodes>
		</nodes>
	</definition>
</property_typeproperty_type>
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
	 *
	 * @param string $sXMLContent
	 * @param string $sExpectedClass
	 * @param string $sExpectedMessage
	 *
	 * @return void
	 * @throws \Combodo\iTop\PropertyType\Compiler\PropertyTypeCompilerException
	 * @throws \Combodo\iTop\PropertyType\PropertyTypeException
	 * @throws \DOMFormatException
	 */
	public function testCompileFormFromInvalidXML(string $sXMLContent, string $sExpectedClass, string $sExpectedMessage)
	{
		$this->expectException($sExpectedClass);
		$this->expectExceptionMessage($sExpectedMessage);
		PropertyTypeCompiler::GetInstance()->CompileFormFromXML($sXMLContent);
	}

	public function CompileFormFromInvalidXMLProvider()
	{
		return [
			'Invalid OQL expression in condition' => [
				'sXMLContent'      => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="RelevanceCondition" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-PropertyTree">
    <nodes>
		<node id="dependant_property" xsi:type="Combodo-ValueType-String">
			<label>UI:Dependant</label>
			<relevance-condition>{{source_property.text == 'count'}}</relevance-condition>
        </node>
	</nodes>
	</definition>
</property_type>
XML,
				'sExpectedClass'   => 'Combodo\iTop\PropertyType\PropertyTypeException',
				'sExpectedMessage' => 'Node: dependant_property, invalid syntax in condition: Unexpected token EQ - found \'=\' at 22 in \'source_property.text == \'count\'\'',
			],

			'Unknown source in relevance condition' => [
				'sXMLContent'      => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="RelevanceCondition" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-PropertyTree">
    <nodes>
		<node id="dependant_property" xsi:type="Combodo-ValueType-String">
			<label>UI:Dependant</label>
			<relevance-condition>{{source_property.text = 'count'}}</relevance-condition>
        </node>
	</nodes>
	</definition>
</property_type>
XML,
				'sExpectedClass'   => 'Combodo\iTop\PropertyType\PropertyTypeException',
				'sExpectedMessage' => 'Node: dependant_property, invalid source in condition: source_property',
			],

			'Unknown output in relevance condition' => [
				'sXMLContent'      => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="RelevanceCondition" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-PropertyTree">
    <nodes>
		<node id="source_property" xsi:type="Combodo-ValueType-String">
			<label>UI:Source</label>
        </node>
		<node id="dependant_property" xsi:type="Combodo-ValueType-String">
			<label>UI:Dependant</label>
			<relevance-condition>{{source_property.text_output != 'count'}}</relevance-condition>
        </node>
	</nodes>
	</definition>
</property_type>
XML,
				'sExpectedClass'   => 'Combodo\iTop\PropertyType\PropertyTypeException',
				'sExpectedMessage' => 'Node: dependant_property, invalid output in condition: source_property.text_output',
			],

			'Missing output or source in relevance condition' => [
				'sXMLContent'      => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="RelevanceCondition" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-PropertyTree">
    <nodes>
		<node id="source_property" xsi:type="Combodo-ValueType-String">
			<label>UI:Source</label>
        </node>
		<node id="dependant_property" xsi:type="Combodo-ValueType-String">
			<label>UI:Dependant</label>
			<relevance-condition>{{source_property != 'count'}}</relevance-condition>
        </node>
	</nodes>
	</definition>
</property_type>
XML,
				'sExpectedClass'   => 'Combodo\iTop\PropertyType\PropertyTypeException',
				'sExpectedMessage' => 'Node: dependant_property, missing output or source in condition: source_property',
			],

			'Missing value-type in node specification' => [
				'sXMLContent'      => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="WrongDefinition" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-PropertyTree">
    <nodes>
		<node id="source_property">
			<label>UI:Source</label>
        </node>
	</nodes>
	</definition>
</property_type>
XML,
				'sExpectedClass'   => 'Combodo\iTop\PropertyType\PropertyTypeException',
				'sExpectedMessage' => '/property_type[WrongDefinition]/definition/nodes/node[source_property]: Missing value-type in node specification',
			],
			'Wrong class for value-type in node specification' => [
				'sXMLContent'      => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="WrongDefinition" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-PropertyTree">
    <nodes>
		<node id="source_property" xsi:type="Test-Combodo">
			<label>UI:Source</label>
        </node>
	</nodes>
	</definition>
</property_type>
XML,
				'sExpectedClass'   => 'Combodo\iTop\PropertyType\PropertyTypeException',
				'sExpectedMessage' => '/property_type[WrongDefinition]/definition/nodes/node[source_property]: Unknown value-type node class: "Test-Combodo"',
			],
			'Wrong class for xml-format in node specification' => [
				'sXMLContent'      => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="WrongDefinition" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-PropertyTree">
	    <nodes>
	      <node id="coll" xsi:type="Combodo-ValueType-CollectionOfValues">
	          <label>UI:ClassAttributeValue</label>
	          <xml-format xsi:type="Combodo-test"/>
	          <value-type xsi:type="Combodo-ValueType-ClassAttributeValue">
	            <class>Contact</class>
	            <attribute>status</attribute>
	          </value-type>
	      </node>
		</nodes>
	</definition>
</property_type>
XML,
				'sExpectedClass'   => 'Combodo\iTop\PropertyType\Serializer\SerializerException',
				'sExpectedMessage' => '/property_type[WrongDefinition]/definition/nodes/node[coll]/xml-format: Unknown type node class: "Combodo-test"',
			],
			'Missing class for xml-format in node specification' => [
				'sXMLContent'      => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="WrongDefinition" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-PropertyTree">
	    <nodes>
	      <node id="coll" xsi:type="Combodo-ValueType-CollectionOfValues">
	          <label>UI:ClassAttributeValue</label>
	          <xml-format/>
	          <value-type xsi:type="Combodo-ValueType-ClassAttributeValue">
	            <class>Contact</class>
	            <attribute>status</attribute>
	          </value-type>
	      </node>
		</nodes>
	</definition>
</property_type>
XML,
				'sExpectedClass'   => 'Combodo\iTop\PropertyType\Serializer\SerializerException',
				'sExpectedMessage' => '/property_type[WrongDefinition]/definition/nodes/node[coll]/xml-format: Missing xsi:type in node specification',
			],
			'Missing tag in xml-format ' => [
				'sXMLContent'      => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="WrongDefinition" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-PropertyTree">
	    <nodes>
		    <node xsi:type="Combodo-ValueType-CollectionOfValues">
		      <xml-format xsi:type="Combodo-XMLFormat-ValueAsId">
			  </xml-format>
		      <value-type xsi:type="Combodo-ValueType-Class">
		      </value-type>
		    </node>
		</nodes>
	</definition>
</property_type>
XML,
				'sExpectedClass'   => 'Combodo\iTop\PropertyType\Serializer\SerializerException',
				'sExpectedMessage' => '/property_type[WrongDefinition]/definition/nodes/node/xml-format: Missing <tag-name> element',
			],
		];
	}
}
