<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Component\Form;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;

class FormUIBlockFactory extends AbstractUIBlockFactory
{
	public const UI_BLOCK_CLASS_NAME = "Combodo\\iTop\\Application\\UI\\Base\\Component\\Form\\Form";
	public const TWIG_TAG_NAME = 'UIForm';

	public static function MakeStandard(string $sId = null)
	{
		return new Form($sId);
	}
}