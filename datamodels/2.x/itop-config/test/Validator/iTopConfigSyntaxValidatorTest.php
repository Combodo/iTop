<?php
/**
 * Created by Bruno DA SILVA, working for Combodo
 * Date: 31/12/2019
 * Time: 12:31
 */

namespace Combodo\iTop\Config\Test\Validator;

use Combodo\iTop\Config\Validator\iTopConfigAstValidator;
use Combodo\iTop\Config\Validator\iTopConfigSyntaxValidator;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use PhpParser\Node;
use PhpParser\PrettyPrinter\Standard;

class iTopConfigAstValidatorTest extends ItopTestCase
{

	public function setUp()
	{
		parent::setUp();

		require_once __DIR__.'/../../src/Validator/ConfigNodesVisitor.php';
		require_once __DIR__.'/../../src/Validator/iTopConfigSyntaxValidator.php';
	}


	/**
	 * @throws \Exception
	 * @doesNotPerformAssertions
	 */
	public function testValidCode()
	{
		$oiTopConfigValidator = new iTopConfigSyntaxValidator();
		$oiTopConfigValidator->validate("<?php \n echo 'foo'; ", false);
	}

	public function testThrowOnInvalidCode()
	{
		$oiTopConfigValidator = new iTopConfigSyntaxValidator();

		$this->expectException(\Exception::class);
		try{
			$oiTopConfigValidator->validate("<?php \n zef;zefzef \n zdadz = azdazd \n zerfgzaezerfgzef>", false);
		}catch (\Exception $e)
		{
			$this->assertStringStartsWith('PHP Parse error:  syntax error, unexpected \'zdadz\' (T_STRING)', $e->getMessage());
			throw $e;
		}
	}
}
