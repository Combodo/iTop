<?php

namespace Combodo\iTop\FormSDK\Controller;

use Combodo\iTop\Controller\AbstractAppController;
use Combodo\iTop\FormSDK\Service\FormManager;
use Exception;
use MetaModel;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Length;
use utils;

class TestController extends AbstractAppController
{

	#[Route('/formSDK/test_Form', name: 'formSDK_test_form')]
	public function form(Request $oRequest, FormManager $oFormManager): Response
	{
		// retrieve DB Object
		try {
			$oPerson = MetaModel::GetObject('Person', 1);
		}
		catch (Exception $e) {
			throw $this->createNotFoundException('unable to load object Person 1');
		}

		// build the form
		$oFormFactory = $oFormManager->CreateFactory();
		// object plugin
		$oObjectPlugin = $oFormFactory->CreateObjectPlugin($oPerson, false);
		$oObjectPlugin->AddAttribute('name');
		$oObjectPlugin->AddAttribute('mobile_phone');
		// others data
		$oFormFactory->AddText('data1', ['label' => 'Ma ville',  'constraints' => new Length(['min' => 3])], 'Autun');
		$oFormFactory->AddText('data2', ['label' => 'Pays'], 'FRANCE');

		$aSelectOptions = [
			'ajax_url' => $this->generateUrl('formSDK_ajax_select'),
			'valueField' => 'breed',
            'labelField' => 'breed',
            'searchField' => 'breed',
			'preload' => true,
		];
		$oFormFactory->AddSelect('data3', [
			'label' => 'Mon Chien',
			'placeholder' => 'Sélectionnez un chien',
			'attr' => [
				'data-widget' => 'Select',
				'data-widget-options' => json_encode($aSelectOptions)
			],
//			'choice_loader' => new CallbackChoiceLoader(function(): array {
//
//				$curl_data = utils::DoPostRequest('http://localhost/' . $this->generateUrl('formSDK_ajax_select'), []);
//				$response_data = json_decode($curl_data);
//
//				if(count($response_data->items) > 2) return [];
//
//				$result = [];
//				foreach ($response_data->items as $e) {
//					$result[$e->breed] = $e->breed;
//				}
//
//				return $result;
//			}),
		], null);

		// get the form
		$oForm = $oFormFactory->GetForm();

		// handle request
		$oForm->handleRequest($oRequest);

		// submitted and valid
		if ($oForm->isSubmitted() && $oForm->isValid()) {

			// retrieve form data
			$data = $oForm->getData();

			// ... perform some action, such as saving the data to the database

			return $this->redirectToRoute('app_success');
		}

		return $this->render('formSDK/form.html.twig', [
			'form' => $oForm->createView(),
			'theme' => 'formSDK/themes/portal.html.twig'
		]);

	}


	#[Route('/formSDK/ajax_select', name: 'formSDK_ajax_select')]
	public function ajax(Request $oRequest): Response
	{
		$oJson = file_get_contents('sources/FormSDK/Resources/dogs.json');
		$aDogs = json_decode($oJson, true);

		$sQuery = $oRequest->request->get('query');

		return new JsonResponse([
			"items" => $aDogs['dogBreeds']
		]);
	}

	#[Route('/success', name: 'app_success')]
	public function success(): Response
	{
		return $this->render('formSDK/success.html.twig');
	}
}