<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\TurboForm;

use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use Symfony\Component\Form\FormView;

/**
 * Class TurboFormUIBlockFactory
 *
 * @api
 * @since 3.3.0
 * @package UIBlockAPI
 */
class TurboFormUIBlockFactory  extends AbstractUIBlockFactory
{
	/** @inheritDoc */
	public const TWIG_TAG_NAME = 'UITurboForm';
	/** @inheritDoc */
	public const UI_BLOCK_CLASS_NAME = TurboForm::class;

	/**
	 * @api
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\TurboForm\TurboForm An HTML form in which you can add UIBlocks
	 */
	public static function MakeStandard(FormView $oFormView, string $sId = null): TurboForm
	{
		return new TurboForm($oFormView, $sId);
	}
}