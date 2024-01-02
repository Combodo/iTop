<?php

namespace Combodo\iTop\FormImplementation\Helper;

use Combodo\iTop\Controller\AbstractAppController;
use Combodo\iTop\FormSDK\Service\FormManager;
use DateInterval;
use DateTime;
use MetaModel;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class FormHelper
{
	/**
	 * Create a sample form factory for demo purpose.
	 *
	 * @param \Combodo\iTop\FormSDK\Service\FormManager $oFormManager
	 * @param \Symfony\Component\Routing\RouterInterface $oRouter
	 *
	 * @return \Combodo\iTop\FormSDK\Service\FormFactory
	 * @throws \ArchivedObjectException
	 * @throws \CoreException
	 */
	static public function CreateSampleFormFactory(FormManager $oFormManager, RouterInterface $oRouter)
	{
		// create a factory
		$oFormFactory = $oFormManager->CreateFactory();

		// add X person forms...
		for($i = 0 ; $i < 5 ; $i++){

			// retrieve person
			$oPerson = MetaModel::GetObject('Person', $i+1);

			// create object adapter
			$oObjectPlugin = $oFormFactory->CreateObjectAdapter($oPerson, true);
			$oObjectPlugin->AddAttribute('name');
			$oObjectPlugin->AddAttribute('mobile_phone');
		}

		// city - text
		$oFormFactory->AddTextField('city', [
			'label' => 'Ma ville',
			'help' => 'This is where you live',
			'constraints' => new Length(['min' => 3])
		], 'Autun');

		// tel - text with pattern
		$oFormFactory->AddTextField('tel', [
			'label' => 'Tel',
			'constraints' => new Regex(['pattern' => '/\+33\(\d\) \d\d \d\d \d\d \d\d/'], null, '+{33}(0) 00 00 00 00'),
			'required' => false
		], null);

		// birthday - date
		$oFormFactory->AddDateField('birthday', [
			'label' => 'Anniversaire',
			'widget' => 'single_text',
			'required' => false
		], new DateTime('1979/06/27'));

		// count - number
		$oFormFactory->AddNumberField('count', [
			'label' => 'Compteur',
			'required' => false
		], 10);

		// interval - duration
		$oFormFactory->AddDurationField('interval', [
			'label' => 'Fréquence',
			'input' => 'array',
			'with_minutes' => true,
			'with_seconds' => true,
			'with_weeks' => true,
			'with_days' => false,
			'attr' => [
				'class' => 'form_interval_horizontal'
			]
		], ['days' => '12', 'hours' => '13', 'years' => '10', 'months' => '6', 'minutes' => '0', 'seconds' => '0', 'weeks' => '3']);

		// ready
		$oFormFactory->AddSwitchField('notify', [
			'label' => 'Veuillez m\'avertir en cas de changement',
		], true);

		// blog - date
		$oFormFactory->AddAreaField('blog', [
			'label' => 'Blog',
			'required' => false
		], 'Your story');

		// language - select with static data
		$oFormFactory->AddSelectField('language', [
			'label' => 'Ma langue',
			'choices' => SelectDataProvider::GetApplicationLanguages()
		], 'FR FR');

		// dog - select with ajax API
		$oFormFactory->AddSelectAjaxField('dog', [
			'label' => 'Mon Chien',
			'placeholder' => 'Sélectionnez un chien',
			'required' => false,

		], [
			'url' => 'http://localhost' . $oRouter->generate('formSDK_ajax_select'),
			'query_parameter' => 'query',
			'value_field' => 'breed',
			'label_field' => 'breed',
			'search_field' => 'breed',
			'threshold' => 20,
			'max_items' => 1
		]);

		// friends - select with  OQL
		$oFormFactory->AddSelectOqlField('friends', [
			'label' => 'Ma personne',
			'required' => false
		], 'Person', 'SELECT Person', [], '', 20);

		// requests - select with  OQL
		$oFormFactory->AddSelectOqlField('requests', [
			'label' => 'Tickets',
			'required' => false
		], 'UserRequest', 'SELECT UserRequest', [], '', 20);

		// mode - select with static data
		$oFormFactory->AddSelectField('mode', [
			'label' => 'Mon mode',
			'choices' => SelectDataProvider::GetModes(),
			'expanded' => true,
			'multiple' => false,
			'label_attr' => [
				'class' => 'radio-inline'
			]
		], '1');

		// options - select with static data
		$oFormFactory->AddSelectField('option', [
			'label' => 'Mes options',
			'choices' => SelectDataProvider::GetOptions(),
			'expanded' => true,
			'multiple' => true,
			'label_attr' => [
				'class' => 'checkbox-inline'
			]
		], ['0', '2','4']);

		return $oFormFactory;
	}

}