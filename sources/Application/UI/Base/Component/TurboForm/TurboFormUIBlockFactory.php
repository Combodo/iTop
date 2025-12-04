<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\TurboForm;

use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use Combodo\iTop\Forms\Block\FormBlockService;
use Combodo\iTop\Forms\Compiler\FormsCompiler;
use Combodo\iTop\Forms\Compiler\FormsController;
use Symfony\Component\Form\FormView;

/**
 * Class TurboFormUIBlockFactory
 *
 * @api
 * @since 3.3.0
 * @package UIBlockAPI
 */
class TurboFormUIBlockFactory extends AbstractUIBlockFactory
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

	/**
	 * @param string $sDashletId
	 * @param string|null $sAction
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\TurboForm\TurboForm
	 */
	public static function MakeForDashlet(string $sDashletId, string $sAction = null, string $sId = null): TurboForm
	{
		$oBlockForm = FormBlockService::GetInstance()->GetFormBlockById($sDashletId);
		$oController = new FormsController();
		$oBuilder = $oController->GetFormBuilder($oBlockForm);
		$oForm = $oBuilder->getForm();

		$oTurboForm = new TurboForm($oForm->createView(), $sId);
		if (!is_null($sAction)) {
			$oTurboForm->SetAction($sAction);
		}

		return $oTurboForm;
	}
}
