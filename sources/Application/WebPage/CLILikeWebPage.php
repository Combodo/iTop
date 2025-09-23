<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\WebPage;

class CLILikeWebPage extends WebPage
{
	const DEFAULT_PAGE_TEMPLATE_REL_PATH = 'pages/backoffice/clilikewebpage/layout';
	public function add_comment($sText)
	{
		$this->add('#'.$sText."<br/>\n");
	}
}
