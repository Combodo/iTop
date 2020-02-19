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

		require_once __DIR__.'/../../../env-production/itop-config/src/Validator/ConfigNodesVisitor.php';
		require_once __DIR__.'/../../../env-production/itop-config/src/Validator/iTopConfigSyntaxValidator.php';
	}


	/**
	 * @throws \Exception
	 * @doesNotPerformAssertions
	 */
	public function testValidCode()
	{
		$oiTopConfigValidator = new iTopConfigSyntaxValidator();
		$oiTopConfigValidator->Validate("<?php \n echo 'foo'; ");
	}

	public function testThrowOnInvalidCode()
	{
		$oiTopConfigValidator = new iTopConfigSyntaxValidator();

		$this->expectException(\Exception::class);
		try{
			$oiTopConfigValidator->Validate("<?php \n zef;zefzef \n zdadz = azdazd \n zerfgzaezerfgzef>");
		}catch (\Exception $e)
		{
			$this->assertStringStartsWith('Error in configuration: syntax error, unexpected \'zdadz\' (T_STRING)', $e->getMessage());
			throw $e;
		}
	}
}
