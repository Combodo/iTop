<?php
/**
 * @copyright   Copyright (C) 2010-2024 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\Input;


/**
 * @package Combodo\iTop\Application\UI\Base\Component\Input
 * @since 3.2.0
 */
class Toggler extends Input {
	
	// Overloaded constants
	public const BLOCK_CODE = 'ibo-toggler';
	public const DEFAULT_HTML_TEMPLATE_REL_PATH = 'base/components/input/input-toggler';
	public const DEFAULT_JS_ON_READY_TEMPLATE_REL_PATH = 'base/components/input/input-toggler';


	public function __construct(?string $sId = null)
	{
		parent::__construct($sId);
		$this->SetType('checkbox');
	}
	
	public function SetIsToggled(bool $bIsToggled): static
	{
		return $this->SetIsChecked($bIsToggled);
	}

	public function IsToggled(): bool
	{
		return $this->IsChecked();
	}
}