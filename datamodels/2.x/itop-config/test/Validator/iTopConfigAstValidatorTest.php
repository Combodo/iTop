<?php
/**
 * Created by Bruno DA SILVA, working for Combodo
 * Date: 31/12/2019
 * Time: 12:31
 */

namespace Combodo\iTop\Config\Test\Validator;

use Combodo\iTop\Config\Validator\iTopConfigAstValidator;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use PhpParser\Node;
use PhpParser\PrettyPrinter\Standard;

class iTopConfigAstValidatorTest extends ItopTestCase
{

	public function setUp()
	{
		parent::setUp();

		require_once __DIR__.'/../../src/Validator/ConfigNodesVisitor.php';
		require_once __DIR__.'/../../src/Validator/iTopConfigAstValidator.php';
	}


	/**
	 * @dataProvider InvalidDataProvider
	 * @param $sConf
	 *
	 * @throws \Exception
	 */
	public function testInvalid($sConf)
	{
		$oiTopConfigValidator = new iTopConfigAstValidator();
		$this->expectException(\Exception::class);
		try{
			$oiTopConfigValidator->validate($sConf);
		}catch (\Exception $e)
		{
			$this->assertStringStartsWith('Invalid configuration:', $e->getMessage());
			throw $e;
		}
	}


	public function InvalidDataProvider()
	{
		return array(
			'invalid PHP' => array(
				'sConf' => '<?php fiction Method(){}'
			),
			'function call' => array(
				'sConf' => '<?php FunctionCall();'
			),
			'function declaration' => array(
				'sConf' => '<?php function foo() {};'
			),
			'class instantiation' => array(
				'sConf' => '<?php new Class {};'
			),
			'Class declaration' => array(
				'sConf' => '<?php class foo {};'
			),
			'echo' => array(
				'sConf' => '<?php echo "toto"; ?>'
			),
		);
	}


	/**
	 * @dataProvider ValidDataProvider
	 * @doesNotPerformAssertions
	 *
	 * @param $sConf
	 *
	 * @throws \Exception
	 */
	public function testValid($sConf)
	{
		$oiTopConfigValidator = new iTopConfigAstValidator();

		$oiTopConfigValidator->validate($sConf);
	}

	public function ValidDataProvider()
	{
		return array(
			'simple code' => array(
				'sConf' => '<?php $var = array("toto"); ?>'
			),
			'class constant' => array(
				'sConf' => '<?php $var = array(foo::bar);'
			),
		);
	}
}
