<?php
/*
 * @copyright   Copyright (C) 2010-2025 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

namespace Combodo\iTop\Test\UnitTest\Application\FormType\Base;

use Combodo\iTop\FormType\Orm\AttCodeGroupByType;
use Combodo\iTop\Test\UnitTest\ItopTestCase;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Forms;

class TextTypeTest extends iTopTestCase
{
	public function GetFormBuilder(string $type = FormType::class, mixed $data = null, array $options = []): FormBuilderInterface
	{
		$oFormFactory = Forms::createFormFactoryBuilder()
			->addExtension(new HttpFoundationExtension())
			->getFormFactory();
		return $oFormFactory->createBuilder($type, $data,$options);
	}

	public function GetForm(string $type = FormType::class, mixed $data = null, array $options = []): FormInterface
	{
		return $this->GetFormBuilder($type, $data,$options)->getForm();
	}

	public function testTextType()
	{
		$oFormView = $this->GetForm(AttCodeGroupByType::class)->createView();
		return;
	}
}
