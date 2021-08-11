<?php
/**
 * Created by Bruno DA SILVA, working for Combodo
 * Date: 31/12/2019
 * Time: 12:29
 */

use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;

class iTopConfigParser
{

	/** @var \PhpParser\Node[] */
	private $aInitialNodes;

	/** @var \PhpParser\Node[] */
	private $aVisitedNodes;

	/** @var string|null  */
	private $oException = null;
	/**
	 * @var array
	 */
	private $aVarsMap;

	/**
	 * iTopConfigValidator constructor.
	 *
	 * @param $sConfig
	 * @param \PhpParser\Parser|null $oParser
	 *
	 * @throws \Exception
	 */
	public function __construct($sConfig)
	{
		$oParser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);

		$this->aVarsMap = array(
			'MySettings' => array(),
			'MyModuleSettings' => array(),
			'MyModules' => array(),
		);

		if ($sConfig !== null)
		{
			$this->BrowseFile($oParser, $sConfig);
		}
	}

	/**
	 * @return array
	 */
	public function GetVarsMap()
	{
		return $this->aVarsMap;
	}

	/**
	 * @param $arrayName
	 * @param $key
	 *
	 * @return array
	 */
	public function GetVarValue($arrayName, $key)
	{
		if (!array_key_exists($arrayName, $this->aVarsMap)){
			return array('found' => false);
		}
		$arrayValue = $this->aVarsMap[$arrayName];
		if (!array_key_exists($key, $arrayValue)){
			return array('found' => false);
		}
		return array('found' => true,
			'value' => $arrayValue[$key]);
	}

	/**
	 * @param \PhpParser\Parser $oParser
	 * @param $sConfig
	 *
	 * @return void
	 */
	private function BrowseFile(Parser $oParser, $sConfig)
	{
		$prettyPrinter = new Standard();

		try {
			$aNodes = $oParser->parse($sConfig);
		}
		catch (\Error $e) {
			$sMessage = Dict::Format('config-parse-error', $e->getMessage(), $e->getLine());
			$this->oException = new \Exception($sMessage, 0, $e);
		}

		foreach ($aNodes as $sKey => $oNode) {
			// With PhpParser 3 we had an Assign node at root
			// In PhpParser 4 the root node is now an Expression

			if (false === ($oNode instanceof \PhpParser\Node\Stmt\Expression)) {
				continue;
			}
			/** @var \PhpParser\Node\Stmt\Expression $oNode */

			if (false === ($oNode->expr instanceof Assign)) {
				continue;
			}
			/** @var Assign $oAssignation */
			$oAssignation = $oNode->expr;

			if (false === ($oAssignation->var instanceof Variable)) {
				continue;
			}
			if (false === ($oAssignation->expr instanceof PhpParser\Node\Expr\Array_)) {
				continue;
			}

			$sCurrentRootVar = $oAssignation->var->name;
			if (!array_key_exists($sCurrentRootVar, $this->aVarsMap)) {
				continue;
			}
			$aCurrentRootVarMap =& $this->aVarsMap[$sCurrentRootVar];

			foreach ($oAssignation->expr->items as $oItem) {
				$sValue = $prettyPrinter->prettyPrintExpr($oItem->value);
				$aCurrentRootVarMap[$oItem->key->value] = $sValue;
			}
		}
	}
}