<?php

/*
 * @copyright   Copyright (C) 2010-2025 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Application\UI\Base\Component\TurboForm;

use Combodo\iTop\Application\UI\Base\AbstractUIBlockFactory;
use Combodo\iTop\Forms\Block\FormBlockService;
use Combodo\iTop\Forms\Controller\FormsController;
use Symfony\Component\Form\FormView;
use utils;

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
	 *
	 * @param \Symfony\Component\Form\FormView $oFormView
	 * @param string|null $sAction
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\TurboForm\TurboForm An HTML form in which you can add UIBlocks
	 */
	public static function MakeStandard(FormView $oFormView, string $sAction = null, string $sId = null): TurboForm
	{
		$oTurboForm = new TurboForm($oFormView, $sId);
		if (!is_null($sAction)) {
			$oTurboForm->setAction($sAction);
		}

		return $oTurboForm;
	}

	/**
	 * For dashlet configuration forms
	 *
	 * @api
	 *
	 * @param string $sDashletId
	 * @param string|null $sId
	 *
	 * @return \Combodo\iTop\Application\UI\Base\Component\TurboForm\TurboForm
	 * @throws \Combodo\iTop\Forms\Block\FormBlockException
	 */
	public static function MakeForDashletConfiguration(string $sDashletId, array $aData = [], string $sId = null): TurboForm
	{
		$oBlockForm = FormBlockService::GetInstance()->GetFormBlockById($sDashletId, 'Dashlet');
		$oController = new FormsController();
		$oBuilder = $oController->GetFormBuilder($oBlockForm, $aData);
		$oForm = $oBuilder->getForm();

		$oTurboForm = new TurboForm($oForm->createView(), $sId);
		$oTurboForm->SetAction(utils::GetAbsoluteUrlAppRoot().'pages/UI.php?route=forms.dashlet_configuration&dashlet_code='.urlencode($sDashletId));

		return $oTurboForm;
	}
}
