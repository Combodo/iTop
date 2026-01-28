<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

use Combodo\iTop\PropertyType\PropertyTypeDesign;
use Combodo\iTop\Service\DependencyInjection\ServiceLocator;
use Combodo\iTop\Test\UnitTest\ItopDataTestCase;

/**
 *  @copyright   Copyright (C) 2010-2026 Combodo SAS
 *  @license     http://opensource.org/licenses/AGPL-3.0
 */

class DashboardSerializerTest extends ItopDataTestCase
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
	public function testSerializeXML($normalizedValue, string $sXMLContent)
	{
		$oDOMDocument = new PropertyTypeDesign();
		$oDOMDocument->preserveWhiteSpace = false;
		$oDOMDocument->formatOutput = true;

		/** @var \Combodo\iTop\DesignElement $oRootNode */
		$oRootNode = $oDOMDocument->createElement('root');
		$oRootNode->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
		$oDOMDocument->appendChild($oRootNode);

		$oXMLSerializer = MetaModel::GetService('XMLSerializer');
		$oXMLSerializer->Serialize($normalizedValue, $oRootNode, 'DashboardGrid', 'Dashboard');

		$sActualXML = $oDOMDocument->saveXML();

		var_export($sActualXML);

		$this->AssertEqualiTopXML($sXMLContent, $sActualXML);
	}

	public function XMLSerializerProvider()
	{
		return [
			'Basic test should serialize to XML' => [
				'normalizedValue' => [
					'schema_version' => 2,
					'id'             => 'WelcomeMenuPage',
					'title'          => 'Bienvenido al Panel de Control Panel',
					'refresh'        => '60',
					'pos_dashlets'   => [
						'CUSTOM_WelcomeMenuPage_ID_row0_col0_1' => [
							'position_x' => 1,
							'position_y' => 2,
							'width'      => 3,
							'height'     => 1,
							'dashlet'    => [
								'type' => 'DashletHeaderStatic',
								'properties' => [
									'title' => 'Menu:ConfigManagementCI',
									'icon'  => '../images/icons/icons8-database.svg',
								],
							],
						],
					],
				],
				'sXMLContent' => <<<XML
<?xml version="1.0"?>
<root xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<title>Bienvenido al Panel de Control Panel</title>
	<refresh>60</refresh>
	<pos_dashlets>
		<pos_dashlet id="CUSTOM_WelcomeMenuPage_ID_row0_col0_1">
			<position_x>1</position_x>
			<position_y>2</position_y>
			<width>3</width>
			<height>1</height>
			<dashlet xsi:type="DashletHeaderStatic">
				<title>Menu:ConfigManagementCI</title>
				<icon>../images/icons/icons8-database.svg</icon>
			</dashlet>
		</pos_dashlet>
	</pos_dashlets>
</root>
XML,
					],
			];
	}
}
