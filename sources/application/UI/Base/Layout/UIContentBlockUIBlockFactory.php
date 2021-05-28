<?php
/**
 * @copyright   Copyright (C) 2010-2021 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */


namespace Combodo\iTop\Application\UI\Base\Layout;


use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use Combodo\iTop\Application\UI\Base\Component\Html\Html;

class UIContentBlockUIBlockFactory extends AbstractUIBlockFactory
{
	public const TWIG_TAG_NAME = 'UIContentBlock';
	public const UI_BLOCK_CLASS_NAME = UIContentBlock::class;

	public static function MakeStandard(string $sId = null, array $aContainerClasses = [])
	{
		return new UIContentBlock($sId, $aContainerClasses);
	}


	/**
	 * Used to display a block of code like <pre> but allows line break.
	 * The \n are replaced by <br>
	 *
	 * @param string $sCode
	 * @param string|null $sId
	 */
	public static function MakeForCode(string $sCode, string $sId = null)
	{
		$oCode = new UIContentBlock($sId, ['ibo-is-code']);
		$sCode = str_replace("\n", '<br>', $sCode);
		$oCode->AddSubBlock(new Html($sCode));

		return $oCode;
	}

}