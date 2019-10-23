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
}
