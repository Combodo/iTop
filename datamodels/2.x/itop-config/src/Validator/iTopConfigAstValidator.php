<?php
/**
 * Created by Bruno DA SILVA, working for Combodo
 * Date: 31/12/2019
 * Time: 12:29
 */

namespace Combodo\iTop\Config\Validator;


use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;

class iTopConfigAstValidator
{
	/**
	 * validate.
	 *
	 * @param $sConfig
	 * @param \PhpParser\Parser|null $oParser
	 *
	 * @throws \Exception
	 */
	public function Validate($sConfig)
	{
		$oParser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);

		$oNodeVisitor = new ConfigNodesVisitor();

		try {
			$aInitialNodes = $oParser->parse($sConfig);
		} catch (\Error $e) {
			$sMessage = 'Invalid configuration: '. \Dict::Format('config-parse-error', $e->getMessage(), $e->getLine());
			throw new \Exception($sMessage, 0, $e);
		}catch (\Exception $e) {
			$sMessage = 'Invalid configuration: '. \Dict::Format('config-parse-error', $e->getMessage(), $e->getLine());
			throw new \Exception($sMessage, 0, $e);
		}

		$oTraverser = new NodeTraverser();
		$oTraverser->addVisitor($oNodeVisitor);
		$oTraverser->traverse($aInitialNodes);
	}
}