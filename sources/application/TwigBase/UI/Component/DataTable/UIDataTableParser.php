<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\TwigBase\UI\Component;


use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class UIDataTableParser extends AbstractTokenParser
{

	/**
	 * @inheritDoc
	 */
	public function parse(Token $token)
	{
		$iLineno = $token->getLine();
		$oStream = $this->parser->getStream();

		$sType = $oStream->expect(Token::NAME_TYPE)->getValue();

		$oParams = $this->parser->getExpressionParser()->parseExpression();

		$oStream->expect(Token::BLOCK_END_TYPE);

		return new UIDataTableNode($sType, $oParams, $iLineno, $this->getTag());
	}

	/**
	 * @inheritDoc
	 */
	public function getTag()
	{
		return 'UIDataTable';
	}
}