<?php

/**
 * Created by Bruno DA SILVA, working for Combodo
 * Date: 31/12/2019
 * Time: 12:31
 */

namespace Combodo\iTop\Test\UnitTest\Module\iTopConfig\Validator;

use Combodo\iTop\Config\Validator\iTopConfigAstValidator;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use PhpParser\Node;
use PhpParser\PrettyPrinter\Standard;

class iTopConfigAstValidatorTest extends ItopTestCase
{
	public function setUp(): void
	{
		parent::setUp();

		$this->RequireOnceItopFile('env-production/itop-config/src/Validator/ConfigNodesVisitor.php');
		$this->RequireOnceItopFile('env-production/itop-config/src/Validator/iTopConfigAstValidator.php');
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
		try {
			$oiTopConfigValidator->Validate($sConf);
		} catch (\Exception $e) {
			$this->assertStringStartsWith('Invalid configuration:', $e->getMessage());
			throw $e;
		}
	}

	public function InvalidDataProvider()
	{
		return [
			'invalid PHP' => [
				'sConf' => '<?php fiction Method(){}',
			],
			'function call' => [
				'sConf' => '<?php FunctionCall();',
			],
			'function declaration' => [
				'sConf' => '<?php function foo() {};',
			],
			'class instantiation' => [
				'sConf' => '<?php new Class {};',
			],
			'Class declaration' => [
				'sConf' => '<?php class foo {};',
			],
			'echo' => [
				'sConf' => '<?php echo "toto"; ?>',
			],
		];
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

		$oiTopConfigValidator->Validate($sConf);
	}

	public function ValidDataProvider()
	{
		return [
			'simple code' => [
				'sConf' => '<?php $var = array("toto"); ?>',
			],
			'class constant' => [
				'sConf' => '<?php $var = array(foo::bar);',
			],
		];
	}
}
