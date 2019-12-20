<?php


namespace Combodo\iTop\Test\UnitTest\Core;

use Combodo\iTop\Test\UnitTest\ItopDataTestCase;
use MetaModel;

/**
 * Class MetaModelTest
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 * @backupGlobals disabled
 *
 * @since 2.6
 * @package Combodo\iTop\Test\UnitTest\Core
 */
class MetaModelTest extends ItopDataTestCase
{
    protected static $iDefaultUserOrgId = 1;
    protected static $iDefaultUserCallerId = 1;
    protected static $sDefaultUserRequestTitle = 'Unit test title';
    protected static $sDefaultUserRequestDescription = 'Unit test description';

	protected function setUp()
	{
		parent::setUp();
		require_once APPROOT.'/core/metamodel.class.php';
		require_once APPROOT.'/core/coreexception.class.inc.php';
	}

    /**
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
                'description' => static::$sDefaultUserRequestDescription,
            )
        );

        $aParams['this->object()'] = $oUserRequest;

        $sGeneratedOutput = MetaModel::ApplyParams($sInput, $aParams);

		$this->assertEquals($sExpectedOutput, $sGeneratedOutput, "ApplyParams test returned $sGeneratedOutput");
	}

	public function ApplyParamsProvider()
	{
	    $sTitle = static::$sDefaultUserRequestTitle;

	    $aParams = array();

		return array(
		    'Object string attribute (text format)' => array(
		        'Title: $this->title$',
                $aParams,
                'Title: '.$sTitle,
            ),
            'Object string attribute (html format)' => array(
                'Title: <p>$this-&gt;title$</p>',
                $aParams,
                'Title: <p>'.$sTitle.'</p>',
            ),
            'Object string attribute urlencoded (html format)' => array(
                'Title: <a href="http://foo.bar/%24this-&gt;title%24">Hyperlink</a>',
                $aParams,
                'Title: <a href="http://foo.bar/'.$sTitle.'">Hyperlink</a>',
            ),
		);
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
		$m_PluginManager = new \PluginManager($m_aExtensionClassNames, $m_aExtensionClasses, $mPluginInstanciationManager);

		//warning: called twice on purpose
		$m_PluginManager->EnumPlugins($interface, $sFilterInstanceOf);
		$pluginInstances = $m_PluginManager->EnumPlugins($interface, $sFilterInstanceOf);

		$this->assertCount(sizeof($expectedResults), $pluginInstances);
		foreach($pluginInstances as $pluginInstance)
		{
			if ($sFilterInstanceOf!==null)
			{
				$this->assertTrue($pluginInstance instanceof $sFilterInstanceOf);
			}
		}
		$index=0;
		foreach($expectedResults as $expectedInterface)
		{
			$this->assertTrue(is_a($pluginInstances[$index], $expectedInterface));
			$index++;
		}
	}

	public function enumPluginsProvider(){
		$aInterfaces = [
			"empty conf" => [ 0, [], [], [], 'Wizzard'],
			"simple instance retrieval" => [ 1, [Gryffindor::class], [ 'Wizzard' => [ Gryffindor::class]], [], 'Wizzard'],
			"check instanceof parameter" => [ 1, [Gryffindor::class, Slytherin::class], [ 'Wizzard' => [ Gryffindor::class, Slytherin::class]], [], 'Wizzard'],
			"try to retrieve a non instanciable object" => [ 1, [Gryffindor::class], [ 'Wizzard' => [ Gryffindor::class, Muggle::class]], [], 'Wizzard', Gryffindor::class ],
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
		$m_PluginManager = new \PluginManager($m_aExtensionClassNames, $m_aExtensionClasses, $mPluginInstanciationManager);

		//warning: called twice on purpose
		$m_PluginManager->GetPlugins($interface, $className);
		$pluginInstance = $m_PluginManager->GetPlugins($interface, $className);

		if (sizeof($expectedResults)==0)
		{
			$this->assertNull($pluginInstance);
			return;
		}

		$this->assertTrue($pluginInstance instanceof $className);
		$this->assertTrue(is_a($pluginInstance, $expectedResults[0]));
	}

	public function getPluginsProvider(){
		$aInterfaces = [
			"empty conf" => [ 0, [], [], [], 'Wizzard', Gryffindor::class],
			"simple instance retrieval" => [ 1, [Gryffindor::class], [ 'Wizzard' => [ Gryffindor::class]], [], 'Wizzard', Gryffindor::class],
			"check instanceof parameter" => [ 1, [Gryffindor::class], [ 'Wizzard' => [ Gryffindor::class, Slytherin::class]], [], 'Wizzard', Gryffindor::class],
			"try to retrieve a non instanciable object" => [ 1, [Gryffindor::class], [ 'Wizzard' => [ Gryffindor::class, Muggle::class]], [], 'Wizzard', Gryffindor::class ],
		];
		return $aInterfaces;
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