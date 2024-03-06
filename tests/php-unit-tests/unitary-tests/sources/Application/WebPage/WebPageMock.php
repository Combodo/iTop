<?php

/*
 * @copyright   Copyright (C) 2010-2024 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Test\UnitTest\Application\WebPage;


use Combodo\iTop\Application\WebPage\WebPage;

class WebPageMock extends WebPage
{
	public function __construct()
	{
		// Don't call parent construct as we don't want the ob_start() method to be called (it would mess with PHPUnit)
	}
}