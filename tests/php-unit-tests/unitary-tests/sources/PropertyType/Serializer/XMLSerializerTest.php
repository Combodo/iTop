<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\PropertyType\Compiler\PropertyTypeCompiler;
use Combodo\iTop\PropertyType\PropertyTypeDesign;
use Combodo\iTop\Service\DependencyInjection\ServiceLocator;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class XMLSerializerTest extends ItopDataTestCase
{
	/**
	 * @dataProvider XMLSerializerProvider
	 *
	 * @param $normalizedValue
	 * @param string $sPropertyTypeXML
	 * @param string $sXMLContent
	 *
	 * @return void
	 * @throws \DOMException
	 */
	public function testSerializeXML($normalizedValue, string $sPropertyTypeXML, string $sXMLContent)
	{
		ServiceLocator::GetInstance()->RegisterService('ModelReflection', new ModelReflectionRuntime());

		$oDOMDocument = new PropertyTypeDesign();
		$oDOMDocument->preserveWhiteSpace = false;
		$oDOMDocument->formatOutput = true;

		/** @var \Combodo\iTop\DesignElement $oRootNode */
		$oRootNode = $oDOMDocument->createElement('root');
		$oRootNode->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');

		$oDOMDocument->appendChild($oRootNode);

		Combodo\iTop\PropertyType\Serializer\XMLSerializer::GetInstance()->SerializeForPropertyType($normalizedValue, $oRootNode, $sPropertyTypeXML);

		$sActualXML = $oDOMDocument->saveXML();

		$this->AssertEqualiTopXML($sXMLContent, $sActualXML);
	}

	public function XMLSerializerProvider()
	{
		return [
			'Basic test should serialize to XML' => [
				'normalizedValue' => 'text',
				'sPropertyTypeXML' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="basic_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-Label">
    </definition>
</property_type>
XML,
				'sXMLContent' => <<<XML
<?xml version="1.0"?>
<root xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">text</root>
XML,
			],
			'Collection of values as CSV' => [
				'normalizedValue' => ['Contact', 'Organization'],
				'sPropertyTypeXML' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="basic_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-CollectionOfValues">
      <xml-format xsi:type="Combodo-XMLFormat-CSV"/>
      <value-type xsi:type="Combodo-ValueType-Class">
      </value-type>
    </definition>
</property_type>
XML,
				'sXMLContent' => <<<XML
<?xml version="1.0"?>
<root xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">Contact,Organization</root>
XML,
			],
			'Collection of values as id attribute' => [
				'normalizedValue' => ['Contact', 'Organization'],
				'sPropertyTypeXML' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="class_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-CollectionOfValues">
      <xml-format xsi:type="Combodo-XMLFormat-ValueAsId">
        <tag-name>item</tag-name>
	  </xml-format>
      <value-type xsi:type="Combodo-ValueType-Class">
      </value-type>
    </definition>
</property_type>
XML,
				'sXMLContent' => <<<XML
<?xml version="1.0"?>
<root xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<item id="Contact"/>
	<item id="Organization"/>
</root>
XML,
			],
			'Collection of tree as flat array' => [
				'normalizedValue' => [
					[
						'title_property' => 'title_a',
						'class_property' => 'class_a',
					],
					[
						'title_property' => 'title_b',
						'class_property' => 'class_b',
					],
				],
				'sPropertyTypeXML' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="collection_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-Collection">
      <xml-format xsi:type="Combodo-XMLFormat-FlatArray">
        <count-tag>item_count</count-tag>
        <tag-format>item_\$rank\$_\$id\$</tag-format>
	  </xml-format>
	  <prototype>
        <node id="title_property" xsi:type="Combodo-ValueType-Label">
            <label>UI:BasicTest:Prop-Title</label>
        </node>
        <node id="class_property" xsi:type="Combodo-ValueType-Class">
            <label>UI:BasicTest:Prop-Class</label>
            <categories-csv>test</categories-csv>
        </node>
	  </prototype>
    </definition>
</property_type>
XML,
				'sXMLContent' => <<<XML
<?xml version="1.0"?>
<root xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<item_count>2</item_count>
	<item_0_title_property>title_a</item_0_title_property>
	<item_0_class_property>class_a</item_0_class_property>
	<item_1_title_property>title_b</item_1_title_property>
	<item_1_class_property>class_b</item_1_class_property>
</root>
XML,
			],
			'Collection of tree as flat array in a property tree' => [
				'normalizedValue' => [
					'collection' => [
						[
							'title_property' => 'title_a',
							'class_property' => 'class_a',
						],
						[
							'title_property' => 'title_b',
							'class_property' => 'class_b',
						],
					],
				],
				'sPropertyTypeXML' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="collection_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
	<definition xsi:type="Combodo-ValueType-PropertyTree">
		<nodes>
		    <node id="collection" xsi:type="Combodo-ValueType-Collection">
		      <xml-format xsi:type="Combodo-XMLFormat-FlatArray">
		        <count-tag>item_count</count-tag>
		        <tag-format>item_\$rank\$_\$id\$</tag-format>
			  </xml-format>
			  <prototype>
		        <node id="title_property" xsi:type="Combodo-ValueType-Label">
		            <label>UI:BasicTest:Prop-Title</label>
		        </node>
		        <node id="class_property" xsi:type="Combodo-ValueType-Class">
		            <label>UI:BasicTest:Prop-Class</label>
		            <categories-csv>test</categories-csv>
		        </node>
			  </prototype>
		    </node>
	    </nodes>
    </definition>
</property_type>
XML,
				'sXMLContent' => <<<XML
<?xml version="1.0"?>
<root xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<item_count>2</item_count>
	<item_0_title_property>title_a</item_0_title_property>
	<item_0_class_property>class_a</item_0_class_property>
	<item_1_title_property>title_b</item_1_title_property>
	<item_1_class_property>class_b</item_1_class_property>
</root>
XML,
			],
			'Polymorphic tree' => [
				'normalizedValue' => [
					'type' => 'DashletHeaderStatic',
					'properties' => [
						'title' => 'Menu:ConfigManagementCI',
						'icon'  => '../images/icons/icons8-database.svg',
					],
				],
				'sPropertyTypeXML' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="property_tree_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-Polymorphic">
        <label>Dashlet</label>
        <allowed-types>
          <allowed-type>Dashlet</allowed-type>
        </allowed-types>
    </definition>
</property_type>
XML,
				'sXMLContent' => <<<XML
<?xml version="1.0"?>
<root xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="DashletHeaderStatic">
	<title>Menu:ConfigManagementCI</title>
	<icon>../images/icons/icons8-database.svg</icon>
</root>
XML,
			],
			'Collection of tree with id' => [
				'normalizedValue' => [
					'a' => [
						'title_property' => 'title_a',
						'class_property' => 'class_a',
					],
					'b' => [
						'title_property' => 'title_b',
						'class_property' => 'class_b',
					],
				],
				'sPropertyTypeXML' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="collection_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-Collection">
      <xml-format xsi:type="Combodo-XMLFormat-CollectionWithId">
        <tag-name>item</tag-name>
      </xml-format>
	  <prototype>
        <node id="title_property" xsi:type="Combodo-ValueType-Label">
            <label>UI:BasicTest:Prop-Title</label>
        </node>
        <node id="class_property" xsi:type="Combodo-ValueType-Class">
            <label>UI:BasicTest:Prop-Class</label>
            <categories-csv>test</categories-csv>
        </node>
	  </prototype>
    </definition>
</property_type>
XML,
				'sXMLContent' => <<<XML
<?xml version="1.0"?>
<root xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<item id="a">
		<title_property>title_a</title_property>
		<class_property>class_a</class_property>
	</item>
	<item id="b">
		<title_property>title_b</title_property>
		<class_property>class_b</class_property>
	</item>
</root>
XML,
			],
			'Property tree' => [
				'normalizedValue' => [
					'title_property' => 'title',
					'sub_tree' => [
						'sub_tree_property' => 'my_class',
					],
					'items' => [
						'a' => [
							'title_property' => 'title_a',
							'class_property' => 'class_a',
						],
						'b' => [
							'title_property' => 'title_b',
							'class_property' => 'class_b',
						],
					],
					'boolean_property' => true,
					'integer_property' => 3,
				],
				'sPropertyTypeXML' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="property_tree_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
	<definition xsi:type="Combodo-ValueType-PropertyTree">
		<nodes>
			<node id="title_property" xsi:type="Combodo-ValueType-Label">
			<label>UI:BasicTest:Prop-Title</label>
			</node>
			<node id="sub_tree" xsi:type="Combodo-ValueType-PropertyTree">
				<label>My sub tree</label>
				<nodes>
					<node id="sub_tree_property" xsi:type="Combodo-ValueType-Class">
						<label>UI:BasicTest:Prop-Class</label>
						<categories-csv>test</categories-csv>
					</node>
				</nodes>
			</node>
			<node id="items" xsi:type="Combodo-ValueType-Collection">
				<xml-format xsi:type="Combodo-XMLFormat-CollectionWithId">
					<tag-name>item</tag-name>
				</xml-format>
				<prototype>
					<node id="title_property" xsi:type="Combodo-ValueType-Label">
						<label>UI:BasicTest:Prop-Title</label>
					</node>
					<node id="class_property" xsi:type="Combodo-ValueType-Class">
						<label>UI:BasicTest:Prop-Class</label>
						<categories-csv>test</categories-csv>
					</node>
				</prototype>
			</node>
			<node id="boolean_property" xsi:type="Combodo-ValueType-Boolean">
				<label>Boolean</label>
			</node>
			<node id="integer_property" xsi:type="Combodo-ValueType-Integer">
				<label>Integer</label>
			</node>
		</nodes>
	</definition>
</property_type>
XML,
				'sXMLContent' => <<<XML
<?xml version="1.0"?>
<root xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<title_property>title</title_property>
	<sub_tree>
      <sub_tree_property>my_class</sub_tree_property>
	</sub_tree>
	<items>
		<item id="a">
			<title_property>title_a</title_property>
			<class_property>class_a</class_property>
		</item>
		<item id="b">
			<title_property>title_b</title_property>
			<class_property>class_b</class_property>
		</item>
	</items>
	<boolean_property>true</boolean_property>
	<integer_property>3</integer_property>
</root>
XML,
			],
		];
	}

	/**
	 * @dataProvider XMLSerializerProvider
	 *
	 * @param $sInputXMLContent
	 * @param string $sPropertyTypeXML
	 * @param $expectedValue
	 *
	 * @return void
	 */
	public function testUnserializeXML($normalizedValue, string $sPropertyTypeXML, string $sXMLContent)
	{
		ServiceLocator::GetInstance()->RegisterService('ModelReflection', new ModelReflectionRuntime());

		$oDoc = new PropertyTypeDesign();
		$oDoc->loadXML($sXMLContent);
		/** @var \Combodo\iTop\DesignElement $oRoot */
		$oRoot = $oDoc->firstChild;

		$aActualValue = Combodo\iTop\PropertyType\Serializer\XMLSerializer::GetInstance()->DeserializeForPropertyType($oRoot, $sPropertyTypeXML);

		$this->assertEquals($normalizedValue, $aActualValue);
	}
}
