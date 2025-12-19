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
	 * @param $inputContent
	 * @param string $sPropertyTypeXML
	 * @param string $sExpectedXMLContent
	 *
	 * @return void
	 * @throws \DOMException
	 */
	public function testSerializeXML($inputContent, string $sPropertyTypeXML, string $sExpectedXMLContent)
	{
		ServiceLocator::GetInstance()->RegisterService('ModelReflection', new ModelReflectionRuntime());

		$oDOMDocument = new PropertyTypeDesign();
		$oDOMDocument->preserveWhiteSpace = false;
		$oDOMDocument->formatOutput = true;

		/** @var \Combodo\iTop\DesignElement $oRootNode */
		$oRootNode = $oDOMDocument->createElement('root');
		$oDOMDocument->appendChild($oRootNode);

		Combodo\iTop\PropertyType\Serializer\XMLSerializer::GetInstance()->SerializeForPropertyType($inputContent, $oRootNode, $sPropertyTypeXML);

		$sActualXML = $oDOMDocument->saveXML();

		$this->AssertEqualiTopXML($sExpectedXMLContent, $sActualXML);
	}

	public function XMLSerializerProvider()
	{
		return [
			'Basic test should serialize to XML' => [
				'inputContent' => 'text',
				'sPropertyTypeXML' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="basic_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-Label">
    </definition>
</property_type>
XML,
				'sExpectedXMLContent' => <<<XML
<?xml version="1.0"?>
<root>text</root>
XML,
			],
			'Collection of values as CSV' => [
				'inputContent' => ['Contact', 'Organization'],
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
				'sExpectedXMLContent' => <<<XML
<?xml version="1.0"?>
<root>Contact,Organization</root>
XML,
			],
			'Collection of values as id attribute' => [
				'inputContent' => ['Contact', 'Organization'],
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
				'sExpectedXMLContent' => <<<XML
<?xml version="1.0"?>
<root>
	<item id="Contact"/>
	<item id="Organization"/>
</root>
XML,
			],
			'Collection of tree as flat array' => [
				'inputContent' => [
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
				'sExpectedXMLContent' => <<<XML
<?xml version="1.0"?>
<root>
	<item_count>2</item_count>
	<item_0_title_property>title_a</item_0_title_property>
	<item_0_class_property>class_a</item_0_class_property>
	<item_1_title_property>title_b</item_1_title_property>
	<item_1_class_property>class_b</item_1_class_property>
</root>
XML,
			],
			'Property tree' => [
				'inputContent' => ['title_property' => 'title', 'class_property' => 'class'],
				'sPropertyTypeXML' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="property_tree_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
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
				'sExpectedXMLContent' => <<<XML
<?xml version="1.0"?>
<root>
	<title_property>title</title_property>
	<class_property>class</class_property>
</root>
XML,
			],
		];
	}

	/**
	 * @dataProvider XMLUnserializerProvider
	 *
	 * @param $sInputXMLContent
	 * @param string $sPropertyTypeXML
	 * @param $expectedValue
	 *
	 * @return void
	 */
	public function testUnserializeXML($sInputXMLContent, string $sPropertyTypeXML, $expectedValue)
	{
		ServiceLocator::GetInstance()->RegisterService('ModelReflection', new ModelReflectionRuntime());

		$oDoc = new PropertyTypeDesign();
		$oDoc->loadXML($sInputXMLContent);
		/** @var \Combodo\iTop\DesignElement $oRoot */
		$oRoot = $oDoc->firstChild;

		$aActualValue = Combodo\iTop\PropertyType\Serializer\XMLSerializer::GetInstance()->UnserializeForPropertyType($oRoot, $sPropertyTypeXML);

		$this->assertEquals($expectedValue, $aActualValue);
	}

	public function XMLUnserializerProvider()
	{
		return [
			'Basic test should unserialize from XML' => [
				'sInputXMLContent' => <<<XML
<?xml version="1.0"?>
<root>text</root>
XML,
				'sPropertyTypeXML' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="basic_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-Label">
    </definition>
</property_type>
XML,
				'expectedValue' => 'text',
			],
			'Collection of values as CSV' => [
				'sInputXMLContent' => <<<XML
<?xml version="1.0"?>
<root>Contact,Organization</root>
XML,
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
				'expectedValue' => ['Contact', 'Organization'],
			],
			'Collection of values as id attribute' => [
				'sInputXMLContent' => <<<XML
<?xml version="1.0"?>
<root>
	<item id="Contact"/>
	<item id="Organization"/>
</root>
XML,
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
				'expectedValue' => ['Contact', 'Organization'],
			],
			'Collection of tree as flat array' => [
				'sInputXMLContent' => <<<XML
<?xml version="1.0"?>
<root>
	<item_count>2</item_count>
	<item_0_title_property>title_a</item_0_title_property>
	<item_0_class_property>class_a</item_0_class_property>
	<item_1_title_property>title_b</item_1_title_property>
	<item_1_class_property>class_b</item_1_class_property>
</root>
XML,
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
				'expectedValue' => [
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
			'Property tree' => [
				'sInputXMLContent' => <<<XML
<?xml version="1.0"?>
<root>
	<title_property>title</title_property>
	<class_property>class</class_property>
</root>
XML,
				'sPropertyTypeXML' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="property_tree_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
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
				'expectedValue' => ['title_property' => 'title', 'class_property' => 'class'],
			],
			];
	}

}
