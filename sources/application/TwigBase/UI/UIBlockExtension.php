<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\TwigBase\UI;


use Combodo\iTop\Application\TwigBase\UI\Component\UIAlertParser;
use Combodo\iTop\Application\TwigBase\UI\Component\UIContentBlockParser;
use Combodo\iTop\Application\TwigBase\UI\Component\UIDataTableParser;
use Combodo\iTop\Application\TwigBase\UI\Component\UIFieldParser;
use Combodo\iTop\Application\TwigBase\UI\Component\UIFieldSetParser;
use Combodo\iTop\Application\TwigBase\UI\Component\UIFormParser;
use Combodo\iTop\Application\TwigBase\UI\Component\UIHtmlParser;
use Combodo\iTop\Application\TwigBase\UI\Component\UIInputParser;
use Combodo\iTop\Application\TwigBase\UI\Component\UITitleParser;
use Twig\Extension\AbstractExtension;

class UIBlockExtension extends AbstractExtension
{
	public function getTokenParsers()
	{
		return [
			new UIHtmlParser(),
			new UIContentBlockParser(),
			new UIFieldSetParser(),
			new UIFieldParser(),
			new UIAlertParser(),
			new UITitleParser(),
			new UIDataTableParser(),
			new UIFormParser(),
			new UIInputParser(),
		];
	}
}