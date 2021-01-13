<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\TwigBase\UI\Component;


use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class UIHtmlParser extends AbstractTokenParser
{

	/**
	 * @inheritDoc
	 */
	public function parse(Token $token)
	{
		$iLineno = $token->getLine();
		$oStream = $this->parser->getStream();
		$oStream->expect(Token::BLOCK_END_TYPE);

		$oBody = $this->parser->subparse([$this, 'decideForEnd'], true);
		$oStream->expect(Token::BLOCK_END_TYPE);

		return new UIHtmlNode($oBody, $iLineno, $this->getTag());
	}

	/**
	 * @inheritDoc
	 */
	public function getTag()
	{
		return 'UIHtml';
	}

	public function decideForEnd(Token $token)
	{
		return $token->test('EndUIHtml');
	}
}