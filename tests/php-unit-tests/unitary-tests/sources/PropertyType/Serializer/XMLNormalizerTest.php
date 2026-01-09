<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\PropertyType\Compiler\PropertyTypeCompiler;
use Combodo\iTop\PropertyType\PropertyTypeDesign;
use Combodo\iTop\Service\DependencyInjection\ServiceLocator;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

class XMLNormalizerTest extends ItopDataTestCase
{
	/**
	 * @dataProvider XMLNormalizerProvider
	 *
	 * @param $denormalizedValue
	 * @param string $sPropertyTypeXML
	 * @param string $normalizedValue
	 *
	 * @return void
	 * @throws \DOMException
	 */
	public function testNormalizeXML($denormalizedValue, string $sPropertyTypeXML, $normalizedValue)
	{
		ServiceLocator::GetInstance()->RegisterService('ModelReflection', new ModelReflectionRuntime());

		$oDOMDocument = new PropertyTypeDesign();
		$oDOMDocument->preserveWhiteSpace = false;
		$oDOMDocument->formatOutput = true;

		/** @var \Combodo\iTop\DesignElement $oRootNode */
		$oRootNode = $oDOMDocument->createElement('root');
		$oDOMDocument->appendChild($oRootNode);

		$actualValue = Combodo\iTop\PropertyType\Serializer\XMLNormalizer::GetInstance()->NormalizeForPropertyType($denormalizedValue, $sPropertyTypeXML);

		$this->assertEquals($normalizedValue, $actualValue);
	}

	public function XMLNormalizerProvider()
	{
		return [
			'Basic test should serialize to XML' => [
				'denormalizedValue' => 'text',
				'sPropertyTypeXML' => <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<property_type id="basic_test" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Combodo-PropertyType" xsi:noNamespaceSchemaLocation = "https://www.combodo.com/itop-schema/3.3">
	<extends>Dashlet</extends>
    <definition xsi:type="Combodo-ValueType-Label">
    </definition>
</property_type>
XML,
				'normalizedValue' => 'text',
			],
			'Collection of values as CSV' => [
				'denormalizedValue' => ['Contact', 'Organization'],
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
				'normalizedValue' => ['Contact', 'Organization'],
			],
			'Collection of values as id attribute' => [
				'denormalizedValue' => ['Contact', 'Organization'],
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
				'normalizedValue' => ['Contact', 'Organization'],
			],
			'Collection of tree as flat array' => [
				'denormalizedValue' => [
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
				'normalizedValue' => [
					'item_count' => 2,
					'item_0_title_property' => 'title_a',
					'item_0_class_property' => 'class_a',
					'item_1_title_property' => 'title_b',
					'item_1_class_property' => 'class_b',
				],
			],
			'Property tree' => [
				'denormalizedValue' => ['title_property' => 'title', 'class_property' => 'class'],
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
				'normalizedValue' => ['title_property' => 'title', 'class_property' => 'class'],
			],
		];
	}

	/**
	 * @dataProvider XMLNormalizerProvider
	 *
	 * @param $sInputXMLContent
	 * @param string $sPropertyTypeXML
	 * @param $expectedValue
	 *
	 * @return void
	 */
	public function testDenormalizeXML($denormalizedValue, string $sPropertyTypeXML, $normalizedValue)
	{
		ServiceLocator::GetInstance()->RegisterService('ModelReflection', new ModelReflectionRuntime());

		$aActualValue = Combodo\iTop\PropertyType\Serializer\XMLNormalizer::GetInstance()->DenormalizeForPropertyType($normalizedValue, $sPropertyTypeXML);

		$this->assertEquals($denormalizedValue, $aActualValue);
	}
}
