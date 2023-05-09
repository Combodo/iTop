<?php
/**
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


class CLILikeWebPage extends WebPage
{
	const DEFAULT_PAGE_TEMPLATE_REL_PATH = 'pages/backoffice/clilikewebpage/layout';
	public function add_comment($sText)
	{
		$this->add('#'.$sText."<br/>\n");
	}
}
