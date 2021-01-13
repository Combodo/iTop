<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\TwigBase\UI\Component;


use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class UIContentBlockParser extends AbstractTokenParser
{

	/**
	 * @inheritDoc
	 */
	public function parse(Token $token)
	{
		$iLineno = $token->getLine();
		$oStream = $this->parser->getStream();

		$sName = null;
		if ($oStream->test(Token::STRING_TYPE)) {
			$sName = $oStream->expect(Token::STRING_TYPE)->getValue();
		}
		$sContainerClass = '';
		if ($oStream->test(Token::STRING_TYPE)) {
			$sContainerClass = $oStream->expect(Token::STRING_TYPE)->getValue();
		}
		$oStream->expect(Token::BLOCK_END_TYPE);

		$oBody = $this->parser->subparse([$this, 'decideForEnd'], true);
		$oStream->expect(Token::BLOCK_END_TYPE);

		return new UIContentBlockNode($sName, $sContainerClass, $oBody, $iLineno, $this->getTag());
	}

	/**
	 * @inheritDoc
	 */
	public function getTag()
	{
		return 'UIContentBlock';
	}

	public function decideForEnd(Token $token)
	{
		return $token->test('EndUIContentBlock');
	}
}