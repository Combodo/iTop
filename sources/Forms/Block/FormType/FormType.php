<?php

namespace Combodo\iTop\Forms\Block\FormType;

use Combodo\iTop\Forms\Block\Base\FormBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class FormType  extends AbstractType
{
	public function getParent(): string
	{
		return \Symfony\Component\Form\Extension\Core\Type\FormType::class;
	}


	public function buildView(FormView $view, FormInterface $form, array $options)
	{
		parent::buildView($view, $form, $options);

		/** @var FormBlock $oBlock */
		$oBlock = $options['form_block'];

		$aData = [];
		foreach($oBlock->GetChildren() as $oChild) {

			if($oChild->IsAdded()){
				$oFormChild = $form->get($oChild->GetName());
				$aData[] = [
					'name' => $oChild->GetName(),
					'added' => $oChild->IsAdded(),
					'id' => $oChild->GetIdentifier(),
					'path' => $oFormChild->getPropertyPath(),
				];
			}
			else{
				$aData[] = [
					'name' => $oChild->GetName(),
					'added' => $oChild->IsAdded(),
					'id' => $oChild->GetIdentifier(),
				];
			}

		}
		$view->vars['blocks'] = $aData;
	}

}