<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\Forms\Compiler\FormsCompiler;
use Combodo\iTop\Service\DependencyInjection\DIService;
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
	 * @throws \Combodo\iTop\Forms\Compiler\FormsCompilerException
	 * @throws \Combodo\iTop\PropertyTree\PropertyTreeException
	 * @throws \DOMFormatException
	 */
	public function testCompileFormFromXML(string $sXMLContent, string $sExpectedPHP)
	{
		DIService::GetInstance()->RegisterService('ModelReflection', new ModelReflectionRuntime());

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
<property_tree id="basic_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
        <node id="title_property">
            <label>UI:BasicTest:Prop-Title</label>
            <value-type xsi:type="Combodo-ValueType-Label">
            </value-type>
        </node>
        <node id="class_property">
            <label>UI:BasicTest:Prop-Class</label>
            <value-type xsi:type="Combodo-ValueType-Class">
              <categories-csv>test</categories-csv>
            </value-type>
        </node>
    </nodes>
</property_tree>
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
<property_tree id="EmptyTest" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
	</nodes>
</property_tree>
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
<property_tree id="empty_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
	</nodes>
</property_tree>
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
<property_tree id="AllValueTypesTest" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
		<node id="aggregate_function_property">
			<label>UI:AggregateFunction</label>
            <value-type xsi:type="Combodo-ValueType-AggregateFunction">
            </value-type>
        </node>
		<node id="choice_property">
			<label>UI:Choice</label>
            <value-type xsi:type="Combodo-ValueType-Choice">
              <values>
                <value id="value_a">
                  <label>Label A</label>
                </value>
                <value id="value_b">
                  <label>Label B</label>
                </value>
              </values>
            </value-type>
        </node>
		<node id="class_property">
			<label>UI:Class</label>
            <value-type xsi:type="Combodo-ValueType-Class">
              <categories-csv>test</categories-csv>
            </value-type>
        </node>
		<node id="class_attribute_property">
			<label>UI:ClassAttribute</label>
            <value-type xsi:type="Combodo-ValueType-ClassAttribute">
            </value-type>
        </node>
		<node id="class_attribute_group_by_property">
			<label>UI:ClassAttributeGroupBy</label>
            <value-type xsi:type="Combodo-ValueType-ClassAttributeGroupBy">
            </value-type>
        </node>
		<node id="class_attribute_value_property">
			<label>UI:ClassAttributeValue</label>
            <value-type xsi:type="Combodo-ValueType-ClassAttributeValue">
            </value-type>
        </node>
		<node id="integer_property">
			<label>UI:Integer</label>
            <value-type xsi:type="Combodo-ValueType-Integer">
            </value-type>
        </node>
		<node id="label_property">
			<label>UI:Label</label>
            <value-type xsi:type="Combodo-ValueType-Label">
            </value-type>
        </node>
		<node id="oql_property">
			<label>UI:OQL</label>
            <value-type xsi:type="Combodo-ValueType-OQL">
            </value-type>
        </node>
		<node id="string_property">
			<label>UI:String</label>
            <value-type xsi:type="Combodo-ValueType-String">
            </value-type>
        </node>
        <node id="choice_from_input">
            <label>UI:ChoiceFromInput</label>
            <value-type xsi:type="Combodo-ValueType-ChoiceFromInput">
              <values>
                <value id="value_a">
                  <label>{{class_attribute_property.label}}</label>
                </value>
                <value id="value_b">
                  <label>{{class_attribute_group_by_property.label}}</label>
                </value>
              </values>
            </value-type>
        </node>
	</nodes>
</property_tree>
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
<property_tree id="collection_of_trees_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
	    <node id="sub_tree_collection">
	      <label>UI:SubTree</label>
	      <value-type xsi:type="Combodo-ValueType-Collection">
		      <prototype>
		        <node id="string_property">
		          <label>UI:String</label>
		          <value-type xsi:type="Combodo-ValueType-String">
		          </value-type>
		        </node>
				<node id="integer_property">
					<label>UI:Integer</label>
					<relevance-condition>{{string_property.text != 'no-display'}}</relevance-condition>
		            <value-type xsi:type="Combodo-ValueType-Integer">
		            </value-type>
		        </node>
		      </prototype>
	      </value-type>
	    </node>
	</nodes>
</property_tree>
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
<node id="input_static_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
		<node id="class_attribute_property">
			<label>UI:ClassAttribute</label>
			<value-type xsi:type="Combodo-ValueType-ClassAttribute">
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

			'Quotes should be handled gracefully' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<node id="input_quotes_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
		<node id="class_attribute_property">
			<label>'Class' and "Attribute"</label>
			<value-type xsi:type="Combodo-ValueType-ClassAttribute">
				<class>{{CONCAT("'", '"')}}</class>
				<category>'Class' and "Attribute"</category>
			</value-type>
        </node>
	</nodes>
</node>
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
<node id="input_binding_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
		<node id="class_property">
			<label>UI:Class</label>
            <value-type xsi:type="Combodo-ValueType-Class">
              <categories-csv>test</categories-csv>
            </value-type>
        </node>
		<node id="class_attribute_property">
			<label>UI:ClassAttribute</label>
			<value-type xsi:type="Combodo-ValueType-ClassAttribute">
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
<node id="input_binding_expression" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
		<node id="class_property">
			<label>UI:Class</label>
            <value-type xsi:type="Combodo-ValueType-Class">
              <categories-csv>test</categories-csv>
            </value-type>
        </node>
		<node id="class_attribute_property">
			<label>UI:ClassAttribute</label>
			<value-type xsi:type="Combodo-ValueType-ClassAttribute">
				<class>{{IF(class_property.value = '', 'Person', class_property.value)}}</class>
			</value-type>
        </node>
	</nodes>
</node>
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
<node id="RelevanceCondition" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
		<node id="source_property">
			<label>UI:Source</label>
            <value-type xsi:type="Combodo-ValueType-String">
            </value-type>
        </node>
		<node id="dependant_property">
			<label>UI:Dependant</label>
			<relevance-condition>{{source_property.text != 'count'}}</relevance-condition>
            <value-type xsi:type="Combodo-ValueType-String">
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
<node id="ComplexRelevanceCondition" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
		<node id="source_a_property">
			<label>UI:Source</label>
            <value-type xsi:type="Combodo-ValueType-String">
            </value-type>
        </node>
		<node id="source_b_property">
			<label>UI:Source</label>
            <value-type xsi:type="Combodo-ValueType-String">
            </value-type>
        </node>
		<node id="dependant_property">
			<label>UI:Dependant</label>
			<relevance-condition>{{IF(source_a_property.text != '', source_a_property.text, source_b_property.text)}}</relevance-condition>
            <value-type xsi:type="Combodo-ValueType-String">
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
			'Class category for value type class' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<node id="ClassCategory" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
    	<node id="class_property">
			<label>UI:Class</label>
            <value-type xsi:type="Combodo-ValueType-Class">
              <categories-csv>addon/authentication,grant_by_profile,silo</categories-csv>
            </value-type>
        </node>
	</nodes>
</node>
XML,
				'sExpectedPHP' => <<<PHP
class FormFor__ClassCategory extends Combodo\iTop\Forms\Block\Base\FormBlock
{
	protected function BuildForm(): void
	{
		\$this->Add('class_property', 'Combodo\iTop\Forms\Block\Base\ChoiceFormBlock', [
			'label' => 'UI:Class',
			'choices' => [
				\Dict::S('Class:ActionEmail') => 'ActionEmail',
				\Dict::S('Class:ActionNewsroom') => 'ActionNewsroom',
				\Dict::S('Class:AuditCategory') => 'AuditCategory',
				\Dict::S('Class:AuditDomain') => 'AuditDomain',
				\Dict::S('Class:AuditRule') => 'AuditRule',
				\Dict::S('Class:OAuthClientAzure') => 'OAuthClientAzure',
				\Dict::S('Class:OAuthClientGoogle') => 'OAuthClientGoogle',
				\Dict::S('Class:QueryOQL') => 'QueryOQL',
				\Dict::S('Class:SynchroAttExtKey') => 'SynchroAttExtKey',
				\Dict::S('Class:SynchroAttLinkSet') => 'SynchroAttLinkSet',
				\Dict::S('Class:SynchroAttribute') => 'SynchroAttribute',
				\Dict::S('Class:SynchroDataSource') => 'SynchroDataSource',
				\Dict::S('Class:SynchroLog') => 'SynchroLog',
				\Dict::S('Class:SynchroReplica') => 'SynchroReplica',
				\Dict::S('Class:TriggerOnAttachmentCreate') => 'TriggerOnAttachmentCreate',
				\Dict::S('Class:TriggerOnAttachmentDelete') => 'TriggerOnAttachmentDelete',
				\Dict::S('Class:TriggerOnAttachmentDownload') => 'TriggerOnAttachmentDownload',
				\Dict::S('Class:TriggerOnAttributeBlobDownload') => 'TriggerOnAttributeBlobDownload',
				\Dict::S('Class:TriggerOnObjectCreate') => 'TriggerOnObjectCreate',
				\Dict::S('Class:TriggerOnObjectDelete') => 'TriggerOnObjectDelete',
				\Dict::S('Class:TriggerOnObjectMention') => 'TriggerOnObjectMention',
				\Dict::S('Class:TriggerOnObjectUpdate') => 'TriggerOnObjectUpdate',
				\Dict::S('Class:TriggerOnPortalUpdate') => 'TriggerOnPortalUpdate',
				\Dict::S('Class:TriggerOnStateEnter') => 'TriggerOnStateEnter',
				\Dict::S('Class:TriggerOnStateLeave') => 'TriggerOnStateLeave',
				\Dict::S('Class:TriggerOnThresholdReached') => 'TriggerOnThresholdReached',
				\Dict::S('Class:URP_Profiles') => 'URP_Profiles',
				\Dict::S('Class:URP_UserOrg') => 'URP_UserOrg',
				\Dict::S('Class:UserExternal') => 'UserExternal',
				\Dict::S('Class:UserLDAP') => 'UserLDAP',
				\Dict::S('Class:UserLocal') => 'UserLocal',
			],
		]);
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
		<node id="dependant_property">
			<label>UI:Dependant</label>
			<relevance-condition>{{source_property.text == 'count'}}</relevance-condition>
            <value-type xsi:type="Combodo-ValueType-String">
            </value-type>
        </node>
	</nodes>
</node>
XML,
				'sExpectedClass' => 'Combodo\iTop\PropertyTree\PropertyTreeException',
				'sExpectedMessage' => 'Node: dependant_property, invalid syntax in condition: Unexpected token EQ - found \'=\' at 22 in \'source_property.text == \'count\'\'',
			],

			'Unknown source in relevance condition' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<node id="RelevanceCondition" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
		<node id="dependant_property">
			<label>UI:Dependant</label>
			<relevance-condition>{{source_property.text = 'count'}}</relevance-condition>
            <value-type xsi:type="Combodo-ValueType-String">
            </value-type>
        </node>
	</nodes>
</node>
XML,
				'sExpectedClass' => 'Combodo\iTop\PropertyTree\PropertyTreeException',
				'sExpectedMessage' => 'Node: dependant_property, invalid source in condition: source_property',
			],

			'Unknown output in relevance condition' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<node id="RelevanceCondition" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
		<node id="source_property">
			<label>UI:Source</label>
            <value-type xsi:type="Combodo-ValueType-String">
            </value-type>
        </node>
		<node id="dependant_property">
			<label>UI:Dependant</label>
			<relevance-condition>{{source_property.text_output != 'count'}}</relevance-condition>
            <value-type xsi:type="Combodo-ValueType-String">
            </value-type>
        </node>
	</nodes>
</node>
XML,
				'sExpectedClass' => 'Combodo\iTop\PropertyTree\PropertyTreeException',
				'sExpectedMessage' => 'Node: dependant_property, invalid output in condition: source_property.text_output',
			],

			'Missing output or source in relevance condition' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<node id="RelevanceCondition" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
		<node id="source_property">
			<label>UI:Source</label>
            <value-type xsi:type="Combodo-ValueType-String">
            </value-type>
        </node>
		<node id="dependant_property">
			<label>UI:Dependant</label>
			<relevance-condition>{{source_property != 'count'}}</relevance-condition>
            <value-type xsi:type="Combodo-ValueType-String">
            </value-type>
        </node>
	</nodes>
</node>
XML,
				'sExpectedClass' => 'Combodo\iTop\PropertyTree\PropertyTreeException',
				'sExpectedMessage' => 'Node: dependant_property, missing output or source in condition: source_property',
			],

			'Missing value-type in node specification' => [
				'sXMLContent' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<node id="RelevanceCondition" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyTree" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
    <nodes>
		<node id="source_property">
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
