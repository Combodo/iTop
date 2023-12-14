<?php


namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use CoreException;
use MetaModel;

/**
 * Class MetaModelTest
 *
 * @since 2.6.0
 * @package Combodo\iTop\Test\UnitTest\Core
 */
class MetaModelTest extends ItopDataTestCase
{
    protected static $iDefaultUserOrgId = 1;
    protected static $iDefaultUserCallerId = 1;
    protected static $sDefaultUserRequestTitle = 'Unit test title';
    protected static $sDefaultUserRequestDescription = 'Unit test description';
    protected static $sDefaultUserRequestDescriptionAsHtml = '<p>Unit test description</p>';

	protected function setUp(): void
	{
		parent::setUp();
		$this->RequireOnceItopFile('/core/metamodel.class.php');
	}

	protected function tearDown(): void
	{
		$this->InvokeNonPublicStaticMethod('PluginManager', 'ResetPlugins');
		parent::tearDown();
	}

	/**
	 * @covers MetaModel::GetObjectByName
	 * @return void
	 * @throws \CoreException
	 */
	public function testGetFinalClassName()
	{
		// Standalone classes
		$this->assertEquals('Organization', MetaModel::GetFinalClassName('Organization', 1), 'Should work with standalone classes');
		$this->assertEquals('SynchroDataSource', MetaModel::GetFinalClassName('SynchroDataSource', 1), 'Should work with standalone classes');

		// 2 levels hierarchy
		$this->assertEquals('Person', MetaModel::GetFinalClassName('Contact', 1));
		$this->assertEquals('Person', MetaModel::GetFinalClassName('Person', 1));

		// multi-level hierarchy
		$oServer1 = MetaModel::GetObjectByName('Server', 'Server1');
		$sServer1Id = $oServer1->GetKey();
		foreach (MetaModel::EnumParentClasses('Server',ENUM_PARENT_CLASSES_ALL) as $sClass) {
			$this->assertEquals('Server', MetaModel::GetFinalClassName($sClass, $sServer1Id), 'Should return Server for all the classes in the hierarchy');
		}
	}

	/**
     * @group itopRequestMgmt
     * @covers       MetaModel::ApplyParams()
     * @dataProvider ApplyParamsProvider
     *
     * @param string $sInput
     * @param array $aParams
     * @param string $sExpectedOutput
     *
     * @throws \Exception
     */
	public function testApplyParams($sInput, $aParams, $sExpectedOutput)
	{
        $oUserRequest = $this->createObject(
            'UserRequest',
            array(
                'org_id' => static::$iDefaultUserOrgId,
                'caller_id' => static::$iDefaultUserCallerId,
                'title' => static::$sDefaultUserRequestTitle,
                'description' => static::$sDefaultUserRequestDescriptionAsHtml,
            )
        );

        $aParams['this->object()'] = $oUserRequest;

        $sGeneratedOutput = MetaModel::ApplyParams($sInput, $aParams);

		$this->assertEquals($sExpectedOutput, $sGeneratedOutput, "ApplyParams test returned $sGeneratedOutput");
	}

	public function ApplyParamsProvider()
	{
	    $sTitle = static::$sDefaultUserRequestTitle;

	    $aParams = [
			'simple' => 'I am simple',
		    'foo->bar' => 'I am bar', // N°2889 - Placeholder with an arrow that is not an object
	    ];

		return [
			'Simple placeholder' => [
				'Result: $simple$',
				$aParams,
				'Result: I am simple',
			],
			'Placeholder with an arrow but that is not an object (text format)' => [
				'Result: $foo->bar$',
				$aParams,
				'Result: I am bar',
			],
			'Placeholder with an arrow but that is not an object (html format)' => [
				'Result: $foo-&gt;bar$',
				$aParams,
				'Result: I am bar',
			],
			'Placeholder with an arrow url-encoded but that is not an object (html format)' => [
				'Result: <a href="http://foo.bar/%24foo-&gt;bar%24">Hyperlink</a>',
				$aParams,
				'Result: <a href="http://foo.bar/I am bar">Hyperlink</a>',
			],
			'Placeholder for an object string attribute (text format)' => [
		        'Title: $this->title$',
                $aParams,
                'Title: '.$sTitle,
			],
			'Placeholder for an object string attribute (html format)' => [
                'Title: <p>$this-&gt;title$</p>',
                $aParams,
                'Title: <p>'.$sTitle.'</p>',
			],
			'Placeholder for an object string attribute url-encoded (html format)' => [
                'Title: <a href="http://foo.bar/%24this-&gt;title%24">Hyperlink</a>',
                $aParams,
                'Title: <a href="http://foo.bar/'.$sTitle.'">Hyperlink</a>',
			],
			'Placeholder for an object HTML attribute as its default format' => [
				'$this->description$',
				$aParams,
				static::$sDefaultUserRequestDescriptionAsHtml,
			],
			'Placeholder for an object HTML attribute as plain text' => [
				'$this->text(description)$',
				$aParams,
				static::$sDefaultUserRequestDescription,
			],
			'Placeholder for an object HTML attribute as HTML' => [
				'$this->html(description)$',
				$aParams,
				'<div class="HTML ibo-is-html-content" ><p>Unit test description</p></div>', // As the AttributeText::GetForHTML() called by AttributeDefinition::GetForTemplate() adds this markup, we have to mock it here as well
			],
		];
	}

	/**
	 * @covers       MetaModel::GetDependentAttributes()
	 * @dataProvider GetDependentAttributesProvider
	 *
	 * @param string $sClass
	 * @param string $sAttCode
	 * @param array $aExpectedAttCodes
	 *
	 * @throws \Exception
	 */
	public function testGetDependentAttributes($sClass, $sAttCode, array $aExpectedAttCodes)
	{
		$aRes = MetaModel::GetDependentAttributes($sClass, $sAttCode);
		// The order doesn't matter
		sort($aRes);
		sort($aExpectedAttCodes);
		static::assertEquals($aExpectedAttCodes, $aRes);
	}

	public function GetDependentAttributesProvider()
	{
		$aRawCases = array(
			array('Person', 'org_id', array('location_id', 'org_name', 'org_id_friendlyname', 'org_id_obsolescence_flag')),
			array('Person', 'name', array('friendlyname')),
			array('Person', 'status', array('obsolescence_flag')),
		);
		$aRet = array();
		foreach ($aRawCases as $i => $aData)
		{
			$aRet[$aData[0].'::'.$aData[1]] = $aData;
		}
		return $aRet;
	}

	/**
	 * @covers       MetaModel::GetPrerequisiteAttributes()
	 * @dataProvider GetPrerequisiteAttributesProvider
	 *
	 * @param string $sClass
	 * @param string $sAttCode
	 * @param array $aExpectedAttCodes
	 *
	 * @throws \Exception
	 */
	public function testGetPrerequisiteAttributes($sClass, $sAttCode, array $aExpectedAttCodes)
	{
		$aRes = MetaModel::GetPrerequisiteAttributes($sClass, $sAttCode);
		// The order doesn't matter
		sort($aRes);
		sort($aExpectedAttCodes);
		static::assertEquals($aRes, $aExpectedAttCodes);
	}

	public function GetPrerequisiteAttributesProvider()
	{
		$aRawCases = array(
			array('Person', 'friendlyname', array('name', 'first_name')),
			array('Person', 'obsolescence_flag', array('status')),
			array('Person', 'org_id_friendlyname', array('org_id')),
			array('Person', 'org_id', array()),
			array('Person', 'org_name', array('org_id')),
		);
		$aRet = array();
		foreach ($aRawCases as $i => $aData)
		{
			$aRet[$aData[0].'::'.$aData[1]] = $aData;
		}
		return $aRet;
	}

	/**
	 * To be removed as soon as the dependencies on external fields are obsoleted
	 * @Group Integration
	 */
	public function testManualVersusAutomaticDependenciesOnExtKeys()
	{
		foreach (\MetaModel::GetClasses() as $sClass)
		{
			if (\MetaModel::IsAbstract($sClass)) continue;

			foreach (\MetaModel::ListAttributeDefs($sClass) as $sAttCode => $oAttDef)
			{
				if (\MetaModel::GetAttributeOrigin($sClass, $sAttCode) != $sClass) continue;
				if (!$oAttDef instanceof \AttributeExternalKey) continue;

				$aManual = $oAttDef->Get('depends_on');
				$aAuto = \MetaModel::GetPrerequisiteAttributes($sClass, $sAttCode);
				// The order doesn't matter
				sort($aAuto);
				sort($aManual);
				static::assertEquals($aManual, $aAuto, "Class: $sClass, Attribute: $sAttCode");
			}
		}
	}

	/**
	 * @dataProvider enumPluginsProvider
	 *
	 * @param $expectedResults
	 * @param $m_aExtensionClassNames
	 * @param $m_aExtensionClasses
	 * @param $interface
	 * @param null $sFilterInstanceOf
	 */
	public function testEnumPlugins($expectedInstanciationCalls, $expectedResults, $m_aExtensionClassNames, $m_aExtensionClasses, $interface, $sFilterInstanceOf=null)
	{
		$pluginInstanciationManager = new \PluginInstanciationManager();
		$res = $pluginInstanciationManager->InstantiatePlugins($m_aExtensionClassNames, $interface);

		$mPluginInstanciationManager = $this->createMock(\PluginInstanciationManager::class);
		$mPluginInstanciationManager->expects($this->exactly($expectedInstanciationCalls))
			->method('InstantiatePlugins')
			->willReturn($res);
		$m_PluginManager = new \PluginManager($m_aExtensionClassNames, $mPluginInstanciationManager);

		//warning: called twice on purpose
		$m_PluginManager->EnumPlugins($interface, $sFilterInstanceOf);
		$pluginInstances = $m_PluginManager->EnumPlugins($interface, $sFilterInstanceOf);

		$this->assertCount(sizeof($expectedResults), $pluginInstances);
		foreach ($pluginInstances as $pluginInstance) {
			if ($sFilterInstanceOf !== null) {
				$this->assertTrue($pluginInstance instanceof $sFilterInstanceOf);
			}
		}
		$index = 0;
		foreach ($expectedResults as $expectedPHPClass => $expectedInterface) {
			$this->assertTrue(is_a($pluginInstances[$expectedPHPClass], $expectedInterface));
			$index++;
		}
	}

	public function enumPluginsProvider(){
		$aInterfaces = [
			"empty conf" => [0, [], [], [], 'Wizzard'],
			"simple instance retrieval" => [1, [Gryffindor::class => Gryffindor::class], ['Wizzard' => [Gryffindor::class]], [], 'Wizzard'],
			"check instanceof parameter" => [1, [Gryffindor::class => Gryffindor::class, Slytherin::class => Slytherin::class], ['Wizzard' => [Gryffindor::class, Slytherin::class]], [], 'Wizzard'],
			"try to retrieve a non instanciable object" => [1, [Gryffindor::class => Gryffindor::class], ['Wizzard' => [Gryffindor::class, Muggle::class]], [], 'Wizzard', Gryffindor::class],
		];
		return $aInterfaces;
	}

	/**
	 * @dataProvider getPluginsProvider
	 *
	 * @param $expectedInstanciationCalls
	 * @param $expectedResults
	 * @param $m_aExtensionClassNames
	 * @param $m_aExtensionClasses
	 * @param $interface
	 * @param $className
	 */
	public function testGetPlugins($expectedInstanciationCalls, $expectedResults, $m_aExtensionClassNames, $m_aExtensionClasses, $interface, $className)
	{
		$pluginInstanciationManager = new \PluginInstanciationManager();
		$res = $pluginInstanciationManager->InstantiatePlugins($m_aExtensionClassNames, $interface);

		$mPluginInstanciationManager = $this->createMock(\PluginInstanciationManager::class);
		$mPluginInstanciationManager->expects($this->exactly($expectedInstanciationCalls))
			->method('InstantiatePlugins')
			->willReturn($res);
		$m_PluginManager = new \PluginManager($m_aExtensionClassNames, $mPluginInstanciationManager);

		//warning: called twice on purpose
		$m_PluginManager->GetPlugins($interface, $className);
		$pluginInstance = $m_PluginManager->GetPlugins($interface, $className);

		if (sizeof($expectedResults) == 0) {
			$this->assertNull($pluginInstance);
			return;
		}

		$this->assertTrue($pluginInstance instanceof $className);
		$this->assertTrue(is_a($pluginInstance, $expectedResults[0]));
	}

	public function getPluginsProvider()
	{
		$aInterfaces = [
			"empty conf" => [0, [], [], [], 'Wizzard', Gryffindor::class],
			"simple instance retrieval" => [1, [Gryffindor::class], ['Wizzard' => [Gryffindor::class]], [], 'Wizzard', Gryffindor::class],
			"check instanceof parameter" => [1, [Gryffindor::class], ['Wizzard' => [Gryffindor::class, Slytherin::class]], [], 'Wizzard', Gryffindor::class],
			"try to retrieve a non instanciable object" => [1, [Gryffindor::class], ['Wizzard' => [Gryffindor::class, Muggle::class]], [], 'Wizzard', Gryffindor::class],
		];
		return $aInterfaces;
	}

	/**
	 * @group itopRequestMgmt
	 * @dataProvider GetClassStyleProvider
	 */
	public function testGetClassStyle($sClass, $sAwaitedCSSClass, $sAwaitedCSSClassAlt, $sAwaitedDecorationClasses, $sAwaitedIconRelPath)
	{
		$oStyle = MetaModel::GetClassStyle($sClass);

		if (is_null($sAwaitedCSSClass) && is_null($sAwaitedIconRelPath)) {
			self::assertNull($oStyle);
			return;
		}

		self::assertInstanceOf('ormStyle', $oStyle);

		self::assertEquals($sAwaitedCSSClass, $oStyle->GetStyleClass());
		self::assertEquals($sAwaitedCSSClassAlt, $oStyle->GetAltStyleClass());
		self::assertEquals($sAwaitedDecorationClasses, $oStyle->GetDecorationClasses());
		self::assertEquals($sAwaitedIconRelPath, $oStyle->GetIconAsRelPath());
	}

	public function GetClassStyleProvider()
	{
		return [
			'Class with no color, only icon' => ['Organization', null, null, null, 'itop-structure/../../images/icons/icons8-organization.svg'],
			'Class with colors and icon' => ['Contact', 'ibo-dm-class--Contact', 'ibo-dm-class-alt--Contact', null, 'itop-structure/../../images/icons/icons8-customer.svg'],
		];
	}

	/**
	 * @group itopRequestMgmt
	 * @dataProvider GetEnumStyleProvider
	 */
	public function testGetEnumStyle($sClass, $sAttCode, $sValue, $sAwaitedCSSClass, $sAwaitedCSSClassAlt, $sAwaitedDecorationClasses, $sAwaitedIconRelPath)
	{
		$oStyle = MetaModel::GetEnumStyle($sClass, $sAttCode, $sValue);

		if (is_null($sAwaitedCSSClass)) {
			self::assertNull($oStyle);
			return;
		}

		self::assertInstanceOf('ormStyle', $oStyle);

		self::assertEquals($sAwaitedCSSClass, $oStyle->GetStyleClass());
		self::assertEquals($sAwaitedCSSClassAlt, $oStyle->GetAltStyleClass());
	}

	public function GetEnumStyleProvider()
	{
		return [
			'status-new' => ['UserRequest', 'status', 'new', 'ibo-dm-enum--UserRequest-status-new', 'ibo-dm-enum-alt--UserRequest-status-new', null, null],
			'status-default' => ['UserRequest', 'status', '', 'ibo-dm-enum--UserRequest-status', 'ibo-dm-enum-alt--UserRequest-status', null, null],
			'urgency' => ['UserRequest', 'origin', 'mail', null, null, null, null],
		];
	}

	public function testGetEnumStyleException()
	{
		try {
			MetaModel::GetEnumStyle('Contact', 'name', '');
		} catch (CoreException $e) {
			self::assertStringContainsString('AttributeEnum', $e->getMessage());
			return;
		}

		// Should not get here
		assertTrue(false);
	}

	/**
	 * @covers \MetaModel::IsLinkClass
	 * @dataProvider GetIsLinkClassProvider
	 *
	 * @param string $sClass Class to test
	 * @param bool   $bExpectedIsLink Expected result
	 */
	public function testIsLinkClass(string $sClass, bool $bExpectedIsLink)
	{
		$bIsLink = MetaModel::IsLinkClass($sClass);

		$this->assertEquals($bExpectedIsLink, $bIsLink, 'Class "'.$sClass.'" was excepted to be '.($bExpectedIsLink ? '' : 'NOT ').'a link class.');
	}

	public function GetIsLinkClassProvider(): array
	{
		return [
			['Person', false],
			['lnkPersonToTeam', true],
		];
	}

	/**
	 * @covers       \MetaModel::IsObjectInDB
	 * @dataProvider IsObjectInDBProvider
	 *
	 * @param int $iKeyOffset Offset to apply on the key of the test object. This is necessary to test an object that doesn't exist yet in any DB as we can't know what is the last existing object key.
	 * @param $bExpectedResult
	 *
	 * @throws \CoreException
	 * @throws \MySQLException
	 * @throws \MySQLQueryHasNoResultException
	 */
	public function testIsObjectInDB(int $iKeyOffset, $bExpectedResult)
	{
		$oPerson = $this->CreatePerson(1, 1);
		$sClass = get_class($oPerson);
		$iKey = $oPerson->GetKey() + $iKeyOffset;

		$bTestResult = MetaModel::IsObjectInDB($sClass, $iKey);
		$this->assertEquals($bTestResult, $bExpectedResult);
	}

	public function IsObjectInDBProvider(): array
	{
		return [
			'Existing person' => [0, true],
			'Non existing person' => [10, false],
		];
	}
}

abstract class Wizzard
{

	/**
	 * Wizzard constructor.
	 */
	public function __construct()
	{
	}
}

class Gryffindor extends Wizzard
{

}
class Hufflepuff extends Wizzard
{

}
class Ravenclaw extends Wizzard
{

}

class Slytherin extends Wizzard
{

}

class Muggle
{

}
