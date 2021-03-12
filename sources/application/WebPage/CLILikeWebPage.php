<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


class CLILikeWebPage extends WebPage
{
	public function add_comment($sText)
	{
		$this->add('#'.$sText."<br/>\n");
	}
}
