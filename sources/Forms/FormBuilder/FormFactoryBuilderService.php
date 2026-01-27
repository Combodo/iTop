<?php

/*
 * @copyright   Copyright (C) 2010-2026 Combodo SAS
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Forms\FormBuilder;

use Combodo\iTop\Forms\Block\AbstractFormBlock;
use Combodo\iTop\Forms\Forms;
use MetaModel;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryBuilderInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManager;

/**
 * Factory form builder (from Symfony form component @link https://symfony.com/doc/current/components/form.html)
 *
 * @since 3.3.0
 * @api
 */
class FormFactoryBuilderService
{
	private CsrfTokenManager $oCsrfTokenManager;
	private FormFactoryBuilderInterface $oFormFactoryBuilder;

	public function __construct()
	{
		// Initialize the CSRF token manager
		$this->oCsrfTokenManager = MetaModel::GetService('CsrfTokenManager');

		// Initialize the form factory builder to handle Request objects
		$this->oFormFactoryBuilder = Forms::createFormFactoryBuilder()
			->addExtension(new HttpFoundationExtension())
			->addExtension(new CsrfExtension($this->oCsrfTokenManager));
	}

	/**
	 * Get a form builder.
	 * This form builder can be used to create a form or to add fields to an existing form.
	 *
	 * @api
	 *
	 * @param \Combodo\iTop\Forms\Block\AbstractFormBlock $oFormBlock
	 * @param mixed|null $data
	 *
	 * @return \Symfony\Component\Form\FormBuilderInterface
	 */
	public function GetFormBuilder(AbstractFormBlock $oFormBlock, mixed $data = null): FormBuilderInterface
	{
		return $this->oFormFactoryBuilder->getFormFactory()->createNamedBuilder($oFormBlock->GetName(), $oFormBlock->GetFormType(), $data, $oFormBlock->GetOptions());
	}
}
