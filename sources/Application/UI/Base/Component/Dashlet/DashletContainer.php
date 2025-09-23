<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Dashlet;


use Combodo\iTop\Application\UI\Base\Layout\UIContentBlock;

class DashletContainer extends UIContentBlock
{
	public const BLOCK_CODE = 'ibo-dashlet';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/layouts/content-block/layout';

	public function __construct(string $sId = null, array $aContainerClasses = [])
	{
		parent::__construct($sId, $aContainerClasses);

		$this->AddDataAttribute('role', static::BLOCK_CODE);
	}
}