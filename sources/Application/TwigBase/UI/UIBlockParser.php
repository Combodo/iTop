<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\TwigBase\UI;


use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

/**
 * Class UIBlockParser
 *
 * @package Combodo\iTop\Application\TwigBase\UI
 * @author  Eric Espie <eric.espie@combodo.com>
 * @since 3.0.0
 */
class UIBlockParser extends AbstractTokenParser
{
	/** @var string */
	protected $sTag;
	/** @var string */
	protected $sFactoryClass;
	/** @var bool */
	protected $bHasSubBlocks;

	/** @var string */
	protected $sBlockClassName;


	/**
	 * UIBlockParser constructor.
	 *
	 * @param string $sFactoryClass
	 */
	public function __construct(string $sFactoryClass)
	{
		$this->sFactoryClass = $sFactoryClass;
		$this->sTag = call_user_func([$sFactoryClass, 'GetTwigTagName']);
		$this->sBlockClassName = call_user_func([$sFactoryClass, 'GetUIBlockClassName']);
		$this->bHasSubBlocks = is_subclass_of($this->sBlockClassName, "Combodo\\iTop\\Application\\UI\\Base\\Layout\\UIContentBlock") || $this->sBlockClassName == "Combodo\\iTop\\Application\\UI\\Base\\Layout\\UIContentBlock";
	}


	/**
	 * @inheritDoc
	 */
	public function parse(Token $sToken)
	{
		$iLineno = $sToken->getLine();
		$oStream = $this->parser->getStream();

		$sType = $oStream->expect(Token::NAME_TYPE)->getValue();

		$oParams = $this->parser->getExpressionParser()->parseExpression();

		$oStream->expect(Token::BLOCK_END_TYPE);

		if ($this->bHasSubBlocks) {
			$oBody = $this->parser->subparse([$this, 'decideForEnd'], true);
			$oStream->expect(Token::BLOCK_END_TYPE);
		} else {
			$oBody = null;
		}

		return new UIBlockNode($this->sFactoryClass, $this->sBlockClassName, $sType, $oParams, $oBody, $iLineno, $this->getTag());
	}

	/**
	 * @inheritDoc
	 */
	public function getTag()
	{
		return $this->sTag;
	}

	public function decideForEnd(Token $sToken)
	{
		return $sToken->test('End'.$this->sTag);
	}
}